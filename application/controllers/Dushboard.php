<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dushboard extends CI_Controller 
{
	var $_inv_header_param = [];
	public function __construct()
	{
		parent::__construct();
		
		// dummy data

		$_username = "iamadmin";

		var_dump($_SESSION);
		$_profile = $this->session->userdata('profile');
		$_token = $_profile['token'];

		if(!empty($_token))
		{
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/login/".$_token);
			$this->component_api->CallGet();
			$_employee = json_decode($this->component_api->GetConfig("result"),true);


			// sidebar session
			$_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			// $this->session->sess_destroy();
			// unset($_SESSION);
			

		
			
			// check token API
			
			
			$this->_inv_header_param["topNav"] = [
				"isLogin" => true,
				"username" => $_username,
				"employee_code" => "110022",
				"shop_code" => "0012",
				"today" => date("Y-m-d")
			];
			// fatch side bar API
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
			$this->component_api->CallGet();
			$_nav_list = json_decode($this->component_api->GetConfig("result"), true);
			$this->component_sidemenu->SetConfig("nav_list", $_nav_list);
			$this->component_sidemenu->SetConfig("active", $_param);
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
					"param"=> $_param
				], TRUE), 
				'topNav_view' => $this->load->view('top-nav', [
					"topNav" => $this->_inv_header_param["topNav"]
				], TRUE)
			]);
		}
		else
		{
			redirect(base_url("login?url=".urlencode(base_url("dushboard"))),"refresh");
		}
	}
	public function index()
	{
		$this->load->view('dushboard-view');
		$this->load->view('footer');
	}
}
