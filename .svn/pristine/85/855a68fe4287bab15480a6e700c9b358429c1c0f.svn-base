<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 16/1/4
 * Time: 下午1:53
 */
class Model_Order extends Model_Database
{

    //订单类型
    const TYPE_INST = 6;                        //分期借款

    //订单状态
    const STATUS_INIT = 0;                      //订单初始
    const STATUS_READY = 1;                     //待审
    const STATUS_ACCEPT = 2;                    //已审批
    const STATUS_REJECT = 3;                    //拒绝
    const STATUS_UNPAID = 4;                    //待付款
    const STATUS_PAID = 5;                      //已付款,待还
    const STATUS_ACT_REPAY = 6;                 //主动还款
    const STATUS_DEDUCT_SUCCESS = 8;            //扣款成功
    const STATUS_DEDUCT_FAILED = 9;             //扣款失败
    const STATUS_CLOSED = 10;                   //扣款失败

    const STATUS_DEDUCT_RUNNING = 11;           //扣款处理中
    const STATUS_PAYMENT_RUNNING = 12;          //付款处理中
    const STATUS_PAYMENT_FAILED = 13;           //付款失败
    const STATUS_PAYMENT_SUCCESS = 14;          //付款成功

    const STATUS_OVERDUE = 50;                  //逾期
    const STATUS_OVERDUE_REPAY_SUCCESS = 51;    //逾期催缴成功
    const STATUS_OVERDUE_REPAY_FAILED = 52;     //逾期催缴失败
    const STATUS_OVERDUE_ACT_REPAY = 56;        //逾期主动还款
    const STATUS_OVERDUE_DEDUCT_FAILED = 59;    //逾期主动还款失败
    const STATUS_OVERDUE_DEDUCT_SUCCESS = 58;   //逾期扣款成功
    const STATUS_OVERDUE_DEDUCT_RUNNING = 511;  //逾期扣款处理中

    const STATUS_REPAY_SUCCESS = 61;            //订单还款成功
    const STATUS_ADVANCE_REPAY_SUCCESS = 71;    //提前还款成功
    const STATUS_PENDING_VERIFICATION = 15;     //需进一步审核

    const STATUS_BULL_DEDUCT = 30;                      //扫扣
    const STATUS_BULL_DEDUCT_RUNNING = 31;              //扫扣处理中
    const STATUS_OVERDUE_BULL_DEDUCT = 530;             //逾期扫扣
    const STATUS_OVERDUE_BULL_DEDUCT_RUNNING = 531;     //逾期扫扣处理中


    //状态组,用于简化DB WHERE 增强一致性
    const STATUSGROUP_PAID = [  //待还款中的正常订单
        self::STATUS_PAID,
        self::STATUS_ACT_REPAY,
        self::STATUS_DEDUCT_SUCCESS,
        self::STATUS_DEDUCT_FAILED,
        self::STATUS_DEDUCT_RUNNING,
        self::STATUS_PAYMENT_SUCCESS,
    ];
    const STATUSGROUP_OVERDUE = [   //逾期中的订单
        self::STATUS_OVERDUE,
        self::STATUS_OVERDUE_REPAY_FAILED,
        self::STATUS_OVERDUE_ACT_REPAY,
        self::STATUS_OVERDUE_DEDUCT_FAILED,
        self::STATUS_OVERDUE_DEDUCT_SUCCESS,
        self::STATUS_OVERDUE_DEDUCT_RUNNING,
        self::STATUS_OVERDUE_BULL_DEDUCT,
        self::STATUS_OVERDUE_BULL_DEDUCT_RUNNING,
    ];

    const STATUSGROUP_SUCCESSED = [ //成功完成的订单
        self::STATUS_ADVANCE_REPAY_SUCCESS,
        self::STATUS_REPAY_SUCCESS,
        self::STATUS_OVERDUE_REPAY_SUCCESS,
    ];

