<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: isNum
 * Date: 17/11/24
 * Time: 下午17:27
 */

class Model_SMS_Provider extends Model_Database {


    public function get_one($id=0) {
        return DB::select()->from('sms_provider')->where('id','=',$id)->execute()->current();
    }

    //创建
    public function create($array=NULL){
        if(isset($array['code']) && isset($array['title'])){
            $create_array = array(
                'name'=> isset($array['name']) ? $array['name']:'' ,
                'desc'=> isset($array['desc']) ? $array['desc'] :'' ,
                'provider'=> isset($array['provider']) ? $array['provider'] :'' ,
                'create_time'=> time(),
            );

            list($insert_id,$affected_rows)=DB::insert("sms_provider",array('name','desc','provider','create_time','create_time'))
                ->values($create_array)
                ->execute();
            return $insert_id;
        }
        return FALSE;

    }


    //更改
    public function update($id=0, $array=NULL) {
        if(!$array){
            return FALSE;
        }
        unset($array['id']);
        $affected_rows = DB::update('sms_provider')->set($array)->where('id','=',intval($id))->execute();
        return $affected_rows!==NULL;
    }

    //取消(删除)
    public function delete($id=0) {
        return DB::update('sms')->set(array('status'=>self::DELETED))->where('id','=',$id)->execute();
    }


    //构造查询条件
    private function query_builder($query, $array=array()) {

        if(isset($array['title']) && $array['title'] ) {
            $query->where('title','like','%'.trim($array['title']).'%');
        }

        if(isset($array['code']) && $array['code']) {
            $query->where('code','like','%'.trim($array['code']).'%');
        }

        if(isset($array['status']) && $array['status'] ) {
            $query->where('status', '=', $array['status']);
        }else{
            $query->where('status','IN',array(Model_SMS::VALID,Model_SMS::INVALID));
        }

        return $query;
    }


    //查询分页
    public function get_list($array=array(),$perpage=20,$page=1) {

        $query=DB::select()->from('sms');
        $query = $this->query_builder($query,$array);
        if($page<1) {
            $page=1;
        }
        $rs=$query->order_by('sms.id','name')->offset($perpage*($page-1))->limit($perpage)->execute()->as_array();
        return $rs;
    }


    //查询统计
    public function get_total($array=array()) {
        $query=DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('sms');
        if(count($array)>0) {
            $query = $this->query_builder($query,$array);
        }
        $rs=$query->execute()->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }

    //获取全部数据
    public function get_all() {
        return DB::select()->from('sms_provider')->execute()->as_array();
    }
}