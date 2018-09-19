<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 17/10/13
 * Time: 上午7:16
 *
 * 已产品为单位的授信申请记录model
 */
class Model_Credit_ApplyRecord extends Model_Database {

    //固定项目为每个产品线都会有的。
    const TYPE_APPLY_LOAN = 1;//申请借款 (固定)


    /** 添加
     * @param int $user_id
     * @param int $order_type
     * @param string $credit_code
     * @return bool | int
     */
    public function create($array=[]) {

        $user_id = isset($array['user_id']) ?  (int)$array['user_id'] : 0;
        $order_type = isset($array['order_type']) ?  (int)$array['order_type'] : 0;
        $apply_date = isset($array['apply_date']) ?  (string)$array['apply_date'] : date('Y-m-d');
        $apply_type = isset($array['apply_type']) ?  (int)$array['apply_type'] : 0;
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;
        if( $user_id<1 || $order_type<1 || $apply_type<1 ){
            return FALSE;
        }

        list($insert_id,$affected_rows)=DB::insert("credit_apply_record",array('user_id','order_type','apply_date','apply_type','create_time'))
            ->values(array( $user_id, $order_type, $apply_date, $apply_type, $create_time ))
            ->execute();
        return $insert_id;
    }


    /** 查询单条
     * @param array $array
     * @return mixed
     */
    public function get_one_by_array($array=[]){
        return $this->get_by_array($array,1);
    }

    /** 查询
     * @param array $array
     * @return mixed
     */
    public function get_by_array($array=[],$row=0) {
        if(count($array)<1) return FALSE;
        $query =DB::select()->from('credit_apply_record');

        if(isset($array['user_id'])){
            $query->and_where('user_id','=',$array['user_id']);
        }
        if(isset($array['order_type'])){
            $query->and_where('order_type','=',$array['order_type']);
        }
        if(isset($array['apply_type'])){
            $query->and_where('apply_type','=',$array['apply_type']);
        }
        if(isset($array['apply_date'])){
            $query->and_where('apply_date','=',$array['apply_date']);
        }
        if($row>0){
            $query->limit($row);
        }
        if($row==1){
            return $query->execute()->current();
        }else{
            return $query->execute()->as_array();
        }

    }



}