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
        echo base_url().uri_string();
    }
    public function create()
    {
        echo "<h1> create </h1>";
        echo base_url().uri_string();
        echo "<br>";

        echo "<a href='".$this->input->get("b_url")."'>back</a>";
    }
    public function edit()
    {
        echo "<h1> edit </h1>";
        echo base_url().uri_string();
        echo "<br>";
        echo "<a href='".base_url()."TestBed/create?b_url=".base_url().uri_string()."'>create</a>";
    }

}