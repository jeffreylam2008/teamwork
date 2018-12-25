<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
	var $_inv_header_param = [];
	public function __construct()
	{
		parent::__construct();
		
		// dummy data

		$username = "iamadmin";
		// sidebar session
		// $_param = $this->router->fetch_class()."/".$this->router->fetch_method();
		// switch($_param)
		// {
		// 	case "items/edit":
		// 		$_param = "items/index";
		// 	break;
		// 	case "items/delete":
		// 		$_param = "items/index";
		// 	break;
		// }

		// fatch employee API
		$this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/employee/".$username);
		$this->component_api->CallGet();
		$_employee = json_decode($this->component_api->GetConfig("result"),true);
		//var_dump($_employee);


		//load header view
		$this->load->view('login/login-header-view',[
			'title'=>'Login'
		]);

		
		// $this->_inv_header_param["topNav"] = [
		// 	"isLogin" => true,
		// 	"username" => $username,
		// 	"employee_code" => "110022",
		// 	"shop_code" => "0012",
		// 	"today" => date("Y-m-d")
		// ];


		// fatch side bar API
		// $this->component_api->SetConfig("url", $this->config->item('api_url')."/systems/menu/side");
		// $this->component_api->CallGet();
		// $_nav_list = json_decode($this->component_api->GetConfig("result"), true);
		// $this->component_sidemenu->SetConfig("nav_list", $_nav_list);
		// $this->component_sidemenu->SetConfig("active", $_param);
		// $this->component_sidemenu->Proccess();
		// echo "<pre>";
		// var_dump( $this->component_sidemenu->GetConfig("slug"));
		// echo "</pre>";
		
		
		// load breadcrumb
		// $this->load->view('breadcrumb');
	}

	public function index()
	{
		$this->load->view('login/login-view');
		$this->load->view('footer');
	}
}
