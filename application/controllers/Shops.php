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


		// dummy data
		if(!empty($this->session->userdata['login']))
		{
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
			// set preference 
			if($this->input->get("page"))
			{
				$this->_page = $this->input->get("page");
			}
			if($this->input->get("show"))
			{
				$this->_default_per_page = $this->input->get("show");
			}
			$_query['page'] = $this->_page;
			$_query['show'] = $this->_default_per_page;
			$_query = $this->component_uri->QueryToString($_query);

			$_login = $this->session->userdata['login'];
			$_login['preference'] = $_query;
			$this->session->set_userdata("login", $_login);

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
	public function edit($shop_code)
	{
		// user data
		$_previous_disable = "";
		$_next_disable = "";

		$this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$shop_code);
		$this->component_api->CallGet();
		$_API_SHOPS = $this->component_api->GetConfig("result");
		$_API_SHOPS = $_API_SHOPS['query'];

		if(empty($_API_SHOPS["previous"]))
		{
			$_previous_disable = "disabled";
		}
		if(empty($_API_SHOPS["next"]))
		{
			$_next_disable = "disabled";
		}
		// if(!empty($shop_code))
		// {
		// 	// Call API here

		// 	// API data usage
		// 	if(!empty($this->_shops) && !empty($shop_code) )
		// 	{
		// 		$_all = array_column($this->_shops, "shop_code");
				
		// 		// search key
		// 		$_key = array_search(
		// 			$shop_code, array_column($this->_shops, "shop_code")
		// 		);
				
		// 		if($_key !== false)
		// 		{
		// 			$_cur = $_key;
		// 			$_next = $_key + 1;
		// 			$_previous = $_key - 1;
					
		// 			if($_cur == (count($_all)-1))
		// 			{
		// 				$_next_disable = "disabled";
		// 				$_next = (count($_all)-1);
		// 			}
		// 			if($_cur <= 0)
		// 			{
		// 				$_previous_disable = "disabled";
		// 				$_previous = 0;
		// 			}
	// echo "<pre>";
	// var_dump($this->_shops);
	// echo "</pre>";
					// function bar with next, preview and save button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/administration/shops'), "style" => "", "show" => true],
				["name" => "Reset", "type"=>"button", "id" => "reset", "url" => "" , "style" => "btn btn-outline-secondary", "show" => true],
				["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true],
				["name" => "Previous", "type"=>"button", "id" => "previous", "url"=> base_url("/administration/shops/edit/".$_API_SHOPS["previous"]), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
				["name" => "Next", "type"=>"button", "id" => "next", "url"=> base_url("/administration/shops/edit/".$_API_SHOPS["next"]), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
			]
		]);

		// load main view
		$this->load->view('shops/shops-edit-view', [
			"save_url" => base_url("administration/shops/edit/save/".$shop_code),
			"data" => $_API_SHOPS
		]);
		$this->load->view('footer');
		// 		}
		// 	}
		// }
		// else
		// {
		// 	$alert = "danger";
		// 	$this->load->view('error-handle', [
		// 		'message' => "Data not Ready Yet!", 
		// 		'code'=> "", 
		// 		'alertstyle' => $alert
		// 	]);
		// }
	}
	/**
	 * Shop save edit
	 */
	public function saveedit($shop_code = "")
	{
		if(isset($_POST) && !empty($_POST) && isset($shop_code) && !empty($shop_code))
		{
			$_api_body = json_encode($_POST,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			$result = "";
			if($_api_body != "")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$shop_code);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
				if(isset($result['error']['message']) || isset($result['error']['code']))
				{
					$alert = "danger";
					switch($result['error']['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}		
					$this->load->view('error-handle', [
						'message' => $result['error']['message'], 
						'code'=> $result['error']['code'], 
						'alertstyle' => $alert
					]);
					
					// callback initial page
					header("Refresh: 5; url=".base_url("/administration/shops/"));
				}
			}
		}
	}
}
