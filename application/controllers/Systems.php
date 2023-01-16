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
		
		$this->load->library("Component_Login",[$this->_token, "systems/backuprestore"]);

		// login session
		if(!empty($this->component_login->CheckToken()))
		{
			//API data
			$this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];

			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "systems/backuprestore":
					$this->_param = "systems/backuprestore";
				break;
				case "systems/access":
					$this->_param = "systems/access";
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
			//Set user preference
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

    public function backuprestore()
    {
        $this->load->view('title-bar', [
            "title" => "Backup / Restore: "
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

	public function access()
	{
		$this->load->view('title-bar', [
            "title" => "Access Right Configuration:"
        ]);
	}
}