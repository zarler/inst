<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 18/1/12
 * Time: 上午10:57
 */
class Model_Bill extends Model_Database {

    //订单类型
    const ORDER_TYPE_INST = 6;                //分期贷款

    //订单状态
    const STATUS_INIT = 0;                           	//订单初始
    const STATUS_UNPAID = 4;                            //待付款
    const STATUS_PAID = 5;                             	//已付款,待还
    const STATUS_ACT_REPAY = 6;                    		//主动还款
    const STATUS_DEDUCT_SUCCESS = 8;                    //扣款成功  TO:61
    const STATUS_DEDUCT_FAILED = 9;                     //扣款失败
	const STATUS_DEDUCT_RUNNING = 11;        			//扣款处理中
    const STATUS_CLOSED = 10;               			//强制关闭
	const STATUS_PENDING_VERIFICATION = 15;             //需进一步审核

    const STATUS_OVERDUE = 50;      					//逾期
    const STATUS_OVERDUE_REPAY_SUCCESS = 51;        	//逾期催缴成功
    const STATUS_OVERDUE_REPAY_FAILED = 52;        		//逾期催缴失败
	const STATUS_OVERDUE_ACT_REPAY = 56;                //逾期主动还款
	const STATUS_OVERDUE_DEDUCT_FAILED = 59;            //逾期主动还款失败
    const STATUS_OVERDUE_DEDUCT_SUCCESS = 58;           //逾期扣款成功
    const STATUS_OVERDUE_DEDUCT_RUNNING = 511;          //逾期扣款处理中

    const STATUS_REPAY_SUCCESS = 61;                	//订单还款成功
    const STATUS_ADVANCE_REPAY_SUCCESS = 71;        	//提前还款成功

    /*扫扣*/
    const STATUS_BULL_DEDUCT = 30;                      //扫扣
    const STATUS_BULL_DEDUCT_RUNNING = 31;              //扫扣处理中
    const STATUS_OVERDUE_BULL_DEDUCT = 530;             //逾期扫扣
    const STATUS_OVERDUE_BULL_DEDUCT_RUNNING = 531;		//逾期扫扣处理中


