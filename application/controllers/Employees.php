<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		// dummy data

		$username = "iamadmin";
		// fatch employee API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employee/".$username);
		$this->component_api->CallGet();
		$_employee = json_decode($this->component_api->GetConfig("result"),true);
		//var_dump($_employee);
		$this->_inv_header_param["topNav"] = [
			"isLogin" => true,
			"username" => "",
			"employee_code" => "110022",
			"shop_code" => "0012",
			"today" => date("Y-m-d")
		];
		// fatch side bar API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
		$this->component_api->CallGet();
		$_nav_list = json_decode($this->component_api->GetConfig("result"), true);
		$this->component_sidemenu->SetConfig("nav_list", $_nav_list);
		$this->component_sidemenu->Proccess();

		// load header view
		$this->load->view('header',[
			'title'=>'Employee',
			'sideNav_view' => $this->load->view('side-nav', ["sideNav"=>$this->component_sidemenu->GetConfig("nav_finished_list")], TRUE), 
			'topNav_view' => $this->load->view('top-nav', ["topNav" => $this->_inv_header_param["topNav"]], TRUE)
		]);
		// load breadcrumb
		//$this->load->view('breadcrumb');
	}
	public function index()
	{
		// load shops view
		$this->load->view('employees/employees-view');
		$this->load->view('footer');
	}
}
