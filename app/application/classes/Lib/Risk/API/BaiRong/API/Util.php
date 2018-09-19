<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/12 0012
 * Time: 17:11
 */
class Util{
    /**
     * desc : csv文件表头字段
     * $data : Array
     **/
    public static $title = array();

    public static function post($url, $data, $timeout = 30){
        $ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
        $ch = curl_init();
        $opt = array(
            CURLOPT_URL     => $url,
            CURLOPT_POST    => 1,
            CURLOPT_HEADER  => 0,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_TIMEOUT         => $timeout,
        );
        if ($ssl)
        {
            $opt[CURLOPT_SSL_VERIFYHOST] = 2;
            $opt[CURLOPT_SSL_VERIFYPEER] = FALSE;
        }
        curl_setopt_array($ch, $opt);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }


    public static function getTitle($arr){
        if(is_array($arr)){

            foreach($arr as $key => $val){
                if(is_array($val)){}
            }
        }
    }

    public static function array_remove($data, $key){
        if(!array_key_exists($key, $data)){
            return $data;
        }
        $keys = array_keys($data);
        $index = array_search($key, $keys);
        if($index !== FALSE){
            array_splice($data, $index, 1);
        }
        return $data;
    }
}