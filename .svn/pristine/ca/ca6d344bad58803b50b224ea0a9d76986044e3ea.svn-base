<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/19
 * Time: 上午1:09
 *
 * 授信信息 - 工作信息
 */
class Model_CreditInfo_Work extends Model_Database{

    const EMAIL_VERIFIED = 1;		//已验证
    const EMAIL_FAILED= 2;			//验证失败
    const EMAIL_READY = 3;			//待审核
    const EMAIL_CHECKING = 4;		//审核中

    const STATUS_VALID = 1;    //有效
    const STATUS_INVALID = 2;  //无效


    /** 创建
     * @param int user_id
     * @param array $array
     * @return bool | int
     */

    public function create($user_id,$array = array()) {
        if( $user_id<1 || !isset($array['company_name']) || !isset($array['address']) || !isset($array['tel'])  ){ //|| !isset($array['email'])  || !isset($array['validated_email'])
            return FALSE;
        }

        if($rs = $this->get_one($user_id)){
            return FALSE;
        }

        $ts = Tool::factory('String');
        $user_id = (int)$user_id;
        $company_name = isset($array['company_name']) ? mb_substr( trim($ts->filter_utf8($array['company_name'])) , 0, 60, 'utf8') : '';
        $address = isset($array['address']) ? mb_substr( trim($ts->filter_utf8($array['address'])) , 0, 150, 'utf8')  : '';
        $province = isset($array['province']) ? mb_substr( trim($ts->filter_utf8($array['province'])) , 0, 50, 'utf8') : '';
        $city = isset($array['city']) ? mb_substr( trim($ts->filter_utf8($array['city'])) , 0, 50, 'utf8') : '';
        $county = isset($array['county']) ? mb_substr( trim($ts->filter_utf8($array['county'])) , 0, 50, 'utf8') : '';
        $tel = isset($array['tel']) ? mb_substr( trim($ts->filter_utf8($array['tel'])) , 0, 60, 'utf8') : '';
        //$email = $array['email'];
        //$validated_email = isset($array['validated_email'])? (int)$array['validated_email']: Model_CreditInfo_Work::STATUS_READY;
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;

        list($insert_id,$affected_rows)=DB::insert("ci_work",array('user_id','company_name','address','province','city','county','tel','create_time','status')) //'email','validated_email',
            ->values(array( $user_id, $company_name, $address, $province, $city, $county, $tel, $create_time, self::STATUS_VALID)) //$email, $validated_email,
            ->execute();
        return $insert_id;
    }


    //单条数据
    public function get_one($user_id,$status=self::STATUS_VALID) {
        return DB::select()->from('ci_work')->where('user_id','=',(int)$user_id)->and_where('status','=',$status)->execute()->current();
    }

/*    public function get_one_by_email($email){
        return DB::select()->from('ci_work')->where('email','=',$email)->and_where('validated_email','=',self::EMAIL_VERIFIED)->execute()->current();
    }*/

    /** 注销记录
     * @param $user_id
     * @return bool
     */
    public function cancel($user_id){
        return NULL!==DB::update('ci_work')->set(array('status'=>self::STATUS_INVALID))->where('user_id','=',(int)$user_id)->execute();
    }


}