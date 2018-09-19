<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/2/1
 * Time: 下午4:03
 */
class Lib_Moxie_Message

{

    protected $_post = null;
    protected $_get = null;
    const URL = 'https://tenant.51datakey.com';

    const ACTION_URL = [
        'Email' => self::URL.'/email/report_data?data=',
        'Fund' => self::URL.'/fund/report_data?data=',
        'JD' => self::URL.'/jingdong/report_data?data=',
        'Taobao' => self::URL.'/taobao/report_data?data=',
        'SocialSecurity' => self::URL.'/security/report_data?data=',
        'Mno' => self::URL.'/carrier/report_data?data=',
    ];

    public function query($data)
    {
        $data = Model::factory('Moxie_Message')->get_one($data['user_id'], $data['action']);
        if (isset($data['message'])) {
            return self::ACTION_URL[$data['action']].$data['message'];
        } else {
            return null;
        }
    }

}