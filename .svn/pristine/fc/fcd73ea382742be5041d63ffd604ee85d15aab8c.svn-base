<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/3/14
 * Time: 上午11:42
 */
return array(
    //九斗鱼接口
    '9douyu' => array(
        //验卡通道
        'auth_key' => "xuN1mFEI3viLXMg7",
        'auth_url' => 'https://www.9douyu.com/api/checkCard',
        //卡信息查询
        'card_info_key' => "xuN1mFEI3viLXMg7",
        'card_info_url' => 'https://www.9douyu.com/api/fetchCardInfo',
        //身份证接口
        'verify_key' => 'dC9uwlX26g',
        'verify_url' => 'http://api.jiudouyu.com.cn/API/Verify/doVerify',
        //'verify_remote_url' => 'https://www.9douyu.com/API/Verify/remoteVerify',
    ),

    //短信通道
    'SMS' => array(
        //短信通道密文
        'key' => '14b4c92ee345f28284a1cf5a0bf77026',
        //发送短信地址
        'send_url' => 'http://sms-api.luosimao.com/v1/send.json',
        ////查询短信剩余条数地址
        'status_url' => 'http://sms-api.luosimao.com/v1/status.json',
    ),
    
    //短信通道-讯淘
    'SMSXuntao'     =>[
        'yzm' => [
            'uid'       => '305042',
            'pwd'       => md5('xgkjgs'),
            'url'       => 'http://101.201.41.56:9885/c123/sendsms?'
        ],
        'yx' => [
            'uid'       => '305080',
            'pwd'       => md5('xgkjgs'),
            'url'       => 'http://223.223.180.20:9885/c123/sendsms?'
        ]
    ],
    
    //短信通道-盟信
    'mengxin' => array(
        //短信通道用户名，密码
        1 => array(
            'user_id'=> 473,
            'user' => 'xgkj'
        ),
        2 => array(
            'user_id'=> 476,
            'user' => 'xgkjcf'
        ),
        3 => array(
            'user_id'=> 503,
            'user' => 'xgkjyx'
        ),
        'password' => 'Abcd1234',
        //发送短信地址
        'send_url' => 'http://120.25.254.138:8888/v2sms.aspx',
        ////查询短信剩余条数地址
        'status_url' => 'http://120.25.254.138:8888/v2sms.aspx',
    ),
    
    'newmengxin' => array(
        //短信通道用户名，密码
        1 => array(
            'user' => 'xgkjhy'
        ),
        2 => array(
            'user' => 'xgkjcf'
        ),
        'password' => 'Abcd1234',
        //发送短信地址
        'send_url' => 'http://119.23.58.200/msg/HttpSendSM',
        ////查询短信剩余条数地址
        'status_url' => 'http://120.25.254.138:8888/v2sms.aspx',
    ),

    'PS' => array(
        'client_id'		=> 'admin',						//client_id 配置client键值
        'client_key'	=> 'xqP8MuCMZEhJuOaSbL',		//CLIENT_KEY 配置client键值
        'ps_url'		=> 'http://test23.ps.timecash.cn',	//ps.timecash.cn
    ),

    //faceid 配置
    'FaceID' => array(
        'api_key'=>'KeLoHCW03dp99P5pEMZNg6l1mnbz2E8t',
        'api_secret' => 'GcQLwRrvIvgOCp9wN89gc4tv7RFKSBdw',
        'server' => 'http://api.faceid.com/faceid/v1',
        'server_v2' => 'https://api.megvii.com/faceid/v2',
    ),

    //极光推送
    'JPush' => [
        'apns_production' => false,//测试false  线上true
        'app_key'         => '0a17ac4e1d43bcfd6be9abca',
        'master_secret'   => '37c30e27d7ecc1e709af4394',
//        'app_key'         => '36ee82a5c19f7f0bf2861ba5',//测试
//        'master_secret'   => 'b5c7191d47cd11f83efda09f',//测试
    ],
    
    //连连支付
    'LianLianPay' => array(
        'company_name' => '星果科技有限公司（快金）',
        'company_contact_way' => '010-56592060',
        'trade_desc' => '快金还款',
        'return_url' => 'http://m.timecash.cn/APIPay_Sign_LianLianPay_Return/',
        'notify_url' => 'http://capi.timecash.cn/APIPay_Deduct_LianLianPay_Notify/',
    ),
    //魔蝎数据
    'Moxie'=>array(
        'ApiKey' => 'b72f70980d76463c851f81348429f398',
        'Token' => '9f3065420dda4713870ed8e4c38822dc',
        'Secret' => '27c7e4bc518c48d095d9caf544771876',
        'backUrl' => 'http://test23.api.timecash.cn/Moxie/Bank/SocialSecurity',
        'url' => 'https://api.51datakey.com/h5/importV3',                   //公共url
        'SocialSecurityPath' => '/index.html#/securityList',    //社保path
        'FundPath' => '/index.html#/fundlist', //公积金path
        'TaobaoPath' => '/index.html#/taobao',  //淘宝path
        'JDPath' => '/index.html#/jingdong',  //京东path
        'BankPath' => '/index.html#/banklist',   //网银path
        'EmailPath' => '/index.html#/email',  //邮箱信用卡path
        'ShixinPath' => '/index.html#/shixin', //法院失信人path
        'ZhixingPath' => '/index.html#/zhixing',    //法院被执行人path
        'MnoPath' => '/index.html#/carrier',    //运营商path
        'ChisPath' => '/index.html#/chsi',   //学信网path
    )
    
);
