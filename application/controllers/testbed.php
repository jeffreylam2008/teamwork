<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestBed extends CI_Controller 
{
	public function __construct()
	{
        parent::__construct();
    }
    public function index()
    {
        $export_data = array(
            array(
                      'Product' => 'Product 1',
                      'Valid From' => '2016-04-06 19:02:00',
                      'Valid Till' => '2016-04-07 19:04:00',
                      'Views' => 17,
                      'Quantity Request' => '酒精清毒酒精清毒',
                      'Number of Accepted Requests' => 0,
                      'Number of Rejected Requests' => 4,
                      'Vendor' => 1,
                      'System' => 3
                  ),
            array(
                      'Product' => 'Product 2',
                      'Valid From' => '2016-04-08 19:00:00',
                      'Valid Till' => '2016-04-15 19:00:00',
                      'Views' => 19,
                      'Quantity Request' => '揭門式洗碗碟機',
                      'Number of Accepted Requests' => 1,
                      'Number of Rejected Requests' => 0,
                      'Vendor' => 1,
                      'System' => 0
                  ),
            array(
                      'Product' => 'Product 3',
                      'Valid From' => '2016-04-08 15:30:00',
                      'Valid Till' => '2016-04-12 04:30:00',
                      'Views' => 6,
                      'Quantity Request' => '雙頭清潔劑儀器',
                      'Number of Accepted Requests' => 1,
                      'Number of Rejected Requests' => 0,
                      'Vendor' => 1,
                      'System' => 0
                  )
          );


          $fileName = "export_data" . rand(1,100) . ".xls";

        if ($export_data) {
            function filterData(&$str) {
                $str = preg_replace("/\t/", "\\t", $str);
                $str = preg_replace("/\r?\n/", "\\n", $str);
                if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
            }

            // headers for download
            header('Content-Encoding: UTF-8');
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header("Content-Type: application/vnd.ms-excel; charset=utf-8");

            $flag = false;
            foreach($export_data as $row) {
                if(!$flag) {
                    // display column names as first row
                    echo implode("\t", array_keys($row)) . "\n";
                    $flag = true;
                }
                // filter data
                array_walk($row, 'filterData');
                echo implode("\t", array_values($row)) . "\n";
            }
            exit;            
        }
    }
    

}