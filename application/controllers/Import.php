<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller 
{
    var $products_word;
    var $products_length;
    var $categories_word;
    var $categories_length;
    var $customers_word;
    var $customers_length;
    var $suppliers_word;
    var $suppliers_length;
    var $paymentmethod_word;
    var $paymentmethod_length;
    var $paymentterm_word;
    var $paymentterm_length;

    public function __construct()
	{
        parent::__construct();
        $this->products_word = '"item Code"';
        $this->products_length = 8;
        $this->categories_word = '"categories code"';
        $this->categories_length = 2;
        $this->customers_word = '"customer code"';
        $this->customers_length = 30;
        $this->suppliers_word = '"Supplier code"';
        $this->suppliers_length = 11;
        $this->paymentmethod_word = '"payment method code"';
        $this->paymentmethod_length = 2;
        $this->paymentterm_word = '"payment term code"';
        $this->paymentterm_length = 2;
    }

    public function stocktake()
    {
        header('Content-Type: application/json; charset=utf-8');
        $response = array();
        echo $this->component_file->ReadCSVContents($_FILES['i-import']);
        die();
    }
    public function checkheader($type)
    {
        header('Content-Type: application/json; charset=utf-8');
        $response = array();
        $content = $this->component_file->ReadCSVContents($_FILES['i-import']);
        $content = json_decode($content);
        if($type == "products" && strcmp($content->data[0][0], $this->products_word) == 0 && count($content->data[0]) == $this->products_length )
        {
            $response = array(
                "length" => count($content->data),
                "status" => "success",
                "error" => false,
                "message" => "Checked Header - OK!"
            );
        }
        elseif($type == "categories" && strcmp($content->data[0][0],$this->categories_word) == 0 && count($content->data[0]) == $this->categories_length)
        {
            $response = array(
                "length" => count($content->data),
                "status" => "success",
                "error" => false,
                "message" => "Checked Header - OK!"
            );  
        }
        elseif($type == "customers" && strcmp($content->data[0][0],$this->customers_word) == 0 && count($content->data[0]) == $this->customers_length)
        {
            $response = array(
                "length" => count($content->data),
                "status" => "success",
                "error" => false,
                "message" => "Checked Header - OK!"
            );  
        }
        elseif($type == "suppliers" && strcmp($content->data[0][0],$this->suppliers_word) == 0 && count($content->data[0]) == $this->suppliers_length)
        {
            $response = array(
                "length" => count($content->data),
                "status" => "success",
                "error" => false,
                "message" => "Checked Header - OK!"
            );  
        }
        else
        {
            $response = array(
                "data" => "",
                "status" => "failure",
                "error" => true,
                "message" => "Wrong Header"
            );
        }
        echo json_encode($response);
        //echo json_encode($_FILES);
        die();
    }
    public function products()
    {
        header('Content-Type: application/json; charset=utf-8');
        $response = array();
        $content = $this->component_file->ReadCSVContents($_FILES['i-import']);
        $content = json_decode($content);
        if(strcmp($content->data[0][0],$this->products_word) == 0 && count($content->data[0]) == $this->products_length)
        {
            $response = array(
                "data" => $content->data,
                "status" => "success",
                "error" => false,
                "message" => "file content"
            );
        }
        else
        {
            $response = array(
                "data" => "",
                "status" => "failure",
                "error" => true,
                "message" => "Wrong Header"
            );
        }
        echo json_encode($response);
        //echo json_encode($_FILES);
        die();
    }
    public function categories()
    {
        header('Content-Type: application/json; charset=utf-8');
        $response = array();
        $content = $this->component_file->ReadCSVContents($_FILES['i-import']);
        $content = json_decode($content);
        if(strcmp($content->data[0][0],$this->categories_word) == 0 && count($content->data[0]) == $this->categories_length)
        {
            $response = array(
                "data" => $content->data,
                "status" => "success",
                "error" => false,
                "message" => "file content"
            );  
        }
        else
        {
            $response = array(
                "data" => "",
                "status" => "failure",
                "error" => true,
                "message" => "Wrong Header"
            );
        }
        echo json_encode($response);
        die();
    }
    public function customers()
    {
        header('Content-Type: application/json; charset=utf-8');
        $response = array();
        $content = $this->component_file->ReadCSVContents($_FILES['i-import']);
        $content = json_decode($content);
        if(strcmp($content->data[0][0],$this->customers_word) == 0 && count($content->data[0]) == $this->customers_length)
        {
            $response = array(
                "data" => $content->data,
                "status" => "success",
                "error" => false,
                "message" => "file content"
            );  
        }
        else
        {
            $response = array(
                "data" => "",
                "status" => "failure",
                "error" => true,
                "message" => "Wrong Header"
            );
        }
        echo json_encode($response);
        die();
    }
    public function suppliers()
    {
        header('Content-Type: application/json; charset=utf-8');
        $response = array();
        $content = $this->component_file->ReadCSVContents($_FILES['i-import']);
        $content = json_decode($content);
        if(strcmp($content->data[0][0],$this->categories_word) == 0 && count($content->data[0]) == $this->categories_length)
        {
            $response = array(
                "data" => $content->data,
                "status" => "success",
                "error" => false,
                "message" => "file content"
            );  
        }
        else
        {
            $response = array(
                "data" => "",
                "status" => "failure",
                "error" => true,
                "message" => "Wrong Header"
            );
        }
        echo json_encode($response);
        die();
    }

    public function paymentmethod()
    {
        header('Content-Type: application/json; charset=utf-8');
        $response = array();
        $content = $this->component_file->ReadCSVContents($_FILES['i-import']);
        $content = json_decode($content);
        if(strcmp($content->data[0][0],$this->paymentmethod_word) == 0 && count($content->data[0]) == $this->paymentmethod_length)
        {
            $response = array(
                "data" => $content->data,
                "status" => "success",
                "error" => false,
                "message" => "file content"
            );  
        }
        else
        {
            $response = array(
                "data" => "",
                "status" => "failure",
                "error" => true,
                "message" => "Wrong Header"
            );
        }
        echo json_encode($response);
        die();
    }
}
