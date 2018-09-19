<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/11/4
 * Time: 上午1:09
 *
 * 快金CAPI配置CACHE v1
 */
class Lib_TCCache_Settings {


    protected $key = 'settings';
    protected $field = '';
    protected $json = array();
    protected $redis;

    public function __construct($uri=NULL) {
        $this->redis = Redis_Hash::instance();
        $this->uri($uri);
    }

    public function uri($uri){
        if(!empty($uri)){
            $this->field = $uri;
        }
        return $this;
    }


    /** 读取CACHE信息
     * @param null $key
     * @return array|bool|mixed|null|string
     */
    public function get(){
        try{
            $this->json = $this->redis->get_field($this->key,$this->field);
            if(Lib::factory('String')->isJson($this->json)){
                return json_decode($this->json,TRUE);
            }
            return $this->json;
        }catch (Exception $e){
            return NULL;
        }

    }

    /** 设置字段
     * @param $value
     * @return bool
     */
    public function set($value){
        if(is_array($value)){
            $value = json_encode($value);
        }
        try{
            return $this->redis->set($this->key,$this->field,$value);
        }catch (Exception $e){
            return NULL;
        }
    }


    /** 清除
     * @param null $key
     */
    public function remove(){
        try{
            return $this->redis->del($this->key);
        }catch (Exception $e){
            return NULL;
        }
    }



    public function __destruct() {
        // TODO: Implement __destruct() method.
        unset($this->redis);
    }


}