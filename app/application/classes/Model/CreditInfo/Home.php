<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/19
 * Time: 上午1:09
 *
 * 授信信息 - 家庭信息
 */
class Model_CreditInfo_Home extends Model_Database{

    const STATUS_VALID = 1;    //有效
    const STATUS_INVALID = 2;  //无效

    /** 创建
     * @param int user_id
     * @param array $array
     * @return bool | int
     */

    public function create($user_id,$array = array()) {
        if( $user_id<1 || !isset($array['address']) || !isset($array['province']) || !isset($array['city'])  ){
            return FALSE;
        }

        if($rs = $this->get_one($user_id)){
            return FALSE;
        }

        $ts = Tool::factory('String');
        $user_id = (int)$user_id;
        $address = isset($array['address']) ? mb_substr( trim($ts->filter_utf8($array['address'])) , 0, 150, 'utf8') : '';
        $province = isset($array['province']) ? mb_substr( trim($ts->filter_utf8($array['province'])) , 0, 50, 'utf8')  : '';
        $city = isset($array['city']) ? mb_substr( trim($ts->filter_utf8($array['city'])) , 0, 50, 'utf8')  : '';
        $county = isset($array['county']) ? mb_substr( trim($ts->filter_utf8($array['county'])) , 0, 50, 'utf8')  : '';
        $tel = isset($array['tel']) ? mb_substr( trim($ts->filter_utf8($array['tel'])) , 0, 60, 'utf8')  : '';
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;

        list($insert_id,$affected_rows)=DB::insert("ci_home",array('user_id','address','province','city','county','tel','create_time','status'))
            ->values(array( $user_id, $address, $province, $city, $county, $tel, $create_time, self::STATUS_VALID))
            ->execute();
        return $insert_id;
    }


    //单条数据
    public function get_one($user_id,$status=self::STATUS_VALID) {
        return DB::select()->from('ci_home')->where('user_id','=',(int)$user_id)->and_where('status','=',self::STATUS_VALID)->execute()->current();
    }

    /** 注销记录
     * @param $user_id
     * @return bool
     */
    public function cancel($user_id){
        return NULL!==DB::update('ci_home')->set(array('status'=>self::STATUS_INVALID))->where('user_id','=',(int)$user_id)->execute();
    }

}