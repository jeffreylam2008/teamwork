<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ThePrint extends CI_Controller 
{
    public function __construct()
	{
        parent::__construct();
    }
    public function invoices($_option)
    {
        //define variable
        $_cur_invoicenum = $this->session->userdata('cur_invoicenum');
        $_transaction = $this->session->userdata('transaction');

        if(isset($_transaction[$_cur_invoicenum]) && !empty($_transaction[$_cur_invoicenum])){
            // read data from session
            // echo "<pre>";
            // var_dump($_transaction[$_cur_invoicenum]);
            // echo "</pre>";
            switch($_option)
            {
                case "preview":
                    $this->load->view('invoices/invoices-print-view', [
                        "data" => $_transaction[$_cur_invoicenum],
                        "preview" => true
                    ]);
                break;
                case "save":
                    $this->load->view('invoices/invoices-print-view', [
                        "data" => $_transaction[$_cur_invoicenum],
                        "preview" => false
                    ]);
                break;
            }
        }
    }
    public function quotations($_option)
    {
        $_cur_num = $this->session->userdata('cur_quotationnum');
        $_transaction = $this->session->userdata('transaction');
       
        if(isset($_transaction[$_cur_num]) && !empty($_transaction[$_cur_num])){
            // read data from session
            // echo "<pre>";
            // var_dump($_transaction[$_cur_num]);
            // echo "</pre>";
            switch($_option)
            {
                case "preview":
                    $this->load->view('quotations/quotations-print-view', [
                        "data" => $_transaction[$_cur_num],
                        "preview" => true
                    ]);
                break;
                case "save":
                    $this->load->view('quotations/quotations-print-view', [
                        "data" => $_transaction[$_cur_num],
                        "preview" => false
                    ]);
                break;
            }
        }
    }
    public function grn($_option)
    {
        
        $_cur_num = $this->session->userdata('cur_grnnum');
        $_transaction = $this->session->userdata('transaction');
        // echo "<pre>";
        // var_dump($_transaction[$_cur_num]);
        // echo "</pre>";
        // // API Call
       
        if(isset($_transaction[$_cur_num]) && !empty($_transaction[$_cur_num])){
            // read data from session
            // echo "<pre>";
            // var_dump($_transaction[$_cur_num]);
            // echo "</pre>";
            switch($_option)
            {
                case "preview":
                    $this->load->view('stocks/goods-recevied-print-view', [
                        "data" => $_transaction[$_cur_num],
                        "preview" => true
                    ]);
                break;
                case "save":
                    $this->load->view('stocks/goods-recevied-print-view', [
                        "data" => $_transaction[$_cur_num],
                        "preview" => false
                    ]);
                break;
            }
        }
    }
    public function dn($_option)
    {
        $_cur_num = $this->session->userdata('cur_dnnum');
        $_transaction = $this->session->userdata('transaction');

        // // API Call
       
        if(isset($_transaction[$_cur_num]) && !empty($_transaction[$_cur_num])){
            // read data from session

            switch($_option)
            {
                case "preview":
                    $this->load->view('stocks/dn/delivery-note-print-view', [
                        "data" => $_transaction[$_cur_num],
                        "preview" => true
                    ]);
                break;
                case "save":
                    $this->load->view('stocks/dn/delivery-note-print-view', [
                        "data" => $_transaction[$_cur_num],
                        "preview" => false
                    ]);
                break;
            }
        }
    }
    public function stocktake($_option)
    {
        $_cur_num = $this->session->userdata('cur_stocktake_num');
        $_transaction = $this->session->userdata('transaction');

        // // API Call
       
        if(isset($_transaction[$_cur_num]) && !empty($_transaction[$_cur_num])){
            // read data from session

            switch($_option)
            {
                case "preview":
                    $this->load->view('stocks/stocks-stocktake-print-view', [
                        "data" => $_transaction[$_cur_num],
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
    public function purchases($_option)
    {
        $_cur_num = $this->session->userdata('cur_purchases_num');
        $_transaction = $this->session->userdata('transaction');

        // // API Call
       
        if(isset($_transaction[$_cur_num]) && !empty($_transaction[$_cur_num]))
        {
            // read data from session
            switch($_option)
            {
                case "preview":
                    $this->load->view('purchases/purchases-print-view', [
                        "data" => $_transaction[$_cur_num],
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