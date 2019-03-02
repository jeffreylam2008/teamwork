<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller
{
    private $_master = [];
    public function __construct()
	{
        parent::__construct();
    }
    public function index()
    { 
        $this->Init();
        $mm = $this->FetchAll();   
        //echo "Master files load completed";
        echo "<pre>";
        // var_dump(array_keys($mm));
        //$this->Remove();
        var_dump(array_keys($mm));
        echo "</pre>";
        echo "<pre>";
        var_dump($_SESSION['master']['employee']);
        echo "</pre>";
    }
    public function Init()
    {
        
        // API call here
        // Shops
        $this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
		$this->component_api->CallGet();
        $this->_master['shop']  = json_decode($this->component_api->GetConfig("result"), true);

        // employee
        $this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employee/");
		$this->component_api->CallGet();
        $this->_master['employee']  = json_decode($this->component_api->GetConfig("result"),true);

        // payment method
        $this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/");
		$this->component_api->CallGet();
        $this->_master['paymethod'] = json_decode($this->component_api->GetConfig("result"),true);

        // items
        $this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/");
		$this->component_api->CallGet();
        $this->_master['items'] = json_decode($this->component_api->GetConfig("result"), true);
        
        // categories
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/categories/");
		$this->component_api->CallGet();
		$this->_master['categories'] = json_decode($this->component_api->GetConfig("result"), true);
        
        // customers
        $this->component_api->SetConfig("url", $this->config->item('api_url')."/customers/");
		$this->component_api->CallGet();
        $this->_master['customers'] = json_decode($this->component_api->GetConfig("result"), true);

        // put data to session
        $this->session->set_userdata("master",$this->_master);
    }
    public function FetchAll()
    {
        return $this->_master;
    }
    public function Refresh()
    {
        // blind API again to renew the master file
        $this->Init();
    }
    public function Remove()
    {
        // clear master
        $this->_master = [];
        $this->session->set_userdata("master",$this->_master);
    }
}