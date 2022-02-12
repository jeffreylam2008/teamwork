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
        if(!empty($this->_config["url"]))
        {
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
            $resp = json_encode($resp, true);
            $this->SetConfig("result",$resp);
        }
    }

    public function CallPost()
    {
        $resp = [];
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
            $resp = json_encode($resp, true);
            $this->SetConfig("result",$resp);
        }
    }
    public function CallPatch()
    {
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
            $resp = json_encode($resp, true);
            $this->SetConfig("result",$resp);
        }
    }
    public function CallDelete()
    {
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
            $resp = json_encode($resp, true);
            $this->SetConfig("result",$resp);
        }
    }
}