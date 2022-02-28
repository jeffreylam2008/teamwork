<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Network
{
    private $_ip;
    public function __construct()
	{
        $this->_ip = getenv("REMOTE_ADDR");       
        
    }
    public function GetIP()
    {
        return $this->_ip;
    }
}