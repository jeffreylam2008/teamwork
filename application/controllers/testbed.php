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
        echo $folder = $_SERVER['DOCUMENT_ROOT']."/webapp/logs";
        error_log("error msg --\r\n", 3, $folder."/LOG.txt");
    }

}