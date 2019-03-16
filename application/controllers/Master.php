<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller
{
    public function __construct()
	{
        
        parent::__construct();

        $this->load->library("Component_Master");
    }
    public function index()
    {   
        $this->component_master->Init();

        // master file checking
        // items
        echo "Master<br>";
        echo "----------------------<br>";

        if(isset($this->session->userdata['master']['menu']))
        {
            if($this->session->userdata['master']['menu']['error']['code']==="00000")
            {
                echo "(menu) = ".count($this->session->userdata['master']['menu']['query'])." Record loaded.<br>";
            }
        }
      
        if(isset($this->session->userdata['master']['shop']))
        {
            if($this->session->userdata['master']['shop']['error']['code']==="00000")
            {
                echo "(shop) = ".count($this->session->userdata['master']['shop']['query'])." Record loaded.<br>";
            }
        }
        
        if(isset($this->session->userdata['master']['employees']))
        {
            if($this->session->userdata['master']['employees']['error']['code']==="00000")
            {
                echo "(employees) = ".count($this->session->userdata['master']['employees']['query'])." Record loaded.<br>";
            }
        }
        
        if(isset($this->session->userdata['master']['paymethod']))
        {
            if($this->session->userdata['master']['paymethod']['error']['code']==="00000")
            {
                echo "(paymethod) = ".count($this->session->userdata['master']['paymethod']['query'])." Record loaded.<br>";
            }
        }

        if(isset($this->session->userdata['master']['items']))
        {
            if($this->session->userdata['master']['items']['error']['code']==="00000")
            {
                echo "(items) = ".count($this->session->userdata['master']['items']['query'])." Record loaded.<br>";
            }
        }
         
        if(isset($this->session->userdata['master']['categories']))
        {
            if($this->session->userdata['master']['categories']['error']['code']==="00000")
            {
                echo "(categories) = ".count($this->session->userdata['master']['categories']['query'])." Record loaded.<br>";
            }
        } 

        if(isset($this->session->userdata['master']['customers']))
        {
            if($this->session->userdata['master']['customers']['error']['code']==="00000")
            {
                echo "(customers) = ".count($this->session->userdata['master']['customers']['query'])." Record loaded.<br>";
            }
        }
        echo "<pre>"; 
        var_dump(($this->session->userdata['master']['categories']['query']));
        echo "</pre>";
        // echo "testbed";
        // echo "<br>";
        // echo "------------<br>";
        //$tt = $this->component_master->SearchByKey("employees","username","iamadmin");
        // echo "<pre>"; 
        //var_dump($tt);
        // echo "</pre>";
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