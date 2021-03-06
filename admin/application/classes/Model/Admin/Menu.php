<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: majin
 * Date: 15/12/21
 * Time: 下午1:24
 */
class Model_Admin_Menu extends Model_Database {


    //菜单二纬树
    public function getTree(&$array=NULL) {
        if( isset($array) && is_array($array)){
            $data = &$array;
        }else{
            $data = $this->getAll();
        }

        $tree2 = array();
        foreach($data as $v){
            $tree2[$v['fid']][$v['id']] = $v;
        }
        return $tree2;
    }


    //全部菜单
    public function getAll( $array = array('level'=>NULL,'pub_show'=>NULL,'controller'=>NULL,'action'=>NULL) ) {
        $query = DB::select()->from('menu');
        if(isset($array['level'])) {
            $query->and_where('level','=',intval($array['level']));
        }
        if(isset($array['show'])) {
            $query->and_where('pub_show','=',intval($array['pub_show']));
        }
        if(isset($array['controller'])) {
            $query->and_where('controller','=',$array['controller']);
        }
        if(isset($array['action'])) {
            $query->and_where('action','=',$array['action']);
        }
        $rs =  $query->order_by('sort','DESC')->execute('admin')->as_array();
        $menu_array = array();
        if($rs){
            foreach ($rs as $v) {
                $menu_array[$v['id']] = $v;
            }
        }
        return $menu_array;
    }


    //单条
    public function getOne($id=0){
        return DB::select()->from('menu')->where('id','=',intval($id))->execute('admin')->current();
    }


    //单条
    public function getMenuByController($array =[] ){
       $query=  DB::select()->from('menu');

        if(isset($array['controller'])) {
            $query->and_where('controller','=',$array['controller']);
        }

        if(isset($array['action'])) {

            $query->and_where('action','=',$array['action']);
        }

       $rs = $query->execute('admin')->current();

        return $rs;
    }

    //获取子菜单
    public function getSon($fid=0){
        return DB::select()->from('menu')->where('fid','=',intval($fid))->execute('admin')->as_array();
    }

    //递归获取父级菜单
    public function getParentMenuByController($id=0,$menu_array=[]){

        $rs = DB::select('id','fid','name','level','url')->from('menu')->where('id','=',intval($id))->execute('admin')->current();

        if($rs){

             $menu_array[$rs['level']] = $rs;

             if($rs['fid']>=0){
                 $menu_array = $this->getParentMenuByController($rs['fid'],$menu_array);
             }
        }

        return $menu_array;
    }


    private function queryBuilder($query,$array = array()) {
        if (isset($array['fid']) && $array['fid'] >0 ) {
            $query->where('fid', '=', intval($array['fid']));
        }
        if (isset($array['name']) && $array['name'] != '') {
            $query->where('name', 'like', '%' . $array['name'] . '%');
        }
        if (isset($array['url']) && $array['url'] != '') {
            $query->where('url', 'like', '%' . $array['url'] . '%');
        }
        if (isset($array['controller']) && $array['controller'] != '') {
            $query->where('controller', 'like', '%' . $array['controller'] . '%');
        }
        if (isset($array['action']) && $array['action'] != '') {
            $query->where('action', 'like', '%' . $array['action'] . '%');
        }
        return $query;
    }


    public function  getList($array = array(), $perpage = 20, $page = 1) {
        $query = DB::select()->from('menu');
        if (count($array) > 0) {
            $query = $this->queryBuilder($query,$array);
        }
        $rs = $query->order_by('id')->order_by('sort', 'desc')->offset($perpage * ($page - 1))->limit($perpage)->execute('admin')->as_array();
        return $rs;
    }


    public function getTotal($array = array()) {
        $query = DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('menu');
        if (count($array) > 0) {
            $query = $this->queryBuilder($query,$array);
        }
        $rs = $query->execute('admin')->current();
        return isset($rs['total']) ? $rs['total'] : 0;
    }


    public function create($array=array()){
        $parent = array('level'=>0,'id'=>0);
        if($array['fid']>0) {
            $parent = DB::select()->from('menu')->where('id','=',$array['fid'])->execute('admin')->current();
        }
        list($insert_id,$affected_rows)=DB::insert("menu",array('fid','name','url','controller','action','level','pub_show','group_show','description','sort','create_time'))
            ->values(array(
                        'fid'=>intval($array['fid']),
                        'name' =>$array['name'],
                        'url' =>$array['url'],
                        'controller'=>$array['controller'],
                        'action'=>$array['action'],
                        'level'=> intval($parent['level'])+1,
                        'pub_show'=> $array['pub_show'] ? 1 : 0,
                        'group_show'=> $array['group_show'] ? intval($array['group_show']) : 0 ,
                        'description'=>$array['description'],
                        'sort'=>$array['sort'] ? intval($array['sort']) : 0 ,
                        'create_time'=>time(),
                    )
                )
                ->execute('admin');
        return $insert_id;

    }

    //更改信息
    public function update($id=0,$array=NULL) {
        if(!$array){
            return FALSE;
        }
        unset($array['id']);
        $affected_rows = DB::update('menu')->set($array)->where('id','=',intval($id))->execute('admin');
        return $affected_rows!==NULL;
    }


    //删除
    public function delete($id=0) {
        if($id>0){
            return DB::delete('menu')->where('id','=',$id)->execute('admin');
        }
        return FALSE;
    }
















}
