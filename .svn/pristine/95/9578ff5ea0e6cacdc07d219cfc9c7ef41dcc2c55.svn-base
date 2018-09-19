<?php
defined('SYSPATH') or die('No direct script access.');


class Lib_Test
{
        
    public function __construct()
    {

    }

    public function doSomething($url)
    {
        try {
            $res = file_get_contents($url);
        } catch (Exception $e) {
            // print_R($e->getMessage());
        }
        $second = rand(500,1000)/500;
        sleep($second);
        if(isset($res)){
            return strlen($res);
        }else{
            return 0;
        }
    }



}