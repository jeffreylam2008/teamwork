<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GoodReceivedNote extends CI_Controller 
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
			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_PO_GRN_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix" => "",];
			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "GoodReceivedNote/create":
					$this->_param = "stocks/index";
				case "GoodReceivedNote/process":
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
			redirect(base_url("login?url=".urlencode($this->component_login->GetRedirectURL())),"refresh");
		}	
    }

	/**
	 * Create
	 * To create new goods received note transaction
	 * @param _grn_num Goods received note number
	 * @param _po_num purchase number
	 */
	public function create($_session_id = "",$_grn_num = "", $_po_num = "")
	{
		$_show_discard_btn = false;
		$_transaction = [];
		
		if(!empty($_session_id) && !empty($_grn_num))
		{
			$_show_discard_btn = true;

            // For back button after submit to tender page
			$_data = $this->session->userdata($_session_id);
			if( isset( $_data[$_grn_num] ) && !empty( $_data[$_grn_num] ) )
			{
				$_transaction = $_data[$_grn_num];
				$_transaction['prefix'] = $this->_inv_header_param["topNav"]['prefix'];
				$_transaction['po_num'] = $_po_num;
				$_transaction['date'] = date("Y-m-d H:i:s");
			}
			// For new create
			else 
			{
				$_transaction = [
					'items' => [],
					'date' => date("Y-m-d H:i:s"),
					'grn_num' => $_grn_num,
					'po_num' => $_po_num,
					'supp_code' => "",
					'supp_name' => "",
					'paymentmethod' => "",
					'paymentmethodname' => "",
					'remark' => "",
					'prefix' => $this->_inv_header_param["topNav"]['prefix'],
				];
			}
			// save transation to session
			$_sess[$_grn_num] = $_transaction;
			$this->session->set_tempdata($_session_id, $_sess, 600);

            // fatch items API
            $this->component_api->SetConfig("url", $this->config->item('URL_MASTER'));
            $this->component_api->CallGet();
            $_API_MASTER = $this->component_api->GetConfig("result");
			// echo "<pre>";
			// var_dump($_sess[$_grn_num]);
			// echo "</pre>";
            if(!empty($_API_MASTER['query']))
            {
                $_API_MASTER = $_API_MASTER['query'];
            }

			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks') ,"style" => "","show" => true],
					["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/router/grn/discard/'.$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);

			$this->load->view('title-bar', [
				"title" => $this->lang->line("grn_new_titles")
			]);
			
			$this->load->view("stocks/grn/goods-recevied-create-view", [
				"submit_to" => base_url("/stocks/grn/process/".$_session_id),
                "discard_url" => base_url("/router/grn/discard/".$_session_id),
                "data" => $_sess[$_grn_num],
				"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"ajax" => [
					"items" => $_API_MASTER['items'],
					"shop_code" => $_API_MASTER['shops'],
					"suppliers" => $_API_MASTER['suppliers'],
					"tender" => $_API_MASTER['paymentmethod']
				],
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
	 * Process
	 * To confirm submit information
	 */
	public function process($_session_id = "")
	{
		if(isset($_POST["i-post"]))
		{
			// variable initial
			$_data = json_decode($_POST['i-post'], true);

			$_transaction = [];

			$_cur_grnnum = $_data['grn_num'];
			$_show_save_btn = true;
			$_show_reprint_btn = false;
			$_show_discard_btn = true;
			
			$_transaction = $_data;
			$_sess[$_cur_grnnum] = $_transaction;
			$_sess['cur_grnnum'] = $_cur_grnnum;
			$this->session->set_tempdata($_session_id, $_sess, 600);

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
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/grn/'.$_data['formtype'].'/'.$_session_id.'/'.$_data['grn_num']) ,"style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url('/stocks/grn/'.$_the_form_type.'/'.$_session_id) , "style" => "","show" => $_show_save_btn],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/router/grn/discard/'.$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn],
				]
			]);

			//view content
			$this->load->view("stocks/grn/goods-recevied-process-view", [
				"data" => $_transaction,
				"discard_url" => base_url("/router/grn/discard/".$_session_id),
				"preview_url" => base_url('/ThePrint/grn/preview/'.$_session_id),
				"print_url" => base_url('/ThePrint/grn/save/'.$_session_id)
			]);
			$this->load->view('footer');
		}
	}
	
	/**
	 * Show GRN detail 
	 * @param _session_id session id
	 * @param _grn_num transaction code
	 */
	 public function edit($_session_id = "", $_grn_num = "")
	 {
		$_transaction = [];
		$_show_void_btn = false;
		$_API_MASTER = ['items' => "", 'shops' => "", 'suppliers'=> "", 'paymentmethod' => ""];
		$_login = $this->session->userdata("login");

		if(!empty($_session_id) && !empty($_grn_num))
		{
			// Call API
			$this->component_api->SetConfig("url", $this->config->item('URL_PO_GRN').$_grn_num);
			$this->component_api->CallGet();
			$_transaction = $this->component_api->GetConfig("result");
			$_transaction = $_transaction != null ? $_transaction : "";
			
			// fatch items API
			$this->component_api->SetConfig("url", $this->config->item('URL_MASTER'));
			$this->component_api->CallGet();
			$_API_MASTER = $this->component_api->GetConfig("result");
			$_API_MASTER = $_API_MASTER['query'] != null ? $_API_MASTER['query'] : "";

			$_data[$_grn_num] = $_transaction['query'];
			$_data['cur_grnnum'] = $_grn_num;
			$this->session->set_tempdata($_session_id, $_data, 600);
		}
		if(!empty($_transaction['query']) && $_transaction['has'])
		{		
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/'.$_login['preference']) ,"style" => "","show" => true],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => true],
					["name" => "<i class='fas fa-sliders-h'></i> ".$this->lang->line("adjustment"), "type"=>"button", "id" => "adjustment", "url"=> base_url('/stocks/donewadj/'.$_grn_num) , "style" => "btn btn-warning" , "show" => true]
				]
			]);
			$this->load->view('title-bar', [
				"title" => $this->lang->line("grn")
			]);
			//view content
			$this->load->view("stocks/grn/goods-recevied-detail-view", [
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "new", "url"=>base_url('/customers/?new=1'), "style" => "", "show" => true]
					]
				],true),
				"discard_url" => base_url("/router/grn/discard/".$_session_id),
				"date" => date("Y-m-d H:i:s"),
				"ajax" => [
					"items" => $_API_MASTER['items'],
					"shop_code" => $_API_MASTER['shops'],
					"customers" => $_API_MASTER['suppliers'],
					"tender" => $_API_MASTER['paymentmethod']
				],
				"data" => $_transaction['query'],
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"preview_url" => base_url('/ThePrint/grn/preview/'.$_session_id),
				"print_url" => base_url('/ThePrint/grn/save/'.$_session_id),
				
			]);
		}
		else
		{
			redirect(base_url("/stocks"),"refresh");
		}
	 }

	 /**
	 * Save GRN
	 * @param _session_id session id
	 */
	public function save($_session_id = "")
	{
		$_transaction = [];
		$_login = $this->session->userdata('login');
		$_data = $this->session->userdata($_session_id);
		$result = [];
		if(isset($_data))
		{
			$_cur_grnnum = $_data['cur_grnnum'];
			$_transaction = $_data[$_cur_grnnum];
		}
		$alert = "danger";
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/'.$_login['preference']) ,"style" => "","show" => true],
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/router/grn/create/'),"style" => "","show" => true],
			]
		]);

		if(!empty($_transaction) && isset($_transaction))
		{
			$_api_body = json_encode($_transaction,true);

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
				$_result = $this->component_api->GetConfig("result");

				switch($_result["http_code"])
				{
					case 200:
						$alert = "success";
					break;
					case 404:
						$alert = "danger";
					break;
				}
				$this->load->view('error-handle', [
					'message' => $_result["error"]['message'], 
					'code'=> $_result["error"]['code'], 
					'alertstyle' => $alert
				]);
			}
			else
			{
				$alert = "danger";
				$_result["error"]['code'] = "90000";
				$_result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again"; 
				$this->load->view('error-handle', [
					'message' => $_result["error"]['message'], 
					'code'=> $_result["error"]['code'], 
					'alertstyle' => $alert
				]);
			}
			$this->session->unset_userdata($_session_id);
			//header("Refresh: 10; url='".base_url('/stocks')."'");
		}
    }
}