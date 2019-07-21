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
        $_cur_invoicenum = $this->session->userdata('cur_invoicenum');
        $_transaction = $this->session->userdata('transaction');
        if(isset($_transaction[$_cur_invoicenum]) && !empty($_transaction[$_cur_invoicenum])){
            switch($_option)
            {
                case "preview":
                    // read data from session
                    // echo "<pre>";
                    // var_dump($_tranaction);
                    // echo "</pre>";
                    $this->load->view('invoices/invoices-preview-view', $_transaction[$_cur_invoicenum]);
                break;
                case "save":
                    $this->load->view('invoices/invoices-print-view', $_transaction[$_cur_invoicenum]);
                break;
            }
        }
    }
    public function quotations($_option)
    {
        $_cur_num = $this->session->userdata('cur_quotationnum');
        $_transaction = $this->session->userdata('transaction');
        if(isset($_transaction[$_cur_num]) && !empty($_transaction[$_cur_num])){
            switch($_option)
            {
                case "preview":
                    // read data from session
                    // echo "<pre>";
                    // var_dump($_tranaction);
                    // echo "</pre>";
                    $this->load->view('quotations/quotations-preview-view', $_transaction[$_cur_num]);
                break;
                case "save":
                    $this->load->view('quotations/quotations-print-view', $_transaction[$_cur_num]);
                break;
            }
        }
    }
}