    const STATUSGROUP_UNFINISHED = [ //未完成
        self::STATUS_INIT,
        self::STATUS_READY,
        self::STATUS_ACCEPT,
        self::STATUS_UNPAID,
        self::STATUS_PAID,
        self::STATUS_ACT_REPAY,
        self::STATUS_DEDUCT_FAILED,
        self::STATUS_DEDUCT_SUCCESS,
        self::STATUS_DEDUCT_RUNNING,
        self::STATUS_PAYMENT_RUNNING,
        self::STATUS_PAYMENT_FAILED,
        self::STATUS_PAYMENT_SUCCESS,
        self::STATUS_OVERDUE,
        self::STATUS_OVERDUE_REPAY_FAILED,
        self::STATUS_OVERDUE_ACT_REPAY,
        self::STATUS_OVERDUE_DEDUCT_FAILED,
        self::STATUS_OVERDUE_DEDUCT_SUCCESS,
        self::STATUS_OVERDUE_DEDUCT_RUNNING,
        self::STATUS_OVERDUE_BULL_DEDUCT,
        self::STATUS_OVERDUE_BULL_DEDUCT_RUNNING,
    ];

    const STATUSGROUP_DEDUCTING = [  //扣款处理
        self::STATUS_ACT_REPAY,
        self::STATUS_DEDUCT_FAILED,
        self::STATUS_DEDUCT_SUCCESS,
        self::STATUS_DEDUCT_RUNNING,
        self::STATUS_OVERDUE_ACT_REPAY,
        self::STATUS_OVERDUE_DEDUCT_FAILED,
        self::STATUS_OVERDUE_DEDUCT_SUCCESS,
        self::STATUS_OVERDUE_DEDUCT_RUNNING,
        self::STATUS_OVERDUE_REPAY_FAILED,

    ];

    const STATUSGROUP_PAY = [   //付款中
        self::STATUS_PAYMENT_RUNNING,
        self::STATUS_PAYMENT_FAILED,
        self::STATUS_PAYMENT_SUCCESS,
    ];

    const STATUSGROUP_PAY_SUCCESS__NOT_IN = [  //成功打款之外的状态，用于NOT IN(0,1,2,3,4,10,12,13,15)过滤出成功打款的订单
        self::STATUS_INIT,                  //0
        self::STATUS_READY,                 //1
        self::STATUS_ACCEPT,                //2
        self::STATUS_REJECT,                //3
        self::STATUS_UNPAID,                //4
        self::STATUS_CLOSED,                //10
        self::STATUS_PAYMENT_RUNNING,       //12
        self::STATUS_PAYMENT_FAILED,        //13
        self::STATUS_PENDING_VERIFICATION,  //15
    ];


    //记录锁,防止重复关键性操作
    //表中 pre_auth_lock; repay_lock pay_lock
    const UNLOCK = 1;
    const LOCK = 2;

    public static $status_array = [
        self::STATUS_INIT                 => '订单初始',
        self::STATUS_READY                => '待审',
        self::STATUS_ACCEPT               => '已审',
        self::STATUS_REJECT               => '拒绝',
        self::STATUS_UNPAID               => '待付',
        self::STATUS_PAID                 => '已付(待还)',
        self::STATUS_CLOSED               => '强制关闭',
        self::STATUS_PENDING_VERIFICATION => '需进一步审核',

        self::STATUS_ACT_REPAY      => '主动还款',
        self::STATUS_DEDUCT_SUCCESS => '扣款成功',
        self::STATUS_DEDUCT_FAILED  => '扣款失败',
        self::STATUS_DEDUCT_RUNNING => '扣款处理中',

        self::STATUS_PAYMENT_FAILED  => '付款失败',
        self::STATUS_PAYMENT_SUCCESS => '付款成功',
        self::STATUS_PAYMENT_RUNNING => '付款处理中',

        self::STATUS_OVERDUE                => '逾期',
        self::STATUS_OVERDUE_REPAY_SUCCESS  => '逾期催缴成功',
        self::STATUS_OVERDUE_REPAY_FAILED   => '逾期催缴失败',
        self::STATUS_OVERDUE_ACT_REPAY      => '逾期主动还款',
        self::STATUS_OVERDUE_DEDUCT_FAILED  => '逾期扣款失败',
        self::STATUS_OVERDUE_DEDUCT_SUCCESS => '逾期扣款成功',
        self::STATUS_OVERDUE_DEDUCT_RUNNING => '逾期扣款处理中',

        self::STATUS_REPAY_SUCCESS         => '订单还款成功',
        self::STATUS_ADVANCE_REPAY_SUCCESS => '提前还款成功',

    ];

