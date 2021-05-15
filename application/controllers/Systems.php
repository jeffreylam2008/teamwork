<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Systems extends CI_Controller 
{
    var $_inv_header_param = [];
	var $_default_per_page = "";
	var $_page = "";
	var $_token = "";
	var $_profile = "";
	var $_param = "";
	var $_user_auth = ['create' => false, 'edit' => false, 'delete' => false];
	//var $_pm = [];
	//var $_pt = [];
	public function __construct()
	{
		parent::__construct();
		$_query = $this->input->get();
		// initial Access rule
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
		if(!empty($this->session->userdata['login']))
		{
			$this->_token = $this->session->userdata['login']['token'];
			$this->_profile = $this->session->userdata['login']['profile'];
		}
		
		$this->load->library("Component_Login",[$this->_token, "systems/index"]);

		// login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API call
			// fatch master
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
				case "systems/backup":
					$this->_param = "systems/index";
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
			//Set user preference
			$_query['page'] = htmlspecialchars($this->_page);
			$_query['show'] = htmlspecialchars($this->_default_per_page);
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
				'title'=>'Suppliers',
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
        $this->load->view('title-bar', [
            "title" => "Backup / Restore"
        ]);
        $this->load->view("systems/backup-view",[
            "submit_to" => base_url("#"),
			"checkheader_url" =>base_url("import/checkheader"),
            "products_import_url" => base_url("import/products"),
			"categories_import_url" => base_url("import/categories"),
			"customers_import_url" => base_url("import/customers"),
			"suppliers_import_url" => base_url("import/suppliers"),
			"paymentmethod_import_url" => base_url("import/paymentmethod"),
			"paymentterm_import_url" => base_url("import/paymentterm"),
			"employees_import_url" => base_url("import/employees"),
			"districts_import_url" => base_url("import/districts"),
            "products_export_url" => base_url("export/products"),
            "categories_export_url" => base_url("export/categories"),
            "customers_export_url" => base_url("export/customers"),
            "suppliers_export_url" => base_url("export/suppliers"),
            "paymentmethod_export_url" => base_url("export/paymentmethod"),
            "paymentterm_export_url" => base_url("export/paymentterm"),
            "employees_export_url" => base_url("export/employees"),
            "districts_export_url" => base_url("export/districts")
        ]); 

    }
	public function test()
	{
		$this->load->view("systems/test-view");
	}
}