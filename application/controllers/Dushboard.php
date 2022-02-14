<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dushboard extends CI_Controller 
{
	var $_inv_header_param = [];	
	var $_token = "";
	var $_profile = "";
	var $_param = "";
	public function __construct()
	{
		parent::__construct();

		if(!empty($this->session->userdata['login']))
		{
			$this->_token = $this->session->userdata['login']['token'];
			$this->_profile = $this->session->userdata['login']['profile'];
		}
		
		$this->load->library("Component_Login",[$this->_token, "dushboard"]);

		// login session
		if(!empty($this->component_login->CheckToken()))
		{
			$_param = $this->router->fetch_class()."/".$this->router->fetch_method();

			// fatch master
			$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES').$this->_profile['username']);
			$this->component_api->CallGet();
			$_API_EMP = $this->component_api->GetConfig("result");
			$_API_EMP = $_API_EMP['query'];
			$this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$this->_profile['shopcode']);
			$this->component_api->CallGet();
			$_API_SHOP = $this->component_api->GetConfig("result");
			$_API_SHOP = !empty($_API_SHOP['query']) ? $_API_SHOP['query'] : ['shop_code' => "", 'name' => ""];
			$this->component_api->SetConfig("url", $this->config->item('URL_MENU_SIDE'));
			$this->component_api->CallGet();
			$_API_MENU = $this->component_api->GetConfig("result");
			$_API_MENU = !empty($_API_MENU['query']) ? $_API_MENU['query'] : [];

			// sidebar session
			$this->_param = strtolower($this->router->fetch_class()."/".$this->router->fetch_method());
			
			// header data
			$this->_inv_header_param["topNav"] = [
				"isLogin" => true,
				"username" => $_API_EMP['username'],
				"employee_code" => $_API_EMP['employee_code'],
				"shop_code" => $_API_SHOP['shop_code'],
				"shop_name" => $_API_SHOP['name'],
				"today" => date("Y-m-d")
			];

			$this->component_sidemenu->SetConfig("nav_list", $_API_MENU);
			$this->component_sidemenu->SetConfig("active", $this->_param);
			$this->component_sidemenu->Proccess();


			// load header view
			$this->load->view('header',[
				'title'=>'Dushboard',
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
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_DUSHBOARD_MONTHLY_INVOICES')."?year=".date('Y')."&month=".date('m')."");
		$this->component_api->CallGet();
		$_API_MONTHLY_INVOICES = $this->component_api->GetConfig("result");
		// echo "<pre>";
		// var_dump($_API_MONTHLY_INVOICES);
		// echo "</pre>";
		$_API_MONTHLY_INVOICES = !empty($_API_MONTHLY_INVOICES['query']) ? $_API_MONTHLY_INVOICES['query'] : "";
		$this->component_api->SetConfig("url", $this->config->item('URL_CUSTOMERS_COUNT'));
		$this->component_api->CallGet();
		$_API_CUSTOMERS_COUNT = $this->component_api->GetConfig("result");
		$_API_CUSTOMERS_COUNT = !empty($_API_CUSTOMERS_COUNT['query']) ? $_API_CUSTOMERS_COUNT['query'] : "";
		$this->component_api->SetConfig("url", $this->config->item('URL_ITEMS_COUNT'));
		$this->component_api->CallGet();
		$_API_ITEMS_COUNT = $this->component_api->GetConfig("result");
		$_API_ITEMS_COUNT = !empty($_API_ITEMS_COUNT['query']) ? $_API_ITEMS_COUNT['query'] : "";
		$this->component_api->SetConfig("url", $this->config->item('URL_DUSHBOARD_MONTHLY_PURCHASES')."?year=".date('Y')."&month=".date('m')."");
		$this->component_api->CallGet();
		$_API_MONTHLY_PURCHASES = $this->component_api->GetConfig("result");
		$_API_MONTHLY_PURCHASES = !empty($_API_MONTHLY_PURCHASES['query']) ? $_API_MONTHLY_PURCHASES['query'] : "";

		
		$this->load->view('dushboard-view', [
			"title" => $this->lang->line("dushboard"),
			"invoices_url" => base_url("/invoices/list"),
			"customer_url" => base_url("/customers"),
			"income_url" => base_url("/purchases/order"),
			"items_url" => base_url("/products/items"),
			"elem" => [
				"m_customers" => $_API_CUSTOMERS_COUNT['count'],
				"m_invoices" => $_API_MONTHLY_INVOICES['count'],
				"m_purchases" => $_API_MONTHLY_PURCHASES['count'],
				"m_items" => $_API_ITEMS_COUNT['count'],
				"m_income" => $_API_MONTHLY_INVOICES['income']
			]
		]);
		$this->load->view('footer');
		
	}
}
