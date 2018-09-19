<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 2017/8/18
 * Time: 下午6:26
 */
class Controller_API_Test extends Controller{

    public function action_test(){
        $file = array('file'=>DOCROOT."Image/123.jpg");
        $tc = Lib::factory("Upload")->file($file)->query();
        var_dump($tc);
    }
}
