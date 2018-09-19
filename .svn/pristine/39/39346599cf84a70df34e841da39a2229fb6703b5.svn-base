<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: wangxuesong
 * Date: 16/7/19
 * Time: 下午4:17
 *
 * 新增授信审核
 *
 */

class Model_User_Audit extends Model_Database {




    //标记记录审核状态
    const STATUS_AUDITING = '1';      //审核中
    const STATUS_FINISH = '2';        //审核完成


    //构造查询条件
    private function queryBuilder($query, $array=array()) {

        if(isset($array['credit_auth']) && $array['credit_auth']!='') {
            $query->where('user.credit_auth','=',$array['credit_auth']);
        }
        if(isset($array['name']) && $array['name']!='') {
            $query->where('user.name','like',"%".$array['name']."%");
        }

        if(isset($array['mobile']) && $array['mobile']!="") {
            $query->where('user.mobile','=',$array['mobile']);
        }

        if(isset($array['identity_code']) && $array['identity_code']!='') {
            $query->where('user.identity_code','=',$array['identity_code']);
        }

        if(isset($array['id']) && $array['id']!='') {
            $query->where('user.id','=',$array['id']);
        }
          
        //判断是列表分类  [show=all:调用所有列表,show=slef:调用自己审核的列表]
        if(isset($array['admin_id'])) {
          
            if(isset($array['option']) && $array['option']){

                $query->where('user_audit.admin_id','=',$array['admin_id']);
            }else{
                $query->and_where_open()
                ->or_where_open()->where('user_audit.admin_id','=',$array['admin_id'])->or_where_close()
                ->or_where_open()->where('user_audit.admin_id','is',null)->or_where_close()
                ->and_where_close();
            }
           
        }
        

        return $query;
    }


    //查询分页
    public function getList($array=array(),$perpage=20,$page=1) {


        $query=DB::select(
            'user.id','user.name','user.identity_code','user.mobile','user.credit_auth','user.mobile',
            'finance_profile.inst_amount',array('admin_user.name','admin_name'),array('user_audit.id','audit_id')
        )->from('user');

        $query->join('user_audit','LEFT')->on('user_audit.user_id','=','user.id');
        $query->join('admin_user','LEFT')->on('user_audit.admin_id','=','admin_user.id');
   
        $query->join('finance_profile','LEFT')->on('finance_profile.user_id','=','user.id');
        
        $query->where('user.credit_auth','in',[Model_User::CREDIT_AUTH_STATUS_SUBMITED,Model_User::CREDIT_AUTH_STATUS_CHECKING]);
        $query->where('user.status','in',[Model_User::STATUS_NORMAL]);

        if($page<1) {
            $page=1;
        }

        if(count($array)>0) {
            $query = $this->queryBuilder($query,$array);
        }
 

        $rs=$query->offset($perpage*($page-1))->limit($perpage)->execute()->as_array();


        return $rs;
    }


    //查询列表数量
    public function getTotal($array=array()) {

        $query=DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('user');
        $query->join('user_audit','LEFT')->on('user_audit.user_id','=','user.id');
        $query->where('user.credit_auth','in',[Model_User::CREDIT_AUTH_STATUS_SUBMITED,Model_User::CREDIT_AUTH_STATUS_CHECKING]);
        $query->where('user.status','in',[Model_User::STATUS_NORMAL]);
        
        if(count($array)>0) {
            $query = $this->queryBuilder($query,$array);
        }

        $rs=$query->execute()->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }



    /** 创建审核记录
     * @param int $user_id
     * @param array $array
     * @return bool
     */
    public function create($array=array()) {
        
        $array['create_time'] = time();
        return DB::insert("user_audit",array_keys($array))->values(array_values($array))->execute();
    }


    //
    public function getOne($id=NULL){

         return DB::select()->from('user_audit')->where('id','=',intval($id))->execute()->current();

    }

    //
    public function getOneByUserId($user_id=NULL){

         return DB::select()->from('user_audit')->where('user_id','=',intval($user_id))->execute()->current();

    }
  
    /***
     * @param int $id
     * @param null $array
     * @return bool
     */
    public function update($id=0,$array=NULL) {
        if(!$array){
            return FALSE;
        }

        $affected_rows = DB::update("user_audit")->set($array)->where('id','=',intval($id))->execute();
        return $affected_rows!==NULL;
    }


    /***
     * @param int $user_id
     * @param null $array
     * @return bool
     */
    public function  updateByUser($user_id=0,$array=NULL) {
        if(!$array){
            return FALSE;
        }

        $affected_rows = DB::update("user_audit")->set($array)->where('user_id','=',intval($user_id))->execute();
        return $affected_rows!==NULL;
    }


}