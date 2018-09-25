<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		
		// dummy data
		// $sampleNavData["sideNav"] = [
		// 	["order" => 2, "id" => 1, "parent_id" => "", "name" => "login", "isActive" => "", "slug"=>"login"],
		// 	["order" => 1, "id" => 2, "parent_id" => "", "name" => "Dushboard", "isActive" => "", "slug"=>"dushboard"],
		// 	["order" => 3, "id" => 3, "parent_id" => "", "name" => "Product", "isActive" => "active", "slug"=>""],
		// 	["order" => 3, "id" => 23, "parent_id" => 3, "name" => "Items", "isActive" => "active", "slug"=>"products/items"],		
		// 	["order" => 1, "id" => 54, "parent_id" => 3, "name" => "Categories", "isActive" => "", "slug"=>"products/categories"],
		// 	["order" => 2, "id" => 22, "parent_id" => "", "name" => "Administration", "isActive" => "", "slug"=>""],
		// 	["order" => 2, "id" => 62, "parent_id" => 3, "name" => "Settings", "isActive" => "", "slug"=>"products/settings"],
		// 	["order" => 1, "id" => 71, "parent_id" => 22, "name" => "Settings", "isActive" => "", "slug"=>"administration/settings"],
		// 	["order" => 1, "id" => 555, "parent_id" => 44, "name" => "test item 2", "isActive" => "", "slug"=>"test/test/test_item2"],
		// 	["order" => 1, "id" => 44, "parent_id" => 71, "name" => "test item1", "isActive" => "", "slug"=>"test/test_item1"]
		// ];
		// echo "<pre>";
		// var_dump($_SESSION);
		// echo "</pre>";
		$username = "iamadmin";
		// fatch employee API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employee/index.php/".$username);
		$this->component_api->CallGet();
		$_employee = json_decode($this->component_api->GetConfig("result"),true);
		//var_dump($_employee);
		$this->_inv_header_param["topNav"] = [
			"isLogin" => true,
			"username" => "",
			"employee_code" => "110022",
			"prefix" => "INV",
			"shop_code" => "0012",
			"today" => date("Y-m-d")
		];

		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/index.php/side");
		$this->component_api->CallGet();
		$nav_list = json_decode($this->component_api->GetConfig("result"), true);
		$this->component_sidemenu->SetConfig("nav_list", $nav_list);
		$this->component_sidemenu->SetConfig("active",true);
		$this->component_sidemenu->Proccess();

		// load header view
		$this->load->view('header',[
			'title'=>'Items',
			'sideNav_view' => $this->load->view('side-nav', ["sideNav"=>$this->component_sidemenu->GetConfig("nav_finished_list")], TRUE), 
			'topNav_view' => $this->load->view('top-nav', ["topNav" => $this->_inv_header_param["topNav"]], TRUE)
		]);
		// load breadcrumb
		//$this->load->view('breadcrumb');
	}
	public function index($page = "")
	{
		// variable initial
		$_default_per_page = 50;
	
		if(!empty($page))
		{
			$_uri = $this->uri->uri_to_assoc(1);
			$_page = $_uri["page"];
		}
		else
		{
			$_page = 1;
		}
		
		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/categories/index.php");
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);
		
		//set user data
		$this->session->set_userdata('page',$_page);
		$this->session->set_userdata('cate_list',$_data);

		// function bar with next, preview and save button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "New", "type"=>"button", "id" => "newitem", "url"=> base_url("/products/categories/new"), "style" => "", "show" => true]
			]
		]);
		// Main view loaded
		$this->load->view("categories/categories-view",[
			"base_url" => base_url("/products/categories/edit/"),
			"route_url" => base_url("/products/categories/page/"),
			"data" => $_data,
			"default_per_page" => $_default_per_page,
			"page" => $_page
		]);
		$this->load->view('footer');
	}
	public function edit($cate_code="")
	{
		// variable initial
		$_next_btn_show = true;
		$_previous_btn_show = true;
		$_page = 1;

		// set user data
		$_page = $this->session->userdata("page");
		$_cate = $this->session->userdata('cate_list');

		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/categories/index.php/".$cate_code);
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);

		// data convertion for items edit (next and previous functions)
		if(!empty($_cate))
		{
			$_all = array_column($_cate['query'], "cate_code");
			// echo "<pre>";
			// var_dump($_items['query']);
			
			
			// search key
			$_key = array_search(
				$cate_code, array_column($_cate['query'], "cate_code")
			);
			// echo "</pre>"; 
			$_cur = $_key;
			$_next = $_key + 1;
			$_previous = $_key - 1;
			
			if($_cur == (count($_all)-1))
			{
				$_next_btn_show = false;
				$_next = (count($_all)-1);
			}
			if($_cur <= 0)
			{
				$_previous_btn_show = false;
				$_previous = 0;
			}
			// echo "<pre>";
			// var_dump ($_all);
			// echo "</pre>";
		}


		// function bar with next, preview and save button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "Back", "type"=>"button", "id" => "Back", "url"=> base_url('/products/categories/page/'.$_page), "style" => "", "show" => true],
				["name" => "Save", "type"=>"button", "id" => "Save", "url"=>"", "style" => "", "show" => true],
				["name" => "|<<<", "type"=>"button", "id" => "Previous", "url"=> base_url("/products/categories/edit/".$_all[$_previous]), "style" => "btn btn-link", "show" => $_previous_btn_show],
				["name" => ">>>|", "type"=>"button", "id" => "Next", "url"=> base_url("/products/categories/edit/".$_all[$_next]), "style" => "btn btn-link", "show" => $_next_btn_show]
			]
		]);
		$this->load->view("categories/categories-edit-view", [
			"data" => $_data,
		]);
	}
	public function create()
	{
		// set user data
		$_page = 1;
		$_page = $this->session->userdata("page");


		// function bar with next, preview and save button
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "Back", "type"=>"button", "id" => "Back", "url"=> base_url('/products/categories/page/'.$_page), "style" => "", "show" => true],
				["name" => "Save", "type"=>"button", "id" => "Save", "url"=>"", "style" => "", "show" => true]
			]
		]);
		$this->load->view("categories/categories-create-view");
	}

	function savecreate()
	{

	}
	function saveedit()
	{
		
	}
}
