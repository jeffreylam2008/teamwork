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
		$this->load->library("Component_Login",[$this->_token, "stocks/index"]);

		// // login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCKS_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];
			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				// case "stocks/grn":
				// 	$this->_param = "stocks/index";
				// break;
				// case "stocks/grn_confirm":
				// 	$this->_param = "stocks/index";
				// break;
				// case "stocks/adjust":
				// 	$this->_param = "stocks/index";
				// break;
				// case "stocks/grn_detail":
				// 	$this->_param = "stocks/index";
				// break;
				// case "stocks/dn_detail":
				// 	$this->_param = "stocks/index";
				// break;
				case "stocks/stocktake":
					$this->_param = "stocks/index";
				break;
				case "stocks/stocktake_detail":
					$this->_param = "stocks/index";
				break;
			}
			// header data
			$this->_inv_header_param["topNav"] = [
				"isLogin" => true,
				"username" => $this->_API_HEADER['employee']['username'],
				"employee_code" => $this->_API_HEADER['employee']['employee_code'],
				"shop_code" => $this->_API_HEADER['employee']['shop_code'],
				"shop_name" => $this->_API_HEADER['employee']['shop_name'],
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
			$this->component_sidemenu->SetConfig("nav_list", $this->_API_HEADER['menu']);
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
			'i-supp-code' => $this->input->get('i-supp-code'),
			'page' => htmlspecialchars($this->_page),
			'show' => htmlspecialchars($this->_default_per_page),
		];
		
		if(!empty($_query))
		{
			//Set user preference
			$_q_str = $this->component_uri->QueryToString($_query);
			$_login = $this->session->userdata('login');
			$_login['preference'] = $_q_str;
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
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCKS').$_q_str);
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
					["name" => "<i class='fa-solid fa-people-carry-box'></i> ".$this->lang->line("dn_short"), "type"=>"button", "id" => "i-grn", "url"=> base_url('/router/dn/create'), "style" => "", "show" => true, "extra" => ""],
					["name" => "<i class='fa-solid fa-dolly'></i> ".$this->lang->line("grn_short"), "type"=>"button", "id" => "i-grn", "url"=> base_url('/router/grn/create'), "style" => "", "show" => true, "extra" => ""],
					["name" => "<i class='fas fa-adjust'></i> ".$this->lang->line("adjustment_short"), "type"=>"button", "id" => "i-adj", "url"=> base_url('/router/adjustments/create'), "style" => "", "show" => true, "extra" => ""],
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
				"edit_url" => base_url("/router/warehouse/view/"),
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
	 * Create new Stock Take
	 */
	public function donew_stocktake()
	{
		$_transaction = [];
		// if(!empty($this->session->userdata('transaction')))
		// {
		// 	$this->session->unset_userdata('transaction');
		// }
		
		$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API = $this->component_api->GetConfig("result");
		$_API = !empty($_API['query']) ? $_API['query'] : "";

		// $this->session->set_userdata('cur_stocktake_num',$_API);
		// $this->session->set_userdata('transaction',$_transaction);
	
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
			$_API_ITEMS = $this->component_api->GetConfig("result");
			$_API_ITEMS = !empty($_API_ITEMS['query']) ? $_API_ITEMS['query'] : "";

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
			$_API = $this->component_api->GetConfig("result");
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
		$_API = $this->component_api->GetConfig("result");
		$_API = !empty($_API['query']) ? $_API['query'] : "";
		if(!empty($_API))
		{
			$_transaction[$_input] = $_API; 
	
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_NEXT_NUM'));
			$this->component_api->CallGet();
			$_API_ADJ = $this->component_api->GetConfig("result");
			$_API_ADJ = !empty($_API_ADJ['query']) ? $_API_ADJ['query'] : "";
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_PREFIX'));
			$this->component_api->CallGet();
			$_API_PREFIX = $this->component_api->GetConfig("result");
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
