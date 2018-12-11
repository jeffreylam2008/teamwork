<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller 
{
	var $_inv_header_param = [];
	public function __construct()
	{
		parent::__construct();
	// echo "<pre>";
	// var_dump($_SESSION);
	// echo "</pre>";
		// dummy data
		
		$username = "iamadmin";

		// sidebar session
		$_param = $this->router->fetch_class()."/".$this->router->fetch_method();
	
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
			"today" => date("Y-m-d"),
			"prefix" => "QTA"
		];
		// fatch side bar API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
		$this->component_api->CallGet();
		$_nav_list = json_decode($this->component_api->GetConfig("result"), true);
		$this->component_sidemenu->SetConfig("nav_list", $_nav_list);
		$this->component_sidemenu->SetConfig("active", $_param);
		$this->component_sidemenu->Proccess();

		
		// render the view
		$this->load->view('header',[
			'title'=>'Quotations',
			'sideNav_view' => $this->load->view('side-nav', [
				"sideNav"=>$this->component_sidemenu->GetConfig("nav_finished_list"),
				"path"=>$this->component_sidemenu->GetConfig("path"),
				"param"=> $_param
			], TRUE), 
			'topNav_view' => $this->load->view('top-nav', ["topNav" => $this->_inv_header_param["topNav"]], TRUE)
		]);
    }
    public function index()
    {
        $this->load->view('payments/payments-view');
    }
}