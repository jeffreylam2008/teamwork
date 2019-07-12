<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shops extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_token = "";
	var $_param = "";
	var $_shops = [];
	
	public function __construct()
	{
		parent::__construct();
		
		// dummy data
		if(!empty($this->session->userdata['login']))
		{
			$this->_token = $this->session->userdata['login']['token'];
		}
		
		$this->load->library("Component_Login",[$this->_token, "customers"]);

		// login session
		if(!empty($this->component_login->CheckToken()))
		{
			$this->_username = $this->session->userdata['login']['profile']['username'];

			// fatch employee API
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employees/".$this->_username);
			$this->component_api->CallGet();
			$_API_EMP = json_decode($this->component_api->GetConfig("result"), true);
			$_API_EMP = $_API_EMP['query'];

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
				"username" => $_API_EMP['username'],
				"employee_code" => $_API_EMP['employee_code'],
				"shop_code" => $_API_EMP['default_shopcode'],
				"today" => date("Y-m-d")
			];

			// Call API here
			// Shop api
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
			$this->component_api->CallGet();
			$_API_SHOPS = json_decode($this->component_api->GetConfig("result"), true);
			$this->_shops = $_API_SHOPS['query'];
			// Menu api
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
			$this->component_api->CallGet();
			$_API_MENU = json_decode($this->component_api->GetConfig("result"), true);
			$_API_MENU = $_API_MENU['query'];

			// Set side menu config
			$this->component_sidemenu->SetConfig("nav_list", $_API_MENU);
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
	public function index()
	{
		// variable initial
		$_default_per_page = 50;
		$_categories = [];
		if(empty($page))
		{
			$page = 1;
		}
		// Call API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);
		
		// echo "<pre>";
		// var_dump($_shops);
		// echo "</pre>";
		// load shops view
		$this->load->view('shops/shops-view', [
			"edit_url" => base_url("/administration/shops/edit/"),
			"route_url" => base_url("/administration/shops/page/"),
			"data" => $_data['query'],
			"user_auth" => true,
			"default_per_page" => $_default_per_page,
			"page" => $page
			
		]);
		$this->load->view('footer');
	}
	public function edit($shop_code)
	{
		// user data
		$_previous_disable = "";
		$_next_disable = "";
		$_page = 1;
		if(!empty($shop_code))
		{
			// Call API here

			// API data usage
			if(!empty($this->_shops) && !empty($shop_code) )
			{
				$_all = array_column($this->_shops, "shop_code");
				
				// search key
				$_key = array_search(
					$shop_code, array_column($this->_shops, "shop_code")
				);
				
				if($_key !== false)
				{
					$_cur = $_key;
					$_next = $_key + 1;
					$_previous = $_key - 1;
					
					if($_cur == (count($_all)-1))
					{
						$_next_disable = "disabled";
						$_next = (count($_all)-1);
					}
					if($_cur <= 0)
					{
						$_previous_disable = "disabled";
						$_previous = 0;
					}
	// echo "<pre>";
	// var_dump($this->_shops);
	// echo "</pre>";
					// function bar with next, preview and save button
					$this->load->view('function-bar', [
						"btn" => [
							["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/administration/shops/page/'.$_page), "style" => "", "show" => true],
							["name" => "Reset", "type"=>"button", "id" => "reset", "url" => "" , "style" => "btn btn-outline-secondary", "show" => true],
							["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true],
							["name" => "Previous", "type"=>"button", "id" => "previous", "url"=> base_url("/administration/shops/edit/".$_all[$_previous]), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
					 		["name" => "Next", "type"=>"button", "id" => "next", "url"=> base_url("/administration/shops/edit/".$_all[$_next]), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
					 	]
					]);

					// load main view
					$this->load->view('shops/shops-edit-view', [
						"save_url" => base_url("administration/shops/edit/save/".$shop_code),
						"data" => $this->_shops[$_key]
					]);
					$this->load->view('footer');
				}
			}
		}
		else
		{
			$alert = "danger";
			$this->load->view('error-handle', [
				'message' => "Data not Ready Yet!", 
				'code'=> "", 
				'alertstyle' => $alert
			]);
		}
	}
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
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/".$shop_code);
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
