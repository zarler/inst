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


    //数组值转字符串
    public function value_string($array = NULL){
        if(is_array($array)){
            foreach($array as $k => $v){
                $array[$k] = is_array($v) ? $this->value_string($v) : (string) $v;
            }
        }
        return $array;
    }

    //对象转数组
    public function object_to_array($obj) {
        $arr=array();
        $_arr = is_object($obj) ? get_object_vars($obj):$obj;
        foreach ($_arr as $key=>$val){
            $val = (is_array($val) || is_object($val))?$this->object_to_array($val):$val;
            $arr[$key] = $val;
        }
        return $arr;
    }

    //将数组中按照指定数组的$key顺序排前面，数组重新排序
    public function use_sortarr_tosort_array($sortarr,$arr) {
        $_arr=array();
        foreach ($sortarr as $val){
            if(array_key_exists($val,$arr)){
                $_arr[$val]=$arr[$val];
                unset($arr[$val]);
            }
        }
        return array_merge($_arr,$arr);
    }

    //二维数组按指定 key 排序
    public function multi_array_sort($multi_array,$sort_key,$sort = SORT_ASC){
        if(is_array($multi_array)){
            foreach ($multi_array as $row_array){
                if(is_array($row_array)){
                    $key_array[] = $row_array[$sort_key];
                }else{
                    return FALSE;
                }
            }
        }else{
            return FALSE;
        }
        array_multisort($key_array,$sort,$multi_array);
        return $multi_array;
    }

    /**
     * 二维数组将制定字段的值置为键
     * @param   $arr: 二维数组
     * @param   $field: 要指定为键的字段
     */
    public function array_value_to_key($arr,$field="id"){
        if(!empty($arr)){
            foreach($arr as $k => $v){
                $res[$v[$field]] = $v;
            }
            return $res;
        }else{
            return array();
        }
    }



}