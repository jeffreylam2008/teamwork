<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_token = "";
	var $_param = "";
	public function __construct()
	{
		parent::__construct();
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
			$this->load->library("Component_Login",[$this->_token, "products/categories"]);

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
					case "categories/edit":
						$this->_param = "categories/index";
					break;
					case "categories/delete":
						$this->_param = "categories/index";
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
				// fatch side bar API
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
				$this->component_api->CallGet();
				$_API_MENU = json_decode($this->component_api->GetConfig("result"), true);
				$_API_MENU = $_API_MENU['query'];
				$this->component_sidemenu->SetConfig("nav_list", $_API_MENU);
				$this->component_sidemenu->SetConfig("active", $this->_param);
				$this->component_sidemenu->Proccess();

				// load header view
				$this->load->view('header',[
					'title'=>'Category',
					'sideNav_view' => $this->load->view('side-nav', [
						"sideNav" => $this->component_sidemenu->GetConfig("nav_finished_list"),
						"path" => $this->component_sidemenu->GetConfig("path"),
						"param" => $this->_param
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
	public function index($_page = 1)
	{
		// variable initial
		$_default_per_page = 50;
		$_API_ITEMS = [];
		$_API_CATEGORIES = [];
		
		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/categories/");
		$this->component_api->CallGet();
		$_API_CATEGORIES = json_decode($this->component_api->GetConfig("result"), true);
		$_API_CATEGORIES = $_API_CATEGORIES['query'];
		
		// data for ordering items in sequence
		foreach($_API_CATEGORIES as $key => $val)
		{
			$_cate[]['cate_code'] = $val['cate_code'];
		}
		$this->session->set_userdata('cate_list',$_cate);
		//set user data
		$this->session->set_userdata('page',$_page);


		// function bar with next, preview and save button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => true, "extra" => "data-toggle='modal' data-target='#modal01'"]
			]
		]);
		// Main view loaded
		$this->load->view("categories/categories-view",[
			"base_url" => base_url("/products/categories/edit/"),
			"del_url" => base_url("/products/categories/delete/"),
			"route_url" => base_url("/products/categories/page/"),
			"data" => $_API_CATEGORIES,
			"user_auth" => true,
			"default_per_page" => $_default_per_page,
			"page" => $_page
		]);

		$this->load->view("categories/categories-create-view",[
			"function_bar" => $this->load->view('function-bar', [
				"btn" => [
					["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/products/categories/page/'.$_page), "style" => "", "show" => true],
					["name" => "Reset", "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
					["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
				 ]
			],true),
			"save_url" => base_url("/products/categories/save/")
		]);
		$this->load->view('footer');
	}
	/**
	 * Edit
	 * 
	 */
	public function edit($cate_code="")
	{

		// variable initial
		$_previous_disable = "";
		$_next_disable = "";
		$_page = 1;

		// set user data
		$_page = $this->session->userdata("page");
		//$_cate = $this->session->userdata('cate_list');

		// API data
		$_cate =  $this->session->userdata['cate_list'];
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/categories/".$cate_code);
		$this->component_api->CallGet();
		$_API_CATEGORIES = json_decode($this->component_api->GetConfig("result"), true);
		$_API_CATEGORIES = $_API_CATEGORIES['query'];
		// data convertion for items edit (next and previous functions)
		if(!empty($_cate))
		{
			$_all = array_column($_cate, "cate_code");
			// echo "<pre>";
			// var_dump($_items['query']);
			
			// search key
			$_key = array_search(
				$cate_code, array_column($_cate, "cate_code")
			);
			// echo "</pre>"; 
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
			// var_dump ($_all);
			// echo "</pre>";
		}
		
		// function bar with next, preview and save button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "Back", "type"=>"button", "id" => "back", "url"=> base_url('/products/categories/page/'.$_page), "style" => "", "show" => true],
				["name" => "Save", "type"=>"button", "id" => "save", "url"=> "#", "style" => "", "show" => true],
				["name" => "Previous", "type"=>"button", "id" => "Previous", "url"=> base_url("/products/categories/edit/".$_all[$_previous]), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
				["name" => "Next", "type"=>"button", "id" => "Next", "url"=> base_url("/products/categories/edit/".$_all[$_next]), "style" => "btn btn-outline-secondary ". $_next_disable, "show" => true]
			]
		]);
		$this->load->view("categories/categories-edit-view", [
			"save_url" => base_url("/products/categories/edit/save/"),
			"data" => $_API_CATEGORIES
		]);
	}
	/**
	 * Delete
	 * 
	 */
	public function delete($cate_code="")
	{
		// user data
		$_page = $this->session->userdata("page");
		$_comfirm_show = true;
		$_page = 1;
		
		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/has/category/".$cate_code);
		$this->component_api->CallGet();
		$_API_CATEGORIES = json_decode($this->component_api->GetConfig("result"), true);

		if(isset($_API_CATEGORIES))
		{	
			if($_API_CATEGORIES['query'])
			{
				$_comfirm_show = false;	
			}
			// function bar with next, preview and save button
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "Back", "type"=>"button", "id" => "Back", "url"=>base_url('/products/categories/page/'.$_page), "style" => "", "show" => true],
					["name" => "Yes", "type"=>"button", "id" => "yes", "url"=>base_url('/products/categories/delete/confirmed/'.$cate_code), "style" => "btn btn-outline-danger", "show" => $_comfirm_show],
				]
			]);
			// main view loaded
			$this->load->view("categories/categories-del-view",[
				"cate_code" => $cate_code,
				"data" => $_API_CATEGORIES,
			]);
		}
	}
	/** 
	 * Process Save delete 
	 * 
	 */
	public function savedel($cate_code = "")
	{
		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/categories/".$cate_code);
		$this->component_api->CallDelete();
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
			header("Refresh: 5; url=".base_url("/products/categories/"));
		}
	}
	/** 
	 * Process Save create 
	 * 
	 */
	public function savecreate()
	{
		if(isset($_POST) && !empty($_POST))
		{
			$_api_body = json_encode($_POST,true);

			if($_api_body != "null")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/categories/");
				$this->component_api->CallPost();
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
					header("Refresh: 5; url=".base_url("/products/categories/"));
				}
			}
		}
	}
	/** 
	 * Process Save edit 
	 * 
	 */
	public function saveedit($cate_code = "")
	{
		if(isset($_POST) && !empty($_POST) && isset($cate_code) && !empty($cate_code))
		{
			$_api_body = json_encode($_POST,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			if($_api_body != "")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/categories/".$cate_code);
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
					header("Refresh: 5; url=".base_url("/products/categories/"));
				}
			}
		}
	}
	
}
