<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_API
{
    private $_config = [
        "url" => "",
        "result" => "",
        "body" => []
    ];
    protected $_CI;

    public function __construct()
	{
        $this->_CI =& get_instance();  
    }
    public function SetConfig($func, $val)
    {
        $this->_config[$func] = $val;
    }
    public function GetConfig($func)
    {
        return $this->_config[$func];
    }
    public function CallGet()
    {
        //$resp = ['query'=>"", "error" => ['code'=> "", "message"=>""], "API_Error" => "", "API_errCode" => ""];
        $alert = "danger";
        if(!empty($this->_config["url"]))
        {
            // echo "<pre>";
            // var_dump($this->_config["url"]);
            // echo "</pre>";
            $curl = curl_init($this->_config["url"]);
            //curl_setopt($ch, CURLOPT_HEADER, 0);
            //curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt_array($curl,[
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_NOSIGNAL => 1,
                CURLOPT_TIMEOUT_MS => $this->_CI->config->item("API_INVOKE_TIMEOUT")
            ]);
            $resp = curl_exec($curl);
            $resp = json_decode($resp,true);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // echo "<pre>";
            // var_dump($code);
            // echo "</pre>";
            $resp['http_code'] = $code;
            // $resp["API_Error"] = curl_error($curl);
            // $resp["API_errCode"] = curl_errno($curl);

            switch($code)
            {
                case 200:
                    if(!isset($resp['query']))
                    {
                        $resp["API_Error"] = "Systems Error: Adnormal Data Response - Data Source Error - ".curl_error($curl);
                        $resp["API_errCode"] = "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                        $this->_CI->load->view('error-handle', [
                            'message'=> $resp["API_Error"], 
                            'code' => $resp["API_errCode"], 
                            'alertstyle' => "success"
                        ]);
                    }
                break;
                case 400:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error";
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code;
                    $resp["error"]["code"] = ""; 
                    $resp["error"]["message"] = "";
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 404:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 405:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 500:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
            }
            curl_close($curl);
            $this->SetConfig("result",$resp);
        }
    }

    public function CallPost()
    {
        //$resp = ['query'=>"", "error" => ['code'=> "", "message"=>""]];
        $alert = "danger";
        if(!empty($this->_config["url"]))
        {
            // echo "<pre>";
            // var_dump($this->_config["url"]);
            // echo "</pre>";
            $curl = curl_init($this->_config["url"]);
            curl_setopt_array($curl, [
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POSTFIELDS => $this->_config["body"],
                CURLOPT_TIMEOUT_MS => $this->_CI->config->item("API_INVOKE_TIMEOUT")
            ]);
            $resp = curl_exec($curl);
            $resp = json_decode($resp,true);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $resp['http_code'] = $code;
            // $resp["API_Error"] = curl_error($curl);
            // $resp["API_errCode"]  = curl_errno($curl);
            switch($code)
            {
                case 200:
                    if(!isset($resp['query']))
                    {
                        $resp["API_Error"] = "Systems Error: Adnormal Data Response - Data Source Error - ".curl_error($curl);
                        $resp["API_errCode"] = "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                        $this->_CI->load->view('error-handle', [
                            'message'=> $resp["API_Error"], 
                            'code' => $resp["API_errCode"], 
                            'alertstyle' => "success"
                        ]);
                    }
                break;
                case 400:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error";
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code;
                    $resp["error"]["code"] = ""; 
                    $resp["error"]["message"] = "";
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 404:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 405:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 500:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
            }
            curl_close($curl);
            $this->SetConfig("result",$resp);
        }
    }
    public function CallPatch()
    {
        // $resp = ['query'=>"", "error" => ['code'=> "", "message"=>""]];
        $alert = "danger";
        if(!empty($this->_config["url"]))
        {
            //  echo "<pre>";
            // var_dump($this->_config["url"]);
            // echo "</pre>";
            $curl = curl_init($this->_config["url"]);
            curl_setopt_array($curl, [
                CURLOPT_CUSTOMREQUEST => "PATCH",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POSTFIELDS => $this->_config["body"],
                CURLOPT_TIMEOUT_MS => $this->_CI->config->item("API_INVOKE_TIMEOUT")
            ]);

            $resp = curl_exec($curl);
            $resp = json_decode($resp,true);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $resp['http_code'] = $code;
            // $resp["API_Error"] = curl_error($curl);
            // $resp["API_errCode"]  = curl_errno($curl);
            switch($code)
            {
                case 200:
                    if(!isset($resp['query']))
                    {
                        $resp["API_Error"] = "Systems Error: Adnormal Data Response - Data Source Error - ".curl_error($curl);
                        $resp["API_errCode"] = "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                        $this->_CI->load->view('error-handle', [
                            'message'=> $resp["API_Error"], 
                            'code' => $resp["API_errCode"], 
                            'alertstyle' => "success"
                        ]);
                    }
                break;
                case 400:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error";
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code;
                    $resp["error"]["code"] = ""; 
                    $resp["error"]["message"] = "";
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 404:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 405:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 500:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
            }
            curl_close($curl);
            $this->SetConfig("result",$resp);
        }
    }
    public function CallDelete()
    {
        // $resp = ['query'=>"", "error" => ['code'=> "", "message"=>""]];
        $alert = "danger";
        if(!empty($this->_config["url"]))
        {
            $curl = curl_init($this->_config["url"]);
            curl_setopt_array($curl, [
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_TIMEOUT_MS => $this->_CI->config->item("API_INVOKE_TIMEOUT")
            ]);

            $resp = curl_exec($curl);
            $resp = json_decode($resp,true);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $resp['http_code'] = $code;
            // $resp["API_Error"] = curl_error($curl);
            // $resp["API_errCode"]  = curl_errno($curl);
            
            switch($code)
            {
                case 200:
                    if(!isset($resp['query']))
                    {
                        $resp["API_Error"] = "Systems Error: Adnormal Data Response - Data Source Error - ".curl_error($curl);
                        $resp["API_errCode"] = "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                        $this->_CI->load->view('error-handle', [
                            'message'=> $resp["API_Error"], 
                            'code' => $resp["API_errCode"], 
                            'alertstyle' => "success"
                        ]);
                    }
                break;
                case 400:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error";
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code;
                    $resp["error"]["code"] = ""; 
                    $resp["error"]["message"] = "";
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 404:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 405:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
                case 500:
                    $resp['query'] = [];
                    $resp["API_Error"] = "Systems Error: Data Source Error - ".curl_error($curl);
                    $resp["API_errCode"] =  "Systems Error: HTTP Code: ".$code." - ".curl_errno($curl);
                    $this->_CI->load->view('error-handle', [
                        'message'=> $resp["API_Error"], 
                        'code' => $resp["API_errCode"],
                        'alertstyle' => "danger"
                    ]);
                break;
            }
            curl_close($curl);
            $this->SetConfig("result",$resp);
        }
    }
}