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

		// load library
		$this->load->library("Component_Login",[$this->_token, "invoices/list"]);
		
		// // login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_INVOICES_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];
			
			// echo "<pre>";
			// var_dump($_API_HEADER);
			// echo "/<pre>";

			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "invoices/donew":
					$this->_param = "invoices/index";
				break;
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
		
		// $this->component_transactions->Remove("b44872241c450018c4be7a37369790351db34c82");

		if(empty($_GET['i-start-date']) && empty($_GET['i-end-date']))
		{
			$_GET['i-start-date'] = date("Y-m-d", strtotime('-5 days'));
			$_GET['i-end-date'] = date("Y-m-d");
		}

		$_query = [
			'i-invoice-num' => $this->input->get("i-invoice-num"),
			'i-cust-code' => $this->input->get('i-cust-code'),
			'i-start-date' => $this->input->get('i-start-date'),
			'i-end-date' => $this->input->get('i-end-date'),
			'page' => htmlspecialchars($this->_page),
			'show' => htmlspecialchars($this->_default_per_page)
		];
		if(!empty($_query))
		{
			//Set user preference
			if(!empty($_query['i-invoice-num'])){
				$_query['i-start-date'] = $_query['i-end-date'];
			}
			$_q_str = $this->component_uri->QueryToString($_query);
			$_login = $this->session->userdata['login'];
			$_login['preference'] = $_q_str;
			$this->session->set_userdata("login", $_login);
			
			// fatch items API
			if(!empty($_query['i-cust-code']))
			{
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY')."getlast/cust/".$_query['i-cust-code']);
			}
			else
			{
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY').$_q_str);
			}
			$this->component_api->CallGet();
			$_data = $this->component_api->GetConfig("result");
		}
		// echo "<pre>";
		// var_dump($_data);
		// echo "</pre>";
		if(!$_data['error']['code'] == "00000")
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
					["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=> base_url("/router/invoices/create/"), "style" => "", "show" => true, "extra" => ""]
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
				"data" => $_data['query'],
				"submit_to" => base_url("/invoices/list"),
				"edit_url" => base_url("/router/invoices/edit/"),
				"quotation_url" => base_url("quotations/edit/"),
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"ad_start_date" => $_query['i-start-date'],
				"ad_end_date" => $_query['i-end-date'],
				"ad_invoice_num" => $_query['i-invoice-num'],
				"ad_cust_code" => $_query['i-cust-code']
			]);

			$this->load->view("footer");
		}
	}
	
	/**
	 * Create Process
	 * To create new inovice transaction
	 * @param _invoice_num Invoice number
	 * @param _quotation_num Quotation number
	 */
	public function create($_session_id = "", $_invoice_num = "", $_quotation_num = "")
	{
		// variable initial
		$_show_discard_btn = false;
		$_API_MASTER = ['items' => "", 'shops' => "", 'customers'=> "", 'paymentmethod' => ""];

		if(!empty($_session_id) && !empty($_invoice_num))
		{
			$_transaction = [
				"items" => [],
				"quotation" => "",
				"cust_code" => "",
				"cust_name" => "",
				"paymentmethod" => "",
				"paymentmethodname" => "",
				"remark" => "", 
				"invoice_num" => $_invoice_num
			];
			$_show_discard_btn = true;
			
			$_data = $this->session->userdata($_session_id);
			// create invoice	
			// For back button after submit to tender page
			if( isset( $_data[$_invoice_num] ) && !empty( $_data[$_invoice_num] ) )
			{
				$_transaction = $_data[$_invoice_num];
				$_transaction['invoice_num'] = $_invoice_num;
				$_transaction['prefix'] = $this->_inv_header_param["topNav"]['prefix'];
				$this->session->set_flashdata($_session_id, $_transaction);
			}
			// For new create
			else
			{
				$_data[$_invoice_num] = $_transaction;
				$this->session->set_flashdata($_session_id, $_data);
			}
		// echo "<pre>";
		// var_dump($_SESSION);
		// echo "</pre>";

			// fatch items API
			$this->component_api->SetConfig("url", $this->config->item('URL_MASTER'));
			$this->component_api->CallGet();
			$_API_MASTER = $this->component_api->GetConfig("result");

			if(!empty($_API_MASTER['query']))
			{
				$_API_MASTER = $_API_MASTER['query'];
			}

			// function bar with next, preview and save button
			$this->load->view('function-bar', [

				"btn" => [
					["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/router/invoices/discard/'.$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);
			$this->load->view('title-bar', [
				"title" => $this->lang->line("invoice_new_titles")
			]);
			// present form view
			$this->load->view('invoices/invoices-create-view', [
				"submit_to" => base_url("/invoices/tender/".$_session_id),
				"discard_url" => base_url("/router/invoices/discard/".$_session_id),
				"prefix" => $this->_inv_header_param['topNav']['prefix'],
				"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"quote_fetch_url" => $this->config->item('URL_QUOTATIONS')."getinfo",
				"quote_item_fetch_url" => $this->config->item('URL_INVENTORY')."getinfo",
				"date" => date("Y-m-d H:i:s"),
				"dn_num" => $this->_API_HEADER['dn']['dn_num'],
				"dn_prefix" => $this->_API_HEADER['dn']['dn_prefix'],
				"ajax" => [
					"items" => $_API_MASTER['items'],
					"shop_code" => $_API_MASTER['shops'],
					"customers" => $_API_MASTER['customers'],
					"tender" => $_API_MASTER['paymentmethod']
				],
				"data" => $_transaction,
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
	public function edit($_session_id = "", $_invoice_num = "")
	{
		// variable initial
		$_transaction = [];
		$_show_void_btn = false;
		$_show_next_btn = true;
		$_show_copy_btn = true;
		$_API_MASTER = ['items' => "", 'shops' => "", 'customers'=> "", 'paymentmethod' => ""];

		if(!empty($_invoice_num))
		{
			$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY').$_invoice_num);
			$this->component_api->CallGet();
			$_transaction = $this->component_api->GetConfig("result");
			$_transaction = $_transaction != null ? $_transaction : "";

			$_data[$_invoice_num] = $_transaction['query'];
			$_data['cur_invoicenum'] = $_invoice_num;
			$this->session->set_flashdata($_session_id, $_data);

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
					$this->component_api->SetConfig("url", $this->config->item('URL_MASTER'));
					$this->component_api->CallGet();
					$_API_MASTER = $this->component_api->GetConfig("result");
					if(!empty($_API_MASTER['query']))
					{
						$_API_MASTER = $_API_MASTER['query'];
					}
					// echo "<pre>";
					// var_dump($_API_MASTER);
					// echo "</pre>";
					// function bar with next, preview and save button
					$this->load->view('function-bar', [
						"btn" => [
							["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/invoices/list'.$_login['preference']), "style" => "", "show" => true],
							["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => $_show_next_btn],
							["name" => "<i class='far fa-copy'></i> ".$this->lang->line("function_copy"), "type"=>"button", "id" => "copy", "url"=> base_url('/router/invoices/copy/'.$_session_id), "style" => "btn btn-dark", "show" => $_show_copy_btn],
							["name" => "<i class='fas fa-eraser'></i> ".$this->lang->line("function_void"), "type"=>"button", "id" => "void", "url"=> base_url('/invoices/void/'.$_session_id.'/'.$_invoice_num), "style" => "btn btn-danger", "show" => $_show_void_btn],
						]
					]);
					
					$this->load->view('title-bar', [
						"title" => $this->lang->line("invoice_edit_titles")
					]);
			
					//show edit view
					$this->load->view("invoices/invoices-edit-view", [
						"submit_to" => base_url("/invoices/tender/".$_session_id),
						"prefix" => $this->_inv_header_param['topNav']['prefix'],
						"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
						"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
						// "invoice_num" => $_invoice_num,
						"date" => date("Y-m-d H:i:s"),
						"ajax" => [
							"items" => $_API_MASTER['items'],
							"shop_code" => $_API_MASTER['shops'],
							"customers" => $_API_MASTER['customers'],
							"tender" => $_API_MASTER['paymentmethod']
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
					redirect(base_url("invoices/list/"),"auto");
				}
			}
		}
	}
	/**
	 * Tender Process
	 * To proceed tender information before save
	 */
	public function tender($_session_id)
	{
		if(isset($_POST["i-post"]))
		{
			// variable initial
			$_data = json_decode($_POST['i-post'], true);
			$_transaction = [];
			$_cur_invoicenum = $_data['trans_code'];
			$_show_save_btn = false;
			$_show_reprint_btn = false;
			$_show_discard_btn = true;

			$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS').$_data['cust_code']);
			$this->component_api->CallGet();
			$_API_CUSTOMER = $this->component_api->GetConfig("result");
			$_API_CUSTOMER = !empty($_API_CUSTOMER['query']) ? $_API_CUSTOMER['query'] : "";

			$_transaction = $_data;
			$_transaction['customer']['name'] = $_API_CUSTOMER['name'];
			$_transaction['customer']['delivery_addr'] = $_API_CUSTOMER['delivery_addr'];
			$_transaction['customer']['statement_remark'] = $_API_CUSTOMER['statement_remark'];

			$_sess[$_cur_invoicenum] = $_transaction;
			$_sess['cur_invoicenum'] = $_cur_invoicenum;
			$this->session->set_flashdata($_session_id, $_sess);
			// echo "<pre>";
			// var_dump($_sess);
			// echo "</pre>";
			
			// show save button
			if(isset($_transaction['void']))
			{
				if($_transaction['void'])
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

			
			// function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/invoices/'.$_data['formtype']."/".$_session_id.'/'.$_data['trans_code']."/".$_data['quotation']) ,"style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url("/invoices/".$_the_form_type."/".$_session_id) , "style" => "btn btn-primary", "show" => $_show_save_btn],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/router/invoices/discard/'.$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);
			// render view
			$this->load->view("invoices/invoices-tender-view", [
				"data" => $_transaction,
				"discard_url" => base_url("/router/invoices/discard/".$_session_id),
				"preview_url" => base_url('/ThePrint/invoices/preview/'.$_session_id),
				"print_url" => base_url('/ThePrint/invoices/save/'.$_session_id)
			]);
			$this->load->view("footer");
		}
	}
	/**
	 * Save Create Process
	 * To save new invoice creation
	 */
	public function save($_session_id)
	{
		$_transaction = [];
		$_login = $this->session->userdata('login');
		$_data = $this->session->userdata($_session_id);
		// echo "<pre>";
		// var_dump($_data);
		// echo "</pre>";
		if(isset($_data) )
		{
			$_cur_invoicenum = $_data['cur_invoicenum'];
			$_transaction = $_data[$_cur_invoicenum];
		}

		$alert = "danger";
		$invoice_ok = true;
		$result = [];
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/invoices/list'.$_login['preference']) ,"style" => "","show" => true],
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/router/invoices/create/'),"style" => "","show" => true],
			]
		]);

		if(!empty($_transaction) && isset($_transaction))
		{
			$_api_body = json_encode($_transaction,true);
			
			//$_api_body = '{"trans_code":"INV220246","prefix":"INV","quotation":"QTA220203","employee_code":"12332","date":"2022-02-18 19:45:52","shopcode":"HQ01","dn_prefix":"DN","dn_num":"DN220200","cust_code":"C150402","cust_name":"Fantastic Cafe (\u67f4\u7063)","items":[{"item_code":"AG0405","eng_name":"\u9152\u7cbe\u6d88\u6bd2?\u55b1Hand Sanitizer Gel","chi_name":"AG Alcohol Gel","qty":2,"unit":"4X5L","price":"320.00","price_special":0,"subtotal":"640","stockonhand":"161"},{"item_code":"AG0120","eng_name":"\u9152\u7cbe\u6d88\u6bd2?\u55b1Hand Sanitizer Gel","chi_name":"AG Alcohol Gel","qty":1,"unit":"20 x 450ml","price":"500.00","price_special":0,"subtotal":"500","stockonhand":"122"}],"total":"1140.00","remark":"","paymentmethod":"PM002","shopname":"TeamWork Ltd","paymentmethodname":"Cheque","formtype":"create","void":"true","customer":{"name":"Fantastic Cafe (\u67f4\u7063)","delivery_addr":"\u9999\u6e2f\u67f4\u7063\u5229\u773e\u885724\u865f\u6771\u8cbf\u5ee3\u58346\/F"}}';
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";

			// save invoice 
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY'));
			$this->component_api->CallPost();
			$result = $this->component_api->GetConfig("result");
			// echo "<pre>";
			// var_dump($result);
			// echo "</pre>";

			switch($result["http_code"])
			{
				case 200:
					$alert = "success";
				break;
				case 404:
					$invoice_ok = false;
					$alert = "danger";
				break;
			}
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
			// Invoice cannot be create
			if($invoice_ok)
			{
				// create DN
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE'));
				$this->component_api->CallPost();
				$result = $this->component_api->GetConfig("result");
				// echo "<pre>";
				// var_dump($result);
				// echo "</pre>";
				switch($result["http_code"])
				{
					case 200:
						$alert = "success";
					break;
					case 404:
						$alert = "danger";
					break;
				}
				$this->load->view('error-handle', [
					'message' => $result["error"]['message'], 
					'code'=> $result["error"]['code'], 
					'alertstyle' => $alert
				]);
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
		$this->load->view("footer");
		header("Refresh: 10; url='".base_url('/invoices/list'.$_login["preference"])."'");
	}
	/**
	 * Save Edit Process
	 * To save Invoice edit
	 */
	public function saveedit($_session_id)
	{
		$alert = "danger";
		$_login = $this->session->userdata('login');
		$_data = $this->session->userdata($_session_id);
		if(isset($_data) )
		{
			$_cur_invoicenum = $_data['cur_invoicenum'];
			$_transaction = $_data[$_cur_invoicenum];
		}
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/invoices/list'.$_login['preference']), "style" => "", "show" => true],
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/router/invoices/create/'),"style" => "","show" => true],
			]
		]);
		if(!empty($_transaction) && isset($_transaction))
		{
			$_api_body = json_encode($_transaction,true);
			// echo $_cur_invoicenum;
			// echo "<pre>";
			// echo ($_api_body);
			// echo "</pre>";

			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY').$_cur_invoicenum);
			$this->component_api->CallPatch();
			$result = $this->component_api->GetConfig("result");
			// echo "<pre>";
			// var_dump($result);
			// echo "</pre>";
			switch($result["http_code"])
			{
				case 200:
					$alert = "success";
				break;
				case 404:
					$alert = "danger";
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
			$result["error"]['code'] = "90000";
			$result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again"; 
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}

		header("Refresh: 10; url='".base_url('/invoices/list'.$_login["preference"])."'");
	}
	/**
	* Save void operation
	* To remove invoice from the list
	* @param num invoice number selected to be voided
	*/
	public function savevoid($_session_id="")
	{
		$_login = $this->session->userdata('login');
		$_data = $this->session->userdata($_session_id);
		
		$_cur_invoicenum = $_data['cur_invoicenum'];
		$_transaction = $_data[$_cur_invoicenum];
		$alert = "danger";
		$invoice_ok = true;
		$result = [];
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/invoices/list'.$_login["preference"]), "style" => "", "show" => true]
			]
		]);

		if(!empty($_transaction) && isset($_transaction))
		{
			// echo "<pre>";
			// var_dump($_transaction);
			// echo "</pre>";
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_NEXT_NUM'));
			$this->component_api->CallGet();
			$result = $this->component_api->GetConfig("result");
			$_transaction['adj_num'] = !empty($result['query']) ? $result['query'] : "";
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_PREFIX'));
			$this->component_api->CallGet();
			$result = $this->component_api->GetConfig("result");
			$_transaction['prefix'] = !empty($result['query']) ? $result['query'] : "";
			$_transaction['remark'] = "Stock ajustment - Invoice Void";
			// echo "<pre>";
			// var_dump($_transaction);
			// echo "</pre>";
			$_api_body = json_encode($_transaction, true);

			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY').$_cur_invoicenum);
			$this->component_api->CallDelete();
			$result = $this->component_api->GetConfig("result");
			switch($result["http_code"])
			{
				case 200:
					$alert = "success";
				break;
				case 404:
					$invoice_ok = false;
					$alert = "danger";
				break;
			}		
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
			if($invoice_ok)
			{
				//Get next Ajustment number
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ'));
				$this->component_api->CallPost();
				$result = $this->component_api->GetConfig("result");
				switch($result["http_code"])
				{
					case 200:
						$alert = "success";
					break;
					case 404:
						$alert = "danger";
					break;
				}			
				$this->load->view('error-handle', [
					'message' => $result["error"]['message'], 
					'code'=> $result["error"]['code'], 
					'alertstyle' => $alert
				]);
			}
		}
		else
		{
			$result["error"]['code'] = "90000";
			$result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again"; 
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
		$this->load->view("footer");
		//header("Refresh: 10; url='".base_url('/invoices/list'.$_login["preference"])."'");
	}

	/**
	 * Void Operation
	 * @param _num Invoice number user's selected to be voided 
	 */
	public function void($_session_id = "", $_num = "")
	{
		$this->session->keep_flashdata($_session_id);
		$this->load->view("invoices/invoices-void-view", [
			"submit_to" => base_url("invoices/void/confirmed/".$_session_id),
			"to_deleted_num" => $_num,
			"return_url" => base_url("invoices/edit/".$_session_id."/".$_num)
		]);
		$this->load->view("footer");
	}

}
