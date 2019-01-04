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
		// Get user input here
		$loginID = $this->input->post('login_id');
		$password = $this->input->post('login_pwd');
		$com_code = $this->input->post("com_code");
		$rememberMe = $this->input->post("login_rem");
		
	}
}
