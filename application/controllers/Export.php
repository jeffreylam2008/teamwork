<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends CI_Controller 
{
    public function __construct()
	{
        parent::__construct();
    }
    public function stocktake()
    {
        $_new_array = [];
        $this->component_api->SetConfig("url", $this->config->item('URL_ITEMS'));
		$this->component_api->CallGet();
		$_API_ITEMS = $this->component_api->GetConfig("result");
        $_API_ITEMS = !empty($_API_ITEMS['query']) ? $_API_ITEMS['query'] : [];
        
        $_new_array[0][0] = "item Code";
        $_new_array[0][1] = "English Name";
        $_new_array[0][2] = "Chinese Name";
        $_new_array[0][3] = "QTY";
        $_new_array[0][4] = "Unit";
        $k = 1;
        foreach($_API_ITEMS as $row)
        {
            $_new_array[$k][0] = $row['item_code'];
            $_new_array[$k][1] = $row['eng_name'];
            $_new_array[$k][2] = $row['chi_name'];
            $_new_array[$k][3] = "";
            $_new_array[$k][4] = $row['unit'];
            $k++;
        }
        
        $this->component_file->Array2CSV($_new_array);
        $this->component_file->DownloadHeaders("export-products-stocktake.csv");
        die();
    }
    public function products()
    {
        $this->component_api->SetConfig("url", $this->config->item('URL_BACKUP')."products/");
		$this->component_api->CallGet();
		$_API = $this->component_api->GetConfig("result");
        $_API = !empty($_API['query']) ? $_API['query'] : [];
        $_new_array = [];
        $_new_array[0][] = "item Code";
        $_new_array[0][] = "English Name";
        $_new_array[0][] = "Chinese Name";
        $_new_array[0][] = "Description";
        $_new_array[0][] = "Price";
        $_new_array[0][] = "Discount";
        $_new_array[0][] = "category";
        $_new_array[0][] = "Unit";
        $k = 1;
        foreach($_API as $row )
        {
            $_new_array[$k][] = $row['item_code'];
            $_new_array[$k][] = $row['eng_name'];
            $_new_array[$k][] = $row['chi_name'];
            $_new_array[$k][] = $row['desc'];
            $_new_array[$k][] = $row['price'];
            $_new_array[$k][] = $row['price_special'];
            $_new_array[$k][] = $row['category'];
            $_new_array[$k][] = $row['unit'];
            $k++;
        }
        $this->component_file->Array2CSV($_new_array);
        $this->component_file->DownloadHeaders("products.csv");
        die();
    }
    public function categories()
    {
        $this->component_api->SetConfig("url", $this->config->item('URL_BACKUP')."categories/");
		$this->component_api->CallGet();
		$_API = $this->component_api->GetConfig("result");
        $_API = !empty($_API['query']) ? $_API['query'] : [];
        $_new_array = [];
        $_new_array[0][] = "categories code";
        $_new_array[0][] = "Description";
        $k = 1;
        foreach($_API as $row )
        {   
            $_new_array[$k][] = $row["cate_code"];
            $_new_array[$k][] = $row["desc"];
            $k++;
        }
        $this->component_file->Array2CSV($_new_array);
        $this->component_file->DownloadHeaders("categories.csv");
        die();
    }
    public function customers()
    {
        $this->component_api->SetConfig("url", $this->config->item('URL_BACKUP')."customers/");
		$this->component_api->CallGet();
		$_API = $this->component_api->GetConfig("result");
        $_API = !empty($_API['query']) ? $_API['query'] : [];
        $_new_array = [];
        $_new_array[0][] = "Customer code";
        $_new_array[0][] = "Mail Address";
        $_new_array[0][] = "Shop Address";
        $_new_array[0][] = "Delivery Address";
        $_new_array[0][] = "Attentant 1";
        $_new_array[0][] = "Phone 1";
        $_new_array[0][] = "Fax 1";
        $_new_array[0][] = "Email 1";
        $_new_array[0][] = "Attentant 2";
        $_new_array[0][] = "Phone 2";
        $_new_array[0][] = "Fax 2";
        $_new_array[0][] = "Email 2";
        $_new_array[0][] = "Statement Remark";
        $_new_array[0][] = "Name";
        $_new_array[0][] = "Payment Method";
        $_new_array[0][] = "Payment Term";
        $_new_array[0][] = "Remark";
        $_new_array[0][] = "District";
        $_new_array[0][] = "Delivery From Time";
        $_new_array[0][] = "Delivery To Time";
        $_new_array[0][] = "Delivery remark";
        $_new_array[0][] = "Status";
        $_new_array[0][] = "Company BR Number";
        $_new_array[0][] = "Company Sign";
        $_new_array[0][] = "Group Name";
        $_new_array[0][] = "Account Attentant";
        $_new_array[0][] = "Account tel";
        $_new_array[0][] = "Account fax";
        $_new_array[0][] = "Account Email";
        $k = 1;
        foreach($_API as $row )
        {   
            $_new_array[$k][] = $row["cust_code"];
            $_new_array[$k][] = $row["mail_addr"];
            $_new_array[$k][] = $row["shop_addr"];
            $_new_array[$k][] = $row["delivery_addr"];
            $_new_array[$k][] = $row["attn_1"];
            $_new_array[$k][] = $row["phone_1"];
            $_new_array[$k][] = $row["fax_1"];
            $_new_array[$k][] = $row["email_1"];
            $_new_array[$k][] = $row["attn_2"];
            $_new_array[$k][] = $row["phone_2"];
            $_new_array[$k][] = $row["fax_2"];
            $_new_array[$k][] = $row["email_2"];
            $_new_array[$k][] = $row["statement_remark"];
            $_new_array[$k][] = $row["name"];
            $_new_array[$k][] = $row["pm_code"];
            $_new_array[$k][] = $row["pt_code"];
            $_new_array[$k][] = $row["remark"];
            $_new_array[$k][] = $row["district_code"];
            $_new_array[$k][] = $row["from_time"];
            $_new_array[$k][] = $row["to_time"];
            $_new_array[$k][] = $row["delivery_remark"];
            $_new_array[$k][] = $row["status"];
            $_new_array[$k][] = $row["company_BR"];
            $_new_array[$k][] = $row["company_sign"];
            $_new_array[$k][] = $row["group_name"];
            $_new_array[$k][] = $row["attn"];
            $_new_array[$k][] = $row["tel"];
            $_new_array[$k][] = $row["fax"];
            $_new_array[$k][] = $row["email"];
            $k++;
        }
        $this->component_file->Array2CSV($_new_array);
        $this->component_file->DownloadHeaders("customers.csv");
        die();
    }
    public function suppliers()
    {
        $this->component_api->SetConfig("url", $this->config->item('URL_BACKUP')."suppliers/");
		$this->component_api->CallGet();
		$_API = $this->component_api->GetConfig("result");
        $_API = !empty($_API['query']) ? $_API['query'] : [];
        $_new_array = [];
        $_new_array[0][] = "Supplier code";
        $_new_array[0][] = "Mail Address";
        $_new_array[0][] = "Attentant";
        $_new_array[0][] = "Phone";
        $_new_array[0][] = "Fax";
        $_new_array[0][] = "Email";
        $_new_array[0][] = "Name";
        $_new_array[0][] = "Payment Method";
        $_new_array[0][] = "Payment Term";
        $_new_array[0][] = "Remark";
        $_new_array[0][] = "Status";
        $k = 1;
        foreach($_API as $row )
        {   
            $_new_array[$k][] = $row["supp_code"];
            $_new_array[$k][] = $row["mail_addr"];
            $_new_array[$k][] = $row["attn_1"];
            $_new_array[$k][] = $row["phone_1"];
            $_new_array[$k][] = $row["fax_1"];
            $_new_array[$k][] = $row["email_1"];
            $_new_array[$k][] = $row["name"];
            $_new_array[$k][] = $row["pm_code"];
            $_new_array[$k][] = $row["pt_code"];
            $_new_array[$k][] = $row["remark"];
            $_new_array[$k][] = $row["status"];
            $k++;
        }
        $this->component_file->Array2CSV($_new_array);
        $this->component_file->DownloadHeaders("suppliers.csv");
        die();
    }
    public function paymentmethod()
    {
        $this->component_api->SetConfig("url", $this->config->item('URL_BACKUP')."paymentmethods/");
		$this->component_api->CallGet();
		$_API = $this->component_api->GetConfig("result");
        $_API = !empty($_API['query']) ? $_API['query'] : [];
        $_new_array = [];
        $_new_array[0][] = "payment method code";
        $_new_array[0][] = "Payment Method";
        $k = 1;
        foreach($_API as $row )
        {   
            $_new_array[$k][] = $row["pm_code"];
            $_new_array[$k][] = $row["payment_method"];
            $k++;
        }
        
        $this->component_file->Array2CSV($_new_array);
        $this->component_file->DownloadHeaders("paymentmethod.csv");
        die();
    }
    public function paymentterm()
    {
        $this->component_api->SetConfig("url", $this->config->item('URL_BACKUP')."paymentterms/");
		$this->component_api->CallGet();
		$_API = $this->component_api->GetConfig("result");
        $_API = !empty($_API['query']) ? $_API['query'] : [];
        $_new_array = [];
        $_new_array[0][] = "payment term code";
        $_new_array[0][] = "Payment Term";
        $k = 1;
        foreach($_API as $row )
        {   
            $_new_array[$k][] = $row["pt_code"];
            $_new_array[$k][] = $row["terms"];
            $k++;
        }
        $this->component_file->Array2CSV($_new_array);
        $this->component_file->DownloadHeaders("paymentterm.csv");
        die();
    }
    
    public function districts()
    {
        $_new_array = [];
        $_new_array[0][] = "District Code";
        $_new_array[0][] = "Chinese name";
        $_new_array[0][] = "English name";
        $this->component_file->Array2CSV($_new_array);
        $this->component_file->DownloadHeaders("districts.csv");
        die();
    }
}
