<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotations extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_token = "";
	var $_param = "";
	
	public function __construct()
	{
		parent::__construct();
		$_API_EMP = [];
		$this->load->library("Component_Master");
		if(isset($this->session->userdata['master']))
		{
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
					case "quotations/edit":
						$this->_param = "quotations/qualist";
					break;
					case "quotations/tender":
						$this->_param = "quotations/qualist";
					break;
				}
				// header data
				$this->_inv_header_param["topNav"] = [
					"isLogin" => true,
					"username" => $_API_EMP['username'],
					"employee_code" => $_API_EMP['username'],
					"shop_code" => $_API_EMP['default_shopcode'],
					"today" => date("Y-m-d"),
					"prefix" => "QTA"
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
					'title'=>'Quotations',
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
		else
		{
			redirect(base_url("master"),"refresh");
		}			
	}

	public function qualist($page="")
	{
		// variable initial
		$_default_per_page = 50;
		$_API_QUOTA = [];
		$_shopcode_list = [];
		// set page
		if(empty($page))
		{
			$page = 1;
		}
		// fatch quotation API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/quotations/");
		$this->component_api->CallGet();
		$_API_QUOTA = json_decode($this->component_api->GetConfig("result"), true);

		if(!empty($_API_QUOTA))
		{
			// set user data
			$this->session->set_userdata('page',$page);

		
		// echo "<pre>";
		// var_dump($_shop_data);
		// echo "</pre>";

			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=> base_url("quotations/donew/"), "style" => "", "show" => true, "extra" => ""]
				]
			]);

			$this->load->view("quotations/quotations-list-view", [
				'data' => $_API_QUOTA, 
				"url" => base_url("quotations/edit/"),
				"default_per_page" => $_default_per_page,
				"page" => $page
			]);
		}
	}
	public function donew()
	{
		if(!empty($this->session->userdata('transaction')))
		{
			$this->session->unset_userdata('transaction');
		}
		$_num = $this->_inv_header_param['topNav']['prefix'].date("Ymds");
		redirect(base_url("quotations/create/".$_num),"refresh");
	}
	public function create($_num = "")
	{
		// variable initial
		$_default_per_page = 50;
		$_show_discard_btn = false;
		$_show_transaction_data = [];
		$_cur_quotationnum = "";
		$_transaction = [];
		$_items_list = [];
		$_shopcode_list = [];
		$_cust_list = [];
		$_tender_list = [];
				
		if(!empty($_num))
		{
			if(substr($_num , 0 , 3) === $this->_inv_header_param["topNav"]['prefix'] 
				&& strlen($_num) == 13)
			{
				if(!empty($this->session->userdata('transaction')))
				{
					$_cur_quotationnum = $this->session->userdata('cur_quotationnum');
					$_transaction = $this->session->userdata('transaction');
				}
				//unset($_SESSION['transaction']);
				// echo "<pre>";
				// var_dump($_SESSION);
				// echo "</pre>";
				// echo "<pre>";
				// var_dump($_transaction);
				// echo "</pre>";
				
				// check quotation is exist or new create
				if(array_key_exists($_num, $_transaction))
				{
					$_show_discard_btn = true;
					$_show_transaction_data = $_transaction[$_num];
				}
				else
				{
					$_show_discard_btn = true;
					$_transaction[$_num] = [];
					// set quotation number to session
					$this->session->set_userdata('cur_quotationnum',$_num);
					$this->session->set_userdata('transaction',$_transaction);
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
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/");
				$this->component_api->CallGet();
				$_API_PAYMENTS = json_decode($this->component_api->GetConfig("result"),true);
				
				
				// var_dump($_theprint_data);
				// function bar with next, preview and save button
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "Next", "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => true],
						["name" => "Discard", "type"=>"button", "id" => "discard", "url"=> base_url('/quotations/discard'), "style" => "btn btn-danger", "show" => $_show_discard_btn]
					]
				]);
				// present form view
				$this->load->view('quotations/quotations-create-view', [
					"submit_to" => base_url("/quotations/tender"),
					"prefix" => $this->_inv_header_param['topNav']['prefix'],
					"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
					"quotation" => $_num,
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
					"default_per_page" => $_default_per_page
				]);
				// persent footer view
				$this->load->view('footer');
			}
		}
	}

	public function edit($_num="")
	{
		// variable initial
		$_default_per_page = 50;
		$_show_void_btn = false;
		$_show_convert_btn = false;
		$_show_next_btn = false;
		$_show_transaction_data = [];
		$_items_list = [];
		$_shopcode_list = [];
		$_cust_list = [];
		$_tender_list = [];
		$_quotation = [];

		if(!empty($_num))
		{
			// Check Quotation exist
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/quotations/".$_num);
			$this->component_api->CallGet();
			$_quotation = json_decode($this->component_api->GetConfig("result"),true);
			
			// set current invoice number to session
			//$this->session->set_userdata('transaction',$_transaction);
			$this->session->set_userdata('cur_quotationnum',$_num);
			
			// unset($_SESSION['transaction']);
			// unset($_SESSION['cur_invoicenum']);

			if($_quotation['has'])
			{
				// variable initial
				$_show_transaction_data = $_quotation['query'];
			// echo "<pre>";
			// var_dump($_show_transaction_data);
			// echo "</pre>";
				if($_quotation['query']['is_convert'] === 0)
				{
					$_show_convert_btn = true;
					$_show_void_btn = true;
					$_show_next_btn = true;
				}
				$_today = date_create($this->_inv_header_param['topNav']['today']);
				$_invoice_date = date_create(date("Y-m-d",strtotime($_quotation['query']['date'])));
				$_diff = date_diff($_today,$_invoice_date);
				
				// Check business date for void 
				// $_the_date_diff = $_diff->format("%a");
				// // check invoice date was same with today
				// if($_the_date_diff =! 0){
				// 	$_show_void_btn = true;
				// }

				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/");
				$this->component_api->CallGet();
				$_items_list = json_decode($this->component_api->GetConfig("result"), true);
				// fatch shop code and shop detail API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
				$this->component_api->CallGet();
				$_shopcode_list = json_decode($this->component_api->GetConfig("result"), true);
				// fatch customer API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
				$this->component_api->CallGet();
				$_cust_list = json_decode($this->component_api->GetConfig("result"), true);
				// fatch payment method API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/");
				$this->component_api->CallGet();
				$_tender_list = json_decode($this->component_api->GetConfig("result"),true);

				// function bar with next, preview and save button
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "Back", "type"=>"button", "id" => "Back", "url"=> base_url('/quotations/list'), "style" => "", "show" => true],
						["name" => "Next", "type"=>"button", "id" => "next", "url"=> "#", "style" => "", "show" => $_show_next_btn],
						["name" => "Convert to Invoice", "type"=>"button", "id" => "convert", "url"=> base_url('/invoices/convert/'.$_quotation['query']['quotation']), "style" => "", "show" => $_show_convert_btn],
						["name" => "Void", "type"=>"button", "id" => "discard", "url"=> base_url('/quotations/void/'.$_quotation['query']['quotation']), "style" => "btn btn-danger", "show" => $_show_void_btn]
					]
				]);
				// show edit view
				$this->load->view('quotations/quotations-edit-view', [
					"submit_to" => base_url("/quotations/tender"),
					"prefix" => $this->_inv_header_param['topNav']['prefix'],
					"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
					"quotation" => $_quotation['query']['quotation'],
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
						"items" => $_items_list['query'],
						"shop_code" => $_shopcode_list['query'],
						"customers" => $_cust_list['query'],
						"tender" => $_tender_list['query']
					],
					"theprint_data" => $_show_transaction_data,
					"show" => $_show_void_btn,
					"default_per_page" => $_default_per_page
				]);
			}
			else
			{
				redirect(base_url("quotations/list/"),"refresh");
			}
		}
	}
	/**
	 * tender payment
	 */
	public function tender()
	{
		if(isset($_POST["i-post"]))
		{

			// variable initial
			$_data = json_decode($_POST['i-post'], true);
			$_cur_num = $this->session->userdata('cur_quotationnum');
			$_show_save_btn = false;
			$_show_reprint_btn = false;
			$_transaction = [];
		// echo "<pre>";
		// var_dump ($_data);
		// echo "</pre>";

			$this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/".$_data['customer']);
			$this->component_api->CallGet();
			$customer = json_decode($this->component_api->GetConfig("result"),true);

			// marge customer data from API to main array. * it must be only one reoard retrieve 
			$_data['customer'] = $customer['query'][0];
			$_transaction[$_cur_num] = $_data;

			// save print data to session
			$this->session->set_userdata('transaction',$_transaction);

			// show save button
			if(isset($_transaction[$_cur_num]['editmode']))
			{
				if($_transaction[$_cur_num]['editmode'])
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
		// var_dump($_transaction[$_cur_num]);
		// echo "</pre>";
			
			// function bar
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "Back", "type"=>"button", "id" => "back", "url"=> base_url('/quotations/'.$_data['formtype'].'/'.$_data['quotation']) ,"style" => "","show" => true],
					["name" => "Preview", "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
					["name" => "Save", "type"=>"button", "id" => "save", "url"=> base_url("/quotations/".$_the_form_type), "style" => "","show" => $_show_save_btn],
					//["name" => "Reprint", "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn]
				]
			]);
			// render view
			$this->load->view("quotations/quotations-tender-view", [
				"preview_url" => base_url('/ThePrint/quotations/preview'),
				"print_url" => base_url('/ThePrint/quotations/save')
			]);
			
		}
	}
	public function save()
	{
		$_cur_num = $this->session->userdata('cur_quotationnum');
		$_transaction = $this->session->userdata('transaction');
		// echo "<pre>";
		// var_dump($_transaction);
		// echo "</pre>";

		$this->load->view('function-bar', [
			"btn" => [
				["name" => "Create New", "type"=>"button", "id" => "donew", "url"=> base_url('/quotations/donew'),"style" => "","show" => true],
			]
		]);
		if(!empty($_cur_num))
		{
			 //echo "<pre>";
			 //var_dump($_transaction[$_cur_num]);
			 //echo "</pre>";
			$_api_body = json_encode($_transaction[$_cur_num],true);
			echo "<pre>";
			echo ($_api_body);
			echo "</pre>";

			if($_api_body != null)
			{
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/quotations/");
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);

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
					unset($_transaction[$_cur_num]);
					$this->session->set_userdata('cur_quotationnum',"");
					$this->session->set_userdata('transaction',$_transaction);
					
					//header("Refresh: 10; url='donew/'");
				}
			}
		}
	}
	public function saveedit()
	{
		// session
		$_cur_num = $this->session->userdata('cur_quotationnum');
		$_transaction = $this->session->userdata('transaction');
		// echo "<pre>";
		// var_dump($_SESSION);
		// echo "</pre>";

		$this->load->view('function-bar', [
			"btn" => [
				["name" => "Create New", "type"=>"button", "id" => "donew", "url"=> base_url('/invoices/donew'),"style" => "","show" => true],
			]
		]);
		if(!empty($_cur_num))
		{
			$_api_body = json_encode($_transaction[$_cur_num],true);
			// echo $_cur_invoicenum;
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			if($_api_body != "null")
			{
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/invoices/".$_cur_num);
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

					header("Refresh: 2; url='list/'");
					// unset($_transaction[$_cur_invoicenum]);
					// $this->session->set_userdata('cur_invoicenum',"");
					// $this->session->set_userdata('transaction',$_transaction);
				}
			}
		}
	}
	public function discard()
	{
		//unset($_SESSION['cur_invoicenum']);
		$_cur_quotationnum = $this->session->userdata('cur_quotationnum');
		$_transaction = $this->session->userdata('transaction');
		unset($_SESSION['cur_quotationnum']);
		unset($_transaction[$_cur_quotationnum]);
		redirect(base_url("quotations/donew"),"refresh");
	}
	public function void($_num = "")
	{
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/quotations/".$_num);
		$this->component_api->CallDelete();
		$_result = json_decode($this->component_api->GetConfig("result"),true);
		var_dump($_result);
	}
}