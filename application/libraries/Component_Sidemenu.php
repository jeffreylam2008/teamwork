<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_Sidemenu
{
    /**
     * Global variable
     */
    private $config = [
        "nav_list" => [],
        "nav_finished_list" => [],
        "active" => "",
        "current" => "",
        "path" => [],
        "slug" => ""
    ];

    /**
     * Class constructor
     * 
     */
    public function __construct()
	{
        
    }
    /**
     * Proccess
     * 
     * To generate the menu
     */
    public function Proccess()
    {
        if(empty($this->config['active']))
        {
            $this->config['active'] = "Dushboard/index";
        }

        $this->config['nav_finished_list'] = $this->build_menu($this->config["nav_list"], 0, $this->config['active']);
        $this->find_parent($this->config["nav_list"],$this->config["current"]);
        $this->config['path'] = array_reverse($this->config['path']);
        // echo "<pre>";
        // var_dump($this->config['nav_finished_list']);
        // echo "</pre>";
    }
    /**
     * has_children
     * 
     * look for children from the list
     * 
     * @param rows the list of menu
     * @param id parent ID
     */
    private function has_children($rows,$id) {
        foreach ($rows as $row) {
        if ($row['parent_id'] == $id)
            return true;
        }
        return false;
    }
    /**
     * build_menu
     * 
     * build the menu structure
     * 
     * @param rows the list of menu
     * @param parent user input parent ID for comparison
     * @param set_active the uri that currently located
     */
    private function build_menu($rows, $parent=0, $set_active="")
    {  
        foreach ($rows as $row)
        {

            if ($row['parent_id'] == $parent){
                $result[$row['order']] = $row;

                // If the result same as current URI 
                if(strcmp($result[$row['order']]['param'], $set_active) == 0 )
                {
                    // Set current
                    $this->config['current'] = $row['id'];
                }

                if ($this->has_children($rows,$row['id']))
                {
                    $result[$row['order']]['child'] = $this->build_menu($rows,$row['id'],$set_active);
                    $result[$row['order']]['isParent'] = false;
                }
                else
                {
                    $result[$row['order']]['isParent'] = true;
                }
            }   
        }
        // echo "<pre>";
        // var_dump($result);
        // echo "</pre>";
        return $result;
    }
    /**
     * find_parent
     * 
     * Look for parent ID of the element
     * 
     * @param rows the list of menu
     * @param parent user input parent ID for comparison
     */
    private function find_parent($rows, $parent)
    {
        foreach($rows as $k => $row)
        {
            if ($row['id'] == $parent){
				// found parent
                $this->config['path'][] = $row['name'];
				// has parent, then find next
                if(!empty($row['parent_id']))
                {
                    $this->config['slug'] = $row['slug'];
                    $this->find_parent($rows, $row['parent_id']);
                }
            }
        }
    }

    /**
     * SetConfig
     * 
     * Set user input to this class
     * 
     * @param func the key for this item
     * @param val the val to set
     */
    public function SetConfig($func, $val)
    {
        $this->config[$func] = $val;
    }

    /**
     * SetConfig
     * 
     * Retrieve user input from this class
     * 
     * @param func the key for this item
     */
    public function GetConfig($func)
    {
        return $this->config[$func];
    }
}