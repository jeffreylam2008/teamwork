<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Router extends CI_Controller 
{
    public function __construct()
	{
		parent::__construct();
    } 
    public function invoices($option = "", $param = "")
    {
        switch($option)
        {
            case "create":
                session_regenerate_id();
                $session = uniqid();
                $this->component_api->SetConfig("url", $this->config->item('URL_INVOICES_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
                // $this->session->set_flashdata('cur_invoicenum', $_API_NEXT);
                redirect(base_url("invoices/create/".$session."/".$_API_NEXT),"auto");
                break;

            case "edit":
                session_regenerate_id();
                $session = uniqid();
                redirect(base_url("invoices/edit/".$session."/".$param),"auto");
                break;

            case "copy":
                session_regenerate_id();
                $session = uniqid();
                $_transaction = [];
                // get next Invoice number
                $this->component_api->SetConfig("url", $this->config->item('URL_INVOICES_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";

                // echo "<pre>";
                // var_dump($_SESSION);
                // echo "</pre>";
                // retrieve transaction info base on unique ID
                $_data = $this->session->userdata($param);
                // retreve current inovice number
                $cur_invoicenum = $_data["cur_invoicenum"];
                // retreve inovice from session
                $_transaction = $_data[$cur_invoicenum];
                // change transaction date
                $_transaction['date'] = date("Y-m-d H:i:s");
                // set old transaction to new tranaction
                $_new_data[$_API_NEXT] = $_transaction;
                //session_regenerate_id();

                $this->session->set_flashdata($session,$_new_data);
                redirect(base_url("invoices/create/".$session."/".$_API_NEXT),"auto");
                break;

            case "convert":
                $session = uniqid();
                $_transaction = [];
                $this->component_api->SetConfig("url", $this->config->item('URL_INVOICES_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";

                if(!empty($param))
                {
                    // retrieve transaction info base on unique ID
                    $_data = $this->session->userdata($param);
                    // retreve current inovice number
                    $_cur_quotationnum = $_data["cur_quotationnum"];
                    $_transaction[$_API_NEXT] = $_data[$_cur_quotationnum];
                    $_transaction[$_API_NEXT]['date'] = date("Y-m-d H:i:s");

                    $this->session->set_flashdata($param,$_transaction);
                    
                    unset($_SESSION[$param][$_cur_quotationnum]);
                }
                redirect(base_url("invoices/create/".$session."/".$_API_NEXT."/".$_cur_quotationnum),"auto");
                break;

            case "discard":
                $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTION_DISCARD').$param);
                $this->component_api->CallDelete();
                $this->component_api->GetConfig("result");
                redirect(base_url("invoices/list"),"auto");
                break;
        }
    }
    public function quotations($option = "", $param = "")
    {
        switch($option)
        {
            case "create":
                session_regenerate_id();
                $session = uniqid();
                $this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
                // $this->session->set_userdata('cur_quotationnum', $_API_NEXT);
                
                redirect(base_url("quotations/create/".$session."/".$_API_NEXT),"auto");
                break;
        
            case "edit":
                session_regenerate_id();
                $session = uniqid();
                redirect(base_url("quotations/edit/".$session."/".$param),"auto");
                break;
            
            case "copy":
                session_regenerate_id();
                $session = uniqid();
                $_transaction = [];
                // get next Invoice number
                $this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";

                // retrieve transaction info base on unique ID
                $_data = $this->session->userdata($param);
                // retreve current quotation number
                $_cur_quotationnum = $_data["cur_quotationnum"];
                $_transaction = $_data[$_cur_quotationnum];
                // change transaction date
                $_transaction['date'] = date("Y-m-d H:i:s");
                // set old transaction to new tranaction
                $_new_data[$_API_NEXT] = $_transaction;

                $this->session->set_flashdata($session,$_new_data);
                redirect(base_url("quotations/create/".$session."/".$_API_NEXT),"auto");
                break;
            
            case "discard":
                $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTION_DISCARD').$param);
                $this->component_api->CallDelete();
                $this->component_api->GetConfig("result");
                redirect(base_url("quotations/list"),"auto");
                break;
        }
    }
    public function dn($option = "", $param = "")
    {
        switch($option)
        {
            case "create":
                session_regenerate_id();
                $session = uniqid();
                $this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
                redirect(base_url("stocks/dn/create/".$session."/".$_API_NEXT),"auto");
                break;
            case "discard":
                $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTION_DISCARD').$param);
                $this->component_api->CallDelete();
                $this->component_api->GetConfig("result");
                redirect(base_url("stocks/"),"auto");
                break;
            case "edit":
        }
    }
}