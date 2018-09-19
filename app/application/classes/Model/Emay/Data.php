<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 2018/1/9
 * Time: 下午5:57
 */
class Model_Emay_Data extends Model
{
    const EMAY_MODULE = 'riskservice';
    const STATUS_YES = 1;
    const STATUS_NO = 2;

    public function create($token)
    {
        return DB::insert("emay_token",array('token','date'))->values(array($token,date("Y-m-d",time())))->execute();
    }

    public function getOne()
    {
        return DB::select("*")->from('emay_token')->where('date','=',date("Y-m-d",time()))->order_by('id','DESC')->execute()->current();
    }

    public function add_data($user_id,$request_data,$response_data)
    {
        return DB::insert("emay_data",array('user_id','request_data','response_data','create_time'))->values(array($user_id,json_encode($request_data),json_encode($response_data),time()))->execute();
    }

    public function get_data($user_id)
    {
        return DB::select("*")->from('emay_data')->where('user_id','=',$user_id)->order_by('id','DESC')->execute()->current();
    }

    public function add_short($user_id,$result)
    {
        return DB::insert("emay_short",array('user_id','module','status','create_time'))->values(array($user_id,self::EMAY_MODULE,$result,time()))->execute();
    }
}