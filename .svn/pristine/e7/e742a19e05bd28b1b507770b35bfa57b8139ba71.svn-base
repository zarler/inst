<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/26
 * Time: 上午1:33
 *
 * APP端通话记录
 *  主表 tc_app_callhistory
 *  目前只有android支持
 */
class Model_App_CallHistory extends Model_Database {


    const TYPE_OUT = 1;             //类型 呼出
    const TYPE_IN = 2;              //类型 呼入

    const CONNECTED_YES = 1;        //接通
    const CONNECTED_NO = 2;         //未接通


    /** 创建短信记录
     * @param $user_id
     * @param array $array
     * @return bool
     */
//    public function create($user_id,$array=array()){
//        if( $user_id<1 || !isset($array['from_number']) || !isset($array['to_number']) ||
//            !isset($array['type']) || !isset($array['connected']) ){
//            return FALSE;
//        }
//
//        $user_id = (int)$user_id;
//        $from_number = mb_substr($array['from_number'], 0, 50, 'utf8');
//        $to_number = mb_substr($array['to_number'], 0, 50, 'utf8');
//        $type = (int) $array['type'];
//        $connected = (int) $array['connected'];
//        $connect_time = isset($array['connect_time']) ? (int)$array['connect_time']  : 0 ;
//        $dateline = isset($array['dateline']) ? mb_substr($array['dateline'], 0, 30, 'utf8') : '';
//        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;
//
//        $mobile='';
//        if($type==self::TYPE_OUT){
//            $mobile = Tool::factory('String')->string2mobile($to_number);
//        }else{
//            $mobile = Tool::factory('String')->string2mobile($from_number);
//        }
//
//        list($insert_id,$affected_rows)=DB::insert("app_callhistory",array('user_id','from_number','to_number','type','connected','connect_time','dateline','create_time','mobile'))
//            ->values(array( $user_id, $from_number, $to_number, $type, $connected, $connect_time, $dateline, $create_time, $mobile))
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
        list($insert_id,$affected_rows)=DB::insert("app_callhistory_data",array('user_id','history','create_time'))->values(array($user_id,$json,time()))->execute();
        return $insert_id;

    }


    /** 是否有记录
     * @param $user_id
     * @return mixed
     */
    public function has($user_id){
        return DB::select()->from('app_callhistory_data')->where('user_id','=',(int)$user_id)->limit(1)->execute()->current();
    }



    /** 读取通话记录
     * @param $user_id
     * @return mixed
     */
    public function get_by_user_id($user_id){
        return DB::select()->from('app_callhistory_data')->where('user_id','=',(int)$user_id)->order_by('id','DESC')->execute()->current();
    }


    /** 读取通话记录(只要手机号码记录)
     * @param $user_id
     * @return mixed
     */
    public function get_mobile_by_user_id($user_id){
        return DB::select()->from('app_callhistory')->where('user_id','=',(int)$user_id)->and_where('mobile','is',DB::expr('not null'))->execute()->as_array();
    }


}