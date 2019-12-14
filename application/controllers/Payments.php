<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_token = "";
	var $_profile = "";
	var $_username = "";
	var $_param = "";
	var $_user_auth = ['create' => false, 'edit' => false, 'delete' => false];
	
	/**
	 * Payment method and term constructor
	 * 
	 *	
	 */
	public function __construct()
	{
		parent::__construct();
		if(!empty($this->session->userdata['login']))
		{
			$this->_token = $this->session->userdata['login']['token'];
			$this->_profile = $this->session->userdata['login']['profile'];
			$this->_username = $this->session->userdata['login']['profile']['username'];
		}
		
		$this->load->library("Component_Login",[$this->_token, "customers"]);

		// check login session
		if(!empty($this->component_login->CheckToken()))
		{
			// fatch master
			$this->component_api->SetConfig("url", $this->config->item('URL_EMPLOYEES').$this->_username);
			$this->component_api->CallGet();
			$_API_EMP = json_decode($this->component_api->GetConfig("result"), true);
			$_API_EMP = $_API_EMP['query'];
			$this->component_api->SetConfig("url", $this->config->item('URL_SHOP').$this->_profile['shopcode']);
			$this->component_api->CallGet();
			$_API_SHOP = json_decode($this->component_api->GetConfig("result"), true);
			$_API_SHOP = $_API_SHOP['query'];
			$this->component_api->SetConfig("url", $this->config->item('URL_MENU_SIDE'));
			$this->component_api->CallGet();
			$_API_MENU = json_decode($this->component_api->GetConfig("result"), true);
			$_API_MENU = $_API_MENU['query'];
			// sidebar session
			$this->_param = $this->router->fetch_class()."/".$this->router->fetch_method();
			
			switch($this->_param)
			{
				case "payments/paymentmethodedit":
					$this->_param = "payments/paymentmethod";
				break;
				case "payments/paymenttermedit":
					$this->_param = "payments/paymentterm";
				break;
				// case "administration/delete":
				// 	$this->_param = "payments/paymentterm";
			}
			
			// header data
			$this->_inv_header_param["topNav"] = [
				"isLogin" => true,
				"username" => $_API_EMP['username'],
				"employee_code" => $_API_EMP['employee_code'],
				"shop_code" => $_API_SHOP['shop_code'],
				"shop_name" => $_API_SHOP['name'],
				"today" => date("Y-m-d")
			];
			// initial Access rule
			$this->_user_auth = ['create' => true, 'edit' => true, 'delete' => true];

			// Call API here
			$this->component_sidemenu->SetConfig("nav_list", $_API_MENU);
			$this->component_sidemenu->SetConfig("active", $this->_param);
			$this->component_sidemenu->Proccess();


			// load header view
			$this->load->view('header',[
				'title'=>'Customers',
				'sideNav_view' => $this->load->view('side-nav', [
					"sideNav"=>$this->component_sidemenu->GetConfig("nav_finished_list"),
					"path"=>$this->component_sidemenu->GetConfig("path"),
					"param"=> $this->_param
				], TRUE), 
				'topNav_view' => $this->load->view('top-nav', [
					"topNav" => $this->_inv_header_param["topNav"]
				], TRUE)
			]);
			
		}
		else
		{
			redirect(base_url("login?url=".urlencode($this->component_login->GetRedirectURL())),"refresh");
		}
	}
	/**
	 * Payment method main page, list of payment methods here
	 * 
	 * @param _page page initial
	 * @return view /payments/payment-method-view
	 */
	public function paymentmethod($_page = 1)
	{
		// variable initial
		$_default_per_page = 50;
		$_API_PAYMENT_METHOD = [];

		// set user data
		$this->session->set_userdata('page',$_page);
		// Uer Auth
		$this->_user_auth = ['create' => true, 'edit' => true, 'delete' => false];

		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_METHOD = json_decode($this->component_api->GetConfig("result"),true);
		$_API_PAYMENT_METHOD = $_API_PAYMENT_METHOD['query'];
		
		// echo "<pre>";
		// var_dump($_API_PAYMENT_METHOD);
		// echo "</pre>";

		// API data usage
		if(!empty($_API_PAYMENT_METHOD))
		{
			// load function bar view
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => $this->_user_auth['create'], "extra" => "data-toggle='modal' data-target='#modal01'"]
				]
			]);

			// load main view
			$this->load->view('/payments/payment-method-view', [
				"edit_url" => base_url("/administration/payments/method/edit/"),
				"del_url" => base_url(""),
				'data' => $_API_PAYMENT_METHOD,
				"user_auth" => $this->_user_auth,
				"default_per_page" => $_default_per_page,
				"page" => $_page
			]);
			$this->load->view("/payments/payment-method-create-view",[
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/administration/payments/method/page/'.$_page), "style" => "", "show" => true],
						["name" => "Reset", "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
					]
				],true),
				"save_url" => base_url("/administration/payments/method/save")
			]);
			$this->load->view('footer');
		}
	}

	/**
	 * Payment method edit page, edit specifc payment method information
	 * 
	 * @param _pm_code payment method ID
	 * @return view /payments/payment-method-edit-view
	 */
	 public function paymentmethodedit($_pm_code)
	 {
		 // API data
		 $this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS').$_pm_code);
		 $this->component_api->CallGet();
		 $_API_PAYMENT_METHOD = json_decode($this->component_api->GetConfig("result"),true);
		 $_API_PAYMENT_METHOD = $_API_PAYMENT_METHOD['query'];
		 // echo "<pre>";
		 // var_dump($_API_PAYMENT_METHOD);
		 // echo "</pre>";
		 $this->load->view('function-bar', [
			 "btn" => [
				 ["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/administration/payments/method'), "style" => "", "show" => true],
				 ["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => $this->_user_auth['edit']]
			 ]
		 ]);
		 $this->load->view('/payments/payment-method-edit-view', [
			 "save_url" => base_url("/administration/payments/method/edit/save/"),
			 'data' => $_API_PAYMENT_METHOD,
		 ]);
		 $this->load->view('footer');
	 }
 
	 /**
	  * Payment method create new confirm page, commit new payment method creation
	  * 
	  */
	 public function paymentmethodsave()
	 {
		 // echo "you are on payment save";
		 // echo "<pre>";
		 // var_dump($_POST);
		 // echo "</pre>";
		 if(isset($_POST) && !empty($_POST) )
		 {
			 $_api_body = json_encode($_POST,true);
 
			 if($_api_body != "")
			 {
				 // echo "<pre>";
				 // var_dump($_api_body);
				 // echo "</pre>";
				 // API data
				 $this->component_api->SetConfig("body", $_api_body);
				 $this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
				 $this->component_api->CallPost();
				 $result = json_decode($this->component_api->GetConfig("result"),true);
				 
				 if(isset($result['error']['message']) || isset($result['error']['code']))
				 {
					 $alert = "danger";
					 switch($result['error']['code'])
					 {
						 case "00000":
							 $alert = "success";
						 break;
					 }		
					 $this->load->view('error-handle', [
						 'message' => $result['error']['message'], 
						 'code'=> $result['error']['code'], 
						 'alertstyle' => $alert
					 ]);
					 
					 // callback initial page
					 header("Refresh: 5; url=".base_url("/administration/payments/method"));
				 }
			 }
		 }	
	 }

	/**
	 * Payment method edit confirm page, confirm change after edit
	 * 
	 * @param _pm_code payment method ID
	 * 
	 */
	public function paymentmethodsaveedit($pm_code = "")
	{
		if(isset($_POST) && !empty($_POST) && isset($pm_code) && !empty($pm_code))
		{
			$_api_body = json_encode($_POST,true);
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";
			if($_api_body != "")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS').$pm_code);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
				if(isset($result['error']['message']) || isset($result['error']['code']))
				{
					$alert = "danger";
					switch($result['error']['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}		
					$this->load->view('error-handle', [
						'message' => $result['error']['message'], 
						'code'=> $result['error']['code'], 
						'alertstyle' => $alert
					]);
					
					// callback initial page
					header("Refresh: 5; url=".base_url("/administration/payments/method"));
				}
			}
		}
	}
	
	/**
	 * Payment term main page, list of payment term here
	 * 
	 * @param _page page initial
	 * @return view /payments/payment-term-view
	 */
	 public function paymentterm($_page = 1)
	 {
		 // variable initial
		 $_default_per_page = 50;
		 $_API_TERM = [];
 
		 // set user data
		 $this->session->set_userdata('page',$_page);
		 $this->_user_auth = ['create' => true, 'edit' => true, 'delete' => false];
 
		 // API data
		 $this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS'));
		 $this->component_api->CallGet();
		 $_API_TERM = json_decode($this->component_api->GetConfig("result"),true);
		 $_API_TERM = $_API_TERM['query'];
 
		 // load function bar view
		 $this->load->view('function-bar', [
			 "btn" => [
				 ["name" => "<i class='fas fa-plus-circle'></i> New", "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => $this->_user_auth['create'], "extra" => "data-toggle='modal' data-target='#modal01'"]
			 ]
		 ]);
 
		 // load main view
		 $this->load->view('payments/payment-term-view',[
			 "edit_url" => base_url("/administration/payments/term/edit/"),
			 "del_url" => base_url(""),
			 'data' => $_API_TERM,
			 "user_auth" => $this->_user_auth,
			 "default_per_page" => $_default_per_page,
			 "page" => $_page
		 ]);
 
		 $this->load->view("/payments/payment-term-create-view",[
			 "function_bar" => $this->load->view('function-bar', [
				 "btn" => [
					 ["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/administration/payments/term/page/'.$_page), "style" => "", "show" => true],
					 ["name" => "Reset", "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
					 ["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
				 ]
			 ],true),
			 "save_url" => base_url("/administration/payments/term/save")
		 ]);
 
		 // create view
		 $this->load->view('footer');
	 }

	/**
	 * Payment method edit page, edit specifc payment method information
	 * 
	 * @param _pt_code payment method ID
	 * @return view /payments/payment-method-edit-view
	 */
	 public function paymenttermedit($_pt_code)
	 {
		 // API data
		 $this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS').$_pt_code);
		 $this->component_api->CallGet();
		 $_API_PTerm = json_decode($this->component_api->GetConfig("result"),true);
		 $_API_PTerm = $_API_PTerm['query'];
		 // echo "<pre>";
		 // var_dump($_API_PAYMENT_METHOD);
		 // echo "</pre>";
		 $this->load->view('function-bar', [
			 "btn" => [
				 ["name" => "Back", "type"=>"button", "id" => "back", "url"=>base_url('/administration/payments/term'), "style" => "", "show" => true],
				 ["name" => "Save", "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => $this->_user_auth['edit']]
			 ]
		 ]);
		 $this->load->view('/payments/payment-term-edit-view', [
			 "save_url" => base_url("/administration/payments/term/edit/save/"),
			 'data' => $_API_PTerm,
		 ]);
		 $this->load->view('footer');
	 }
	/**
	  * Payment terms create new confirm page, commit new payment term creation
	  * 
	  */
	  public function paymenttermsave()
	  {
		  if(isset($_POST) && !empty($_POST) )
		  {
			  $_api_body = json_encode($_POST,true);
  
			  if($_api_body != "")
			  {

				  // API data
				  $this->component_api->SetConfig("body", $_api_body);
				  $this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS'));
				  $this->component_api->CallPost();
				  $result = json_decode($this->component_api->GetConfig("result"),true);
				  
				  if(isset($result['error']['message']) || isset($result['error']['code']))
				  {
					  $alert = "danger";
					  switch($result['error']['code'])
					  {
						  case "00000":
							  $alert = "success";
						  break;
					  }		
					  $this->load->view('error-handle', [
						  'message' => $result['error']['message'], 
						  'code'=> $result['error']['code'], 
						  'alertstyle' => $alert
					  ]);
					  
					  // callback initial page
					  header("Refresh: 5; url=".base_url("/administration/payments/term"));
				  }
			  }
		  }	
	  }
	 /**
	 * Payment Term edit confirm page, confirm change after edit
	 * 
	 * @param _pt_code payment term ID
	 * 
	 */
	public function paymenttermsaveedit($_pt_code = "")
	{
		if(isset($_POST) && !empty($_POST) && isset($_pt_code) && !empty($_pt_code))
		{
			$_api_body = json_encode($_POST,true);

			if($_api_body != "")
			{
				// API data
				$this->component_api->SetConfig("body", $_api_body);
				$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS').$_pt_code);
				$this->component_api->CallPatch();
				$result = json_decode($this->component_api->GetConfig("result"),true);
				
				if(isset($result['error']['message']) || isset($result['error']['code']))
				{
					$alert = "danger";
					switch($result['error']['code'])
					{
						case "00000":
							$alert = "success";
						break;
					}		
					$this->load->view('error-handle', [
						'message' => $result['error']['message'], 
						'code'=> $result['error']['code'], 
						'alertstyle' => $alert
					]);
					
					// callback initial page
					header("Refresh: 5; url=".base_url("/administration/payments/term"));
				}
			}
		}
	}

}