<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Car
{
    var $_lic;
    var $_items;
    public function __construct($lic)
	{
        $this->_lic = $lic;
    }
    
    public function SetItems($item_code, $qty)
    {   
        $this->_items[$item_code] = $qty++;
        
    }
    public function GetItems()
    {
        return $this->_items;
    }
}