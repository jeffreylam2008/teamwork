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
        $this->products_word = 'item Code';
        $this->products_length = 11;
        $this->categories_word = 'categories code';
        $this->categories_length = 2;
        $this->customers_word = 'customer code';
        $this->customers_length = 30;
        $this->suppliers_word = 'Supplier code';
        $this->suppliers_length = 11;
        $this->paymentmethod_word = 'payment method code';
        $this->paymentmethod_length = 2;
        $this->paymentterm_word = 'payment term code';
        $this->paymentterm_length = 2;
        $this->districts_word = 'District Code';
        $this->districts_length = 4;

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
        // checking file is exist
        if(!isset($_FILES['i-import']))
        {   
            $response = array(
                "query" => "",
                "status" => "failure",
                "error" => true,
                "message" => "File not found!"
            );
            echo json_encode($response);
            die();
        }
        // checking file extension
        $ext = pathinfo($_FILES['i-import']['name'], PATHINFO_EXTENSION);
        if($ext != "csv")
        {
            $response = array(
                "query" => "",
                "status" => "failure",
                "error" => true,
                "message" => "File must be .csv extension!"
            );
            echo json_encode($response);
            die();
        }
        $response = array();
        $content = $this->component_file->ReadCSVContents($_FILES['i-import']);
        $content = json_decode($content);
        // echo json_encode($content);
        // echo trim($content->data[0][0], "\xEF\xBB\xBF");
        switch($type)
        {
            case "products":
                $length = $this->products_length;
                $title_name = $this->products_word;
            break;
            case "categories":
                $length = $this->categories_length;
                $title_name = $this->categories_word;
            break;
            case "customers":
                $length = $this->customers_length;
                $title_name = $this->customers_word;
            break;
            case "suppliers":
                $length = $this->suppliers_length;
                $title_name = $this->suppliers_word;
            break;
            case "paymentmethod":
                $length = $this->paymentmethod_word;
                $title_name = $this->paymentmethod_word;
            break;
            case "paymentterm":
                $length = $this->paymentterm_length;
                $title_name = $this->paymentterm_word;
            break;
            case "districts":
                $length = $this->districts_length;
                $title_name = $this->districts_word;
            break;
        }
        if(count($content[0]) == $length && $content[0][0] === $title_name)
        {
            $response = array(
                "length" => count($content),
                "query" => $content,
                "status" => "success",
                "error" => false,
                "message" => "Checked File - OK!"
            );
        }
        else
        {
            $response = array(
                "query" => "",
                "status" => "failure",
                "error" => true,
                "message" => "Wrong File"
            );
        }
        echo json_encode($response);
        die();
    }
   
  
}
