<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/24
 * Time: 下午1:52
 *
 * Lib::factory("CreditReview_Moxie")->user_id(1000007)->getDecision('Mno')->body();
 */
class Lib_CreditReview_Moxie extends Lib_CreditReview_RiskCommon

{

//    protected $user;
    protected $action;

    const PROVIDER = "Moxie";
    public $redisHash;

    public function __construct()
    {
        $this->redisHash = Redis_Hash::instance();
    }

    //魔蝎-运营商
    public function Mno()
    {

        $rs = Model::factory('Moxie_Queue')->get_one($this->user_id, 'Mno');

        $res = Lib::factory('Moxie_GetReport')->query(
            [
                'task_id' => $rs['task_id'],
                'extends' => $rs['extends'],
                'mobile' => $rs['mobile'],
                'action' => $rs['action'],
            ]
        );

        if ($this->is_json($res)) {
            $data = json_decode($res, true);
            if (isset($data['status']) && $data['status'] == true) {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '接口请求成功']);
            } else {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
        }

        return $this;
    }

    //魔蝎-公积金
    public function Fund()
    {
        $rs = Model::factory('Moxie_Queue')->get_one($this->user_id, 'Fund');
        $res = Lib::factory('Moxie_GetReport')->query(
            [
                'task_id' => $rs['task_id'],
                'extends' => $rs['extends'],
                'action' => $rs['action'],
            ]
        );

        if ($this->is_json($res)) {
            $data = json_decode($res, true);
            if (isset($data['status']) && $data['status'] == true) {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '接口请求成功']);
            } else {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
        }

        return $this;
    }

    //魔蝎-淘宝
    public function Taobao()
    {
        $rs = Model::factory('Moxie_Queue')->get_one($this->user_id, 'Taobao');

        $res = Lib::factory('Moxie_GetReport')->query(
            [
                'task_id' => $rs['task_id'],
                'extends' => $rs['extends'],
                'action' => $rs['action'],
            ]
        );

        if ($this->is_json($res)) {
            $data = json_decode($res, true);
            if (isset($data['status']) && $data['status'] == true) {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '接口请求成功']);
            } else {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
        }

        return $this;
    }

    //魔蝎-京东
    public function JD()
    {
        $rs = Model::factory('Moxie_Queue')->get_one($this->user_id, 'JD');
        $res = Lib::factory('Moxie_GetReport')->query(
            [
                'task_id' => $rs['task_id'],
                'extends' => $rs['extends'],
                'action' => $rs['action'],
            ]
        );

        if ($this->is_json($res)) {
            $data = json_decode($res, true);
            if (isset($data['status']) && $data['status'] == true) {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '接口请求成功']);
            } else {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
        }

        return $this;
    }

    //魔蝎-社保
    public function SocialSecurity()
    {
        $rs = Model::factory('Moxie_Queue')->get_one($this->user_id, 'SocialSecurity');
        $res = Lib::factory('Moxie_GetReport')->query(
            [
                'task_id' => $rs['task_id'],
                'extends' => $rs['extends'],
                'action' => $rs['action'],
            ]
        );

        if ($this->is_json($res)) {
            $data = json_decode($res, true);
            if (isset($data['status']) && $data['status'] == true) {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '接口请求成功']);
            } else {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
        }

        return $this;
    }

    //魔蝎-信用卡账单
    public function Email()
    {

        $rs = Model::factory('Moxie_Queue')->get_one($this->user_id, 'Email');
        if (!isset($rs['email_id'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, '接口异常');
        }
        $res = Lib::factory('Moxie_GetReport')->query(
            [
                'task_id' => $rs['task_id'],
                'extends' => $rs['extends'],
                'email_id' => $rs['email_id'],
                'action' => $rs['action'],
            ]
        );


        if ($this->is_json($res)) {
            $data = json_decode($res, true);
            if (isset($data['status']) && $data['status'] == true) {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '接口请求成功']);
            } else {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '接口异常']);
        }

        return $this;
    }

    //第一步远程请求
    public function getData($action)
    {
        if ($action) {

            call_user_func_array('self::'.$action, ['data' => []]);
        } else {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, '缺少参数 action');
        }

        return $this;
    }

    //第二步 获取数据并发送

    public function sendData($action)
    {
        if ($action == 'Mno') {
            //紧急联系人
            $Contact = Model::factory('CreditInfo_Contact')->get_by_array($this->user_id);
            if (!empty($Contact)) {
                foreach ($Contact as $k => $v) {
                    $Contact_new[$k]['name'] = $v['name'];
                    $Contact_new[$k]['tel'] = $v['mobile'];
                }
            } else {
                $Contact_new = '';
            }
            unset($Contact);
            $this->user_id = 2;
            //短信记录 先读redis如果有直接用，没有读DB
            $sms_keys = $this->redisHash->get('inst_sms_add_'.$this->user_id);
            if (!isset($sms_keys['message'])) {
                $sms_new = $sms_keys['message'];

            } else {
                $sms = Model::factory('App_SMSRecord')->get_by_user_id($this->user_id);
                if (isset($sms['sms'])) {
                    $sms_json = $sms['sms'];
                    $sms_new = json_decode($sms_json, true);
                    foreach ($sms_new as $k => $v) {
                        if ($v['type'] == 1) {
                            $sms_new[$k]['type'] = 'receive';
                        } else {
                            $sms_new[$k]['type'] = 'send';
                        }
                    }
                    $sms_new = json_encode($sms_new);

                } else {
                    $sms_new = '';
                }
            }


            //电话本 先读redis如果有直接用，没有读DB
            $phone_keys = $this->redisHash->get('inst_phone_add_'.$this->user_id);

            if (!isset($phone_keys['phonebook'])) {
                $phone_new = $phone_keys['phonebook'];
            } else {
                $phonebook = Model::factory('App_PhoneBook')->get_by_user_id($this->user_id);
                if (isset($phonebook['phonebook'])) {
                    $phone_new = $phonebook['phonebook'];
                } else {
                    $phone_new = '';
                }
            }
            //原文
            $Original = Model::factory("Moxie_Report")->get_report($this->user_id, $action, 1);

            $Report = Model::factory("Moxie_Report")->get_report($this->user_id, $action, 2);
//                var_dump($Report);die;
            $data = [
                'data' => [
                    'Original' => json_decode($Original['data'], true),
                    'Report' => json_decode($Report['data'], true),
                    'Contact' => $Contact_new ? $Contact_new : "",
                    'Sms' => $sms_new ? json_decode($sms_new, true) : "",
                    'PhoneBook' => $phone_new ? json_decode($phone_new, true) : "",
                ],
                'provider' => self::PROVIDER,
                'action' => $action,
            ];
        } else {
            $Original = Model::factory("Moxie_Report")->get_report($this->user_id, $action, 1);
            $Report = Model::factory("Moxie_Report")->get_report($this->user_id, $action, 2);
            //        var_dump($Original);die;
            if (isset($Original) && isset($Report)) {
                $data = [
                    'data' => [
                        'Original' => json_decode($Original['data'], true),
                        'Report' => json_decode($Report['data'], true),
                    ],
                    'provider' => self::PROVIDER,
                    'action' => $action,
                ];
            } else {
                $this->response(false, [], Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, '缺少参数 action');
            }
        }
        $this->send($data, self::PROVIDER, $action);
        unset($Original);
        unset($Report);
        unset($Contact_new);
        unset($sms_new);
        unset($phone_new);
        unset($Original);

        return $this;
    }

    //第三步 获取决策

    public function getDecision($action)
    {
        $this->get(self::PROVIDER, $action);

        return $this;
    }


}