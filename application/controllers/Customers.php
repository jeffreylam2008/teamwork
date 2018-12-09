<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		// dummy data
		
		$username = "iamadmin";
		// read URI
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
			"today" => date("Y-m-d")
		];
		// Call API here
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
		$this->component_api->CallGet();
		$_nav_list = json_decode($this->component_api->GetConfig("result"), true);
		$this->component_sidemenu->SetConfig("nav_list", $_nav_list);
		$this->component_sidemenu->SetConfig("active", $_param);
		$this->component_sidemenu->Proccess();

		// load header view
		$this->load->view('header',[
			'title'=>'Shop',
			'sideNav_view' => $this->load->view('side-nav', [
				"sideNav"=>$this->component_sidemenu->GetConfig("nav_finished_list"),
				"path"=>$this->component_sidemenu->GetConfig("path"),
				"param"=> $_param
			], TRUE), 
			'topNav_view' => $this->load->view('top-nav', [
				"topNav" => $this->_inv_header_param["topNav"]
			], TRUE)
		]);
		// load breadcrumb
		//$this->load->view('breadcrumb');
	}
	public function cuslist($page="")
	{
		$_default_per_page = 50;
		$data = [];

		// Call API here
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);

		echo "<pre>";
		echo var_dump($_data);
		echo "</pre>";
		// load function bar view
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=>"", "style" => "", "show" => true, "extra" => ""]
			]
		]);

		// load main view
		$this->load->view('customers/customers-list-view', [
			'data' => $_data, 
			"url" => base_url("customers/edit/"),
			"default_per_page" => $_default_per_page,
			"page" => $page
		]);
		$this->load->view('footer');
	}
}
