<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 18/1/12
 * Time: 上午11:59
 *
 * 分期产品逾期Model
 */
class Model_Order_Inst_Overdue extends Model_Database{


    const PENALTY_MAX_DAY = 999;    //最大罚息天数
    const PENALTY_RATE = 0.01;      //罚息率 : 每日罚息 = 罚息率 * 本金;
    const LATE_FEE_RATE = 0.01;     //滞纳金率 : 每日滞纳金 = 滞纳金率 * 本金;














}