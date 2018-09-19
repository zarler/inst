<?php defined('SYSPATH') or die('No direct script access.');



class Controller_Admin_Crontab extends AdminController {



    

    public function action_List()
    {
        $cron_list = DB::select()->from(DB::expr("__crontab"))->execute()->as_array();
        if (!empty($cron_list)){
            foreach ($cron_list as $key => &$value) {
                $value['time_format_arr'] = Lib_Crontab::formatToArray($value['time_format']);
                $value['status_explanation'] = $value['status'] == 1 ? "可用" : "不可用" ;
                $value['create_time_format'] = date("Y-m-d H:i:s", $value['create_time']);
                $value['last_execute_time_format'] = date("Y-m-d H:i:s", $value['last_execute_time']);
            }
        }
        // print_R($cron_list);
        Template::factory('Admin/Crontab/List', array(
            'cron_list' => $cron_list,
        ))->response();
    }


    public function action_Detail()
    {
        if(!isset($this->get['id']) || $this->get['id']==0){
            Template::factory('_Common/404')->response();
        }
        $cron_item = DB::select()->from(DB::expr("__crontab"))->where('id','=',$this->get['id'])->execute()->current();
        if(empty($cron_item)){
            Template::factory('_Common/404')->response();
        }
        Template::factory('Admin/Crontab/Detail', array(
            'cron_item' => $cron_item,
        ))->response();
    }


} // End Welcome
