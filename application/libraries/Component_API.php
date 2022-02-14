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
        $resp = ['query'=>"", "error" => ['code'=> "", "message"=>""]];
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
            $resp['http_code'] = $code;
            $resp["API_Error"] = curl_error($curl);
            $resp["API_errCode"]  = curl_errno($curl);
            curl_close($curl);
            // echo "<pre>";
            // var_dump($resp);
            // echo "</pre>";
            switch($code)
            {
                case 200:
                    
                break;
                case 404:
                    $this->_CI->load->view('error-handle', [
                        'message'=> "Systems Error: Data Source Error - ".$resp["API_Error"], 
                        'code' => "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
                case 405:
                    $this->_CI->load->view('error-handle', [
                        'message'=> "Systems Error: Data Source Error - ".$resp["API_Error"], 
                        'code' => "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
                case 500:
                    $this->_CI->load->view('error-handle', [
                        'message' => "Systems Error: Server Error - ".$resp["API_Error"],
                        'code'=> "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
            }
            $this->SetConfig("result",$resp);
        }
    }

    public function CallPost()
    {
        $resp = ['query'=>"", "error" => ['code'=> "", "message"=>""]];
        $alert = "danger";
        if(!empty($this->_config["url"]))
        {
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
            $resp["API_Error"] = curl_error($curl);
            $resp["API_errCode"]  = curl_errno($curl);
            curl_close($curl);
            switch($code)
            {
                case 200:
                    
                break;
                case 404:
                    $this->_CI->load->view('error-handle', [
                        'message'=> "Systems Error: Data Source Error - ".$resp["API_Error"], 
                        'code' => "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
                case 405:
                    $this->_CI->load->view('error-handle', [
                        'message'=> "Systems Error: Data Source Error - ".$resp["API_Error"], 
                        'code' => "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
                case 500:
                    $this->_CI->load->view('error-handle', [
                        'message' => "Systems Error: Server Error - ".$resp["API_Error"],
                        'code'=> "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
            }
            $this->SetConfig("result",$resp);
        }
    }
    public function CallPatch()
    {
        $resp = ['query'=>"", "error" => ['code'=> "", "message"=>""]];
        $alert = "danger";
        if(!empty($this->_config["url"]))
        {
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
            $resp["API_Error"] = curl_error($curl);
            $resp["API_errCode"]  = curl_errno($curl);
            curl_close($curl);
            switch($code)
            {
                case 200:
                    
                break;
                case 404:
                    $this->_CI->load->view('error-handle', [
                        'message'=> "Systems Error: Data Source Error - ".$resp["API_Error"], 
                        'code' => "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
                case 405:
                    $this->_CI->load->view('error-handle', [
                        'message'=> "Systems Error: Data Source Error - ".$resp["API_Error"], 
                        'code' => "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
                case 500:
                    $this->_CI->load->view('error-handle', [
                        'message' => "Systems Error: Server Error - ".$resp["API_Error"],
                        'code'=> "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
            }
            $this->SetConfig("result",$resp);
        }
    }
    public function CallDelete()
    {
        $resp = ['query'=>"", "error" => ['code'=> "", "message"=>""]];
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
            $resp["API_Error"] = curl_error($curl);
            $resp["API_errCode"]  = curl_errno($curl);
            curl_close($curl);
            switch($code)
            {
                case 200:
                    
                break;
                case 404:
                    $this->_CI->load->view('error-handle', [
                        'message'=> "Systems Error: Data Source Error - ".$resp["API_Error"], 
                        'code' => "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
                case 405:
                    $this->_CI->load->view('error-handle', [
                        'message'=> "Systems Error: Data Source Error - ".$resp["API_Error"], 
                        'code' => "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
                case 500:
                    $this->_CI->load->view('error-handle', [
                        'message' => "Systems Error: Server Error - ".$resp["API_Error"],
                        'code'=> "Systems Error: HTTP-Code: ".$code." - ".$resp["API_errCode"], 
                        'alertstyle' => $alert
                    ]);
                break;
            }
            $this->SetConfig("result",$resp);
        }
    }
}