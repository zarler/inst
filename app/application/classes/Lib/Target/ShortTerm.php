<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: majin
 * Date: 17/12/26
 * Time: 上午11:27
 *
 * 短期跳转token 不适用数据库存储(分钟)
 *
 */
class Lib_Target_ShortTerm
{

    private static $hash_key = '749075ff8a64aee74d500c35c0b10a78'; //
    protected $string ='';
    protected $secret = '';
    protected $start_date = '0000-00-00';
    protected $end_date = '0000-00-00';
    protected $previous_date = '0000-00-00';

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
            $this->start_date = date('Y-m-d H:i',strtotime($date));
            $this->end_date = date('Y-m-d H:i',strtotime($this->start_date.' + 1 minutes'));
            $this->previous_date = date('Y-m-d H:i',strtotime($this->start_date.' - 1 minutes'));
        }else{
            $this->start_date = date('Y-m-d H:i');
            $this->end_date = date('Y-m-d H:i',strtotime(' + 1 minutes'));
            $this->previous_date = date('Y-m-d H:i',strtotime(' - 1 minutes'));
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
        $hash1 = md5($this->start_date."\n".$this->end_date."\n".$string."\n".$this->secret);//当前
        $hash2 = md5($this->previous_date."\n".$this->start_date."\n".$string."\n".$this->secret);//下一分钟
        if($hash_str == $hash1 || $hash_str == $hash2 ){
            return TRUE;
        }
        return FALSE;
    }


    //带随机数验证
    public function valid_random($hash_str,$string='',$rnd=''){
        $hash1 = md5(md5($this->start_date."\n".$this->end_date."\n".$string."\n".$this->secret).$rnd);//上一分钟
        $hash2 = md5(md5($this->previous_date."\n".$this->start_date."\n".$string."\n".$this->secret).$rnd);//当前
        if($hash_str == $hash1 || $hash_str == $hash2 ){
            return TRUE;
        }
        return FALSE;
    }


}