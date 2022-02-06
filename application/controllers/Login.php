<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
	var $_inv_header_param = [];
	public function __construct()
	{
		parent::__construct();
		//load header view
		$this->load->view('login/login-header-view',[
			'title'=>'Login'
		]);
	}

	public function index()
	{	
		$this->component_api->SetConfig("url", $this->config->item('URL_SHOP'));
		$this->component_api->CallGet();
		$_shop = json_decode($this->component_api->GetConfig("result"), true);
		// error handling here 
		$_e_code = "";
		$_e_msg = "";
		if(!empty($this->input->get("e_code")))
		{
			$_e_code = $this->input->get("e_code");
			$_e_msg = $this->input->get("e_msg");
		}
		$this->load->view('login/login-view', [
			"shop" => $_shop['query'],
			"submit"=>"login/process/?url=".urlencode($this->input->get('url')),
			"e_code"=> $_e_code,
			"e_msg" => $_e_msg,
			"s_status" => $_SESSION
		]);
		$this->load->view('footer');
	}
	public function dologin()
	{
		$_api_body = [];
		//echo "debug here ===> ";
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
		$this->component_api->SetConfig("url", $this->config->item('URL_LOGIN'));
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
				'shopcode' => $this->input->post('i-shops',true)
			];
			// remember the password 
			if($_rememberme)
			{
				// save cookie
				$this->session->set_userdata('login',$_profile);
			}
			// will not remember the password
			else
			{
				// save temp cookie
				$this->session->set_tempdata('login',$_profile,86400);
			}
			if(!empty($this->input->get('url')))
			{
				// have url already
				redirect($this->input->get('url')."?token=".$_profile['token'],"refresh");
			}
			else
			{
				// No url perpare
				redirect(base_url($this->config->item('default_home')."/?token=".$_profile['token']),"refresh");
			}
		}
		else
		{
			// something went wrong 
			// No url perpare

			$_e_code = urlencode($_result['error']['code']);
			$_e_msg = urlencode($_result['error']['message']);
			$_url = urlencode($this->input->get('url'));
			//redirect(base_url("login?url=".$_url."&e_code=".$_e_code."&e_msg=".$_e_msg),"refresh");
		}
	}
}
