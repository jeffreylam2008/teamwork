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
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];

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
			$_GET['i-start-date'] = date("Y-m-d", strtotime('-5 days'));
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
		
		switch($_data["http_code"])
		{
			case 200:
				$alert = "success";
			break;
			case 404:
				$alert = "danger";
			break;
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
			// Function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=> base_url("purchases/order/donew/"), "style" => "", "show" => true, "extra" => ""],
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
				"edit_url" => base_url("/purchases/order/edit/"),
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"ad_start_date" => $_query['i-start-date'],
				"ad_end_date" => $_query['i-end-date'],
				"ad_supp_code" => $_query['i-supp-code'],
				"ad_num" => $_query['i-num']
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
		// if(!empty($this->session->userdata('transaction')))
		// {
		// 	$this->session->unset_userdata('transaction');
		// }
		$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API_NEXT = $this->component_api->GetConfig("result");
		$_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
		redirect(base_url("purchases/order/create/".$_API_NEXT),"refresh");
	}

	/**
	 * To clone existing PO data to new PO view
	 */
	public function docopy($_old_num)
	{
		$_transaction = [];
		// if(!empty($this->session->userdata('transaction')))
		// {
		// 	$this->session->unset_userdata('transaction');
		// }
		//fatch existing transaction
		$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER').$_old_num);
		$this->component_api->CallGet();
		$_API_PURCHASES = $this->component_api->GetConfig("result");
		$_API_PURCHASES = !empty($_API_PURCHASES['query']) ? $_API_PURCHASES['query'] : "";
		// get next transaction number
		$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API_NEXT = $this->component_api->GetConfig("result");
		$_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
		// transaction retrieve
		$_transaction[$_API_NEXT] = $_API_PURCHASES;
		$_transaction[$_API_NEXT]['date'] = date("Y-m-d H:i:s");
		$this->session->set_userdata('cur_purchasesnum',$_API_NEXT);
		$this->session->set_userdata('transaction',$_transaction);
		// echo "<pre>";
		// var_dump($_API_PURCHASES);
		// echo "</pre>";
		redirect(base_url("purchases/order/create/".$_API_NEXT),"refresh");
	}

	/**
	 * Create Process
	 * To create new Purchase transaction
	 * @param _num purchase number
	 * @param _refer_num reference number
	 */
	public function create($_num = "", $_refer_num = "")
	{
		// variable initial
		$_show_discard_btn = false;
		$_transaction = [];

		if(!empty($_num))
		{
			$_show_discard_btn = true;

			// For back button after submit to tender page
			if(!empty($this->session->userdata('transaction')) && !empty($this->session->userdata('cur_purchasesnum')))
			{
				$_num = $this->session->userdata('cur_purchasesnum');
				$_transaction = $this->session->userdata('transaction');
			}
			// For new create
			else 
			{
				$_transaction[$_num]['refernum'] = "";
				$_transaction[$_num]['items'] = [];
				$_transaction[$_num]['supp_code'] = "";
				$_transaction[$_num]['supp_name'] = "";
				$_transaction[$_num]['paymentmethod'] = "";
				$_transaction[$_num]['paymentmethodname'] = "";
				$_transaction[$_num]['remark'] = "";
				$this->session->set_userdata('cur_purchasesnum',$_num);
				$this->session->set_userdata('transaction',$_transaction);
			}

		 // echo "<pre>";
		 // var_dump($_SESSION);
		 // echo "</pre>";
 
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

			// function bar with next, preview and save button

			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('purchases/order/discard'), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);
			$this->load->view('title-bar', [
				"title" => $this->lang->line("purchase_order_new_title")
			]);
			// present form view
			$this->load->view('purchases/purchases-create-view', [
				"submit_to" => base_url("/purchases/order/process"),
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
				"data" => $_transaction[$_num],
				"default_per_page" => $this->_default_per_page,
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "new", "url"=>base_url('/suppliers/?new=1'), "style" => "", "show" => true]
					]
				],true)
			]);
			// persent footer view
			$this->load->view('footer');
		}
	}

	/**
	 * Process
	 * To confirm Purchase transaction
	 */
	public function confirm()
	{
		if(isset($_POST["i-post"]))
		{
			//var_dump($_POST['i-post']);
			// variable initial
			$_data = json_decode($_POST['i-post'], true);
			$_transaction = [];
			$_cur_num = $_data['purchasesnum'];
			$_show_save_btn = false;
			$_show_reprint_btn = false;

			$_transaction[$_cur_num] = $_data;
			$this->session->set_userdata('cur_purchasesnum',$_cur_num);
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
			// var_dump($_transaction);
			// echo "</pre>";
			
			// function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/purchases/order/'.$_data['formtype'].'/'.$_cur_num) ,"style" => "","show" => true],
					//["name" => "Preview", "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url("/purchases/order/".$_the_form_type) , "style" => "","show" => $_show_save_btn],
					//["name" => "Reprint", "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn]
				]
			]);
			// render view
			$this->load->view("purchases/purchases-process-view", [
				"data" => $_transaction[$_cur_num],
				"preview_url" => base_url('/ThePrint/purchases/preview'),
				"print_url" => base_url('/ThePrint/purchases/save')
			]);
			$this->load->view("footer");
		}
	}

	/**
	 * Save New Purchase Order
	 * To Save new purchase order
	 */
	public function save()
	{
		$_login = $this->session->userdata('login');
		$_cur_num = $this->session->userdata('cur_purchasesnum');
		$_transaction = $this->session->userdata('transaction');
		$alert = "danger";
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/purchases/order/'.$_login['preference']) ,"style" => "","show" => true],
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/purchases/order/donew'),"style" => "","show" => true]
			]
		]);

		if(!empty($_transaction[$_cur_num]) && isset($_transaction[$_cur_num]))
		{
			$_api_body = json_encode($_transaction[$_cur_num],true);
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
						
		unset($_transaction[$_cur_num]);
		$this->session->set_userdata('cur_purchasesnum',"");
		$this->session->set_userdata('transaction',[]);
		
		header("Refresh: 5; url='".base_url('purchases/order'.$_login["preference"])."'");
	}

	/**
	 * Edit PO transaction 
	 * @param _num Quotation number
	 *
	 */
	public function edit($_num="")
	{
		// variable initial
		$_transaction = [];
		$_show_void_btn = true;
		$_show_next_btn = true;
		$_show_grn_btn = true;
		$_grn_btn_name = "";
		if(!empty($_num))
		{
			// Check Quotation exist
			$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER').$_num);
			$this->component_api->CallGet();
			$_transaction = $this->component_api->GetConfig("result");
			$_transaction = $_transaction != null ? $_transaction : "";
			
			// set current invoice number to session
			$this->session->set_userdata('cur_purchasesnum',$_num);
			$this->session->set_userdata('transaction',$_transaction['query']);
			
			// echo "<pre>";
			// var_dump($_transaction);
			// echo "</pre>";
			if(!empty($_transaction))
			{	
				$_login = $this->session->userdata('login');

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

					// function bar with next, preview and save button
					$this->load->view('function-bar', [
						"btn" => [
							["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/purchases/order/'.$_login['preference']), "style" => "", "show" => true],
							["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => $_show_next_btn],
							["name" => "<i class='far fa-copy'></i> ".$this->lang->line("function_copy"), "type"=>"button", "id" => "copy", "url"=> base_url('/purchases/order/copy/'.$_num), "style" => "btn btn-dark", "show" => true],
							["name" => "<i class='fas fa-truck-loading'></i> ".$_grn_btn_name."", "type"=>"button", "id" => "grn", "url"=> base_url('/purchases/order/togrn/'.$_num), "style" => "btn btn-success", "show" => $_show_grn_btn],
							["name" => "<i class='fas fa-eraser'></i> ".$this->lang->line("function_cancel"), "type"=>"button", "id" => "discard", "url"=> base_url('/purchases/order/void/'.$_num), "style" => "btn btn-danger", "show" => $_show_void_btn]
						]
					]);
					$this->load->view('title-bar', [
						"title" => $this->lang->line("purchase_order_edit_title")
					]);
					
					// show edit view
					$this->load->view('purchases/purchases-edit-view', [
						"submit_to" => base_url("/purchases/order/process"),
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
						"default_per_page" => $this->_default_per_page,
						"function_bar" => $this->load->view('function-bar', [
							"btn" => [
								["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "new", "url"=>base_url('/suppliers/?new=1'), "style" => "", "show" => true]
							]
						],true)						
					]);
					$this->load->view('footer');
				}
				else
				{
					redirect(base_url("purchases/order/list/"),"refresh");
				}
			}
		}
	}
	
	 /**
	  * Save Edit
	  */
	public function saveedit()
	{
		$_transaction = [];
		$_cur_num = "";
		$_cur_num = $this->session->userdata('cur_purchasesnum');
		$_transaction = $this->session->userdata('transaction');
		$_login = $this->session->userdata('login');
		$alert = "danger";
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/purchases/order/'.$_login['preference']) ,"style" => "","show" => true],
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/purchases/order/donew'),"style" => "","show" => true],
			]
		]);
		if(!empty($_transaction[$_cur_num]) && isset($_transaction[$_cur_num]))
		{
			$_api_body = json_encode($_transaction[$_cur_num],true);
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
		unset($_transaction[$_cur_num]);
		$this->session->set_userdata('cur_purchasesnum',"");
		$this->session->set_userdata('transaction',$_transaction);
		
		header("Refresh: 5; url='".base_url('purchases/order')."'");
	}

	/**
	 * Discard Operation only for new creation
	 * To discard Invoice 
	 */
	public function discard()
	{
		$_cur_purchasesnum = $this->session->userdata('cur_purchasesnum');
		$_transaction = $this->session->userdata('transaction');
		unset($_SESSION['cur_purchasesnum']);
		unset($_transaction[$_cur_purchasesnum]);
		redirect(base_url("purchases/order/donew"),"refresh");
	}

	/**
	 * Void
	* @param _num transaction number which PO number
	*/
	public function void($_num = "")
	{
		$this->load->view("purchases/purchases-void-view", [
			"submit_to" => base_url("purchases/order/void/confirmed/".$_num),
			"to_deleted_num" => $_num,
			"return_url" => base_url("purchases/order/edit/".$_num)
		]);
	}
	/**
	* Save Void
	*/
	public function savevoid($_num = "")
	{
		$alert = "danger";
		$_login = $this->session->userdata('login');
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
		if(!empty($_num))
		{
			$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER').$_num);
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

			//header("Refresh: 5; url='".base_url('purchases/order'.$_login["preference"])."'");
		}
	}
	/**
	* To GRN
	* @param _num PO number to good recevied Note
	*/
	public function togrn($_num = "")
	{
		$_transaction = [];
		$_temp = [];
		$this->component_api->SetConfig("url", $this->config->item('URL_PO_GRN_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API_NEXT = $this->component_api->GetConfig("result");
		$_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
		
		if(!empty($_API_NEXT))
		{
			$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_GRN').$_num);
			$this->component_api->CallGet();
			$_API_GET_GRN = $this->component_api->GetConfig("result");
			// echo "<pre>";
			// var_dump($_API_GET_GRN);
			// echo "</pre>";
			$_API_GET_GRN = !empty($_API_GET_GRN['query']) ? $_API_GET_GRN['query'] : "";
			
			if(!empty($_API_GET_GRN)){
				// send settlement 
				if($_API_GET_GRN['settlement'])
				{
					$this->session->set_userdata('transaction', $_API_GET_GRN);
					redirect(base_url("purchases/order/settlement/".$_num),"refresh");
				}

				$_transaction[$_API_NEXT] = $_API_GET_GRN;
				$_transaction[$_API_NEXT]['trans_code'] = $_API_NEXT;
				$_transaction[$_API_NEXT]['po_num'] = $_num;
				$this->session->set_userdata('cur_grnnum', $_API_NEXT);
				$this->session->set_userdata('transaction', $_transaction);
			}			
		}
		redirect(base_url("stocks/grn/create/".$_API_NEXT."/".$_num),"refresh");
	}
	/**
	 * Settlement
	 * @param _num Purchase Order reference code
	 */
	public function settlement($_num = "")
	{
		$_total = 0;
		$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_SETTLEMENT').$_num);
		$this->component_api->CallGet();
		$_API_SETTLEMENT = $this->component_api->GetConfig("result");
		$_API_SETTLEMENT = !empty($_API_SETTLEMENT['query']) ? $_API_SETTLEMENT['query'] : "";

		// set current invoice number to session
		$this->session->set_userdata('transaction',$_API_SETTLEMENT['all_grn']);

		$this->load->view("purchases/purchases-settlement-view", [
			"return_url" => base_url("purchases/order/edit/".$_num),
			"submit_to" => base_url("purchases/order/settlement/save/".$_num),
			"po_num" => $_num,
			"data" => $_API_SETTLEMENT['all_grn'],
			"total" => $_API_SETTLEMENT['total']
		]);
	}
	/**
	 * Settlement Save
	 * @param _num trans_code
	 */
	public function savesettlement($_num = "")
	{
		$_login = $this->session->userdata('login');
		$_transaction = $this->session->userdata('transaction');
		$this->load->view('function-bar', [
			"btn" => [
				[
					"name" => "<i class='fas fa-chevron-left'></i> Back", "type"=>"button", "id" => "back", "url"=> base_url('/purchases/order/'.$_login["preference"]), "style" => "", "show" => true
				]
			]
		]);

		if(!empty($_num))
		{
			$_api_body = json_encode($_transaction,true);
			// echo $_api_body;
			if($_api_body != null)
			{
				// save invoice 
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_SETTLEMENT').$_num);
				$this->component_api->CallPatch();
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
					redirect(base_url("purchases/order/".$_login['preference']),"refresh");
				}
			}
		}
	}
}
