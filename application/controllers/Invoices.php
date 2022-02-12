<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller 
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
		// enable application cache 
		// $this->component_master->Init();
		// $this->_master = $this->component_master->FatehAll();  
		
		$_query = $this->input->get();
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

		// call token from session
		if(!empty($this->session->userdata['login']))
		{
			// extend logon timeout
			$this->_token = $this->session->userdata['login']['token'];
			$this->_profile = $this->session->userdata['login']['profile'];
		}

		// API call
		$this->load->library("Component_Login",[$this->_token, "invoices/list"]);

		// // login session
		if(!empty($this->component_login->CheckToken()))
		{

			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_INVOICES_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = json_decode($this->component_api->GetConfig("result"), true);
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];
			// $this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES').$this->_profile['username']);
			// $this->component_api->CallGet();
			// $_API_EMP = json_decode($this->component_api->GetConfig("result"), true);
			// $_API_EMP = !empty($_API_EMP['query']) ? $_API_EMP['query'] : ['username' => "", 'employee_code' => ""];
			// $this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$this->_profile['shopcode']);
			// $this->component_api->CallGet();
			// $_API_SHOP = json_decode($this->component_api->GetConfig("result"), true);
			// $_API_SHOP = !empty($_API_SHOP['query']) ? $_API_SHOP['query'] : ['shop_code' => "", 'name' => ""];
			// $this->component_api->SetConfig("url", $this->config->item('URL_MENU_SIDE'));
			// $this->component_api->CallGet();
			// $_API_MENU = json_decode($this->component_api->GetConfig("result"), true);
			// $_API_MENU = !empty($_API_MENU['query']) ? $_API_MENU['query'] : [];
			
			// from data cache 			
			//var_dump($_API_EMP);
			// $_API_EMP = $this->component_master->FetchByKey("employees", "username", $this->_profile['username']); 
			// $_API_SHOP = $this->component_master->FetchByKey("shops", "shop_code", $this->_profile['shopcode']);
			// $_API_MENU = $this->_master['menu'];
			// $_API_MENU = !empty($_API_MENU['query']) ? $_API_MENU['query'] : [];

			// $this->component_api->SetConfig("url", $this->config->item('URL_INVOICES_PREFIX'));
			// $this->component_api->CallGet();
			// $_API_PREFIX = json_decode($this->component_api->GetConfig("result"), true);
			// $_API_PREFIX = !empty($_API_PREFIX['query']) ? $_API_PREFIX['query'] : [];
			// dummy data
			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "invoices/edit":
					$this->_param = "invoices/index";
				break;
				case "invoices/tender":
					$this->_param = "invoices/index";
				break;
			}
			
			// fatch employee API
			$this->_inv_header_param["topNav"] = [
				"isLogin" => true,
				"username" => $this->_API_HEADER['employee']['username'],
				"employee_code" => $this->_API_HEADER['employee']['employee_code'],
				"shop_code" => $this->_API_HEADER['employee']['shop_code'],
				"shop_name" => $this->_API_HEADER['employee']['shop_name'],
				"today" => date("Y-m-d"),
				"prefix" => $this->_API_HEADER['prefix']['prefix']
			];
			
			if(!empty($_query))
			{
				//Set user preference
				$_query['page'] = htmlspecialchars($this->_page);
				$_query['show'] = htmlspecialchars($this->_default_per_page);
				$_query = $this->component_uri->QueryToString($_query);
				$_login = $this->session->userdata['login'];
				$_login['preference'] = $_query;
				$this->session->set_userdata("login", $_login);
			}
			// fatch side bar API
			$this->component_sidemenu->SetConfig("nav_list", $this->_API_HEADER['menu']);
			$this->component_sidemenu->SetConfig("active", $this->_param);
			$this->component_sidemenu->Proccess();

			// render the view
			$this->load->view('header',[
				'title'=>'Invoices',
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
	/**
	 * List Invoice Process
	 * To list out and query invoice record
	 */
	public function index()
	{
		// variable initial
		$_data = [];
		$_start_date = "";
		$_end_date = "";
		$_invoice_num = "";
		$_cust_code = "";
		if(empty($_GET['i-start-date']) && empty($_GET['i-end-date']))
		{
			$_GET['i-start-date'] = date("Y-m-d", strtotime('-5 days'));
			$_GET['i-end-date'] = date("Y-m-d");
		}
		$_query =$this->input->get();
		if(!empty($_query))
		{
			$_invoice_num = $this->input->get("i-invoice-num");
			$_start_date = $this->input->get('i-start-date');
			$_end_date = $this->input->get('i-end-date');
			$_cust_code = $this->input->get('i-cust-code');

			//Set user preference
			$_query['page'] = htmlspecialchars($this->_page);
			$_query['show'] = htmlspecialchars($this->_default_per_page);
			$_query['i-start-date'] = htmlspecialchars($_start_date);
			$_query['i-end-date'] = htmlspecialchars($_end_date);
			$_query['i-invoice-num'] = htmlspecialchars($_invoice_num);
			$_query['i-cust-code'] = htmlspecialchars($_cust_code);
			if(!empty($_query['i-invoice-num'])){
				$_query['i-start-date']  = $_query['i-end-date'];
			}
			$_query = $this->component_uri->QueryToString($_query);
			$_login = $this->session->userdata['login'];
			$_login['preference'] = $_query;
			$this->session->set_userdata("login", $_login);
			
			// fatch items API
			if(!empty($_cust_code))
			{
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY')."getlast/cust/".$_cust_code);
			}
			else
			{
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY').$_query);
			}
			$this->component_api->CallGet();
			$_data = json_decode($this->component_api->GetConfig("result"), true);
			$_data = $_data != null ? $_data : "";
		}
		// echo "<pre>";
		// var_dump($_data);
		// echo "</pre>";
		
		if(!empty($_data['error']['code']) && $_data['error']['code'] != "00000")
		{
			$this->load->view("error-handle", [
				"alertstyle" => "danger",
				"code" => $_data['error']['code'],
				"message" => $_data['error']['message']
			]);
		}
		else
		{
			// Function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=> base_url("invoices/donew/"), "style" => "", "show" => true, "extra" => ""]
				]
			]);
			// Function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-search'></i> ".$this->lang->line("function_search"), "type"=>"button", "id" => "i-search", "url"=> "#", "style" => "", "show" => true, "extra" => ""],
					["name" => "<i class='fas fa-undo-alt'></i> ".$this->lang->line("function_clear"), "type"=>"button", "id" => "i-clear", "url"=> "#", "style" => "btn btn-secondary", "show" => true, "extra" => ""]
				]
			]);
			// View Content
			$this->load->view("invoices/invoices-list-view", [
				"data" => $_data,
				"submit_to" => base_url("/invoices/list"),
				"edit_url" => base_url("invoices/edit/"),
				"quotation_url" => base_url("quotations/edit/"),
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"ad_start_date" => $_start_date,
				"ad_end_date" => $_end_date,
				"ad_invoice_num" => $_invoice_num,
				"ad_cust_code" => $_cust_code
			]);
		}
		$this->load->view("footer");
	}
	/**
	 * Invoice Number Generation
	 * To generate new invoice number
	 */
	public function donew()
	{
		
		if(!empty($this->session->userdata('transaction')))
		{
			$this->session->unset_userdata('transaction');
		}
		$this->component_api->SetConfig("url", $this->config->item('URL_INVOICES_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API_NEXT = json_decode($this->component_api->GetConfig("result"), true);
		$_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
		redirect(base_url("invoices/create/".$_API_NEXT),"refresh");
	}
	/**
	 * Copy Operation
	 * @param _num Invoice number user's want to be copied
	 */
	public function docopy($_num)
	{
		$_transaction = [];
		if(!empty($this->session->userdata('transaction')))
		{
			$this->session->unset_userdata('transaction');
		}
		//fatch existing transaction
		$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY').$_num);
		$this->component_api->CallGet();
		$_API_INV = json_decode($this->component_api->GetConfig("result"),true);
		$_API_INV = !empty($_API_INV['query']) ? $_API_INV['query'] : "";
		// get next Invoice number
		$this->component_api->SetConfig("url", $this->config->item('URL_INVOICES_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API_NEXT = json_decode($this->component_api->GetConfig("result"), true);
		$_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";

		$_transaction[$_API_NEXT] = $_API_INV;
		$_transaction[$_API_NEXT]['date'] = date("Y-m-d H:i:s");
		$_transaction[$_API_NEXT]['quotation'] = "";
		$this->session->set_userdata('cur_invoicenum',$_API_NEXT);
		$this->session->set_userdata('transaction',$_transaction);
		redirect(base_url("invoices/create/".$_API_NEXT),"refresh");
	}
	/**
	 * Convert Operation
	 * @param _quotation quotation number user's will be converted
	 */
	public function convert($_quotation = "")
	{
		$_transaction = [];
		$this->component_api->SetConfig("url", $this->config->item('URL_INVOICES_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API_NEXT = json_decode($this->component_api->GetConfig("result"), true);
		$_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";


		if(!empty($_quotation))
		{
			$_temp = $this->session->userdata('transaction');
			$_transaction[$_API_NEXT] = $_temp['query'];
			$_transaction[$_API_NEXT]['invoicenum'] = $_API_NEXT;
			$_transaction[$_API_NEXT]['date'] = date("Y-m-d H:i:s");
			$_transaction[$_API_NEXT]['prefix'] = $this->_inv_header_param["topNav"]['prefix'];
			$this->session->set_userdata('transaction', $_transaction);
			$this->session->set_userdata('cur_invoicenum', $_API_NEXT);
		}
		// echo "<pre>";
		// var_dump($_transaction);
		// echo "</pre>";
		
		redirect(base_url("invoices/create/".$_API_NEXT."/".$_quotation),"refresh");
	}
	/**
	 * Create Process
	 * To create new inovice transaction
	 * @param _invoice_num Invoice number
	 * @param _quotation_num Quotation number
	 */
	public function create($_invoice_num = "", $_quotation_num = "")
	{
		// variable initial
		$_show_discard_btn = false;
		$_transaction = [];

		if(!empty($_invoice_num))
		{
			$_show_discard_btn = true;
			// create invoice	
			// if((substr($_invoice_num , 0 , 3) === $this->_inv_header_param["topNav"]['prefix']))
			// {
			// For back button after submit to tender page
			if(!empty($this->session->userdata('transaction')) && !empty($this->session->userdata('cur_invoicenum')))
			{
				$_invoice_num = $this->session->userdata('cur_invoicenum');
				$_transaction = $this->session->userdata('transaction');
			}
			// For new create
			else 
			{
				$_transaction[$_invoice_num]['items'] = [];
				$_transaction[$_invoice_num]['quotation'] = "";
				$_transaction[$_invoice_num]['cust_code'] = "";
				$_transaction[$_invoice_num]['cust_name'] = "";
				$_transaction[$_invoice_num]['paymentmethod'] = "";
				$_transaction[$_invoice_num]['paymentmethodname'] = "";
				$_transaction[$_invoice_num]['remark'] = "";
				$_transaction[$_invoice_num]['invoice_num'] = $_invoice_num;
				$this->session->set_userdata('cur_invoicenum',$_invoice_num);
				$this->session->set_userdata('transaction',$_transaction);
			}
			// }

		// echo "<pre>";
		// var_dump($_SESSION);
		// echo "</pre>";

			// fatch items API
			$this->component_api->SetConfig("url", $this->config->item('URL_MASTER'));
			$this->component_api->CallGet();
			$_API_MASTER = json_decode($this->component_api->GetConfig("result"), true);
			$_API_MASTER = !empty($_API_MASTER['query']) ? $_API_MASTER['query'] : "";

			// // $_API_ITEMS = $this->_master['items'];
			// $this->component_api->SetConfig("url", $this->config->item('URL_ITEMS'));
			// $this->component_api->CallGet();
			// $_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
			// $_API_ITEMS = !empty($_API_ITEMS['query']) ? $_API_ITEMS['query'] : "";


			// echo "<pre>";
			// var_dump($_API_ITEMS);
			// echo "</pre>";
			
			// // fatch shop code and shop detail API
			// //$_API_SHOPS = $this->_master['shops'];
			// $this->component_api->SetConfig("url", $this->config->item('URL_SHOP'));
			// $this->component_api->CallGet();
			// $_API_SHOPS = json_decode($this->component_api->GetConfig("result"), true);
			// $_API_SHOPS = !empty($_API_SHOPS['query']) ? $_API_SHOPS['query'] : "";
			
			// // fatch customer API
			// // $_API_CUSTOMERS = $this->_master['customers'];
			// $this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS'));
			// $this->component_api->CallGet();
			// $_API_CUSTOMERS = json_decode($this->component_api->GetConfig("result"), true);
			// $_API_CUSTOMERS = !empty($_API_CUSTOMERS['query']) ? $_API_CUSTOMERS['query'] : "";
			
			// // fatch payment method API
			// // $_API_PAYMENTS = $this->_master['paymentmethods'];
			// $this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
			// $this->component_api->CallGet();
			// $_API_PAYMENTS = json_decode($this->component_api->GetConfig("result"),true);
			// $_API_PAYMENTS = !empty($_API_PAYMENTS['query']) ? $_API_PAYMENTS['query'] : "";
			
			//fatch DN number and set DN prefix
			// $this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE_PREFIX'));
			// $this->component_api->CallGET();
			// $_API_DN_PREFIX = json_decode($this->component_api->GetConfig("result"),true);
			// $_API_DN_PREFIX = !empty($_API_DN_PREFIX['query']) ? $_API_DN_PREFIX['query'] : "";
			// $this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE_NEXT_NUM'));
			// $this->component_api->CallGET();
			// $_API_DN_NUM = json_decode($this->component_api->GetConfig("result"),true);
			// $_API_DN_NUM = !empty($_API_DN_NUM['query']) ? $_API_DN_NUM['query'] : "";			

			// function bar with next, preview and save button
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/invoices/discard'), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);
			$this->load->view('title-bar', [
				"title" => $this->lang->line("invoice_new_titles")
			]);
			// present form view
			$this->load->view('invoices/invoices-create-view', [
				"submit_to" => base_url("/invoices/tender"),
				"prefix" => $this->_inv_header_param['topNav']['prefix'],
				"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"quote_fetch_url" => $this->config->item('URL_QUOTATIONS')."getinfo/cust/",
				"quote_item_fetch_url" => $this->config->item('URL_INVENTORY')."getinfo",
				"invoice_num" => $_invoice_num,
				"date" => date("Y-m-d H:i:s"),
				"dn_num" => $this->_API_HEADER['dn']['dn_num'],
				"dn_prefix" => $this->_API_HEADER['dn']['dn_prefix'],
				"ajax" => [
					"items" => $_API_MASTER['items'],
					"shop_code" => $_API_MASTER['shops'],
					"customers" => $_API_MASTER['customers'],
					"tender" => $_API_MASTER['paymentmethod']
				],
				"data" => $_transaction[$_invoice_num],
				"default_per_page" => $this->_default_per_page,
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "new", "url"=>base_url('/customers/?new=1'), "style" => "", "show" => true]
					 ]
				],true)
			]);
			// persent footer view
			$this->load->view('footer');
		}
	}
	/**
	 * Edit Process
	 * To edit inovice information
	 * @param _invoice_num invoice number selected to be edit
	 */
	public function edit($_invoice_num = "")
	{
		// variable initial
		$_transaction = [];
		$_trans = [];
		$_show_void_btn = false;
		$_show_next_btn = true;
		$_show_copy_btn = true;

		if(!empty($_invoice_num))
		{
			$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY').$_invoice_num);
			$this->component_api->CallGet();
			$_transaction = json_decode($this->component_api->GetConfig("result"),true);
			$_transaction = $_transaction != null ? $_transaction : "";

			$this->session->set_userdata('cur_invoicenum',$_invoice_num);
			$this->session->set_userdata('transaction',$_transaction['query']);
		// echo "<pre>";
		// var_dump($_transaction);
		// echo "</pre>";
			
			if(!empty($_transaction))
			{
				// set current invoice number to session
				$_login = $this->session->userdata("login");
				
				if($_transaction['has'])
				{
					// check invoice date was same with today
					$_today = date_create($this->_inv_header_param['topNav']['today']);
					$_date = date_create(date("Y-m-d",strtotime($_transaction['query']['date'])));
					$_diff = date_diff($_today,$_date);
					$_the_date_diff = $_diff->format("%a");
					if($_the_date_diff == 0){
						$_show_void_btn = true;
					}

					if($_transaction['query']['is_void'])
					{
						$_show_copy_btn = false;
						$_show_next_btn = false;
						$_show_void_btn = false;
					}
					// fatch items API
					//$_API_SHOPS = $this->_master['items'];
					$this->component_api->SetConfig("url", $this->config->item('URL_ITEMS'));
					$this->component_api->CallGet();
					$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
					$_API_ITEMS = !empty($_API_ITEMS['query']) ? $_API_ITEMS['query'] : "";
					
					// fatch shop code and shop detail API
					//$_API_SHOPS = $this->_master['shops'];
					$this->component_api->SetConfig("url", $this->config->item('URL_SHOP'));
					$this->component_api->CallGet();
					$_API_SHOPS = json_decode($this->component_api->GetConfig("result"), true);
					$_API_SHOPS = !empty($_API_SHOPS['query']) ? $_API_SHOPS['query'] : "";
					
					// fatch customer API
					//$_API_CUSTOMERS = $this->_master['customers'];
					$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS'));
					$this->component_api->CallGet();
					$_API_CUSTOMERS = json_decode($this->component_api->GetConfig("result"), true);
					$_API_CUSTOMERS = !empty($_API_CUSTOMERS['query']) ? $_API_CUSTOMERS['query'] : "";
					
					// fatch payment method API
					//$_API_PAYMENTS = $this->_master['paymentmethods'];
					$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
					$this->component_api->CallGet();
					$_API_PAYMENTS = json_decode($this->component_api->GetConfig("result"),true);
					$_API_PAYMENTS = !empty($_API_PAYMENTS['query']) ? $_API_PAYMENTS['query'] : "";

					// function bar with next, preview and save button
					$this->load->view('function-bar', [
						"btn" => [
							["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/invoices/list'.$_login['preference']), "style" => "", "show" => true],
							["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => $_show_next_btn],
							["name" => "<i class='far fa-copy'></i> ".$this->lang->line("function_copy"), "type"=>"button", "id" => "copy", "url"=> base_url('/invoices/copy/'.$_invoice_num), "style" => "btn btn-dark", "show" => $_show_copy_btn],
							["name" => "<i class='fas fa-eraser'></i> ".$this->lang->line("function_void"), "type"=>"button", "id" => "discard", "url"=> base_url('/invoices/void/'.$_invoice_num), "style" => "btn btn-danger", "show" => $_show_void_btn]
						]
					]);
					
					$this->load->view('title-bar', [
						"title" => $this->lang->line("invoice_edit_titles")
					]);
			
					//show edit view
					$this->load->view("invoices/invoices-edit-view", [
						"submit_to" => base_url("/invoices/tender"),
						"prefix" => $this->_inv_header_param['topNav']['prefix'],
						"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
						"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
						"quotation" => "",
						"invoice_num" => $_invoice_num,
						"date" => date("Y-m-d H:i:s"),
						"ajax" => [
							"items" => $_API_ITEMS,
							"shop_code" => $_API_SHOPS,
							"customers" => $_API_CUSTOMERS,
							"tender" => $_API_PAYMENTS
						],
						"data" => $_transaction['query'],
						"show" => $_show_void_btn,
						"default_per_page" => $this->_default_per_page,
						"function_bar" => $this->load->view('function-bar', [
							"btn" => [
								["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "new", "url"=>base_url('/customers/?new=1'), "style" => "", "show" => true]
							]
						],true)
					]);
					$this->load->view("footer");
				}
				else
				{
					redirect(base_url("invoices/list/"),"refresh");
				}
			}
		}
	}
	/**
	 * Tender Process
	 * To proceed tender information before save
	 */
	public function tender()
	{
		if(isset($_POST["i-post"]))
		{
			// variable initial
			$_data = json_decode($_POST['i-post'], true);
			$_transaction = [];
			$_cur_invoicenum = $_data['trans_code'];
			$_show_save_btn = false;
			$_show_reprint_btn = false;

			$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS').$_data['cust_code']);
			$this->component_api->CallGet();
			$_API_CUSTOMER = json_decode($this->component_api->GetConfig("result"),true);
			$_API_CUSTOMER = !empty($_API_CUSTOMER['query']) ? $_API_CUSTOMER['query'] : "";

			$_transaction[$_cur_invoicenum] = $_data;
			$_transaction[$_cur_invoicenum]['customer'] = $_API_CUSTOMER;
			$this->session->set_userdata('cur_invoicenum',$_cur_invoicenum);
			$this->session->set_userdata('transaction',$_transaction);

			// show save button
			if(isset($_transaction[$_cur_invoicenum]['void']))
			{
				if(filter_var($_transaction[$_cur_invoicenum]['void'], FILTER_VALIDATE_BOOLEAN))
				{
					$_show_save_btn = true;
				}
			}

			switch($_data['formtype'])
			{
				case "edit":
					$_show_reprint_btn = true;
					$_the_form_type = "saveedit";
				break;
				case "create":
					$_show_reprint_btn = false;
					$_the_form_type = "save";
				break;
			}
			// echo "<pre>";
			// var_dump($_transaction);
			// echo "</pre>";
			
			// function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/invoices/'.$_data['formtype'].'/'.$_data['trans_code']."/".$_data['quotation']) ,"style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url("/invoices/".$_the_form_type) , "style" => "btn btn-primary", "show" => $_show_save_btn],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn]
				]
			]);
			// render view
			$this->load->view("invoices/invoices-tender-view", [
				"data" => $_transaction[$_cur_invoicenum],
				"preview_url" => base_url('/ThePrint/invoices/preview'),
				"print_url" => base_url('/ThePrint/invoices/save')
			]);
			$this->load->view("footer");
		}
	}
	/**
	 * Save Create Process
	 * To save new invoice creation
	 */
	 public function save()
	 {
		 $_cur_invoicenum = $this->session->userdata('cur_invoicenum');
		 $_transaction = $this->session->userdata('transaction');
		 $alert = "danger";
		 $this->load->view('function-bar', [
			 "btn" => [
				 ["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/invoices/donew'),"style" => "","show" => true],
			 ]
		 ]);
		 if(!empty($_transaction[$_cur_invoicenum]) && isset($_transaction[$_cur_invoicenum]))
		 {
			$_api_body = json_encode($_transaction[$_cur_invoicenum],true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";

			if($_api_body != null)
			{
				// save invoice 
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY'));
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				if(isset($result["error"]['code']))
				{
					switch($result["error"]['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}
					$this->load->view('error-handle', [
						'message' => $result["error"]['message'], 
						'code'=> $result["error"]['code'], 
						'alertstyle' => $alert
					]);
				}
				else
				{
					$result["error"]['code'] = "99999";
					$result["error"]['message'] = "API-Error"; 
				}

				echo "<pre>";
				var_dump($result);
				echo "</pre>";

				// create DN
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE'));
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
				if(isset($result["error"]['code']))
				{
					switch($result["error"]['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}
					$this->load->view('error-handle', [
						'message' => $result["error"]['message'], 
						'code'=> $result["error"]['code'],
						'alertstyle' => $alert
					]);
				}
				else
				{
					$result["error"]['code'] = "99999";
					$result["error"]['message'] = "API-Error";
				}
				
				echo "<pre>";
				var_dump($result);
				echo "</pre>";

				
				unset($_transaction[$_cur_invoicenum]);
				$this->session->set_userdata('cur_quotationnum',"");
				$this->session->set_userdata('transaction',$_transaction);

				//header("Refresh: 10; url='list/'");
			}
		}
		else
		{
			$alert = "danger";
			$result["error"]['code'] = "90000";
			$result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again"; 
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
	 }
	/**
	 * Save Edit Process
	 * To save Invoice edit
	 */
	public function saveedit()
	{
		$_cur_invoicenum = $this->session->userdata('cur_invoicenum');
		$_transaction = $this->session->userdata('transaction');
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "donew", "url"=> base_url('/invoices/donew'),"style" => "","show" => true],
			]
		]);
		if(!empty($_cur_invoicenum))
		{
			$_api_body = json_encode($_transaction[$_cur_invoicenum],true);
			// echo $_cur_invoicenum;
			// echo "<pre>";
			// echo ($_api_body);
			// echo "</pre>";
			if($_api_body != null)
			{
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY').$_cur_invoicenum);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);
			
			// echo "<pre>";
			// var_dump($result);
			// echo "</pre>";
				if(isset($result["error"]['code']))
				{
					$alert = "danger";
					switch($result["error"]['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}					
					
					$this->load->view('error-handle', [
						'message' => $result["error"]['message'], 
						'code'=> $result["error"]['code'], 
						'alertstyle' => $alert
					]);
					unset($_transaction[$_cur_invoicenum]);
					$this->session->set_userdata('cur_invoicenum',"");
					$this->session->set_userdata('transaction',$_transaction);
					header("Refresh: 10; url='list/'");
				}
			}
		}
	}
	/**
	* Save void operation
	* To remove invoice from the list
	* @param num invoice number selected to be voided
	*/
	public function savevoid($_num = "")
	{
		$_login = $this->session->userdata('login');
		$_transaction = $this->session->userdata('transaction');

		$this->load->view('function-bar', [
			"btn" => [
				[
					"name" => "<i class='fas fa-chevron-left'></i> Back",
					"type"=>"button",
					"id" => "back", 
					"url"=> base_url('/invoices/list'.$_login["preference"]),
				 	"style" => "",
				 	"show" => true
				]
			]
		]);
		if(!empty($_num))
		{
			if(!empty($_transaction) && isset($_transaction))
			{
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_NEXT_NUM'));
				$this->component_api->CallGet();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				$_transaction['adj_num'] = !empty($result['query']) ? $result['query'] : "";
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_PREFIX'));
				$this->component_api->CallGet();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				$_transaction['prefix'] = !empty($result['query']) ? $result['query'] : "";
				$_transaction['remark'] = "Stock ajustment - Void";
 				$_api_body = json_encode($_transaction,true);
				// echo "<pre>";
				// var_dump($_api_body);
				// echo "</pre>";
				// echo "<pre>";
				// var_dump($_transaction);
				// echo "</pre>";
				$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY').$_num);
				$this->component_api->CallDelete();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				if(isset($result["error"]))
				{
					switch($result["error"]['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}
					$this->load->view('error-handle', [
						'message' => $result["error"]['message'], 
						'code'=> $result["error"]['code'], 
						'alertstyle' => $alert
					]);
				}

				//Get next Ajustment number
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ'));
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				if(isset($result["error"]))
				{
					$alert = "danger";

					switch($result["error"]['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}					
					
					$this->load->view('error-handle', [
						'message' => $result["error"]['message'], 
						'code'=> $result["error"]['code'], 
						'alertstyle' => $alert
					]);
					$this->session->set_userdata('cur_invoicenum',"");
					$this->session->set_userdata('transaction',[]);
					header("Refresh: 10; url='".base_url('/invoices/list'.$_login["preference"])."'");
				}
			}
		}
	}
	/**
	 * Discard Operation
	 * To discard Invoice 
	 */
	public function discard()
	{
		$_cur_invoicenum = $this->session->userdata('cur_invoicenum');
		$_transaction = $this->session->userdata('transaction');
		unset($_SESSION['cur_invoicenum']);
		unset($_transaction[$_cur_invoicenum]);
		redirect(base_url("invoices/donew"),"refresh");
	}
	/**
	 * Void Operation
	 * @param _num Invoice number user's selected to be voided 
	 */
	public function void($_num = "")
	{
		$this->load->view("invoices/invoices-void-view", [
			"submit_to" => base_url("invoices/void/confirmed/".$_num),
			"to_deleted_num" => $_num,
			"return_url" => base_url("invoices/edit/".$_num)
		]);
	}

}
