<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller
{
	var $_inv_header_param = [];
	public function __construct()
	{
		parent::__construct();
		
		// dummy data

		$username = "iamadmin";
		// sidebar session
		$_param = $this->router->fetch_class()."/".$this->router->fetch_method();
		switch($_param)
		{
			case "items/edit":
				$_param = "items/index";
			break;
		}
		
		echo $_param;
		
		// fatch employee API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employee/".$username);
		$this->component_api->CallGet();
		$_employee = json_decode($this->component_api->GetConfig("result"),true);
		//var_dump($_employee);
		$this->_inv_header_param["topNav"] = [
			"isLogin" => true,
			"username" => "",
			"employee_code" => "110022",
			"shop_code" => "0012",
			"today" => date("Y-m-d")
		];
		// fatch side bar API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
		$this->component_api->CallGet();
		$_nav_list = json_decode($this->component_api->GetConfig("result"), true);
		$this->component_sidemenu->SetConfig("nav_list", $_nav_list);
		$this->component_sidemenu->SetConfig("active", $_param);
		$this->component_sidemenu->Proccess();
		// echo "<pre>";
		// var_dump( $this->component_sidemenu->GetConfig("slug"));
		// echo "</pre>";
		
		// load header view
		$this->load->view('header',[
			'title'=>'Items',
			'sideNav_view' => $this->load->view('side-nav', [
				"sideNav"=>$this->component_sidemenu->GetConfig("nav_finished_list"),
				"path"=>$this->component_sidemenu->GetConfig("path"),
				"param"=> $_param
			], TRUE), 
			'topNav_view' => $this->load->view('top-nav', [
				"topNav" => $this->_inv_header_param["topNav"]
			], TRUE)
		]);
		// load breadcrumb
		//$this->load->view('breadcrumb');
	}

	/** 
	 * Item page display 
	 * 
	 */
	public function index($page="")
	{	
		// variable initial
		$_default_per_page = 50;
		$_categories = [];
		if(empty($page))
		{
			$page = 1;
		}

		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/");
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/categories/");
		$this->component_api->CallGet();
		$_data_categories = json_decode($this->component_api->GetConfig("result"), true);
		
		// data for items type selection
		if(!empty($_data_categories["query"]))
		{
			foreach($_data_categories["query"] as $key => $val)
			{
				$_categories[$val["cate_code"]] = $val["desc"];
			}
			

			// set user data
			$this->session->set_userdata('page',$page);
			$this->session->set_userdata('items_list',$_data);

			// function bar with next, preview and save button
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=> "#", "style" => "", "show" => true, "extra" => "data-toggle='modal' data-target='#modal01'"]
				]
			]);

			// Main view loaded
			$this->load->view("items/items-view",[
				"edit_url" => base_url("/products/items/edit/"),
				"del_url" => base_url("/products/items/delete/"),
				"route_url" => base_url("/products/items/page/"),
				"data" => $_data,
				"user_auth" => true,
				"default_per_page" => $_default_per_page,
				"page" => $page
			]);
			$this->load->view("items/items-create-view",[
				"save_url" => base_url("/products/items/save/"),
				"categories_baseurl" => base_url("/products/categories/"),
				"categories" => $_categories
			]);
			$this->load->view('footer');
		}
	}

	/** 
	 * Delete Page Display 
	 * 
	 */
	public function delete($item_code="")
	{
		// user data
		$_page = $this->session->userdata("page");
		$_comfirm_show = true;
		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/inventory/invoices/transaction/d/".$item_code);
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);
		if(isset($_data))
		{
			// configure message 
			if($_data['query'])
			{
				$_comfirm_show = false;
			}
			// function bar with next, preview and save button
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "Back", "type"=>"button", "id" => "Back", "url"=>base_url('/products/items/page/'.$_page), "style" => "", "show" => true],
					["name" => "Yes", "type"=>"button", "id" => "yes", "url"=>base_url('/products/items/delete/confirmed/'.$item_code), "style" => "btn btn-outline-danger", "show" => $_comfirm_show],
				]
			]);
			// main view loaded
			$this->load->view("items/items-del-view",[
				"item_code" => $item_code,
				"trans_url" => base_url("/invoices/edit/".$_data['query']['trans_code']),
				"data" => $_data,
			]);
		}
	}

	/** 
	 * Edit Page Display 
	 * 
	 */
	public function edit($item_code="")
	{
		
		// variable initial
		$_categories = [];
		$_previous_disable = "";
		$_next_disable = "";

		// user data
		$_page = $this->session->userdata("page");
		$_items = $this->session->userdata('items_list');

		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/categories/");
		$this->component_api->CallGet();
		$_data_categories = json_decode($this->component_api->GetConfig("result"), true);
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/".$item_code);
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);
		$_data['query']['desc'] = trim($_data['query']['desc']);
		echo "<pre>";
		var_dump($_data);
		echo "</pre>";
		
		// data convertion for items edit (next and previous functions)
		if(!empty($_items))
		{
			$_all = array_column($_items['query'], "item_code");
			// echo "<pre>";
			// var_dump($_items['query']);
			// echo "</pre>";
			
			// search key
			$_key = array_search(
				$item_code, array_column($_items['query'], "item_code")
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
				// var_dump ($_all);
				// echo "</pre>";
				// data for items type selection
				if(!empty($_data_categories["query"]))
				{
					foreach($_data_categories["query"] as $key => $val)
					{
						$_categories[$val["cate_code"]] = $val["desc"];
					}
				}
				// function bar with next, preview and save button
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/products/items/page/'.$_page), "style" => "", "show" => true],
						["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "", "show" => true],
						["name" => "Previous", "type"=>"button", "id" => "previous", "url"=> base_url("/products/items/edit/".$_all[$_previous]), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
						["name" => "Next", "type"=>"button", "id" => "next", "url"=> base_url("/products/items/edit/".$_all[$_next]), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
					]
				]);

				// main view loaded
				$this->load->view("items/items-edit-view",[
					"categories_baseurl" => base_url("/products/categories/"),
					"save_url" => base_url("/products/items/edit/save/"),
					"data" => $_data,
					"categories" => $_categories
				]);
			}
			else
			{
				$alert = "danger";
				$this->load->view('error-handle', [
					'message' => "Item Code not found!", 
					'code'=> "", 
					'alertstyle' => $alert
				]);
			}
		}
	}

	/** 
	 * Process Save delete 
	 * 
	 */
	public function savedel($item_code="")
	{
		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/".$item_code);
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
			header("Refresh: 5; url=".base_url("/products/items/"));
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
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/");
				$this->component_api->CallPost();
				$result = json_decode($this->component_api->GetConfig("result"),true);

				if(isset($result['message']) || isset($result['code']))
				{
					$alert = "danger";
					switch($result['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}					
					
					$this->load->view('error-handle', [
						'message' => $result['message'], 
						'code'=> $result['code'], 
						'alertstyle' => $alert
					]);
			
					// callback initial page
					header("Refresh: 5; url=".base_url("/products/items/"));
				}
			}
		}
	}

	/** 
	 * Process Save Edit 
	 * 
	 */
	public function saveedit($item_code="")
	{
		if(isset($_POST) && !empty($_POST) && isset($item_code) && !empty($item_code))
		{
			$_api_body = json_encode($_POST,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";

			if($_api_body != "null")
			{

				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/".$item_code);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);

				// echo "<pre>";
				// var_dump($result);
				// echo "</pre>";
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
					header("Refresh: 5; url=".base_url("/products/items/"));
				}
			}
		}
	}
}
