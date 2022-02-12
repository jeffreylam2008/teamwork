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
            $_err_detail["Error"] = curl_error($curl);
            $_err_detail["Code"]  = curl_errno($curl);
            curl_close($curl);

            if($_err_detail["Code"] > 0){
                $_err = json_encode($_err_detail,true);
                $this->SetConfig("result",$_err);
            }
            else
            {
                $this->SetConfig("result",$resp);
            }
        }
    }

    public function CallPost()
    {
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
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if(!$resp){
                $_err_detail["Error"] = curl_error($curl);
                $_err_detail["Code"]  = $curl ;
                $_err_detail["http_code"] = $code;
                $_err = json_encode($_err_detail,true);
                $resp['resp_err'] = $err;
            }
            curl_close($curl);
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
            if(!$resp){
                $_err_detail["Error"] = curl_error($curl);
                $_err_detail["Code"]  = $curl ;
                $_err = json_encode($_err_detail,true);
                $this->SetConfig("result",$_err);
            }
            curl_close($curl);
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
            if(!$resp){
                $_err_detail["Error"] = curl_error($curl);
                $_err_detail["Code"]  = $curl ;
                $_err = json_encode($_err_detail,true);
                $this->SetConfig("result",$_err);
            }
            curl_close($curl);
            $this->SetConfig("result",$resp);
        }
    }
}