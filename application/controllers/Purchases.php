<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchases extends CI_Controller 
{
    var $_inv_header_param = [];
	var $_default_per_page = "";
	var $_page = "";
	var $_token = "";
	var $_profile = "";
	var $_param = "";
    var $_user_auth = ['create' => false, 'edit' => false, 'delete' => false];
    var $_API_HEADER;
	/**
	 * Purchases constructor
	 */
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
		$this->load->library("Component_Login",[$this->_token, "purchases/index"]);

		// // login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix" => "",];

			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "purchases/edit":
					$this->_param = "purchases/index";
				break;
				case "purchases/tender":
					$this->_param = "purchases/index";
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
				'title'=>'Purchases',
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
	 * List PO tranaction record
	 */
	public function index()
    {
        // variable initial
		$_data = [];

		if(empty($_GET['i-start-date']) && empty($_GET['i-end-date']))
		{
			$_GET['i-start-date'] = date("Y-m-d", strtotime('-'.$this->config->item('NUM_DATE_OF_SEARCH').' days'));
			$_GET['i-end-date'] = date("Y-m-d");
		}
		$_query = [
			'i-num' => $this->input->get("i-num"),
			'i-start-date' => $this->input->get('i-start-date'),
			'i-end-date' => $this->input->get('i-end-date'),
			'i-supp-code' => $this->input->get('i-supp-code'),
			'page' => htmlspecialchars($this->_page),
			'show' => htmlspecialchars($this->_default_per_page)
		];

		if(!empty($_query))
		{
			//Set user preference
			$_q_str = $this->component_uri->QueryToString($_query);
			$_login = $this->session->userdata['login'];
			$_login['preference'] = $_q_str;
			$this->session->set_userdata("login", $_login);

			if(!empty($_query['i-supp-code']))
			{
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER')."getlast/supp/".$_query['i-supp-code']);
			}
			else
			{
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER').$_q_str);
				//echo $this->config->item('URL_PURCHASES_ORDER').$_query;
			}
			$this->component_api->CallGet();
			$_data = $this->component_api->GetConfig("result");
		}
		// echo "<pre>";
		// var_dump($_data);
		// echo "</pre>";

		// Function bar
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=> base_url("/router/purchases/create/"), "style" => "btn btn-primary", "show" => true, "extra" => ""],
			]
		]);
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-search'></i> ".$this->lang->line("function_search"), "type"=>"button", "id" => "i-search", "url"=> "#", "style" => "", "show" => true, "extra" => ""],
				["name" => "<i class='fas fa-undo-alt'></i> ".$this->lang->line("function_clear"), "type"=>"button", "id" => "i-clear", "url"=> "#", "style" => "btn btn-secondary", "show" => true, "extra" => ""]
			]
		]);
		$this->load->view("purchases/purchases-list-view", [
			"data" => $_data['query'],
			"submit_to" => base_url("/purchases/order/"),
			"edit_url" => base_url("/router/purchases/edit/"),
			"default_per_page" => $this->_default_per_page,
			"page" => $this->_page,
			"ad_start_date" => $_query['i-start-date'],
			"ad_end_date" => $_query['i-end-date'],
			"ad_supp_code" => $_query['i-supp-code'],
			"ad_num" => $_query['i-num']
		]);

		$this->load->view("footer");
	}

	/**
	 * Create Process
	 * To create new Purchase transaction
	 * @param _session_id session ID
	 * @param _num purchase number
	 * @param _refer_num reference number
	 */
	public function create($_session_id = "", $_num = "", $_refer_num = "")
	{
		// variable initial
		$_show_discard_btn = false;
		$_login = $this->session->userdata('login');
		$_API_MASTER = ['items' => "", 'shops' => "", 'suppliers'=> "", 'paymentmethod' => ""];
		if(!empty($_session_id) && !empty($_num))
		{
			$_show_discard_btn = true;
			// Initial transaction array

			// read data from session
			$_sess = $this->session->userdata($_session_id);
			if( isset( $_sess[$_num] ) && !empty( $_sess[$_num] ) )
			{
				$_transaction = $_sess[$_num];
				$_transaction['purchases_num'] = $_num;
				$_transaction['prefix'] = $this->_inv_header_param["topNav"]['prefix'];
				$this->session->set_tempdata($_session_id, $_transaction, 600);
			}
			// For new create
			else
			{
				$_transaction = [
					'items' => [],
					'refer_num' => "",
					'supp_code' => "",
					'supp_name' => "",
					"paymentmethod" => "",
					"paymentmethodname" => "",
					"remark" => "",
					"purchases_num" => $_num,
				];
				$_sess[$_num] = $_transaction;
				$this->session->set_tempdata($_session_id, $_sess, 600);
			}

			// fatch items API
			$this->component_api->SetConfig("url", $this->config->item('URL_MASTER'));
			$this->component_api->CallGet();
			$result = $this->component_api->GetConfig("result");

			if(!empty($result['query']))
			{
				$_API_MASTER = $result['query'];
				// function bar with next, preview and save button
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/purchases/order/'.$_login['preference']), "style" => "", "show" => true],
						["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "btn btn-primary", "show" => true],
						["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/router/purchases/discard/'.$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn]
					]
				]);
				$this->load->view('title-bar', [
					"title" => $this->lang->line("purchase_order_new_title")
				]);
				// present form view
				$this->load->view('purchases/purchases-create-view', [
					"submit_to" => base_url("/purchases/order/process/".$_session_id),
					"discard_url" => base_url("/router/purchases/discard/".$_session_id),
					"prefix" => $this->_inv_header_param['topNav']['prefix'],
					"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
					"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
					"purchasesnum" => $_num,
					"date" => date("Y-m-d H:i:s"),
					"ajax" => [
						"items" => $_API_MASTER['items'],
						"shop_code" => $_API_MASTER['shops'],
						"suppliers" => $_API_MASTER['suppliers'],
						"tender" => $_API_MASTER['paymentmethod']
					],
					"data" => $_transaction,
					"default_per_page" => $this->_default_per_page,
					"function_bar" => $this->load->view('function-bar', [
						"btn" => [
							["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "new", "url"=>base_url('/suppliers/?new=1'), "style" => "", "show" => true]
						]
					],true)
				]);
			}
			// persent footer view
			$this->load->view('footer');
		}
	}

	/**
	 * Process
	 * To confirm Purchase transaction
	 * @param _session_id adjustments number
	 */
	public function confirm($_session_id = "")
	{
		if(isset($_POST["i-post"]))
		{
			//var_dump($_POST['i-post']);
			// variable initial
			$_data = json_decode($_POST['i-post'], true);
			$_transaction = [];
			
			$_cur_num = $_data['purchases_num'];
			$_show_save_btn = false;
			$_show_reprint_btn = false;
			$_show_discard_btn = true;

			$_transaction = $_data;
			$_sess[$_cur_num] = $_transaction;
			$_sess['cur_purchasesnum'] = $_cur_num;
			$this->session->set_tempdata($_session_id, $_sess, 600);

			// show save button
			if(isset($_transaction['void']))
			{
				if(filter_var($_transaction['void']))
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
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/purchases/order/'.$_data['formtype'].'/'.$_session_id.'/'.$_data['purchases_num'].'?b_url='.base_url().uri_string().'') ,"style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url("/purchases/order/".$_the_form_type."/".$_session_id) , "style" => "btn btn-primary", "show" => $_show_save_btn],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					//["name" => "Reprint", "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/router/purchases/discard/'.$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);
			// render view
			$this->load->view("purchases/purchases-process-view", [
				"data" => $_transaction,
				"discard_url" => base_url("/router/purchases/discard/".$_session_id),
				"preview_url" => base_url('/ThePrint/purchases/preview/'.$_session_id),
				"print_url" => base_url('/ThePrint/purchases/save/'.$_session_id)
			]);
			$this->load->view("footer");
		}
	}

	/**
	 * Save New Purchase Order
	 * To Save new purchase order
	 */
	public function save($_session_id = "")
	{
		$_transaction = [];
		$_login = $this->session->userdata('login');
		$_data = $this->session->userdata($_session_id);
		$_alert = "danger";
		if(isset($_data))
		{
			$_cur_purchasesnum = $_data['cur_purchasesnum'];
			$_transaction = $_data[$_cur_purchasesnum];
		}

		$alert = "danger";
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/purchases/order/'.$_login['preference']) ,"style" => "","show" => true],
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/router/purchases/create/'),"style" => "","show" => true]
			]
		]);

		if(!empty($_transaction) && isset($_transaction))
		{
			$_api_body = json_encode($_transaction,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";

			// save invoice 
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER'));
			$this->component_api->CallPost();
			$result = $this->component_api->GetConfig("result");
			
			switch($result["http_code"])
			{
				case 200:
					$_alert = "success";
				break;
				case 404:
					$_alert = "danger";
				break;
			}
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $_alert
			]);
		}
		else
		{
		   
		   $result["error"]['code'] = "90000";
		   $result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again"; 
		   $this->load->view('error-handle', [
			   'message' => $result["error"]['message'], 
			   'code'=> $result["error"]['code'], 
			   'alertstyle' => $_alert
		   ]);
		}
		$this->session->unset_userdata($_session_id);
		header("Refresh: 5; url='".base_url('purchases/order/'.$_login["preference"])."'");
	}

	/**
	 * Edit PO transaction 
	 * @param _num Quotation number
	 *
	 */
	public function edit($_session_id = "", $_num="")
	{
		// variable initial
		$_transaction = [];
		$_show_void_btn = true;
		$_show_next_btn = true;
		$_show_grn_btn = true;
		$_grn_btn_name = "";
		$_login = $this->session->userdata('login');
		$_API_MASTER = ['items' => "", 'shops' => "", 'suppliers'=> "", 'paymentmethod' => ""];
		if(!empty($_num))
		{
			// Check Quotation exist
			$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER').$_num);
			$this->component_api->CallGet();
			$result = $this->component_api->GetConfig("result");
			// echo "<pre>";
			// var_dump($result);
			// echo "</pre>";
			if($result['http_code'] == 200)
			{
				$_transaction = $result != null ? $result : "";
				// set current invoice number to session
				$_sess[$_num] = $_transaction['query'];
				$_sess['cur_purchasesnum'] = $_num;
				$this->session->set_tempdata($_session_id, $_sess, 600);
				
				if(!empty($_transaction))
				{	
					// already has GRN so transaction cannot be void
					if($_transaction['query']['has_grn'] > 0)
					{
						$_show_void_btn = false;
						$_show_next_btn = false;
					}
					// already settlement
					if($_transaction['query']['is_settle'] > 0)
					{
						$_show_grn_btn = false;
					}
					// display button name
					if($_transaction['query']['settlement'])
					{
						$_grn_btn_name = $this->lang->line("function_settlement");
					}
					else
					{
						$_grn_btn_name = $this->lang->line("function_good_received");
					}

					if($_transaction['has'])
					{
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
								["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/purchases/order/'.$_login['preference']), "style" => "", "show" => true],
								["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "btn btn-primary", "show" => $_show_next_btn],
								["name" => "<i class='fas fa-truck-loading'></i> ".$_grn_btn_name."", "type"=>"button", "id" => "togrn", "url"=> base_url('/router/purchases/togrn/'.$_num), "style" => "btn btn-success", "show" => $_show_grn_btn],
								["name" => "<i class='far fa-copy'></i> ".$this->lang->line("function_copy"), "type"=>"button", "id" => "copy", "url"=> base_url('/router/purchases/copy/'.$_session_id), "style" => "btn btn-dark", "show" => true],
								["name" => "<i class='fas fa-eraser'></i> ".$this->lang->line("function_cancel"), "type"=>"button", "id" => "discard", "url"=> base_url('/purchases/order/void/'.$_session_id.'/'.$_num), "style" => "btn btn-danger", "show" => $_show_void_btn]
							]
						]);
						$this->load->view('title-bar', [
							"title" => $this->lang->line("purchase_order_edit_title")
						]);
						
						// show edit view
						$this->load->view('purchases/purchases-edit-view', [
							"submit_to" => base_url("/purchases/order/process/".$_session_id),
							"discard_url" => base_url("/router/purchases/discard/".$_session_id),
							"prefix" => $this->_inv_header_param['topNav']['prefix'],
							"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
							"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
							"date" => date("Y-m-d H:i:s"),
							"ajax" => [
								"items" => $_API_MASTER['items'],
								"shop_code" => $_API_MASTER['shops'],
								"suppliers" => $_API_MASTER['suppliers'],
								"tender" => $_API_MASTER['paymentmethod']
							],
							"data" => $_transaction['query'],
							"show" => $_show_void_btn,
							"default_per_page" => $this->_default_per_page,
							"function_bar" => $this->load->view('function-bar', [
								"btn" => [
									["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "new", "url"=>base_url('/suppliers/?new=1'), "style" => "", "show" => true]
								]
							],true)						
						]);
						$this->load->view('footer');
					}
				}
			}
			else
			{
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/purchases/order/'.$_login['preference']), "style" => "", "show" => true],
					]
				]);
			}
		}
	}
	
	/**
	 * Save Edit
	 */
	public function saveedit($_session_id = "")
	{
		$alert = "danger";
		$_transaction = [];
		$_login = $this->session->userdata('login');
		$_sess = $this->session->userdata($_session_id);
		if(isset($_sess) )
		{
			$_cur_num = $_sess['cur_purchasesnum'];
			$_transaction = $_sess[$_cur_num];
		}
		
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/purchases/order/'.$_login['preference']) ,"style" => "","show" => true],
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/router/purchases/create'),"style" => "","show" => true],
			]
		]);
		if(!empty($_transaction) && isset($_transaction))
		{
			$_api_body = json_encode($_transaction,true);
			//  echo $_api_body;
			// echo "<pre>";
			// echo ($_api_body);
			// echo "</pre>";
			// save invoice 
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER').$_cur_num);
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
		header("Refresh: 5; url='".base_url('/purchases/order/'.$_login["preference"])."'");
	}
	
	/**
	* Save Void
	*/
	public function savevoid($_session_id = "")
	{
		$_login = $this->session->userdata('login');
		$_sess = $this->session->userdata($_session_id);
		// echo "<pre>";
		// var_dump($_data);
		// echo "</pre>";
		$_cur_num = $_sess['cur_purchasesnum'];
		$alert = "danger";

		$result = [];
		$this->load->view('function-bar', [
			"btn" => [
				[
					"name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"),
					"type"=>"button",
					"id" => "back", 
					"url"=> base_url('/purchases/order/'.$_login["preference"]),
					"style" => "",
					"show" => true
				]
			]
		]);
		if(!empty($_cur_num))
		{
			$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER').$_cur_num);
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
		$this->load->view("footer");
		//header("Refresh: 5; url='".base_url('purchases/order/'.$_login["preference"])."'");
	}
	/**
	 * Void
	* @param _num transaction number which PO number
	*/
	public function void($_session_id = "", $_num = "")
	{
		$this->load->view("purchases/purchases-void-view", [
			"submit_to" => base_url("/purchases/order/confirmed/void/".$_session_id),
			"to_deleted_num" => $_num,
			"return_url" => base_url("/purchases/order/edit/".$_session_id."/".$_num)
		]);
		$this->load->view("footer");
	}
	
	/**
	 * Settlement
	 * @param _num Purchase Order reference code
	 */
	public function settlement($_session_id = "", $_num = "")
	{
		$_transaction = [];
		$_total = 0;
		$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_SETTLEMENT').$_num);
		$this->component_api->CallGet();
		$_API_SETTLEMENT = $this->component_api->GetConfig("result");
		$_API_SETTLEMENT = !empty($_API_SETTLEMENT['query']) ? $_API_SETTLEMENT['query'] : "";
		// echo "<pre>";
		// var_dump($_API_SETTLEMENT);
		// echo "</pre>";
		// set current invoice number to session
		$_transaction = $_API_SETTLEMENT['all_grn'];
		$_sess[$_num] = $_transaction;
		$_sess['cur_purchasesnum'] = $_num;
		$this->session->set_tempdata($_session_id, $_sess, 600);

		$this->load->view("purchases/purchases-settlement-view", [
			"return_url" => base_url("/purchases/order/edit/".$_session_id."/".$_num),
			"submit_to" => base_url("/purchases/order/settlement/save/".$_session_id."/".$_num),
			"po_num" => $_num,
			"data" => $_API_SETTLEMENT['all_grn'],
			"total" => $_API_SETTLEMENT['total']
		]);
	}

	/**
	 * Settlement Save
	 * @param _num trans_code
	 */
	public function savesettlement($_session_id = "", $_num = "")
	{
		$_transaction = [];
		$_login = $this->session->userdata('login');
		$_sess = $this->session->userdata($_session_id);
		if( isset($_sess[$_num]) )
		{
			$_transaction = $_sess[$_num];
		}
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/purchases/order/'.$_login["preference"]), "style" => "", "show" => true]
			]
		]);

		if(!empty($_num) && !empty($_session_id))
		{
			$_api_body = json_encode($_transaction,true);
			// echo $_api_body;
			if($_api_body != null)
			{
				// save invoice 
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_SETTLEMENT').$_num);
				$this->component_api->CallPatch();
				$result = $this->component_api->GetConfig("result");
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
					// remove current session
					$this->session->unset_userdata($_session_id);
					header("Refresh: 5; url='".base_url('purchases/order/'.$_login["preference"])."'");
				}
			}
		}
	}
}
