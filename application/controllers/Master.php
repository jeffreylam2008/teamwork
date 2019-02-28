<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller
{
    private $_master = [];
    public function __construct()
	{
        parent::__construct();
    }
    public function Init()
    {
        // API call here
        // Shops
        $this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/shops/");
		$this->component_api->CallGet();
        $this->master['shop']  = json_decode($this->component_api->GetConfig("result"), true);

        // employee
        $this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employee/");
		$this->component_api->CallGet();
        $this->master['employee']  = json_decode($this->component_api->GetConfig("result"),true);

        // payment method
        $this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/payments/");
		$this->component_api->CallGet();
        $this->master['paymethod'] = json_decode($this->component_api->GetConfig("result"),true);

        // items
        $this->component_api->SetConfig("url", $this->config->item('api_url')."/products/items/");
		$this->component_api->CallGet();
        $this->master['items'] = json_decode($this->component_api->GetConfig("result"), true);
        
        // categories
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/products/categories/");
		$this->component_api->CallGet();
		$this->master['categories'] = json_decode($this->component_api->GetConfig("result"), true);
		
    }

    public function Refresh()
    {

    }
    public function Remove()
    {

    }
}