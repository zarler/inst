<?php
defined('SYSPATH') or die('No direct script access.');
/**
 *  芝麻分调用
 * Created by PhpStorm.
 * Permission: wangxuesong
 * Date: 16/6/23
 * Time: 下午4:17
 */

class Model_User_ZhiMaScore extends Model_Database {

    const SCORE_UPPER_STRATA =650; //芝麻信用大于等于700分
    const SCORE_MIDDLE_LEVEL =549; //芝麻信小于等于599分
    const  STATUS_ZHIMA_NORMAL= 1;//芝麻信用状态正常
    const  STATUS_ZHIMA_ABNORMAL= 1;//芝麻信用状态异常
    const  STATUS_ZHIMA_LOSE= 1;//芝麻信用状态失败



    public function getZhiMaScoreByUserId($userId){
        if(empty($userId)){
            return FALSE;
        }
        return DB::select()->from('zhima_score')->where('user_id','=',(int)$userId)->and_where("type","=",self::STATUS_ZHIMA_NORMAL)->order_by('id','DESC')->limit(1)->execute()->current();
    }

}

