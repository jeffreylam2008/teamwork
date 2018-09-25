<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Sidemenu
{
    private $config = [
        "nav_list" => [],
        "nav_finished_list" => [],
        "active" => ""
    ];
    public function __construct()
	{
        
    }
    public function Proccess()
    {
        $this->config['nav_finished_list'] = $this->build_menu($this->GetConfig("nav_list"));
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
    private function build_menu($rows,$parent=0)
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
                if ($this->has_children($rows,$row['id'])){
                    $result[$row['id']]['child'] = $this->build_menu($rows,$row['id']);
                    $result[$row['id']]['isParent'] = false;
                }
                else{
                    $result[$row['id']]['isParent'] = true;
                }
            }
           
        }
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