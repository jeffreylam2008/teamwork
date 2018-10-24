<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotations extends CI_Controller 
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
			"prefix" => "QTA"
		];
		// fatch side bar API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
		$this->component_api->CallGet();
		$_nav_list = json_decode($this->component_api->GetConfig("result"), true);
		$this->component_sidemenu->SetConfig("nav_list", $_nav_list);
		$this->component_sidemenu->Proccess();
		
		// render the view
		$this->load->view('header',[
			'title'=>'Invoices',
			'sideNav_view' => $this->load->view('side-nav', ["sideNav"=>$this->component_sidemenu->GetConfig("nav_finished_list")], TRUE), 
			'topNav_view' => $this->load->view('top-nav', ["topNav" => $this->_inv_header_param["topNav"]], TRUE)
		]);
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
		if(!empty($_num))
		{
			if(substr($_num , 0 , 3) === $this->_inv_header_param["topNav"]['prefix'] 
				&& strlen($_num) == 13)
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
				if(array_key_exists($_num, $_transaction))
				{
					$_show_discard_btn = true;
					$_show_transaction_data = $_transaction[$_num];
				}
				else
				{
					$_show_discard_btn = true;
					$_transaction[$_num] = [];
					// set invoices number to session
					$this->session->set_userdata('cur_invoicenum',$_num);
					$this->session->set_userdata('transaction',$_transaction);
				}
				// fatch items API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/items/");
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
						["name" => "Discard", "type"=>"button", "id" => "discard", "url"=> base_url('/quotations/discard'), "style" => "btn btn-danger", "show" => $_show_discard_btn]
					]
				]);
				// present form view
				$this->load->view('quotations/quotations-create-view', [
					"submit_to" => base_url("/quotations/tender"),
					"prefix" => $this->_inv_header_param['topNav']['prefix'],
					"employee_code" => $this->_inv_header_param['topNav']['employee_code'],
					"quotation_num" => $_num,
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
    public function qualist()
	{
        $this->load->view('quotations/quotations-list-view');
        $this->load->view('footer');

	}
	public function tender()
	{
		echo "<pre>";
		var_dump($_POST);
		echo "</pre>";


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

			
			$_transaction[$_num] = $_data;

			// save print data to session
			$this->session->set_userdata('transaction',$_transaction);

			// show save button
			if(isset($_transaction[$_num]['editmode']))
			{
				if($_transaction[$_num]['editmode'])
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
			
			// // function bar
			// $this->load->view('function-bar', [
			// 	"btn" => [
			// 		["name" => "Back", "type"=>"button", "id" => "back", "url"=> base_url('/invoices/'.$_data['formtype'].'/'.$_data['invoicenum']) ,"style" => "","show" => true],
			// 		["name" => "Preview", "type"=>"button", "id" => "preview", "url"=> "#","style" => "","show" => true],
			// 		["name" => "Save", "type"=>"button", "id" => "save", "url"=> base_url("/invoices/".$_the_form_type) , "style" => "","show" => $_show_save_btn],
			// 		["name" => "Reprint", "type"=>"button", "id" => "reprint", "url"=> "#" , "style" => "" , "show" => $_show_reprint_btn]
			// 	]
			// ]);
			// // render view
			// $this->load->view("invoices/invoices-tender-view", [
			// 	"preview_url" => base_url('/ThePrint/invoices/preview'),
			// 	"print_url" => base_url('/ThePrint/invoices/save')
			// ]);
			
		}
	}
	public function discard()
	{

		
	}
}