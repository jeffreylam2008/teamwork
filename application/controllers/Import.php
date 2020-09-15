<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller 
{
    public function __construct()
	{
        parent::__construct();
    }
    public function stocktake()
    {
        header('Content-Type: application/json; charset=utf-8');
        $response = array();

        // try 
        // {
        //     if(!isset($_FILES['i-import']) || is_array($_FILES['i-import'])) 
        //     {
        //         throw new RuntimeException('Invalid parameters.');
        //     }

        //     // Check $_FILES['upfile']['error'] value.
        //     switch ($_FILES['i-import']['error']) {
        //         case UPLOAD_ERR_OK:
        //         break;
        //         case UPLOAD_ERR_NO_FILE:
        //         throw new RuntimeException('No file sent.');
        //         case UPLOAD_ERR_INI_SIZE:
        //         case UPLOAD_ERR_FORM_SIZE:
        //         throw new RuntimeException('Exceeded filesize limit.');
        //         default:
        //         throw new RuntimeException('Unknown errors.');
        //     }
        //     if ($_FILES['i-import']['size'] > 1000000) {
        //         throw new RuntimeException('Exceeded filesize limit.');
        //     }

        

            $result = array();
            $row = 0;
            if (($handle = fopen($_FILES['i-import']['tmp_name'], "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    
                    for ($c=0; $c < $num; $c++) {
                        $result[$row][] =  $data[$c];
                    }
                    $row++;
                }
                fclose($handle);
            }
            if(!empty($result))
            {
                $result = json_encode($result);
            }
        // }
        $response = array(
                "data" =>  $result,
                "status" => "success",
                "error" => false,
                "message" => "File uploaded successfully"
              );
        echo json_encode($response);
        die();
    }
}
