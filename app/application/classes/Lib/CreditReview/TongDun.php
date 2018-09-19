<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/24
 * Time: 下午8:29
 */
class Lib_CreditReview_TongDun extends Lib_CreditReview_RiskCommon

{

    protected $action;

    const PROVIDER = "TongDun";


    public function RiskService()
    {
        $equipment = Model::factory('TongDun_Equipment')->get_one($this->user_id);
        if (!$equipment) {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_MISS_PARAM, 'message' => 'Equipment 数据不存在']);
        }
        $result = Lib::factory("Risk_API")->anti('TongDun')->query(
            'execute', [
                'user_id' => $this->user_id ? $this->user_id : '',
                "account_name" => $this->user['name'] ? $this->user['name'] : '',
                "account_mobile" => $this->user['mobile'] ? $this->user['mobile'] : '',
                "id_number" => $this->user['identity_code'] ? $this->user['identity_code'] : '',
                "event_type" => "ANDROID",
                "black_box" => $equipment['black_box'],
            ]
        )->execute()->body();
        if (is_array($result)) {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '接口请求成功']);
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
        $Original = Model::factory("AntiFraud_TongDun")->get_one_success_data($this->user_id);

        $Rules = Model::factory("AntiFraud_TongDun")->get_fraud_rule($Original['seq_id']);
        $new_rules = array();
        if (!empty($Rules)) {
            if ($Rules) {
                foreach ($Rules as $k => $v) {
                    $new_rules[$v['uuid']] = json_decode($v['hit_rules'], true);
                }
            }
        }

        $data = [
            'data' => [
                'Original' => $Original['response_data'] ? json_decode($Original['response_data'], true) : "",
                'Rules' => $new_rules ? $new_rules : "",
            ],
            'provider' => self::PROVIDER,
            'action' => $action,
        ];

        $this->send($data, self::PROVIDER, $action);

        return $this;
    }

    //第三步 获取决策

    public function getDecision($action)
    {
        $this->get(self::PROVIDER, $action);

        return $this;
    }


}