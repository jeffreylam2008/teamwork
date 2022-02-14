<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();
        /**
		* ******Cation******
		* All session delete at this point
		*/

        //$this->session->sess_destroy();
    }
    public function index()
    {
        // // have url already
        // echo "<pre>";
        // var_dump($_SESSION);
        // echo "</pre>";
        // Reset login session

        $_login = $this->session->userdata('login');
        // API Call
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/logout/".$_login['token']);
		$this->component_api->CallPatch();
        $_result = $this->component_api->GetConfig("result");
        // clear session 

        $this->session->set_userdata('login',"");

        header("Refresh: 0; url='".base_url('login')."'");
    }
}