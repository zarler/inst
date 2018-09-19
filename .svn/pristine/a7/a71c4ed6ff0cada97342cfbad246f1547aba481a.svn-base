<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/24
 * Time: 下午7:16
 */
class Model_CreditInfo_MNO extends Model_Database {

    const TYPE_CHINAMOBILE = 'chinamobile';     //中国移动
    const TYPE_CHINAUNICOM = 'chinaunicom';     //中国联通
    const TYPE_CHINATELECOM = 'chinatelecom';   //中国电信

    const STATUS_VALID = 1;    //有效
    const STATUS_INVALID = 2;  //无效

    const SOURCE = 1;
    const ENCRYPT = 2;


    /** 添加
     * @param int user_id
     * @param array $array
     * @return bool | int
     */
    public function create($user_id,$array = array()) {
        if( $user_id<1 || !isset($array['mobile']) || !isset($array['service_password']) || !isset($array['type']) ){
            return FALSE;
        }

        $user_id = (int)$user_id;
        $mobile = $array['mobile'];
        $service_password = $array['service_password'];
        //新增网站查询密码 {仅北京移动}
        $query_password = isset($array['query_password'])?$array['query_password']:'';
        $type = $array['type'];
        $source = isset($array['source']) ? (int)$array['source'] : 0;
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;


        list($insert_id,$affected_rows)=DB::insert("ci_account_mno",array('user_id','mobile','service_password','query_password','type','source','create_time','status'))
            ->values(array( $user_id, $mobile, $service_password,$query_password, $type, $source, $create_time, self::STATUS_VALID ))
            ->execute();
        return $insert_id;
    }


    /** 查询单条
     * @param $user_id
     * @return mixed
     */
    public function get_one($user_id,$status=self::STATUS_VALID) {
        return DB::select()->from('ci_account_mno')->where('user_id','=',(int)$user_id)->and_where('status','=',$status)->limit(1)->execute()->current();
    }


    /** 更新
     * @param $user_id
     * @param array $array
     * @return bool
     */
    public function update($user_id,$array=array()){
        if( $user_id<1 || !isset($array['mobile']) || !isset($array['service_password']) || !isset($array['type']) ){
            return FALSE;
        }
        return NULL !== DB::update('ci_account_mno')->set($array)->where('user_id','=',(int)$user_id)->execute();
    }

    /** 注销记录
     * @param $user_id
     * @return bool
     */
    public function cancel($user_id){
        return NULL!==DB::update('ci_account_mno')->set(array('status'=>self::STATUS_INVALID))->where('user_id','=',(int)$user_id)->execute();
    }


}