<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_default_per_page = "";
	var $_page = "";
	var $_token = "";
	var $_profile = "";
	var $_param = "";
	var $_user_auth = ['create' => false, 'edit' => false, 'delete' => false];
	var $_API_HEADER;

	public function __construct()
	{
		parent::__construct();
		$_query = $this->input->get();
		// initial Access rule
		$this->_user_auth = ['create' => true, 'edit' => true, 'delete' => true];
		$this->_default_per_page = $this->config->item('DEFAULT_PER_PAGE');
		$this->_page = $this->config->item('DEFAULT_FIRST_PAGE');

		if($this->input->get("page"))
		{
			$this->_page = $this->input->get("page");
		}
		if($this->input->get("show"))
		{
			$this->_default_per_page = $this->input->get("show");
		}
		if(!empty($this->session->userdata['login']))
		{
			$this->_token = $this->session->userdata['login']['token'];
			$this->_profile = $this->session->userdata['login']['profile'];
		}
		
		$this->load->library("Component_Login",[$this->_token, "customers"]);

		// login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];

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
				"username" => $this->_API_HEADER['employee']['username'],
				"employee_code" => $this->_API_HEADER['employee']['employee_code'],
				"shop_code" => $this->_API_HEADER['employee']['shop_code'],
				"shop_name" => $this->_API_HEADER['employee']['shop_name'],
				"today" => date("Y-m-d"),
			];
			//Set user preference
			$_query['page'] = htmlspecialchars($this->_page);
			$_query['show'] = htmlspecialchars($this->_default_per_page);
			$_query = $this->component_uri->QueryToString($_query);
			$_login = $this->session->userdata['login'];
			$_login['preference'] = $_query;
			$this->session->set_userdata("login", $_login);

			// fatch side bar 
			$this->component_sidemenu->SetConfig("nav_list", $this->_API_HEADER['menu']);
			$this->component_sidemenu->SetConfig("active", $this->_param);
			$this->component_sidemenu->Proccess();

			// load header view
			$this->load->view('header',[
				'title'=>'Customers',
				'sideNav_view' => $this->load->view('side-nav', [
					"sideNav" => $this->component_sidemenu->GetConfig("nav_finished_list"),
					"path" => $this->component_sidemenu->GetConfig("path"),
					"param" => $this->_param
				], TRUE), 
				'topNav_view' => $this->load->view('top-nav', [
					"topNav" => $this->_inv_header_param["topNav"]
				], TRUE)
			]);
		}
		else
		{
			redirect(base_url("login?url=".urlencode($this->component_login->GetRedirectURL())),"auto");
		}
	}
	public function index()
	{
		// variable initial
		$_modalshow = 0;
		
		// set create new modal pop up on initial
		if($this->input->get("new") == 1)
		{
			$_modalshow = 1;
		}
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS'));
		$this->component_api->CallGet();
		$_API_CUSTOMERS = $this->component_api->GetConfig("result");
		$_API_CUSTOMERS = !empty($_API_CUSTOMERS['query']) ? $_API_CUSTOMERS['query'] : [];

		// Get payment method
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_METHOD = $this->component_api->GetConfig("result");
		$_API_PAYMENT_METHOD = !empty($_API_PAYMENT_METHOD['query']) ? $_API_PAYMENT_METHOD['query'] : [];
		
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_TERM = $this->component_api->GetConfig("result");
		$_API_PAYMENT_TERM = !empty($_API_PAYMENT_TERM['query']) ? $_API_PAYMENT_TERM['query'] : [];

		$this->component_api->SetConfig("url", $this->config->item('URL_DISTRICT'));
		$this->component_api->CallGet();
		$_API_DISTRICT = $this->component_api->GetConfig("result");
		$_API_DISTRICT = !empty($_API_DISTRICT['query']) ? $_API_DISTRICT['query'] : [];

		// get user preference
		$_login = $this->session->userdata("login");

		// API data usage
		if(!empty($_API_CUSTOMERS) && !empty($_API_PAYMENT_METHOD))
		{
			// load function bar view
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => $this->_user_auth['create'], "extra" => "data-toggle='modal' data-target='#modal01'"]
				]
			]);

			// load main view
			$this->load->view('customers/customers-list-view', [
				"detail_url" => base_url("/customers/detail/"),
				"del_url" => base_url("/customers/delete/"),
				'data' => $_API_CUSTOMERS,
				"user_auth" => $this->_user_auth,
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"modalshow" => $_modalshow
			]);
			$this->load->view("customers/customers-create-view",[
				"title" => $this->lang->line("customer_new_titles"),
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/customers'), "style" => "", "show" => true],
						["name" => "<i class='fas fa-redo'></i> ".$this->lang->line("function_reset"), "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
					 ]
				],true),
				"save_url" => base_url("/customers/save/"),
				"new_pm_url" => base_url("/administration/payments/method/?new=1"),
				"new_pt_url" => base_url("/administration/payments/term/?new=1"),
				'data_payment_method' => $_API_PAYMENT_METHOD,
				'data_payment_term' => $_API_PAYMENT_TERM,
				'data_district' => $_API_DISTRICT
			]);
			$this->load->view('footer');
		}
	}
	/**
	 * Edit customer data
	 */
	public function edit($cust_code = "")
	{
		//$this->session->sess_destroy();
		// $_data = [];
		// $_new_customer = [];
		$_previous_disable = "";
		$_next_disable = "";
		// user data

		$_login = $this->session->userdata("login");
	
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS').$cust_code);
		$this->component_api->CallGet();
		$_API_CUSTOMERS = $this->component_api->GetConfig("result");
		$_API_CUSTOMERS = !empty($_API_CUSTOMERS['query']) ? $_API_CUSTOMERS['query'] : [];

		// Get payment method
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_METHOD = $this->component_api->GetConfig("result");
		$_API_PAYMENT_METHOD = !empty($_API_PAYMENT_METHOD['query']) ? $_API_PAYMENT_METHOD['query'] : [];
		
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_TERM = $this->component_api->GetConfig("result");
		$_API_PAYMENT_TERM = !empty($_API_PAYMENT_TERM['query']) ? $_API_PAYMENT_TERM['query'] : [];

		$this->component_api->SetConfig("url", $this->config->item('URL_DISTRICT'));
		$this->component_api->CallGet();
		$_API_DISTRICT = $this->component_api->GetConfig("result");
		$_API_DISTRICT = !empty($_API_DISTRICT['query']) ? $_API_DISTRICT['query'] : [];
		
		if(!empty($cust_code))
		{
			// API data usage
			if(!empty($_API_CUSTOMERS) )
			{
				if(empty($_API_CUSTOMERS['previous']))
				{
					$_previous_disable = "disabled";
				}
				if(empty($_API_CUSTOMERS['next']))
				{
					$_next_disable = "disabled";
				}
				// function bar with next, preview and save button
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/customers/detail/'.$cust_code.$_login['preference']), "style" => "", "show" => true],
						["name" => "<i class='fas fa-home'></i> ".$this->lang->line("function_home"), "type"=>"button", "id" => "home", "url"=>base_url('/customers'.$_login['preference']), "style" => "", "show" => true],
						["name" => "<i class='fas fa-undo-alt'></i> ".$this->lang->line("function_reset"), "type"=>"button", "id" => "reset", "url" => "" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true],
						["name" => "<i class='fas fa-step-backward'></i> ".$this->lang->line("function_previous"), "type"=>"button", "id" => "previous", "url"=> base_url("/customers/edit/".$_API_CUSTOMERS['previous'].$_login['preference']), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
						["name" => "<i class='fas fa-step-forward'></i> ".$this->lang->line("function_next"), "type"=>"button", "id" => "next", "url"=> base_url("/customers/edit/".$_API_CUSTOMERS['next'].$_login['preference']), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
					]
				]);

				// load main view
				$this->load->view('customers/customers-edit-view', [
					"save_url" => base_url("customers/edit/save/".$cust_code),
					'data' => $_API_CUSTOMERS,
					'data_payment_method' => $_API_PAYMENT_METHOD,
					'data_payment_term' => $_API_PAYMENT_TERM,
					'data_district' => $_API_DISTRICT
				]);
				$this->load->view('footer');
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
	public function detail($cust_code = "")
	{
		$_previous_disable = "";
		$_next_disable = "";
		// user data

		
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS').$cust_code);
		$this->component_api->CallGet();
		$_API_CUSTOMERS = $this->component_api->GetConfig("result");
		$_API_CUSTOMERS = !empty($_API_CUSTOMERS['query']) ? $_API_CUSTOMERS['query'] : [];

		// Get payment method
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_METHOD = $this->component_api->GetConfig("result");
		$_API_PAYMENT_METHOD = !empty($_API_PAYMENT_METHOD['query']) ? $_API_PAYMENT_METHOD['query'] : [];
		
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_TERM = $this->component_api->GetConfig("result");
		$_API_PAYMENT_TERM = !empty($_API_PAYMENT_TERM['query']) ? $_API_PAYMENT_TERM['query'] : [];

		$this->component_api->SetConfig("url", $this->config->item('URL_DISTRICT'));
		$this->component_api->CallGet();
		$_API_DISTRICT = $this->component_api->GetConfig("result");
		$_API_DISTRICT = !empty($_API_DISTRICT['query']) ? $_API_DISTRICT['query'] : [];
	
		// echo "<pre>";
		// var_dump($_API_CUSTOMERS);
		// echo "</pre>";
		$_login = $this->session->userdata("login");

		//var_dump($_API_PAYMENT_METHOD);
		if(!empty($cust_code))
		{
			if(!empty($_API_CUSTOMERS)){
				if(empty($_API_CUSTOMERS['previous']))
				{
					$_previous_disable = "disabled";
				}
				if(empty($_API_CUSTOMERS['next']))
				{
					$_next_disable = "disabled";
				}
				// API data usage
				// function bar with next, preview and save button
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/customers'.$_login['preference']), "style" => "", "show" => true],
						["name" => "<i class='far fa-edit'></i> ".$this->lang->line("function_edit"), "type"=>"button", "id" => "Edit", "url"=>base_url('/customers/edit/'.$cust_code.$_login['preference']), "style" => "btn btn-primary", "show" => true],
						["name" => "<i class='fas fa-step-backward'></i> ".$this->lang->line("function_previous"), "type"=>"button", "id" => "previous", "url"=> base_url("/customers/detail/".$_API_CUSTOMERS['previous'].$_login['preference']), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
						["name" => "<i class='fas fa-step-forward'></i> ".$this->lang->line("function_next"), "type"=>"button", "id" => "next", "url"=> base_url("/customers/detail/".$_API_CUSTOMERS['next'].$_login['preference']), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
					]
				]);

				// load main view
				$this->load->view('customers/customers-detail-view', [
					'data' => $_API_CUSTOMERS,
					'data_payment_method' => $_API_PAYMENT_METHOD,
					'data_payment_term' => $_API_PAYMENT_TERM,
					'data_district' => $_API_DISTRICT
				]);
				$this->load->view('footer');

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
	 *  Delete 
	 *
	 * TO delete
	 * @param cust_code 
	 */
	public function delete($cust_code = "")
	{
		$_comfirm_show = true;
		$_count = "";
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY_HAS_TRANSACTION_CUSTOMERS').$cust_code);
		$this->component_api->CallGet();
		$_data = $this->component_api->GetConfig("result");
		$_data = $_data['query'] != null ? $_data['query'] : [];

		if(!empty($_data))
		{
		   $_login = $this->session->userdata("login");
			// configure message 
			if($_data['has'])
			{
				$_comfirm_show = false;
				$_count = count($_data['data']);

			}
			$this->load->view("customers/customers-del-view",[
				"submit_to" => base_url('/customers/delete/confirmed/'.$cust_code),
				"to_deleted_num" => $cust_code,
				"confirm_show" => $_comfirm_show,
				"count" => $_count,
				"return_url" => base_url('/customers'.$_login['preference'])
			]);
		}
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

			if($_api_body != "")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS').$cust_code);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);

				if(isset($result['error']['message']) || isset($result['error']['code']))
				{
					$_login = $this->session->userdata("login");
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
					header("Refresh: 5; url=".base_url("/customers".$_login['preference']));
				}
			}
		}
	}

	/** 
	 * Process Save delete 
	 * 
	 * To save delete configuration
	 * @param cust_code
	 */
	public function savedel($cust_code="")
	{
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS').$cust_code);
		$this->component_api->CallDelete();
		$result = json_decode($this->component_api->GetConfig("result"),true);
		if(isset($result['error']['message']) || isset($result['error']['code']))
		{
			$_login = $this->session->userdata("login");
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
			header("Refresh: 5; url=".base_url("/customers".$_login['preference']));
		}
	}

	/**
	 * Process save create
	 * 
	 */
	public function save()
	{

		if(isset($_POST) && !empty($_POST))
		{
			$_api_body = json_encode($_POST,true);

			if($_api_body != "")
			{
				// echo "<pre>";
				// var_dump($_api_body);
				// echo "</pre>";
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS'));
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
				if(isset($result['error']['message']) || isset($result['error']['code']))
				{
					$_login = $this->session->userdata("login");
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
					header("Refresh: 5; url=".base_url("/customers".$_login['preference']));
				}
			}
		}
	}
}
