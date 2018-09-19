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
 * 例子
countdayceil
 * Lib_Date::countDayCeil($time1,s$time2);
 * or
countdayceil
 * Lib::factory('Date')->countDayCeil($time1,$time2);
 *
 * 用法
countdayceil
countdayceil
 * echo 'countDayCeil:'.Lib::factory('Date')->countDayCeil(strtotime($time1),strtortime($time2));
 * echo 'countDayFloor:'.Lib::factory('Date')->countDayFloor(strtotime($time1),strtortime($time2));
 * echo 'countDayFloor:'.Lib::factory('Date')->countDayDay(strtotime($time1),strtortime($time2));
 * 结果
 * time1=2016-1-1 0:0:0&time2=2016-1-3
countdayceil
 * countDayCeil:2
 * countDayFloor:2
 * countDayDay:3
 *
 * time1=2016-1-1 0:0:1&time2=2016-1-3
countdayceil
 * countDayCeil:2
 * countDayFloor:1
 * countDayDay:3
 *
 *
 *
 *
 */

class Lib_Date
{

    //时间戳 上舍入 超过24小时的算2天,小于24小时算一天
    public static function countDayCeil($small_time,$large_time=NULL)
    {
        if($large_time===NULL){
            $large_time = time();
        }

    }

    //时间戳 下舍入 不足48小时的算1天
    public static function countDayFloor($small_time,$large_time=NULL)
    {
        if($large_time===NULL){
            $large_time = time();
        }
        return floor(abs($large_time-$small_time)/86400);
    }

    /**
     * @param int $time1,$time2
     * @return int
     * 按整天计算 2016-01-01 00:00:00  到 2016-01-02 00:00:00 算2天
     */
    public static function countDayDay($time1,$time2=NULL)
    {
        $time1 = strtotime(date('Y-m-d',$time1));
        if($time2===NULL){
            $time2 = strtotime(date('Y-m-d'));
        }else{
            $time2 = strtotime(date('Y-m-d',$time2));
        }
        return ceil(abs($time1-$time2)/86400)+1;
    }


    /** 
     * 给出1个天数逾期借款M数字
     * @param $time
     * @param null $time2
     * @return bool|int
     */
    public static function m($time,$time2=NULL)
    {
        if($time2===NULL){
            $time2 = time();
        }
        $day = self::countDayDay($time,$time2);
        $mtype=NULL;
        if($day>=1 && $day<=29){
            return 1;
        }elseif($day>=30 && $day<=59){
            return 2;
        }elseif($day>=60 && $day<=89){
            return 3;
        }elseif($day>=90 && $day<=119){
            return 4;
        }elseif($day>=120 && $day<=149){
            return 5;
        }elseif($day>=150 && $day<=179){
            return 6;
        }elseif($day>179){
            return 7;
        }
        return FALSE;
    }

    /**
     * 根据mtype推算过期时间的范围
     */
    public static function expireTime($mtype,$time=NULL)
    {
        if(!$time){
            $time = time();
        }
        switch ($mtype) {
        case '1':
            $expire_time_max = $time - 0*86400;
            $expire_time_min = $time - 29*86400;
            break;
        case '2':
            $expire_time_max = $time - 29*86400;
            $expire_time_min = $time - 59*86400;
            break;
        case '3':
            $expire_time_max = $time - 59*86400;
            $expire_time_min = $time - 89*86400;
            break;
        case '4':
            $expire_time_max = $time - 89*86400;
            $expire_time_min = $time - 119*86400;
            break;
        case '5':
            $expire_time_max = $time - 119*86400;
            $expire_time_min = $time - 149*86400;
            break;
        case '6':
            $expire_time_max = $time - 149*86400;
            $expire_time_min = $time - 179*86400;
            break;
        default:
            $expire_time_max = $time - 179*86400;
            $expire_time_min = 0;
            break;
        }
        $expire_time_min = date("Y-m-d",$expire_time_min);
        $expire_time_max = date("Y-m-d",$expire_time_max);
        return array("expire_time_min" => $expire_time_min, "expire_time_max" => $expire_time_max);
    }



    /*
     * 根据mtype推算过期时间的范围时间戳
     */
    public static function mtypeTime($mtype)
    {
        switch ($mtype) {
        case '1':
            $expire_time_min =  0*86400;
            $expire_time_max =  29*86400;
            break;
        case '2':
            $expire_time_min =  29*86400;
            $expire_time_max =  59*86400;
            break;
        case '3':
            $expire_time_min =  59*86400;
            $expire_time_max =  89*86400;
            break;
        case '4':
            $expire_time_min =  89*86400;
            $expire_time_max =  119*86400;
            break;
        case '5':
            $expire_time_min =  119*86400;
            $expire_time_max =  149*86400;
            break;
        case '6':
            $expire_time_min =  149*86400;
            $expire_time_max =  179*86400;
            break;
        default:
            $expire_time_min =  179*86400;
            $expire_time_max = 0;
            break;
        }

        return array("expire_time_min" => $expire_time_min, "expire_time_max" => $expire_time_max);
    }


    /*
     * 根据expire_time获取每个账期的起始结束时间
     */
    public function getAllTimeRangeByExpireTime($expire_time = 0)
    {
        $expire_time = strtotime(date("Y-m-d",$expire_time));
        $res['1'] = [
            'min' => 0*86400+$expire_time,
            'max' => 29*86400+$expire_time-1,
        ];
        $res['2'] = [
            'min' => 29*86400+$expire_time,
            'max' => 59*86400+$expire_time-1,
        ];
        $res['3'] = [
            'min' => 59*86400+$expire_time,
            'max' => 89*86400+$expire_time-1,
        ];
        $res['4'] = [
            'min' => 89*86400+$expire_time,
            'max' => 119*86400+$expire_time-1,
        ];
        $res['5'] = [
            'min' => 119*86400+$expire_time,
            'max' => 149*86400+$expire_time-1,
        ];
        $res['6'] = [
            'min' => 149*86400+$expire_time,
            'max' => 179*86400+$expire_time-1,
        ];
        $res['7'] = [
            'min' => 179*86400+$expire_time,
            'max' => 9999999999,
        ];
        return $res;
    }


}
