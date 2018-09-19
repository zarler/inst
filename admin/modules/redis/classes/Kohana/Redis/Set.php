<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Redis
 * Type:    Set
 * Editor:  zhangmiao
 * Updated: 2016-07-22
 * Des:     集合 set(1,2,3,4,5)
 *
 * Example:
    Redis_Set::instance()->set('h1',array('key3'=>'kkk1','key4'=>'kkkk'));
    Redis_Set::instance()->get('h1');
    Redis_Set::instance()->del_field('h1','key3');
    Redis_Set::instance()->exists_field('h1','key4');
    Redis_Set::instance()->get('h1');
    Redis_Set::instance()->get_field('h1',array('key3','key4'));
 *
 *
 */
class Kohana_Redis_Set extends Kohana_Redis_Connect {

    public static $type = 'Set';


    public static function instance($name = NULL, array $config = NULL){
        Kohana_Redis::$type = Kohana_Redis_Set::$type;
        return parent::instance($name,$config);
    }

    /** 是否存在
     * @param null $key | array $key
     * @return bool If the key exists, return TRUE, otherwise return FALSE.
     * @throws Redis_Exception
     */
    public function exists($key=NULL) {
        if($key===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        return $this->_connection->exists($key);
    }


    /** 类型
     * @param null $key
     * @return bool | Depending on the type of the data pointed by the key, this method will return the following
     * value:
     *      string: Redis::REDIS_STRING set: Redis::REDIS_SET list: Redis::REDIS_LIST zset: Redis::REDIS_ZSET
     *      hash: Redis::REDIS_HASH
     *      other: Redis::REDIS_NOT_FOUND
     * @throws Redis_Exception
     */
    public function type($key=NULL) {
        if($key===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        return $this->_connection->type($key);
    }

    /** 元素数
     * @param null $key
     * @return bool | Int
     * @throws Redis_Exception
     */
    public function count($key=NULL) {
        if($key===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        return $this->_connection->sCard($key);
    }


    /** 
     * 返回集合的所有元素值
     * @param null $key
     * @return bool | array
     * @throws Redis_Exception
     */
    public function members($key=NULL) {
        if($key===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        return $this->_connection->sMembers($key);
    }


    /** 
     * 返回该值在当前集合中是否存在
     * @param null $key
     * @param null $value
     * @return bool
     * @throws Redis_Exception
     */
    public function is_member($key=NULL,$value=NULL) {
        if($key===NULL || $value===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        return $this->_connection->sIsMember($key,$value);
    }




    /** 
     * 添加元素
     * @param $key
     * @param $value
     * @param null $params
     * @return bool
     * @throws Redis_Exception
     */
    public function add($key=NULL, $value=NULL) {
        if($key===NULL || $value===NULL ){
            return FALSE;
        }
        $this->_connection or $this->connect();
        if(is_array($value)){
            return call_user_func_array(array($this->_connection, 'sAdd'), array_merge([$key],$value));
        }else{
            return $this->_connection->sAdd($key, $value);
        }
        
    }


    /** 
     * 删除元素
     * @param null $key
     * @param null $value
     * @return bool
     * @throws Redis_Exception
     */
    public function del($key=NULL,$value=NULL) {
        if($key===NULL || $value===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        if(is_array($value)){
            return call_user_func_array(array($this->_connection, 'sRem'), array_merge([$key],$value));
        }else{
            return $this->_connection->sAdd($key, $value);
        }
    }



} // End
