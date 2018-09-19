<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 15/12/14
 * Time: 下午6:43
 *
 * 操作日志Model
 */

class Model_Admin_Log_Operation extends Model_Database {


    //构造查询条件
    private function buildQuery($query,$array=array()) {
        if(isset($array['type'])&& $array['type'] != '') {
            $query->and_where('type','=',$array['type']);
        }
        if(isset($array['username'])&& $array['username'] != ''){
            $query->and_where('username','=',$array['username']);
        }
        if(isset($array['time_start'])&& $array['time_start']>0) {
            $query->and_where('create_time','>=',$array['time_start']);
        }
        if(isset($array['time_end'])&& $array['time_end']>0) {
            $query->and_where('create_time','<=',$array['time_end']);
        }
        return $query;
    }


    //查询分页
    public function getList($array=array(),$perpage=20,$page=1) {
        $query=DB::select();
        $query->from('log_operation');
        if(count($array)>0) {
            $query = $this->buildQuery($query,$array);
        }
        if($page<1){
            $page=1;
        }
        $rs=$query->order_by('id','DESC')->offset($perpage*($page-1))->limit($perpage)->execute('admin')->as_array();
        //echo $query->__tostring();
        return $rs;
    }


    //查询统计
    public function getTotal($array=array()) {
        $query=DB::select(array(DB::expr('COUNT(*)'), 'total'));
        $query->from('log_operation');
        if(count($array)>0) {
            $query = $this->buildQuery($query,$array);
        }
        $rs=$query->execute('admin')->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }


    //单条记录
    public function getDetail($id=0) {
        if($id<1) {
            return NULL;
        }
        return DB::select()->from('log_operation')->where('id','=',$id)->execute('admin')->current();
    }


    //登陆日志
    public function create($array=array()) {
        if( !isset($array['ip']) || !isset($array['user_id'])  || !isset($array['summary']) ) {
            return FALSE;
        }
        DB::insert('log_operation',array('user_id','username','summary','content','ip','user_agent','controller','action','url','create_time'))->values( array('user_id'=> isset($array['user_id']) ? $array['user_id'] : 0,
            'username'=> isset($array['username']) ? $array['username'] : '',
            'summary'=> isset($array['summary']) ? $array['summary'] : 'unknow',
            'content'=> isset($array['content']) ? json_encode($array['content']) : '',
            'ip'=> isset($array['ip']) ? $array['ip'] : '',
            'user_agent'=> isset($array['user_agent']) ? $array['user_agent'] : '',
            'controller'=> isset($array['controller']) ? $array['controller'] : '',
            'action'=> isset($array['action']) ? $array['action'] : '',
            'url'=> isset($array['url']) ? $array['url'] : '',
            'create_time'=> time() ) )->execute('admin');
        return TRUE;
    }



}