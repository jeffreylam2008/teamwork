<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Logs 
{
    protected $_CI;
    /**
     * Constructor
     * 
     */
    public function __construct()
	{
        $this->_CI =& get_instance();
    }
    /**
     * Write Logs
     */
    public function AppLogs($_user, $_token ,$_app_type, $_msg)
    {
        $_path = $this->_CI->config->item('APP_LOG_PATH');
        $_file = $this->_CI->config->item('APP_LOG_FILE');
        $_file_location = $_path.$_file; 
        $_timestamp = date("Y-m-d H:i:s");
        if(!empty($_app_type) && !empty($_msg))
        {
            error_log($_timestamp." [".$_user." - ".$_token."] - [".$_app_type."] - ".$_msg."\r\n", 3, $_file_location);
        }
    }
}