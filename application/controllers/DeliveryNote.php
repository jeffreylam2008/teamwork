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
			$_API_EMP = $this->component_api->GetConfig("result");
			$_API_EMP = !empty($_API_EMP['query']) ? $_API_EMP['query'] : ['username' => "", 'employee_code' => ""];
			$this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$this->_profile['shopcode']);
			$this->component_api->CallGet();
			$_API_SHOP = $this->component_api->GetConfig("result");
			$_API_SHOP = !empty($_API_SHOP['query']) ? $_API_SHOP['query'] : ['shop_code' => "", 'name' => ""];
			$this->component_api->SetConfig("url", $this->config->item('URL_MENU_SIDE'));
			$this->component_api->CallGet();
			$_API_MENU = $this->component_api->GetConfig("result");
			$_API_MENU = !empty($_API_MENU['query']) ? $_API_MENU['query'] : [];
			$this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE_PREFIX'));
			$this->component_api->CallGet();
			$_API_PREFIX = $this->component_api->GetConfig("result");
			$_API_PREFIX = !empty($_API_PREFIX['query']) ? $_API_PREFIX['query'] : [];
			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "DeliveryNote/dn_detail":
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
				"today" => date("Y-m-d"),
				"prefix" => $_API_PREFIX
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
    public function index()
    { 
    }

	/**
	 * Do New
	 * To kick start Delivery transaction
	 */
	public function donew()
	{
		// if(!empty($this->session->userdata('transaction')))
		// {
		// 	$this->session->unset_userdata('transaction');
		// }
		$this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE_NEXT_NUM'));
		$this->component_api->CallGet();
		$_API_INV = $this->component_api->GetConfig("result");
		$_API_INV = !empty($_API_INV['query']) ? $_API_INV['query'] : "";
		redirect(base_url("stocks/dn/create/".$_API_INV),"refresh");
	}

	/**
	 * Create
	 * To create new delivery note transaction
	 * @param _dn_num delivery not number
	 */
	public function create($_dn_num = "")
	{
		$_show_discard_btn = false;
		$_transaction = [];
		if(!empty($_dn_num))
		{
			// For back button after submit to tender page
			if(!empty($this->session->userdata('transaction')) && !empty($this->session->userdata('cur_dnnum')))
			{
				$_dn_num = $this->session->userdata('cur_dnnum');
				$_transaction = $this->session->userdata('transaction');
			}
			// For new create
			else 
			{
				$_transaction[$_dn_num]['items'] = [];
				$_transaction[$_dn_num]['dn_num'] = $_dn_num;
				$_transaction[$_dn_num]['date'] = date("Y-m-d H:i:s");
				$_transaction[$_dn_num]['cust_code'] = "";
				$_transaction[$_dn_num]['cust_name'] = "";
				$this->session->set_userdata('cur_dnnum',$_dn_num);
				$this->session->set_userdata('transaction',$_transaction);
			}
		}

		// fatch items API
		$this->component_api->SetConfig("url", $this->config->item('URL_ITEMS'));
		$this->component_api->CallGet();
		$_API_ITEMS = $this->component_api->GetConfig("result");
		$_API_ITEMS = !empty($_API_ITEMS['query']) ? $_API_ITEMS['query'] : "";
		// fatch shop code and shop detail API
		$this->component_api->SetConfig("url", $this->config->item('URL_SHOP'));
		$this->component_api->CallGet();
		$_API_SHOPS = $this->component_api->GetConfig("result");
		$_API_SHOPS = !empty($_API_SHOPS['query']) ? $_API_SHOPS['query'] : "";
		// fatch customer API
		$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS'));
		$this->component_api->CallGet();
		$_API_CUSTOMERS = $this->component_api->GetConfig("result");
		$_API_CUSTOMERS = !empty($_API_CUSTOMERS['query']) ? $_API_CUSTOMERS['query'] : "";
		// fatch payment method API
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
		$this->component_api->CallGet();
		$_API_PAYMENTS = json_decode($this->component_api->GetConfig("result"),true);
		$_API_PAYMENTS = !empty($_API_PAYMENTS['query']) ? $_API_PAYMENTS['query'] : "";
		//fatch DN number and set DN prefix
		$this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE_PREFIX'));
		$this->component_api->CallGET();
		$_API_DN_PREFIX = json_decode($this->component_api->GetConfig("result"),true);
		$_API_DN_PREFIX = !empty($_API_DN_PREFIX['query']) ? $_API_DN_PREFIX['query'] : "";
		$this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE_NEXT_NUM'));
		$this->component_api->CallGET();
		$_API_DN_NUM = json_decode($this->component_api->GetConfig("result"),true);
		$_API_DN_NUM = !empty($_API_DN_NUM['query']) ? $_API_DN_NUM['query'] : "";	
		//function bar
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-arrow-alt-circle-right'></i> ".$this->lang->line("function_go_next"), "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
				["name" => "<i class='fas fa-trash-alt'></i> ".$this->lang->line("function_discard"), "type"=>"button", "id" => "discard", "url"=> base_url('/invoices/discard'), "style" => "btn btn-danger", "show" => $_show_discard_btn]
			]
		]);
		//view title
		$this->load->view('title-bar', [
			"title" => $this->lang->line("dn_new_titles")
		]);
		//view content
		$this->load->view("stocks/dn/delivery-note-create-view", [
			"submit_to" => base_url("/DeliveryNote/process"),
			"data" => $_transaction[$_dn_num],
			"prefix" => $this->_inv_header_param['topNav']['prefix'],
			"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
			"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
			"default_per_page" => $this->_default_per_page,
			"preview_url" => base_url('/ThePrint/dn/preview'),
			"print_url" => base_url('/ThePrint/dn/save'),
			"ajax" => [
				"items" => $_API_ITEMS,
				"shop_code" => $_API_SHOPS,
				"customers" => $_API_CUSTOMERS,
				"tender" => $_API_PAYMENTS
			],
			"function_bar" => $this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "new", "url"=>base_url('/customers/?new=1'), "style" => "", "show" => true]
				]
			],true)
		]);
		// persent footer view
		$this->load->view('footer');
	}

	/**
	 * Process
	 * To confirm submit information
	 */
	public function process()
	{
		if(isset($_POST["i-post"]))
		{
			//Insert Post data to transaction
			$_data = json_decode($_POST['i-post'], true);
			$_transaction = [];
			$_cur_dnnum = $_data['dn_num'];
			$_show_save_btn = false;
			$_show_reprint_btn = false;

			$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS').$_data['cust_code']);
			$this->component_api->CallGet();
			$_API_CUSTOMER = json_decode($this->component_api->GetConfig("result"),true);
			$_API_CUSTOMER = !empty($_API_CUSTOMER['query']) ? $_API_CUSTOMER['query'] : "";

			$_transaction[$_cur_dnnum] = $_data;
			$_transaction[$_cur_dnnum]['customer'] = $_API_CUSTOMER;
			$this->session->set_userdata('cur_invoicenum',$_cur_dnnum);
			$this->session->set_userdata('transaction',$_transaction);

			// show save button
			if(isset($_transaction[$_cur_dnnum]['void']))
			{
				if(filter_var($_transaction[$_cur_dnnum]['void'], FILTER_VALIDATE_BOOLEAN))
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
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/stocks/dn/'.$_data['formtype'].'/'.$_data['dn_num']) ,"style" => "","show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> base_url("/stocks/dn/".$_the_form_type) , "style" => "btn btn-primary", "show" => $_show_save_btn],
					["name" => "<i class='far fa-file-alt'></i> ".$this->lang->line("function_preview"), "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "<i class='fas fa-print'></i> ".$this->lang->line("function_reprint"), "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn]
				]
			]);
			// render view
			$this->load->view("stocks/dn/delivery-note-process-view", [
				"submit_to" => base_url('/stock/dn/save'),
				"data" => $_transaction[$_cur_dnnum],
				"preview_url" => base_url('/ThePrint/invoices/preview'),
				"print_url" => base_url('/ThePrint/invoices/save')
			]);
			$this->load->view("footer");
		}
	}
	
	/**
	 * Show DN detail 
	 * @param _input transaction code
	 */
	 public function dn_detail($_input)
	 {
		$_transaction = [];
		// Call API
		$this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE').$_input);
		$this->component_api->CallGet();
		$_API_DN = $this->component_api->GetConfig("result");
		$_API_DN = !empty($_API_DN['query']) ? $_API_DN['query'] : "";

		$_login = $this->session->userdata("login");

		$_transaction[$_input] = $_API_DN;
		$this->session->set_userdata('cur_dnnum',$_input);
		$this->session->set_userdata('transaction',$_transaction);
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
			"data" => $_transaction[$_input],
			"default_shopcode" => $this->_inv_header_param["topNav"]['shop_code'],
			"preview_url" => base_url('/ThePrint/dn/preview'),
			"print_url" => base_url('/ThePrint/dn/save'),
			"function_bar" => $this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "new", "url"=>base_url('/customers/?new=1'), "style" => "", "show" => true]
					]
			],true)
		]);
	 }

	 /**
	 * Save DN
	 */
	public function save()
	{
		$_cur_dnnum = $this->session->userdata('cur_dnnum');
		$_transaction = $this->session->userdata('transaction');
		$alert = "danger";
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "donew", "url"=> base_url('/stocks/dn/donew'),"style" => "","show" => true],
			]
		]);
		if(!empty($_transaction[$_cur_dnnum]) && isset($_transaction[$_cur_dnnum]))
		{
			$_api_body = json_encode($_transaction[$_cur_dnnum],true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
		
			if($_api_body != null)
			{
				// create DN
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE'));
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
				if(isset($result["error"]['code']))
				{
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
				}
				else
				{
					$result["error"]['code'] = "99999";
					$result["error"]['message'] = "API-Error";
				}
				unset($_transaction[$_cur_dnnum]);
				$this->session->set_userdata('cur_dnnum',"");
				$this->session->set_userdata('transaction',$_transaction);
				header("Refresh: 8; url='".base_url('/stocks')."'");
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
	}
}