<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DeliveryNote extends CI_Controller 
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
			//API data
			$this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];
			// sidebar session
			$this->_param = $this->router->fetch_class().'/'.$this->router->fetch_method();
	
			switch($this->_param)
			{
				case "DeliveryNote/create":
					$this->_param = "stocks/index";
				break;
				case "DeliveryNote/process":
					$this->_param = "stocks/index";
				break;
				case "DeliveryNote/edit":
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
	 * Create
	 * To create new delivery note transaction
	 * @param _dn_num delivery not number
	 */
	public function create($_session_id = "",$_dn_num = "")
	{
		$_show_discard_btn = false;
		$_transaction = [];

		if(!empty($_session_id) && !empty($_dn_num))
		{
			$_show_discard_btn = true;

			// For back button after submit to tender page
			$_data = $this->session->userdata($_session_id);
			if( isset( $_data[$_dn_num] ) && !empty( $_data[$_dn_num] ) )
			{
				$_transaction = $_data[$_dn_num];
				$_transaction['dn_num'] = $_dn_num;
				$_transaction['prefix'] = $this->_inv_header_param["topNav"]['prefix'];
			}
			// For new create
			else 
			{
				$_transaction = [
					"items" => [],
					"dn_num" => $_dn_num,
					"cust_code" => "",
					"cust_name" => "",
					"date" => date("Y-m-d H:i:s"),
					"remark" => "", 
				];
			}
			// save transation to session
			$_sess[$_dn_num] = $_transaction;
			$this->session->set_tempdata($_session_id, $_sess, 600);

			// fatch items API
			$this->component_api->SetConfig("url", $this->config->item('URL_MASTER'));
			$this->component_api->CallGet();
			$_API_MASTER = $this->component_api->GetConfig("result");
			if(!empty($_API_MASTER['query']))
			{
				$_API_MASTER = $_API_MASTER['query'];
			}

			//function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks') ,"style" => "","show" => true],
					["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/router/dn/discard/'.$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);
			//view title
			$this->load->view('title-bar', [
				"title" => $this->lang->line("dn_new_titles")
			]);
			//view content
			$this->load->view("stocks/dn/delivery-note-create-view", [
				"submit_to" => base_url("/stocks/dn/process/".$_session_id),
				"discard_url" => base_url("/router/dn/discard/".$_session_id),
				"data" => $_sess[$_dn_num],
				"prefix" => $this->_inv_header_param['topNav']['prefix'],
				"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
				"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
				"default_per_page" => $this->_default_per_page,
				"preview_url" => base_url('/ThePrint/dn/preview'),
				"print_url" => base_url('/ThePrint/dn/save'),
				"ajax" => [
					"items" => $_API_MASTER['items'],
					"shop_code" => $_API_MASTER['shops'],
					"customers" => $_API_MASTER['customers'],
					"tender" => $_API_MASTER['paymentmethod']
				],
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
	 * Process
	 * To confirm submit information
	 */
	public function process($_session_id = "")
	{
		if(isset($_POST["i-post"]))
		{
			//Insert Post data to transaction
			$_data = json_decode($_POST['i-post'], true);
			$_transaction = [];
			$_cur_dnnum = $_data['dn_num'];
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
			$_transaction['customer']['delivery_remark'] = $_API_CUSTOMER['delivery_remark'];

			$_sess[$_cur_dnnum] = $_transaction;
			$_sess['cur_dnnum'] = $_cur_dnnum;
			$this->session->set_tempdata($_session_id, $_sess, 600);

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
			// echo "<pre>";
			// var_dump($_transaction);
			// echo "</pre>";
			
			// function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/dn/'.$_data['formtype'].'/'.$_session_id.'/'.$_data['dn_num']) ,"style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url("/stocks/dn/".$_the_form_type.'/'.$_session_id) , "style" => "btn btn-primary", "show" => $_show_save_btn],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn],
					["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/router/dn/discard/'.$_session_id), "style" => "btn btn-danger", "show" => $_show_discard_btn],
				]
			]);
			// render view
			$this->load->view("stocks/dn/delivery-note-process-view", [
				"data" => $_transaction,
				"discard_url" => base_url("/router/dn/discard/".$_session_id),
				"preview_url" => base_url('/ThePrint/dn/preview/'.$_session_id),
				"print_url" => base_url('/ThePrint/dn/save/'.$_session_id)
			]);
			$this->load->view("footer");
		}
	}
	
	/**
	 * Show DN detail 
	 * @param _session_id session_id
	 * @param _dn_num transaction code
	 */
	 public function edit($_session_id = "", $_dn_num = "")
	 {
		$_transaction = [];
		$_login = $this->session->userdata("login");

		// Call API
		$this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE').$_dn_num);
		$this->component_api->CallGet();
		$result = $this->component_api->GetConfig("result");

		if($result['http_code'] == 200)
		{
			$_transaction = $result != null ? $result : "";
			$result = $_transaction != null ? $_transaction : "";


			$_sess[$_dn_num] = $_transaction['query'];
			$_sess['cur_dnnum'] = $_dn_num;
			$this->session->set_tempdata($_session_id, $_sess, 600);


			
			if($_transaction['has'])
			{

				//function bar
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/'.$_login['preference']) ,"style" => "","show" => true],
						["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
						["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => true]
					]
				]);
				//view title
				$this->load->view('title-bar', [
					"title" => $this->lang->line("dn_new_titles")
				]);
				//view content
				$this->load->view("stocks/dn/delivery-note-detail-view", [
					"data" => $_transaction['query'],
					"discard_url" => base_url("/router/dn/discard/".$_session_id),
					"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
					"preview_url" => base_url('/ThePrint/dn/preview/'.$_session_id),
					"print_url" => base_url('/ThePrint/dn/save/'.$_session_id),
					"function_bar" => $this->load->view('function-bar', [
						"btn" => [
							["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "new", "url"=>base_url('/customers/?new=1'), "style" => "", "show" => true]
							]
					],true)
				]);
			}
		}
		else
		{
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('/stocks/'.$_login['preference']), "style" => "", "show" => true],
				]
			]);
		}
	 }

	 /**
	 * Save DN
	 * @param _session_id session id
	 */
	public function save($_session_id = "")
	{
		$_transaction = [];
		$_login = $this->session->userdata('login');
		$_data = $this->session->userdata($_session_id);

		if(isset($_data) )
		{
			$_cur_dnnum = $_data['cur_dnnum'];
			$_transaction = $_data[$_cur_dnnum];
		}
		$alert = "danger";
		$result = [];
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "donew", "url"=> base_url('/router/dn/create'),"style" => "","show" => true],
			]
		]);
		if(!empty($_transaction) && isset($_transaction))
		{
			$_api_body = json_encode($_transaction,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			// create DN
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE'));
			$this->component_api->CallPost();
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
		// header("Refresh: 10; url='".base_url('/stocks')."'");
	}
}