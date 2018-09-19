<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: wangxuesong
 * Date: 16/05/05
 * Time: 下午3:20
 */
class Model_JuXinLi_User extends Model_Database{


    /**
     * 根据userId获取用户信息列表
     * @param $userId
     * @return bool
     */

    public function getUserInformation($userId){


        try{
            $user     = DB::select('mobile','name','identity_code')->from('user')->where('id','=',$userId)->execute()->current();
            $home     = DB::select('address','tel')->from('ci_home')->where('user_id','=',$userId)->execute()->current();
            $work     = DB::select('address','tel')->from('ci_work')->where('user_id','=',$userId)->execute()->current();
            $contact  = DB::select("name","relation","mobile")->from('ci_contact')->where('user_id','=',$userId)->execute()->current();

            $home = !empty($home)?$home:[];
            $work = !empty($work)?$work:[];
            $contact = !empty($contact)?$contact:[];
            $data = array_merge($user,$home,$work,$contact);

        } catch (Exception $e){
            return false;
        }

        return $data;

    }


    /**
     * 根据userId获取用户信息列表
     * @param $userId
     * @return bool
     */

    public function getUserIdRecord($userId){


        try{
            $data    = DB::select('mobile','name','identity_code')->from('user')->where('id','=',$userId)->execute()->current();

        } catch (Exception $e){
            return false;
        }

        return $data;

    }
}