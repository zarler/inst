<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 15/12/31
 * Time: 下午5:03
 *
 *
 */

class Tool_Array {

     /* 用法介绍
        过滤数组留下需要的字段
        $columns = Tool::factory('Array')->key_filter(array('user_id'=>1,'mobile'=>'13333331233','email'=>'aaa@aaa.cn'),array('user_id','mobile','identity_code'));
        只留需要的字段,如果不存在给空字符值.
        $columns = Tool::factory('Array')->key_filter($array,array('user_id','mobile'),'');
        过虑数组,给不同的键配置值独立的默认值
        $columns = Tool::factory('Array')->key_filter($array,array('user_id','mobile'),array('user_id'=>0,'mobile'=>'00000000000'));
      */
     //过滤数组字段,只取允许的键,最后一个参数不为NULL时将给给不存在的字段设置默认值.
     public static function key_filter($search_array,$key,$def=NULL) {
         if(!is_array($search_array)){
                return FALSE;
         }
         $array = array();
         if($key && is_array($key)) {
            foreach($key as $v){
               if(array_key_exists($v,$search_array)) {
                  $array[$v]=$search_array[$v];
               }elseif($def!==NULL){
                   if(!is_array($def)){
                       $array[$v]=$def;
                   }elseif(is_array($def) && isset($def[$v])){
                       $array[$v]=$def[$v];
                   }
               }
            }
         }else{
            if(array_key_exists($key,$search_array)) {
                $array[$key]=$search_array[$key];
            }elseif($def!==NULL){
                $array[$key]=$def;
            }
         }
         return $array;
     }

    //过滤数组字段,只取允许的并且有值的键
    public static function key_filter_has_value($search_array,$key) {
        if(!is_array($search_array)){
            return FALSE;
        }
        $array = array();
        if($key && is_array($key)) {
            foreach($key as $v){
                if(array_key_exists($v,$search_array) && $search_array[$v]!==NULL && $search_array[$v]!=='') {
                    $array[$v]=$search_array[$v];
                }
            }
        }else{
            if(array_key_exists($key,$search_array) && $search_array[$key]!==NULL && $search_array[$key]!=='') {
                $array[$key]=$search_array[$key];
            }
        }
        return $array;
    }

    //判断是否是JSON
    public function is_json($json_string){
        $test=json_decode($json_string);
        return (json_last_error() == JSON_ERROR_NONE);
    }






}