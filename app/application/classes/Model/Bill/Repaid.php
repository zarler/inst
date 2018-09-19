<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 2018/1/17
 * Time: 上午2:58
 */
class Model_Bill_Repaid extends Model_Database
{


    public function segment($bill,$amount,$extend=[])
    {
        if ($bill) {
            $repay_amount = $bill['repay_amount'];
            $repay_late_fee = $bill['repay_late_fee'];
            $repay_penalty = $bill['repay_penalty'];
            $repay_management = $bill['repay_management'];
            $repay_interest = $bill['repay_interest'];
            $repay_principal = $bill['repay_principal'];
            $repay_damage = $bill['repay_damage'];

            $repaid_amount = $bill['repaid_amount'];
            $repaid_late_fee = $bill['repaid_late_fee'];
            $repaid_penalty = $bill['repaid_penalty'];
            $repaid_management = $bill['repaid_management'];
            $repaid_interest = $bill['repaid_interest'];
            $repaid_principal = $bill['repaid_principal'];
            $repaid_damage = $bill['repaid_damage'];

            $_amount = $amount;

            $unrepaid_late_fee = bcsub($bill['repay_late_fee'],$bill['repaid_late_fee'],2);
            $unrepaid_penalty = bcsub($bill['repay_penalty'],$bill['repaid_penalty'],2);
            $unrepaid_management = bcsub($bill['repay_management'],$bill['repaid_management'],2);
            $unrepaid_interest = bcsub($bill['repay_interest'],$bill['repaid_interest'],2);
            $unrepaid_principal = bcsub($bill['repay_principal'],$bill['repaid_principal'],2);
            $unrepaid_damage = bcsub($bill['repay_damage'],$bill['repaid_damage'],2);

            $current_late_fee = 0.00;
            $current_penalty = 0.00;
            $current_management = 0.00;
            $current_interest = 0.00;
            $current_principal = 0.00;
            $current_damage = 0.00;


            //滞纳金
            if(bccomp($_amount,$unrepaid_late_fee,2)>=0) {
                $_amount = bcsub($_amount,$unrepaid_late_fee,2);
                $repaid_late_fee = bcadd($repaid_late_fee,$unrepaid_late_fee,2);
                $current_late_fee = $unrepaid_late_fee;
                $unrepaid_late_fee = 0.00;
            } else {
                $unrepaid_late_fee = bcsub($unrepaid_late_fee,$_amount,2);
                $repaid_late_fee  = bcadd($repaid_late_fee ,$_amount,2);
                $current_late_fee = $_amount;
                $_amount = 0.00;
            }

            //罚息
            if(bccomp($_amount,0,2)>0) {
                if(bccomp($_amount,$unrepaid_penalty,2)>=0) {
                    $_amount = bcsub($_amount,$unrepaid_penalty,2);
                    $repaid_penalty = bcadd($repaid_penalty,$unrepaid_penalty,2);
                    $current_penalty = $unrepaid_penalty;
                    $unrepaid_penalty = 0.00;
                } else {
                    $unrepaid_penalty = bcsub($unrepaid_penalty,$_amount,2);
                    $repaid_penalty = bcadd($repaid_penalty,$_amount,2);
                    $current_penalty = $_amount;
                    $_amount = 0.00;
                }
            }

            //管理费
            if(bccomp($_amount,0,2)>0) {
                if(bccomp($_amount,$unrepaid_management,2)>=0) {
                    $_amount = bcsub($_amount,$unrepaid_management,2);
                    $repaid_management = bcadd($repaid_management,$unrepaid_management,2);
                    $current_management = $unrepaid_management;
                    $unrepaid_management = 0.00;
                } else {
                    $unrepaid_management = bcsub($unrepaid_management,$_amount,2);
                    $repaid_management = bcadd($repaid_management,$_amount,2);
                    $current_management = $_amount;
                    $_amount = 0.00;
                }
            }

            //利息
            if(bccomp($_amount,0,2)>0) {
                if(bccomp($_amount,$unrepaid_interest,2)>=0) {
                    $_amount = bcsub($_amount,$unrepaid_interest,2);
                    $repaid_interest= bcadd($repaid_interest,$unrepaid_interest,2);
                    $current_interest = $unrepaid_interest;
                    $day_unrepaid_interest = 0.00;
                } else {
                    $unrepaid_interest = bcsub($unrepaid_interest,$_amount,2);
                    $repaid_interest = bcadd($repaid_interest,$_amount,2);
                    $current_interest = $_amount;
                    $_amount = 0.00;
                }
            }

            //本金
            if(bccomp($_amount,0,2)>0){
                if(bccomp($_amount,$unrepaid_principal,2)>=0){
                    $_amount = bcsub($_amount,$unrepaid_principal,2);
                    $repaid_principal = bcadd($repaid_principal,$unrepaid_principal,2);
                    $current_principal = $unrepaid_principal;
                    $day_unrepaid_principal = 0.00;
                }else{
                    $unrepaid_principal = bcsub($unrepaid_principal,$_amount,2);
                    $repaid_principal = bcadd($repaid_principal,$_amount,2);
                    $current_principal = $_amount;
                    $_amount = 0.00;
                }
            }

            //违约金
            if(bccomp($_amount,0,2)>0){
                if(bccomp($_amount,$unrepaid_damage,2)>=0){
                    $_amount = bcsub($_amount,$unrepaid_damage,2);
                    $repaid_damage = bcadd($repaid_damage,$unrepaid_damage,2);
                    $current_damage = $unrepaid_damage;
                    $unrepaid_damage = 0.00;
                }else{
                    $unrepaid_damage = bcsub($unrepaid_damage,$_amount,2);
                    $repaid_damage = bcadd($repaid_damage,$_amount,2);
                    $current_damage = $_amount;
                    $_amount = 0.00;
                }
            }

            return [
                'bill_id'           => $bill['id'],
                'order_id'           => $bill['order_id'],
                'user_id'           => $bill['user_id'],
                'repaid_amount'     => $amount,
                'repaid_late_fee'   => $current_late_fee,
                'repaid_penalty'    => $current_penalty,
                'repaid_management' => $current_management,
                'repaid_interest'   => $current_interest,
                'repaid_principal'  => $current_principal,
                'repaid_damage'     => $current_damage,
            ];

        }
        return false;
    }

    /**
     * 入账
     * @param $bill_id
     * @param $amount
     * @param array $array
     * @return bool|array
     */
    public function in($bill_id,$amount,$array=[])
    {
        if($bill = Model::factory('Bill')->getOne($bill_id)) {
            $seg = $this->segment($bill, $amount, $array);//拆分单笔还款的各项费用
            if ($seg) {
                //更新账单表
                $update_result = Model::factory('Bill')->update($bill_id,[
                    'repaid_amount'     => bcadd($bill['repaid_amount'],$seg['repaid_amount'],2),
                    'repaid_late_fee'   => bcadd($bill['repaid_late_fee'],$seg['repaid_late_fee'],2),
                    'repaid_penalty'    => bcadd($bill['repaid_penalty'],$seg['repaid_penalty'],2),
                    'repaid_management' => bcadd($bill['repaid_management'],$seg['repaid_management'],2),
                    'repaid_interest'   => bcadd($bill['repaid_interest'],$seg['repaid_interest'],2),
                    'repaid_principal'  => bcadd($bill['repaid_principal'],$seg['repaid_principal'],2),
                    'repaid_damage'     => bcadd($bill['repaid_damage'],$seg['repaid_damage'],2),
                    'repaid_time'       => time(),
                ]);
                if($update_result){
                    return $seg;
                }
            }

        }
        return false;
    }







}