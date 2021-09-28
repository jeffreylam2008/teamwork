<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller
{
	var $_inv_header_param = [];
	var $_default_per_page = "";
	var $_page = "";
	var $_i_all_cate = "";
	var $_token = "";
	var $_profile = "";
	var $_param = "";
	var $_user_auth = ['create' => false, 'edit' => false, 'delete' => false];
	/**
	 * Constructor
	 */
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
		if($this->input->get("i-all-cate"))
		{
			$this->_i_all_cate = $this->input->get("i-all-cate");
		}

		// dummy data
		//$this->session->sess_destroy();
		// echo "<pre>";
		// var_dump(($_SESSION['login']));
		// echo "</pre>";
		// call token from session
		if(!empty($this->session->userdata['login']))
		{
			$this->_token = $this->session->userdata['login']['token'];
			$this->_profile = $this->session->userdata['login']['profile'];
		}

		// API call
		$this->load->library("Component_Login",[$this->_token, "products/items"]);

		// // login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES').$this->_profile['username']);
			$this->component_api->CallGet();
			$_API_EMP = json_decode($this->component_api->GetConfig("result"), true);
			$_API_EMP = !empty($_API_EMP['query']) ? $_API_EMP['query'] : ['username' => "", 'employee_code' => ""];
			$this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$this->_profile['shopcode']);
			$this->component_api->CallGet();
			$_API_SHOP = json_decode($this->component_api->GetConfig("result"), true);
			$_API_SHOP = !empty($_API_SHOP['query']) ? $_API_SHOP['query'] : ['shop_code' => "", 'name' => ""];
			$this->component_api->SetConfig("url", $this->config->item('URL_MENU_SIDE'));
			$this->component_api->CallGet();
			$_API_MENU = json_decode($this->component_api->GetConfig("result"), true);
			$_API_MENU = !empty($_API_MENU['query']) ? $_API_MENU['query'] : [];

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
				"shop_code" => $_API_SHOP['shop_code'],
				"shop_name" => $_API_SHOP['name'],
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
			
			// Navigator
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
	}

	/** 
	 * Main item page display 
	 * 
	 */
	public function index()
	{
		// variable initial

		$_where_arr = [];
		$_modalshow = 0;

		// input GET from previous page with name i-all-cate
		if($this->input->get("new") == 1)
		{
			$_modalshow = 1;
		}
		//  Call API
		$_where_arr = explode("/", $this->_i_all_cate);
		$_trim_where = implode("/",array_filter($_where_arr));
		$this->component_api->SetConfig("url", $this->config->item('URL_ITEMS_CATE').$_trim_where);
		$this->component_api->CallGet();
		$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
		$_API_ITEMS = !empty($_API_ITEMS['query']) ? $_API_ITEMS['query'] : [];
		
		$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES'));
		$this->component_api->CallGet();
		$_API_CATEGORIES = json_decode($this->component_api->GetConfig("result"), true);
		$_API_CATEGORIES = !empty($_API_CATEGORIES['query']) ? $_API_CATEGORIES['query'] : [];

		// $_API_CATEGORIES = $_API_CATEGORIES['query'];

		// get user preference
		$_login = $this->session->userdata("login");


		// data for items type selection
		if(!empty($_API_CATEGORIES))
		{
			// function bar with next, preview and save button
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=> "#", "style" => "", "show" => true, "extra" => "data-toggle='modal' data-target='#modal01'"],
					["name" => "<i class='fas fa-search'></i> ".$this->lang->line("function_search"), "type"=>"button", "id" => "search", "url"=> "#", "style" => "", "show" => true, "extra" => ""]
				]
			]);

			// Main view loaded
			$this->load->view("items/items-view",[
				"edit_url" => base_url("/products/items/edit/"),
				"del_url" => base_url("/products/items/delete/"),
				"data" => $_API_ITEMS,
				"user_auth" => true,
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"categories" => $_API_CATEGORIES,
				"where" => $_where_arr,
				"modalshow" => $_modalshow
			]);
			$this->load->view("items/items-create-view",[
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/products/items'), "style" => "", "show" => true],
						["name" => "<i class='fas fa-redo'></i> ".$this->lang->line("function_reset"), "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
					 ]
				],true),
				"save_url" => base_url("/products/items/save/"),
				"categories_baseurl" => base_url("/products/categories/?new=1"),
				"categories" => array_column($_API_CATEGORIES,"desc","cate_code"),
			]);
			$this->load->view('footer');
		}
	}

	/** 
	 * Edit Operation 
	 * @param item_code selected item code
	 */
	public function edit($item_code="")
	{
		// variable initial
		$_previous_disable = "";
		$_next_disable = "";
		$_remove_img = false;

		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_CATEGORIES'));
		$this->component_api->CallGet();
		$_API_CATEGORIES = json_decode($this->component_api->GetConfig("result"), true);
		$_API_CATEGORIES = !empty($_API_CATEGORIES['query']) ? $_API_CATEGORIES['query'] : [];
		$this->component_api->SetConfig("url", $this->config->item('URL_ITEMS').$item_code);
		$this->component_api->CallGet();
		$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
		$_API_ITEMS = !empty($_API_ITEMS['query']) ? $_API_ITEMS['query'] : [];
		

		$this->component_api->SetConfig("url", $this->config->item('URL_STOCKSONHAND').$item_code);
		$this->component_api->CallGet();
		$_API_ONHAND = json_decode($this->component_api->GetConfig("result"), true);
		$_API_ONHAND = !empty($_API_ONHAND['query']) ? $_API_ONHAND['query'] : [];

		$_API_ITEMS['stockonhand'] = $_API_ONHAND['qty'];
		$_API_ITEMS['desc'] = trim($_API_ITEMS['desc']);
		if(empty($_API_ITEMS['image_body']))
		{
			$_API_ITEMS['image_body'] = "data:image/png;base64,".base64_encode(file_get_contents(base_url("/assets/img/empty-img.jpg")));	
			$_remove_img = true;
		}
		else
		{
			$_API_ITEMS['image_body'] = "data:image/png;base64,".$_API_ITEMS['image_body'];	
		}

		$_login = $this->session->userdata("login");
		// data convertion for items edit (next and previous functions)
		if(!empty($_API_ITEMS))
		{
			if(empty($_API_ITEMS["previous"]))
			{
				$_previous_disable = "disabled";
			}
			if(empty($_API_ITEMS["next"]))
			{
				$_next_disable = "disabled";
			}
			// function bar with next, preview and save button
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/products/items'.$_login["preference"]), "style" => "", "show" => true],
					["name" => "<i class='fas fa-redo'></i> ".$this->lang->line("function_reset"), "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true],
					["name" => "<i class='fas fa-step-backward'></i> ".$this->lang->line("function_previous"), "type"=>"button", "id" => "previous", "url"=> base_url("/products/items/edit/".$_API_ITEMS["previous"].$_login["preference"]), "style" => "btn btn-outline-secondary ".$_previous_disable, "show" => true],
					["name" => "<i class='fas fa-step-forward'></i> ".$this->lang->line("function_next"), "type"=>"button", "id" => "next", "url"=> base_url("/products/items/edit/".$_API_ITEMS["next"].$_login["preference"]), "style" => "btn btn-outline-secondary ". $_next_disable , "show" => true]
				]
			]);

			// main view loaded
			$this->load->view("items/items-edit-view",[
				//"categories_baseurl" => base_url("/products/categories/"),
				"save_url" => base_url("/products/items/edit/save/"),
				"categories_baseurl" => base_url("/products/categories/?new=1"),
				"data" => $_API_ITEMS,
				"categories" => array_column($_API_CATEGORIES,"desc","cate_code"),
				"types" => [1 => "Non Inventory", 2 => "Inventory", 3 => "Non Inventory - Point"],
				"remove_img" => $_remove_img
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
		$this->load->view('footer');
	}

	/** 
	 * Delete Page Display 
	 * @param item_code selected item code to be delete
	 */
	public function delete($item_code="")
	{
		$_data = [];
		$_trans_url = "";
		$_trans_code = "";
		$_login = $this->session->userdata("login");
		$_comfirm_show = true;

		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_INVENTORY_HAS_TRANSACTION_D').$item_code);
		$this->component_api->CallGet();
		$_data = json_decode($this->component_api->GetConfig("result"), true);
		// echo "<pre>";
		// var_dump($_data['query']);
		// echo "</pre>";
		if(isset($_data))
		{	
			if($_data['query'])
			{
				$_comfirm_show = false;
				$_trans_url = base_url("/invoices/edit/".$_data['query']['trans_code']);
				$_trans_code = $_data['query']['trans_code'];
			}
			$this->load->view("items/items-del-view",[
				"submit_to" => base_url('/products/items/delete/confirmed/'.$item_code),
				"to_deleted_num" => $item_code,
				"confirm_show" => $_comfirm_show,
				"trans_url" => $_trans_url,
				"trans_code" => $_trans_code,
				"return_url" => base_url('/products/items'.$_login['preference'])
			]);
		}
	}

	/** 
	 * Save Operation 
	 * @param item_code selected item code will be saved
	 */
	public function savedel($item_code="")
	{
		$_login = $this->session->userdata("login");
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_ITEMS').$item_code);
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
			header("Refresh: 5; url=".base_url("/products/items/".$_login['preference']));
		}
	}

	/** 
	 * Save Operation for create 
	 */
	public function savecreate()
	{
		$_NEW_POST = [];
		$_FILES['i-img']['content'] = "";
		$_login = $this->session->userdata("login");
		if(isset($_POST) && !empty($_POST))
		{
			if(!empty($_FILES['i-img']['tmp_name'])){
				$_FILES['i-img']['content'] = $this->component_file->Encode($_FILES['i-img']);
			}
			$_NEW_POST = array_merge($_POST, $_FILES);
			$_api_body = json_encode($_NEW_POST,true);
			
			// echo "<per>";
			// var_dump($_api_body);
			// echo "</per>";

			if($_api_body != "null")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_ITEMS'));
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
					header("Refresh: 5; url=".base_url("/products/items/".$_login['preference']));
				}
			}
		}
	}

	/** 
	 * Save  Operation for Edit 
	 * @param item_code selected item code 
	 */
	public function saveedit($item_code="")
	{
		$_NEW_POST = [];
		$_login = $this->session->userdata("login");

		
		if(isset($_POST) && !empty($_POST) && isset($item_code) && !empty($item_code))
		{
			if(!empty($_FILES))
			{
				$_FILES['i-img']['content'] = "";
				$_FILES['i-img']['name'] = "";
				
				if(!empty($_FILES['i-img']['tmp_name'])){
					$_FILES['i-img']['content'] = $this->component_file->Encode($_FILES['i-img']);
				}
				$_NEW_POST = array_merge($_POST, $_FILES);
			}
			else
			{
				$_NEW_POST = $_POST;
			}
			
		 	$_api_body = json_encode($_NEW_POST,true);

			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";

			if($_api_body != "null")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_ITEMS').$item_code);
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
					header("Refresh: 2; url=".base_url("/products/items/".$_login['preference']));
				}
			}
		}
	}
}
