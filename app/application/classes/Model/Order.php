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
    const STATUS_CLOSED = 10;                   //关闭

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

    const STATUSGROUP_NEEDREPAY = [ //需要还款
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

    const STATUSGROUP_DEALING = [  //成功打款之外的状态，用于NOT IN(0,1,2,3,4,10,12,13,15)过滤出成功打款的订单
        self::STATUS_INIT,                  //0
        self::STATUS_READY,                 //1
        self::STATUS_ACCEPT,                //2
        self::STATUS_UNPAID,                //4
        self::STATUS_PAYMENT_RUNNING,       //12
        self::STATUS_PAYMENT_FAILED,        //13
        self::STATUS_PAYMENT_SUCCESS,       //14
        self::STATUS_PENDING_VERIFICATION,  //15
    ];


    //记录锁,防止重复关键性操作
    //表中 pre_auth_lock; repay_lock pay_lock
    const UNLOCK = 1;
    const LOCK = 2;

    public static $show_status = [
        self::STATUS_INIT => 'init',  //初始
        self::STATUS_READY => 'ready',//待审
        self::STATUS_ACCEPT => 'pass',//通过
        self::STATUS_REJECT => 'reject',//拒绝
        self::STATUS_UNPAID => 'pay_in',//付款中
        self::STATUS_PAID => 'paid',//已付待还
        self::STATUS_ACT_REPAY => 'actrepay_in',   //主动还款中
//        self::STATUS_ACTIVE_REPAYMENT_FAILED=>'actrepay_fail',//主动还款失败
        self::STATUS_DEDUCT_SUCCESS => 'deduct_succ',     //扣款成功
        self::STATUS_DEDUCT_FAILED => 'deduct_fail',      //扣款失败
        self::STATUS_CLOSED => 'closed',                  //关闭
        self::STATUS_DEDUCT_RUNNING => 'repay_in',        //还款中
        self::STATUS_PAYMENT_RUNNING => 'pay_in',         //付款中
        self::STATUS_PAYMENT_FAILED => 'pay_fail',        //付款失败
        self::STATUS_PAYMENT_SUCCESS => 'pay_in',         //付款成功//付款中

        self::STATUS_OVERDUE => 'overdue_in',                         //逾期中
        self::STATUS_OVERDUE_REPAY_SUCCESS => 'overdue_succ',     //逾期催收成功
        self::STATUS_OVERDUE_REPAY_FAILED => 'overdue_fail',      //逾期催收失败
        self::STATUS_OVERDUE_ACT_REPAY => 'overdue_actrepay_in',             //逾期主动还款
        self::STATUS_OVERDUE_DEDUCT_FAILED => 'overdue_actrepay_fail',    //逾期主动还款失败
        self::STATUS_OVERDUE_DEDUCT_SUCCESS => 'overdue_deduct_succ',               //逾期扣款成功
        self::STATUS_OVERDUE_DEDUCT_RUNNING => 'repay_in',                          //逾期扣款成功
        self::STATUS_REPAY_SUCCESS => 'repay_succ',   //还款成功
        self::STATUS_ADVANCE_REPAY_SUCCESS => 'repay_succ',   //还款成功
        self::STATUS_PENDING_VERIFICATION => 'ready',//需进一步审核
        self::STATUS_OVERDUE_BULL_DEDUCT => 'repay_in',           //逾期扫扣
        self::STATUS_OVERDUE_BULL_DEDUCT_RUNNING => 'repay_in',   //逾期扫扣

        self::STATUS_BULL_DEDUCT => 'repay_in',           //扫扣
        self::STATUS_BULL_DEDUCT_RUNNING => 'repay_in',   //扫扣处理中
    ];


    public $type_array = [
        self::TYPE_INST => '分期贷款',
    ];

    const DEDCUT_FAILED_COUNT = 2;
    const OVERDUE_REPAY_DAY = 500;


    //单条数据
    public function getOne($id)
    {
        return DB::select()->from('order')->where('id', '=', $id)->execute()->current();
    }

    //单条数据
    public function getOneByOrderNo($order_no)
    {
        return DB::select()->from('order')->where('order_no', '=', $order_no)->execute()->current();
    }


    //单条数据
    public function getOneByUserId($user_id, $id)
    {
        if ($user_id < 1 || $id < 1) {
            return false;
        }

        return DB::select()->from('order')->where('id', '=', $id)->and_where('user_id', '=', $user_id)->execute()->current();
    }


    /** 历史借款
     * @param $user_id
     * @param int $last_id
     * @param int $pagesize
     * @return bool | array
     */
    public function unfinished($user_id, $pagesize, $last_id = 0)
    {
        if ($user_id < 1) {
            return false;
        }
        $query = DB::select('order.id', 'order.loan_amount', 'order.status', 'order.create_time', 'bill.repay_amount', 'bill.expire_time')->from('order')
            ->join('bill', 'left')->on('order.id', '=', 'bill.order_id')
            ->where('order.user_id', '=', (int)$user_id);
        if ($last_id > 0) {
            $query->and_where('order.id', '<', $last_id);
        }

        $rs1 = $query
            ->and_where('order.status', 'not in', array_merge([self::STATUS_CLOSED, self::STATUS_REJECT], self::STATUSGROUP_SUCCESSED))
//            ->and_where('bill.start_time', '<', time())
            ->order_by('order.id', 'DESC')
            ->group_by('order.id')
            ->limit($pagesize)
            ->execute()->as_array();

        return $rs1;
    }

    public function finished($user_id, $pagesize, $last_id = 0)
    {
        if ($user_id < 1) {
            return false;
        }
        $query = DB::select('order.id', 'order.loan_amount', 'order.status', 'order.create_time')->from('order')
            ->where('order.user_id', '=', (int)$user_id);
        if ($last_id > 0) {
            $query->and_where('order.id', '<', $last_id);
        }

        $rs1 = $query
            ->and_where('order.status', 'in', array_merge([self::STATUS_CLOSED, self::STATUS_REJECT], self::STATUSGROUP_SUCCESSED))
            ->order_by('order.id', 'DESC')
            ->limit($pagesize)
            ->execute()->as_array();

        return $rs1;
    }


    /** 获取需要还款记录
     * @param $user_id
     * @return mixed
     */
    public function getNeedRepay($user_id)
    {
        $rs1 = DB::select_array()->from('order')->where('user_id', '=', (int)$user_id)->and_where('status', 'IN', self::STATUSGROUP_NEEDREPAY
        )->order_by('expire_time', 'DESC')->execute()->as_array();

        return $rs1;
    }


    /** 生成订单号 (时间戳+8位随机大小写数字)
     * @return string
     */
    public function makeNo()
    {
        return 'TC' . time() . Text::random('alpha', 3) . Text::random('alnum', 5);
    }

    /** 创建订单
     * @param $user_id
     * @param array $array
     * @return bool
     */
    public function create($user_id, $array = [])
    {
        if ($user_id < 1 || !isset($array['order_no']) ||
            !isset($array['loan_amount']) ||
            !isset($array['month']) ||
            !isset($array['type']) ||
            !isset($array['bankcard_id']) ||
            !isset($array['repay_amount']) ||
            !isset($array['charge'])
        ) {
            return false;
        }

        $data = [
            'order_no' => $array['order_no'] ? $array['order_no'] : $this->makeNo(),
            'user_id' => (int)$user_id,
            'bankcard_id' => $array['bankcard_id'],
            'loan_amount' => round($array['loan_amount'], 2),
            'pay_amount' => round($array['loan_amount'], 2),
            'repay_amount' => round($array['repay_amount'], 2),
            'status' => isset($array['status']) ? (int)$array['status'] : Model_Order::STATUS_READY,
            'type' => (int)$array['type'],
            'charge' => round($array['charge'], 2),
            'month' => (int)$array['month'],
            'name' => $array['name'],
            'mobile' => $array['mobile'],
            'identity_code' => $array['identity_code'],
            'bankcard_no' => $array['bankcard_no'] ? $array['bankcard_no'] : '',
            'create_time' => time(),
        ];

        list($new_id, $row) = DB::insert('order', array_keys($data))
            ->values(array_values($data))
            ->execute();

        return $new_id;

    }

    /** 是否首次借款
     * @param $user_id
     * @param array $status
     * @return bool
     */
    public function firstByUserId($user_id)
    {
        $rs = DB::select('id')
            ->from('order')
            ->where('user_id', '=', (int)$user_id)->and_where('status', 'NOT IN', self::STATUSGROUP_PAY_SUCCESS__NOT_IN)
            ->limit(1)->execute()->current();
        if ($rs) {
            return false;
        }

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
        if (isset($array['expire_time__start']) && $array['expire_time__start'] > 0) {
            $query->and_where('order.expire_time', '>=', $array['expire_time__start']);
        }
        if (isset($array['expire_time__end']) && $array['expire_time__end'] > 0) {
            $query->and_where('order.expire_time', '<=', $array['expire_time__end']);
        }
        //start_time (>= __start | <=__end)
        if (isset($array['start_time__start']) && $array['start_time__start'] > 0) {
            $query->and_where('order.start_time', '>=', $array['start_time__start']);
        }
        if (isset($array['start_time__end']) && $array['start_time__end'] > 0) {
            $query->and_where('order.start_time', '<=', $array['start_time__end']);
        }
        //start_time (>= __start | <=__end)
        if (isset($array['create_time__start']) && $array['create_time__start'] > 0) {
            $query->and_where('order.create_time', '>=', $array['create_time__start']);
        }
        if (isset($array['create_time__end']) && $array['create_time__end'] > 0) {
            $query->and_where('order.create_time', '<=', $array['create_time__end']);
        }


        return $query;
    }

    /**
     * 多条件查询
     * @param array $array
     * @param array $order_by
     * @param int $limit
     * @return mixed
     */
    public function getByArray($array = [], $order_by = [], $limit = 0)
    {
        $query = $this->queryBuilder(DB::select_array()->from('order'), $array);
        if ($order_by) {
            foreach ($order_by as $ob) {
                $query->order_by($ob[0], $ob[1]);
            }
        } else {
            $query->order_by('order.id', 'DESC');
        }
        if ($limit > 0) {
            $query->limit($limit);
        }
        if ($limit == 1) {
            return $query->execute()->current();
        }

        return $query->execute()->as_array();
    }

    /**
     * 多条件查询
     * @param array $array
     * @param int $limit
     * @return mixed
     */
    public function getCountByArray($array = [], $limit = 0)
    {
        $query = $this->queryBuilder(DB::select([DB::expr('count(*)'), 'count'])->from('order'), $array);

        if ($limit > 0) {
            $query->limit($limit);
        }

        $rs = $query->execute()->current();
        return isset($rs['count']) ? $rs['count'] : 0;

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



    public function dealingOrder($user_id)
    {
        return DB::select()
            ->from('order')
            ->where('user_id', '=', (int)$user_id)->and_where('status', 'IN', self::STATUSGROUP_PAY_SUCCESS__NOT_IN)
            ->execute()
            ->as_array();
    }


    //未完成订单总金额
    public function unfinishedAmount($user_id)
    {
        $rs = DB::select([DB::expr('SUM(loan_amount)'), 'amount'])
            ->from('order')
            ->where('user_id', '=', intval($user_id))
            ->and_where('status', 'IN', self::STATUSGROUP_UNFINISHED)
            ->execute()
            ->current();

        return isset($rs['amount']) ? $rs['amount'] : 0;
    }


    /** 格式化内容
     * @param $array
     * @return array|bool
     */
    public function format($array)
    {
        return $array ? [
            'id' => isset($array['id']) && $array['id'] > 0 ? $array['id'] : '0',
            'type_name' => isset($array['type']) && isset($this->type_array[$array['type']]) ? $this->type_array[$array['type']] : '',
            'user_id' => isset($array['user_id']) && $array['user_id'] > 0 ? $array['user_id'] : '0',
            'loan_amount' => isset($array['loan_amount']) && $array['loan_amount'] > 0 ? $array['loan_amount'] : '0',
            'repay_amount' => isset($array['repay_amount']) && $array['repay_amount'] > 0 ? $array['repay_amount'] : '0',
            'repaid_amount' => isset($array['repaid_amount']) && $array['repaid_amount'] > 0 ? $array['repaid_amount'] : '0',
            'status' => isset($array['status']) && isset(self::$show_status[$array['status']]) ? self::$show_status[$array['status']] : '',
            'status_message' => isset($array['status_message']) ? $array['status_message'] : '',
            'charge' => isset($array['charge']) && $array['charge'] > 0 ? $array['charge'] : '0',
            'month' => isset($array['month']) && $array['month'] > 0 ? $array['month'] : '0',
            'start_time' => isset($array['start_time']) && $array['start_time'] > 0 ? date('Y/m/d', $array['start_time']) : '',
            'create_time' => isset($array['create_time']) && $array['create_time'] > 0 ? date('Y/m/d', $array['create_time']) : '',
            'expire_time' => isset($array['expire_time']) && $array['expire_time'] > 0 ? date('Y/m/d', $array['expire_time'] - 1) : '', //字段记录的是过期时间,过期时间为次日凌晨0点0分,所以需要-1秒才能算出还款日的最后时间
            'start_date' => isset($array['start_time']) && $array['start_time'] > 0 ? date('Y/m/d', $array['start_time']) : '',
            'end_date' => isset($array['expire_time']) && $array['expire_time'] > 0 ? date('Y/m/d', $array['expire_time'] - 1) : '', //到期日期
            'mobile' => isset($array['mobile']) ? $array['mobile'] : '',
            'name' => isset($array['name']) ? $array['name'] : '',
            'bankcard_id' => isset($array['bankcard_id']) ? $array['bankcard_id'] : '0',
            'bankcard_no' => isset($array['bankcard_no']) ? Lib::factory('String')->strShieldMiddle($array['bankcard_no'], 4, 4) : '',
            //'active_repayment_time'=>isset($array['active_repayment_time']) ? (int)$array['active_repayment_time'] : 0 ,
            //'active_repayment_count'=>isset($array['active_repayment_count']) ? (int)$array['active_repayment_count'] : 0 ,
        ] : false;

    }


}