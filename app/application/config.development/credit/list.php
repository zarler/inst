<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * 站点配置 config 写在这里面可多纬
 *
 * 授信URL APP & WEB
 */
return [
    'app_phonebook' => [
        'start' => 'app://app.inst/Credit/AppPhonebookCollect',
        'success' => '',
        'validity' => '311040000',
        'failed' => '',
        'group' => '',
    ],//手机电话本采集

    'app_callhistory' => [
        'start' => 'app://app.inst/Credit/AppCallHistoryCollect',
        'success' => '',
        'validity' => '311040000',
        'failed' => '',
        'group' => '',
    ],//手机通话记录采集

    'app_sms' => [
        'start' => 'app://app.inst/Credit/AppSmsCollect',
        'success' => '',
        'validity' => '311040000',
        'failed' => '',
        'group' => '',
    ],//手机短信采集

    'app_location' => [
        'start' => 'app://app.inst/Credit/LocationCollect',
        'success' => '',
        'validity' => '311040000',
        'failed' => '',
        'group' => '',
    ],//地理位置采集

    'app_authorize' => [
        'start' => 'app://app.inst/Credit/AppAuthorize',
        'success' => '',
        'validity' => '311040000',
        'failed' => '',
        'group' => '',
    ],//应用授权:

    'app_homeinfo' => [
        'start' => '/v/Chedit/HomeInfo',
        'success' => '',
        'validity' => '311040000',
        'failed' => '',
        'group' => '',
    ],//居住信息

    'app_faceid' => [
        'start' => 'app://app.inst/Credit/FaceIdCheck',
        'success' => '',
        'validity' => '311040000',
        'failed' => '',
        'group' => '',
    ],//人脸识别

    'app_mno' => [
        'start' => 'app://app.inst/Credit/H5',
        'success' => '',
        'validity' => '2592000',
        'failed' => '',
        'group' => '',
    ],//运营商认证

    'app_bankcard' => [
        'start' => 'app://app.inst/BankCard/Add',
        'success' => '',
        'validity' => '311040000',
        'failed' => '',
        'group' => '',
    ],//绑定银行卡

    'app_contact' => [
        'start' => '/v/Chedit/EmergencyContact',
        //http://m.inst.cn/RegisterApp/Company?target_token={target_token}
        'success' => '',
        'validity' => '311040000',
        'failed' => '',
        'group' => '',
    ],//紧急联系人

    'app_work' => [
        'start' => '/v/Chedit/WorkInfo',
        //http://m.inst.cn/RegisterApp/Company?target_token={target_token}
        'success' => '',
        'validity' => '311040000',
        'failed' => '',
        'group' => '',
    ],//工作信息

    'app_creditcardbill' => [
        'start' => 'app://app.inst/Credit/H5',
        'success' => '',
        'validity' => '2592000',
        'failed' => '',
        'group' => 'no',
    ],//信用卡账单

    'app_jingdong' => [
        'start' => 'app://app.inst/Credit/H5',
        'success' => '',
        'validity' => '2592000',
        'failed' => '',
        'group' => 'no',
    ],//京东

    'app_taobao' => [
        'start' => 'app://app.inst/Credit/H5',
        'success' => '',
        'validity' => '2592000',
        'failed' => '',
        'group' => 'no',
    ],//淘宝

    'app_socialsecurity' => [
        'start' => 'app://app.inst/Credit/H5',
        'success' => '',
        'validity' => '2592000',
        'failed' => '',
        'group' => 'no',
    ],//社保

    'app_fund' => [
        'start' => 'app://app.inst/Credit/H5',
        'success' => '',
        'validity' => '2592000',
        'failed' => '',
        'group' => 'no',
    ],//公积金
];
