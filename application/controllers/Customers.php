<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_token = "";
	var $_param = "";
	var $_customers = [];
	public function __construct()
	{
		parent::__construct();
		$this->load->library("Component_Master");
		if(isset($this->session->userdata['master']))
		{
			// $this->session->sess_destroy();
			// dummy data
			// echo "<pre>";
			// var_dump(array_keys($_SESSION['master']));
			// echo "</pre>";
			// call token from session
			if(!empty($this->session->userdata['login']))
			{
				$this->_token = $this->session->userdata['login']['token'];
			}
			// API call
			$this->load->library("Component_Login",[$this->_token, "customers"]);

			// login session
			if(!empty($this->component_login->CheckToken()))
			{
				$this->_username = $this->session->userdata['login']['profile']['username'];
				// fatch master
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employees/".$this->_username);
				$this->component_api->CallGet();
				$_API_EMP = json_decode($this->component_api->GetConfig("result"), true);
				$_API_EMP = $_API_EMP['query'];

				$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
				$this->component_api->CallGet();
				$_API_CUSTOMERS = json_decode($this->component_api->GetConfig("result"), true);
				$this->_customers = $_API_CUSTOMERS['query'];
		

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
				$_nav_list = $this->session->userdata['master']['menu']['query'];
				$this->component_sidemenu->SetConfig("nav_list", $_nav_list);
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
		else
		{
			redirect(base_url("master"),"refresh");
		}

	}
	public function index($page = "")
	{
		// variable initial
		$_default_per_page = 50;
		$data = [];

		// set user data
		$this->session->set_userdata('page',$page);

		// Call API here
		// Get customer on list
		// $this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
		// $this->component_api->CallGet();
		

		// API data
		$_API_CUSTOMERS = $this->_customers;

		// Get payment method
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/");
		$this->component_api->CallGet();
		$_API_PAYMENTS = json_decode($this->component_api->GetConfig("result"),true);
		$_API_PAYMENTS = $_API_PAYMENTS['query'];

		// API data usage
		if(!empty($_API_CUSTOMERS) && !empty($_API_PAYMENTS))
		{
			// join different table into one array	
			foreach($_API_CUSTOMERS as $key => $val)
			{
				if(array_key_exists($val['pm_code'],$_API_PAYMENTS))
				{
					$_pm_code = $_API_CUSTOMERS[$key]['pm_code'];
					$_API_CUSTOMERS[$key]['payment_method'] = $_API_PAYMENTS[$_pm_code]['payment_method'];
				}
				else{
					$_API_CUSTOMERS[$key]['payment_method'] = "";
				}
			}
		
		// echo "<pre>";
		// var_dump($_data);
		// echo "</pre>";
			// load function bar view
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=>"", "style" => "", "show" => true, "extra" => ""]
				]
			]);

			// load main view
			$this->load->view('customers/customers-list-view', [
				'data' => $_API_CUSTOMERS,
				'paymethod' => $_API_PAYMENTS,
				"url" => base_url("customers/edit/"),
				"default_per_page" => $_default_per_page,
				"page" => $page
			]);
			$this->load->view('footer');
		}
	}
	public function edit($cust_code)
	{
		//$this->session->sess_destroy();
		$_data = [];
		$_new_customer = [];
		$_previous_disable = "";
		$_next_disable = "";
		// user data
		$_page = 1;

		
		if(!empty($cust_code))
		{
			// Call API here
			// Get customer on list by cust_code

			// Get customer on list

		
			// API data usage
			if(!empty($this->_customers) && !empty($cust_code) )
			{
				$_all = array_column($this->_customers, "cust_code");
				
				// search key
				$_key = array_search(
					$cust_code, array_column($this->_customers, "cust_code")
				);
				
				if($_key !== false)
				{
					$_cur = $_key;
					$_next = $_key + 1;
					$_previous = $_key - 1;
					
					if($_cur == (count($_all)-1))
					{
						$_next_disable = "disabled";
						$_next = (count($_all)-1);
					}
					if($_cur <= 0)
					{
						$_previous_disable = "disabled";
						$_previous = 0;
					}
					 //echo "<pre>";
					 //var_dump ($_all);
					 //echo "</pre>";
					// data for items type selection

					

					// function bar with next, preview and save button
					$this->load->view('function-bar', [
						"btn" => [
							["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/customers/page/'.$_page), "style" => "", "show" => true],
							["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "", "show" => true],
					 		["name" => "Previous", "type"=>"button", "id" => "previous", "url"=> base_url("/customers/edit/".$_all[$_previous]), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
					 		["name" => "Next", "type"=>"button", "id" => "next", "url"=> base_url("/customers/edit/".$_all[$_next]), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
					 	]
					]);

					// load main view
					//$this->load->view('customers/customers-edit-view', [
					//	"save_url" => base_url("customers/edit/save/"),
					// 	'data' => $_data
					//]);
					//$this->load->view('footer');
				}
			}
		}
		else
		{
			$alert = "danger";
			$this->load->view('error-handle', [
				'message' => "Data not Ready Yet!", 
				'code'=> "", 
				'alertstyle' => $alert
			]);
		}
	}
}
