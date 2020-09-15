<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_default_per_page = "";
	var $_page = "";
	var $_token = "";
	var $_profile = "";
	var $_param = "";
	var $_user_auth = ['create' => false, 'edit' => false, 'delete' => false];


	public function __construct()
	{
		parent::__construct();

		$_API_EMP = [];
		$_API_SHOP = [];
		$_API_MENU = [];
		$this->_user_auth = ['create' => true, 'edit' => true, 'delete' => true];
		$this->_default_per_page = $this->config->item('DEFAULT_PER_PAGE');
		$this->_page = $this->config->item('DEFAULT_FIRST_PAGE');
		$_query = ["page" => "", "show"=>""];
		$_query = $this->input->get();
		// dummy data
		// $this->session->sess_destroy();
		// echo "<pre>";
		// var_dump(($_SESSION['master']));
		// echo "</pre>";
		// call token from session
		if(!empty($this->session->userdata['login']))
		{
			$this->_token = $this->session->userdata['login']['token'];
			$this->_profile = $this->session->userdata['login']['profile'];
		}
		// API call
		$this->load->library("Component_Login",[$this->_token, "products/categories"]);

		// login session
		if(!empty($this->component_login->CheckToken()))
		{
			// fatch employee API
			$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES').$this->_profile['username']);
			$this->component_api->CallGet();
			$_API_EMP = json_decode($this->component_api->GetConfig("result"), true);
			$_API_EMP = $_API_EMP['query'];
			$this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$this->_profile['shopcode']);
			$this->component_api->CallGet();
			$_API_SHOP = json_decode($this->component_api->GetConfig("result"), true);
			$_API_SHOP = $_API_SHOP['query'];
			$this->component_api->SetConfig("url", $this->config->item('URL_MENU_SIDE'));
			$this->component_api->CallGet();
			$_API_MENU = json_decode($this->component_api->GetConfig("result"), true);
			$_API_MENU = $_API_MENU['query'];

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
				"shop_code" => $_API_SHOP['shop_code'],
				"shop_name" => $_API_SHOP['name'],
				"today" => date("Y-m-d")
			];

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

			// fatch side bar 
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
	}
	public function index()
	{
		// variable initial
		$_API_CATEGORIES = [];
		$_modalshow = 0;
		
		// set create new modal pop up on initial
		if($this->input->get("new") == 1)
		{
			$_modalshow = 1;
		}
		
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES'));
		$this->component_api->CallGet();
		$_API_CATEGORIES = json_decode($this->component_api->GetConfig("result"), true);
		$_API_CATEGORIES = !empty($_API_CATEGORIES['query']) ? $_API_CATEGORIES['query'] : "";

		// function bar with next, preview and save button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => $this->_user_auth['create'], "extra" => "data-toggle='modal' data-target='#modal01'"]
			]
		]);
		// Main view loaded
		$this->load->view("categories/categories-view",[
			"base_url" => base_url("/products/categories/edit/"),
			"del_url" => base_url("/products/categories/delete/"),
			"route_url" => base_url("/products/categories/"),
			"data" => $_API_CATEGORIES,
			"user_auth" => $this->_user_auth,
			"default_per_page" => $this->_default_per_page,
			"page" => $this->_page,
			"modalshow" => $_modalshow
		]);

		$this->load->view("categories/categories-create-view",[
			"function_bar" => $this->load->view('function-bar', [
				"btn" => [
					["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/products/categories'), "style" => "", "show" => true],
					["name" => "Reset", "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
					["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
				 ]
			],true),
			"save_url" => base_url("/products/categories/save/")
		]);
		$this->load->view('footer');
	}
	/**
	 * Edit category
	 * @param cate_code with input category code
	 *  
	 */
	public function edit($cate_code="")
	{
		// variable initial
		$_previous_disable = "";
		$_next_disable = "";

		// API data
		//$_cate =  $this->session->userdata['cate_list'];
		$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES').$cate_code);
		$this->component_api->CallGet();
		$_API_CATEGORIES = json_decode($this->component_api->GetConfig("result"), true);
		$_API_CATEGORIES = $_API_CATEGORIES['query'];

		$_login = $this->session->userdata("login");
		// data convertion for items edit (next and previous functions)

		if(empty($_API_CATEGORIES['previous']))
		{
			$_previous_disable = "disabled";	
		}
		if(empty($_API_CATEGORIES['next']))
		{
			$_next_disable = "disabled";
		}
			// echo "<pre>";
			// var_dump ($_all);
			// echo "</pre>";

		// function bar with next, preview and save button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "Back", "type"=>"button", "id" => "back", "url"=> base_url('/products/categories'.$_login['preference']), "style" => "", "show" => true],
				["name" => "Save", "type"=>"button", "id" => "save", "url"=> "#", "style" => "", "show" => true],
				["name" => "Previous", "type"=>"button", "id" => "Previous", "url"=> base_url("/products/categories/edit/".$_API_CATEGORIES['previous'].$_login['preference']), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
				["name" => "Next", "type"=>"button", "id" => "Next", "url"=> base_url("/products/categories/edit/".$_API_CATEGORIES['next'].$_login['preference']), "style" => "btn btn-outline-secondary ". $_next_disable, "show" => true]
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
		$_login = $this->session->userdata("login");
		$_comfirm_show = true;
		
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES_HAS_ITEM').$cate_code);
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);

		if(isset($_data))
		{	
			if($_data['query'])
			{
				$_comfirm_show = false;	
			}

			$this->load->view("categories/categories-del-view",[
				"submit_to" => base_url('/products/categories/delete/confirmed/'.$cate_code),
				"to_deleted_num" => $cate_code,
				"confirm_show" => $_comfirm_show,
				"trans_url" => base_url("/products/items/"),
				"trans_code" => $_data['error']['message'],
				"return_url" => base_url('/products/categories'.$_login['preference'])
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
		$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES').$cate_code);
		$this->component_api->CallDelete();
		$result = json_decode($this->component_api->GetConfig("result"),true);
		if(isset($result['error']['message']) || isset($result['error']['code']))
		{
			$_login = $this->session->userdata("login");
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
			header("Refresh: 5; url=".base_url("/products/categories/".$_login['preference']));
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
				$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES'));
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
				if(isset($result['error']['message']) || isset($result['error']['code']))
				{
					$_login = $this->session->userdata("login");
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
					header("Refresh: 5; url=".base_url("/products/categories/".$_login['preference']));
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
				$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES').$cate_code);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
				if(isset($result['error']['message']) || isset($result['error']['code']))
				{
					$_login = $this->session->userdata("login");
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
					header("Refresh: 5; url=".base_url("/products/categories/".$_login['preference']));
				}
			}
		}
	}
	
}
