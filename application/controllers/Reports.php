<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller 
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
		$this->load->library("Component_Login",[$this->_token, "invoices/list"]);

		// // login session
		if(!empty($this->component_login->CheckToken()))
		{
			//API data
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];

			// dummy data
			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "invoices/edit":
					$this->_param = "reports/index";
				break;
				case "reports/monthly_customers":
					$this->_param = "reports/index";
				break;
			}
			
			// fatch employee API
			$this->_inv_header_param["topNav"] = [
				"isLogin" => true,
				"username" => $this->_API_HEADER['employee']['username'],
				"employee_code" => $this->_API_HEADER['employee']['employee_code'],
				"shop_code" => $this->_API_HEADER['employee']['shop_code'],
				"shop_name" => $this->_API_HEADER['employee']['shop_name'],
				"today" => date("Y-m-d"),
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
			redirect(base_url("login?url=".urlencode($this->component_login->GetRedirectURL())),"auto");
		}		
	}
	public function index()
	{
		$this->load->view('title-bar', [
			"title" => "General Report"
		]);
		$this->load->view("reports/menu_view", [
			"reports" => [
				["name"=>"Monthly Report", "url"=>base_url('/reports/monthly')],
				["name"=>"Daily Report", "url"=>base_url('/reports/daily')],
				["name"=>"Transactions Report", "url"=>base_url('/reports/transactions')],
				["name"=>"Products Report", "url"=>base_url('/reports/products')]
			]
		]);
	}
	public function reports($report="")
	{
		echo $report;

		if(empty($_GET['i-start-date']) && empty($_GET['i-end-date']))
		{
			$_GET['i-start-date'] = date("Y-m-d", strtotime('-5 days'));
			$_GET['i-end-date'] = date("Y-m-d");
		}
		$_start_date = $this->input->get('i-start-date');
		$_end_date = $this->input->get('i-end-date');

		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> Back", "type"=>"button", "id" => "Back", "url"=> base_url('/reports'), "style" => "", "show" => true]
			]
		]);
		switch($report)
		{
			case "monthly":
				$this->load->view("reports/calendar_month_view",[
					"ad_end_date"
				]);
				break;
			case "daily":
				$this->load->view("reports/calendar_view", [
					"ad_start_date" => $_start_date,
					"ad_end_date" => $_end_date
				]);
				break;
		}
	}
}
