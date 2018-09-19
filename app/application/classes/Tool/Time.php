<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/26
 * Time: 下午8:52
 *
 *  Tool::factory('Time')->Expire('1516971489','Mno');
 *
 */
class Tool_Time
{

    const EXPIRE_TIME =
        [
            'Moxie' => [
                'Mno' => 'app_mno',
                'Taobao' => 'app_taobao',
                'JD' => 'app_jingdong',
                'SocialSecurity' => 'app_socialsecurity',
                'Fund' => 'app_fund',
            ],
            'ZhiCheng' => [
                'RiskService' => 'app_Zhicheng',
            ],
            'BaiRong' => [
                'SpecialList' => 'app_Bairong',
            ],
            'Jinrong91' => [
                'RiskService' => 'app_Jinrong91',
            ],
            'TongDun' => [
                'RiskService' => 'app_Tongdun',
            ],
        ];

    /**
     * @param $time
     * @param $provider
     * @param $action
     * @return bool
     * 返回true = 过期
     * 返回false = 有效
     */
    public function Expire($time, $provider, $action)
    {

        $expire_time = Kohana::$config->load('credit/list')->get(self::EXPIRE_TIME[$provider][$action]);

        if (isset($expire_time)) {
            if (time() > ($expire_time['validity'] + $time)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
