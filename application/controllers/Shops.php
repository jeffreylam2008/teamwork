<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shops extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_default_per_page = "";
	var $_page = "";
	var $_token = "";
	var $_profile = "";
	var $_param = "";
	var $_shops = [];
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
		
		$this->load->library("Component_Login",[$this->_token, "administration/shops"]);

		// login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_SHOP_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "",];

			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "shops/edit":
					$this->_param = "shops/index";
				break;
				case "shops/delete":
					$this->_param = "shops/index";
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

			// Set side menu config
			$this->component_sidemenu->SetConfig("nav_list", $this->_API_HEADER['menu']);
			$this->component_sidemenu->SetConfig("active", $this->_param);
			$this->component_sidemenu->Proccess();
			
			// load header view
			$this->load->view('header',[
				'title'=>'Shops',
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
	 * Shop List view
	 */
	public function index()
	{
		// variable initial
		// Call API
		$this->component_api->SetConfig("url", $this->config->item('URL_SHOP'));
		$this->component_api->CallGet();
		$_API_SHOPS = $this->component_api->GetConfig("result");
		$_API_SHOPS = $_API_SHOPS['query'];
		
		$_login = $this->session->userdata("login");
		// echo "<pre>";
		// var_dump($_shops);
		// echo "</pre>";
		// load shops view
		$this->load->view('shops/shops-view', [
			"edit_url" => base_url("/administration/shops/edit/"),
			"route_url" => base_url("/administration/shops".$_login['preference']),
			"data" => $_API_SHOPS,
			"user_auth" => true,
			"default_per_page" => $this->_default_per_page,
			"page" => $this->_page
			
		]);
		$this->load->view('footer');
	}
	/**
	 * Shop Edit
	 */
	public function edit($shop_code = "")
	{
		// user data
		$_previous_disable = "";
		$_next_disable = "";

		$this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$shop_code);
		$this->component_api->CallGet();
		$_API_SHOPS = $this->component_api->GetConfig("result");
		$_API_SHOPS = !empty($_API_SHOPS['query']) ? $_API_SHOPS['query'] : [];

		if(!empty($shop_code)){
			if(empty($_API_SHOPS["previous"]))
			{
				$_previous_disable = "disabled";
			}
			if(empty($_API_SHOPS["next"]))
			{
				$_next_disable = "disabled";
			}
		// echo "<pre>";
		// var_dump($this->_shops);
		// echo "</pre>";
						// function bar with next, preview and save button
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/shops'), "style" => "", "show" => true],
					["name" => "<i class='fas fa-undo-alt'></i> ".$this->lang->line("function_clear"), "type"=>"button", "id" => "reset", "url" => "" , "style" => "btn btn-outline-secondary", "show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true],
					["name" => "<i class='fas fa-step-backward'></i> ".$this->lang->line("function_previous"), "type"=>"button", "id" => "previous", "url"=> base_url("/administration/shops/edit/".$_API_SHOPS["previous"]), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
					["name" => "<i class='fas fa-step-forward'></i> ".$this->lang->line("function_next"), "type"=>"button", "id" => "next", "url"=> base_url("/administration/shops/edit/".$_API_SHOPS["next"]), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
				]
			]);
	
			// load main view
			$this->load->view('shops/shops-edit-view', [
				"save_url" => base_url("administration/shops/edit/save/".$shop_code),
				"data" => $_API_SHOPS
			]);
			$this->load->view('footer');

		}
		
	}
	/**
	 * Shop save edit
	 */
	public function saveedit($shop_code = "")
	{
		$alert = "danger";
		$_login = $this->session->userdata('login');
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), 
				"type"=>"button", "id" => "Back", "url"=> base_url('/administration/shops'), "style" => "", "show" => true],
			]
		]);
		if(isset($_POST) && !empty($_POST) && isset($shop_code) && !empty($shop_code))
		{
			$_api_body = json_encode($_POST,true);
			// debug start
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			// $_api_body = "";
			// debug end

			$result = "";
			if($_api_body != "")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$shop_code);
				$this->component_api->CallPatch();
				$result = $this->component_api->GetConfig("result");
				// echo "<pre>";
				// var_dump($result);
				// echo "</pre>";
				switch($result["http_code"])
				{
					case 200:
						$alert = "success";
					break;
					case 404:
						$alert = "danger";
					break;
				}

				$this->load->view('error-handle', [
					'message' => $result["error"]['message'], 
					'code'=> $result["error"]['code'], 
					'alertstyle' => $alert
				]);
			}
		}
		else
		{
			$result["error"]['code'] = "90000";
			$result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again"; 
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
		// callback initial page
		header("Refresh: 5; url=".base_url("/administration/shops/".$_login['preference']));
	}
}
