<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/11/4
 * Time: 上午1:09
 *
 * 快金用户临时CACHE v1
 *
 * $array = Lib::factory('TCCache_User')->user_id(App::$_token['user_id'])->uri('v2/AD/Banner')->get();
 * Lib::factory('TCCache_User')->user_id(App::$_token['user_id'])->remove();
 */
class Lib_TCCache_User {

    const PREFIX ='user@';   //用户为单位 key: user@123

    protected $user_id;
    protected $key =self::PREFIX;
    protected $field = '';
    protected $array = '';
    protected $json = '';
    protected $redis;

    public function __construct($user_id=NULL) {
        $this->redis = Redis_Hash::instance();
        $this->user_id($user_id);
    }

    public function user_id($user_id){
        if(!empty($user_id)){
            $this->user_id = (int)$user_id;
            $this->key = self::PREFIX.$this->user_id;
        }
        return $this;
    }

    public function uri($uri){
        if(!empty($uri)){
            $this->field = (string)$uri;
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