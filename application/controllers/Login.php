<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
	var $_inv_header_param = [];
	public function __construct()
	{
		parent::__construct();
		
		// dummy data
		// $username = "iamadmin";

		// // fatch employee API
		// $this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employee/".$username);
		// $this->component_api->CallGet();
		// $_employee = json_decode($this->component_api->GetConfig("result"),true);
		//var_dump($_employee);

		//load header view
		$this->load->view('login/login-header-view',[
			'title'=>'Login'
		]);
	}

	public function index()
	{
		$this->session->sess_destroy();
		
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
		$this->component_api->CallGet();
		$_shop = json_decode($this->component_api->GetConfig("result"), true);
		$this->load->view('login/login-view', [
			"shop" => $_shop['query'],
			"submit"=>"login/process/?url=".urlencode($this->input->get('url'))
			// "submit"=>"login/process"
		]);
		$this->load->view('footer');
	}
	public function dologin()
	{
		$_api_body = [];

		echo "process page ===> ";
		//echo $this->input->get('url');
		// Get user input here
		$_rememberme = $this->input->post("i-rememberme");
		$_api_body["username"] = $this->input->post('i-username',true);
		$_api_body["password"] = $this->input->post('i-password',true);
		$_api_body["shopcode"] = $this->input->post('i-shops',true);
		$_api_body = json_encode($_api_body, true);
		// echo $_api_body;
		// echo "<br>";
		$this->component_api->SetConfig("body", $_api_body);
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/login/");
		$this->component_api->CallPost();
		$_result = json_decode($this->component_api->GetConfig("result"), true);
		//var_dump($_result);
		// has token return from API
		if(!empty($_result['query']))
		{
			$_profile = [];
			$_profile['token'] = $_result['query'];
			$_profile['profile'] = [
				'username' => $this->input->post('i-username',true),
				//'password' => $this->input->post('i-password',true),
				'shopcode' => $this->input->post('i-shops',true)
			];
			// remember the password 
			if($_rememberme)
			{
				// save cookie
				$this->session->set_userdata('profile',$_profile);
			}
			// will not remember the password
			else
			{
				// save temp cookie
				$this->session->set_tempdata('profile',$_profile,10);
			}
			if(!empty($this->input->get('url')))
			{
				// have url already
				redirect($this->input->get('url')."?token=".$_profile['token'],"refresh");
			}
			else
			{
				// No url perpare
				redirect(base_url($this->config->item['default_home']."/?token=".$_profile['token']),"refresh");
			}
		}
		else
		{
			// No url perpare
			redirect(base_url("login"),"refresh");
		}
	}
}
