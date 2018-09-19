<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 15/12/15
 * Time: 上午11:43
 *
 * 权限MODEL
 */

class Model_Finance_Profile extends Model_Database {

    protected $_allow_update_column = array('inst_amount','total_loan_amount');//只允许更新

    public function getOne($user_id=0) {
        if($user_id<1) {
            return false;
        }
        return DB::select()->from('finance_profile')->where('user_id','=',$user_id)->execute()->current();
    }

    //添加
    public function create($user_id=0,$array=array()) {
        if(!$this->getOne($user_id)) {
            return FALSE;
        }

        list($insert_id,$affected_rows) = DB::insert("finance_profile",array('user_id','max_amount','loan_amount','total_loan_amount'))
            ->values( array($user_id,0,0,0,0,0)	)->execute();
        if($insert_id) {
            //清除TCCache_User
            Lib::factory('TCCache_User')->user_id($user_id)->remove();
            return $insert_id;
        }
        return FALSE;
    }


    //更改
    public function update($user_id=0,$array=array()) {
        if(!$this->getOne($user_id)) {
            return FALSE;
        }
        $columns = Lib::factory('Array')->keyFilter($array,$this->_allow_update_column);
        if(count($columns)>0) {
            $affected_rows = DB::update('finance_profile')->set($columns)->where('user_id','=',$user_id)->execute();
            //清除TCCache_User
            Lib::factory('TCCache_User')->user_id($user_id)->remove();
            return $affected_rows!==NULL;
        }
        return FALSE;
    }




    //增加额度
    public function amount_add($user_id=0,$amount_field,$amount=0.00){
        return $this->update($user_id,array($amount_field=>DB::expr($amount_field.' +'.$amount)));
    }

    //减少额度
    public function amount_sub($user_id=0,$amount_field,$amount=0.00){
        return $this->update($user_id,array($amount_field=>DB::expr($amount_field.' -'.$amount)));
    }



}