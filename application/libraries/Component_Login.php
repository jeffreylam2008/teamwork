<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Login 
{
    private $_args;
    protected $_CI;
    /**
     * Constructor
     * 
     * @param token Token input
     */
    public function __construct($args)
	{
        $this->_CI =& get_instance();
        $this->_args = $args;
    }
    /**
     * Check
     * 
     * @return result Result true for login success, false for failure
     */
    public function Check()
    {

       if(!empty($this->_args[0]))
       {
            $this->_CI->load->library("component_api");
            // API Call: check validation token in Server side 
            $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('api_url')."/systems/login/".$this->_args[0]);
            $this->_CI->component_api->CallGet();
            $_api_result = json_decode($this->_CI->component_api->GetConfig("result"),true);

            if(!empty($_api_result['query']))
            {
               
                
            }
       }
    }
}