<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Pagination
{
    protected $CI;
    private $config = [
        "per_page" => 10,
        "last" => 0,
        "current" => 0,
        "page_of_page" => 10,
        "items" => [],
        "layer1" => [],
        "layer2" => []
    ];
    public function __construct()
	{
        $this->CI =& get_instance();
    }
    public function Proccess()
    {
        $layer1 = [];
        $tmp = [];
        $layer1 = array_chunk($this->config['items'], $this->config["per_page"]);
        $this->config["last"] = count($layer1);
        $this->config["layer1"] = $layer1;

        // for page of page
        for($i = 1; $i <= count($layer1); $i++)
        {
            $tmp[$i] = $i;
        }

        
        $layer2 = array_chunk($tmp,$this->config["page_of_page"]);
       
        for($i = 0; $i < count($layer2); $i++)
        {
            for($j = 0; $j < count($layer2[$i]); $j++)
            {
                // echo "<pre>";
                // print_r($layer2[$i]);
                // echo "</pre>";
                if($this->config["current"] == ($layer2[$i][$j]))
                {
                    // echo "current = ".($pp[$i][$j])."<br>";
                    // echo "within block = ". $i."<br>";
                    foreach($layer2[$i] as $k => $v){

                        $this->config["layer2"][] = $v;
                    }
                }
            }
        }
        // echo "<pre>";
        // print_r($this->config["layer2"]);
        // echo "</pre>";
    }
    public function SetConfig($func, $val)
    {
        $this->config[$func] = $val;
    }
    public function GetConfig($func)
    {
        return $this->config[$func];
    }
    public function ShowContent($template = "")
    {
        // do something html here
        if(!empty($template))
        {
            return $this->CI->load->view(
                $template, 
                [
                    "current" => $this->config['current'],
                    "data" => $this->config["layer1"]
                ],
                TRUE
            );
        }
    }
    public function ShowPageBar($template = "")
    {
        if(!empty($template))
        {
            return $this->CI->load->view(
                $template, 
                [
                    "last" => $this->config['last'], 
                    "current" => $this->config['current'],
                    "data" => $this->config['layer2']
                ],
                TRUE
            );
        }
    }
}