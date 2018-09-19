<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/1/9
 * Time: 上午10:43
 *
 *
 *
 例子
 * Tool_Date::count_day_ceil($time1,s$time2);
 * or
 * Tool::factory('Date')->count_day_ceil($time1,$time2);
 *
 * 用法
 * echo 'count_day_ceil:'.Tool::factory('Date')->count_day_ceil(strtotime($time1),strtortime($time2));
 * echo 'count_day_floor:'.Tool::factory('Date')->count_day_floor(strtotime($time1),strtortime($time2));
 * 结果
 * time1=2016-1-1 0:0:0&time2=2016-1-3
 * count_day_ceil:2
 * count_day_floor:2
 *
 * time1=2016-1-1 0:0:1&time2=2016-1-3
 * count_day_ceil:2
 * count_day_floor:1
 *
 *
 *
 *
 *
 */

class Tool_Date {

    //时间戳 上舍入 超过24小时的算2天
    public static function count_day_ceil($small_time,$large_time=NULL) {
        if($large_time===NULL){
            $large_time = time();
        }
       return ceil(abs($large_time-$small_time)/86400);

    }

    //时间戳 下舍入 不足48小时的算1天
    public static function count_day_floor($small_time,$large_time=NULL) {
        if($large_time===NULL){
            $large_time = time();
        }
        return floor(abs($large_time-$small_time)/86400);
    }






}