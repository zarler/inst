<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: guorui
 * Date: 15/12/21
 * Time: 上午10:26
 */
define('PARTNER_CODE','9douyu'); //合作方标识
define('WEB_EVENT_ID','loan_professional_web'); //WEB应用事件标识
define('IOS_EVENT_ID','loan_professional_ios'); //IOS应用事件标识
define('ANDROID_EVENT_ID','loan_professional_android'); //ANDROID应用事件标识
//测试环境
//RiskService
/*define('WEB_SECRET_KEY','f346a5bb2bc94fc89e8aa921b61d2104');    //WEB应用秘钥
define('IOS_SECRET_KEY','294a0587388d4aa19f80d4b5001e65c1');    //IOS应用秘钥
define('ANDROID_SECRET_KEY','4aa40b37c100463e8316d84b7adb6b0b'); //ANDROID应用秘钥
define('REQUESTURL','https://apitest.tongdun.cn/riskService');                        //同盾风险决策API请求地址
//HitRuleDetail
define('PARTNER_KEY','f5a627456d05454e9edb1cbec67a43e4'); //合作方密匙
define('RULES_REQUESTURL','https://apitest.tongdun.cn/webService/hitRuleDetail'); //命中规则详情查询API请求地址*/

//正式环境
//RiskService
define('WEB_SECRET_KEY','67d78899b4bd4d208aacac7c45775a8f');    //WEB应用秘钥
define('IOS_SECRET_KEY','77168efa322b4f8da98c09953a73c3cf');    //IOS应用秘钥
define('ANDROID_SECRET_KEY','433b4969151b45e18fcebe43cd6641ff'); //ANDROID应用秘钥
define('REQUESTURL','https://api.tongdun.cn/riskService');                    //同盾风险决策API请求地址
//HitRuleDetail
define('PARTNER_KEY','82b7b960a24241ef8122a88f78e30d5a'); //合作方密匙
//define('RULES_REQUESTURL','https://api.tongdun.cn/webService/hitRuleDetail'); //命中规则详情查询API请求地址
define('RULES_REQUESTURL','https://api.tongdun.cn/risk/rule.detail/v2.2'); //命中规则详情查询API请求地址


// 快金申请模型接口 正式环境
define('APPLY_MODEL_PARTNER_CODE', '9douyu'); //合作方标识
define('APPLY_MODEL_URL', 'https://api.tongdun.cn/riskService/v1.1');
define('APPLY_MODEL_SECRET_KEY', '67d78899b4bd4d208aacac7c45775a8f');
define('APPLY_MODEL_EVENT_ID', 'LoaningQuery_web_20170911');
