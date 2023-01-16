<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Security
{
    private $_username = "";

    public function __construct($username)
	{
        $this->_username = $username;
    }
}