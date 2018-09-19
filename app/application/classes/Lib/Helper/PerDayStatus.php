<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 2017/7/31
 * Time: 下午11:10
 *
 * 每日统计,过0点自动清零
 */

class Lib_Helper_PerDayStatus
{

    protected $redis = NULL;
    protected $data = NULL;
    protected $_key = 'inst_app_per_day_status';
    protected $hash_key = '';

    public function __construct() {
        $this->redis =Redis_Hash::instance();
        $data = $this->redis->get($this->_key);
        $date = date('Y-m-d');
        if(isset($data['date']) && $data['date']==$date ){
            $this->data = $data;
        }else{
            $this->redis->del($this->_key);
            $this->data = [];
            $this->data['date'] = $date;
            $this->redis->set($this->_key,'date',$date);
        }
        return $this;
    }

    public function add($hash_key,$num=1){
        $this->data[$hash_key] = isset($this->data[$hash_key]) ? $this->data[$hash_key] + $num : $num ;
        $this->redis->set($this->_key,$hash_key,$this->data[$hash_key]);
        return $this->data[$hash_key];
    }


    public function value($hash_key,$null = 0){
        return isset($this->data[$hash_key]) ? $this->data[$hash_key] : $null ;
    }





}