<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * PAPI
 * Created by PhpStorm.
 * Permission: chunyu
 * Date: 17/11/1
 * Time: 下午4:17
 */

class Model_Location_BaiDuMap extends Model_Database {



    public function getLocationBaiDuMapByUserId($user_id){
        $user_id = (int) $user_id;
        if(empty($user_id)){
            return FALSE;
        }
        return DB::select('lng', 'lat', 'formatted_address', 'business_circle', 'create_time')->from('location_baidumap')
            ->where('user_id', '=', $user_id)
            ->order_by('id', 'asc')
            ->execute()->as_array();
    }

}