    public $type_array = [
        self::TYPE_INST => '分期贷款',
    ];


    /**
     * 状态名对应
     * @param int $status_key
     * @return mixed|null
     */
    public function getStatusName($status_key = 0)
    {
        return isset(self::$status_array[$status_key]) ? self::$status_array[$status_key] : null;
    }


    /**
     * @param int $order_id
     * @param array $array
     * @return bool
     */
    public function update($order_id = 0, $array = [])
    {
        if ($order_id < 1 || !$array) {
            return false;
        }
        unset($array['id']);

        $dbc = DB::update('order')->set($array)->where('id', '=', intval($order_id));
        Model::factory('Order_ModifyLog')->log($order_id, ['method' => 'update', 'data' => $dbc->compile()]);
        $affected_rows = $dbc->execute();
        return $affected_rows !== null;
    }


    /**
     * 单条数据
     * @param $order_id
     * @return mixed
     */
    public function getOne($order_id)
    {
        return DB::select()->from('order')->where('id', '=', intval($order_id))->execute()->current();
    }


    //单条数据
    public function getOneByOrderNo($order_no)
    {
        return DB::select()->from('order')->where('order_no', '=', trim($order_no))->execute()->current();
    }


    /**
     * 多条件查询
     * @param $user_id
     * @param array $array
     * @param array $order_by
     * @param int $limit
     * @return mixed
     */
    public function getByArray($array = [], $order_by = [], $limit = 0)
    {
        $query = $this->queryBuilder(DB::select_array()->from('order'),$array);
        if($order_by){
            foreach ($order_by as $ob){
                $query->order_by($ob[0],$ob[1]);
            }
        }else{
            $query->order_by('order.id', 'DESC');
        }
        if($limit>0){
            $query->limit($limit);
        }
        if($limit==1) {
            return $query->execute()->current();
        }
        return $query->execute()->as_array();
    }


    //订单不能删除
    public function delete($order_id = 0)
    {
        return true;
    }



