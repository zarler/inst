<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/12/5
 * Time: 上午1:09
 *
 * 快金微信 CACHE v1
 *
 * uri 可以在一个HASH类型表里面区分不同的键值对
 * 例如
 *  $json_string = Lib::factory('TCCache_Wechat')->uri('tcwx_v2')->get();
 *  Lib::factory('TCCache_Wechat')->uri('tcwx_v2')->set(['token'=>$accessToken,'ticket'=>$wx_card_json['ticket'],'expires_in'=>$token_json['expires_in']+time()]);
 */
class Lib_TCCache_Wechat {


    protected $key = 'wechat';
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
        }catch (Exception $e) {
            return NULL;
        }

    }



    public function __destruct() {
        // TODO: Implement __destruct() method.
        unset($this->redis);
    }


}