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

        $this->session->sess_destroy();
    }
    public function index()
    {
        echo "load logout";
        redirect(base_url("login"));
    }
}