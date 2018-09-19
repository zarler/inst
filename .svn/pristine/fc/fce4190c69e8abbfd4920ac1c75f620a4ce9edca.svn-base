<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: wangxuesong
 * Date: 16/8/23
 * Time: 下午3:16
 */
class Model_CreditInfo_MobileLocation extends Model{

    /**
     * 根据号段查询本号码的归属地
     * @param $phone
     * @return mixed
     */
    public function get_one($phone) {
        return DB::select()->from('mobile_location')->where('phone','=',(int)$phone)->limit(1)->execute()->current();
    }


}