<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_token = "";
	var $_param = "";
	public function __construct()
	{
		parent::__construct();
		
		// dummy data
		if(!empty($this->session->userdata['login']))
		{
			$this->_token = $this->session->userdata['login']['token'];
		}
		
		$this->load->library("Component_Login",[$this->_token, "customers"]);

		// login session
		if(!empty($this->component_login->CheckToken()))
		{
			$this->_username = $this->session->userdata['login']['profile']['username'];

			$_param = $this->router->fetch_class()."/".$this->router->fetch_method();

			// fatch master
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employees/".$this->_username);
			$this->component_api->CallGet();
			$_API_EMP = json_decode($this->component_api->GetConfig("result"), true);
			$_API_EMP = $_API_EMP['query'];

			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();

			// header data
			$this->_inv_header_param["topNav"] = [
				"isLogin" => true,
				"username" => $_API_EMP['username'],
				"employee_code" => $_API_EMP['employee_code'],
				"shop_code" => $_API_EMP['default_shopcode'],
				"today" => date("Y-m-d")
			];

			// Call API here
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
			$this->component_api->CallGet();
			$_API_MENU = json_decode($this->component_api->GetConfig("result"), true);
			$_API_MENU = $_API_MENU['query'];
			$this->component_sidemenu->SetConfig("nav_list", $_API_MENU);
			$this->component_sidemenu->SetConfig("active", $this->_param);
			$this->component_sidemenu->Proccess();


			// load header view
			$this->load->view('header',[
				'title'=>'Customers',
				'sideNav_view' => $this->load->view('side-nav', [
					"sideNav"=>$this->component_sidemenu->GetConfig("nav_finished_list"),
					"path"=>$this->component_sidemenu->GetConfig("path"),
					"param"=> $this->_param
				], TRUE), 
				'topNav_view' => $this->load->view('top-nav', [
					"topNav" => $this->_inv_header_param["topNav"]
				], TRUE)
			]);
		}
		else
		{
			redirect(base_url("login?url=".urlencode($this->component_login->GetRedirectURL())),"refresh");
		}
	}
	public function index()
	{
		// load shops view
		$this->load->view('employees/employees-view');
		$this->load->view('footer');
	}
}
