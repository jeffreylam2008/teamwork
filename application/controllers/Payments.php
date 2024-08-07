<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller 
{
	var $_inv_header_param = [];
	var $_default_per_page = "";
	var $_page = "";
	var $_token = "";
	var $_profile = "";
	var $_param = "";
	var $_user_auth = ['create' => false, 'edit' => false, 'delete' => false];
	var $_API_HEADER;
	/**
	 * Payment method and term constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$_query = $this->input->get();
		$this->_user_auth = ['create' => true, 'edit' => true, 'delete' => true];
		$this->_default_per_page = $this->config->item('DEFAULT_PER_PAGE');
		$this->_page = $this->config->item('DEFAULT_FIRST_PAGE');
		if($this->input->get("page"))
		{
			$this->_page = $this->input->get("page");
		}
		if($this->input->get("show"))
		{
			$this->_default_per_page = $this->input->get("show");
		}
		if(!empty($this->session->userdata['login']))
		{
			$this->_token = $this->session->userdata['login']['token'];
			$this->_profile = $this->session->userdata['login']['profile'];
		}
		
		$this->load->library("Component_Login",[$this->_token, "payments/paymentmethod"]);

		// check login session
		if(!empty($this->component_login->CheckToken()))
		{
			// API data
			$this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_HEADER').$this->_profile['username'].'/?lang='.$this->config->item('language'));
			$this->component_api->CallGet();
			$_API_HEADER = $this->component_api->GetConfig("result");
			$this->_API_HEADER = !empty($_API_HEADER['query']) ? $_API_HEADER['query'] : ['employee' => "", 'menu' => "",];

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
				"username" => $this->_API_HEADER['employee']['username'],
				"employee_code" => $this->_API_HEADER['employee']['employee_code'],
				"shop_code" => $this->_API_HEADER['employee']['shop_code'],
				"shop_name" => $this->_API_HEADER['employee']['shop_name'],
				"today" => date("Y-m-d")
			];

			$_query['page'] = htmlspecialchars($this->_page);
			$_query['show'] = htmlspecialchars($this->_default_per_page);
			$_query = $this->component_uri->QueryToString($_query);
			$_login = $this->session->userdata['login'];
			$_login['preference'] = $_query;
			$this->session->set_userdata("login", $_login);

			// Call API here
			$this->component_sidemenu->SetConfig("nav_list", $this->_API_HEADER['menu']);
			$this->component_sidemenu->SetConfig("active", $this->_param);
			$this->component_sidemenu->Proccess();

			// load header view
			$this->load->view('header',[
				'title'=>'Payment',
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
	public function paymentmethod()
	{
		$_modalshow = 0;
		// set create new modal pop up on initial
		if($this->input->get("new") == 1)
		{
			$_modalshow = 1;
		}
		// variable initial
		// Uer Auth
		$this->_user_auth = ['create' => true, 'edit' => true, 'delete' => true];

		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
		$this->component_api->CallGet();
		$_API_PAYMENT_METHOD = $this->component_api->GetConfig("result");
		$_API_PAYMENT_METHOD = !empty($_API_PAYMENT_METHOD['query']) ? $_API_PAYMENT_METHOD['query'] : [];
		
		// echo "<pre>";
		// var_dump($_API_PAYMENT_METHOD);
		// echo "</pre>";

		// API data usage
		if(!empty($_API_PAYMENT_METHOD))
		{
			// load function bar view
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("paymentmethod_new").$this->lang->line("paymentmethod_title"), "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => $this->_user_auth['create'], "extra" => "data-toggle='modal' data-target='#modal01'"]
				]
			]);

			// load main view
			$this->load->view('/payments/payment-method-view', [
				"edit_url" => base_url("/administration/payments/method/edit/"),
				"del_url" => base_url("/administration/payments/method/delete/"),
				'data' => $_API_PAYMENT_METHOD,
				"user_auth" => $this->_user_auth,
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"modalshow" => $_modalshow
			]);
			// load create payment method view
			$this->load->view("/payments/payment-method-create-view",[
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/payments/method'), "style" => "", "show" => true],
						["name" => "<i class='fas fa-undo-alt'></i> ".$this->lang->line("function_clear"), "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
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
	 public function paymentmethodedit($_pm_code = "")
	 {
		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS').$_pm_code);
		$this->component_api->CallGet();
		$_API_PAYMENT_METHOD = $this->component_api->GetConfig("result");
		$_API_PAYMENT_METHOD = !empty($_API_PAYMENT_METHOD['query']) ? $_API_PAYMENT_METHOD['query'] : [];
		// echo "<pre>";
		// var_dump($_API_PAYMENT_METHOD);
		// echo "</pre>";
		if(!empty($_API_PAYMENT_METHOD))
		{
			$_login = $this->session->userdata['login'];
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/payments/method'.$_login['preference']), "style" => "", "show" => true],
					["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => $this->_user_auth['edit']]
				]
			]);
			$this->load->view('/payments/payment-method-edit-view', [
				"save_url" => base_url("/administration/payments/method/edit/save/"),
				'data' => $_API_PAYMENT_METHOD,
			]);
			$this->load->view('footer');
		}
	 }
 
	/**
	 * Payment method edit confirm page, confirm change after edit
	 * 
	 * @param _pm_code payment method ID
	 * 
	 */
	public function paymentmethodsaveedit($_pm_code = "")
	{
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('administration/payments/method/edit/'.$_pm_code), "style" => "", "show" => true],
			]
		]);
		if(isset($_POST) && !empty($_POST) && isset($_pm_code) && !empty($_pm_code))
		{
			$_api_body = json_encode($_POST,true);
			// echo $this->config->item('URL_PAYMENT_METHODS').$_pm_code;
			
			// echo "<pre>";
			// var_dump($_api_body);
			// echo "</pre>";

			// API data
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS').$_pm_code);
			$this->component_api->CallPatch();
			$result = $this->component_api->GetConfig("result");
			// echo "<pre>";
			// var_dump($result);
			// echo "</pre>";

			switch($result["http_code"])
			{
				case 200:
					$alert = "success";
				break;				
				case 201:
					$alert = "success";
				break;
				case 202:
					$alert = "info";
				break;
				case 404:
					$alert = "danger";
				break;
			}
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
		else
		{
			$alert = "danger";
			$result["error"]['code'] = "90000";
			$result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again";
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
	}


	 /**
	  * Payment method create new confirm page, commit new payment method creation
	  * 
	  */
	 public function paymentmethodsave()
	 {
		$alert = "danger";
		 // echo "you are on payment save";
		 // echo "<pre>";
		 // var_dump($_POST);
		 // echo "</pre>";
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('administration/payments/method'), "style" => "", "show" => true],
			]
		]);
		if(isset($_POST) && !empty($_POST) )
		{
			 $_api_body = json_encode($_POST,true);
	
			// API data
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS'));
			$this->component_api->CallPost();
			$result = $this->component_api->GetConfig("result");
			//  echo "<pre>";
			//  var_dump($_api_body);
			//  echo "</pre>";
			switch($result["http_code"])
			{
				case 200:
					$alert = "success";
				break;				
				case 201:
					$alert = "success";
				break;
				case 202:
					$alert = "info";
				break;
				case 404:
					$alert = "danger";
				break;
			}
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
		else
		{
			$alert = "danger";
			$result["error"]['code'] = "90000";
			$result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again";
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}

		
		//header("Refresh: 10; url=".base_url("/administration/payments/method"));
	 }
	
	/**
	 * Payment method delete 
	 * 
	 * @param _pm_code payment method ID
	 */
	public function paymentmethodelete($_pm_code = "")
	{
		$_data = [];
		$_login = $this->session->userdata("login");
		$_comfirm_show = true;
		$this->load->view("payments/payment-del-view",[
			"submit_to" => base_url('/administration/payments/method/delete/confirmed/'.$_pm_code),
			"to_deleted_num" => $_pm_code,
			"confirm_show" => $_comfirm_show,
			"return_url" => base_url('/administration/payments/method')
		]);
	
	}

	/**
	 * Payment method delete confirm
	 *
	 * @param _pm_code payment method ID
	 */
	public function paymentmethodsavedelete($_pm_code = "")
	{
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/payments/method'), "style" => "", "show" => true],
			]
		]);

		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_METHODS').$_pm_code);
		$this->component_api->CallDelete();
		$result = $this->component_api->GetConfig("result");

		if(isset($result['error']['message']) || isset($result['error']['code']))
		{
			switch($result["http_code"])
			{
				case 200:
					$alert = "success";
				break;
				case 404:
					$alert = "danger";
				break;
			}
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
	}

	/**
	 * Payment term main page, list of payment term here
	 * 
	 * @param _page page initial
	 * @return view /payments/payment-term-view
	 */
	public function paymentterm()
	{
		$_modalshow = 0;
		// set create new modal pop up on initial
		if($this->input->get("new") == 1)
		{
			$_modalshow = 1;
		}
		// set user data
		$this->_user_auth = ['create' => true, 'edit' => true, 'delete' => true];

		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS'));
		$this->component_api->CallGet();
		$_API_TERM = $this->component_api->GetConfig("result");
		$_API_TERM = !empty($_API_TERM['query']) ? $_API_TERM['query'] : [];
	
		if(!empty($_API_TERM))
		{
			
			// load function bar view
			$this->load->view('function-bar', [
				"btn" => [
					["name" => "<i class='fas fa-plus-circle'></i> ".$this->lang->line("function_new"), "type"=>"button", "id" => "newitem", "url"=>"#", "style" => "", "show" => $this->_user_auth['create'], "extra" => "data-toggle='modal' data-target='#modal01'"]
				]
			]);

			// load main view
			$this->load->view('payments/payment-term-view',[
				"edit_url" => base_url("/administration/payments/term/edit/"),
				"del_url" => base_url("/administration/payments/term/delete/"),
				'data' => $_API_TERM,
				"user_auth" => $this->_user_auth,
				"default_per_page" => $this->_default_per_page,
				"page" => $this->_page,
				"modalshow" => $_modalshow
			]);

			$this->load->view("/payments/payment-term-create-view",[
				"function_bar" => $this->load->view('function-bar', [
					"btn" => [
						["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/payments/term'), "style" => "", "show" => true],
						["name" => "<i class='fas fa-undo-alt'></i> ".$this->lang->line("function_clear"), "type"=>"button", "id" => "reset", "url" => "#" , "style" => "btn btn-outline-secondary", "show" => true],
						["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => true]
					]
				],true),
				"save_url" => base_url("/administration/payments/term/save")
			]);

			// create view
			$this->load->view('footer');
		}
	}

	/**
	 * Payment terms create new confirm page, commit new payment term creation
	 * 
	 */
	public function paymenttermsave()
	{
		$alert = "danger";
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('administration/payments/term'), "style" => "", "show" => true],
			]
		]);
		if(isset($_POST) && !empty($_POST) )
		{
			$_api_body = json_encode($_POST,true);

			// API data
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS'));
			$this->component_api->CallPost();
			$result = $this->component_api->GetConfig("result");
			switch($result["http_code"])
			{
				case 200:
					$alert = "success";
				break;				
				case 201:
					$alert = "success";
				break;
				case 202:
					$alert = "info";
				break;
				case 404:
					$alert = "danger";
				break;
			}
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
		else
		{
			$alert = "danger";
			$result["error"]['code'] = "90000";
			$result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again";
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
	}

	/**
	 * Payment method edit page, edit specifc payment method information
	 * 
	 * @param _pt_code payment method ID
	 * @return view /payments/payment-method-edit-view
	 */
	public function paymenttermedit($_pt_code = "")
	{
	   // API data
	   $this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS').$_pt_code);
	   $this->component_api->CallGet();
	   $_API_PTerm = $this->component_api->GetConfig("result");
	   $_API_PTerm = !empty($_API_PTerm['query']) ? $_API_PTerm['query'] : [];
	   // echo "<pre>";
	   // var_dump($_API_PAYMENT_METHOD);
	   // echo "</pre>";
	   if(!empty( $_API_PTerm))
	   {
		   $_login = $this->session->userdata['login'];
		   $this->load->view('function-bar', [
			   "btn" => [
				   ["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/payments/term'.$_login['preference']), "style" => "", "show" => true],
				   ["name" => "<i class='far fa-save'></i> ".$this->lang->line("function_save"), "type"=>"button", "id" => "save", "url"=>"#", "style" => "btn btn-primary", "show" => $this->_user_auth['edit']]
			   ]
		   ]);
		   $this->load->view('/payments/payment-term-edit-view', [
			   "save_url" => base_url("/administration/payments/term/edit/save/"),
			   'data' => $_API_PTerm
		   ]);
		   $this->load->view('footer');
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
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "Back", "url"=> base_url('administration/payments/term/edit/'.$_pt_code), "style" => "", "show" => true],
			]
		]);
		if(isset($_POST) && !empty($_POST) && isset($_pt_code) && !empty($_pt_code))
		{
			$_api_body = json_encode($_POST,true);
			// echo $_api_body;
			// API data
			$this->component_api->SetConfig("body", $_api_body);
			$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS').$_pt_code);
			$this->component_api->CallPatch();
			$result = $this->component_api->GetConfig("result");
			switch($result["http_code"])
			{
				case 200:
					$alert = "success";
				break;				
				case 201:
					$alert = "success";
				break;
				case 202:
					$alert = "info";
				break;
				case 404:
					$alert = "danger";
				break;
			}
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
		else
		{
			$alert = "danger";
			$result["error"]['code'] = "90000";
			$result["error"]['message'] = "Data Problem - input data missing or crashed! Please try create again";
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}

	}

