<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotations extends CI_Controller 
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
		$this->load->library("Component_Login",[$this->_token, "quotations/list"]);

		// // login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];
			
			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "quotations/edit":
					$this->_param = "quotations/index";
				break;
				case "quotations/tender":
					$this->_param = "quotations/index";
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
				'title'=>'Quotations',
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
	 * A list of quotation
	 * 
	 */
	public function index()
	{
		// variable initial
		$_data = [];
		$_start_date = "";
		$_end_date = "";
		$_quotation_num = "";
		$_cust_code = "";

		if(empty($_GET['i-start-date']) && empty($_GET['i-end-date']))
		{
			$_GET['i-start-date'] = date("Y-m-d", strtotime('-5 days'));
			$_GET['i-end-date'] = date("Y-m-d");
		}
		$_query =$this->input->get();
		if(!empty($_query))
		{
			$_quotation_num = $this->input->get("i-quotation-num");
			$_start_date = $this->input->get('i-start-date');
			$_end_date = $this->input->get('i-end-date');
			$_cust_code = $this->input->get('i-cust-code');

			//Set user preference
			$_query['page'] = htmlspecialchars($this->_page);
			$_query['show'] = htmlspecialchars($this->_default_per_page);
			$_query['i-start-date'] = htmlspecialchars($_start_date);
			$_query['i-end-date'] = htmlspecialchars($_end_date);
			$_query['i-quotation-num'] = htmlspecialchars($_quotation_num);
			$_query['i-cust-code'] = htmlspecialchars($_cust_code);
			$_query = $this->component_uri->QueryToString($_query);
			$_login = $this->session->userdata['login'];
			$_login['preference'] = $_query;
			$this->session->set_userdata("login", $_login);

			if(!empty($_cust_code))
			{
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS')."getlast/cust/".$_cust_code);
			}
			else
			{
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS').$_query);
			}
			$this->component_api->CallGet();
			$_data = $this->component_api->GetConfig("result");

			// echo "<pre>";
			// var_dump($_data);
			// echo "</pre>";
		}
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
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=> base_url("/router/quotations/create/"), "style" => "", "show" => true, "extra" => ""]
				]
			]);
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-search'></i> ".$this->lang->line("function_search"), "type"=>"button", "id" => "i-search", "url"=> "#", "style" => "", "show" => true, "extra" => ""],
					["name" => "<i class='fas fa-undo-alt'></i> ".$this->lang->line("function_clear"), "type"=>"button", "id" => "i-clear", "url"=> "#", "style" => "btn btn-secondary", "show" => true, "extra" => ""]
				]
			]);
			$this->load->view("quotations/quotations-list-view", [
				'data' => $_data['query'],
				"submit_to" => base_url("/quotations/list"),
				"edit_url" => base_url("/router/quotations/edit/"),
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"ad_start_date" => $_start_date,
				"ad_end_date" => $_end_date,
				"ad_quotation_num" => $_quotation_num,
				"ad_cust_code" => $_cust_code
			]);
			$this->load->view("footer");
		}
	}

	/**
	 * Create Process
	 * To create new quotation transaction
	 * @param _num quotation number
	 */
	public function create($_session_id = "", $_num = "")
	{
		// variable initial
		$_show_discard_btn = false;
		$_transaction = [];
		$_API_MASTER = ['items' => "", 'shops' => "", 'customers'=> "", 'paymentmethod' => ""];

		if(!empty($_session_id) && !empty($_num))
		{
			$_transaction = [
				'items' => [],
				'cust_code' => "",
				'cust_name' => "",
				'paymentmethod' => "",
				'paymentmethodname' => "",
				'remark' => "",
				'quotation' => $_num
			];
			$_show_discard_btn = true;

			$_data = $this->session->userdata($_session_id);
			// create new quotation
			// For back button after submit to tender page

			if(isset($_data[$_num]) && !empty($_data[$_num]))
			{
				$_transaction = $_data[$_num];
				$_transaction['quotation'] = $_num;
				$this->session->set_flashdata($_session_id, $_transaction);
			}
			// For new create
			else 
			{
				$_data[$_num] = $_transaction;
				$this->session->set_flashdata($_session_id, $_data);
			}
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
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/router/quotations/discard/'.$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);
			$this->load->view('title-bar', [
				"title" => $this->lang->line("quotation_new_titles")
			]);
			// present form view
			$this->load->view('quotations/quotations-create-view', [
				"submit_to" => base_url("/quotations/tender/".$_session_id),
				"prefix" => $this->_inv_header_param['topNav']['prefix'],
				"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"date" => date("Y-m-d H:i:s"),
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
	 * To edit quotation information
	 * @param _num quotation number
	 *
	 */
	public function edit($_session_id = "",$_num="")
	{
		// variable initial
		$_transaction = [];
		$_show_void_btn = false;
		$_show_convert_btn = false;
		$_API_MASTER = ['items' => "", 'shops' => "", 'customers'=> "", 'paymentmethod' => ""];

		if(!empty($_num))
		{
			// Check Quotation exist
			$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS').$_num);
			$this->component_api->CallGet();
			$_transaction = $this->component_api->GetConfig("result");
			$_transaction = $_transaction != null ? $_transaction : "";

			$_data[$_num] = $_transaction['query'];
			$_data['cur_quotationnum'] = $_num;
			$this->session->set_flashdata($_session_id, $_data);

			if(!empty($_transaction))
			{
				$_login = $this->session->userdata('login');

				if($_transaction['has'])
				{
					if($_transaction['query']['is_convert'] == 0)
					{
						$_show_convert_btn = true;
						$_show_void_btn = true;
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
					// var_dump($_transaction['query']);
					// echo "</pre>";
					// function bar with next, preview and save button
					$this->load->view('function-bar', [
						"btn" => [
							["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/quotations/list'.$_login['preference']), "style" => "", "show" => true],
							["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
							["name" => "<i class='fas fa-exchange-alt'></i> ".$this->lang->line("quotation_convert_to_invoice"), "type"=>"button", "id" => "convert", "url"=> base_url('/router/invoices/convert/'.$_session_id), "style" => "btn btn-success", "show" => $_show_convert_btn],
							["name" => "<i class='far fa-copy'></i> ".$this->lang->line("function_copy"), "type"=>"button", "id" => "copy", "url"=> base_url('/router/quotations/copy/'.$_session_id), "style" => "btn btn-dark", "show" => true],
							["name" => "<i class='fas fa-eraser'></i> ".$this->lang->line("function_cancel"), "type"=>"button", "id" => "void", "url"=> base_url('/quotations/void/'.$_session_id.'/'.$_num), "style" => "btn btn-danger", "show" => $_show_void_btn],
						]
					]);

					$this->load->view('title-bar', [
						"title" => $this->lang->line("quotation_edit_titles")
					]);
					// show edit view
					$this->load->view('quotations/quotations-edit-view', [
						"function_bar" => $this->load->view('function-bar', [
							"btn" => [
								["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "new", "url"=>base_url('/customers/?new=1'), "style" => "", "show" => true]
							]
						],true),
						"submit_to" => base_url("/quotations/tender"),
						"prefix" => $this->_inv_header_param['topNav']['prefix'],
						"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
						"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
						"date" => date("Y-m-d H:i:s"),
						"ajax" => [
							"items" => $_API_MASTER['items'],
							"shop_code" => $_API_MASTER['shops'],
							"customers" => $_API_MASTER['customers'],
							"tender" => $_API_MASTER['paymentmethod']
						],
						"data" => $_transaction['query'],
						"show" => $_show_void_btn,
						"default_per_page" => $this->_default_per_page						
					]);
					$this->load->view('footer');
				}
				else
				{
					redirect(base_url("quotations/list/"),"auto");
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
			// var_dump($_POST["i-post"]);
			// variable initial
			$_data = json_decode($_POST['i-post'], true);
			$_transaction = [];
			$_cur_num = $_data['quotation'];
			$_show_save_btn = false;
			$_show_reprint_btn = false;
			$_show_discard_btn = true;

			// API Call
			$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS').$_data['cust_code']);
			$this->component_api->CallGet();
			$_API_CUSTOMER = $this->component_api->GetConfig("result");
			$_API_CUSTOMER = !empty($_API_CUSTOMER['query']) ? $_API_CUSTOMER['query'] : "";
			// Append API result to transaction array
			$_transaction = $_data;
			$_transaction['customer']['name'] = $_API_CUSTOMER['name'];
			$_transaction['customer']['delivery_addr'] = $_API_CUSTOMER['delivery_addr'];
			$_transaction['customer']['statement_remark'] = $_API_CUSTOMER['statement_remark'];
			// var_dump($_data);
			// save print data to session
			$_sess[$_cur_num] = $_transaction;
			$_sess['cur_quotationnum'] = $_cur_num;
			$this->session->set_flashdata($_session_id, $_sess);

			// show save button
			if(isset($_transaction['void']))
			{
				//if(filter_var($_transaction['void'], FILTER_VALIDATE_BOOLEAN))
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
		// echo "<pre>";
		// var_dump($_transaction[$_cur_num]);
		// echo "</pre>";
			
			// function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/quotations/'.$_data['formtype']."/".$_session_id.'/'.$_data['quotation']) ,"style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url("/quotations/".$_the_form_type."/".$_session_id), "style" => "","show" => $_show_save_btn],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/router/quotations/discard/'.$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);
			// render view
			$this->load->view("quotations/quotations-tender-view", [
				"data" => $_transaction,
				"preview_url" => base_url('/ThePrint/quotations/preview/'.$_session_id),
				"print_url" => base_url('/ThePrint/quotations/save/'.$_session_id)
			]);
			$this->load->view("footer");
		}
	}
	/**
	 * Save Create Process
	 * To save new quotation creation
	 */
	public function save($_session_id)
	{
		$_transaction = [];
		$_login = $this->session->userdata('login');
		$_data = $this->session->userdata($_session_id);
		if(isset($_data) )
		{
			$_cur_num = $_data['cur_quotationnum'];
			$_transaction = $_data[$_cur_num];
		}
		$alert = "danger";
		$result = [];
		// echo "<pre>";
		// var_dump($_transaction);
		// echo "</pre>";

		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/quotations/list'.$_login['preference']), "style" => "", "show" => true],
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/router/quotations/create/'),"style" => "","show" => true],
			]
		]);
		if(!empty($_transaction) && isset($_transaction))
		{
			$_api_body = json_encode($_transaction,true);
			
			// echo "<pre>";
			// echo ($_api_body);
			// echo "</pre>";

			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS'));
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
		header("Refresh: 10; url='".base_url('/quotations/list'.$_login["preference"])."'");
	}
	/**
	 * Save Edit Process
	 * To save quotation edit
	 */
	public function saveedit()
	{
		// session
		$alert = "danger";
		$_login = $this->session->userdata('login');
		$_cur_num = $this->session->userdata('cur_quotationnum');
        $_transaction = $this->component_transactions->Get($_cur_num);
		
		
		// echo "<pre>";
		// var_dump($_cur_num);
		// echo "</pre>";

		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/quotations/list'.$_login['preference']), "style" => "", "show" => true],
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/quotations/donew'),"style" => "","show" => true],
			]
		]);
		if(!empty($_cur_num))
		{
			$_api_body = json_encode($_transaction,true);
			// echo $_cur_invoicenum;

			// echo ($_api_body);
	

			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS').$_cur_num);
			$this->component_api->CallPatch();
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
		$this->component_transactions->Remove($_cur_num);
		unset($_SESSION['cur_quotationnum']);
		header("Refresh: 10; url='".base_url('/quotations/list/'.$_login["preference"])."'");
	}
	/**
	 * Save void quotation
	 * To confirm void quotation 
	 * @param _num quotation number
	 */
	public function savevoid($_num="")
	{
		$_login = $this->session->userdata('login');
		$_cur_num = $this->session->userdata('cur_quotationnum');

		$alert = "danger";
		$invoice_ok = true;
		$result = [];

		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/quotations/list'.$_login["preference"]),"style" => "","show" => true],
			]
		]);
		if(!empty($_num))
		{
			$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS').$_num);
			$this->component_api->CallDelete();
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

		unset($_SESSION['cur_quotationnum']);
		header("Refresh: 10; url='".base_url('/quotations/list'.$_login["preference"])."'");
	}

	/**
	 * Void Quotation Process
	 * To delete quotation
	 * @param _num quotation number
	 */
	public function void($_session_id = "",$_num = "")
	{
		$this->session->keep_flashdata($_session_id);
		$this->load->view("quotations/quotations-void-view", [
			"submit_to" => base_url("quotations/void/confirmed/".$_session_id),
			"to_deleted_num" => $_num,
			"return_url" => base_url("quotations/edit/".$_session_id."/".$_num)
		]);
		$this->load->view("footer");
	}

}