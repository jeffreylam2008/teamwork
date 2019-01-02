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
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops");
		$this->component_api->CallGet();
		$_shop = json_decode($this->component_api->GetConfig("result"), true);
		$this->load->view('login/login-view', [
			"submit"=>"login/process")
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
		// Call database model
		$res = $this->login->GetUserInfo($loginID);
		// debug here
		echo $loginID . " === > " .  crypt($password, $res['password']) ." ===> ". $rememberMe . " ===> ".$com_code ."====>".$this->input->ip_address()."<br>";
		//echo "res:<br>";
		//var_dump($res);
		
		// Check if has this user
		if(hash_equals($res['password'], crypt($password, $res['password'])))
		{
			// Gather Data
			$theData = array(
				// Gather data 
				'username' => $res['username'],
				"company_code" => $res['company_code'],
				'log_date' => mdate('%Y-%m-%d %h:%i:%s',time()),
				'ip_addr' => $this->input->ip_address(),
				'is_login' => 1,
				'token' => md5($res['username']."-".$res['company_code']."-".$this->input->ip_address())
			);
			// debug here
			//echo "theData: <br>";
			//var_dump($theData);
			if($this->login->hasToken($theData['token']))
			{
				$this->login->Logout_Update($theData['token']);
			}
			// check concurrent user
			$cur_sess_res = $this->login->GetUserLoginSession($res['company_code']);
			$constant_sess_res = $this->login->GetUserLoginSessionLimit($res['company_code']);

			if(empty($constant_sess_res))
			{
				echo "Company master data not set!";
				exit();
			}
			// Check if has concurrent user record in database
			empty($cur_sess_res) ? $concur_user = 0 : $concur_user = $cur_sess_res['num_login'];
			// debug here
			//var_dump($cur_sess_res);
			//echo "concurr user = ".$concur_user;

			if($concur_user <= $constant_sess_res['session'])
			{
				// allow login and write record to DB
				if($this->login->hasToken($theData['token']))
				{
					// increase 1 session login
					$this->login->Login_Update($theData['token']);
				}
				else
				{
					// create new session on database
					$err = $this->login->Login_Insert($theData);
				}

				$theData['company_name'] = $res['company_name'];
				$theData['dpm_code'] = $res['dpm_code'];
				// write recode to session
				if($rememberMe)
				{	
					// Set user data
					$this->session->set_userdata("login_info", $theData);
					// Retrieve session data
					$sess_data = $this->session->userdata("login_info");

				}
				else {
					$this->session->set_tempdata('login_info', $theData, 86400);
					// Retrieve session data
					$sess_data = $this->session->userdata("login_info");
				}
				// debug here
				//echo "Sess_data: <br>";
				//var_dump($sess_data);
				
				header("location: ". base_url('dushboard'));
			}
			else
			{	
				// reject login
				header("location: ".base_url('login/?err=454')); 
			}
		}
		else
		{
			header("location: ".base_url('login/?err=1').""); 
		}
	}
}
