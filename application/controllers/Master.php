<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class master extends CI_Controller
{
    public function __construct()
	{
        
        parent::__construct();

        $this->load->library("component_master");
    }
    public function index()
    {   
        $this->component_master->Init();
        echo "<pre>"; 
        var_dump($this->session->userdata("master"));
        echo "</pre>";
        //echo "Master files load completed";
        // echo "<pre>";
        // // var_dump(array_keys($mm));
        // //$this->Remove();
        // var_dump(array_keys($mm));
        // echo "</pre>";
        // echo "<pre>";
        // var_dump($_SESSION['master']['employee']);
        // echo "</pre>";
    }

}