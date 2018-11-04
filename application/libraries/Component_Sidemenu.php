<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Sidemenu
{
    private $config = [
        "nav_list" => [],
        "nav_finished_list" => [],
        "uri" => ""
    ];
    public function __construct()
	{
        
    }
    public function Proccess()
    {
        $this->config['nav_finished_list'] = $this->build_menu($this->GetConfig("nav_list"), 0, $this->config['uri']);
        // echo "<pre>";
        // var_dump($this->config['nav_finished_list']);
        // echo "</pre>";
    }
    private function has_children($rows,$id) {
        foreach ($rows as $row) {
        if ($row['parent_id'] == $id)
            return true;
        }
        return false;
    }
    private function build_menu($rows, $parent=0, $set_active="")
    {  
        /*
		id - 1 login
		id - 2 Dushboard
		id - 3 product
			-> 23 items
                -> 43 Add
                    -> 899 detail 
            -> 54 categories
            -> 62 settings
		id - 22 administration
			-> 71 setttings
				-> 44 test items
        */
        
        foreach ($rows as $row)
        {
            if ($row['parent_id'] == $parent){
                $result[$row['id']] = $row;
                $result[$row['id']]['active'] = "";
                // echo $result[$row['id']]['slug'] . " ---- ". $set_active . " ===== ";
                // echo "str cmp = " . strcmp($result[$row['id']]['slug'], $set_active) . "<br>";
                if(strcmp($result[$row['id']]['slug'], $set_active) == 0 )
                {
                    $result[$row['id']]['active'] = "show";
                }
                
                if ($this->has_children($rows,$row['id'])){
                    $result[$row['id']]['child'] = $this->build_menu($rows,$row['id'],$set_active);
                    $result[$row['id']]['isParent'] = false;
                }
                else{
                    $result[$row['id']]['isParent'] = true;
                    
                }
                
            }
        }
        // echo "<pre>";
        // var_dump($result);
        // echo "</pre>";
        return $result;
    }

    public function SetConfig($func, $val)
    {
        $this->config[$func] = $val;
    }
    public function GetConfig($func)
    {
        return $this->config[$func];
    }
}