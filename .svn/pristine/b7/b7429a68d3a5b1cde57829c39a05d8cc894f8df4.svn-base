<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/6/14
 * Time: 上午12:09
 *
 * 默认配置
 *
 */
class Def {

    const USER_BASE_CREDIT_PASS_MAX_AMOUNT = 3000;   //用户基础授信通过获得基本额度
    const USER_BASE_CREDIT_PASS_FASTLOAN_MAX_AMOUNT = 1500;//用户通过基础授信后分配初始极速贷额度

    const USER_BASE_CREDIT_PASS_ENSURELOAN_RATE = 1.0; //通过基础授信 担保比例
    const USER_BASE_CREDIT_PASS_FASTLOAN_CHARGE_RATE = 1.0; //通过基础授信 极速贷手续费折扣率

    //[begin]   2017-2-17    ------------------------- 这部分2017-2-17后已基本无用
    const MAX_AMOUNT = 5000;    //系统默认最大借款额度
    const MIN_AMOUNT = 500;     //系统默认最小借款额度
    const LOAN_UNIT = 100;       //最小借额度增长幅度
    const MAX_DAY = 21;         //最大借款天数
    const MIN_DAY = 14;          //最小借款天数
    const ENSURE_RATE = 1;      //默认担保授信比例
    //[end]     2017-2-17    ----------------------------

    const PARTNER_CODE = 'timecash';        //平台区别码


    //我的快金::授信管理
    const SHOW_CREDIT_OPTION = array(
        'identity' => 1 ,//实名认证
        'work' =>  1,//工作信息
        'home'=> 1,//家庭信息
        'contact' => 1,//紧急联系人
        'taobao' => 0,//淘宝
        'jingdong' =>1,//京东
        'mno'=> 1,//移动运营商
        'faceid'=>1,//人脸识别
        'phonebook'=>1,//通讯录
        'sms'=>1,//短信记录
        'call'=>1,//通话记录
        'location'=>1,//位置信息
    );


    //短信验证码开关
    const VERIFY_CODE = array(
        'register' => 1,//注册
        'login'=> 0,//登录
        'reset_password'=>1,//重置密码
        'bankcard_add'=>0,//绑定借记卡
        'bankcard_modify'=>0,//改借记卡
        'creditcard_add'=>0,//绑定信用卡
    );



}