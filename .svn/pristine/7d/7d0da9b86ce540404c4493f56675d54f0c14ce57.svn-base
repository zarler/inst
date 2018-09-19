<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/19
 * Time: 上午1:09
 *
 * 授信信息 - 电商账号
 */
class Model_CreditInfo_ECAccount extends Model_Database{

    const STATUS_VALID = 1;    //有效
    const STATUS_INVALID = 2;  //无效

    const SOURCE = 1;
    const ENCRYPT = 2;

    /** 添加淘宝账号
     * @param int user_id
     * @param array $array
     * @return bool | int
     */

    public function create_taobao($user_id,$array = array()) {
        if( $user_id<1 || !isset($array['account']) || !isset($array['password'])   ){
            return FALSE;
        }

        $user_id = (int)$user_id;
        $account = $array['account'];
        $password = isset($array['password']) ? $array['password'] : '';
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;
        $source = isset($array['source']) ? (int)$array['source'] : 0;

        list($insert_id,$affected_rows)=DB::insert("ci_account_taobao",array('user_id','account','password','create_time','source','status'))
            ->values(array( $user_id, $account, $password, $create_time, $source, self::STATUS_VALID))
            ->execute();
        return $insert_id;
    }


    public function get_taobao($user_id,$status=self::STATUS_VALID) {
        return DB::select()->from('ci_account_taobao')->where('user_id','=',(int)$user_id)->and_where('status','=',$status)->limit(1)->execute()->current();
    }


    /** 注销淘宝记录
     * @param $user_id
     * @return bool
     */
    public function cancel_taobao($user_id){
        return NULL!==DB::update('ci_account_taobao')->set(array('status'=>self::STATUS_INVALID))->where('user_id','=',(int)$user_id)->execute();
    }



    /** 添加京东账号
     * @param $user_id
     * @param array $array
     * @return bool
     */
    public function create_jingdong($user_id,$array = array()) {
        if( $user_id<1 || !isset($array['account']) || !isset($array['password'])   ){
            return FALSE;
        }

        $user_id = (int)$user_id;
        $account = $array['account'];
        $password = isset($array['password']) ? $array['password'] : '';
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;
        $source = isset($array['source']) ? (int)$array['source'] : 0;

        list($insert_id,$affected_rows)=DB::insert("ci_account_jingdong",array('user_id','account','password','create_time','source','status'))
            ->values(array( $user_id, $account, $password, $create_time, $source, self::STATUS_VALID))
            ->execute();
        return $insert_id;
    }


    public function get_jingdong($user_id,$status=self::STATUS_VALID) {
        return DB::select()->from('ci_account_jingdong')->where('user_id','=',(int)$user_id)->and_where('status','=',$status)->limit(1)->execute()->current();
    }

    /** 注销京东记录
     * @param $user_id
     * @return bool
     */
    public function cancel_jingdong($user_id){
        return NULL!==DB::update('ci_account_jingdong')->set(array('status'=>self::STATUS_INVALID))->where('user_id','=',(int)$user_id)->execute();
    }














}