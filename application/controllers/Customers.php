<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_token = "";
	var $_param = "";
	var $_customers = [];
	//var $_pm = [];
	//var $_pt = [];
	public function __construct()
	{
		parent::__construct();
		// $this->load->library("Component_Master");
		// if(isset($this->session->userdata['master']))
		// {
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
			
			$this->load->library("Component_Login",[$this->_token, "customers"]);

			// login session
			if(!empty($this->component_login->CheckToken()))
			{
				$this->_username = $this->session->userdata['login']['profile']['username'];

				// API call
				// fatch master
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employees/".$this->_username);
				$this->component_api->CallGet();
				$_API_EMP = json_decode($this->component_api->GetConfig("result"), true);
				$_API_EMP = $_API_EMP['query'];

				$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
				$this->component_api->CallGet();
				$_API_CUSTOMERS = json_decode($this->component_api->GetConfig("result"), true);
				$this->_customers = $_API_CUSTOMERS['query'];
		
				//$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/method/");
				//$this->component_api->CallGet();
				//$_PAYMENT_METHOD = json_decode($this->component_api->GetConfig("result"), true);
				//$this->_pm = $_PAYMENT_METHOD['query'];

				//$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/term/");
				//$this->component_api->CallGet();
				//$_PAYMENT_TERM = json_decode($this->component_api->GetConfig("result"), true);
				//$this->_pt = $_PAYMENT_TERM['query'];

				// sidebar session
				$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
				switch($this->_param)
				{
					case "customers/edit":
						$this->_param = "customers/index";
					break;
					case "customers/delete":
						$this->_param = "customers/index";
					break;
					case "customers/detail":
						$this->_param = "customers/index";
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
			}
			else
			{
				redirect(base_url("login?url=".urlencode($this->component_login->GetRedirectURL())),"refresh");
			}
		// }
		// else
		// {
		// 	redirect(base_url("master"),"refresh");
		// }

	}
	public function index($_page = 1, $new = false)
	{
		// variable initial
		$_default_per_page = 50;
		$_API_CUSTOMERS = [];
		$_modalshow = 0;

		// set create new modal pop up on initial
		if($this->input->get("new") == 1)
		{
			$_modalshow = 1;
		}

		// set user data
		$this->session->set_userdata('page',$_page);

		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
		$this->component_api->CallGet();
		$_API_CUSTOMERS = json_decode($this->component_api->GetConfig("result"), true);
		$_API_CUSTOMERS = $_API_CUSTOMERS['query'];

		// Get payment method
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/method/");
		$this->component_api->CallGet();
		$_PAYMENT_METHOD = json_decode($this->component_api->GetConfig("result"), true);
		$_PAYMENT_METHOD = $_PAYMENT_METHOD['query'];
		
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/term/");
		$this->component_api->CallGet();
		$_PAYMENT_TERM = json_decode($this->component_api->GetConfig("result"), true);
		$_PAYMENT_TERM = $_PAYMENT_TERM['query'];

		// API data usage
		if(!empty($_API_CUSTOMERS) >= 1 && !empty($_PAYMENT_METHOD))
		{

			// join different table into one array	
			foreach($_API_CUSTOMERS as $key => $val)
			{
				if(array_key_exists($val['pm_code'],$_PAYMENT_METHOD))
				{
					$_pm_code = $_API_CUSTOMERS[$key]['pm_code'];
					$_API_CUSTOMERS[$key]['payment_method'] = $_PAYMENT_METHOD[$_pm_code]['payment_method'];
				}
				else{
					$_API_CUSTOMERS[$key]['payment_method'] = "";
				}
			}
		
			// load function bar view
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => true, "extra" => "data-toggle='modal' data-target='#modal01'"]
				]
			]);

			// load main view
			$this->load->view('customers/customers-list-view', [
				"detail_url" => base_url("/customers/customers/detail/"),
				"del_url" => base_url("/customers/customers/delete/"),
				'data' => $_API_CUSTOMERS,
				"user_auth" => true,
				"default_per_page" => $_default_per_page,
				"page" => $_page,
				"modalshow" => $_modalshow
			]);
			$this->load->view("customers/customers-create-view",[
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/customers/customers/page/'.$_page), "style" => "", "show" => true],
						["name" => "Reset", "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
					 ]
				],true),
				"save_url" => base_url("/customers/customers/save/"),
				"new_pm_url" => base_url("/administration/payments/method/"),
				"new_pt_url" => base_url("/administration/payments/term/"),
				'payment_method' => $_PAYMENT_METHOD,
				'payment_term' => $_PAYMENT_TERM
			]);
			$this->load->view('footer');
		}
	}
	public function edit($cust_code)
	{
		//$this->session->sess_destroy();
		// $_data = [];
		// $_new_customer = [];
		$_previous_disable = "";
		$_next_disable = "";
		// user data
		$_page = 1;


		
		// API Call
		// Get payment method
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/method/");
		$this->component_api->CallGet();
		$_PAYMENT_METHOD = json_decode($this->component_api->GetConfig("result"), true);
		$_PAYMENT_METHOD = $_PAYMENT_METHOD['query'];
		
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/term/");
		$this->component_api->CallGet();
		$_PAYMENT_TERM = json_decode($this->component_api->GetConfig("result"), true);
		$_PAYMENT_TERM = $_PAYMENT_TERM['query'];

		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/district/");
		$this->component_api->CallGet();
		$_DISTRICT = json_decode($this->component_api->GetConfig("result"), true);
		$_DISTRICT = $_DISTRICT['query'];

		if(!empty($cust_code))
		{
			// Call API here
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/".$cust_code);
			$this->component_api->CallGet();
			$_API_CUSTOMERS = json_decode($this->component_api->GetConfig("result"), true);
			$_API_CUSTOMERS = $_API_CUSTOMERS['query'];

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

					// function bar with next, preview and save button
					$this->load->view('function-bar', [
						"btn" => [
							["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/customers/customers/detail/'.$cust_code), "style" => "", "show" => true],
							["name" => "Home", "type"=>"button", "id" => "home", "url"=>base_url('/customers/customers/page/'.$_page), "style" => "", "show" => true],
							["name" => "Reset", "type"=>"button", "id" => "reset", "url" => "" , "style" => "btn btn-outline-secondary", "show" => true],
							["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true],
							["name" => "Previous", "type"=>"button", "id" => "previous", "url"=> base_url("/customers/customers/edit/".$_all[$_previous]), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
					 		["name" => "Next", "type"=>"button", "id" => "next", "url"=> base_url("/customers/customers/edit/".$_all[$_next]), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
					 	]
					]);

					// load main view
					$this->load->view('customers/customers-edit-view', [
						"save_url" => base_url("customers/customers/edit/save/".$cust_code),
						'data' => $_API_CUSTOMERS,
						'data_payment_method' => $_PAYMENT_METHOD,
						'data_payment_term' => $_PAYMENT_TERM,
						'data_district' => $_DISTRICT
					]);
					$this->load->view('footer');
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
	/** 
	 * Show the detail customer data
	 */
	public function detail($cust_code)
	{
		$_previous_disable = "";
		$_next_disable = "";
		// user data
		$_page = 1;
		
		// API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/method/");
		$this->component_api->CallGet();
		$_PAYMENT_METHOD = json_decode($this->component_api->GetConfig("result"), true);
		$_PAYMENT_METHOD = $_PAYMENT_METHOD['query'];
		
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/term/");
		$this->component_api->CallGet();
		$_PAYMENT_TERM = json_decode($this->component_api->GetConfig("result"), true);
		$_PAYMENT_TERM = $_PAYMENT_TERM['query'];
		
		
		if(!empty($cust_code))
		{
			// Call API here
		
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
	// echo "<pre>";
	// var_dump($this->_customers[$_key]);
	// echo "</pre>";
					// function bar with next, preview and save button
					$this->load->view('function-bar', [
						"btn" => [
							["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/customers/customers/page/'.$_page), "style" => "", "show" => true],
							["name" => "Edit", "type"=>"button", "id" => "Edit", "url"=>base_url('/customers/customers/edit/'.$cust_code), "style" => "btn btn-primary", "show" => true],
							["name" => "Previous", "type"=>"button", "id" => "previous", "url"=> base_url("/customers/customers/detail/".$_all[$_previous]), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
					 		["name" => "Next", "type"=>"button", "id" => "next", "url"=> base_url("/customers/customers/detail/".$_all[$_next]), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
					 	]
					]);

					// load main view
					$this->load->view('customers/customers-detail-view', [
						'data' => $this->_customers[$_key],
						'data_payment_method' => $_PAYMENT_METHOD,
						'data_payment_term' => $_PAYMENT_TERM
					]);
					$this->load->view('footer');
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
	/**
	 * 
	 */
	public function delete()
	{

	}

	/**
	 * Save Edit
	 *
	 * To save edit configuration
	 * @param cust_code
	 */
	public function saveedit($cust_code = "")
	{
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
		if(isset($_POST) && !empty($_POST) && isset($cust_code) && !empty($cust_code))
		{
			
			$_api_body = json_encode($_POST,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			if($_api_body != "")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/".$cust_code);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);

				if(isset($result['error']['message']) || isset($result['error']['code']))
				{
					$alert = "danger";
					switch($result['error']['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}		
					$this->load->view('error-handle', [
						'message' => $result['error']['message'], 
						'code'=> $result['error']['code'], 
						'alertstyle' => $alert
					]);
					
					// callback initial page
					header("Refresh: 5; url=".base_url("/customers/customers/"));
				}
			}
		}
	}

	public function save()
	{
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
		if(isset($_POST) && !empty($_POST))
		{
			$_api_body = json_encode($_POST,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			if($_api_body != "")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
				if(isset($result['error']['message']) || isset($result['error']['code']))
				{
					$alert = "danger";
					switch($result['error']['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}		
					$this->load->view('error-handle', [
						'message' => $result['error']['message'], 
						'code'=> $result['error']['code'], 
						'alertstyle' => $alert
					]);
					
					// callback initial page
					header("Refresh: 5; url=".base_url("/customers/customers/"));
				}
			}
		}
	}
}
