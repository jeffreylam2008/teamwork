<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends CI_Controller 
{
    public function __construct()
	{
        parent::__construct();
    }
    public function stocktake()
    {
        $_new_array = [];
        $this->component_api->SetConfig("url", $this->config->item('URL_ITEMS'));
		$this->component_api->CallGet();
		$_API_ITEMS = json_decode($this->component_api->GetConfig("result"), true);
        $_API_ITEMS = !empty($_API_ITEMS['query']) ? $_API_ITEMS['query'] : [];
        
        $_new_array[0][0] = "item Code";
        $_new_array[0][1] = "English Name";
        $_new_array[0][2] = "Chinese Name";
        $_new_array[0][3] = "QTY";
        $_new_array[0][4] = "Unit";
        $k = 1;
        foreach($_API_ITEMS as $row)
        {
            $_new_array[$k][0] = $row['item_code'];
            $_new_array[$k][1] = $row['eng_name'];
            $_new_array[$k][2] = $row['chi_name'];
            $_new_array[$k][3] = "";
            $_new_array[$k][4] = $row['unit'];
            $k++;
        }
        
        $this->component_file->Array2CSV($_new_array);
        $this->component_file->DownloadHeaders("export-products-stocktake.csv");
        die();
    }
}
