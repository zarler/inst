<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * crontab解析类
 * Lib_Crontab::formatToArray
 * 将格式为 * * * * *
 * 转换为
 *  [
 *       'minute' => [1,2,3,4],  范围0-59
 *       'hour' => [1,2,3,4],    范围0-12
 *       'date' => [1,2,3,4],    范围1-31
 *       'month' => [1,2,3,4],   范围1-12
 *       'day' => [1,2,3,4],     范围0-6
 *  ]
 *
 * Lib_Crontab::arrayToFormat与formatToArray相反
 * 当前类只支持 “*”, “,”
 * 不支持 “/”, “-”以及混合类型的crontab格式
 *
 */

class Lib_Crontab
{

    public static function getMinuteRange()
    {
        for ($i = 0; $i <= 59; $i ++) {
            $minute_range[] = $i;
        }
        return $minute_range;
    }

    public static function getHourRange()
    {
        for ($i = 0; $i <= 23; $i ++) {
            $minute_range[] = $i;
        }
        return $minute_range;
    }

    public static function getDateRange()
    {
        for ($i = 1; $i <= 31; $i ++) {
            $minute_range[] = $i;
        }
        return $minute_range;
    }

    public static function getMonthRange()
    {
        for ($i = 1; $i <= 12; $i ++) {
            $minute_range[] = $i;
        }
        return $minute_range;
    }

    public static function getDayRange()
    {
        for ($i = 0; $i <= 6; $i ++) {
            $minute_range[] = $i;
        }
        return $minute_range;
    }

    public static function formatToArray($cron_format)
    {
        $format_arr = explode(" ", $cron_format);
        $arr_data['minute'] = self::parseItemToArray($format_arr[0], "getMinuteRange");
        $arr_data['hour'] = self::parseItemToArray($format_arr[1], "getHourRange");
        $arr_data['date'] = self::parseItemToArray($format_arr[2], "getDateRange");
        $arr_data['month'] = self::parseItemToArray($format_arr[3], "getMonthRange");
        $arr_data['day'] = self::parseItemToArray($format_arr[4], "getDayRange");
        return $arr_data;
    }

    public static function arrayToFormat($arr_data)
    {
        $cron_format = "";
        $cron_format .= self::parseArrayToItem($arr_data['minute'], "getMinuteRange")." ";
        $cron_format .= self::parseArrayToItem($arr_data['hour'], "getHourRange")." ";
        $cron_format .= self::parseArrayToItem($arr_data['date'], "getDateRange")." ";
        $cron_format .= self::parseArrayToItem($arr_data['month'], "getMonthRange")." ";
        $cron_format .= self::parseArrayToItem($arr_data['day'], "getDayRange")." ";
        return trim($cron_format);
    }

    public static function parseItemToArray($item_str, $item_type_func)
    {
        $item_arr = [];
        if($item_str == "*"){
            $item_arr = self::$item_type_func();
        }else{
            $item_arr = explode(",", $item_str);
            foreach ($item_arr as $key_item => $value_item) {
                if(!is_numeric($value_item) || !in_array($value_item, self::$item_type_func())){
                    unset($item_arr[$key_item]);
                }
            }
        }
        return $item_arr;
    }

    public static function parseArrayToItem($item_arr, $item_type_func)
    {
        $item_arr = (Array)$item_arr;
        sort($item_arr);
        foreach ($item_arr as $key_item => $value_item) {
            if(!in_array($value_item, self::$item_type_func())){
                unset($item_arr[$key_item]);
            }
        }
        if($item_arr == self::$item_type_func()){
            $item_str = "*";
        } else {
            $item_str = implode(",", $item_arr);
        }
        if(empty($item_arr)){
            $item_str = "*";
        }
        return $item_str;
    }


}
