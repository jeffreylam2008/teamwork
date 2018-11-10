<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller 
{
	var $_inv_header_param = [];
	public function __construct()
	{
		parent::__construct();

		// dummy data
		
		$username = "iamadmin";
		// fatch employee API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employee/".$username);
		$this->component_api->CallGet();
		$_employee = json_decode($this->component_api->GetConfig("result"),true);
		//var_dump($_employee);
		$this->_inv_header_param["topNav"] = [
			"isLogin" => true,
			"username" => "",
			"employee_code" => "110022",
			"shop_code" => "0012",
			"today" => date("Y-m-d"),
			"prefix" => "INV"
		];
		// fatch side bar API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
		$this->component_api->CallGet();
		$_nav_list = json_decode($this->component_api->GetConfig("result"), true);
		$this->component_sidemenu->SetConfig("nav_list", $_nav_list);
		$this->component_sidemenu->SetConfig("uri", $this->uri->uri_string());
		$this->component_sidemenu->Proccess();

		var_dump($this->component_sidemenu->GetConfig("path"));
		
		
		// render the view
		$this->load->view('header',[
			'title'=>'Invoices',
			'sideNav_view' => $this->load->view('side-nav', ["sideNav"=>$this->component_sidemenu->GetConfig("nav_finished_list")], TRUE), 
			'topNav_view' => $this->load->view('top-nav', ["topNav" => $this->_inv_header_param["topNav"]], TRUE)
		]);
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
		redirect(base_url("invoices/create/".$_invoice_num),"refresh");
	}
	public function create($_invoice_num = "")
	{
		// variable initial
		$_default_per_page = 50;
		if(!empty($_invoice_num))
		{
			if(substr($_invoice_num , 0 , 3) === $this->_inv_header_param["topNav"]['prefix'] 
				&& strlen($_invoice_num) == 13)
			{
				// variable initial
				$_show_discard_btn = false;
				$_show_transaction_data = "";
				$_cur_invoicenum = "";
				$_transaction = [];
				
				if(!empty($this->session->userdata('transaction')))
				{
					$_cur_invoicenum = $this->session->userdata('cur_invoicenum');
					$_transaction = $this->session->userdata('transaction');
				}
				//unset($_SESSION['transaction']);
				// echo "<pre>";
				// var_dump($_SESSION);
				// echo "</pre>";
				// echo "<pre>";
				// var_dump($_transaction);
				// echo "</pre>";
				
				// check invoices is exist or new create
				if(array_key_exists($_invoice_num, $_transaction))
				{
					$_show_discard_btn = true;
					$_show_transaction_data = $_transaction[$_invoice_num];
				}
				else
				{
					$_show_discard_btn = true;
					$_transaction[$_invoice_num] = [];
					// set invoices number to session
					$this->session->set_userdata('cur_invoicenum',$_invoice_num);
					$this->session->set_userdata('transaction',$_transaction);
				}
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/");
				$this->component_api->CallGet();
				$_items_list = json_decode($this->component_api->GetConfig("result"), true);
				// fatch shop code and shop detail API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
				$this->component_api->CallGet();
				$_shopcode_list = json_decode($this->component_api->GetConfig("result"), true);
				// fatch customer API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/customers/");
				$this->component_api->CallGet();
				$_cust_list = json_decode($this->component_api->GetConfig("result"), true);
				// fatch payment method API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/");
				$this->component_api->CallGet();
				$_tender = json_decode($this->component_api->GetConfig("result"),true);
				
				
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
					"quotation" => "",
					"invoice_num" => $_invoice_num,
					"invoice_date" => date("Y-m-d H:i:s"),
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
						"items" => $_items_list['query'],
						"shop_code" => $_shopcode_list['query'],
						"customers" => $_cust_list['query'],
						"tender" => $_tender['query']
					],
					"theprint_data" => $_show_transaction_data,
					"default_per_page" => $_default_per_page
				]);
				// persent footer view
				$this->load->view('footer');
			}
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
				$_invoice_date = date_create(date("Y-m-d",strtotime($_invoices['query']['invoicedate'])));
				$_diff = date_diff($_today,$_invoice_date);
				
				$_the_date_diff = $_diff->format("%a");
				// check invoice date was same with today
				if($_the_date_diff =! 0){
					$_show_void_btn = true;
				}

				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/");
				$this->component_api->CallGet();
				$_items_list = json_decode($this->component_api->GetConfig("result"), true);
				// fatch shop code and shop detail API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
				$this->component_api->CallGet();
				$_shopcode_list = json_decode($this->component_api->GetConfig("result"), true);
				// fatch customer API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/customers/");
				$this->component_api->CallGet();
				$_cust_list = json_decode($this->component_api->GetConfig("result"), true);
				// fatch payment method API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/");
				$this->component_api->CallGet();
				$_tender = json_decode($this->component_api->GetConfig("result"),true);

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
					"invoice_date" => date("Y-m-d H:i:s"),
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
						"items" => $_items_list['query'],
						"shop_code" => $_shopcode_list['query'],
						"customers" => $_cust_list['query'],
						"tender" => $_tender['query']
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

			$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/customers/".$_data['customer']);
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
					["name" => "Back", "type"=>"button", "id" => "back", "url"=> base_url('/invoices/'.$_data['formtype'].'/'.$_data['invoicenum']) ,"style" => "","show" => true],
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
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			if($_api_body != "null")
			{
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/invoices/".$_transaction[$_cur_invoicenum]['invoicenum']);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);
			
			// echo "<pre>";
			// var_dump($result);
			// echo "</pre>";
				if(isset($result['message']) || isset($result['code']))
				{
					$alert = "danger";
					switch($result['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}					
					
					$this->load->view('error-handle', [
						'message' => $result['message'], 
						'code'=> $result['code'], 
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
			// echo "<pre>";
			// var_dump($_transaction[$_cur_invoicenum]);
			// echo "</pre>";
			$_api_body = json_encode($_transaction[$_cur_invoicenum],true);
			// echo $_cur_invoicenum;
		// echo "<pre>";
		// var_dump($_api_body);
		// echo "</pre>";
			if($_api_body != "null")
			{

				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/invoices/");
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
			// echo "<pre>";
			// var_dump($result);
			// echo "</pre>";

				if(isset($result['message']) || isset($result['code']))
				{
					$alert = "danger";
					switch($result['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}					
					
					$this->load->view('error-handle', [
						'message' => $result['message'], 
						'code'=> $result['code'], 
						'alertstyle' => $alert
					]);
					unset($_transaction[$_cur_invoicenum]);
					$this->session->set_userdata('cur_invoicenum',"");
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
	public function invlist($page="")
	{
		// variable initial
		$_default_per_page = 50;
		$data = [];
		$_shopcode_list = [];

		// fatch items API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/invoices/");
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);
		// fatch shop API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
		$this->component_api->CallGet();
		$_shopcode_list = json_decode($this->component_api->GetConfig("result"), true);

		if(!empty($_data) && !empty($_shopcode_list))
		{
			foreach($_shopcode_list['query'] as $key => $val)
			{
				$_shop_data[$val['shop_code']] = $val;
			}
			foreach($_data['query'] as $key => $val)
			{
				if(array_key_exists($val['shop_code'],$_shop_data))
				{
					$_data['query'][$key]['shop_name'] = $_shop_data[$val['shop_code']]['name'];
				}
			}
		// echo "<pre>";
		// var_dump($_data);
		// echo "</pre>";
		// echo "<pre>";
		// var_dump($_shop_data);
		// echo "</pre>";

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
