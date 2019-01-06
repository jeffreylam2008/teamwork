<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
	var $_inv_header_param = [];
	public function __construct()
	{
		parent::__construct();
		
		// dummy data
		$username = "iamadmin";

		// fatch employee API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employee/".$username);
		$this->component_api->CallGet();
		$_employee = json_decode($this->component_api->GetConfig("result"),true);
		//var_dump($_employee);


		//load header view
		$this->load->view('login/login-header-view',[
			'title'=>'Login'
		]);

	}

	public function index()
	{
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
		$this->component_api->CallGet();
		$_shop = json_decode($this->component_api->GetConfig("result"), true);
		$this->load->view('login/login-view', [
			"shop" => $_shop['query'],
			"submit"=>"login/process"
		]);
		$this->load->view('footer');
	}
	public function dologin()
	{
		$_api_body = [];
		// Get user input here
		$_loginID = $this->input->post('i-username');
		$_password = $this->input->post('i-password');
		$_shopcode = $this->input->post("i-shops");
		$_rememberme = $this->input->post("i-rememberme");
		$_api_body["loginid"] = $_loginID;
		$_api_body["password"] = $_password;
		$_api_body["shopcode"] = $_shopcode;
		$_api_body = json_encode($_api_body, true);
		echo $_api_body;
		$this->component_api->SetConfig("body", $_api_body);
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/login/");
		$this->component_api->CallPost();
		$_result = json_decode($this->component_api->GetConfig("result"), true);
		var_dump($_result);
	}
}
