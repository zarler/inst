<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 17/10/13
 * Time: 上午7:16
 *
 * 已产品为单位的授信通过记录model
 */
class Model_Credit_PassRecord extends Model_Database {

    //固定项目为每个产品线都会有的。
    const TYPE_CREDIT_PASS = 1;//授信通过 (固定)
    const TYPE_SCORE_PASS = 2;//评分决策通过 (固定)
    //根据每个产品线的差别定义 其他授信通过项目
    const TYPE_FAST_LOAN_EVENT_CREDIT_AF_PASS = 10001;//极速贷授信反欺诈
    const TYPE_FULL_PREAUTH_LOAN_EVENT_CREDIT_AF_PASS = 10002;//100%担保借款授信反欺诈
    const TYPE_PREAUTH_LOAN_EVENT_CREDIT_AF_PASS = 10003;//担保(部分)借款授信反欺诈

    /** 添加
     * @param int $user_id
     * @param int $order_type
     * @param string $credit_code
     * @return bool | int
     */
    public function create($array=[]) {

        $user_id = isset($array['user_id']) ?  (int)$array['user_id'] : 0;
        $order_type = isset($array['order_type']) ?  (int)$array['order_type'] : 0;
        $pass_type = isset($array['pass_type']) ?  (int)$array['pass_type'] : 0;
        $credit_code = isset($array['credit_code']) ?  (string)$array['credit_code'] : '';
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;
        if( $user_id<1 || $order_type<1 || !$credit_code ){
            return FALSE;
        }

        list($insert_id,$affected_rows)=DB::insert("credit_pass_record",array('user_id','order_type','pass_type','credit_code','create_time'))
            ->values(array( $user_id, $order_type, $pass_type, $credit_code, $create_time ))
            ->execute();
        return $insert_id;
    }


    /** 查询单条
     * @param array $array
     * @return mixed
     */
    public function get_one_by_array($array=[]) {
        if(count($array)<1) return FALSE;
        $query =DB::select()->from('credit_pass_record');

        if(isset($array['user_id'])){
            $query->and_where('user_id','=',$array['user_id']);
        }
        if(isset($array['order_type'])){
            $query->and_where('order_type','=',$array['order_type']);
        }
        if(isset($array['pass_type'])){
            $query->and_where('pass_type','=',$array['pass_type']);
        }
        if(isset($array['credit_code'])){
            $query->and_where('credit_code','=',$array['credit_code']);
        }
        return $query->limit(1)->execute()->current();
    }



}