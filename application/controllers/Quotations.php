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
			$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];
			// API data
			// $this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES').$this->_profile['username']);
			// $this->component_api->CallGet();
			// $_API_EMP = $this->component_api->GetConfig("result");
			// $_API_EMP = !empty($_API_EMP['query']) ? $_API_EMP['query'] : ['username' => "", 'employee_code' => ""];
			// $this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$this->_profile['shopcode']);
			// $this->component_api->CallGet();
			// $_API_SHOP = $this->component_api->GetConfig("result");
			// $_API_SHOP = !empty($_API_SHOP['query']) ? $_API_SHOP['query'] : ['shop_code' => "", 'name' => ""];
			// $this->component_api->SetConfig("url", $this->config->item('URL_MENU_SIDE'));
			// $this->component_api->CallGet();
			// $_API_MENU = $this->component_api->GetConfig("result");
			// $_API_MENU = !empty($_API_MENU['query']) ? $_API_MENU['query'] : [];
			// $this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS_PREFIX'));
			// $this->component_api->CallGet();
			// $_API_PREFIX = $this->component_api->GetConfig("result");
			// $_API_PREFIX = !empty($_API_PREFIX['query']) ? $_API_PREFIX['query'] : [];
			
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
					["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=> base_url("quotations/donew/"), "style" => "", "show" => true, "extra" => ""]
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
				"url" => base_url("quotations/edit/"),
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
	 * Quotation Number Generation
	 * To generate new quotation number
	 *
	 */
	public function donew()
	{
		if(!empty($this->session->userdata('transaction')))
		{
			$this->session->unset_userdata('transaction');
		}
		$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API_NEXT = $this->component_api->GetConfig("result");
		$_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
		// echo "<pre>";
		// var_dump($_API_NEXT);
		// echo "</pre>";
		redirect(base_url("quotations/create/".$_API_NEXT),"refresh");
	}
	/**
	 * Quotation Copy 
	 * Copy operation 
	 * @param _old_num is quotation number which want to be cloned
	 */
	public function docopy($_old_num)
	{
		$_transaction = [];
		if(!empty($this->session->userdata('transaction')))
		{
			$this->session->unset_userdata('transaction');
		}
		//fatch existing transaction
		$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS').$_old_num);
		$this->component_api->CallGet();
		$_API_QTA = json_decode($this->component_api->GetConfig("result"),true);
		$_API_QTA = !empty($_API_QTA['query']) ? $_API_QTA['query'] : "";
		// get next Invoice number
		$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API_NEXT = $this->component_api->GetConfig("result");
		$_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
		// transaction retrieve
		$_transaction[$_API_NEXT] = $_API_QTA;
		$_transaction[$_API_NEXT]['date'] = date("Y-m-d H:i:s");
		$this->session->set_userdata('cur_quotationnum',$_API_NEXT);
		$this->session->set_userdata('transaction',$_transaction);
		redirect(base_url("quotations/create/".$_API_NEXT),"refresh");
	}
	/**
	 * Create Process
	 * To create new quotation transaction
	 * @param _num quotation number
	 */
	public function create($_num = "")
	{
		// variable initial
		$_show_discard_btn = false;
		$_transaction = [];

		if(!empty($_num))
		{
			$_show_discard_btn = true;

			// create new quotation
		
			// For back button after submit to tender page
			if(!empty($this->session->userdata('transaction')) && !empty($this->session->userdata('cur_quotationnum')))
			{
				$_num = $this->session->userdata('cur_quotationnum');
				$_transaction = $this->session->userdata('transaction');
				$_transaction[$_num]['quotation'] = $_num;
			}
			// For new create
			else 
			{
				$this->session->set_userdata('cur_quotationnum',$_num);
				$this->session->set_userdata('transaction',$_transaction);
				$_transaction[$_num]['items'] = [];
				$_transaction[$_num]['cust_code'] = "";
				$_transaction[$_num]['cust_name'] = "";
				$_transaction[$_num]['paymentmethod'] = "";
				$_transaction[$_num]['paymentmethodname'] = "";
				$_transaction[$_num]['remark'] = "";
			}
			// fatch items API
			$this->component_api->SetConfig("url", $this->config->item('URL_MASTER'));
			$this->component_api->CallGet();
			$_API_MASTER = $this->component_api->GetConfig("result");

			if(empty($_API_MASTER['query']))
			{
				$_API_MASTER['items'] = [];
				$_API_MASTER['shops'] = [];
				$_API_MASTER['customers'] =[];
				$_API_MASTER['paymentmethod'] = [];
			}
			else
			{
				$_API_MASTER = $_API_MASTER['query'];
			}
					
			
			// var_dump($_theprint_data);
			// function bar with next, preview and save button
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/quotations/discard'), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);
			$this->load->view('title-bar', [
				"title" => $this->lang->line("quotation_new_titles")
			]);
			// present form view
			$this->load->view('quotations/quotations-create-view', [
				"submit_to" => base_url("/quotations/tender"),
				"prefix" => $this->_inv_header_param['topNav']['prefix'],
				"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"quotation" => $_num,
				"date" => date("Y-m-d H:i:s"),
				"ajax" => [
					"items" => $_API_MASTER['items'],
					"shop_code" => $_API_MASTER['shops'],
					"customers" => $_API_MASTER['customers'],
					"tender" => $_API_MASTER['paymentmethod']
				],
				"data" => $_transaction[$_num],
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
	public function edit($_num="")
	{
		// variable initial
		$_transaction = [];
		$_show_void_btn = false;
		$_show_convert_btn = false;

		if(!empty($_num))
		{
			// Check Quotation exist
			$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS').$_num);
			$this->component_api->CallGet();
			$_transaction = $this->component_api->GetConfig("result");
			$_transaction = $_transaction != null ? $_transaction : "";

			// set current invoice number to session
			$this->session->set_userdata('cur_quotationnum',$_num);
			$this->session->set_userdata('transaction',$_transaction['query']);
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
					if(empty($_API_MASTER['query']))
					{
						$_API_MASTER['items'] = [];
						$_API_MASTER['shops'] = [];
						$_API_MASTER['customers'] =[];
						$_API_MASTER['paymentmethod'] = [];
					}
					else
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
							["name" => "<i class='fas fa-exchange-alt'></i> ".$this->lang->line("quotation_convert_to_invoice"), "type"=>"button", "id" => "convert", "url"=> base_url('/invoices/convert/'.$_num), "style" => "btn btn-success", "show" => $_show_convert_btn],
							["name" => "<i class='far fa-copy'></i> ".$this->lang->line("function_copy"), "type"=>"button", "id" => "copy", "url"=> base_url('/quotations/copy/'.$_num), "style" => "btn btn-dark", "show" => true],
							["name" => "<i class='fas fa-eraser'></i> ".$this->lang->line("function_cancel"), "type"=>"button", "id" => "discard", "url"=> base_url('/quotations/void/'.$_num), "style" => "btn btn-danger", "show" => $_show_void_btn]
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
						"quotation" => $_num,
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
					//redirect(base_url("quotations/list/"),"refresh");
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
			$_cur_num = $this->session->userdata('cur_quotationnum');
			$_show_save_btn = false;
			$_show_reprint_btn = false;

			// API Call
			$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS').$_data['cust_code']);
			$this->component_api->CallGet();
			$_API_CUSTOMER = $this->component_api->GetConfig("result");
			$_API_CUSTOMER = !empty($_API_CUSTOMER['query']) ? $_API_CUSTOMER['query'] : "";
			// Append API result to transaction array
			$_transaction[$_cur_num]= $_data;
			$_transaction[$_cur_num]['customer']['name'] = $_API_CUSTOMER['name'];
			$_transaction[$_cur_num]['customer']['delivery_addr'] = $_API_CUSTOMER['delivery_addr'];
			// save print data to session
			$this->session->set_userdata('cur_invoicenum',$_cur_num);
			$this->session->set_userdata('transaction',$_transaction);

			// show save button
			if(isset($_transaction[$_cur_num]['void']))
			{
				if(filter_var($_transaction[$_cur_num]['void'], FILTER_VALIDATE_BOOLEAN))
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
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/quotations/'.$_data['formtype'].'/'.$_data['quotation']) ,"style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url("/quotations/".$_the_form_type), "style" => "","show" => $_show_save_btn],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn]
				]
			]);
			// render view
			$this->load->view("quotations/quotations-tender-view", [
				"data" => $_transaction[$_cur_num],
				"preview_url" => base_url('/ThePrint/quotations/preview'),
				"print_url" => base_url('/ThePrint/quotations/save')
			]);
			$this->load->view("footer");
		}
	}
	/**
	 * Save Create Process
	 * To save new quotation creation
	 */
	public function save()
	{
		$_cur_num = $this->session->userdata('cur_quotationnum');
		$_transaction = $this->session->userdata('transaction');
		// echo "<pre>";
		// var_dump($_transaction);
		// echo "</pre>";

		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/quotations/donew'),"style" => "","show" => true],
			]
		]);
		if(!empty($_cur_num))
		{
			$_api_body = json_encode($_transaction[$_cur_num],true);
			// echo "<pre>";
			// echo ($_api_body);
			// echo "</pre>";

			if($_api_body != null)
			{
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS'));
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);

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
					unset($_transaction[$_cur_num]);
					$this->session->set_userdata('cur_quotationnum',"");
					$this->session->set_userdata('transaction',$_transaction);
					
					header("Refresh: 10; url='donew/'");
				}
				else
				{
					$alert = "danger";
					$result["error"]['code'] = "99999";
					$result["error"]['message'] = "API-Error"; 
						
					$this->load->view('error-handle', [
						'message' => $result["error"]['message'], 
						'code'=> $result["error"]['code'], 
						'alertstyle' => $alert
					]);
					unset($_transaction[$_cur_num]);
					$this->session->set_userdata('cur_quotationnum',"");
					$this->session->set_userdata('transaction',$_transaction);
				}
			}
			
		}
	}
	/**
	 * Save Edit Process
	 * To save quotation edit
	 */
	public function saveedit()
	{
		// session
		$_cur_num = $this->session->userdata('cur_quotationnum');
		$_transaction = $this->session->userdata('transaction');
		// echo "<pre>";
		// var_dump($_cur_num);
		// echo "</pre>";

		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "donew", "url"=> base_url('/quotations/donew'),"style" => "","show" => true],
			]
		]);
		if(!empty($_cur_num))
		{
			$_api_body = json_encode($_transaction[$_cur_num],true);
			// echo $_cur_invoicenum;

			// echo ($_api_body);
	
			if($_api_body != null)
			{
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS').$_cur_num);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);

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

					unset($_transaction[$_cur_num]);
					$this->session->set_userdata('cur_invoicenum',"");
					$this->session->set_userdata('transaction',$_transaction);
					header("Refresh: 10; url='list/'");
				}
			}
		}
	}
	/**
	 * Save void quotation
	 * To confirm void quotation 
	 * @param _num quotation number
	 */
	public function savevoid($_num="")
	{
		$_login = $this->session->userdata('login');
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> Back", "type"=>"button", "id" => "back", "url"=> base_url('/quotations/list'.$_login["preference"]),"style" => "","show" => true],
			]
		]);
		if(!empty($_num))
		{
			$this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS').$_num);
			$this->component_api->CallDelete();
			$result = json_decode($this->component_api->GetConfig("result"),true);

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
				$this->session->set_userdata('cur_invoicenum',"");
				$this->session->set_userdata('transaction',[]);
				header("Refresh: 10; url='".base_url('/quotations/list'.$_login["preference"])."'");
			}
		}
	}
	/**
	 * Discard quotation while creating
	 * 
	 */
	public function discard()
	{
		//unset($_SESSION['cur_invoicenum']);
		$_cur_quotationnum = $this->session->userdata('cur_quotationnum');
		$_transaction = $this->session->userdata('transaction');
		unset($_SESSION['cur_quotationnum']);
		unset($_transaction[$_cur_quotationnum]);
		redirect(base_url("quotations/donew"),"refresh");
	}
	/**
	 * Void Quotation Process
	 * To delete quotation
	 * @param _num quotation number
	 */
	public function void($_num = "")
	{
		$this->load->view("quotations/quotations-void-view", [
			"submit_to" => base_url("quotations/void/confirmed/".$_num),
			"to_deleted_num" => $_num,
			"return_url" => base_url("quotations/edit/".$_num)
		]);
	}

}