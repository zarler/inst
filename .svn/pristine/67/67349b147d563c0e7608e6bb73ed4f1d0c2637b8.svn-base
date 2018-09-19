<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/15
 * Time: 下午11:43
 *
 */
class Model_Location_BaiduMap extends Model_Database{


    /** 创建
     * @param array $array
     * @return bool
     */

    public function create($array = array()){
        if(!isset($array['user_id']) || !isset($array['lng']) || !isset($array['lat']) ){
            return FALSE;
        }
        $user_id = (int)$array['user_id'];
        $lng = $array['lng'];
        $lat = $array['lat'];
        $formatted_address = isset($array['formatted_address']) ? $array['formatted_address'] : '' ;
        $country = isset($array['country']) ? $array['country'] : '' ;
        $province = isset($array['province']) ? $array['province'] : '' ;
        $city = isset($array['city']) ? $array['city'] : '' ;
        $district = isset($array['district']) ? $array['district'] : '' ;
        $business_circle = isset($array['business_circle']) ? $array['business_circle'] : '' ;
        $street = isset($array['street']) ? $array['street'] : '' ;
        $street_number = isset($array['street_number']) ? $array['street_number'] : '' ;
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;

        list($insert_id,$affected_rows)=DB::insert("location_baidumap",array('user_id','lng','lat','formatted_address',
            'country','province','city','district','business_circle','street','street_number','create_time'))
            ->values(array( $user_id, $lng, $lat,$formatted_address,
                $country,$province,$city,$district,$business_circle,$street,$street_number,$create_time))
            ->execute();
        return $insert_id;
    }


    /** 更新
     * @param $id
     * @param array $array
     * @return bool
     */

    public function update($id,$array = array()){
        if($id<1){
            return FALSE;
        }
        return  NULL !== DB::update('location_baidumap')->set($array)->where('id','=',$id)->execute() ;
    }


    /** 单条
     * @param $id
     * @return mixed
     */

    public function get_one($id){
        return DB::select()->from('location_baidumap')->where('id','=',(int)$id)->execute()->current();
    }
    public function get_one_by_user_id($user_id){
        return DB::select()->from('location_baidumap')->where('user_id','=',(int)$user_id)->order_by('id','DESC')->execute()->current();
    }



    /** 注销
     * @param $id
     * @return bool
     */

    public function delete($id){
        if($id<1){
            return FALSE;
        }
        return NULL !== DB::delete('location_baidumap')->where('id','=',(int)$id)->execute();
    }





}