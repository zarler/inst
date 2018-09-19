<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/1/6
 * Time: 下午6:18
 */
Class Tool_AcTool{
    //时间限制
    public function TimeLimit($start,$end,$startMsg='',$endMsg=''){
        is_numeric($start) or die('开始参数不是数字');
        is_numeric($end) or die('结束参数不是数字');
        $time = time();
        if($time<$start){
            //未开始
           return json_encode(array('status'=>false,'msg'=>$startMsg));
        }elseif($time>$end){
            //结束
            return json_encode(array('status'=>false,'msg'=>$endMsg));
        }else{
            return json_encode(array('status'=>true,'msg'=>'正在开始中'));
        }
    }

}