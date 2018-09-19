<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/15
 * Time: 下午11:43
 *
 */
class Model_Location_Gps extends Model_Database{


    /** 创建
     * @param array $array
     * @return bool
     */

    public function create($array = array()){
        if(!isset($array['user_id']) || !isset($array['longitude']) || !isset($array['latitude']) ){
            return FALSE;
        }
        $user_id = (int)$array['user_id'];
        $longitude = $array['longitude'];
        $latitude = $array['latitude'];
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;

        list($insert_id,$affected_rows)=DB::insert("location_gps",array('user_id','longitude','latitude','create_time'))
            ->values(array( $user_id, $longitude, $latitude, $create_time))
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
        return  NULL !== DB::update('location_gps')->set($array)->where('id','=',$id)->execute() ;
    }


    /** 单条
     * @param $id
     * @return mixed
     */

    public function get_one($id){
        return DB::select()->from('location_gps')->where('id','=',(int)$id)->execute()->current();
    }



    /** 注销
     * @param $id
     * @return bool
     */

    public function delete($id){
        if($id<1){
            return FALSE;
        }
        return NULL !== DB::delete('location_gps')->where('id','=',(int)$id)->execute();
    }





}