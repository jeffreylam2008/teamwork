<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Master
{
    private $_master = [];
    protected $_CI;
    /**
     * Constructor
     * Master data will store into cache while network issus
     */
    public function __construct()
	{
        $this->_CI =& get_instance();        
    }
    private function CheckAPIHealth()
    {
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_NETWORK'));
        $this->_CI->component_api->CallGet();
        $result = $this->_CI->component_api->GetConfig("result");

        // API call here
        // network has error
        if($result['Code'] > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    private function Sync()
    {
        // fetch data from API
 
        // menu
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item("URL_MENU_SIDE"));
        $this->_CI->component_api->CallGet();
        $this->_master['menu'] = $this->_CI->component_api->GetConfig("result");

        // Shops
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_SHOP'));
        $this->_CI->component_api->CallGet();
        $this->_master['shops'] = $this->_CI->component_api->GetConfig("result");

        // employee
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_EMPLOYEES'));
        $this->_CI->component_api->CallGet();
        $this->_master['employees'] = $this->_CI->component_api->GetConfig("result");

        // payment method
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_PAYMENT_METHODS'));
        $this->_CI->component_api->CallGet();
        $this->_master['paymentmethods'] = $this->_CI->component_api->GetConfig("result");

        // items
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_ITEMS'));
        $this->_CI->component_api->CallGet();
        $this->_master['items'] = $this->_CI->component_api->GetConfig("result");

        // payment terms
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_PAYMENT_TERMS'));
        $this->_CI->component_api->CallGet();
        $this->_master['paymentterms'] = $this->_CI->component_api->GetConfig("result");

        // categories
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_CATEGORIES'));
        $this->_CI->component_api->CallGet();
        $this->_master['categories'] = $this->_CI->component_api->GetConfig("result");

        // customers
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_CUSTOMERS'));
        $this->_CI->component_api->CallGet();
        $this->_master['customers'] = $this->_CI->component_api->GetConfig("result");
    
    }
    public function ToFile($filename)
    {
        
    }

    public function FatehAll()
    {
        return $this->_master;
    }
    public function Init()
    {

        // Cache empty, API network down
        // if (!($this->_CI->cache->file->get('master1')) && !$this->CheckAPIHealth())
        // {
        //     // bad.......No network, No cache
        //     echo 1;
        // }
        // Cache exist, API network down
        
        if ($this->_CI->cache->file->get('master1'))
        {
            // echo 2;
            $this->_master = $this->_CI->cache->file->get('master1');
        }
        // Cache empty, API network normal
        else{
            // echo 3;
            // use local data
            // clear previous cache
            $this->Update();
            // var_dump($this->_master);
        }
    }
    public function Update()
    {
        if($this->CheckAPIHealth())
        {
            $this->Remove();
            $this->Sync();
            $this->_CI->cache->file->save('master1', $this->_master,$this->_CI->config->item("MASTER_FILE_REFRESH_TIME"));
        }
    }
    private function Remove()
    {
        // clear master
        $this->_CI->cache->file->delete('master1');
    }
    public function FetchByKey($type = "", $name = "" ,$id = "")
    {
        $_result = [];
        $_ms = $this->_master;
        
        if(isset($_ms[$type]['query']) && !empty($_ms[$type]['query']))
        {
            foreach($_ms[$type]['query'] as $key => $val)
			{

                if($val[$name] === $id)
                {
                    $_result = $val;
                }
            }   
        }
        return $_result;
    }
}