    /**
     * 构造查询条件
     *  说明:注意expire_time的查询 (>= expire_time__start | <=expire_time__end)
     * @param $query
     * @param array $array
     * @return mixed
     */
    protected function queryBuilder($query, $array = [])
    {

        if (isset($array['user_id']) && $array['user_id']) {
            $query->where('order.user_id', '=', trim($array['user_id']));
        }
        if (isset($array['order_id']) && $array['order_id']) {
            if (is_array($array['order_id'])) {
                $query->where('order.id', 'in', $array['order_id']);
            } else {
                $query->where('order.id', '=', $array['order_id']);
            }
        }

        if (isset($array['order_no']) && $array['order_no']) {
            if (is_array($array['order_no'])) {
                $query->where('order.order_no', 'in', $array['order_no']);
            } else {
                $query->where('order.order_no', '=', $array['order_no']);
            }
        }

        if (isset($array['mobile']) && $array['mobile']) {
            if (is_array($array['mobile'])) {
                $query->where('order.mobile', 'in', $array['mobile']);
            } else {
                $query->where('order.mobile', '=', $array['mobile']);
            }
        }

        if (isset($array['name']) && $array['name']) {
            if (is_array($array['name'])) {
                $query->where('order.name', 'in', $array['name']);
            } else {
                $query->where('order.name', '=', $array['name']);
            }
        }


        if (isset($array['identity_code']) && $array['identity_code']) {
            if (is_array($array['identity_code'])) {
                $query->where('order.identity_code', 'in', $array['identity_code']);
            } else {
                $query->where('order.identity_code', '=', $array['identity_code']);
            }
        }


        if (isset($array['status'])) {
            if (is_array($array['status'])) {
                $query->where('order.status', 'IN', $array['status']);
            } else {
                $query->where('order.status', '=', trim($array['status']));
            }
        }

        if (isset($array['type'])) {
            if (is_array($array['status'])) {
                $query->where('order.type', 'IN', $array['type']);
            } else {
                $query->where('order.type', '=', $array['type']);
            }
        }

        //注意expire_time参数 (>= expire_time__start | <=expire_time__end)
        if (isset($array['expire_time__start'])  && $array['expire_time__start']>0) {
            $query->and_where('order.expire_time', '>=', $array['expire_time__start']);
        }
        if (isset($array['expire_time__end'])  && $array['expire_time__end']>0) {
            $query->and_where('order.expire_time', '<=', $array['expire_time__end']);
        }
        //start_time (>= __start | <=__end)
        if (isset($array['start_time__start']) && $array['start_time__start'] > 0) {
            $query->and_where('order.start_time', '>=', $array['start_time__start']);
        }
        if (isset($array['start_time__end'])  && $array['start_time__end']>0) {
            $query->and_where('order.start_time', '<=', $array['start_time__end']);
        }
        //start_time (>= __start | <=__end)
        if (isset($array['create_time__start']) && $array['create_time__start'] > 0) {
            $query->and_where('order.create_time', '>=', $array['create_time__start']);
        }
        if (isset($array['create_time__end'])  && $array['create_time__end']>0) {
            $query->and_where('order.create_time', '<=', $array['create_time__end']);
        }


        return $query;
    }


    /**
     * 查询分页
     * @param array $array
     * @param array $order_by
     * @param int $perpage
     * @param int $page
     * @return mixed
     */
    public function getList($array = [],$order_by=[], $perpage = 20, $page = 1)
    {
        $query = $this->queryBuilder(DB::select_array()->from('order'), $array);
        if($order_by){
            foreach ($order_by as $ob){
                $query->order_by($ob[0],$ob[1]);
            }
        }else{
            $query->order_by('order.id', 'DESC');
        }
        //echo $query->__toString();
        //exit();
        if ($page < 1) {
            $page = 1;
        }
        $rs = $query->offset($perpage * ($page - 1))->limit($perpage)->execute()->as_array();
        return $rs;
    }


    /**
     * 查询统计
     * @param array $array
     * @return int
     */
    public function getTotal($array = [])
    {
        $rs = $this->queryBuilder(DB::select([DB::expr('COUNT(*)'), 'total'])->from('order'), $array)->execute()->current();
        //echo $query->__toString();
        return isset($rs['total']) ? $rs['total'] : 0;
    }



    /**
     * 还款成功后统一处理方法
     * @param $order_id
     * @return bool
     * 订单还款成功后的操作,建议在事务中使用
     */
    public function repaySuccessAfter($order_id)
    {
        if ($order = $this->getOne($order_id) ) {
            $this->update($order_id, ['repaid_time' => time()]);
        }
    }


    /** 关闭订单统一处理方法
     * @param $order_id
     * @return bool
     * 订单关闭后的操作,建议在事务中使用
     */
    public function closeAfter($order_id)
    {
        if ($order = $this->getOne($order_id)) {
            if ($rs = Model::factory('Finance_Profile')->amount_sub($order['loan_amount'])) {
                return true;
            } else {
                return false;
            }
        }
    }

}

/*








*/