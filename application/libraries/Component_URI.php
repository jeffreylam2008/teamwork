<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_URI
{
    private $config = [
        "uri" => [],
        "this_uri" => []
    ];
    public function __construct()
	{
        
    }
    public function Parse()
    {
        if(!empty($this->config["uri"]))
        {
           $this->config["this_uri"] = array_slice($this->config["uri"],0,-2);   
        }
    }
    public function SetConfig($func, $val)
    {
        $this->config[$func] = $val;
    }
    public function GetConfig($func)
    {
        return $this->config[$func];
    }
}