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
	var $API_HEADER;

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
			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_CATEGOIRES_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "",];

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
				"username" => $this->_API_HEADER['employee']['username'],
				"employee_code" => $this->_API_HEADER['employee']['employee_code'],
				"shop_code" => $this->_API_HEADER['employee']['shop_code'],
				"shop_name" => $this->_API_HEADER['employee']['shop_name'],
				"today" => date("Y-m-d")
			];


			$_query['page'] = htmlspecialchars($this->_page);
			$_query['show'] = htmlspecialchars($this->_default_per_page);
			$_query = $this->component_uri->QueryToString($_query);
			$_login = $this->session->userdata['login'];
			$_login['preference'] = $_query;
			$this->session->set_userdata("login", $_login);

			// fatch side bar 
			$this->component_sidemenu->SetConfig("nav_list", $this->_API_HEADER['menu']);
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
	
	/**
	 * Listing page of Categories 
	 * To list out categories
	 */
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
		$_API_CATEGORIES = $this->component_api->GetConfig("result");
		$_API_CATEGORIES = !empty($_API_CATEGORIES['query']) ? $_API_CATEGORIES['query'] : "";

		// function bar with next, preview and save button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => $this->_user_auth['create'], "extra" => "data-toggle='modal' data-target='#modal01'"]
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
					["name" => $this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/products/categories'), "style" => "", "show" => true],
					["name" => $this->lang->line("function_reset"), "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
					["name" => $this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
				 ]
			],true),
			"save_url" => base_url("/products/categories/save/")
		]);
		$this->load->view('footer');
	}
	/**
	 * Edit category
	 * @param cate_code with input category code
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
		$_API_CATEGORIES = $this->component_api->GetConfig("result");
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
				["name" => $this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=> base_url('/products/categories'.$_login['preference']), "style" => "", "show" => true],
				["name" => $this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=> "#", "style" => "", "show" => true],
				["name" => $this->lang->line("function_previous"), "type"=>"button", "id" => "Previous", "url"=> base_url("/products/categories/edit/".$_API_CATEGORIES['previous'].$_login['preference']), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
				["name" => $this->lang->line("function_next"), "type"=>"button", "id" => "Next", "url"=> base_url("/products/categories/edit/".$_API_CATEGORIES['next'].$_login['preference']), "style" => "btn btn-outline-secondary ". $_next_disable, "show" => true]
			]
		]);
		$this->load->view("categories/categories-edit-view", [
			"save_url" => base_url("/products/categories/edit/save/"),
			"data" => $_API_CATEGORIES
		]);
	}
	/**
	 * Delete
	 * @param cate_code with input of category code 
	 */
	public function delete($cate_code="")
	{
		$_data = [];
		$_trans_url = "";
		$_trans_code = "";
		// user data
		$_login = $this->session->userdata("login");
		$_comfirm_show = true;
		
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES_HAS_ITEM').$cate_code);
		$this->component_api->CallGet();
		$_data = $this->component_api->GetConfig("result");
		// echo "<pre>";
		// var_dump($_data['query']);
		// echo "</pre>";
		if(isset($_data))
		{	
			if($_data['query'])
			{
				$_comfirm_show = false;
				$_trans_url = base_url("/products/items/edit/".$_data['query']['item_code']);
				$_trans_code = $_data['query']['item_code'];
			}

			$this->load->view("categories/categories-del-view",[
				"submit_to" => base_url('/products/categories/delete/confirmed/'.$cate_code),
				"to_deleted_num" => $cate_code,
				"confirm_show" => $_comfirm_show,
				"trans_url" => $_trans_url,
				"trans_code" => $_trans_code,
				"return_url" => base_url('/products/categories'.$_login['preference'])
			]);
		}
	}
	/** 
	 * Process Save delete 
	 * @param cate_code with input of category code 
	 */
	public function savedel($cate_code = "")
	{
		$_login = $this->session->userdata("login");
		// function bar has go back button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/products/categories/'.$_login["preference"]), "style" => "", "show" => true],
			]
		]);

		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES').$cate_code);
		$this->component_api->CallDelete();
		$result = $this->component_api->GetConfig("result");

		if(isset($result['error']['message']) || isset($result['error']['code']))
		{
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
		$result = [];
		$alert = "danger";
		$_login = $this->session->userdata("login");
		// function bar has go back button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/products/categories/'.$_login["preference"]), "style" => "", "show" => true],
			]
		]);
		if(isset($_POST) && !empty($_POST))
		{
			$_api_body = json_encode($_POST,true);
			if($_api_body != "null")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES'));
				$this->component_api->CallPost();
				$result = $this->component_api->GetConfig("result");
				
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
		// callback initial page
		header("Refresh: 5; url=".base_url("/products/categories/".$_login['preference']));
	}
	/** 
	 * Process Save edit 
	 * @param cate_code with input of category code 
	 */
	public function saveedit($cate_code = "")
	{
		$result = [];
		$alert = "danger";
		$_login = $this->session->userdata("login");

		// function bar with next, preview and save button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/products/categories/'.$_login["preference"]), "style" => "", "show" => true],
			]
		]);

		if(isset($_POST) && !empty($_POST) && isset($cate_code) && !empty($cate_code))
		{
			$_api_body = json_encode($_POST,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			if($_api_body != "null")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES').$cate_code);
				$this->component_api->CallPatch();
				$result = $this->component_api->GetConfig("result");
				
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
		// callback initial page
		header("Refresh: 5; url=".base_url("/products/categories/".$_login['preference']));
	}
	
}
