<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: majin
 * Date: 17/12/26
 * Time: 上午11:27
 *
 * 长期跳转token 不适用数据库存储(月有效)
 *
 */
class Lib_Target_LongTerm
{

    private static $hash_key = '94e8d26da10670ced31b2a2e5802d314'; //
    protected $string ='';
    protected $secret = '';
    protected $start_date = '0000-00';
    protected $end_date = '0000-00';
    protected $previous_date = '0000-00';

    public function __construct($secret=NULL,$date=NULL) {
        $this->secret = self::$hash_key;
        $this->date($date);
        $this->secret($secret);
    }

    public function secret($string=NULL){
        if($string!==NULL) {
            $this->secret = md5($string);
        }
        return $this;
    }
    public function key($string){
        $this->secret($string);
        return $this;
    }

    public function date($date=NULL){
        if($date!==NULL && strtotime($date) ){
            $this->start_date = date('Y-m',strtotime($date));
            $this->end_date = date('Y-m',strtotime($this->start_date.' + 1 months'));
            $this->previous_date = date('Y-m',strtotime($this->start_date.' - 1 months'));
        }else{
            $this->start_date = date('Y-m');
            $this->end_date = date('Y-m',strtotime(' + 1 months'));
            $this->previous_date = date('Y-m',strtotime(' - 1 months'));
        }
        return $this;
    }


    //生成哈希值
    public function hash($string){
        return md5($this->start_date."\n".$this->end_date."\n".$string."\n".$this->secret);
    }


    //生成带随机数的哈希值
    public function hash_random($string,$rnd){
        return md5($this->hash($string).$rnd);
    }


    //验证
    public function valid($hash_str,$string=''){
        $hash1 = md5($this->start_date."\n".$this->end_date."\n".$string."\n".$this->secret);//当月
        $hash2 = md5($this->previous_date."\n".$this->start_date."\n".$string."\n".$this->secret);//跨月
        if($hash_str == $hash1 || $hash_str == $hash2 ){
            return TRUE;
        }
        return FALSE;
    }


    //带随机数验证
    public function valid_random($hash_str,$string='',$rnd=''){
        $hash1 = md5(md5($this->start_date."\n".$this->end_date."\n".$string."\n".$this->secret).$rnd);//当月
        $hash2 = md5(md5($this->previous_date."\n".$this->start_date."\n".$string."\n".$this->secret).$rnd);//跨月
        if($hash_str == $hash1 || $hash_str == $hash2 ){
            return TRUE;
        }
        return FALSE;
    }


}