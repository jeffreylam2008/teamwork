<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocks extends CI_Controller 
{
    var $_inv_header_param = [];
	var $_default_per_page = "";
	var $_page = "";
	var $_token = "";
	var $_profile = "";
	var $_param = "";
    var $_user_auth = ['create' => false, 'edit' => false, 'delete' => false];
    
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
		$this->load->library("Component_Login",[$this->_token, "stocks/index"]);

		// // login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API data
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
				case "stocks/grn":
					$this->_param = "stocks/index";
				break;
				case "stocks/grn_confirm":
					$this->_param = "stocks/index";
				break;
				case "stocks/adjust":
					$this->_param = "stocks/index";
				break;
				case "stocks/grn_detail":
					$this->_param = "stocks/index";
				break;
				case "stocks/dn_detail":
					$this->_param = "stocks/index";
				break;
				case "stocks/stocktake":
					$this->_param = "stocks/index";
				break;
				case "stocks/adj_detail":
					$this->_param = "stocks/index";
				break;
				case "stocks/stocktake_detail":
					$this->_param = "stocks/index";
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
			$this->component_sidemenu->SetConfig("nav_list", $_API_MENU);
			$this->component_sidemenu->SetConfig("active", $this->_param);
			$this->component_sidemenu->Proccess();

			// render the view
			$this->load->view('header',[
				'title'=>'Stocks',
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
	 *GRN List
	 * 
	 */
    public function index()
    {
		// variable initial
		$_data = [];
		if(empty($_GET['i-start-date']) && empty($_GET['i-end-date']) && empty($_GET['i-dn']))
		{
			$_GET['i-start-date'] = date("Y-m-d", strtotime('-5 days'));
			$_GET['i-end-date'] = date("Y-m-d");
		}
		$_query = [
			'i-num' => $this->input->get("i-num"),
			'i-start-date' => $this->input->get('i-start-date'),
			'i-end-date' => $this->input->get('i-end-date'),
			'i-cust-code' => $this->input->get('i-cust-code'),
			'i-supp-code' => $this->input->get('i-supp-code')
		];
		
		if(!empty($_query))
		{
			//Set user preference
			$_query['page'] = htmlspecialchars($this->_page);
			$_query['show'] = htmlspecialchars($this->_default_per_page);
			$_q = $this->component_uri->QueryToString($_query);
			$_login = $this->session->userdata('login');
			$_login['preference'] = $_q;
			$this->session->set_userdata("login", $_login);
			
			// echo "<pre>";
			// var_dump($_q);
			// echo "</pre>";
			// fatch items API
			if(!empty($_query['i-cust-code']))
			{
				// fatch items API
				// get result by customer code 
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCKS')."getlast/cust/".$_query['i-cust-code']);
			}
			elseif(!empty($_query['i-supp-code']))
			{
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCKS')."getlast/supp/".$_query['i-supp-code']);
			}
			else
			{
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCKS').$_q);
			}
			$this->component_api->CallGet();
			$_data = json_decode($this->component_api->GetConfig("result"), true);
			$_data = $_data != null ? $_data : "";
		}
		
			// echo "<pre>";
			// var_dump($_data);
			// echo "</pre>";
		if(!empty($_data['Error']))
		{
			$this->load->view("error-handle", [
				"alertstyle" => "danger",
				"code" => $_data['Code'],
				"message" => $_data['Error']
			]);
		}
		else
		{
			// Function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-truck-loading'></i> ".$this->lang->line("grn_short"), "type"=>"button", "id" => "i-grn", "url"=> base_url('/stocks/grn/donew'), "style" => "", "show" => true, "extra" => ""],
					["name" => "<i class='fas fa-adjust'></i> ".$this->lang->line("adjustment_short"), "type"=>"button", "id" => "i-adj", "url"=> base_url('/stocks/donewadj'), "style" => "", "show" => true, "extra" => ""],
					["name" => "<i class='fas fa-box'></i> ".$this->lang->line("stocktake"), "type"=>"button", "id" => "i-stocktake", "url"=> base_url('/stocks/donew_stocktake'), "style" => "", "show" => true, "extra" => ""]
				]
			]);
			// Function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-search'></i> ".$this->lang->line("function_search"), "type"=>"button", "id" => "i-search", "url"=> "#", "style" => "", "show" => true, "extra" => ""],
					["name" => "<i class='fas fa-undo-alt'></i> ".$this->lang->line("function_clear"), "type"=>"button", "id" => "i-clear", "url"=> "#", "style" => "btn btn-secondary", "show" => true, "extra" => ""]
				]
			]);

			$this->load->view("stocks/stocks-list-view", [
				"data" => $_data,
				"submit_to" => base_url("stocks"),
				"edit_url" => base_url("stocks/route/"),
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"ad_start_date" => $_query['i-start-date'],
				"ad_end_date" => $_query['i-end-date'],
				"ad_num" => $_query['i-num'],
				"ad_cust_code" => $_query['i-cust-code'],
				"ad_supp_code" => $_query['i-supp-code']
			]);
			$this->load->view("footer");
		}

	}

	/**
	 * Route and redirect to target url
	 * @param _input transaction code 
	 */
	 public function route($_input)
	 {
		 $_path = "";
		 if(!empty($_input))
		 {
			 // Call API
			 $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTIONS').$_input);
			 $this->component_api->CallGet();
			 $_API_TRANSACTIONS = json_decode($this->component_api->GetConfig("result"), true);
			 $_API_TRANSACTIONS = !empty($_API_TRANSACTIONS['query']) ? $_API_TRANSACTIONS['query'] : "";
			 // echo "<pre>";
			 // print_r($_API_TRANSACTIONS);
			 // echo "</pre>";
			 if(!empty($_API_TRANSACTIONS))
			 {
				 switch($_API_TRANSACTIONS['prefix'])
				 {
					case "GRN":
						$_path = base_url("/stocks/grn/detail/".$_input);
					break;
					case "DN":
						$_path = base_url("/stocks/dn/detail/".$_input);
					break;
					case "INV":
						$_path = base_url("/invoices/edit/".$_input);
					break;
					case "ADJ":
						$_path = base_url("/stocks/adj/detail/".$_input);
					break;
					case "ST":
						$_path = base_url("/stocks/stocktake/detail/".$_input);
					break;
					case "PO":
						$_path = base_url("/purchases/order/edit/".$_input);
					break;
				 }
				 header("Refresh: 0; url='".$_path."'");
			 }
			 else
			 {
				 $alert = "danger";
				 $result["error"]['code'] = "99998";
				 $result["error"]['message'] = "Code not found"; 
				 $this->load->view('error-handle', [
					 'message' => $result["error"]['message'], 
					 'code'=> $result["error"]['code'], 
					 'alertstyle' => $alert
				 ]);
				 $this->load->view('function-bar', [
					 "btn" => [
						 ["name" => "Back", "type"=>"button", "id" => "back", "url"=> base_url('/stocks/') ,"style" => "","show" => true]
					 ]
				 ]);
			 }
		 }
	 }
	 
	/**
	 * Create new GRN
	 *
	 */
	public function donewgrn()
	{
		if(!empty($this->session->userdata('transaction')))
		{
			$this->session->unset_userdata('transaction');
		}
		$this->component_api->SetConfig("url", $this->config->item('URL_PO_GRN_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API_GRN_NUM = json_decode($this->component_api->GetConfig("result"), true);
		$_API_GRN_NUM = !empty($_API_GRN_NUM['query']) ? $_API_GRN_NUM['query'] : "";
		redirect(base_url("stocks/grn/create/".$_API_GRN_NUM),"refresh");
	}
	
	/**
	 *  Create Good receive note 
	 *  @param grn_num new grn ID number
	 */
	public function grn($_grn_num = "", $_po_num = "")
	{
		$_show_discard_btn = false;
		$_transaction = [];
		if(!empty($_grn_num))
		{
			$_show_discard_btn = true;
			// API call
			$this->component_api->SetConfig("url", $this->config->item('URL_ITEMS'));
			$this->component_api->CallGet();
			$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
			$_API_ITEMS = !empty($_API_ITEMS['query']) ? $_API_ITEMS['query'] : "";
			$this->component_api->SetConfig("url", $this->config->item('URL_SHOP'));
			$this->component_api->CallGet();
			$_API_SHOPS = json_decode($this->component_api->GetConfig("result"), true);
			$_API_SHOPS = !empty($_API_SHOPS['query']) ? $_API_SHOPS['query'] : "";
			$this->component_api->SetConfig("url", $this->config->item('URL_SUPPLIERS'));
			$this->component_api->CallGet();
			$_API_SUPPLIERS = json_decode($this->component_api->GetConfig("result"), true);
			$_API_SUPPLIERS = !empty($_API_SUPPLIERS['query']) ? $_API_SUPPLIERS['query'] : "";
			// fatch payment method API
			$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
			$this->component_api->CallGet();
			$_API_PAYMENTS = json_decode($this->component_api->GetConfig("result"),true);
			$_API_PAYMENTS = !empty($_API_PAYMENTS['query']) ? $_API_PAYMENTS['query'] : "";
			$this->component_api->SetConfig("url", $this->config->item('URL_PO_GRN_PREFIX'));
			$this->component_api->CallGet();
			$_API_GRN_PREFIX = json_decode($this->component_api->GetConfig("result"),true);
			$_API_GRN_PREFIX = !empty($_API_GRN_PREFIX['query']) ? $_API_GRN_PREFIX['query'] : "";

			if((substr($_grn_num , 0 , 3) === $_API_GRN_PREFIX))
			{
				// For back button after submit to tender page
				if(!empty($this->session->userdata('transaction')) && !empty($this->session->userdata('cur_grnnum')))
				{
					$_grn_num = $this->session->userdata('cur_grnnum');
					$_transaction = $this->session->userdata('transaction');
					$_transaction[$_grn_num]['prefix'] = $_API_GRN_PREFIX;
				}
				// For new create
				else 
				{
					$_transaction[$_grn_num]['items'] = [];
					$_transaction[$_grn_num]['po_num'] = "";
					$_transaction[$_grn_num]['supp_code'] = "";
					$_transaction[$_grn_num]['supp_name'] = "";
					$_transaction[$_grn_num]['paymentmethod'] = "";
					$_transaction[$_grn_num]['paymentmethodname'] = "";
					$_transaction[$_grn_num]['remark'] = "";
					$_transaction[$_grn_num]['prefix'] = $_API_GRN_PREFIX;
					
					$this->session->set_userdata('cur_grnnum',$_grn_num);
					$this->session->set_userdata('transaction',$_transaction);
				}
			}

		// echo "<pre>";
		// var_dump($_transaction);
		// echo "</pre>";

			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks') ,"style" => "","show" => true],
					["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/stocks/grn/donew'), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);

			$this->load->view('title-bar', [
				"title" => $this->lang->line("grn")
			]);
			
			$this->load->view("stocks/goods-recevied-create-view", [
				"submit_to" => base_url("stocks/process"),
				"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"grn_num" => $_grn_num,
				"date" => date("Y-m-d H:i:s"),
				"ajax" => [
					"items" => $_API_ITEMS,
					"shop_code" => $_API_SHOPS,
					"suppliers" => $_API_SUPPLIERS,
					"tender" => $_API_PAYMENTS
				],
				"data" => $_transaction[$_grn_num],
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "new", "url"=>base_url('/suppliers/?new=1'), "style" => "", "show" => true]
					]
				],true)
			]);
			$this->load->view('footer');
		}
	}
	/**
	 * GRN Confirm
	 * 
	 * To verify user input data
	 */
	public function grn_confirm()
	{
		// echo "<pre>";
		// print_r($_POST['i-post']);
		// echo "</pre>";

		if(isset($_POST["i-post"]))
		{
			// variable initial
			$_data = json_decode($_POST['i-post'], true);
			$_transaction = [];
			$_cur_grnnum = $this->session->userdata('cur_grnnum');
			$_show_save_btn = true;
			$_show_reprint_btn = false;
			

			$_transaction[$_cur_grnnum] = $_data;
			// echo "<pre>";
			// var_dump( $_transaction );
			// echo "</pre>";
			$this->session->set_userdata('transaction',$_transaction);

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

			$this->load->view('title-bar', [
				"title" => $this->lang->line("grn")
			]);

			// function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/grn/'.$_data['formtype'].'/'.$_data['grn_num']) ,"style" => "","show" => true],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url('/stocks/grn/'.$_the_form_type) , "style" => "","show" => $_show_save_btn],
					["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn]
				]
			]);

			//view content
			$this->load->view("stocks/goods-recevied-process-view", [
				"preview_url" => base_url('/ThePrint/grn/preview'),
				"print_url" => base_url('/ThePrint/grn/save')
			]);
			$this->load->view('footer');
		}
	}
	/**
	 * GRN SAVE
	 *
	 * To save GRN
	 */
	public function grn_save()
	{
		$_cur_grnnum = $this->session->userdata('cur_grnnum');
		$_transaction = $this->session->userdata('transaction');
		
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "donew", "url"=> base_url('/stocks/donew'),"style" => "","show" => true],
			]
		]);

		if(!empty($_transaction[$_cur_grnnum]) && isset($_transaction[$_cur_grnnum]))
		{
			$_api_body = json_encode($_transaction[$_cur_grnnum],true);

			if($_api_body != null)
			{
				/** For debug use start */
				// echo "<pre>";
				// echo ($_api_body);
				// echo "</pre>";
				/** For debug use end */

				// save invoice 
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_PO_GRN'));
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
				$alert = "danger";
				if(isset($result["error"]['code']))
				{
					switch($result["error"]['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}					
				}
				else
				{
					$result["error"]['code'] = "99999";
					$result["error"]['message'] = "API-Error"; 
				}

				$this->load->view('error-handle', [
					'message' => $result["error"]['message'], 
					'code'=> $result["error"]['code'], 
					'alertstyle' => $alert
				]);

				unset($_transaction[$_cur_grnnum]);
				$this->session->set_userdata('cur_grnnum',"");
				$this->session->set_userdata('transaction',$_transaction);
				header("Refresh: 10; url='".base_url('/stocks')."'");
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
	}

	/**
	 * Show GRN detail 
	 * @param _input transaction code
	 */
	public function grn_detail($_input)
	{
		$_transaction = [];
		if(!empty($_input))
		{
			// Call API
			$this->component_api->SetConfig("url", $this->config->item('URL_PO_GRN').$_input);
			$this->component_api->CallGet();
			$_API_GRN = json_decode($this->component_api->GetConfig("result"), true);
			$_API_GRN = !empty($_API_GRN['query']) ? $_API_GRN['query'] : "";
			$this->component_api->SetConfig("url", $this->config->item('URL_SUPPLIERS'));
			$this->component_api->CallGet();
			$_API_SUPPLIERS = json_decode($this->component_api->GetConfig("result"), true);
			$_API_SUPPLIERS = !empty($_API_SUPPLIERS['query']) ? $_API_SUPPLIERS['query'] : "";

			$_login = $this->session->userdata("login");
			$_transaction[$_input] = $_API_GRN;
			$_transaction[$_input]['supplier'] = $_API_SUPPLIERS;
			$this->session->set_userdata('cur_grnnum',$_input);
 			$this->session->set_userdata('transaction',$_transaction);

			// $this->session->set_userdata('cur_grnnum',"");
			// $this->session->set_userdata('transaction',$_transaction);
			// echo "<pre>";
			// print_r($_API_GRN);
			// echo "</pre>";
			
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/'.$_login['preference']) ,"style" => "","show" => true],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => true],
					["name" => "<i class='fas fa-sliders-h'></i> ".$this->lang->line("adjustment"), "type"=>"button", "id" => "adjustment", "url"=> base_url('/stocks/donewadj/'.$_input) , "style" => "btn btn-outline-warning" , "show" => true]
				]
			]);
			$this->load->view('title-bar', [
				"title" => $this->lang->line("grn")
			]);
			//view content
			$this->load->view("stocks/goods-recevied-detail-view", [
				"data" => $_API_GRN,
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"preview_url" => base_url('/ThePrint/grn/preview'),
				"print_url" => base_url('/ThePrint/grn/save'),
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "new", "url"=>base_url('/customers/?new=1'), "style" => "", "show" => true]
					 ]
				],true)
			]);
		}
	}

	/**
	 * Create new adjustment
	 * @param _trans_code the refer transaction code to be adjust 
	 */
	public function donewadj($_trans_code = "")
	{
		$_transaction = [];
		if(!empty($this->session->userdata('transaction')))
		{
			$this->session->unset_userdata('transaction');
		}
		
		$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API = json_decode($this->component_api->GetConfig("result"), true);
		$_API = !empty($_API['query']) ? $_API['query'] : "";
		if(!empty($_trans_code))
		{
			$this->component_api->SetConfig("url", $this->config->item('URL_PO_GRN').$_trans_code);
			$this->component_api->CallGet();
			$_API_GRN = json_decode($this->component_api->GetConfig("result"), true);
			$_API_GRN = !empty($_API_GRN['query']) ? $_API_GRN['query'] : "";
			$_transaction[$_API] = $_API_GRN;
			$_transaction[$_API]['adj_num'] = $_API;
			$_transaction[$_API]['prefix'] = 'ADJ';
			$_transaction[$_API]['refer_num'] = $_trans_code;
			$_transaction[$_API]['date'] = date("Y-m-d H:i:s");
			$this->session->set_userdata('cur_adj_num',$_API);
			$this->session->set_userdata('transaction',$_transaction);
		}
		redirect(base_url("stocks/adj/create/".$_API."/".$_trans_code),"refresh");
	}

	/**
	 * create adjustment
	 * @param _adj_num New generated transaction number
	 * @param _trans_code the refer transaction code to be adjust 
	 */
	public function adjust($_adj_num = "", $_trans_code = "")
	{
		$_show_discard_btn = false;
		$_transaction = [];
		if(!empty($_adj_num))
		{
			$_show_discard_btn = true;
			// API call
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_PREFIX'));
			$this->component_api->CallGET();
			$_API_PREFIX = json_decode($this->component_api->GetConfig("result"),true);
			$_API_PREFIX = !empty($_API_PREFIX['query']) ? $_API_PREFIX['query'] : "";
			$this->component_api->SetConfig("url", $this->config->item('URL_ITEMS'));
			$this->component_api->CallGet();
			$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
			$_API_ITEMS = !empty($_API_ITEMS['query']) ? $_API_ITEMS['query'] : "";

			if((substr($_adj_num , 0 , 3) === $_API_PREFIX))
			{
				// For back button after submit to tender page
				if(!empty($this->session->userdata('transaction')) && !empty($this->session->userdata('cur_adj_num')))
				{
					$_adj_num = $this->session->userdata('cur_adj_num');
					$_transaction = $this->session->userdata('transaction');
				}
				// For new create
				else 
				{
					$_transaction[$_adj_num]['refer_num'] = $_trans_code;
					$_transaction[$_adj_num]['items'] = [];
					$_transaction[$_adj_num]['remark'] = "";
				}
				// echo "<pre>";
				// var_dump($_transaction);
				// echo "</pre>";
			}
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks') ,"style" => "","show" => true],
					["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/stocks/donewadj'), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);

			$this->load->view('title-bar', [
				"title" => $this->lang->line("adjustment_new_titles")
			]);
			
			$this->load->view("stocks/stocks-adj-view",[
				"submit_to" => base_url("stocks/adj/process"),
				"prefix" => $_API_PREFIX,
				"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"adj_num" => $_adj_num,
				"date" => date("Y-m-d H:i:s"),
				"ajax" => [
					"items" => $_API_ITEMS
				],
				"data" => $_transaction[$_adj_num]
			]);
		}
	}
	/*
	 * adjustment Confirm
	 * 
	 * To verify user input data
	 */
	public function adj_confirm()
	{
		//print_r($_POST['i-post']);

		if(isset($_POST["i-post"]))
		{
			// variable initial
			$_data = json_decode($_POST['i-post'], true);
			$_transaction = [];
			$_cur_adj_num = $this->session->userdata('cur_adj_num');
			$_show_save_btn = true;
			$_show_reprint_btn = false;

			$_transaction[$_cur_adj_num] = $_data;
			// echo "<pre>";
			// var_dump( $_transaction[$_cur_grnnum] );
			// echo "</pre>";
			$this->session->set_userdata('transaction',$_transaction);

			switch($_data['formtype'])
			{
				case "create":
					$_show_reprint_btn = false;
					$_the_form_type = "save";
				break;
			}

			$this->load->view('title-bar', [
				"title" => $this->lang->line("adjustment_new_titles")
			]);
			// function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/adj/'.$_data['formtype'].'/'.$_data['adj_num'].'/'.$_data['refer_num']) ,"style" => "","show" => true],
					//["name" => "Preview", "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url('/stocks/adj/'.$_the_form_type) , "style" => "","show" => $_show_save_btn],
					//["name" => "Reprint", "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn]
				]
			]);

			$this->load->view("stocks/stocks-adj-process-view",[
				"data" => $_transaction[$_cur_adj_num],
				"preview_url" => base_url('/ThePrint/adjustment/preview'),
				"print_url" => base_url('/ThePrint/adjustment/save')
			]);
		}
	}

	/**
	 * Adjustment Save
	 *
	 * To save adjment
	 */
	public function adj_save()
	{
		$_cur_num = $this->session->userdata('cur_adj_num');
		$_transaction = $this->session->userdata('transaction');

		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "donew", "url"=> base_url('/stocks/donewadj'),"style" => "","show" => true],
			]
		]);
		 
		if(!empty($_transaction[$_cur_num]) && isset($_transaction[$_cur_num]))
		{
			$_api_body = json_encode($_transaction[$_cur_num],true);
			//echo $_api_body;
			// save transaction 
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ'));
			$this->component_api->CallPost();
			$result = json_decode($this->component_api->GetConfig("result"),true);
			
			$alert = "danger";
			if(isset($result["error"]['code']))
			{
				switch($result["error"]['code'])
				{
					case "00000":
						$alert = "success";
					break;
				}					
			}
			else
			{
				$result["error"]['code'] = "99999";
				$result["error"]['message'] = "API-Error"; 
			}

			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);

			unset($_transaction[$_cur_num]);
			$this->session->set_userdata('cur_adj_num',"");
			$this->session->set_userdata('transaction',$_transaction);
			header("Refresh: 10; url='".base_url('/stocks')."'");
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
	 * Show adjustment detail 
	 * @param _input transaction code
	 */
	public function adj_detail($_input = "")
	{
		//$_transaction = [];
		if(!empty($_input))
		{
			// Call API
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ').$_input);
			$this->component_api->CallGet();
			$_API = json_decode($this->component_api->GetConfig("result"), true);
			$_API = !empty($_API['query']) ? $_API['query'] : "";

			
			$_login = $this->session->userdata("login");

			// For Print
			// $_transaction[$_input] = $_API;
			// $this->session->set_userdata('cur_adj_num',$_input);
			// $this->session->set_userdata('transaction',$_transaction);

			
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/'.$_login['preference']) ,"style" => "","show" => true],
					//["name" => "Preview", "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					//["name" => "Reprint", "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => true]
				]
			]);
			$this->load->view('title-bar', [
				"title" => $this->lang->line("adjustment_edit_titles")
			]);
			//view content
			$this->load->view("stocks/stocks-adj-detail-view", [
				"data" => $_API,
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				//"preview_url" => base_url('/ThePrint/adjustment/preview'),
				//"print_url" => base_url('/ThePrint/adjustment/save'),
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "new", "url"=>base_url('/customers/?new=1'), "style" => "", "show" => true]
					 ]
				],true)
			]);
		}
	}

	/**
	 * Create new Stock Take
	 */
	public function donew_stocktake()
	{
		$_transaction = [];
		if(!empty($this->session->userdata('transaction')))
		{
			$this->session->unset_userdata('transaction');
		}
		
		$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API = json_decode($this->component_api->GetConfig("result"), true);
		$_API = !empty($_API['query']) ? $_API['query'] : "";

		$this->session->set_userdata('cur_stocktake_num',$_API);
		$this->session->set_userdata('transaction',$_transaction);
	
		redirect(base_url("stocks/stocktake/create/".$_API),"refresh");
	}
	/**
	 * Stock Take
	 */
	public function stocktake($_stocktake_num = "")
	{
		$_show_discard_btn = false;
		$_transaction = [];
		if(!empty($_stocktake_num))
		{
			$_show_discard_btn = true;
			// API call
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST_PREFIX'));
			$this->component_api->CallGET();
			$_API_PREFIX = json_decode($this->component_api->GetConfig("result"),true);
			$_API_PREFIX = !empty($_API_PREFIX['query']) ? $_API_PREFIX['query'] : "";
			$this->component_api->SetConfig("url", $this->config->item('URL_ITEMS'));
			$this->component_api->CallGet();
			$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
			$_API_ITEMS = !empty($_API_ITEMS['query']) ? $_API_ITEMS['query'] : "";

			if((substr($_stocktake_num , 0 , 2) === $_API_PREFIX))
			{
				
				// For back button after submit to tender page
				if(!empty($this->session->userdata('transaction')) && !empty($this->session->userdata('cur_stocktake_num')))
				{
					$_stocktake_num = $this->session->userdata('cur_stocktake_num');
					$_transaction = $this->session->userdata('transaction');
				}
				// For new create
				else 
				{
					$_transaction[$_stocktake_num]['trans_code'] = $_stocktake_num;
					$_transaction[$_stocktake_num]['date'] = date("Y-m-d H:i:s");
					$_transaction[$_stocktake_num]['items'] = [];
					$_transaction[$_stocktake_num]['remark'] = "";	
					$this->session->set_userdata('cur_stocktake_num',$_stocktake_num);
					
				}
			}
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks') ,"style" => "","show" => true],
					["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/stocks/donewadj'), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);

			$this->load->view('title-bar', [
				"title" => "Stock Take"
			]);
			
			$this->load->view("stocks/stocks-stocktake-view",[
				"submit_to" => base_url("stocks/stocktake/process"),
				"prefix" => $_API_PREFIX,
				"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"ajax" => [
					"items" => $_API_ITEMS
				],
				"export_url" => base_url("export/stocktake"),
				"import_url" => base_url("import/stocktake"),
				"data" => $_transaction[$_stocktake_num]
			]);
			$this->load->view('footer');
		}
	}

	/**
	 * Stocktake in process
	 * 
	 * To verify input data
	 */
	public function stocktake_process()
	{
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
		if(isset($_POST["i-post"]))
		{
			// variable initial
			$_data = json_decode($_POST['i-post'], true);
			$_transaction = [];
			$_stocktake_num = $this->session->userdata('cur_stocktake_num');
			$_show_save_btn = true;
			$_show_reprint_btn = false;
			

			$_transaction[$_stocktake_num] = $_data;
			// echo "<pre>";
			// var_dump( $_transaction );
			// echo "</pre>";
			$this->session->set_userdata('transaction',$_transaction);

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

			$this->load->view('title-bar', [
				"title" => "Stock Take"
			]);

			// function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "Back", "type"=>"button", "id" => "back", "url"=> base_url('/stocks/stocktake/'.$_data['formtype'].'/'.$_data['trans_code']) ,"style" => "","show" => true],
					["name" => "Preview", "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "Save", "type"=>"button", "id" => "save", "url"=> base_url('/stocks/stocktake/'.$_the_form_type) , "style" => "","show" => $_show_save_btn],
					["name" => "Reprint", "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn]
				]
			]);

			//view content
			$this->load->view("stocks/stocks-stocktake-process-view", [
				"preview_url" => base_url('/ThePrint/stocktake/preview'),
				"print_url" => base_url('/ThePrint/stocktake/save')
			]);
		}
		$this->load->view('footer');
	}

	/**
	 * Stocktake Save
	 * 
	 * To save data to DB
	 */
	public function stocktake_save()
	{
		$_cur_num = $this->session->userdata('cur_stocktake_num');
		$_transaction = $this->session->userdata('transaction');

		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "donew", "url"=> base_url('/stocks/donew'),"style" => "","show" => true],
			]
		]);
		
		if(!empty($_transaction[$_cur_num]) && isset($_transaction[$_cur_num]))
		{
			$_api_body = json_encode($_transaction[$_cur_num],true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";

			// save transaction 
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST'));
			$this->component_api->CallPost();
			$result = json_decode($this->component_api->GetConfig("result"),true);
			
			$alert = "danger";
			if(isset($result["error"]['code']))
			{
				switch($result["error"]['code'])
				{
					case "00000":
						$alert = "success";
					break;
				}					
			}
			else
			{
				$result["error"]['code'] = "99999";
				$result["error"]['message'] = "API-Error"; 
			}

			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);

			unset($_transaction[$_cur_num]);
			$this->session->set_userdata('cur_stocktake_num',"");
			$this->session->set_userdata('transaction',$_transaction);
			header("Refresh: 10; url='".base_url('/stocks')."'");
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
	 * Stocktake Detail
	 * To view and edit stocktake
	 * @param _input transaction code
	 */
	public function stocktake_detail($_input = "")
	{
		//$_transaction = [];
		if(!empty($_input))
		{
			$_show_discard = true;
			$_show_confirm = true;
			$_transaction = [];
			// Call API
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST').$_input);
			$this->component_api->CallGet();
			$_API = json_decode($this->component_api->GetConfig("result"), true);
			$_API = !empty($_API['query']) ? $_API['query'] : "";
			if(!empty($_API))
			{
				$_transaction[$_input] = $_API; 
				if($_transaction[$_input]['is_convert'])
				{
					$_show_discard = false;
					$_show_confirm = false;
				}
				$_login = $this->session->userdata("login");
		
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "Back", "type"=>"button", "id" => "back", "url"=> base_url('/stocks/'.$_login['preference']) ,"style" => "","show" => true],
						["name" => "Confirm", "type"=>"button", "id" => "confirm", "url"=> base_url('/stocks/stocktake/adjust/'.$_input) ,"style" => "btn btn-warning","show" => $_show_confirm],
						["name" => "Discard", "type"=>"button", "id" => "discard", "url"=> base_url('/stocks/stocktake/discard/'.$_input) ,"style" => "btn btn-danger","show" => $_show_discard]
					]
				]);
				$this->load->view('title-bar', [
					"title" => "Stocks -> Stocktake"
				]);
				//view content
				$this->load->view("stocks/stocks-stocktake-detail-view", [
					"data" => $_transaction[$_input],
					"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
					//"preview_url" => base_url('/ThePrint/adjustment/preview'),
					//"print_url" => base_url('/ThePrint/adjustment/save'),
					"function_bar" => $this->load->view('function-bar', [
						"btn" => [
							["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "new", "url"=>base_url('/customers/?new=1'), "style" => "", "show" => true]
						]
					],true)
				]);
				// persent footer view
				$this->load->view('footer');
			}
		}
	}
	/**
	 * Void Operation
	 * @param _num The trans_code to void
	 */
	public function stocktake_discard($_input = "")
	{
		if(!empty($_input))
		{
			$this->load->view("stocks/stocks-stocktake-discard-view", [
				"submit_to" => base_url("stocks/stocktake/discard/confirmed/".$_input),
				"to_deleted_num" => $_input,
				"return_url" => base_url("stocks/stocktake/detail/".$_input)
			]);
			$this->session->set_userdata('cur_stocktake_num',$_input);
		}
	}
		/**
	 * To save stocktake and adjust stock
	 */
	 public function stocktake_save_discard()
	 {
		 $result = [];
		 $_cur_num = $this->session->userdata('cur_stocktake_num');
		 $_login = $this->session->userdata("login");
		 
		 $this->load->view('function-bar', [
			 "btn" => [
				 ["name" => "<i class='fas fa-plus-circle'></i> Back", "type"=>"button", "id" => "Back", "url"=> base_url('/stocks/'.$_login['preference']),"style" => "","show" => true],
			 ]
		 ]);
		 
		 if(!empty($_cur_num))
		 {
			 // save transaction 
			 $this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST').$_cur_num);
			 $this->component_api->CallDelete();
			 $result[0] = json_decode($this->component_api->GetConfig("result"),true);
 
			 $alert = "danger";
			 if(isset($result[0]["error"]['code']))
			 {
				 switch($result[0]["error"]['code'])
				 {
					 case "00000":
						 $alert = "success";
					 break;
				 }					
			 }
			 else
			 {
				 $result[0]["error"]['code'] = "99999";
				 $result[0]["error"]['message'] = "API-Error"; 
			 }
 
			 $this->load->view('error-handle', [
				 'message' => $result[0]["error"]['message'], 
				 'code'=> $result[0]["error"]['code'], 
				 'alertstyle' => $alert
			 ]);
			 $this->session->set_userdata('cur_stocktake_num',"");
			 //header("Refresh: 10; url='".base_url('/stocks/'.$_login['preference'])."'");
		 }
		 else
		 {
			 $alert = "danger";
			 $result[0]["error"]['code'] = "90000";
			 $result[0]["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again"; 
			 $this->load->view('error-handle', [
				 'message' => $result[0]["error"]['message'], 
				 'code'=> $result[0]["error"]['code'], 
				 'alertstyle' => $alert
			 ]);
		 }
	 }
	/**
	 * Confirm stocktake and adjust stock
	 * @param _input The trans_code to void
	 */
	public function stocktake_adjust($_input = "")
	{
		$_transaction = [];
		// Call API
		$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST').$_input);
		$this->component_api->CallGet();
		$_API = json_decode($this->component_api->GetConfig("result"), true);
		$_API = !empty($_API['query']) ? $_API['query'] : "";
		if(!empty($_API))
		{
			$_transaction[$_input] = $_API; 
	
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_NEXT_NUM'));
			$this->component_api->CallGet();
			$_API_ADJ = json_decode($this->component_api->GetConfig("result"), true);
			$_API_ADJ = !empty($_API_ADJ['query']) ? $_API_ADJ['query'] : "";
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_PREFIX'));
			$this->component_api->CallGet();
			$_API_PREFIX = json_decode($this->component_api->GetConfig("result"), true);
			$_API_PREFIX = !empty($_API_PREFIX['query']) ? $_API_PREFIX['query'] : "";

			if(!empty($_API_ADJ))
			{
				$_transaction[$_input]['adj_num'] = $_API_ADJ;
				$_transaction[$_input]['prefix'] = $_API_PREFIX;
				$_transaction[$_input]['refer_num'] = $_input;
				$_transaction[$_input]['date'] = date("Y-m-d H:i:s");
				$_transaction[$_input]['remark'] = "Adjustment for stocktake";
				$_transaction[$_input]['shopcode'] = $_transaction[$_input]['shop_code'];
				foreach($_transaction[$_input]['items'] as $k => $v)
				{
					$_temp = 0;
					$_temp = ($v['qty'] - $v['stockonhand']);
					$_transaction[$_input]['items'][$k]['qty'] = $_temp;
				}
				$this->session->set_userdata('cur_stocktake_num',$_input);
				$this->session->set_userdata('transaction',$_transaction);
			}
		}
		$this->load->view("stocks/stocks-stocktake-adjust-view", [
			"submit_to" => base_url("stocks/stocktake/adjust/confirmed/".$_input),
			"to_deleted_num" => $_input,
			"return_url" => base_url("stocks/stocktake/detail/".$_input)
		]);
		//$this->load->view("footer");
	}
	/**
	 * To save stocktake and adjust stock
	 */
	public function stocktake_save_adjust()
	{
		$_refer_num = "";
		$result = [];
		$_cur_num = $this->session->userdata('cur_stocktake_num');
		$_transaction = $this->session->userdata('transaction');
		$_login = $this->session->userdata("login");
		
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> Back", "type"=>"button", "id" => "Back", "url"=> base_url('/stocks/'.$_login['preference']),"style" => "","show" => true],
			]
		]);
		
		if(!empty($_transaction[$_cur_num]) && isset($_transaction[$_cur_num]))
		{
			$_refer_num = $_transaction[$_cur_num]['refer_num'];
			$_api_body = json_encode($_transaction[$_cur_num],true);
			
			// save transaction 
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ'));
			$this->component_api->CallPost();
			$result[0] = json_decode($this->component_api->GetConfig("result"),true);

			$alert = "danger";
			if(isset($result[0]["error"]['code']))
			{
				switch($result[0]["error"]['code'])
				{
					case "00000":
						$alert = "success";
					break;
				}					
			}
			else
			{
				$result[0]["error"]['code'] = "99999";
				$result[0]["error"]['message'] = "API-Error"; 
			}

			$this->load->view('error-handle', [
				'message' => $result[0]["error"]['message'], 
				'code'=> $result[0]["error"]['code'], 
				'alertstyle' => $alert
			]);

			// update stocktake record status
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST').$_refer_num);
			$this->component_api->CallPatch();
			$result[1] = json_decode($this->component_api->GetConfig("result"),true);

			$alert = "danger";
			if(isset($result[1]["error"]['code']))
			{
				switch($result[1]["error"]['code'])
				{
					case "00000":
						$alert = "success";
					break;
				}					
			}
			else
			{
				$result[1]["error"]['code'] = "99999";
				$result[1]["error"]['message'] = "API-Error"; 
			}

			$this->load->view('error-handle', [
				'message' => $result[1]["error"]['message'], 
				'code'=> $result[1]["error"]['code'], 
				'alertstyle' => $alert
			]);

			unset($_transaction[$_cur_num]);
			$this->session->set_userdata('cur_stocktake_num',"");
			$this->session->set_userdata('transaction',$_transaction);
			header("Refresh: 10; url='".base_url('/stocks/'.$_login['preference'])."'");
		}
		else
		{
			$alert = "danger";
			$result[0]["error"]['code'] = "90000";
			$result[0]["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again"; 
			$this->load->view('error-handle', [
				'message' => $result[0]["error"]['message'], 
				'code'=> $result[0]["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
	}
}
