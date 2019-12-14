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

        
        // API Call
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/logout/".$_SESSION['login']['token']);
		$this->component_api->CallPatch();
        $_result = json_decode($this->component_api->GetConfig("result"), true);
        // clear session 
        $_SESSION['login'] = "";
        // var_dump($_result);
        redirect("login","refresh");
    }
}