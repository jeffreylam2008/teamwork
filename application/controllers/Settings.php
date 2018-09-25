<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		// dummy data
		$sampleNavData["sideNav"] = [
			["name" => "Dushboard", "isActive" => "", "slug"=>"dushboard"],
			["name" => "Items","isActive" => "active", "slug"=>"items"],
			["name" => "Categories","isActive" => "", "slug"=>"categories"],
			["name" => "Settings","isActive" => "", "slug"=>"settings"]
		];
		$sampleNavData["topNav"] = [
			"isLogin" => true
		];

		// render the view
		$this->load->view('header',[
			'title'=>'Settings', 
			'sideNav_view' => $this->load->view('side-nav', $sampleNavData, TRUE), 
			'topNav_view' => $this->load->view('top-nav', $sampleNavData, TRUE) 
		]);
	}
	public function index()
	{
		
	}
}
