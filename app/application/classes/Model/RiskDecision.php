<?php

defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 2018/1/23
 * Time: 下午4:08
 */
class Model_RiskDecision extends Model_Database{

    public function addData($user_id,$request_no,$request_data,$provider,$action)
    {
        list($decision_id,$a) = DB::insert("risk_decision",array('user_id','request_no','request_data','provider','action','create_time'))
            ->values(array($user_id,$request_no,json_encode($request_data),$provider,$action,time()))
            ->execute();
        list($decision_index,$a) = DB::insert("risk_decision_index",array('user_id','decision_id','request_no','provider','action','create_time'))
            ->values(array($user_id,$decision_id,$request_no,$provider,$action,time()))
            ->execute();
        return array('decision_id'=>$decision_id,'id'=>$decision_index);
    }

    public function setCheck($arr = null,$check_no,$result = array())
    {
        DB::update('risk_decision')->set(array('check_no'=>$check_no,'response_data'=>json_encode($result)))->where('id','=',$arr['decision_id'])->execute();

        if(isset($result['isFlag'])){
            $re2 = DB::update('risk_decision_index')->set(array('check_no'=>$check_no,'resume'=>json_encode($result)))->where('id','=',$arr['id'])->execute();
        }else{
            $re2 = DB::update('risk_decision_index')->set(array('check_no'=>$check_no))->where('id','=',$arr['id'])->execute();
        }
        if($re2){
            return true;
        }
        return false;
    }

    public function getOneByNo($filed,$value)
    {
        return DB::select("*")->from("risk_decision_index")->where($filed,'=',$value)->execute()->current();
    }

    public function getOne($user_id,$provider,$action)
    {
        return DB::select("*")->from("risk_decision_index")->where('user_id','=',$user_id)->and_where('provider','=',$provider)->and_where('action','=',$action)->order_by('create_time','desc')->execute()->current();
    }


    public function recordResponse($id,$data)
    {
        return DB::update('risk_decision')->set(array('response_data'=>json_encode($data)))->where('id','=',$id)->execute();
    }


}