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
			"username" => $username,
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
			'title'=>'Customers',
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
	public function index($page=1)
	{
		$_default_per_page = 50;
		$data = [];

		// set user data
		$this->session->set_userdata('page',$page);

		// Call API here
		// Get customer on list
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);
		// Get payment method
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/");
		$this->component_api->CallGet();
		$_paymethod = json_decode($this->component_api->GetConfig("result"),true);

		// API data usage
		if(!empty($_data["query"]) && !empty($_paymethod['query']))
		{
			// join different table into one array	
			foreach($_data['query'] as $key => $val)
			{
				if(array_key_exists($val['pm_code'],$_paymethod['query']))
				{
					$_pm_code = $_data['query'][$key]['pm_code'];
					$_data['query'][$key]['payment_method'] = $_paymethod['query'][$_pm_code]['payment_method'];
				}
				else{
					$_data['query'][$key]['payment_method'] = "";
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
				'data' => $_data,
				'paymethod' => $_paymethod,
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
		
		
		// user data
		$_page = $this->session->userdata("page");


		if(!empty($cust_code))
		{
			// Call API here
			// Get customer on list by cust_code
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/".$cust_code);
			$this->component_api->CallGet();
			$_customer = json_decode($this->component_api->GetConfig("result"), true);
			// Get customer on list
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
			$this->component_api->CallGet();
			$_data = json_decode($this->component_api->GetConfig("result"), true);
			// Get payment method
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/");
			$this->component_api->CallGet();
			$_paymethod = json_decode($this->component_api->GetConfig("result"),true);
			echo "<pre>";
			var_dump($_customer);
			echo "</pre>";
			// API data usage
			if(!empty($_data["query"]) && !empty($_paymethod['query']))
			{
				$_all = array_column($_data['query'], "cust_code");

				// search key
				$_key = array_search(
					$cust_code, array_column($_data['query'], "cust_code")
				);
			// echo "<pre>";
			// var_dump($_all);
			// echo "</pre>";
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
					// echo "<pre>";
					// var_dump ($_all);
					// echo "</pre>";
					// data for items type selection
					if(!empty($_paymethod["query"]))
					{
						foreach($_data['query'] as $key => $val)
						{
							if(array_key_exists($val['pm_code'],$_paymethod['query']))
							{
								$_pm_code = $_data['query'][$key]['pm_code'];
								$_data['query'][$key]['payment_method'] = $_paymethod['query'][$_pm_code]['payment_method'];
							}
							else{
								$_data['query'][$key]['payment_method'] = "";
							}
						}
					}
					// // function bar with next, preview and save button
					// $this->load->view('function-bar', [
					// 	"btn" => [
					// 		["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/customers/page/'.$_page), "style" => "", "show" => true],
					// 		["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "", "show" => true],
					// 		["name" => "Previous", "type"=>"button", "id" => "previous", "url"=> base_url("/customers/edit/".$_all[$_previous]), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
					// 		["name" => "Next", "type"=>"button", "id" => "next", "url"=> base_url("/customers/edit/".$_all[$_next]), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
					// 	]
					// ]);

					// // load main view
					// $this->load->view('customers/customers-edit-view', [
					// 	"save_url" => base_url("customers/edit/save/"),
					// 	'data' => $_data,
					// 	'paymethod' => $_paymethod,
						
					// ]);
					// $this->load->view('footer');
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
