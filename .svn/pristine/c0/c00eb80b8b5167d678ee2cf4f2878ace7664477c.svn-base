<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: wangxuesong
 * Date: 16/6/17
 * Time: 下午5:09
 *
 * 操作 curl 请求第三方接口聚信立系统异常运营商异常记录以及 请求超时记录
 *
 */
class Model_CreditInfo_SystemExceptions extends Model_Database{

    const OVERTIME= 600;		//超时时间


    /**
     * 聚信立 各类异常 以及超时 等操作处理
     * @param $userId
     * @param $data
     * @return int
     */

    public  function  systemExceptions($userId,$data){


        $systemExceptionList = DB::select()->from('juxinli_system_exceptions')->where('user_id','=',$userId)->limit(1)->execute()->current();

        $speaker_map = array('user_id','create_time','update_time','rsp_data','frequency');
        $speaker_data = array($userId,time(),time(),json_encode($data),1);
        if($systemExceptionList){

            $update_time = isset($systemExceptionList['update_time'])?$systemExceptionList['update_time']:0;
            $time        = time()-$update_time;
            if($time> Model_CreditInfo_SystemExceptions::OVERTIME){
                DB::delete('juxinli_system_exceptions')->where('user_id','=',$userId)->execute();
                DB::insert('juxinli_system_exceptions',$speaker_map)->values($speaker_data)->execute();
                return 1;
            }else{
                DB::update('juxinli_system_exceptions')->set(array('frequency' => DB::expr('frequency + 1')))->where('user_id','=',$userId)->execute();
                return 2;
            }

        }
        DB::insert('juxinli_system_exceptions',$speaker_map)->values($speaker_data)->execute();
        return 1;
    }

}