<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 15/12/22
 * Time: 上午10:00
 *
 * 管理员 个人自主信息
 */

class Controller_Admin_Profile extends AdminController {

    var $permission_array = array('only_check_login' => array('*'));//个人设置只需要检查用户登录

    public function action_Index() {
        $rs = Model::factory('Admin_User')->get_one($this->user_id());
        $message = NULL;

        if($rs && isset($this->post['submit'])) {
                Model::factory('Admin_User')->update($this->user_id(), array(
                    'name' => $this->post['name'],
                    'mobile' => $this->post['mobile'],
                    'phone'=>$this->post['phone'],
                    'department' => $this->post['department'],
                    'job' => $this->post['job'],
                ));
                $rs = Model::factory('Admin_User')->get_one($this->user_id());

            if ($message === NULL) {
                $message = array('type' => 'success', 'message' => '更新成功.');
            }
        }


        Template::factory('Admin/Profile/Index',array('message'=>$message,'data'=>$rs))->response();
    }

    public function action_Password() {
        $message = NULL;
        if(isset($this->post['submit'])) {
            $valid = Validation::factory($this->post)->rule('old_password','not_empty')
                ->rule('old_password','min_length',array(':value',6))
                ->rule('new_password','not_empty')
                ->rule('new_password','min_length',array(':value',6))
                ->rule('new_password_confirm','matches',array(':validation','new_password_confirm','new_password'));
            if($valid->check()) {
                $rs = Model::factory('Admin_User')->change_password($this->user_id(),$this->post['old_password'],$this->post['new_password']);
                if($rs) {
                    $message = array('type' => 'success', 'message' => '更新成功.');
                }else {
                    $message = array('type' => 'error', 'message' => '更改失败,原密码错误或数据异常.');
                }
            }else{
                $errors = $valid->errors('Admin/Profile');
                $message = array('type'=>'error','message'=>$errors);
            }
        }

        Template::factory('Admin/Profile/Password',array('message'=>$message))->response();
    }






}