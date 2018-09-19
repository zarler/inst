<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: chunyu
 * Date: 18/01/11
 * Time: 上午0:09
 *
 * 分期Model
 */
class Model_Order_Inst extends Model_Database
{

    const TYPE = Model_Order::TYPE_INST;        //产品类型
    const ON = 1;                               //开关:开
    const OFF = 0;                              //开关:关

    /*通用配置*/
    const MAX_AMOUNT = 6000;              //最大放款金额
    const MIN_AMOUNT = 1000;              //最小放款金额
    const MAX_MONTH = 6;                  //最大借款天数
    const MIN_MONTH = 2;                  //最小借款天数
    const AMOUNT_STEP_LINE = 1000;        //金额步长
    const MONTH_MAP = [2, 3, 6];          //借款期限
    const AMOUNT_MONTH_MAP = [
        1000 => [2, 3],
        2000 => [2, 3, 6],
        3000 => [3, 6],
        4000 => [3, 6],
        5000 => [6],
        6000 => [6],
    ];   //分期贷放款金额
    const AMOUNT_MAP = [1000, 2000, 3000, 4000, 5000, 6000];   //分期贷放款金额

    const PER_DAY_MAX_FIRST = 1000;              //分期贷 首贷 每日上限
    const PER_DAY_MAX_AGAIN = 1900;              //分期贷 复贷 每日上限

    const PER_DAY_START = "10:00";         //每日开发申请时间
    const PER_DAY_END = "23:59";           //每日结束申请时间
    const KEY_STATUS = 'inst_status';     //redis里的key


    const DRAW_PER_DAY_MAX_FIRST = 2000;//首贷 数据渲染最大值
    const DRAW_PER_DAY_MAX_AGAIN = 3000;//复贷 数据渲染最大值


    /**
     * 统计当天放款笔数
     * @return int
     */
    public function total_by_date($date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }
        $rs = DB::select([DB::expr('COUNT(*)'), 'total'])
            ->from('order')
            ->where('partner_code', '=', Def::PARTNER_CODE)//平台区别码
            ->where('type', '=', Model_Order::TYPE_INST)
            ->and_where('create_time', '>=', strtotime($date))
            ->and_where('create_time', '<', strtotime($date . ' +1 day'))
            //->and_where('start_time','<',strtotime($date.' +1 day'))
            ->and_where('status', 'NOT IN',
                        [Model_Order::STATUS_REJECT, Model_Order::STATUS_CLOSED, Model_Order::STATUS_PENDING_VERIFICATION])
            ->execute()
            ->current();

        return isset($rs['total']) ? $rs['total'] : 0;

    }


}