/**
	 * Payment term delete 
	 * 
	 * @param _pt_code payment method ID
	 */
	public function paymenttermdelete($_pt_code = "")
	{
		$_data = [];
		$_login = $this->session->userdata("login");
		$_comfirm_show = true;
		$this->load->view("payments/payment-del-view",[
			"submit_to" => base_url('/administration/payments/term/delete/confirmed/'.$_pt_code),
			"to_deleted_num" => $_pt_code,
			"confirm_show" => $_comfirm_show,
			"return_url" => base_url('/administration/payments/term')
		]);
	
	}

	/**
	 * Payment term delete confirm
	 *
	 * @param _pt_code payment method ID
	 */
	public function paymenttermsavedelete($_pt_code = "")
	{
		$this->load->view('function-bar', [
			"btn" => [
				["name" => "<i class='fas fa-chevron-left'></i> ".$this->lang->line("function_back"), "type"=>"button", "id" => "back", "url"=>base_url('/administration/payments/term'), "style" => "", "show" => true],
			]
		]);

		// API data
		$this->component_api->SetConfig("url", $this->config->item('URL_PAYMENT_TERMS').$_pt_code);
		$this->component_api->CallDelete();
		$result = $this->component_api->GetConfig("result");

		if(isset($result['error']['message']) || isset($result['error']['code']))
		{
			switch($result["http_code"])
			{
				case 200:
					$alert = "success";
				break;
				case 404:
					$alert = "danger";
				break;
			}
			$this->load->view('error-handle', [
				'message' => $result["error"]['message'], 
				'code'=> $result["error"]['code'], 
				'alertstyle' => $alert
			]);
		}
	}


}