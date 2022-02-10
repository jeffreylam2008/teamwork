<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Load 
{
    public $_redirect;
    public function __construct()
	{
        $this->_CI =& get_instance();
        
        // var_dump($token);
    }
    public function loading()
    {
        $this->_CI->load->view("loading-view");
    }
    public function redirect($redirect)
    {
        header("Refresh: 0; url='".base_url($redirect)."'");
    }
}