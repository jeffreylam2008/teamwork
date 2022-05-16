<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Transactions
{
    //private $_transactions;
    //protected $_CI;
    // private $_session_id;

    public function __construct()
	{
    }
    public function Has($session_id,$id)
    {
        if(isset($_SESSION['T'][$session_id][$id]) && !empty($_SESSION['T'][$session_id][$id]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function Add($session_id, $id, $data)
    {
        $_SESSION['T'][$session_id][$id] = $data;
    }
    public function GetAll()
    {
        if(isset($_SESSION['T']) && !empty($_SESSION['T']))
        {
            return $_SESSION['T'];
        }
        return;
    }
    public function Get($session_id,$id)
    {
        if(isset($_SESSION['T'][$session_id][$id]) && !empty($_SESSION['T'][$session_id][$id]))
        {
            return $_SESSION['T'][$session_id][$id];
        }
        return;
    }
    public function Remove($session_id)
    {
        if(isset($_SESSION['T'][$session_id]))
        {
            unset($_SESSION['T'][$session_id]);
        }
        return;
    }
    public function RemoveAll()
    {
        if(isset($_SESSION['T']))
        {
            unset($_SESSION['T']);
        }
        return;
    }
}