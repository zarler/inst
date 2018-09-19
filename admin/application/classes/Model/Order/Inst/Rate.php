<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 18/1/12
 * Time: 上午11:59
 *
 * 分期产品Model
 */
class Model_Order_Inst_Rate extends Model_Abstract_ProductRate
{

    protected $day_interest_rate = 0.001;
    protected $month_interest_rate = 0.03;
    protected $day_penalty_rate = 0.01;
    protected $day_late_fee_rate = 0.01;


    public function dayInterest($amount, $day = 1)
    {
        // TODO: Implement dayInterest() method.
        return bcmul(bcmul($amount,$this->getDayInterestRate(),4),$day,2);
    }

    public function monthInterest($amount, $month = 1)
    {
        // TODO: Implement monthInterest() method.
        return bcmul(bcmul($amount,$this->getMonthInterestRate(),4),$month,2);
    }

    public function dayPenalty($amount, $day = 1)
    {
        // TODO: Implement dayPenalty() method.
        return bcmul(bcmul($amount,$this->getDayPenaltyRate(),4), $day, 2);
    }

    public function dayLateFee($amount, $day = 1)
    {
        // TODO: Implement dayLateFee() method.
        return bcmul(bcmul($amount,$this->getDayPenaltyRate(),4),$day,2);
    }


}