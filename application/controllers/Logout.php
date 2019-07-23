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
        // for debug
        // echo "<pre>";
        // var_dump( $_SESSION);
        // echo "</pre>";

    }
    public function index()
    {  
        unset($_SESSION['cur_invoicenum']);
        unset($_SESSION['cur_quotationnum']);
        unset($_SESSION['transaction']);
        unset($_SESSION['login']);
        redirect(base_url("login"));
    }
}