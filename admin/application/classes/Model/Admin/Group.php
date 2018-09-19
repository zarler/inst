<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 15/12/15
 * Time: 上午10:43
 *
 * 用户组操作MODEL
 *
 * 用户关联组隐藏支持一对多关系
 */
class Model_Admin_Group extends Model_Database {




    //单个组
    public function getOne($group_id=0) {
        return DB::select()->from('group')->where('id','=',intval($group_id))->execute('admin')->current();
    }


    //用户所关联的组
    public function getByUserId($user_id=0) {
        return DB::select()->from('group')->join('grouplink')->on('group.id','=','grouplink.group_id')->where('grouplink.user_id','=',intval($user_id))->execute('admin')->as_array();
    }


    //只取group_id
    public function getGroupIdByUserId($user_id=0) {
        $rs = DB::select('group_id')->from('grouplink')->where('user_id','=',intval($user_id))->execute('admin')->as_array();
        $array = array();
        foreach($rs as $v ){
            $array[] = $v['group_id'];
        }
        return $array;
    }


    //全部用户组
    public function getAll() {
        return DB::select()->from('group')->execute('admin')->as_array();
    }






    //添加
    public function create($array=array()) {
        if(isset($array['name']) && $array['name'] ) {
            if(!isset($array['permission'])){
                $array['permission'] = json_encode(array());
            }
            $name = trim($array['name']);
            $description = $array['description'];
            $permission = $array['permission'];

            list($insert_id,$affected_rows) = DB::insert("group",array('name','description','permission'))
                ->values( array($name,$description,$permission)	)->execute('admin');
            if($insert_id) {
                return $insert_id;
            }
        }
        return FALSE;
    }


    //更改
    public function update($group_id=0, $array=array()) {
        if($group_id && isset($array['name']) && $array['name'] ) {
            if(!isset($array['permission']) ){
                $array['permission'] = json_encode(array());
            }
            $name = trim($array['name']);
            $description = isset($array['description']) ? $array['description'] : '';
            $permission = $array['permission'];
            $rs=DB::update('group')
                ->set(array('name'=>$name, 'description'=>$description, 'permission'=>$permission ))
                ->where('id','=',$group_id)
                ->execute('admin');
            return TRUE;
        }
        return FALSE;
    }


    //删除
    public function delete($group_id=0)
    {
        if($group_id>0){
            $rs = DB::select('id')->from('group')->where('id','=',$group_id)->limit(1)->execute('admin')->current();
            if( isset($rs['id']) && $rs['id']>0 ){
                $this->deleteGroupLink(0,$group_id);
                return DB::delete('group')->where('id','=',$group_id)->execute('admin');
            }
        }
        return FALSE;
    }



    //查询分页
    public function getList($array=array(),$perpage=20,$page=1) {
        return DB::select()->from('group')->order_by('id')->offset($perpage*($page-1))->limit($perpage)->execute('admin')->as_array();
    }


    //查询统计
    public function getTotal($array=array()) {
        $rs = DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('group')->execute('admin')->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }












    //设置管理员与组的关系
    public function setGroupLink($user_id=0,$group_id=0) {
        if(is_array($group_id)){
            return $this->setArrayGroupLink($user_id,$group_id);
        }
        if(!$this->checkGroupLink($user_id,$group_id)) {
            list($insert_id,$affected_rows)  = DB::insert('grouplink',array('user_id','group_id'))->values(array($user_id,$group_id))->execute('admin');
            return $insert_id;
        }
        return FALSE;
    }

    //一对多
    public function setArrayGroupLink($user_id=0,$group_id=array()) {
        if($user_id<1) {
            return FALSE;
        }
        foreach($group_id as $v) {
            if(is_integer($v) && !$this->checkGroupLink($user_id,$v)) {
                $this->setGroupLink($user_id,$v);
            }
        }
    }

    //检查
    public function checkGroupLink($user_id=0,$group_id=0){
        return NULL != DB::select()->from('grouplink')->where('user_id','=',intval($user_id))->and_where('group_id','=',intval($group_id))->execute('admin')->current();
    }


    //删除权限关系
    public function deleteGroupLink($user_id=0,$group_id=0) {
        if($user_id<1 && $group_id<1) {
            return FALSE;
        }
        $query = DB::delete('grouplink');
        if($user_id>0) {
            $query->where('user_id','=',intval($user_id));
        }
        if($group_id>0) {
            $query->where('group_id','=',intval($group_id));
        }
        return $query->execute('admin');
    }




}