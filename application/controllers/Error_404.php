<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error_404 extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		// dummy data
		$sampleNavData["sideNav"] = [
			["name" => "Dushboard", "isActive" => "", "slug"=>"dushboard"],
			["name" => "Items","isActive" => "active", "slug"=>"items"],
			["name" => "Messages","isActive" => "", "slug"=>"Messages"],
			["name" => "Settings","isActive" => "", "slug"=>"Setting"]
		];
		$sampleNavData["topNav"] = [
			"isLogin" => true
		];
		// load header view
		$this->load->view('header',[
			'title'=>'items',
			'sideNav_view' => "", 
			'topNav_view' => $this->load->view('top-nav', $sampleNavData, TRUE) 
		]);
		
	}
	public function index()
	{
        $this->output->set_status_header('404');
        $this->load->view('error');
        $this->load->view('footer');
	}

}