    //状态组,用于简化DB WHERE 增强一致性
    const STATUSGROUP_PAID = [  //待还款中的正常订单
        self::STATUS_PAID,
        self::STATUS_ACT_REPAY,
        self::STATUS_DEDUCT_SUCCESS,
        self::STATUS_DEDUCT_FAILED,
        self::STATUS_DEDUCT_RUNNING,
        self::STATUS_BULL_DEDUCT,
        self::STATUS_BULL_DEDUCT_RUNNING,
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

    const STATUSGROUP_UNFINISHED =[ //未完成
        self::STATUS_INIT,
        self::STATUS_UNPAID,
        self::STATUS_PAID,
        self::STATUS_ACT_REPAY,
        self::STATUS_DEDUCT_SUCCESS,
        self::STATUS_DEDUCT_FAILED,
        self::STATUS_DEDUCT_RUNNING,
        self::STATUS_OVERDUE,
        self::STATUS_OVERDUE_REPAY_FAILED,
        self::STATUS_OVERDUE_ACT_REPAY,
        self::STATUS_OVERDUE_DEDUCT_FAILED,
        self::STATUS_OVERDUE_DEDUCT_SUCCESS,
        self::STATUS_OVERDUE_DEDUCT_RUNNING,
        self::STATUS_OVERDUE_BULL_DEDUCT,
        self::STATUS_OVERDUE_BULL_DEDUCT_RUNNING,
        self::STATUS_BULL_DEDUCT,
        self::STATUS_BULL_DEDUCT_RUNNING,
    ];

    const STATUSGROUP_DEDUCTING =[  //扣款处理
        self::STATUS_ACT_REPAY,
        self::STATUS_DEDUCT_SUCCESS,
        self::STATUS_DEDUCT_FAILED,
        self::STATUS_DEDUCT_RUNNING,
        self::STATUS_OVERDUE_ACT_REPAY,
        self::STATUS_OVERDUE_DEDUCT_FAILED,
        self::STATUS_OVERDUE_DEDUCT_SUCCESS,
        self::STATUS_OVERDUE_DEDUCT_RUNNING,
    ];



    //记录锁,防止重复关键性操作
    //表中 pre_auth_lock; repay_lock pay_lock
    const UNLOCK = 1;
    const LOCK = 2;



    public static $status_array = [
        self::STATUS_INIT 					        => 	'账单初始化',
        self::STATUS_UNPAID 				        => 	'待付',
        self::STATUS_PAID 					        => 	'待还',
        self::STATUS_ACT_REPAY 				        => 	'主动还款',
        self::STATUS_DEDUCT_FAILED 			        => 	'扣款失败',
        self::STATUS_DEDUCT_SUCCESS 		        => 	'还款成功',
        self::STATUS_DEDUCT_RUNNING 		        => 	'扣款处理中',
        self::STATUS_CLOSED 				        => 	'强制关闭',
		self::STATUS_PENDING_VERIFICATION 	        => 	'需进一步审核',

        self::STATUS_OVERDUE 				        => 	'逾期',
        self::STATUS_OVERDUE_REPAY_SUCCESS 	        => 	'逾期催缴成功',
        self::STATUS_OVERDUE_REPAY_FAILED 	        => 	'逾期催缴失败',
        self::STATUS_OVERDUE_ACT_REPAY 		        => 	'逾期主动还款',
        self::STATUS_OVERDUE_DEDUCT_FAILED 	        => 	'逾期还款失败',
        self::STATUS_OVERDUE_DEDUCT_SUCCESS         => 	'逾期扣款成功',
		self::STATUS_OVERDUE_DEDUCT_RUNNING         =>	'逾期扣款处理中',

		self::STATUS_REPAY_SUCCESS 			        => 	'订单还款成功',
		self::STATUS_ADVANCE_REPAY_SUCCESS	        =>	'提前还款成功',

        self::STATUS_BULL_DEDUCT                    => '扫扣',
        self::STATUS_BULL_DEDUCT_RUNNING            => '扫扣处理中',
        self::STATUS_OVERDUE_BULL_DEDUCT            => '逾期扫扣',
        self::STATUS_OVERDUE_BULL_DEDUCT_RUNNING    => '逾期扫扣处理中',
    ];


    /**
     * 状态名对应
     * @param int $status_key
     * @return mixed|null
     */
    public function getStatusName($status_key = 0)
    {
        return isset(self::$status_array[$status_key]) ? self::$status_array[$status_key] : NULL;
    }


    /**
     * 更改 & 记日志
     * @param int $bill_id
     * @param null $array
     * @return bool
     */
    public function update($bill_id = 0, $array = null)
    {
        if ($bill_id < 1 || !$array) {
            return false;
        }
        unset($array['id']);
        $dbc = DB::update('bill')->set($array)->where('id', '=', intval($bill_id));
        Model::factory('Bill_ModifyLog')->log($bill_id, ['method' => 'update', 'data' => $dbc->compile()]);
        $affected_rows = $dbc->execute();
        return $affected_rows !== null;
    }


    /**
     * 单条数据
     * @param $bill_id
     * @return mixed
     */
    public function getOne($bill_id)
    {
        return DB::select()->from('bill')->where('id', '=', intval($bill_id))->execute()->current();
    }


    /**
     * 某订单下全部账单数据
     * @param $order_id
     * @return mixed
     */
    public function getByOrderId($order_id)
    {
        return $this->getByArray(['order_id'=>$order_id]);
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
        $query = $this->queryBuilder(DB::select_array()->from('bill'),$array);
        if($order_by){
            foreach ($order_by as $ob){
                $query->order_by($ob[0],$ob[1]);
            }
        }else{
            $query->order_by('bill.id', 'ASC');
        }
        if($limit>0){
            $query->limit($limit);
        }
        if($limit==1) {
            return $query->execute()->current();
        }
        return $query->execute()->as_array();
    }


    //不能删除
    public function delete($bill_id = 0) {
        return TRUE;
    }


    //构造查询条件
    protected function queryBuilder($query, $array = array())
    {

        if (isset($array['order_id']) && $array['order_id']) {
            if (is_array($array['order_id'])) {
                $query->where('bill.order_id', 'in', $array['order_id']);
            }else{
                $query->where('bill.order_id', '=', $array['order_id']);
            }
        }

        if (isset($array['user_id']) && $array['user_id']) {
            if (is_array($array['user_id'])) {
                $query->where('bill.user_id', 'in', $array['user_id']);
            }else{
                $query->where('bill.user_id', '=', $array['user_id']);
            }
        }

        if (isset($array['bill_id']) && $array['bill_id']) {
            if (is_array($array['bill_id'])) {
                $query->where('bill.id', 'in', $array['bill_id']);
            } else {
                $query->where('bill.id', '=', $array['bill_id']);
            }
        }

        if (isset($array['status'])) {
            if (is_array($array['status'])) {
                $query->where('bill.status', 'IN', $array['status']);
            } else {
                $query->where('bill.status', '=', $array['status']);
            }
        }

        //注意expire_time参数 (>= expire_time__start | <=expire_time__end)
        if (isset($array['expire_time__start'])  && $array['expire_time__start']>0) {
            $query->and_where('bill.expire_time', '>=', $array['expire_time__start']);
        }
        if (isset($array['expire_time__end'])  && $array['expire_time__end']>0) {
            $query->and_where('bill.expire_time', '<=', $array['expire_time__end']);
        }
        //start_time (>= __start | <=__end)
        if (isset($array['start_time__start']) && $array['start_time__start'] > 0) {
            $query->and_where('bill.start_time', '>=', $array['start_time__start']);
        }
        if (isset($array['start_time__end'])  && $array['start_time__end']>0) {
            $query->and_where('bill.start_time', '<=', $array['start_time__end']);
        }
        //start_time (>= __start | <=__end)
        if (isset($array['create_time__start']) && $array['create_time__start'] > 0) {
            $query->and_where('bill.create_time', '>=', $array['create_time__start']);
        }
        if (isset($array['create_time__end'])  && $array['create_time__end']>0) {
            $query->and_where('bill.create_time', '<=', $array['create_time__end']);
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
    public function getList($array = [], $order_by=[], $perpage = 20, $page = 1)
    {

        $query = $this->queryBuilder(DB::select_array()->from('bill'),$array);
        if($order_by){
            foreach ($order_by as $ob){
                $query->order_by($ob[0],$ob[1]);
            }
        }else{
            $query->order_by('bill.id', 'ASC');
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
    public function getTotal($array = array())
    {
        $query = DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('bill');
        if (count($array) > 0) {
            $query = $this->query_builder($query, $array);
        }
        $rs = $query->execute()->current();
        //echo $query->__toString();
        return isset($rs['total']) ? $rs['total'] : 0;
    }


    /**
     * 还款成功后
     * @param $bill_id
     * @return bool
     * 订单还款成功后的操作,必须在事务中使用
     */
    public function repaySuccessAfter($bill_id)
    {
        if ( $bill = $this->get_one($bill_id)) {
            return $this->update($bill_id, array('repaid_time'=>time(), 'repay_lock' => Model_Bill::LOCK));
        }
    }



    /**
     * 账单关闭后
     * @param $bill_id
     * @return bool
     * 预授权冻结超时后 处理的后续操作
     */
    public function closeAfter($bill_id)
    {
        if ($bill = $this->getOne($bill_id)) {
            return true;
        }
    }


    /**
     * 账单还款统一调用
     * @param $bill_id
     * @param $amount
     * @return bool
     * 请使用try/catch 包裹此类错做
     *
     * Model::factory('Bill')->repaid(1,200.00,['deduct_id'=>123]);
     */
    public function repaid($bill_id,$amount,$extend=[]){
        if ($bill = $this->getOne($bill_id)) {

            if(in_array($bill['status'],Model_Bill::STATUSGROUP_OVERDUE)) {
                //逾期使用罚息model,自动同步bill数据
                $res =  Model::factory('Bill_Penalty')->repaid($bill_id,$amount);
                if(is_array($res)){
                    return Model::factory('Bill_RepaidLog')->log($bill['id'],[
                        'user_id'           => $bill['user_id'],
                        'order_id'          => $bill['order_id'],
                        'dateline'          => date('Y-md-d'),
                        'overdue'           => Model_Bill_RepaidLog::OVERDUE_IN,
                        'deduct_id'         => isset($extend['dedcut_id']) ? $extend['dedcut_id'] : 0,
                        'repaid_amount'     => $res['repaid_amount'],
                        'repaid_late_fee'   => $res['repaid_late_fee'],
                        'repaid_penalty'    => $res['repaid_penalty'],
                        'repaid_management' => $res['repaid_management'],
                        'repaid_interest'   => $res['repaid_interest'],
                        'repaid_principal'  => $res['repaid_principal'],
                        'repaid_damage'     => $res['repaid_damage'],
                    ]);
                }
            }else{
                //正常还款使用repaid model,自动同步bill数据
                $res = Model::factory('Bill_Repaid')->in($bill_id,$amount);
                if(is_array($res)){
                    return Model::factory('Bill_RepaidLog')->log($bill['id'],[
                        'user_id'           => $bill['user_id'],
                        'order_id'          => $bill['order_id'],
                        'dateline'          => date('Y-md-d'),
                        'overdue'           => Model_Bill_RepaidLog::NOT_OVERDUE,
                        'deduct_id'         => isset($extend['dedcut_id']) ? $extend['dedcut_id'] : 0,
                        'repaid_amount'     => $res['repaid_amount'],
                        'repaid_late_fee'   => $res['repaid_late_fee'],
                        'repaid_penalty'    => $res['repaid_penalty'],
                        'repaid_management' => $res['repaid_management'],
                        'repaid_interest'   => $res['repaid_interest'],
                        'repaid_principal'  => $res['repaid_principal'],
                        'repaid_damage'     => $res['repaid_damage'],
                    ]);
                }
            }
        }
        return false;
    }








}
