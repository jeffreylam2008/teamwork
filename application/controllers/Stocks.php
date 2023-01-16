<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocks extends CI_Controller 
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
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCKS_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];
			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				// case "stocks/grn":
				// 	$this->_param = "stocks/index";
				// break;
				// case "stocks/grn_confirm":
				// 	$this->_param = "stocks/index";
				// break;
				// case "stocks/adjust":
				// 	$this->_param = "stocks/index";
				// break;
				// case "stocks/grn_detail":
				// 	$this->_param = "stocks/index";
				// break;
				// case "stocks/dn_detail":
				// 	$this->_param = "stocks/index";
				// break;
				case "stocks/stocktake":
					$this->_param = "stocks/index";
				break;
				case "stocks/stocktake_detail":
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
				"today" => date("Y-m-d")
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
	 *GRN List
	 * 
	 */
    public function index()
    {
		// variable initial
		$_data = [];
		if(empty($_GET['i-start-date']) && empty($_GET['i-end-date']) && empty($_GET['i-dn']))
		{
			$_GET['i-start-date'] = date("Y-m-d", strtotime('-'.$this->config->item('NUM_DATE_OF_SEARCH').' days'));
			$_GET['i-end-date'] = date("Y-m-d");
		}
		$_query = [
			'i-num' => $this->input->get("i-num"),
			'i-start-date' => $this->input->get('i-start-date'),
			'i-end-date' => $this->input->get('i-end-date'),
			'i-cust-code' => $this->input->get('i-cust-code'),
			'i-supp-code' => $this->input->get('i-supp-code'),
			'page' => htmlspecialchars($this->_page),
			'show' => htmlspecialchars($this->_default_per_page),
		];
		
		if(!empty($_query))
		{
			//Set user preference
			$_q_str = $this->component_uri->QueryToString($_query);
			$_login = $this->session->userdata('login');
			$_login['preference'] = $_q_str;
			$this->session->set_userdata("login", $_login);
			
			// echo "<pre>";
			// var_dump($_q);
			// echo "</pre>";
			// fatch items API
			if(!empty($_query['i-cust-code']))
			{
				// fatch items API
				// get result by customer code 
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCKS')."getlast/cust/".$_query['i-cust-code']);
			}
			elseif(!empty($_query['i-supp-code']))
			{
				// fatch items API
				// get result by supplier code 
				$this->component_api->SetConfig("url", $this->config->item('URL_STOCKS')."getlast/supp/".$_query['i-supp-code']);
			}
			else
			{
				// fatch items API

				$this->component_api->SetConfig("url", $this->config->item('URL_STOCKS').$_q_str);
			}
			$this->component_api->CallGet();
			$_data = $this->component_api->GetConfig("result");

		}
		

		// Function bar
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fa-solid fa-dolly'></i> ".$this->lang->line("dn_short"), "type"=>"button", "id" => "i-grn", "url"=> base_url('/router/dn/create'), "style" => "", "show" => true, "extra" => ""],
				["name" => "<i class='fa-solid fa-people-carry-box'></i> ".$this->lang->line("grn_short"), "type"=>"button", "id" => "i-grn", "url"=> base_url('/router/grn/create'), "style" => "", "show" => true, "extra" => ""],
				["name" => "<i class='fas fa-adjust'></i> ".$this->lang->line("adjustment_short"), "type"=>"button", "id" => "i-adj", "url"=> base_url('/router/adjustments/create'), "style" => "", "show" => true, "extra" => ""],
				["name" => "<i class='fas fa-box'></i> ".$this->lang->line("stocktake"), "type"=>"button", "id" => "i-stocktake", "url"=> base_url('/router/stocktake/create'), "style" => "", "show" => true, "extra" => ""]
			]
		]);
		// Function bar
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-search'></i> ".$this->lang->line("function_search"), "type"=>"button", "id" => "i-search", "url"=> "#", "style" => "", "show" => true, "extra" => ""],
				["name" => "<i class='fas fa-undo-alt'></i> ".$this->lang->line("function_clear"), "type"=>"button", "id" => "i-clear", "url"=> "#", "style" => "btn btn-secondary", "show" => true, "extra" => ""]
			]
		]);

		$this->load->view("stocks/stocks-list-view", [
			"data" => $_data,
			"submit_to" => base_url("stocks"),
			"edit_url" => base_url("/router/warehouse/view/"),
			"default_per_page" => $this->_default_per_page,
			"page" => $this->_page,
			"ad_start_date" => $_query['i-start-date'],
			"ad_end_date" => $_query['i-end-date'],
			"ad_num" => $_query['i-num'],
			"ad_cust_code" => $_query['i-cust-code'],
			"ad_supp_code" => $_query['i-supp-code']
		]);
		$this->load->view("footer");
	
	}

}
