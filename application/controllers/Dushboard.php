<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dushboard extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		// dummy data
		
		$sampleNavData["topNav"] = [
			"isLogin" => true,
			"username" => "",
			"user_id" => ""
		];

		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
		$this->component_api->CallGet();
		$nav_list = json_decode($this->component_api->GetConfig("result"), true);
		$this->component_sidemenu->SetConfig("nav_list", $nav_list);
		$this->component_sidemenu->SetConfig("active",true);
		$this->component_sidemenu->Proccess();

		// load header view
		$this->load->view('header',[
			'title'=>'Items',
			'sideNav_view' => $this->load->view('side-nav', ["sideNav"=>$this->component_sidemenu->GetConfig("nav_finished_list")], TRUE), 
			'topNav_view' => $this->load->view('top-nav', ["topNav" => $sampleNavData["topNav"]], TRUE) 
		]);
		// load breadcrumb
		$this->load->view('breadcrumb');
		// for($i = 1; $i <= ($this->uri->total_segments()-2); $i++)
		// {
		// 	echo $this->uri->slash_segment($i,"leading");
		// }
		// echo "<br>";
		// echo $this->uri->uri_string();
		
		// $this->component_uri->SetConfig("uri",$this->uri->segment_array());
		// $this->component_uri->Parse();
	}
	public function index()
	{
		$this->load->view('dushboard-view');
		$this->load->view('footer');
	}
}
