<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Component_URI
{

    public function QueryToString($input = "")
    {
        $str = "?";
        $i = 0;
        $len = count($input);
        foreach($input as $k => $v)
        {
        	$str .= $k."=".$v;
        	if ($i != $len - 1) {
        		$str .="&";
        	}
        	$i++;
        }
        return $str;
    }


}