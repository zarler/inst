<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/23
 * Time: 上午1:33
 *
 * APP端短信列表
 *  主表 tc_app_smsrecord
 *  目前只有android支持
 */
class Model_App_SMSRecord extends Model_Database {


    const TYPE_RECEIVE = 1;         //类型 接收
    const TYPE_SEND = 2;            //类型 发送


    /** 创建短信记录
     * @param $user_id
     * @param array $array
     * @return bool
     */
//    public function create($user_id,$array=array()){
//        if( $user_id<1 || !(isset($array['number']) && $array['number'] ) ){
//            return FALSE;
//        }
//
//        $ts = Tool::factory('String');
//        $user_id = (int)$user_id;
//        $number = mb_substr( $array['number'], 0, 50, 'utf8') ;
//        $type = isset($array['type']) ? $array['type'] : 0;
//        $message =  mb_substr( trim($ts->filter_utf8($array['message'])), 0, 400, 'utf8') ;
//        $dateline = isset($array['dateline']) ? mb_substr( trim($ts->filter_utf8($array['dateline'])), 0, 30, 'utf8') : '';
//        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;
//        $mobile = Tool::factory('String')->string2mobile($array['number']);
//
//        list($insert_id,$affected_rows)=DB::insert("app_smsrecord",array('user_id','number','type','message','dateline','create_time','mobile'))
//            ->values(array( $user_id, $number, $type, $message, $dateline, $create_time, $mobile))
//            ->execute();
//
//        return $insert_id;
//
//    }
    public function create($user_id,$json)
    {
        if($user_id<1){
            return false;
        }
        list($insert_id,$affected_rows)=DB::insert("app_smsrecord_data",array('user_id','sms','create_time'))->values(array($user_id,$json,time()))->execute();
        return $insert_id;

    }

    /** 是否有记录
     * @param $user_id
     * @return mixed
     */
    public function has($user_id){
        return DB::select()->from('app_smsrecord_data')->where('user_id','=',(int)$user_id)->limit(1)->execute()->current();
    }


    /** 读取短信记录
     * @param $user_id
     * @return mixed
     */
    public function get_by_user_id($user_id){
        return DB::select()->from('app_smsrecord_data')->where('user_id','=',(int)$user_id)->order_by('id','DESC')->execute()->current();
    }


    /** 读取短信记录(只要手机号码记录)
     * @param $user_id
     * @return mixed
     */
    public function get_mobile_by_user_id($user_id){
        return DB::select()->from('app_smsrecord')->where('user_id','=',(int)$user_id)->and_where('mobile','is',DB::expr('not null'))->execute()->as_array();
    }



}