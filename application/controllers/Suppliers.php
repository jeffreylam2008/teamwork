<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suppliers extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_default_per_page = "";
	var $_page = "";
	var $_token = "";
	var $_profile = "";
	var $_param = "";
	var $_user_auth = ['create' => false, 'edit' => false, 'delete' => false];
	//var $_pm = [];
	//var $_pt = [];
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
		
		$this->load->library("Component_Login",[$this->_token, "suppliers"]);

		// login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API call
			// fatch master
			$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES').$this->_profile['username']);
			$this->component_api->CallGet();
			$_API_EMP = json_decode($this->component_api->GetConfig("result"), true);
			$_API_EMP = !empty($_API_EMP['query']) ? $_API_EMP['query'] : ['username' => "", 'employee_code' => ""];
			$this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$this->_profile['shopcode']);
			$this->component_api->CallGet();
			$_API_SHOP = json_decode($this->component_api->GetConfig("result"), true);
			$_API_SHOP = !empty($_API_SHOP['query']) ? $_API_SHOP['query'] : ['shop_code' => "", 'name' => ""];
			$this->component_api->SetConfig("url", $this->config->item('URL_MENU_SIDE'));
			$this->component_api->CallGet();
			$_API_MENU = json_decode($this->component_api->GetConfig("result"), true);
			$_API_MENU = !empty($_API_MENU['query']) ? $_API_MENU['query'] : [];

			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "suppliers/edit":
					$this->_param = "suppliers/index";
				break;
				case "suppliers/delete":
					$this->_param = "suppliers/index";
				break;
				case "suppliers/detail":
					$this->_param = "suppliers/index";
				break;
			}

			// header data
			$this->_inv_header_param["topNav"] = [
				"isLogin" => true,
				"username" => $_API_EMP['username'],
				"employee_code" => $_API_EMP['employee_code'],
				"shop_code" => $_API_SHOP['shop_code'],
				"shop_name" => $_API_SHOP['name'],
				"today" => date("Y-m-d")
			];
			//Set user preference
			$_query['page'] = htmlspecialchars($this->_page);
			$_query['show'] = htmlspecialchars($this->_default_per_page);
			$_query = $this->component_uri->QueryToString($_query);
			$_login = $this->session->userdata['login'];
			$_login['preference'] = $_query;
			$this->session->set_userdata("login", $_login);

			// fatch side bar 
			$this->component_sidemenu->SetConfig("nav_list", $_API_MENU);
			$this->component_sidemenu->SetConfig("active", $this->_param);
			$this->component_sidemenu->Proccess();

			// load header view
			$this->load->view('header',[
				'title'=>'Suppliers',
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
			redirect(base_url("login?url=".urlencode($this->component_login->GetRedirectURL())),"refresh");
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
		$this->component_api->SetConfig("url", $this->config->item('URL_SUPPLIERS'));
		$this->component_api->CallGet();
		$_API_SUPPLIERS = json_decode($this->component_api->GetConfig("result"), true);
		$_API_SUPPLIERS = !empty($_API_SUPPLIERS['query']) ? $_API_SUPPLIERS['query'] : [];

		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_METHOD = json_decode($this->component_api->GetConfig("result"), true);
		$_API_PAYMENT_METHOD = !empty($_API_PAYMENT_METHOD['query']) ? $_API_PAYMENT_METHOD['query'] : [];
		
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_TERM = json_decode($this->component_api->GetConfig("result"), true);
		$_API_PAYMENT_TERM = !empty($_API_PAYMENT_TERM['query']) ? $_API_PAYMENT_TERM['query'] : [];
		// echo "<pre>";
		// print_r($_API_SUPPLIERS);
		// echo "</pre>";
		
		// get user preference
		$_login = $this->session->userdata("login");

		// API data usage
		
			// load function bar view
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => $this->_user_auth['create'], "extra" => "data-toggle='modal' data-target='#modal01'"]
				]
			]);

			// load main view
			$this->load->view('suppliers/suppliers-list-view', [
				"detail_url" => base_url("/suppliers/detail/"),
				"del_url" => base_url("/suppliers/delete/"),
				'data' => $_API_SUPPLIERS,
				"user_auth" => $this->_user_auth,
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"modalshow" => $_modalshow
			]);
			$this->load->view("suppliers/suppliers-create-view",[
				"title" => $this->lang->line("supplier_new_titles"),
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/suppliers'), "style" => "", "show" => true],
						["name" => "<i class='fas fa-redo'></i> ".$this->lang->line("function_reset"), "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
					 ]
				],true),
				"save_url" => base_url("/suppliers/save/"),
				"new_pm_url" => base_url("/administration/payments/method/?new=1"),
				"new_pt_url" => base_url("/administration/payments/term/?new=1"),
				'data_payment_method' => $_API_PAYMENT_METHOD,
				'data_payment_term' => $_API_PAYMENT_TERM,
			]);
			$this->load->view('footer');
		
	}
	/**
	 * To edit supplier detail
	 * @param supp_code Supplier code
	 */
	public function edit($supp_code = "")
	{
		//$this->session->sess_destroy();
		// $_data = [];
		// $_new_customer = [];
		$_previous_disable = "";
		$_next_disable = "";
		// user data

		$_login = $this->session->userdata("login");
	
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_SUPPLIERS').$supp_code);
		$this->component_api->CallGet();
		$_API_SUPPLIERS = json_decode($this->component_api->GetConfig("result"), true);
		$_API_SUPPLIERS = !empty($_API_SUPPLIERS['query']) ? $_API_SUPPLIERS['query'] : [];

		// Get payment method
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_METHOD = json_decode($this->component_api->GetConfig("result"), true);
		$_API_PAYMENT_METHOD = !empty($_API_PAYMENT_METHOD['query']) ? $_API_PAYMENT_METHOD['query'] : [];
		
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_TERM = json_decode($this->component_api->GetConfig("result"), true);
		$_API_PAYMENT_TERM = !empty($_API_PAYMENT_TERM['query']) ? $_API_PAYMENT_TERM['query'] : [];

		if(!empty($supp_code))
		{
			// API data usage
			if(!empty($_API_SUPPLIERS) )
			{
				if(empty($_API_SUPPLIERS['previous']))
				{
					$_previous_disable = "disabled";
				}
				if(empty($_API_SUPPLIERS['next']))
				{
					$_next_disable = "disabled";
				}
				// function bar with next, preview and save button
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> Back", "type"=>"button", "id" => "back", "url"=>base_url('/suppliers/detail/'.$supp_code.$_login['preference']), "style" => "", "show" => true],
						["name" => "<i class='fas fa-home'></i>  Home", "type"=>"button", "id" => "home", "url"=>base_url('/suppliers'.$_login['preference']), "style" => "", "show" => true],
						["name" => "<i class='fas fa-undo-alt'></i> Reset", "type"=>"button", "id" => "reset", "url" => "" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "<i class='far fa-save'></i> Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true],
						["name" => "<i class='fas fa-step-backward'></i> Previous", "type"=>"button", "id" => "previous", "url"=> base_url("/suppliers/edit/".$_API_SUPPLIERS['previous'].$_login['preference']), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
						["name" => "<i class='fas fa-step-forward'></i> Next", "type"=>"button", "id" => "next", "url"=> base_url("/suppliers/edit/".$_API_SUPPLIERS['next'].$_login['preference']), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
					]
				]);

				// load main view
				$this->load->view('suppliers/suppliers-edit-view', [
					"save_url" => base_url("suppliers/edit/save/".$supp_code),
					'data' => $_API_SUPPLIERS,
					'data_payment_method' => $_API_PAYMENT_METHOD,
					'data_payment_term' => $_API_PAYMENT_TERM,
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
	 * Show the detail supplier data
	 * @param supp_code Suppliers Code to look up record
	 */
	public function detail($supp_code = "")
	{
		$_previous_disable = "";
		$_next_disable = "";
		// user data
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_SUPPLIERS').$supp_code);
		$this->component_api->CallGet();
		$_API_SUPPLIERS = json_decode($this->component_api->GetConfig("result"), true);
		$_API_SUPPLIERS = !empty($_API_SUPPLIERS['query']) ? $_API_SUPPLIERS['query'] : [];

		// Get payment method
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_METHOD = json_decode($this->component_api->GetConfig("result"), true);
		$_API_PAYMENT_METHOD = !empty($_API_PAYMENT_METHOD['query']) ? $_API_PAYMENT_METHOD['query'] : [];

		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_TERM = json_decode($this->component_api->GetConfig("result"), true);
		$_API_PAYMENT_TERM = !empty($_API_PAYMENT_TERM['query']) ? $_API_PAYMENT_TERM['query'] : [];

		// echo "<pre>";
		// var_dump($_API_SUPPLIERS);
		// echo "</pre>";
		$_login = $this->session->userdata("login");

		//var_dump($_API_PAYMENT_METHOD);
		if(!empty($supp_code))
		{
			if(!empty($_API_SUPPLIERS)){
				if(empty($_API_SUPPLIERS['previous']))
				{
					$_previous_disable = "disabled";
				}
				if(empty($_API_SUPPLIERS['next']))
				{
					$_next_disable = "disabled";
				}
				// API data usage
				// function bar with next, preview and save button
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> Back", "type"=>"button", "id" => "back", "url"=>base_url('/suppliers'.$_login['preference']), "style" => "", "show" => true],
						["name" => "<i class='far fa-edit'></i> Edit", "type"=>"button", "id" => "Edit", "url"=>base_url('/suppliers/edit/'.$supp_code.$_login['preference']), "style" => "btn btn-primary", "show" => true],
						["name" => "<i class='fas fa-step-backward'></i> Previous", "type"=>"button", "id" => "previous", "url"=> base_url("/suppliers/detail/".$_API_SUPPLIERS['previous'].$_login['preference']), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
						["name" => "<i class='fas fa-step-forward'></i> Next", "type"=>"button", "id" => "next", "url"=> base_url("/suppliers/detail/".$_API_SUPPLIERS['next'].$_login['preference']), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
					]
				]);

				// load main view
				$this->load->view('suppliers/suppliers-detail-view', [
					'data' => $_API_SUPPLIERS,
					'data_payment_method' => $_API_PAYMENT_METHOD,
					'data_payment_term' => $_API_PAYMENT_TERM,
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
	 * @param supp_code 
	 */
	public function delete($supp_code = "")
	{
		$_comfirm_show = true;
		$_count = "";
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY_HAS_TRANSACTION_SUPPLIERS').$supp_code);
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);
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
			$this->load->view("suppliers/suppliers-del-view",[
				"submit_to" => base_url('/suppliers/delete/confirmed/'.$supp_code),
				"to_deleted_num" => $supp_code,
				"confirm_show" => $_comfirm_show,
				"count" => $_count,
				"return_url" => base_url('/suppliers'.$_login['preference'])
			]);
		}
	}

	/**
	 * Save Edit
	 *
	 * To save edit configuration
	 * @param supp_code
	 */
	public function saveedit($supp_code = "")
	{
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
		if(isset($_POST) && !empty($_POST) && isset($supp_code) && !empty($supp_code))
		{
			
			$_api_body = json_encode($_POST,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			if($_api_body != "")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_SUPPLIERS').$supp_code);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);

				if(isset($result['error']['message']) || isset($result['error']['code']))
				{
					$_login = $this->session->userdata('login');
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
					header("Refresh: 5; url=".base_url("/suppliers".$_login['preference']));
				}
			}
		}
	}

	/** 
	 * Process Save delete 
	 * 
	 * To save delete configuration
	 * @param supp_code
	 */
	public function savedel($supp_code="")
	{
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_SUPPLIERS').$supp_code);
		$this->component_api->CallDelete();
		$result = json_decode($this->component_api->GetConfig("result"),true);
		if(isset($result['error']['message']) || isset($result['error']['code']))
		{
			$_login = $this->session->userdata('login');
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
			header("Refresh: 5; url=".base_url("/suppliers".$_login['preference']));
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
				$this->component_api->SetConfig("url", $this->config->item('URL_SUPPLIERS'));
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
					header("Refresh: 5; url=".base_url("/suppliers/"));
				}
			}
		}
	}
}
