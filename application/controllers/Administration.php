<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class administration extends CI_Controller 
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

        // call token from session
		if(!empty($this->session->userdata['login']))
		{
			// extend logon timeout
			$this->_token = $this->session->userdata['login']['token'];
			$this->_profile = $this->session->userdata['login']['profile'];
		}

		// load library
		$this->load->library("Component_Login",[$this->_token, "invoices/list"]);

		// // login session
		if(!empty($this->component_login->CheckToken()))
		{
            // API data
			$this->component_api->SetConfig("url", $this->config->item('URL_INVOICES_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "", "prefix", "dn"=> ["dn_num"=>"", "dn_prefix"=>""]];
			
			// echo "<pre>";
			// var_dump($_API_HEADER);
			// echo "/<pre>";

			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			switch($this->_param)
			{
				case "invoices/edit":
					$this->_param = "invoices/index";
				break;
				case "invoices/tender":
					$this->_param = "invoices/index";
				break;
			}
			
			// fatch employee API
			$this->_inv_header_param["topNav"] = [
				"isLogin" => true,
				"username" => $this->_API_HEADER['employee']['username'],
				"employee_code" => $this->_API_HEADER['employee']['employee_code'],
				"shop_code" => $this->_API_HEADER['employee']['shop_code'],
				"shop_name" => $this->_API_HEADER['employee']['shop_name'],
				"today" => date("Y-m-d"),
				"prefix" => $this->_API_HEADER['prefix']['prefix']
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
			// fatch side bar API
			$this->component_sidemenu->SetConfig("nav_list", $this->_API_HEADER['menu']);
			$this->component_sidemenu->SetConfig("active", $this->_param);
			$this->component_sidemenu->Proccess();

			// render the view
			$this->load->view('header',[
				'title'=>'Configuration',
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
        
        // function bar
        $this->load->view('function-bar', [
            "btn" => [
                ["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id"=>"save", "url"=>"#" , "style"=>"btn btn-primary", "show" => true],
            ]
        ]);
        $this->load->view("administration/general-view", [
            "submit_to" => base_url("/administration/save"),
            "debug_mode" => $this->config->item('DEBUG_MODE'),
            "default_per_page" =>  $this->config->item('DEFAULT_PER_PAGE')
        ]);
        $this->load->view("footer");
    }
    public function save()
    {
        echo APPPATH . 'config/config.php';
        $_data = json_decode($_POST["i-post"], true);
        $this->load->library('ConfigWriter', array('file'=> APPPATH.'config/config.php', 'variable_name'=>'config'));
        $this->configwriter->write('DEFAULT_PER_PAGE', $_data['perpage'] );
        $this->configwriter->write('DEBUG_MODE', $_data['debugmode'] );
        echo $_POST['i-post'];
        // echo $this->config->item('DEBUG_MODE');
        // echo "<br>";
        // echo $this->config->item('DEFAULT_PER_PAGE');
        //redirect(base_url('/administration/general'),"refresh");
    }

}