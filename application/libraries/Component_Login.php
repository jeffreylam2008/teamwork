<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Login 
{
    private $_token = "";
    private $_redirect_url = "";
    protected $_CI;
    /**
     * Constructor
     * 
     * @param token Token input as array
     * array[0] existing token
     * array[1] redirect URL
     * 
     */
    public function __construct($param)
	{
        $this->_CI =& get_instance();
        $this->_token = $param[0];
        $this->_redirect_url = $param[1];
        // var_dump($token);
    }
    /**
     * Check
     * 
     * @return result Result true for login success, false for failure
     */
    public function CheckToken()
    {
    //    if(!empty($this->_token))
    //    {
        $this->_CI->load->library("component_api");
        // API Call: check validation token in Server side 
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('api_url')."/systems/login/".$this->_token);
        $this->_CI->component_api->CallGet();
        $_api_result = json_decode($this->_CI->component_api->GetConfig("result"),true);

        if(!empty($_api_result['query']))
        {
            return $_api_result['query'];
        }
        else
        {
            return false;
        }
    //    }
    }
    public function GetRedirectURL()
    {
        if(!empty($this->_redirect_url))
        {
            return base_url($this->_redirect_url);
        }
        else
        {
            return base_url("dushboard");
        }
    }
}