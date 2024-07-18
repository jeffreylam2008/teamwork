<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_default_per_page = "";
	var $_page = "";
	var $_token = "";
	var $_profile = "";
	var $_param = "";
	var $_user_auth = ['create' => false, 'edit' => false, 'delete' => true];
	var $_API_HEADER;
	/**
	 * Payment method and term constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$_query = ['page'=> "" , 'show' => ""];
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
		if(!empty($this->session->userdata['login']))
		{
			$this->_token = $this->session->userdata['login']['token'];
			$this->_profile = $this->session->userdata['login']['profile'];
		}
		
		$this->load->library("Component_Login",[$this->_token, "employees"]);

		// login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "",];

			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "employees/edit":
					$this->_param = "employees/index";
				break;
				case "employees/delete":
					$this->_param = "employees/index";
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

			// Call API here
			$this->component_sidemenu->SetConfig("nav_list", $this->_API_HEADER['menu']);
			$this->component_sidemenu->SetConfig("active", $this->_param);
			$this->component_sidemenu->Proccess();

			// load header view
			$this->load->view('header',[
				'title'=>'Employees',
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
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES'));
		$this->component_api->CallGet();
		$_API_EMPLOYEES = $this->component_api->GetConfig("result");
		$_API_EMPLOYEES = !empty($_API_EMPLOYEES['query']) ? $_API_EMPLOYEES['query'] : [];
		
		$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES')."roles/");
		$this->component_api->CallGet();
		$_API_EMPLOYEES_ROLES = $this->component_api->GetConfig("result");
		$_API_EMPLOYEES_ROLES = !empty($_API_EMPLOYEES_ROLES['query']) ? $_API_EMPLOYEES_ROLES['query'] : [];
		
		$this->component_api->SetConfig("url", $this->config->item('URL_SHOP'));
		$this->component_api->CallGet();
		$_API_SHOPS = $this->component_api->GetConfig("result");
		$_API_SHOPS = !empty($_API_SHOPS['query']) ? $_API_SHOPS['query'] : [];

		$_login = $this->session->userdata['login'];

		// API data usage
		if(!empty($_API_EMPLOYEES))
		{
			// load function bar view
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => $this->_user_auth['create'], "extra" => "data-toggle='modal' data-target='#modal01'"]
				]
			]);

			// load main view
			$this->load->view('/employees/employees-view', [
				"edit_url" => base_url("/administration/employees/edit/"),
				"del_url" => base_url("/administration/employees/delete/"),
				'data' => $_API_EMPLOYEES,
				"user_auth" => $this->_user_auth,
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page
			]);
			$this->load->view("/employees/employees-create-view",[
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/employees'), "style" => "", "show" => true],
						["name" => "<i class='fas fa-undo-alt'></i> ".$this->lang->line("function_reset"), "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
					 ]
				],true),
				
				"save_url" => base_url("/administration/employees/save"),
				"data" => ["shop"=>$_API_SHOPS, "emp_roles" => $_API_EMPLOYEES_ROLES] 
			]);
			$this->load->view('footer');
		}
	}

	/**
	 * Edit employee configure 
	 * 
	 */
	public function edit($_employee_code)
	{
		if(empty($_employee_code))
		{	
			echo "System Message: Wrong employee input!";
			return;
		}
		// variable initial
		
		//API call here
		$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES_CODE').$_employee_code);
		$this->component_api->CallGet();
		$_API_EMPLOYEES = $this->component_api->GetConfig("result");
		$_API_EMPLOYEES = !empty($_API_EMPLOYEES['query']) ? $_API_EMPLOYEES['query'] : [];
		
		$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES_ROLES'));
		$this->component_api->CallGet();
		$_API_ROLES = $this->component_api->GetConfig("result");
		$_API_ROLES = !empty($_API_ROLES['query']) ? $_API_ROLES['query'] : [];

		$this->component_api->SetConfig("url", $this->config->item('URL_SHOP'));
		$this->component_api->CallGet();
		$_API_SHOPS = $this->component_api->GetConfig("result");
		$_API_SHOPS = !empty($_API_SHOPS['query']) ? $_API_SHOPS['query'] : [];

		if(empty($_API_EMPLOYEES))
		{
			echo "System Message: Fetch employee error!";
			return;
		}
		$_login = $this->session->userdata['login'];
		// function bar here
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/employees'.$_login['preference']), "style" => "", "show" => true],
				["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true],
			]
		]);
		// load view here
		$this->load->view('/employees/employees-edit-view', [
			"save_url" => base_url("administration/employees/edit/save/".$_employee_code),
			"shops" => $_API_SHOPS,
			"employees" => $_API_EMPLOYEES,
			"roles" => $_API_ROLES
		]);
	}

	/**
	 * Delete employee
	 */
	public function delete($_employee_code = "")
	{
		$_login = $this->session->userdata("login");
		$_comfirm_show = true;
		// echo "<pre>";
		// var_dump($_data['query']);
		// echo "</pre>";

		$this->load->view("items/items-del-view",[
			"submit_to" => base_url('/administration/employees/delete/confirmed/'.$_employee_code),
			"to_deleted_num" => $_employee_code,
			"confirm_show" => $_comfirm_show,
			"return_url" => base_url('/administration/employees/'.$_login['preference'])
		]);
		
	}

	/**
	 * save employees crete configure setting
	 *
	 */
	public function save()
	{	
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
		$_api_body = "";
		$result = "";
		$_login = $this->session->userdata("login");
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/employees'.$_login["preference"]), "style" => "", "show" => true],
			]
		]);
		if(!empty($_POST))
		{
			$_api_body = json_encode($_POST,true);
			//echo $_api_body;
			// API data
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES'));
			$this->component_api->CallPost();
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
				
			// callback initial page
			header("Refresh: 3; url=".base_url("/administration/employees"));
			
		}
	}

	/**
	 * save employees configure setting
	 *
	 */
	public function saveedit($_employee_code = "")
	{
		$_api_body = "";
		$alert = "danger";
		$result = "";
		$_login = $this->session->userdata("login");
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/employees'.$_login["preference"]), "style" => "", "show" => true],
			]
		]);
		if(empty($_POST))
		{
			echo "System Msg: No data submitted or data lost in transmit!";
			return;
		}
		if(empty($_POST['i-pwd']))
		{
			// do change pwd
			unset($_POST["i-pwd"]);
			unset($_POST["i-confirm-pwd"]);
		}
		$_api_body = json_encode($_POST,true);
		// echo $_api_body;
		// API data
		//echo $this->config->item('URL_EMPLOYEES').$_employee_code;
		$this->component_api->SetConfig("body", $_api_body);
		$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES').$_employee_code);
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
			
		// callback initial page
		header("Refresh: 3; url=".base_url("/administration/employees"));
		return;
		// echo "<pre>";
		// var_dump($_POST);
		// echo "</pre>";
	}

	/**
	 * to save delete employee
	 * 
	 */
	public function savedel($_employee_code = "")
	{
		$alert = "danger";
		$result = "";
		// variable define
		$_login = $this->session->userdata("login");
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/employees'.$_login["preference"]), "style" => "", "show" => true],
			]
		]);
		// gather user information
		$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES').$_employee_code);
		$this->component_api->CallDelete();
		$result = $this->component_api->GetConfig("result");
		// send data to view 
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
		header("Refresh: 3; url=".base_url("/administration/employees"));
		return;
	}
}
