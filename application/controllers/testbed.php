<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestBed extends CI_Controller 
{
	public function __construct()
	{
        parent::__construct();
    }
    public function index()
    {


        $this->load->library("Component_Car",["NP&3dd"]);

        $a = ["a" => 1,"b" =>3,"c" => 4,"d"=>2,"e" => 5,"f" => 6,"g" => 2,"h" => 2];

        foreach($a as $k => $v)
        {
            $this->component_car->SetItems($k, $v);
        }
        $this->component_car->SetItems("f", 10);
        $items = $this->component_car->GetItems();
        echo "<pre>";
        var_dump($items);
        echo "</pre>";
    }

}