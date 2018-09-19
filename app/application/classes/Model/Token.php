<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/3
 * Time: 下午11:43
 */
class Model_Token extends Model_Database{

    const LOGIN_PERIOD = 3888000;           //登录成功后 有效期 45天
    const NOLOGIN_PERIOD = 86400;           //未登录的TOKEN有效期 1天
    const RENEW_PERIOD = 864000;            //每次续约10天
    const RENEW_MAX_TIMES = 5;              //可续约5次
    const TARGET_PERIOD = 86400;            //跳转成功新创建的APP TOKEN有效周期

    const STATUS_ACTIVE = 1;                //有效
    const STATUS_REMOVED = 2;               //无效(注销)


    public function make_token(){
        return 'TA'.date('YmdHis').Text::random('alpha',16);
    }

    /** 创建token
     * @param array $array
     * @return bool
     */

    public function create($array = array()){
        if(!isset($array['token']) || !isset($array['app_id']) ){
            return FALSE;
        }
        $token = $array['token'];
        $md5 = md5($token);
        $app_id = $array['app_id'];
        $user_id = isset($array['user_id']) && $array['user_id']>0  ? (int) $array['user_id'] :0 ;
        $app_ver = isset($array['app_ver']) ? trim($array['app_ver']) : '';
        $app_os = isset($array['app_os']) ? trim($array['app_os']) : '';
        $unique_id = isset($array['unique_id']) ? trim($array['unique_id']) : '';
        $ip = isset($array['ip']) ? trim($array['ip']) : '';
        $create_time = isset($array['create_time']) && $array['create_time']>0 ? (int)$array['create_time'] : time() ;
        $expire_time = isset($array['expire_time']) && $array['expire_time']>0 ? (int)$array['expire_time'] :  time()+self::NOLOGIN_PERIOD ;


        list($insert_id,$affected_rows)=DB::insert("app_token",array('token','md5','app_id','user_id','app_ver','app_os','unique_id','ip','expire_time','create_time','status'))
            ->values(array($token, $md5, $app_id, $user_id, $app_ver, $app_os, $unique_id, $ip, $expire_time, $create_time,Model_Token::STATUS_ACTIVE))
            ->execute();
        return $insert_id;
    }


    /** 更新token记录
     * @param $token
     * @param array $array
     * @return bool
     */

    public function update_by_id($id,$array = array()){
        if(!$id){
            return FALSE;
        }
        if($rs = DB::select()->from('app_token')->where('id','=',$id)->execute()->current()){
            Lib::factory('TCCache_Token')->token($rs['token'])->remove();//清除缓存
        }
        return  NULL !== DB::update('app_token')->set($array)->where('id','=',(int)$id)->execute() ;
    }


    /** 更新token记录
     * @param $token
     * @param array $array
     * @return bool
     */

    public function update($token,$array = array()){
        if(!$token){
            return FALSE;
        }
        Lib::factory('TCCache_Token')->token($token)->remove();//清除缓存
        return  NULL !== DB::update('app_token')->set($array)->where('md5','=',md5($token))->execute() ;
    }


    /** 单条token数据
     * @param $id
     * @return mixed
     */

    public function get_one_by_id($id){
        return DB::select()->from('app_token')->where('id','=',$id)->execute()->current();
    }


    /** 单条token数据
     * @param $token
     * @return mixed
     */

    public function get_one($token){
        return DB::select()->from('app_token')->where('md5','=',md5($token))->execute()->current();
    }


    /** 单条token数据,条件为user_id如果有多个时取最后一个
     * @param $user_id
     * @return mixed
     */

    public function get_one_by_user_id($user_id){
        return DB::select()->from('app_token')->where('user_id','=',(int)$user_id)->order_by('id','DESC')->limit(1)->execute()->current();
    }


    /** 统计同一类APP下一个user_id对应多少个token
     * @param $app_id
     * @param $user_id
     * @return bool|int
     */

    public function get_count($app_id,$user_id){
        if(!$app_id || !$user_id){
            return  FALSE;
        }
        $rs = DB::select(array(DB::expr('COUNT(*)'), 'total'))
            ->from('app_token')
            ->where('user_id','=',$user_id)
            ->and_where('app_id','=',$app_id)
            ->execute()
            ->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }


    /** 注销
     * @param $token
     * @return bool
     */

    public function remove($token){
        if($token){
            return FALSE;
        }
        return $this->update_by_token($token,array('status'=>Model_Token::STATUS_REMOVED));
    }


    /** 清理
     * @param $time
     * @return bool
     */
    public function gc($time){
        if(!$time){
            $time = time();
        }
        DB::update('app_token')
            ->set(array('status'=>Model_Token::STATUS_REMOVED))
            ->where('expire_time','<',$time)
            ->and_where('status','!=',Model_Token::STATUS_REMOVED)
            ->execute();
        return NULL !== DB::delete('app_token')
            ->where('status','=',Model_Token::STATUS_REMOVED)
            ->execute();
    }




}