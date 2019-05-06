<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller
{
	var $_inv_header_param = [];
	var $_token = "";
	var $_param = "";

	public function __construct()
	{
		parent::__construct();
		$_API_EMP = [];
		// $this->load->library("Component_Master");
		// if(isset($this->session->userdata['master']))
		// {
			// dummy data
			//$this->session->sess_destroy();
			// echo "<pre>";
			// var_dump(($_SESSION['login']));
			// echo "</pre>";
			// call token from session
			if(!empty($this->session->userdata['login']))
			{
				$this->_token = $this->session->userdata['login']['token'];
			}

			// API call
			$this->load->library("Component_Login",[$this->_token, "products/items"]);

			// // login session
			if(!empty($this->component_login->CheckToken()))
			{
				$this->_username = $this->session->userdata['login']['profile']['username'];
				// fatch employee API

				// API data
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employees/".$this->_username);
				$this->component_api->CallGet();
				$_API_EMP = json_decode($this->component_api->GetConfig("result"), true);
				$_API_EMP = $_API_EMP['query'];
				$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/");
				$this->component_api->CallGet();
				$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
				$_API_ITEMS = $_API_ITEMS['query'];
				

				// sidebar session
				$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
				switch($this->_param)
				{
					case "items/edit":
						$this->_param = "items/index";
					break;
					case "items/delete":
						$this->_param = "items/index";
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
					'title'=>'Items',
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

	/** 
	 * Item page display 
	 * 
	 */
	public function index($_page = 1)
	{	
		// variable initial
		$_default_per_page = 50;
		$_API_ITEMS = [];
		$_API_CATEGORIES = [];
		$_items = [];

		$_where = "";
		if($_get = $this->input->get())
		{
			foreach($_get as $k => $v)
			{
				$_where .= "/".$k;	
			}
		}

		// API data
		if(!empty($_where))
		{
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/where/".$_where);
			$this->component_api->CallGet();
			$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
			$_API_ITEMS = $_API_ITEMS['query'];
			
		}
		else{
			$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/");
			$this->component_api->CallGet();
			$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
			$_API_ITEMS = $_API_ITEMS['query'];	
		}
		
		$_where_arr = explode("/", $_where);

		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/categories/");
		$this->component_api->CallGet();
		$_API_CATEGORIES = json_decode($this->component_api->GetConfig("result"), true);
		$_API_CATEGORIES = $_API_CATEGORIES['query'];
		

		// data for ordering items in sequence
		foreach($_API_ITEMS as $key => $val)
		{
			$_items[]['item_code'] = $val['item_code'];
		}
		$this->session->set_userdata('items_list',$_items);
		
		// data for items type selection
		if(!empty($_API_CATEGORIES))
		{
			// set user data
			$this->session->set_userdata('page',$_page);

			if(!empty($_API_CATEGORIES))
			{
				foreach($_API_CATEGORIES as $key => $val)
				{
					$_categories[$val["cate_code"]] = $val["desc"];
				}
			}
			
			// function bar with next, preview and save button
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=> "#", "style" => "", "show" => true, "extra" => "data-toggle='modal' data-target='#modal01'"],
					["name" => "<i class='fas fa-search'></i> Search", "type"=>"button", "id" => "search", "url"=> "#", "style" => "", "show" => true, "extra" => ""]
				]
			]);

			// Main view loaded
			$this->load->view("items/items-view",[
				"edit_url" => base_url("/products/items/edit/"),
				"del_url" => base_url("/products/items/delete/"),
				"route_url" => base_url("/products/items/page/"),
				"data" => $_API_ITEMS,
				"user_auth" => true,
				"default_per_page" => $_default_per_page,
				"page" => $_page,
				"categories" => $_categories,
				"where" => $_where_arr
			]);
			$this->load->view("items/items-create-view",[
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/products/items/page/'.$_page), "style" => "", "show" => true],
						["name" => "Reset", "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
					 ]
				],true),
				"save_url" => base_url("/products/items/save/"),
				"categories_baseurl" => base_url("/products/categories/"),
				"categories" => $_categories
			]);
			$this->load->view('footer');
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
		$_page = 1;
		$_items = [];
		// user data

		$_page = $this->session->userdata("page");
		$_items = $this->session->userdata['items_list'];

		// API data
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/categories/");
		$this->component_api->CallGet();
		$_API_CATEGORIES = json_decode($this->component_api->GetConfig("result"), true);
		$_API_CATEGORIES = $_API_CATEGORIES['query'];
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/".$item_code);
		$this->component_api->CallGet();
		$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
		$_API_ITEMS = $_API_ITEMS['query'];
		$_API_ITEMS['desc'] = trim($_API_ITEMS['desc']);

		
		// data convertion for items edit (next and previous functions)
		if(!empty($_items))
		{
			$_all = array_column($_items, "item_code");
			
			// search key
			$_key = array_search(
				$item_code, array_column($_items, "item_code")
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
				if(!empty($_API_CATEGORIES))
				{
					foreach($_API_CATEGORIES as $key => $val)
					{
						$_categories[$val["cate_code"]] = $val["desc"];
					}
				}
				// function bar with next, preview and save button
				$this->load->view('function-bar', [
					"btn" => [
						["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/products/items/page/'.$_page), "style" => "", "show" => true],
						["name" => "Reset", "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true],
						["name" => "Previous", "type"=>"button", "id" => "previous", "url"=> base_url("/products/items/edit/".$_all[$_previous]), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
						["name" => "Next", "type"=>"button", "id" => "next", "url"=> base_url("/products/items/edit/".$_all[$_next]), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
					]
				]);

				// main view loaded
				$this->load->view("items/items-edit-view",[
					"categories_baseurl" => base_url("/products/categories/"),
					"save_url" => base_url("/products/items/edit/save/"),
					"data" => $_API_ITEMS,
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
	 * Delete Page Display 
	 * 
	 */
	public function delete($item_code="")
	{
		// user data
		$_page = $this->session->userdata("page");
		$_comfirm_show = true;
		$_page = 1;
		
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
