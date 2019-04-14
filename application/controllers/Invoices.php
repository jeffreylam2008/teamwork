<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_token = "";
	var $_param = "";
	public function __construct()
	{
		parent::__construct();
		$_API_EMP = [];
		// $this->load->library("Component_Master");
		// if(isset($this->session->userdata['master']))
		// {
			// dummy data
			// $this->session->sess_destroy();
			// echo "<pre>";
			// var_dump(($_SESSION['master']));
			// echo "</pre>";
			// call token from session
			if(!empty($this->session->userdata['login']))
			{
				$this->_token = $this->session->userdata['login']['token'];
			}

			// API call
			$this->load->library("Component_Login",[$this->_token, "quotations/list"]);

			// // login session
			if(!empty($this->component_login->CheckToken()))
			{
				$this->_username = $this->session->userdata['login']['profile']['username'];
				// fatch employee API

				// API data
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employees/".$this->_username);
				$this->component_api->CallGet();
				$_API_EMP = json_decode($this->component_api->GetConfig("result"), true);
				$_API_EMP = $_API_EMP['query'];
				// dummy data
				// sidebar session
				$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
				switch($this->_param)
				{
					case "invoices/edit":
						$this->_param = "invoices/invlist";
					break;
					case "invoices/tender":
						$this->_param = "invoices/invlist";
					break;
				}
				
				// fatch employee API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employee/".$this->_username );
				$this->component_api->CallGet();
				$_employee = json_decode($this->component_api->GetConfig("result"),true);
				//var_dump($_employee);
				$this->_inv_header_param["topNav"] = [
					"isLogin" => true,
					"username" => $_API_EMP['username'],
					"employee_code" => $_API_EMP['employee_code'],
					"shop_code" => $_API_EMP['default_shopcode'],
					"today" => date("Y-m-d"),
					"prefix" => "INV"
				];

				// fatch side bar API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
				$this->component_api->CallGet();
				$_API_MENU = json_decode($this->component_api->GetConfig("result"), true);
				$_API_MENU = $_API_MENU['query'];
				$this->component_sidemenu->SetConfig("nav_list", $_API_MENU);
				$this->component_sidemenu->SetConfig("active", $this->_param);
				$this->component_sidemenu->Proccess();

				// render the view
				$this->load->view('header',[
					'title'=>'Invoices',
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
		// }
		// else
		// {
		// 	redirect(base_url("master"),"refresh");
		// }			
	}
	public function index()
	{

	}
	public function donew()
	{
		// if(!empty($this->session->userdata('transaction')))
		// {
		// 	$_transaction = $this->session->userdata('transaction');
		// 	$_tran = array_keys($_transaction);
		// 	$_invoice_num = $_tran[0];
		// }
		if(!empty($this->session->userdata('transaction')))
		{
			$this->session->unset_userdata('transaction');
		}
		$_invoice_num = $this->_inv_header_param['topNav']['prefix'].date("Ymds");
		redirect(base_url("invoices/create/".$_invoice_num));
	}
	public function convert($_quotation_num = "")
	{
		if(!empty($this->session->userdata('transaction')))
		{
			$this->session->unset_userdata('transaction');
		}
		$_invoice_num = $this->_inv_header_param['topNav']['prefix'].date("Ymds");
		redirect(base_url("invoices/create/".$_invoice_num."/".$_quotation_num));
	}
	public function create($_invoice_num = "", $_quotation_num = "")
	{
		// variable initial
		$_default_per_page = 50;
		$_show_discard_btn = false;
		$_show_transaction_data = "";
		$_cur_invoicenum = "";
		$_transaction = [];
		$_items_list = [];
		$_shopcode_list = [];
		$_cust_list = [];
		$_tender = [];

		if(!empty($_invoice_num))
		{
			$_show_discard_btn = true;
			// create invoice	
			if((substr($_invoice_num , 0 , 3) === $this->_inv_header_param["topNav"]['prefix']) && (strlen($_invoice_num) == 13))
			{	
				// For back button after submit to tender page
				if(!empty($this->session->userdata('transaction')) && !empty($this->session->userdata('cur_invoicenum')))
				{
					$_invoice_num = $this->session->userdata('cur_invoicenum');
					$_transaction = $this->session->userdata('transaction');
				}
				// For new create
				else 
				{
					$_transaction[$_invoice_num] = [];
				}
				$_show_transaction_data = $_transaction[$_invoice_num];
				
				$this->session->set_userdata('cur_invoicenum',$_invoice_num);
				$this->session->set_userdata('transaction',$_transaction);
			}
		
			// Convert from quotation
			if(!empty($_quotation_num))
			{
				// Check Quotation exist
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/quotations/".$_quotation_num);
				$this->component_api->CallGet();
				$_quotation = json_decode($this->component_api->GetConfig("result"),true);
				$_quotation['query']['invoicenum'] = $_invoice_num;
				$_quotation['query']['date'] = date("Y-m-d H:i:s");
				$_show_transaction_data = $_quotation['query'];
			}
			
		// echo "<pre>";
		// var_dump($_SESSION);
		// echo "</pre>";


			// fatch items API
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/");
			$this->component_api->CallGet();
			$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
			// fatch shop code and shop detail API
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
			$this->component_api->CallGet();
			$_API_SHOPS = json_decode($this->component_api->GetConfig("result"), true);
			// fatch customer API
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
			$this->component_api->CallGet();
			$_API_CUSTOMERS = json_decode($this->component_api->GetConfig("result"), true);
			// fatch payment method API
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/method/");
			$this->component_api->CallGet();
			$_API_PAYMENT = json_decode($this->component_api->GetConfig("result"),true);

			
			// var_dump($_theprint_data);
			// function bar with next, preview and save button
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "Next", "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
					["name" => "Discard", "type"=>"button", "id" => "discard", "url"=> base_url('/invoices/discard'), "style" => "btn btn-danger", "show" => $_show_discard_btn]
				]
			]);


			// present form view
			$this->load->view('invoices/invoices-create-view', [
				"submit_to" => base_url("/invoices/tender"),
				"prefix" => $this->_inv_header_param['topNav']['prefix'],
				"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
				"quotation" => $_quotation_num,
				"invoice_num" => $_invoice_num,
				"date" => date("Y-m-d H:i:s"),
				"items" => [
					0 => [
						"item_code" => "",
						"eng_name" => "",
						"chi_name" => "",
						"qty" => "",
						"unit" => "",
						"price" => "",
					]
				],
				"total" => 0,
				"ajax" => [
					"items" => $_API_ITEMS['query'],
					"shop_code" => $_API_SHOPS['query'],
					"customers" => $_API_CUSTOMERS['query'],
					"tender" => $_API_PAYMENT['query']
				],
				"theprint_data" => $_show_transaction_data,
				"default_per_page" => $_default_per_page
			]);
			// persent footer view
			$this->load->view('footer');
		}
	}
	public function edit($_invoice_num = "")
	{
		// variable initial
		$_default_per_page = 50;
		$_show_transaction_data = "";
		$_items_list = [];
		$_shopcode_list = ["query" =>[]];
		$_cust_list = [];
		$_tender = [];
		$_invoices = [];
		$_items_list = [];
		$_shopcode_list = [];

		if(!empty($_invoice_num))
		{
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/invoices/".$_invoice_num);
			$this->component_api->CallGet();
			$_invoices = json_decode($this->component_api->GetConfig("result"),true);
	
			// set current invoice number to session
			//$this->session->set_userdata('transaction',$_transaction);
			$this->session->set_userdata('cur_invoicenum',$_invoice_num);
			
			// unset($_SESSION['transaction']);
			// unset($_SESSION['cur_invoicenum']);

			// echo "<pre>";
			// var_dump($_invoices);
			// echo "</pre>";

			if($_invoices['has'])
			{
				// variable initial
				$_show_void_btn = false;
				$_show_transaction_data = $_invoices['query'];

				$_today = date_create($this->_inv_header_param['topNav']['today']);
				$_date = date_create(date("Y-m-d",strtotime($_invoices['query']['date'])));
				$_diff = date_diff($_today,$_date);
				
				$_the_date_diff = $_diff->format("%a");
				// check invoice date was same with today
				if($_the_date_diff =! 0){
					$_show_void_btn = true;
				}

				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/");
				$this->component_api->CallGet();
				$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
				// fatch shop code and shop detail API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
				$this->component_api->CallGet();
				$_API_SHOPS = json_decode($this->component_api->GetConfig("result"), true);
				// fatch customer API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
				$this->component_api->CallGet();
				$_API_CUSTOMERS = json_decode($this->component_api->GetConfig("result"), true);
				// fatch payment method API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/method/");
				$this->component_api->CallGet();
				$_API_PAYMENTS = json_decode($this->component_api->GetConfig("result"),true);

				// function bar with next, preview and save button
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "Back", "type"=>"button", "id" => "Back", "url"=> base_url('/invoices/list'), "style" => "", "show" => true],
						["name" => "Next", "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
						["name" => "Void", "type"=>"button", "id" => "discard", "url"=> base_url('/invoices/void'), "style" => "btn btn-danger", "show" => $_show_void_btn]
					]
				]);
				// show edit view
				$this->load->view('invoices/invoices-edit-view', [
					"submit_to" => base_url("/invoices/tender"),
					"prefix" => $this->_inv_header_param['topNav']['prefix'],
					"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
					"quotation" => "",
					"invoice_num" => $_invoice_num,
					"date" => date("Y-m-d H:i:s"),
					"items" => [
						0 => [
							"item_code" => "",
							"eng_name" => "",
							"chi_name" => "",
							"qty" => "",
							"unit" => "",
							"price" => "",
						]
					],
					"total" => 0,
					"ajax" => [
						"items" => $_API_ITEMS['query'],
						"shop_code" => $_API_SHOPS['query'],
						"customers" => $_API_CUSTOMERS['query'],
						"tender" => $_API_PAYMENTS['query']
					],
					"theprint_data" => $_show_transaction_data,
					"show" => $_show_void_btn,
					"default_per_page" => $_default_per_page
				]);
			}
			else
			{
				redirect(base_url("invoices/list/"),"refresh");
			}
		}
		
	}
	public function tender()
	{
		if(isset($_POST["i-post"]))
		{
			// variable initial
			$_data = json_decode($_POST['i-post'], true);
			$_cur_invoicenum = $this->session->userdata('cur_invoicenum');
			$_show_save_btn = false;
			$_show_reprint_btn = false;
			$_transaction = [];
		// echo "<pre>";
		// var_dump ($_SESSION);
		// echo "</pre>";

			$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/".$_data['customer']);
			$this->component_api->CallGet();
			$result = json_decode($this->component_api->GetConfig("result"),true);

			//$session = json_encode($this->session->userdata('theprint'),true);
			// combine customer data from API to main array. * it must be only one reoard retrieve 
			$_data['customer'] = $result['query'][0];

			
			$_transaction[$_cur_invoicenum] = $_data;

			// save print data to session
			$this->session->set_userdata('transaction',$_transaction);

			// show save button
			if(isset($_transaction[$_cur_invoicenum]['editmode']))
			{
				if($_transaction[$_cur_invoicenum]['editmode'])
				{
					$_show_save_btn = true;
				}
			}
			else{
				$_show_save_btn = true;
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
					["name" => "Back", "type"=>"button", "id" => "back", "url"=> base_url('/invoices/'.$_data['formtype'].'/'.$_data['invoicenum']."/".$_data['quotation']) ,"style" => "","show" => true],
					["name" => "Preview", "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "Save", "type"=>"button", "id" => "save", "url"=> base_url("/invoices/".$_the_form_type) , "style" => "","show" => $_show_save_btn],
					["name" => "Reprint", "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn]
				]
			]);
			// render view
			$this->load->view("invoices/invoices-tender-view", [
				"preview_url" => base_url('/ThePrint/invoices/preview'),
				"print_url" => base_url('/ThePrint/invoices/save')
			]);
			
		}
	}

	public function saveedit()
	{
		$_cur_invoicenum = $this->session->userdata('cur_invoicenum');
		$_transaction = $this->session->userdata('transaction');
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "Create New", "type"=>"button", "id" => "donew", "url"=> base_url('/invoices/donew'),"style" => "","show" => true],
			]
		]);
		if(!empty($_cur_invoicenum))
		{
			$_api_body = json_encode($_transaction[$_cur_invoicenum],true);
			// echo $_cur_invoicenum;
			//echo "<pre>";
			//echo ($_api_body);
			//echo "</pre>";
			if($_api_body != null)
			{
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/invoices/".$_cur_invoicenum);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);
			
			// echo "<pre>";
			// var_dump($result);
			// echo "</pre>";
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

					header("Refresh: 10; url='list/'");
					// unset($_transaction[$_cur_invoicenum]);
					// $this->session->set_userdata('cur_invoicenum',"");
					// $this->session->set_userdata('transaction',$_transaction);
				}
			}
		}
	}
	public function save()
	{
		$_cur_invoicenum = $this->session->userdata('cur_invoicenum');
		$_transaction = $this->session->userdata('transaction');
		
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "Create New", "type"=>"button", "id" => "donew", "url"=> base_url('/invoices/donew'),"style" => "","show" => true],
			]
		]);
		if(!empty($_cur_invoicenum))
		{
			$_api_body = json_encode($_transaction[$_cur_invoicenum],true);
	// echo $_cur_invoicenum;
	// echo "<pre>";
	// var_dump($_api_body);
	// echo "</pre>";
			if($_api_body != null)
			{
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/invoices/");
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
	// echo "<pre>";
	// var_dump($result);
	// echo "</pre>";
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
			}
		}
		//header("Refresh: 10; url='donew/'");
		//redirect(base_url("invoices/donew"),"refresh");
	}

	public function discard()
	{
		$_cur_invoicenum = $this->session->userdata('cur_invoicenum');
		$_transaction = $this->session->userdata('transaction');
		unset($_SESSION['cur_invoicenum']);
		unset($_transaction[$_cur_invoicenum]);
		redirect(base_url("invoices/donew"),"refresh");
	}
	/*
	 * List out all Invoices
	 *  
	 */
	public function invlist($page=1)
	{
		// variable initial
		$_default_per_page = 50;
		$data = [];
		$_shopcode_list = [];

		// fatch items API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/invoices/");
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);

		if(!empty($_data) )//&& !empty($_shopcode_list))
		{
			// Set user data
			$this->session->set_userdata('page',$page);
			
		// echo "<pre>";
		// var_dump($_data);
		// echo "</pre>";
			// Function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=> base_url("invoices/donew/"), "style" => "", "show" => true, "extra" => ""]
				]
			]);
			
			$this->load->view("invoices/invoices-list-view", [
				'data' => $_data, 
				"url" => base_url("invoices/edit/"),
				"default_per_page" => $_default_per_page,
				"page" => $page
			]);
		}
	}
	

}
