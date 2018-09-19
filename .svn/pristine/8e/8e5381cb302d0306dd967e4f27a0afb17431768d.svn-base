<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: guorui
 * Date: 2016/1/8
 * Time: 15:48
 *
 * 2016-3-13 by majin
 *   将查询类操作归纳进Query目录. 去除网址使用CONFIG中的API地址配置.
 */
class Controller_Query_AuthCard extends AdminController
{
    public function action_Index()
    {
        //4要素参数
        $holder = trim($this->request->post('holder'));
        $identity_code = trim($this->request->post('identity_code'));
        $mobile = trim($this->request->post('mobile'));
        $mobile = str_replace(' ', '', $mobile);
        $bank_no = trim($this->request->post('bank_no'));
        $bank_no = str_replace(' ', '', $bank_no);

        //6要素参数
        $security_code = trim($this->request->post('security_code'));
        $security_code = str_replace(' ', '', $security_code);
        $expire_date = trim($this->request->post('expire_date'));
        $expire_date = str_replace(' ', '', $expire_date);
        $expire_date = strtotime($expire_date);

        $message = null;
        $result = null;
        $result_array = null;

        $card_type_array = array(
            '2' => '借记卡',
            '3' => '信用卡',
        );

        $bank_name = null;
        $card_type = null;


        $postarr['holder'] = $holder ? $holder : '';
        $postarr['identity_code'] = $identity_code ? $identity_code : '';
        $postarr['mobile'] = $mobile ? $mobile : '';
        $postarr['bank_no'] = $bank_no ? $bank_no : '';
        $postarr['security_code'] = $security_code ? $security_code : '';
        $postarr['expire_date'] = $expire_date ? $expire_date : '';

        if (isset($this->post['submit']) && $this->post['submit']) {
            $valid = Validation::factory($this->post)->rule('holder', 'not_empty')->rule('identity_code',
                'not_empty')->rule('mobile', 'not_empty')->rule('bank_no', 'not_empty');
            if ($valid->check()) {
                $data = array(
                    'card_holder' => $holder,
                    'identity_code' => $identity_code,
                    'phone' => $mobile,
                    'card_no' => $bank_no,
                    'cvv' => $security_code,
                    'enddate' => $expire_date,
                );

                $result = Lib::factory('AuthCard_API')->auth('geo',$data)->execute()->asJson();
                if (Lib::factory('Array')->isJson($result)) {
                    $result_array = json_decode($result, true);
                }

                if (isset($result_array['status']) && $result_array['status'] == true && isset($result_array['api_result']['result']) && $result_array['api_result']['result'] == '0000') {
                    //卡信息
                    $card_info_result = Lib::factory('CardInfo_API')->query($data)->execute()->asJson();
                    if (Lib::factory('Array')->isJson($card_info_result)) {
                        $card_info = json_decode($card_info_result, true);
                        if (isset($card_info['status']) && $card_info['status'] == true && isset($card_info['api_result']['result']) && $card_info['api_result']['result'] == '0000') {
                            $bank_name = $card_info['api_result']['info']['bank_name'];
                            $card_type = $card_type_array[$card_info['api_result']['info']['card_type']];
                        }
                    }
                }
            } else {
                $message = array('type' => 'error', 'message' => '查询失败,请查看是否有必填信息未填.');
            }
        }
        Template::factory('Query/AuthCard', array(
            'postarr' => $postarr,
            'result' => $result,
            'result_array' => $result_array,
            'message' => $message,
            'bank_name' => $bank_name,
            'card_type' => $card_type
        ))->response();
    }
}