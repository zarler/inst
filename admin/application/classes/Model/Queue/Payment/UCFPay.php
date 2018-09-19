<?php
/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2017/11/14
 * Time: 下午3:09
 */
class Model_Queue_Payment_UCFPay extends Model_Database
{

    //status 队列状态
    const SUBMIT_NONE = 0;      //无
    const SUBMIT_READY = 1;     //待执行
    const SUBMIT_RUNNING = 2;   //执行中
    const SUBMIT_SUCCESS = 3;   //完成
    const SUBMIT_FAILED = 4;    //失败
    const SUBMIT_CLOSED = 5;    //关闭
    const SUBMIT_SUSPENDED = 6; //挂起

    //query 等待查询
    const QUERY_NONE = 0;   //无
    const QUERY_WAIT = 1;   //等待查询
    const QUERY_RUNNING = 2;   //查询中
    const QUERY_SUCCESS = 3;   //查询成功
    const QUERY_FAILED = 4;    //查询失败
    const QUERY_CLOSED = 5;    //查询关闭(提交时直接扣款成功,超过次数上线,订单状态非扣款中,或者手工关闭)
    const QUERY_SUSPENDED = 6; //挂起
    const QUERY_UNFINISHED = 7; //未完成

//    //查询统计
//    public function get_total($array=array()) {
//        $query=DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('user');
//        if(count($array)>0) {
//            $query = $this->query_builder($query,$array);
//        }
//        $rs=$query->execute()->current();
//        return isset($rs['total']) ? $rs['total'] : 0 ;
//    }

    //查询统计
    public function getTotal($array = null) {

        $query = DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('finance_payment_ucfpay_queue') ;
        //处理中
        if(isset($array['Process']) && $array['Process']){
            $query->where('query_status', 'not in', array(self::QUERY_SUCCESS,self::QUERY_FAILED,self::QUERY_CLOSED,self::QUERY_SUSPENDED));
        }
        //扣款成功
        if(isset($array['Success']) && $array['Success']){
            $query->where('query_status', 'in', array(self::QUERY_SUCCESS));
        }
        //扣款失败
        if(isset($array['Fail']) && $array['Fail']){
            $query->where('query_status', 'in', array(self::QUERY_FAILED,self::QUERY_CLOSED,self::QUERY_SUSPENDED));
        }
        if(count($array)>0) {
            $query = $this->queryBuilder($query,$array);
        }
        $rs=$query->execute()->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }

    //查询分页
    public function getList($array = array(), $perpage = 20, $page = 1) {
        $query = DB::select();
        $query->from('finance_payment_ucfpay_queue');
        //处理中
        if(isset($array['Process']) && $array['Process']){
            $query->where('query_status', 'not in', array(self::QUERY_SUCCESS,self::QUERY_FAILED,self::QUERY_CLOSED,self::QUERY_SUSPENDED));
        }
        //扣款成功
        if(isset($array['Success']) && $array['Success']){
            $query->where('query_status', 'in', array(self::QUERY_SUCCESS));
        }
        //扣款失败
        if(isset($array['Fail']) && $array['Fail']){
            $query->where('query_status', 'in', array(self::QUERY_FAILED,self::QUERY_CLOSED,self::QUERY_SUSPENDED));
        }
        if(count($array)>0) {
            $query = $this->queryBuilder($query,$array);
        }
        $query->execute()->as_array();
        if ($page < 1) {
            $page = 1;
        }
        $rs = $query->offset($perpage * ($page - 1))->limit($perpage)->execute()->as_array();
        return $rs;
    }

    //构造查询条件
    private function queryBuilder($query, $array = array()) {

        if (isset($array['user_id']) && $array['user_id']) {
            $query->where('order.user_id', '=', trim($array['user_id']));
        }
        if (isset($array['order_id']) && $array['order_id']) {
            if(is_array($array['order_id'])){
                $query->where('order.id', 'in', $array['order_id']);
            }else{
                $query->where('order.id', '=', trim($array['order_id']));
            }
        }
        if (isset($array['order_no']) && $array['order_no']) {
            $query->where('order.order_no', '=', trim($array['order_no']));
        }
        if (isset($array['mobile']) && $array['mobile']) {
            $query->where('order.mobile', '=', trim($array['mobile']));
        }
        if (isset($array['name']) && $array['name']) {
            $query->where('order.name', '=', $array['name']);
        }

        if (isset($array['identity_code']) && $array['identity_code']) {
            $query->where('user.identity_code', '=', trim($array['identity_code']));
            $array['join'] = 'user';
        }

        if (isset($array['username']) && $array['username']) {
            $query->where('user.username', '=', $array['username']);
        }
        if (isset($array['email']) && $array['email']) {
            $query->where('user.email', '=', $array['email']);
        }
        if (isset($array['creditcard_hold_id'])) {
            $query->where('order.creditcard_hold_id', '>', trim($array['creditcard_hold_id']));
        }
        if (isset($array['status_in'])) {
            if (is_array($array['status_in'])) {
                $query->where('order.status', 'IN', $array['status_in']);
            }else{
                $query->where('order.status', '=', trim($array['status_in']));
            }
        }

        if (isset($array['type'])) {
            $query->where('order.type', '=', trim($array['type']));
        }

        if (isset($array['expire_time']) && $array['expire_time']) {
            $query->and_where('order.expire_time', 'BETWEEN', array($array['expire_time'], $array['expire_time'] + 86399));
        }

        if (isset($array['expire_time_start']) && $array['expire_time_start']) {
            $query->and_where('order.expire_time', '>=', $array['expire_time_start']);
        }

        if (isset($array['expire_time_end']) && $array['expire_time_end']) {
            $query->and_where('order.expire_time', '<', $array['expire_time_end']);
        }


        if(isset($array['time_start'])&& $array['time_start']>0) {
            $query->and_where('order.expire_time','>=',$array['time_start']);
        }
        if(isset($array['time_end'])&& $array['time_end']>0) {
            $query->and_where('order.expire_time','<=',$array['time_end']);
        }

        //统一链表
        if (isset($array['join'])) {
            $query->join('order')->on('order.id','=','finance_payment_ucfpay_queue.order_id');
        }

        return $query;
    }
}