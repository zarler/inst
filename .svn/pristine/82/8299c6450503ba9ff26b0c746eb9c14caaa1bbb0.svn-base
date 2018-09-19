<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 15/12/14
 * Time: 下午6:11
 *
 * 后台账号登录日志
 */
class Model_Admin_Log_Login extends Model_Database {

    const TYPE_LOGIN = 'login';                     //登录
    const TYPE_FAIL_PASSWORD = 'fail_password';     //密码错误
    const TYPE_UNKNOW = 'unknow';                   //未知


    //构造查询条件:int user_id ,string username , string ip , int time_start , int time_end
    private function buildQuery($query,$array=array()) {
        if(isset($array['type'])&& $array['type'] != '') {
            $query->and_where('type','=',$array['type']);
        }
        if(isset($array['username'])&& $array['username'] != ''){
            $query->and_where('username','=',$array['username']);
        }
        if(isset($array['ip'])&& $array['ip'] != '') {
            $query->and_where('ip','=',$array['ip']);
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
        $query->from('log_login');
        if(count($array)>0){
            $query = $this->buildQuery($query,$array);
        }
        if($page<1){
            $page=1;
        }
        //var_dump($query->__toString());
        $rs=$query->order_by('id','DESC')->offset($perpage*($page-1))->limit($perpage)->execute('admin')->as_array();
        return $rs;
    }


    //查询统计
    public function getTotal($array=array()) {
        $query=DB::select(array(DB::expr('COUNT(*)'), 'total'));
        $query->from('log_login');
        if(count($array)>0){
            $query = $this->buildQuery($query,$array);
        }
        $rs=$query->execute('admin')->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }


    //登陆日志
    public function create($array=array()) {
        if(!isset($array['ip']) || !isset($array['user_id']) ){
            return FALSE;
        }
        DB::insert('log_login',array('user_id','username','type','ip','user_agent','location','create_time'))->values( array(
                'user_id'=> isset($array['user_id']) ? $array['user_id'] : 0,
                'username'=> isset($array['username']) ? $array['username'] : '',
                'type'=> isset($array['type']) ? $array['type'] : self::TYPE_UNKNOW,
                'ip'=> isset($array['ip']) ? $array['ip'] : '',
                'user_agent'=> isset($array['user_agent']) ? $array['user_agent'] : '',
                'location'=>isset($array['ip']) ? Lib::factory('IPLocation')->getLocation($array['ip']) : '',
                'create_time'=> time() ) )
            ->execute('admin');
        return TRUE;
    }



}