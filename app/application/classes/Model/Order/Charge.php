<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/6/5
 * Time: 上午0:09
 *
 * 费用表
 */
class Model_Order_Charge extends Model_Database
{

    //加减
    const IN = 1;                   //加
    const DE = 2;                   //减
    //费用类型
    const FEE = 1;      //手续费类型
    const AMOUNT = 2;   //本金类型
    const SHOW = 3;     //仅做展示

    //费用记录与计算类型
    const TYPE_TOTAL = ['name' => '总利息', 'code' => 'total_1', 'calc' => Model_Order_Charge::IN, 'type' => self::FEE];                           //手续费合计
    const TYPE_INTEREST = ['name' => '借款利息', 'code' => 'interest_1', 'calc' => Model_Order_Charge::IN, 'type' => self::FEE];                    //利息
    const TYPE_PLATFORM_MANAGE = ['name' => '平台管理费', 'code' => 'platform_manage_1', 'calc' => Model_Order_Charge::IN, 'type' => self::FEE];    //平台管理费
    const TYPE_DAMAGE = ['name' => '违约金', 'code' => 'damage_1', 'calc' => Model_Order_Charge::IN, 'type' => self::FEE];    //违约金

    const TYPE_COUPON = ['name' => '优惠券', 'code' => 'coupon_1', 'calc' => Model_Order_Charge::DE, 'type' => self::FEE];             //优惠券

    const INTEREST_RATE = 0.006667;


    const REPAY_DAMAGE_RATE = 0.01;//提前还款违约金
    const DAY_PENALTY_RATE = 0.01;//罚息日利率存档
    const DAY_LATE_FEE_RATE = 0.01;//滞纳金日利率存


    /** 创建
     * @param $user_id
     * @param $order_id
     * @param array $array
     * @return bool
     *
     * example:
     *  Model::factory('Order_Charge')->create(App::$_token['user_id'],$order_id,Model_Order_Charge::TYPE_LOAN,'10.00');
     */

    public function create($user_id, $order_id, $array = [], $amount = 0)
    {
        if ($user_id < 1 || $order_id < 1 || !isset($array['name']) || !isset($array['code']) || !isset($array['calc']) || $amount < 0) {
            return false;
        }
        $user_id = (int)$user_id;
        $order_id = (int)$order_id;
        $type_code = $array['code'];
        $type_name = $array['name'];
        $type_calc = $array['calc'];
        $amount = round($amount, 2);
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time();
        $charge_type = $array['type'] ? $array['type'] : 0;

        list($new_id, $row) = DB::insert("order_charge", ['user_id', 'order_id', 'type_code', 'type_name', 'in_de', 'amount', 'charge_type', 'create_time'])
            ->values([$user_id, $order_id, $type_code, $type_name, $type_calc, $amount, $charge_type, $create_time])
            ->execute();

        return $new_id;
    }

    //单个订单数据
    public function update_amount($order_id, $type_code, $array)
    {
        return null !== DB::update('order_charge')->set($array)
                ->where('order_id', '=', (int)$order_id)
                ->where('type_code', '=', $type_code)
                ->execute();

    }

    //单个订单数据
    public function get_by_order_id($order_id)
    {
        return DB::select()->from('order_charge')->where('order_id', '=', (int)$order_id)->order_by('id', 'ASC')->execute()->as_array();
    }


    /**
     * @param $loan_amount
     * @param $month
     * @param $fee_amount
     * @return array|bool
     * des: 本方法生成结构数据可以用于创建费率子表记录。
     * example: $this->format_fee($this->make_fee_item(1000.00, 21, 123.00));
     */
    public function make_fee_item($loan_amount, $month, $fee_amount)
    {
        if (!$loan_amount || !$month || !$fee_amount) {
            return false;
        }
        $interest = bcmul(bcmul($loan_amount, $month, 4), self::INTEREST_RATE, 2);//计算利息

        $fee = bcsub($fee_amount, $interest, 2);//抛除利息外拆分细项
        $platform_manage = $fee;

        return [
            self::TYPE_INTEREST['code'] => ['amount' => $interest, 'model_type' => self::TYPE_INTEREST],
            self::TYPE_PLATFORM_MANAGE['code'] => ['amount' => $platform_manage, 'model_type' => self::TYPE_PLATFORM_MANAGE],
        ];

    }


    /**
     * @param $loan_amount
     * @param $month
     * @param $fee_amount
     * @return array|bool
     * des: 本方法生成结构数据通过format_fee转换可以输出的费用合计。
     * example: $this->format_fee($this->make_fee_total(123.00));
     */
    public function make_fee_total($total_amount)
    {
        return [['amount' => $total_amount, 'model_type' => self::TYPE_TOTAL]];
    }

    /** ver2.3.0 费用细项格式化输出
     * @param $array
     * @return array|bool
     */
    public function format_fee($array)
    {
        if (!$array || !is_array($array)) {
            return false;
        }
        $list = [];
        $unit = '元';
        foreach ($array as $v) {
            $list[] = [
                'amount' => (int)$v['model_type']['calc'] == Model_Order_Charge::DE ? (string)bcmul($v['amount'], -1, 2) : (string)$v['amount'],
                'unit' => $unit,
                'name' => (string)$v['model_type']['name'],
                'type' => isset($v['model_type']['type']) ? (string)$v['model_type']['type'] : (string)Model_Order_Charge::FEE,
            ];
        }

        return $list;
    }


    /**  目前不用
     * 数据表中的数据格式化输出
     * @param array $array
     * @return array|bool
     * example: $this->format($this->get_by_order_id(123));
     */
    public function format($array = [])
    {
        if (!is_array($array) || !$array) {
            return false;
        }
        $list = [];
        foreach ($array as $v) {

            $amount = (int)$v['in_de'] == Model_Order_Charge::DE ? bcmul($v['amount'], -1, 2) : $v['amount'];
            $unit = '元';
            $list[] = [
                'amount' => (string)$amount,
                'name' => (string)$v['type_name'],
                'unit' => (string)$unit,
                'type' => isset($v['charge_type']) ? (string)$v['charge_type'] : (string)Model_Order_Charge::FEE,
            ];
        }

        return $list;
    }


}
