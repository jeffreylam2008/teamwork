<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Master
{
    private $_master;
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
        $this->_master['health'] = json_decode($this->_CI->component_api->GetConfig("result"),true);

        // API call here
        // network has error
        if($this->_master['health']['Code'] > 0)
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
        $this->_master['menu'] = json_decode($this->_CI->component_api->GetConfig("result"),true);

        // Shops
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_SHOP'));
        $this->_CI->component_api->CallGet();
        $this->_master['shops'] = json_decode($this->_CI->component_api->GetConfig("result"), true);

        // employee
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_EMPLOYEES'));
        $this->_CI->component_api->CallGet();
        $this->_master['employees'] = json_decode($this->_CI->component_api->GetConfig("result"),true);

        // payment method
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_PAYMENT_METHODS'));
        $this->_CI->component_api->CallGet();
        $this->_master['paymentmethods'] = json_decode($this->_CI->component_api->GetConfig("result"),true);

        // items
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_PAYMENT_TERMS'));
        $this->_CI->component_api->CallGet();
        $this->_master['items'] = json_decode($this->_CI->component_api->GetConfig("result"), true);

        // categories
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_CATEGORIES'));
        $this->_CI->component_api->CallGet();
        $this->_master['categories'] = json_decode($this->_CI->component_api->GetConfig("result"), true);

        // customers
        $this->_CI->component_api->SetConfig("url", $this->_CI->config->item('URL_CUSTOMERS'));
        $this->_CI->component_api->CallGet();
        $this->_master['customers'] = json_decode($this->_CI->component_api->GetConfig("result"), true);   
    }
    public function ToFile($filename)
    {
        
    }
    public function Find($src, $what, $bywhere)
    {

    }
    public function FatehAll()
    {
        return $this->_master;
    }
    public function init()
    {
        
        // Cache empty, API network down
        if (($this->_CI->cache->get('master1')) && !$this->CheckAPIHealth())
        {
            // bad.......No network, No cache
            // echo 1;
        }
        // Cache exist, API network down
        elseif (!$this->_CI->cache->get('master1') && !$this->CheckAPIHealth())
        {
            // echo 2;
            $this->_master = $this->_CI->cache->get('master1');
        }
        // Cache empty, API network normal
        else{
            // echo 3;
            // use local data
            // clear previous cache
            $this->Remove();
            // fetch new data from API
            $this->Sync();
            $this->_CI->cache->save('master1', $this->_master,$this->_CI->config->item("MASTER_FILE_REFRESH_TIME"));

            // var_dump($this->_master);
        }
    }
    private function Remove()
    {
        // clear master
        $this->_CI->cache->delete('master1');
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