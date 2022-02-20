<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller
{
    public function __construct()
	{
        parent::__construct();
    }
    public function index()
    {   

        echo "update";
        echo "<br>";
        $this->component_master->init();
        echo "<br>";
        echo "fetch all";
        $_master = $this->component_master->FatehAll();     
        echo "<pre>";

         var_dump($_master);
        // foreach($_master as $k => $v)
        // {
        //     var_dump($k);
        // }
        echo "</pre>";
    } 
}