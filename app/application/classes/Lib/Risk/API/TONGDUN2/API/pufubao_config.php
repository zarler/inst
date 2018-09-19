<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: guorui
 * Date: 15/12/21
 * Time: 上午10:26
 */
define('PARTNER_CODE','9douyu'); //合作方标识
define('WEB_EVENT_ID','Loan_web_20170421'); //WEB应用事件标识
define('IOS_EVENT_ID','Loan_ios_20170421'); //IOS应用事件标识
define('ANDROID_EVENT_ID','Loan_android_20170421'); //ANDROID应用事件标识
//测试环境
//RiskService
define('WEB_SECRET_KEY','75fd298ab9104d33b93f938a145a8129');    //WEB应用秘钥
define('IOS_SECRET_KEY','fd448db126b74a1a94195a763f7111a0');    //IOS应用秘钥
define('ANDROID_SECRET_KEY','c34f859923c24a929155db26f70d8cb2'); //ANDROID应用秘钥
define('REQUESTURL','https://apitest.tongdun.cn/riskService');                        //同盾风险决策API请求地址
//HitRuleDetail
define('PARTNER_KEY','f5a627456d05454e9edb1cbec67a43e4'); //合作方密匙
define('RULES_REQUESTURL','https://apitest.tongdun.cn/webService/hitRuleDetail'); //命中规则详情查询API请求地址

//正式环境
//RiskService
/*define('WEB_SECRET_KEY','1034d3d52b8a4b128536fad4b7036db1');    //WEB应用秘钥
define('IOS_SECRET_KEY','53299531fe9049cebbecfc2e5b4cd3f3');    //IOS应用秘钥
define('ANDROID_SECRET_KEY','4b2f1a88ba09427296edc4696ae5b879'); //ANDROID应用秘钥
define('REQUESTURL','https://api.tongdun.cn/riskService');                    //同盾风险决策API请求地址
//HitRuleDetail
define('PARTNER_KEY','82b7b960a24241ef8122a88f78e30d5a'); //合作方密匙
define('RULES_REQUESTURL','https://api.tongdun.cn/webService/hitRuleDetail'); //命中规则详情查询API请求地址*/