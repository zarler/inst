<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/1/29
 * Time: 上午10:18
 */
Class Tool_Finance{
    /**
     * @param int $amount
     * @param int $day
     * @param float $rate
     * @return float
     * 带本金利滚利
     */
    public function interest_witch_capital($amount=0,$day=1,$rate=0.03){
        for($i=1;$i<$day;$i++){
            $amount=$this->interest_day($amount,$rate);
        }
        return $amount;
    }

    /**
     * @param int $amount   金额
     * @param int $day      天数
     * @param float $rate   利率
     * @return int|string
     * 不带本金利滚利 只要利息计算
     */
    public function interest($amount=0,$day=1,$rate=0.03){
        $source = $amount;
        for($i=1;$i<$day;$i++){
            $amount=$this->interest_day($amount,$rate);
        }
        return bcsub($amount,$source,2);
    }

    function interest_day($amount=0,$rate=0.03){
        return bcadd($amount,bcmul($amount,$rate,3),2);
    }

    //带本金: 直息
    public function interest_direct_capital($amount=0,$day=1,$rate=0.03){
        return bcadd($amount,bcmul(bcmul($amount,$rate,3),$day,3),2);
    }

    //不带本金:直息
    public function interest_direct($amount=0,$day=1,$rate=0.03){
        return bcmul(bcmul($amount,$rate,3),$day,2);
    }




}