<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Car
{
    var $_lic;
    var $_items = [];
    public function __construct($param)
	{
        $this->_lic = $param[0];
    }
    
    public function SetItems($item_code, $qty)
    {   
        if(isset($this->_items[$item_code]))
        {
            $this->_items[$item_code] += $qty;
        }
        else
        {
            $this->_items[$item_code] = $qty;
        }
        
    }
    public function GetItems()
    {
        return $this->_items;
    }

    public function Order($date)
    {
        
    }
}