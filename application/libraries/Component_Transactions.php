<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Transactions
{
    private $_transactions = ["T" => []];
    public function __construct()
	{
        if(isset($_SESSION['T']))
        {
            $this->_transactions['T'] = $_SESSION['T'];
        }
        $this->save();
    }
    public function Has($id)
    {
        if(isset($this->_transactions['T'][$id]) && !empty($this->_transactions['T'][$id]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function Add($id, $data)
    {
        $this->_transactions['T'][$id] = $data;
        $this->save();
    }
    public function GetAll()
    {
        if(!empty($this->_transactions['T']))
        {
            return $this->_transactions['T'];
        }
        return;
    }
    public function Get($id)
    {
        if(isset($this->_transactions['T'][$id]) && !empty($this->_transactions['T'][$id]))
        {
            return $this->_transactions['T'][$id];
        }
        return;
    }
    public function Remove($id)
    {
        if(isset($this->_transactions['T'][$id]) && !empty($this->_transactions['T'][$id]))
        {
            $this->_transactions['T'][$id] = "";
            $this->save();
            return true;
        }
        return false;
    }
    public function RemoveAll()
    {
        unset($this->_transactions['T']);
        $this->save();
        return true;
    }
    private function save()
    {
        $_SESSION['T'] = $this->_transactions['T'];
    }

}