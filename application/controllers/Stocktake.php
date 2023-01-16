<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocktake extends CI_Controller 
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
	 * constructor
	 */
    public function __construct()
	{
        parent::__construct();
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
		$this->load->library("Component_Login",[$this->_token, "stocks"]);

        // // login session
        if(!empty($this->component_login->CheckToken()))
        {
			//API data
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];
			// sidebar session
			$this->_param = $this->router->fetch_class().'/'.$this->router->fetch_method();
	
			switch($this->_param)
			{
				case "Stocktake/create":
					$this->_param = "stocks/index";
				break;
				case "Stocktake/process":
					$this->_param = "stocks/index";
				break;
				case "Stocktake/edit":
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
			redirect(base_url("login?url=".urlencode($this->component_login->GetRedirectURL())),"auto");
		}	
    }

	/**
	 * Stocktake Create
	 * To view and create of stocktake function
	 * @param _session_id transaction session ID 
	 * @param _st_num stocktake number for transaction
	 */
    public function create($_session_id = "", $_st_num = "")
    {
        $_show_discard_btn = false;
		$_transaction = [];
		if(!empty($_session_id) && !empty($_st_num))
		{
			$_show_discard_btn = true;
            $_data = $this->session->userdata($_session_id);
            if(isset($_data[$_st_num]) && !empty($_data[$_st_num]))
			{
				$_transaction = $_data[$_st_num];
			}
            // For new create
			else 
			{
				$_transaction = [
					'items' => [],
					'trans_code' => $_st_num,
					'refer_num' => "",
					'date' => date("Y-m-d H:i:s"),
					'remark' => "",
				];
			}
            $_sess[$_st_num] = $_transaction;
			$this->session->set_tempdata($_session_id, $_sess, 600);

			// fatch API
			$this->component_api->SetConfig("url", $this->config->item('URL_MASTER'));
			$this->component_api->CallGet();
			$_API_MASTER = $this->component_api->GetConfig("result");
			if(!empty($_API_MASTER['query']))
			{
				$_API_MASTER = $_API_MASTER['query'];
			}
		
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks') ,"style" => "","show" => true],
					["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "btn btn-primary", "show" => true],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/router/stocktake/discard/'.$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);

			$this->load->view('title-bar', [
				"title" => $this->lang->line("stocktake")
			]);
			
			$this->load->view("stocks/st/stocks-stocktake-create-view",[
				"submit_to" => base_url("stocks/stocktake/process/".$_session_id),
                "discard_url" => base_url("/router/stocktake/discard/".$_session_id),
				"prefix" => $this->_inv_header_param['topNav']['prefix'],
				"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"ajax" => [
					"items" => $_API_MASTER['items']
				],
				"export_url" => base_url("export/stocktake"),
				"import_url" => base_url("import/stocktake"),
				"data" => $_transaction
			]);
			$this->load->view('footer');
		}
    }
	
	/**
	 * Stocktake Detail
	 * To view and edit stocktake function
	 * @param _session_id transaction session ID 
	 * @param _input transaction code
	 */
	public function edit($_session_id = "", $_input = "")
	{
		//$_transaction = [];
		if(!empty($_input))
		{
			$_show_discard = true;
			$_show_confirm = true;
			$_show_next = true;
			$_show_reprint_btn = false;
			$_transaction = [];
			$_transaction['idisabled'] = "";
			$_data = $this->session->userdata($_session_id);
			$_login = $this->session->userdata("login");

			// pull data from session
            if(isset($_data[$_input]) && !empty($_data[$_input]))
			{
				$_transaction = $_data[$_input];
				$_transaction['idisabled'] = "";
			}
			// pull data from API
			else
			{
				// Call API
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST').$_input);
				$this->component_api->CallGet();
				$_API = $this->component_api->GetConfig("result");
				$_API = !empty($_API['query']) ? $_API['query'] : "";
				$_data[$_input] = $_API;
				$_transaction = $_data[$_input];
				$_transaction['idisabled'] = "";
			}
			$_sess[$_input] = $_transaction;
			$_sess['cur_stocktakenum'] = $_input;
			$this->session->set_tempdata($_session_id, $_sess, 600);

			// fatch API
			$this->component_api->SetConfig("url", $this->config->item('URL_MASTER'));
			$this->component_api->CallGet();
			$_API_MASTER = $this->component_api->GetConfig("result");
			if(!empty($_API_MASTER['query']))
			{
				$_API_MASTER = $_API_MASTER['query'];
			}

			if(!empty($_transaction))
			{
				if($_transaction['is_convert'])
				{
					$_show_discard = false;
					$_show_confirm = false;
					$_show_next = false;
					$_show_reprint_btn = true;
					$_transaction['idisabled'] = "disabled";
				}
				// echo "<pre>";
				// var_dump($_transaction);
				// echo "</pre>";
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/'.$_login['preference']) ,"style" => "","show" => true],
						["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "btn btn-primary", "show" => $_show_next],
						["name" => "<i class='fas fa-check-circle'></i> ".$this->lang->line("label_stocktakeconfirmadjust"), "type"=>"button", "id" => "confirm", "url"=> base_url('/stocks/stocktake/adjust/'.$_session_id.'/'.$_input) ,"style" => "btn btn-warning","show" => $_show_confirm],
						["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn],
						["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "delete", "url"=> base_url('/stocks/stocktake/delete/'.$_session_id.'/'.$_input) ,"style" => "btn btn-danger","show" => $_show_discard]
					]
				]);
				$this->load->view('title-bar', [
					"title" => $this->lang->line("stocktake") . $this->lang->line("function_edit") 
				]);
				//view content
				$this->load->view("stocks/st/stocks-stocktake-edit-view", [
					"submit_to" => base_url("stocks/stocktake/process/".$_session_id),
                	"discard_url" => base_url("/router/stocktake/discard/".$_session_id),
					"prefix" => $this->_inv_header_param['topNav']['prefix'],
					"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
					"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
					"default_per_page" => $this->_default_per_page,
					"page" => $this->_page,
					"ajax" => [
						"items" => $_API_MASTER['items']
					],
					"data" => $_transaction,
					// "preview_url" => base_url('/ThePrint/adjustment/preview'),
					"print_url" => base_url('/ThePrint/stocktake/save/'.$_session_id)
					// "function_bar" => $this->load->view('function-bar', [
					// 	"btn" => [
					// 		["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "new", "url"=>base_url('/customers/?new=1'), "style" => "", "show" => true]
					// 	]
					// ],true)
				]);
				// persent footer view
				$this->load->view('footer');
			}
		}
	}
    
	/**
	 * Process
	 * To confirm submit information
	 * @param _session_id transaction session ID 
	 */
	public function process($_session_id = "")
    {
		$_show_discard_btn = false;
        // echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
		if(isset($_POST["i-post"]))
		{
			// variable initial
			$_data = json_decode($_POST['i-post'], true);
			$_transaction = [];
            $_cur_num = $_data['trans_code'];
			$_show_save_btn = true;
			$_show_reprint_btn = false;
			$_show_discard_btn = true;

            $_transaction = $_data;
			$_sess[$_cur_num] = $_transaction;
			$_sess['cur_stocktakenum'] = $_cur_num;
			$this->session->set_tempdata($_session_id, $_sess, 600);	
			// echo "<pre>";
			// var_dump( $_transaction );
			// echo "</pre>";
			
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
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/stocktake/'.$_data['formtype']."/".$_session_id.'/'.$_data['trans_code']) ,"style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url('/stocks/stocktake/'.$_the_form_type."/".$_session_id) , "style" => "btn btn-primary","show" => $_show_save_btn],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url("/router/stocktake/discard/".$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);

			// render view
			$this->load->view("stocks/st/stocks-stocktake-process-view", [
				"data" => $_transaction,
				"discard_url" => base_url("/router/stocktake/discard/".$_session_id),
				"preview_url" => base_url('/ThePrint/stocktake/preview/'.$_session_id),
				"print_url" => base_url('/ThePrint/stocktake/save/'.$_session_id)
			]);
		}
		$this->load->view('footer');
    }

	/**
	 * Stocktake Save
	 * To save data to DB
	 * @param _session_id transaction session ID 
	 */
	public function save($_session_id = "")
	{
		$_transaction = [];
		$_login = $this->session->userdata('login');
		$_data = $this->session->userdata($_session_id);
		$_result = [];
		$_alert = "danger";
		if(isset($_data))
		{
			$_cur_num = $_data['cur_stocktakenum'];
			$_transaction = $_data[$_cur_num];
		}
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/'.$_login['preference']) ,"style" => "","show" => true],
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/router/stocktake/create/'),"style" => "btn btn-primary","show" => true],
			]
		]);

		if(!empty($_transaction) && isset($_transaction))
		{
			$_api_body = json_encode($_transaction,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			if($_api_body != null)
			{
				// save transaction 
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST'));
				$this->component_api->CallPost();
				$_result = $this->component_api->GetConfig("result");
				
				switch($_result["http_code"])
				{
					case 200:
						$_alert = "success";
					break;
					case 404:
						$_alert = "danger";
					break;
				}
				$this->load->view('error-handle', [
					'message' => $_result["error"]['message'], 
					'code'=> $_result["error"]['code'], 
					'alertstyle' => $_alert
				]);
			}
		}
		else
		{
			$_result["error"]['code'] = "90000";
			$_result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again"; 
			$this->load->view('error-handle', [
				'message' => $_result["error"]['message'], 
				'code'=> $_result["error"]['code'], 
				'alertstyle' => $_alert
			]);
		}
		$this->session->unset_userdata($_session_id);
	}

	/**
	 * Save stocktake modify
	 * To save data to DB
	 * @param _session_id transaction session ID 
	 */
	public function saveedit($_session_id = "")
	{
		$_transaction = [];
		$_login = $this->session->userdata('login');
		$_data = $this->session->userdata($_session_id);
		$_result = [];
		$_alert = "danger";
		if(isset($_data))
		{
			$_cur_num = $_data['cur_stocktakenum'];
			$_transaction = $_data[$_cur_num];
		}
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/'.$_login['preference']) ,"style" => "","show" => true],
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/router/stocktake/create/'),"style" => "","show" => true],
			]
		]);

		// echo "<pre>";
		// var_dump($_transaction);
		// echo "</pre>";
		if(!empty($_transaction) && isset($_transaction))
		{
			$_api_body = json_encode($_transaction,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			if($_api_body != null)
			{
				// save transaction 
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST').$_cur_num);
				$this->component_api->CallPatch();
				$_result = $this->component_api->GetConfig("result");
				
				switch($_result["http_code"])
				{
					case 200:
						$_alert = "success";
					break;
					case 404:
						$_alert = "danger";
					break;
				}
				$this->load->view('error-handle', [
					'message' => $_result["error"]['message'], 
					'code'=> $_result["error"]['code'], 
					'alertstyle' => $_alert
				]);
			}
		}
		else
		{
			$_result["error"]['code'] = "90000";
			$_result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again"; 
			$this->load->view('error-handle', [
				'message' => $_result["error"]['message'], 
				'code'=> $_result["error"]['code'], 
				'alertstyle' => $_alert
			]);
		}
		$this->session->unset_userdata($_session_id);
	}

	/**
	 * Void Operation
	 * to delete stocktake transaction
	 * @param _session_id transaction session ID 
	 * @param _input The trans_code to void
	 */
	public function delete($_session_id = "", $_input = "")
	{
		$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST').$_input);
		$this->component_api->CallGet();
		$_API = $this->component_api->GetConfig("result");
		$_API = !empty($_API['query']) ? $_API['query'] : "";
		if(!empty($_API))
		{
			$_sess[$_input] = $_API;
			$_sess['cur_stocktakenum'] = $_input;
			$this->session->set_tempdata($_session_id, $_sess, 600);

			$this->load->view("stocks/st/stocks-stocktake-delete-view", [
				"submit_to" => base_url("stocks/stocktake/confirmed/delete/".$_session_id),
				"to_deleted_num" => $_input,
				"return_url" => base_url("stocks/stocktake/edit/".$_session_id."/".$_input)
			]);
			//$this->session->set_userdata('cur_stocktake_num',$_input);
		}

		$this->load->view("footer");
	}
	/**
	 * savedelete
	 * To save stocktake and adjust stock
	 * @param _session_id transaction session ID 
	 */
	public function savedelete($_session_id = "")
	{
		
		$_data = $this->session->userdata($_session_id);
		$_login = $this->session->userdata('login');
		$_result = [];
		$_alert = "danger";

		if(isset($_data))
		{
			$_cur_num = $_data['cur_stocktakenum'];
			// $_transaction = $_data[$_cur_num];
		}
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/stocks/'.$_login['preference']),"style" => "","show" => true],
			]
		]);
		
		if(!empty($_cur_num))
		{
			// save transaction 
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST').$_cur_num);
			$this->component_api->CallDelete();
			$_result = $this->component_api->GetConfig("result");
			
			// var_dump($_result);
			$_alert = "danger";
			switch($_result["http_code"])
			{
				case 200:
					$_alert = "success";
				break;
				case 404:
					$_alert = "danger";
				break;
			}
			$this->load->view('error-handle', [
				'message' => $_result["error"]['message'], 
				'code'=> $_result["error"]['code'], 
				'alertstyle' => $_alert
			]);

			// clear session
			$this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTION_DISCARD').$_cur_num);
			$this->component_api->CallDelete();
			$this->component_api->GetConfig("result");
			$this->session->unset_userdata($_session_id);
			//header("Refresh: 10; url='".base_url('/stocks/'.$_login['preference'])."'");
		}
		else
		{
			$_result["error"]['code'] = "90000";
			$_result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again"; 
			$this->load->view('error-handle', [
				'message' => $_result["error"]['message'], 
				'code'=> $_result["error"]['code'], 
				'alertstyle' => $_alert
			]);
		}
		$this->load->view("footer");
	}

	/**
	 * confirm and approve
	 * To adjust stocktake when approve
	 */
	public function adjust($_session_id = "", $_input = "")
	{
		$_transaction = [];
		$_alert = "danger";
		// Call API		
		$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST').$_input);
		$this->component_api->CallGet();
		$_API = $this->component_api->GetConfig("result");
		$_API = !empty($_API['query']) ? $_API['query'] : "";
		$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_NEXT_NUM').$_session_id);
		$this->component_api->CallGet();
		$_API_ADJ = $this->component_api->GetConfig("result");
		$_API_ADJ = !empty($_API_ADJ['query']) ? $_API_ADJ['query'] : "";
		$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_PREFIX'));
		$this->component_api->CallGet();
		$_API_PREFIX = $this->component_api->GetConfig("result");
		$_API_PREFIX = !empty($_API_PREFIX['query']) ? $_API_PREFIX['query'] : "";
		
		// echo "<pre>";
		// var_dump($_API);
		// echo "</pre>";
		if(empty($_API) || empty($_API_ADJ) || empty($_API_PREFIX))
		{
			// return to main page or show error page
			$_result["error"]['code'] = "90404";
			$_result["error"]['message'] = "API Problem - No respond on API call. Please try again later!"; 
			$this->load->view('error-handle', [
				'message' => $_result["error"]['message'], 
				'code'=> $_result["error"]['code'], 
				'alertstyle' => $_alert
			]);
		}
		else
		{
			$_transaction = $_API;
			// echo "<pre>";
			// var_dump($_transaction);
			// echo "</pre>";
			$_transaction['adj_num'] = $_API_ADJ;
			$_transaction['prefix'] = $_API_PREFIX;
			$_transaction['refer_num'] = $_input;
			$_transaction['date'] = date("Y-m-d H:i:s");
			$_transaction['remark'] = "Adjustment for stocktake";
			$_transaction['is_convert'] = 1;
			foreach($_transaction['items'] as $k => $v)
			{
				$_temp = 0;
				$_temp = ($v['qty'] - $v['stockonhand']);
				$_transaction['items'][$k]['qty'] = $_temp;
			}

			// make copy of stocktake transaction array to adjustment transaction array
			$_sess[$_API_ADJ] = $_transaction;
			$_sess['cur_stocktakenum'] = $_input;
			$_sess['_cur_adj_num'] = $_API_ADJ;
			$this->session->set_tempdata($_session_id, $_sess, 600);	

			$this->load->view("/stocks/st/stocks-stocktake-adjust-view", [
				"submit_to" => base_url("/stocks/adj/save/".$_session_id),
				"to_deleted_num" => $_input,
				"return_url" => base_url("/stocks/stocktake/edit/".$_session_id."/".$_input)
			]);
			
		}
		$this->load->view("footer");
	}
}