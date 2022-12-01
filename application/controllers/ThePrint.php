<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ThePrint extends CI_Controller 
{
    public function __construct()
	{
        parent::__construct();
    }
    public function invoices($_option,$_session_id)
    {
        //define variable
        $_data = $this->session->userdata($_session_id);
        if(!empty($_data))
        {
            $_cur_invoicenum = $_data['cur_invoicenum'];
            $_transaction = $_data[$_cur_invoicenum];
            $this->session->keep_flashdata($_session_id);

            if(isset($_transaction) && !empty($_transaction)){
                // read data from session
                // echo "<pre>";
                // var_dump($_transaction[$_cur_invoicenum]);
                // echo "</pre>";
                switch($_option)
                {
                    case "preview":
                        $this->load->view('invoices/invoices-print-view', [
                            "data" => $_transaction,
                            "preview" => true
                        ]);
                    break;
                    case "save":
                        $this->load->view('invoices/invoices-print-view', [
                            "data" => $_transaction,
                            "preview" => false
                        ]);
                    break;
                }
            }
        }
    }
    public function quotations($_option,$_session_id)
    {
        //define variable
        $_data = $this->session->userdata($_session_id);
        if(!empty($_data))
        {
            $_cur_num = $_data['cur_quotationnum'];
            $_transaction = $_data[$_cur_num];
            $this->session->keep_flashdata($_session_id);
        
            if(isset($_transaction) && !empty($_transaction)){
                // read data from session
                // echo "<pre>";
                // var_dump($_transaction[$_cur_num]);
                // echo "</pre>";
                switch($_option)
                {
                    case "preview":
                        $this->load->view('quotations/quotations-print-view', [
                            "data" => $_transaction,
                            "preview" => true
                        ]);
                    break;
                    case "save":
                        $this->load->view('quotations/quotations-print-view', [
                            "data" => $_transaction,
                            "preview" => false
                        ]);
                    break;
                }
            }
        }
    }
    public function grn($_option,$_session_id)
    {
        $_data = $this->session->userdata($_session_id);
        if(!empty($_data))
        {
            $_cur_num = $_data['cur_grnnum'];
            $_transaction = $_data[$_cur_num];
            $this->session->keep_flashdata($_session_id);
            if(isset($_transaction) && !empty($_transaction)){
                // read data from session
                // echo "<pre>";
                // var_dump($_transaction[$_cur_num]);
                // echo "</pre>";
                switch($_option)
                {
                    case "preview":
                        $this->load->view('stocks/grn/goods-recevied-print-view', [
                            "data" => $_transaction,
                            "preview" => true
                        ]);
                    break;
                    case "save":
                        $this->load->view('stocks/grn/goods-recevied-print-view', [
                            "data" => $_transaction,
                            "preview" => false
                        ]);
                    break;
                }
            }
            else{
                echo "Data Not Found!";
            }
        }
    }
    public function dn($_option,$_session_id)
    {
        $_data = $this->session->userdata($_session_id);
        if(!empty($_data))
        {
            $_cur_num = $_data['cur_dnnum'];
            $_transaction = $_data[$_cur_num];
            $this->session->keep_flashdata($_session_id);

            // // API Call
            if(isset($_transaction) && !empty($_transaction)){
                // read data from session

                switch($_option)
                {
                    case "preview":
                        $this->load->view('stocks/dn/delivery-note-print-view', [
                            "data" => $_transaction,
                            "preview" => true
                        ]);
                    break;
                    case "save":
                        $this->load->view('stocks/dn/delivery-note-print-view', [
                            "data" => $_transaction,
                            "preview" => false
                        ]);
                    break;
                }
            }
        }
    }
    public function stocktake($_option)
    {
        $_cur_num = $this->session->userdata('cur_stocktake_num');
        $_transaction = $this->component_transactions->Get($_cur_num);

        // // API Call
       
        if(isset($_transaction) && !empty($_transaction)){
            // read data from session

            switch($_option)
            {
                case "preview":
                    $this->load->view('stocks/stocks-stocktake-print-view', [
                        "data" => $_transaction,
                        "preview" => true
                    ]);
                break;
                case "save":
                    // $this->load->view('stocks/delivery-note-print-view', [
                    //     "data" => $_transaction[$_cur_num],
                    //     "preview" => false
                    // ]);
                break;
            }
        }
    }
    public function purchases($_option,$_session_id)
    {
        $_data = $this->session->userdata($_session_id);
        if(!empty($_data))
        {
            $_cur_num = $_data['cur_purchasesnum'];
            $_transaction = $_data[$_cur_num];
            $this->session->keep_flashdata($_session_id);
            
            // // API Call
            if(isset($_transaction) && !empty($_transaction))
            {
                // read data from session
                switch($_option)
                {
                    case "preview":
                        $this->load->view('purchases/purchases-print-view', [
                            "data" => $_transaction,
                            "preview" => true
                        ]);
                    break;
                    case "save":
                        $this->load->view('purchases/purchases-print-view', [
                            "data" => $_transaction,
                            "preview" => true
                        ]);
                    break;
                }
            }
        }
    }
    // public function adjustment($_option)
    // {
    //     $_cur_num = $this->session->userdata('cur_adj_num');
    //     $_transaction = $this->session->userdata('transaction');

    //     // Append API result to transaction array
       
    //     if(isset($_transaction[$_cur_num]) && !empty($_transaction[$_cur_num])){
    //         // read data from session
    //         switch($_option)
    //         {
    //             case "preview":
    //                 $this->load->view('stocks/stocks-adj-print
    //                 -view', [
    //                     "data" => $_transaction[$_cur_num],
    //                     "preview" => true
    //                 ]);
    //             break;
    //             case "save":
    //                 $this->load->view('stocks/stocks-adj-print-view', [
    //                     "data" => $_transaction[$_cur_num],
    //                     "preview" => false
    //                 ]);
    //             break;
    //         }
    //     }
    // }
}