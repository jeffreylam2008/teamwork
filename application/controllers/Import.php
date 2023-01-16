<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller 
{
    public $products_word = "";
    public $products_length = 0;
    public $categories_word = "";
    public $categories_length = 0;
    public $customers_word = "";
    public $customers_length = 0;
    public $suppliers_word = "";
    public $suppliers_length = 0;
    public $paymentmethod_word = "";
    public $paymentmethod_length = 0;
    public $paymentterm_word = "";
    public $paymentterm_length = 0;

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

        if(isset($_FILES['i-import']))
        {
            $response = array();
            $content = $this->component_file->ReadCSVContents($_FILES['i-import']);
            $content = json_decode($content);
            // echo trim($content->data[0][0], "\xEF\xBB\xBF");
            if($type == "products" && strcmp(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $content->data[0][0]),$this->products_word) == 0 && count($content->data[0]) == $this->products_length )
            {
                $response = array(
                    "length" => count($content->data),
                    "status" => "success",
                    "error" => false,
                    "message" => "Checked Header - OK!"
                );
            }
           
            // echo json_encode( strcmp(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $content->data[0][0]),$this->products_word)) ;
            
            // elseif($type == "categories" && strcmp($content->data[0][0],$this->categories_word) == 0 && count($content->data[0]) == $this->categories_length)
            // {
            //     $response = array(
            //         "length" => count($content->data),
            //         "status" => "success",
            //         "error" => false,
            //         "message" => "Checked Header - OK!"
            //     );  
            // }
            // elseif($type == "customers" && strcmp($content->data[0][0],$this->customers_word) == 0 && count($content->data[0]) == $this->customers_length)
            // {
            //     $response = array(
            //         "length" => count($content->data),
            //         "status" => "success",
            //         "error" => false,
            //         "message" => "Checked Header - OK!"
            //     );  
            // }
            // elseif($type == "suppliers" && strcmp($content->data[0][0],$this->suppliers_word) == 0 && count($content->data[0]) == $this->suppliers_length)
            // {
            //     $response = array(
            //         "length" => count($content->data),
            //         "status" => "success",
            //         "error" => false,
            //         "message" => "Checked Header - OK!"
            //     );  
            // }
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
        }
        die();
    }
    public function products()
    {
        header('Content-Type: application/json; charset=utf-8');
        $response = array();
        $content = $this->component_file->ReadCSVContents($_FILES['i-import']);
        $content = json_decode($content);
       
        // if(count($content->data[0]) == $this->products_length)
        // {
            
        //     // do import here
        //     // fatch items API
            $this->component_api->SetConfig("body", json_encode($content->data));
            $this->component_api->SetConfig("url", $this->config->item('URL_SYS_RESTORE')."products/");
            $this->component_api->CallPost();
            $result = $this->component_api->GetConfig("result");
            echo json_encode($result);
        //     // respone result
        //     $response = array(
        //         "data" => $content->data,
        //         "status" => "success",
        //         "error" => false,
        //         "message" => "file content"
        //     );
        // }
        // else
        // {
        //     $response = array(
        //         "data" => "",
        //         "status" => "failure",
        //         "error" => true,
        //         "message" => "Wrong Header"
        //     );
        // }
        
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
