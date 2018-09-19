<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 2017/3/28
 * Time: 下午3:57
 */
class Lib_Treasure
{

    /**
     * 生成外部订单号
     * @param $start
     * @param $date
     * @param $type
     * @param $length
     * @return string
     */
    public static function out_order_no($start='TC',$date='YmdHis',$type=NULL,$length=6){
        return $start.date($date).Text::random($type,$length);
    }

    /**
     * 生成外部订单号
     * @param $start
     * @param $length
     * @return string
     */
    public static function in_order_no($start='TC',$length=6){
        return $start.date('YmdHis').Text::random(NULL,$length);
    }

    /**
     * 生成四要素标识
     * @param $holder
     * @param $identity_code
     * @param $card_no
     * @return string
     */
    public static function id_key($holder,$identity_code,$card_no){
        return md5($holder.$identity_code.$card_no);
    }

}
