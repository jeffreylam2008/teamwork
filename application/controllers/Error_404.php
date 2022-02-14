<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error_404 extends CI_Controller
{
	var $_inv_header_param = [];
	var $_token = "";
	var $_profile = "";
	var $_username = "";

	public function __construct()
	{
		parent::__construct();
		$_API_EMP = [];
		$_API_SHOP = [];
		$_API_MENU = [];

		// call token from session
		if(!empty($this->session->userdata['login']))
		{
			// extend logon timeout
			//$this->session->set_tempdata('login',$this->session->userdata['login'],6000);
			$this->_token = $this->session->userdata['login']['token'];
			$this->_profile = $this->session->userdata['login']['profile'];
			$this->_username = $this->session->userdata['login']['profile']['username'];
		}

		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES').$this->_username);
		$this->component_api->CallGet();
		$_API_EMP = $this->component_api->GetConfig("result");
		$_API_EMP = $_API_EMP['query'];
		$this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$this->_profile['shopcode']);
		$this->component_api->CallGet();
		$_API_SHOP = $this->component_api->GetConfig("result");
		$_API_SHOP = $_API_SHOP['query'];
		$this->_inv_header_param["topNav"] = [
			"isLogin" => true,
			"username" => $_API_EMP['username'],
			"employee_code" => $_API_EMP['employee_code'],
			"shop_code" => $_API_SHOP['shop_code'],
			"shop_name" => $_API_SHOP['name'],
			"today" => date("Y-m-d"),
			"prefix" => "000"
		];
		// dummy data

		// load header view
		$this->load->view('header',[
			'title'=>'items',
			'sideNav_view' => "", 
			'topNav_view' => $this->load->view('top-nav', [
				"topNav" => $this->_inv_header_param["topNav"]
			], TRUE)
		]);
		
	}
	public function index()
	{
        $this->output->set_status_header('404');
        $this->load->view('error');
        $this->load->view('footer');
	}

}
