<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 17/7/14
 * Time: 上午1:09
 *
 * 快金 TOKEN CACHE v1
 * redis key命名规则 token@substr(md5($token),0,2) 取md5后的前两位字符作为key的一部分
 *
 *
 * $array = Lib::factory('TCCache_Token')->token(App::$token)->get();
 * Lib::factory('TCCache_Token')->token(App::$token)->remove();
 *
 *
 *
 *
 */
class Lib_TCCache_Token {

    const PREFIX ='token@';   //用户为单位 key: user@123

    protected $user_id;
    protected $key =self::PREFIX;
    protected $field = '';
    protected $array = '';
    protected $json = '';
    protected $redis;

    public function __construct($token=NULL) {
        $this->redis = Redis_Hash::instance();
        if($token!==NULL){
            $this->token($token);
        }
    }


    public function token($key){
        if(!empty($key)){
            $this->key = (string)self::PREFIX .substr(md5($key),0,2);
            $this->field = $key;
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
            if(Lib::factory('Array')->isJson($this->json)){
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


    /** 增加相应值
     * @param $value
     * @return bool
     */
    public function hincrby($value){
        if(is_array($value)){
            $value = json_encode($value);
        }
        try{
            return $this->redis->hincrby($this->key,$this->field,$value);
        }catch (Exception $e){
            return NULL;
        }
    }

    /** 清除
     * @param null $key
     */
    public function remove($field=NULL){
        try{
            if($field!==NULL){
                return $this->redis->del_field($this->key,$field);
            }else{
                return $this->redis->del_field($this->key,$this->field);
            }
        }catch (Exception $e){
            return NULL;
        }
    }



    public function __destruct() {
        // TODO: Implement __destruct() method.
        unset($this->redis);
    }


}
