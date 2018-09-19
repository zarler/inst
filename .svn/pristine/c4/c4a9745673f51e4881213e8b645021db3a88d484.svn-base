<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/1/11
 * Time: 下午1:19
 */
class Model_Admin_Upload extends Model_Database {

    public $_join_rule = array(
        'user'=>array(
            'type' => 'LEFT',
            'on'=>array('{main}.user_id','=','user.id'),
            'column'=>array(
                '.id','.file','.ext','.create_time',
                array('user.username','username'),
                array('user.status','user_status'),
                array('user.email','user_email'),
            )
        )
    );




    public function get_one($id=0){
        return DB::select()->from('upload')->where('id','=',$id)->execute('admin')->current();
    }

    public function get_by_user_id($user_id=0){
        return DB::select()->from('upload')->where('user_id','=',$user_id)->execute('admin')->as_array();
    }



    public function create($array=NULL){
        if($array === NULL ){
            return FALSE;
        }
        $create_array['user_id'] = $array['user_id'] ? intval($array['user_id']) : 0;
        $create_array['controller'] = $array['controller'] ? trim($array['controller']) : '';
        $create_array['action'] = $array['action'] ? trim($array['action']) : '';
        $create_array['file'] = $array['file'] ? trim($array['file']) : '';
        $p=pathinfo($array['file']);
        $create_array['ext'] = isset($p['extension']) ? $p['extension'] : '' ;

        list($insert_id,$affected_rows)=DB::insert("upload",array('user_id','controller','action','file','ext','create_time'))
            ->values(array(
                    $create_array['user_id'],
                    $create_array['controller'],
                    $create_array['action'],
                    $create_array['file'],
                    $create_array['ext'],
                    time(),
                )
            )
            ->execute('admin');
        return $insert_id;
    }



    //构造查询条件
    private function query_builder($query, $array=array()) {


        if(isset($array['user_id']) && $array['user_id']>0) {
            $query->and_where('upload.user_id','=',$array['user_id']);
        }

        if(isset($array['time_start']) && $array['time_start']>0) {
            $query->and_where('upload.create_time','>',$array['time_start']);
        }
        if(isset($array['time_end']) && $array['time_end']>0) {
            $query->and_where('upload.create_time','<',$array['time_end']);
        }

        if(isset($array['controller']) && $array['controller'] ) {
            $query->and_where('upload.controller','=',$array['controller']);
        }
        if(isset($array['action']) && $array['action'] ) {
            $query->and_where('upload.action','=',$array['action']);
        }

        if(isset($array['username']) && $array['username'] ){
            $query->and_where('user.username','=',$array['username']);
        }

        if(isset($array['email']) && $array['email']) {
            $query->and_where('user.email','=',$array['email']);
        }


        return $query;
    }


    //查询分页
    public function get_list($array=array(),$perpage=20,$page=1) {
        $join = Lib::factory('DBJoin')->mainTable('upload')->rule($this->_join_rule);
        $query = DB::select_array($join->join_field(array('user')))->from('upload');

        $query = $join->join_distinct($query,'user');
        if(count($array)>0) {
            $query = $this->query_builder($query,$array);
        }
        if($page<1) {
            $page=1;
        }

        $rs=$query->order_by('upload.id','DESC')->offset($perpage*($page-1))->limit($perpage)->execute('admin')->as_array();
        return $rs;
    }

    public function get_total($array=array()) {
        $query=DB::select(array(DB::expr('COUNT(*)'),'total'))->from('upload');
        if(count($array)>0) {
            $query = $this->query_builder($query,$array);
        }
        $rs=$query->execute('admin')->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }







}