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
                //session_regenerate_id();
                $session = uniqid();
                $this->component_api->SetConfig("url", $this->config->item('URL_INVOICES_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
                // $this->session->set_flashdata('cur_invoicenum', $_API_NEXT);
                // header("Refresh: 0; url='".base_url("/invoices/create/".$session."/".$_API_NEXT)."'");
                redirect(base_url("/invoices/create/".$session."/".$_API_NEXT),"refresh");
                break;

            case "edit":
                //session_regenerate_id();
                $session = uniqid();
                redirect(base_url("/invoices/edit/".$session."/".$param),"refresh");
                break;
            case "copy":
                //session_regenerate_id();
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
                redirect(base_url("/invoices/create/".$session."/".$_API_NEXT),"refresh");
                break;

            case "discard":
                $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTION_DISCARD').$param);
                $this->component_api->CallDelete();
                $this->component_api->GetConfig("result");
                // Remove session content from session
                $this->session->unset_userdata($param);
                redirect(base_url("/invoices/list"),"refresh");
                break;
        }
    }
    public function quotations($option = "", $param = "")
    {
        switch($option)
        {
            case "create":
                //session_regenerate_id();
                $session = uniqid();
                $this->component_api->SetConfig("url", $this->config->item('URL_QUOTATIONS_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
                // $this->session->set_userdata('cur_quotationnum', $_API_NEXT);
                redirect(base_url("quotations/create/".$session."/".$_API_NEXT),"auto");
                break;
        
            case "edit":
                //session_regenerate_id();
                $session = uniqid();
                redirect(base_url("quotations/edit/".$session."/".$param),"auto");
                break;
            
            case "copy":
                //session_regenerate_id();
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
            case "convert":
                //session_regenerate_id();
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
                    // Set new transaction to session
                    $this->session->set_tempdata($session, $_transaction, 600);
                    // Remove quotation session
                    $this->session->unset_userdata($param);
                }
                redirect(base_url("/invoices/create/".$session."/".$_API_NEXT."/".$_cur_quotationnum),"auto");
                break;
            case "discard":
                $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTION_DISCARD').$param);
                $this->component_api->CallDelete();
                $this->component_api->GetConfig("result");
                // Remove session content from session
                $this->session->unset_userdata($param);
                redirect(base_url("/quotations/list"),"auto");
                break;
        }
    }
    public function dn($option = "", $param = "")
    {
        switch($option)
        {
            case "create":
                //session_regenerate_id();
                $session = uniqid();
                $this->component_api->SetConfig("url", $this->config->item('URL_DELIVERY_NOTE_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
                redirect(base_url("/stocks/dn/create/".$session."/".$_API_NEXT),"auto");
                break;
            case "edit":
                //session_regenerate_id();
                $session = uniqid();
                redirect(base_url("/stocks/dn/edit/".$session."/".$param),"auto");
                break;
            case "discard":
                $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTION_DISCARD').$param);
                $this->component_api->CallDelete();
                $this->component_api->GetConfig("result");
                // Remove session content from session
                $this->session->unset_userdata($param);
                redirect(base_url("/stocks"),"auto");
                break;
        }
    }
    public function adjustments($option = "", $param = "")
    {
        switch($option)
        {
            case "create":
                $session = uniqid();
                $this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ADJ_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
                // echo "<pre>";
                // var_dump($_API_NEXT);
                // echo "</pre>";
                redirect(base_url("/stocks/adj/create/".$session."/".$_API_NEXT."/".$param),"auto");
                break;
            case "edit":
                $session = uniqid();
                redirect(base_url("/stocks/adj/edit/".$session."/".$param),"auto");
                break;    
            case "discard":
                $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTION_DISCARD').$param);
                $this->component_api->CallDelete();
                $this->component_api->GetConfig("result");
                // Remove session content from session
                $this->session->unset_userdata($param);
                redirect(base_url("/stocks"),"auto");
                break;
        }
    }
    public function grn($option = "", $param = "")
    {
        switch($option)
        {
            case "create":
                //session_regenerate_id();
                $session = uniqid();
                $this->component_api->SetConfig("url", $this->config->item('URL_PO_GRN_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
                redirect(base_url("/stocks/grn/create/".$session."/".$_API_NEXT."/".$param),"auto");
                break;
            case "edit":
                $session = uniqid();
                redirect(base_url("/stocks/grn/edit/".$session."/".$param),"auto");
                break;
            case "discard":
                $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTION_DISCARD').$param);
                $this->component_api->CallDelete();
                $this->component_api->GetConfig("result");
                // Remove session content from session
                $this->session->unset_userdata($param);
                redirect(base_url("/stocks"),"auto");
                break;
        }
    }
    public function stocktake($option = "", $param = "")
    {
        switch($option)
        {
            case "create":
                $session = uniqid();		
                $this->component_api->SetConfig("url", $this->config->item('URL_STOCK_ST_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
                redirect(base_url("/stocks/stocktake/create/".$session."/".$_API_NEXT),"auto");
                break;
            // where param is stocktake ID
            case "edit":
                $session = uniqid();
                redirect(base_url("/stocks/stocktake/edit/".$session."/".$param),"auto");
                break;
            // discard session 
            case "discard":
                $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTION_DISCARD').$param);
                $this->component_api->CallDelete();
                $this->component_api->GetConfig("result");
                // Remove session content from session
                $this->session->unset_userdata($param);
                redirect(base_url("/stocks"),"auto");
                break;
            // case "adjust":

            //     break;
        }
    }
    public function purchases($option = "", $param = "")
    {
        switch($option)
        {
            case "create":
                //session_regenerate_id();
                $session = uniqid();
                $this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
                redirect(base_url("/purchases/order/create/".$session."/".$_API_NEXT),"auto");
                break;
            case "edit":
                //session_regenerate_id();
                $session = uniqid();
                redirect(base_url("/purchases/order/edit/".$session."/".$param),"auto");
                break;
            case "copy":
                //session_regenerate_id();
                $session = uniqid();
                $_transaction = [];
                // get next Invoice number
                $this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";

                // retrieve transaction info base on unique ID
                $_data = $this->session->userdata($param);
                // retreve current quotation number
                $_cur_purchasesnum = $_data["cur_purchasesnum"];
                $_transaction = $_data[$_cur_purchasesnum];
                // change transaction date
                $_transaction['date'] = date("Y-m-d H:i:s");
                // set old transaction to new tranaction
                $_new_data[$_API_NEXT] = $_transaction;

                $this->session->set_flashdata($session,$_new_data);
                redirect(base_url("/purchases/order/create/".$session."/".$_API_NEXT),"auto");
                break;
            case "togrn":
                //session_regenerate_id();
                $session = uniqid();
                $_transaction = [];
                $this->component_api->SetConfig("url", $this->config->item('URL_PO_GRN_NEXT_NUM').$session);
                $this->component_api->CallGet();
                $_API_NEXT = $this->component_api->GetConfig("result");
                $_API_NEXT = !empty($_API_NEXT['query']) ? $_API_NEXT['query'] : "";
                if(!empty($_API_NEXT))
                {
                    $this->component_api->SetConfig("url", $this->config->item('URL_PURCHASES_ORDER_GRN').$param);
                    $this->component_api->CallGet();
                    $_API_GET_GRN = $this->component_api->GetConfig("result");
                    // echo "<pre>";
                    // var_dump($_API_GET_GRN);
                    // echo "</pre>";
                    $_API_GET_GRN = !empty($_API_GET_GRN['query']) ? $_API_GET_GRN['query'] : "";
                    
                    if(!empty($_API_GET_GRN)){
                        $_transaction = $_API_GET_GRN;
                        $_transaction['grn_num'] = $_API_NEXT;
                        $_transaction['po_num'] = $param;
                       
                        $_sess[$_API_NEXT] = $_transaction;
                        $_sess['cur_grnnum'] = $_API_NEXT;

                        $this->session->set_tempdata($session, $_sess, 600);
                        // echo "<pre>";
                        // var_dump($_sess);
                        // echo "</pre>";
                        // send settlement 
                        if($_API_GET_GRN['settlement'])
                        {
                            redirect(base_url("/purchases/order/settlement/".$session."/".$param),"auto");
                        }
                        else
                        {
                            redirect(base_url("/stocks/grn/create/".$session."/".$_API_NEXT."/".$param),"auto");
                        } 
                    }
                }
                
                break;
            case "discard":
                $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTION_DISCARD').$param);
                $this->component_api->CallDelete();
                $this->component_api->GetConfig("result");
                // Remove session content from session
                $this->session->unset_userdata($param);
                redirect(base_url("/purchases/order"),"auto");
                break;
        }
    }
    public function warehouse($option = "", $param = "")
    {
        $_num = "";
        switch($option)
        {
            case "view":
                // Call API
                // prefix, refer_code, 
                $this->component_api->SetConfig("url", $this->config->item('URL_TRANSACTIONS').$param);
                $this->component_api->CallGet();
                $_API_TRANSACTIONS = $this->component_api->GetConfig("result");
                $_API_TRANSACTIONS = !empty($_API_TRANSACTIONS['query']) ? $_API_TRANSACTIONS['query'] : "";
                if(!empty($_API_TRANSACTIONS)){
                    $_num = $_API_TRANSACTIONS['trans_code'];
                    switch($_API_TRANSACTIONS['prefix'])
                    {
                        case "DN":
                            $this->dn("edit",$_num);
                            break;
                        case "GRN":
                            $this->grn("edit",$_num);
                            break;
                        case "INV":
                            $this->invoices("edit", $_num);
                            break;
                        case "PO":
                            $this->purchases("edit",$_num);
                            break;
                        case "ADJ":
                            $this->adjustments("edit",$_num);
                            break;
                        case "ST":
                            $this->stocktake("edit",$_num);
                            break;
                    }
                }
                
                break;
        }
    }

}