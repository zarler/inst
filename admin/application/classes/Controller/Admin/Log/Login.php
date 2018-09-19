<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 15/12/28
 * Time: 上午9:50
 *
 * 登录日志查看
 *
 */
class Controller_Admin_Log_Login extends AdminController {

    //权限映射
    var $permission_array = array(
        'map' => array(
                    'List' => array('Index')
                )
    );


    public function before() {
        parent::before(); // TODO: Change the autogenerated stub
    }


    public function action_Index() {
        $this->action_List();
    }



    public function action_List() {

        $page = isset($this->get['page']) ? intval($this->get['page']):1;
        $perpage = isset($this->get['pagesize']) ? intval($this->get['pagesize']):20;
        $array = array();
        if(array_key_exists('type',$this->get)) {
            $array['type'] = trim($this->get['type']);
        }
        if(array_key_exists('username',$this->get)) {
            $array['username'] = trim($this->get['username']);
        }
        if(array_key_exists('ip',$this->get)) {
            $array['ip'] = trim($this->get['ip']);
        }
        if(array_key_exists('time_start',$this->get)) {
            $array['time_start'] = intval(strtotime($this->get['time_start']));
        }
        if(array_key_exists('time_end',$this->get)) {
            $array['time_end'] = intval(strtotime($this->get['time_end']));
        }

        $total = Model::factory('Admin_Log_Login')->getTotal($array);
        $pagination = Pagination::factory(
            array(
                'total_items' => $total,
                'items_per_page' => $perpage,
            ));
        $list = Model::factory('Admin_Log_Login')->getList($array, $perpage , $page);

        Template::factory('Admin/Log/Login/List',array(
                'list'=>$list,
                'pagination'=>$pagination,
            )
        )->response();
    }




}