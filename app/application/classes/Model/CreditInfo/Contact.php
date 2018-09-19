<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/19
 * Time: 上午1:09
 *
 * 授信信息 - 联系人
 */
class Model_CreditInfo_Contact extends Model_Database{

    const STATUS_VALID = 1;    //有效
    const STATUS_INVALID = 2;  //无效

    /**
     * parent:父母
     * brother:兄弟姐妹
     * spouse:配偶
     * children:子女
     * colleague:同事
     * classmate:同学
     * friend:朋友
     */
    const ALLOW_RELATION = array('parent', 'brother', 'spouse', 'children', 'colleague', 'classmate', 'friend');



    /** 创建
     * @param int user_id
     * @param array $array
     * @return bool | int
     */

    public function create($user_id,$array = array()) {
        if( $user_id<1 || !isset($array['name']) || !isset($array['relation']) || !isset($array['mobile'])  ){
            return FALSE;
        }

        $user_id = (int)$user_id;
        $name = $array['name'];
        $mobile = isset($array['mobile']) ? $array['mobile'] : '';
        $relation = isset($array['relation']) ? $array['relation'] : '';
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time() ;

        list($insert_id,$affected_rows)=DB::insert("ci_contact",array('user_id','name','mobile','relation','create_time','status'))
            ->values(array( $user_id, $name, $mobile, $relation, $create_time, self::STATUS_VALID ))
            ->execute();
        return $insert_id;
    }


    //查询数据
    public function get_by_array($user_id,$array=array(),$status=self::STATUS_VALID) {
        $query = DB::select()->from('ci_contact')->where('user_id','=', (int)$user_id);
        if(isset($array['name']) && $array['name'] ){
            $query->where('name','=',$array['name']);
        }
        if(isset($array['mobile']) && $array['mobile']){
            $query->where('mobile','=', $array['mobile']);
        }
        $query->and_where('status','=',$status);
        return $query->execute()->as_array();
    }


    //查询单条数据
    public function get_one_by_array($user_id,$array=array(),$status=self::STATUS_VALID) {
        $query = DB::select()->from('ci_contact')->where('user_id','=',(int)$user_id)->limit(1)->order_by('id','ASC');
        if(isset($array['name']) && $array['name'] ){
            $query->where('name','=',$array['name']);
        }
        if(isset($array['mobile']) && $array['mobile']){
            $query->where('mobile','=', $array['mobile']);
        }
        $query->and_where('status','=',$status);
        return $query->execute()->current();
    }


    /** 注销记录
     * @param $user_id
     * @return bool
     */
    public function cancel($user_id){
        return NULL!==DB::update('ci_contact')->set(array('status'=>self::STATUS_INVALID))->where('user_id','=',(int)$user_id)->execute();
    }



}