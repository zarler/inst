<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: wangxuesong
 * Date: 16/5/24
 * Time: 下午7:16
 */
class Model_CreditInfo_Speaker extends Model{


    /** 添加
     * @param int user_id
     * @param array $array
     * @return bool | int
     */
    public function create($user_id,$array = array()) {
        if( $user_id<1){
            return FALSE;
        }

        $user_id = (int)$user_id;
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time();
        list($insert_id,$affected_rows)=DB::insert("juxinli_speaker",array('user_id','id_card_num','create_time','data_format','content','status','score'))
            ->values(array($user_id,'', $create_time,  '', '', '0', '0.00'))
            ->execute();
        return $insert_id;
    }


    /** 查询单条
     * @param $user_id
     * @return mixed
     */
    public function get_one($user_id) {
        return DB::select()->from('juxinli_speaker')->where('user_id','=',(int)$user_id)->limit(1)->execute()->current();
    }


    /** 更新
     * @param $user_id
     * @param array $array
     * @return bool
     */
    public function update($user_id,$array=array()){
        if( $user_id<1 ){
            return FALSE;
        }
        $array = array(
             'create_time' => time()
        );
        return NULL !== DB::update('juxinli_speaker')->set($array)->where('user_id','=',(int)$user_id)->execute();
    }




}