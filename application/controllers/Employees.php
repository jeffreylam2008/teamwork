<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_token = "";
	var $_param = "";
	var $_user_auth = ['create' => false, 'edit' => false, 'delete' => false];
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
			
			switch($this->_param)
			{
				case "employees/edit":
					$this->_param = "employees/index";
				break;
				case "employees/delete":
					$this->_param = "employees/index";
				break;
			}
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
			$this->_user_auth = ['create' => true, 'edit' => true, 'delete' => true];
		}
		else
		{
			redirect(base_url("login?url=".urlencode($this->component_login->GetRedirectURL())),"refresh");
		}
	}
	public function index($_page = 1)
	{
		// variable initial
		$_default_per_page = 50;
		$_API_EMPLOYEES = [];

		// set user data
		$this->session->set_userdata('page',$_page);

		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employees/");
		$this->component_api->CallGet();
		$_API_EMPLOYEES = json_decode($this->component_api->GetConfig("result"),true);
		$_API_EMPLOYEES = $_API_EMPLOYEES['query'];

		
		// echo "<pre>";
		// var_dump($_API_EMPLOYEES);
		// echo "</pre>";

		// API data usage
		if(!empty($_API_EMPLOYEES))
		{
			
			// load function bar view
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => $this->_user_auth['create'], "extra" => "data-toggle='modal' data-target='#modal01'"]
				]
			]);

			// load main view
			$this->load->view('/employees/employees-view', [
				"edit_url" => base_url("/administration/employees/edit/"),
				"del_url" => base_url(""),
				'data' => $_API_EMPLOYEES,
				"user_auth" => $this->_user_auth,
				"default_per_page" => $_default_per_page,
				"page" => $_page
			]);
			$this->load->view("/employees/employees-create-view",[
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/administration/employees/page/'.$_page), "style" => "", "show" => true],
						["name" => "Reset", "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
					 ]
				],true),
				"save_url" => base_url("/administration/employees/save"),
			]);
			$this->load->view('footer');
		}
	}
	/**
	 * Edit employee configure 
	 * 
	 */
	public function edit($_employee_code)
	{
		// variable initial
		$_API_EMPLOYEES = [];
		$_page = 1;
		$_data = [];
		if(!empty($_employee_code))
		{
			//API call here
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employees/code/".$_employee_code);
			$this->component_api->CallGet();
			$_API_EMPLOYEES = json_decode($this->component_api->GetConfig("result"),true);
			$_API_EMPLOYEES = $_API_EMPLOYEES['query'];
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
			$this->component_api->CallGet();
			$_API_SHOPS = json_decode($this->component_api->GetConfig("result"),true);
			$_API_SHOPS = $_API_SHOPS['query'];
		
			$_data["employees"] = $_API_EMPLOYEES;
			$_data['shops'] = $_API_SHOPS;

			// function bar here
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/administration/employees/page/'.$_page), "style" => "", "show" => true],
					["name" => "Save", "type"=>"button", "id" => "Save", "url"=>base_url('/administration/employees/save/'.$_employee_code), "style" => "btn btn-primary", "show" => true],
				]
			]);
			// load view here
			$this->load->view('/employees/employees-edit-view', [
				"save_url" => base_url("administration/employees/edit/save/".$_employee_code),
				"data" => $_data
			]);
		}
	}
	/**
	 * save employees configure setting
	 *
	 */
	public function save()
	{
		echo "employee save";
	}
}
