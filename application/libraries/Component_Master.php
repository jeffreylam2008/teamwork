<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Master
{
    private $_master;
    protected $_CI;
    /**
     * Constructor
     *
     * 
     * 
     */
    public function __construct()
	{
        $this->_CI =& get_instance();
        // var_dump($token);
    }
    /**
     * Inital
     * 
     * @return result Result true for login success, false for failure
     */
    public function Init()
    {
        // API call here
        // menu
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('api_url')."/systems/menu/side");
        $this->_CI->component_api->CallGet();
        $this->_master['menu'] = json_decode($this->_CI->component_api->GetConfig("result"), true);
        
        // Shops
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('api_url')."/systems/shops/");
		$this->_CI->component_api->CallGet();
        $this->_master['shop'] = json_decode($this->_CI->component_api->GetConfig("result"), true);

        // employee
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('api_url')."/systems/employees/");
		$this->_CI->component_api->CallGet();
        $this->_master['employees'] = json_decode($this->_CI->component_api->GetConfig("result"),true);

        // payment method
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('api_url')."/systems/payments/");
		$this->_CI->component_api->CallGet();
        $this->_master['paymethod'] = json_decode($this->_CI->component_api->GetConfig("result"),true);

        // items
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('api_url')."/products/items/");
		$this->_CI->component_api->CallGet();
        $this->_master['items'] = json_decode($this->_CI->component_api->GetConfig("result"), true);
        
        // categories
		$this->_CI->component_api->SetConfig("url", $this->_CI->config->item('api_url')."/products/categories/");
		$this->_CI->component_api->CallGet();
		$this->_master['categories'] = json_decode($this->_CI->component_api->GetConfig("result"), true);
        
        // customers
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('api_url')."/customers/");
		$this->_CI->component_api->CallGet();
        $this->_master['customers'] = json_decode($this->_CI->component_api->GetConfig("result"), true);

        
        // put data to session
        $this->_CI->session->set_userdata("master",$this->_master);
        
        return true;
    }
    public function FetchAll()
    {
        return $this->_master;
    }
    public function Refresh()
    {
        $this->Init();
    }
    public function Remove()
    {
        // clear master
        $this->_master = [];
        $this->_CI->session->set_userdata("master",$this->_master);
    }
    public function FetchByKey($item = "", $name = "" ,$id = "")
    {
        if(!empty($this->_master[$item]))
        {
            foreach($this->_master[$item] as $key => $val)
			{
                if($val[$name] === $id)
                {
                    return $this->_master[$item][$key];
                }
            }
        }
    }
}