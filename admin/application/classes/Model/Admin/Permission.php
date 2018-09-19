<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 15/12/15
 * Time: 上午11:43
 *
 * 权限MODEL
 */

class Model_Admin_Permission extends Model_Database {


    //单个组
    public function getGroup($group_id) {
        return DB::select()->from('permission_group')->where('id','=',intval($group_id))->execute('admin')->current();
    }

    //全部权限组
    public function getGroupAll() {
        return DB::select()->from('permission_group')->order_by('sort','DESC')->execute('admin')->as_array();
    }

    //添加组
    public function createGroup($array=array()) {
        if(isset($array['name']) && $array['name'] ) {
            $name = trim($array['name']);
            $description = $array['description'];
            $sort = intval($array['sort']);
            list($insert_id,$affected_rows)=DB::insert("permission_group",array('name','description','sort'))
                ->values( array($name,$description,$sort) )->execute('admin');
            if($insert_id) {
                return $insert_id;
            }
        }
        return FALSE;
    }


    //更改组
    public function updateGroup($group_id=0,$array=array()) {
        if($group_id>0 && isset($array['name']) && trim($array['name']) ) {
            $rs=DB::update('permission_group')
                ->set($array)
                ->where('id','=',$group_id)
                ->execute('admin');
            return TRUE;
        }
        return FALSE;
    }


    //删除组
    public function deleteGroup($group_id=0) {
        if($group_id) {
            $rs = DB::select('id')->from('permission_item')->where('group_id','=',$group_id)->limit(1)->execute('admin')->current();
            if( !isset($rs['id'])  ){//有子权限的不能删除
                return DB::delete('permission_group')
                    ->where('id','=',$group_id)
                    ->execute('admin');
            }
        }
        return FALSE;
    }


    //查询分页
    public function getGroupList($array=array(),$perpage=20,$page=1) {
        return DB::select()->from('permission_group')->order_by('sort','desc')->order_by('id')->offset($perpage*($page-1))->limit($perpage)->execute('admin')->as_array();
    }


    //查询统计
    public function getGroupTotal($array=array()) {
        $rs=DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('permission_group')->execute('admin')->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }


    //单个权限项
    public function getOne($id) {
        return DB::select()->from('permission_item')->where('id','=',intval($id))->execute('admin')->current();
    }


    //添加
    public function create($array=array()) {
        if(isset($array['name']) && trim($array['name']) ) {
            $group_id = intval($array['group_id']);
            $name = trim($array['name']);
            $controller = trim($array['controller']);
            $action = $array['action'];
            $sort = intval($array['sort']);
            $description = $array['description'];
            list($insert_id,$affected_rows) = DB::insert("permission_item",array('name','group_id','controller','action','description','sort'))
                ->values( array($name,$group_id,$controller,$action,$description,$sort)	)->execute('admin');
            if($insert_id) {
                return $insert_id;
            }
        }
        return FALSE;
    }


    //更改
    public function update($id=0,$array=array())
    {
        if($id && isset($array['name']) && $array['name']  && $array['group_id']>0){
            $name = trim($array['name']);
            $group_id = intval($array['group_id']);
            $controller = isset($array['controller']) ? trim($array['controller']) : '';
            $action = isset($array['action']) ? trim($array['action']) : '';
            $sort = isset($array['sort']) ? intval($array['sort']) : 0;
            $description = isset($array['description']) ? $array['description'] : '';

            $rs=DB::update('permission_item')
                ->set(array('name'=>$name,
                    'group_id'=>$group_id,
                    'controller'=>$controller,
                    'action'=>$action,
                    'sort'=>$sort,
                    'description'=>$description))
                ->where('id','=',$id)
                ->execute('admin');
            return TRUE;
        }
        return FALSE;
    }


    //删除
    public function delete($id=0) {
        return DB::delete('permission_item')
                ->where('id','=',intval($id))
                ->execute('admin');
    }


    //查询条件
    private function queryBuilder($query, $array=array()) {
        $query->where('group_id','=',$array['group_id']);
        return $query;
    }


    //查询分页
    public function getList($array=array(),$perpage=20,$page=1) {
        $query = DB::select()->from('permission_item');
        if(isset($array['group_id']) && $array['group_id']>0 ){
            $query = $this->queryBuilder($query,$array);
        }
        return $query->order_by('sort','desc')->order_by('id')->offset($perpage*($page-1))->limit($perpage)->execute('admin')->as_array();
    }


    //查询统计
    public function getTotal($array=array()) {
        $query = DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('permission_item');
        if(isset($array['group_id']) && $array['group_id']>0 ){
            $query = $this->queryBuilder($query,$array);
        }
        $rs = $query->execute('admin')->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }







    //按组ID 重新盘列成2纬数组
    public function getGroupPermissionArray() {
        $array =array();
        $rs = DB::select()->from('permission_item')->order_by('sort','DESC')->execute('admin')->as_array();
        foreach($rs as $k => $v) {
            $array[$v['group_id']][$v['id']]=$v;
        }
        return $array;
    }



}