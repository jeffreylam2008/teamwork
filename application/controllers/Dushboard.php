<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dushboard extends CI_Controller 
{
	var $_inv_header_param = [];	
	var $_token = "";
	var $_param = "";
	public function __construct()
	{
		parent::__construct();
		$this->load->library("Component_Master");
		if(isset($this->session->userdata['master']))
		{
		   	// dummy data
			// $this->session->sess_destroy();
			// echo "<pre>";
			// var_dump(($_SESSION['master']));
			// echo "</pre>";
			// call token from session
			if(!empty($this->session->userdata['login']))
			{
				$this->_token = $this->session->userdata['login']['token'];
			}
			// API call
			$this->load->library("Component_Login",[$this->_token, "products/items"]);

			// login session
			if(!empty($this->component_login->CheckToken()))
			{
				$this->_username = $this->session->userdata['login']['profile']['username'];
				// fatch employee API
				$_employees = $this->component_master->SearchByKey("employees","username",$this->_username);

				// sidebar session
				$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
				// $this->session->sess_destroy();
				// unset($_SESSION);
				
				// header data
				$this->_inv_header_param["topNav"] = [
					"isLogin" => true,
					"username" => $_employees['username'],
					"employee_code" => $_employees['username'],
					"shop_code" => $_employees['default_shopcode'],
					"today" => date("Y-m-d")
				];
				// API Call: fatch sidebar API
				$_nav_list = $this->session->userdata['master']['menu']['query'];
				$this->component_sidemenu->SetConfig("nav_list", $_nav_list);
				$this->component_sidemenu->SetConfig("active", $this->_param);
				$this->component_sidemenu->Proccess();
				// echo "<pre>";
				// var_dump( $this->component_sidemenu->GetConfig("slug"));
				// echo "</pre>";
				// load header view
				$this->load->view('header',[
					'title'=>'Dushboard',
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
		else
		{
			redirect(base_url("master"),"refresh");
		}
	}
	public function index()
	{
		$this->load->view('dushboard-view');
		$this->load->view('footer');
	}
}
