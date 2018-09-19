<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 15/12/14
 * Time: 下午2:31
 *
 * 管理员的常用功能入口,具体实现在更细化的MODEL中
 *
 *  登录
 *  推出
 *  记录
 *  权限审核
 *
 *  另一个想法是,隔离SESSION操作与现有底层数据MODEL
 */

class Model_Admin extends Model_Database {

    protected $session_key='user';
    protected $session;


    public function __construct($config = array()) {
        $this->session = Session::instance('database');
    }


    //完成登录
    //  --权限树不会加载到SESSION,便于权限发生变更后时时反应在用户身上不会出现异步
    public function login($user_id){
        $rs = Model::factory('Admin_User')->getOne($user_id);
        if( isset($rs['status']) && $rs['status']==1 ){
            $user=array('id' => $rs['id'],
                    'user_id' => $rs['id'],
                    'username' => $rs['username'],
                    //'password' => md5($rs['password']),
                    'department' => $rs['department'],
                    'job' => $rs['job'],
                    'name' => $rs['name'],
                    'status' => $rs['status']);
            $this->newSession($user);
            $this->loginLog();

        }
    }


    //完成登出
    public function logout($destroy = FALSE) {

        //清空cookie
        setcookie("loginCJI_agentgroupid", "", time()-3600,'/');
        setcookie("loginCJI_password", "", time()-3600,'/');
        setcookie("loginCJI_switch", "", time()-3600,'/');
        setcookie("loginCJI_user", "", time()-3600,'/');

        if ($destroy === TRUE){
            $this->session->destroy();
        }else{
            $this->session->delete($this->session_key);
            $this->session->write();
            $this->session->regenerate();
        }
        // 退出后再检测
        return ! $this->loggedIn();
    }

    public function loggedIn() {
        $user = $this->getUserSessArray();
        return ( $user !== NULL && isset($user['id']) );
    }


    //为登录创建一个新SESSION
    protected function newSession($user) {
        $this->session->regenerate();
        $this->session->set($this->session_key, $user);
        return true;
    }



    //SESSION中的用户记录
    public function getUserSessArray($default = NULL) {
        return (array)$this->session->get($this->session_key, $default);
}


    public function session() {
        return $this->session;
    }



    //获取用户权限   权限字段使用json
    public function getPermissionArray($user_id=0) {
        if($user_id<1) {
            $user = $this->getUserSessArray();
            $user_id = $user['id'];
        }
        $groups = Model::factory('Admin_Group')->getByUserId($user_id);
        $permission_array = array();
        if($groups) {
            foreach ($groups as $group) {
                $permission_array = $this->permissionMerge($permission_array, (array)json_decode($group['permission'], TRUE));
            }
            return $permission_array;
        }
        return NULL;
    }

    //权限合并
    protected function permissionMerge($array,$array2){
        if(!$array2 || !is_array($array2)) {
            return $array;
        }
        foreach( $array2 as $k => $v ) {
            if(isset($array2[$k])) {
                foreach( $array2[$k] as $k1 =>$v1 ) {
                    if(!isset($array[$k][$k1]) || !$array[$k][$k1] && $v1){
                        $array[$k][$k1] = $v1;
                    }
                }
            }
        }
        return $array;
    }



    /**
     * 关于登录日志
     *
     * Model::factory('Admin')->login_log('...类型可查看类:Model/Admin/Log/Login.php');
     */
    //记录登录日志
    public function loginLog($type=NULL) {
        if(!$type){
            $type = Model_Admin_Log_Login::TYPE_LOGIN;
        }
        $user = $this->getUserSessArray();
        return Model::factory('Admin_Log_Login')->create( array('user_id'=>$user['id'], 'username'=>$user['username'], 'type'=>$type, 'ip'=>Request::$client_ip, 'user_agent'=>Request::$user_agent));
    }


    /**
     * 关于操作日志
     * 在关键操作完成完成部分统一加入
     *
     * Model::factory('Admin')->opteration_log('简要描述','详细内容....');
     */
    //操作日志
    public function opterationLog($summary='',$content=''){
        $user = $this->getUserSessArray();
        return Model::factory('Admin_Log_Operation')->create(array('user_id'=>$user['id'], 'username'=>$user['username'], 'ip'=>Request::$client_ip, 'controller'=>Request::current()->controller(), 'action'=>Request::current()->action(), 'url'=> $_SERVER['REQUEST_URI'], 'summary'=>$summary, 'content'=>$content));
    }
}