<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Delivery
{
    var $_dnote;
    public function __construct()
	{
        
    }
    public function GetDeliveryNote()
    {
        return $this->_dnote;
    }
}