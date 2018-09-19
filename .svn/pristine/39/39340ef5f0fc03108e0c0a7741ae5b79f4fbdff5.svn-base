<?php

/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 2018/1/23
 * Time: 下午3:42
 *
 * Lib::factory("Logic_QianHai")->user_id(2423423)->action("RiskService")->getData();
 * Lib::factory("Logic_QianHai")->user_id(2423423)->action("RiskService")->sendData();
 * Lib::factory("Logic_QianHai")->user_id(2423423)->action("RiskService")->getDecision();
 *
 */
class Lib_CreditReview_QianHai extends Lib_CreditReview_RiskCommon
{

    public $user_id;
    public $user;
    protected $action;

    const PROVIDER = "QianHai";
    const ACTION = 'RiskService';

    public function user_id($user_id)
    {
        $this->user_id = $user_id;
        $user = Model::factory("User")->get_one($user_id);
        $this->user = $user;

        return $this;
    }


    public function getData()
    {
        $data = array('idCard' => $this->user['identity_code'], 'name' => $this->user['name']);
        $res = Lib::factory("Risk_API")->user_id($this->user_id)->anti("QianHai")->query('execute', $data)->execute()->body();
        if($res['status'] == 1 && $res['error'] ==Lib_Common::LIB_COMMON_API_RESULT_SUCCESS && $res['api_result']['status'] == 1 && $res['api_result']['data'] =='') {
            return ['status'=>true,'error'=>Lib_Common::LIB_COMMON_API_RESULT_SUCCESS,'api_result'=>['result'=>Lib_Common::LIB_COMMON_API_RESULT_SUCCESS,'review'=>Lib_CreditReview_RiskCommon::LIB_RISK_REVIEW_STATUS_PASS,'message'=>'更新记录并审核通过']];
        }
        return $res;
    }

    public function sendData($action=null)
    {
        if($action){
            $this->action = $action;
        }else{
            $this->action = self::ACTION;
        }
        $result = Model::factory("QianHai_Data")->get_data($this->user_id);
        $data = [
                'data' => json_decode($result['response_data'],true),
                'provider' => self::PROVIDER,
                'action' => $this->action,
        ];
        $this->send($data, self::PROVIDER, $this->action);

        return $this;
    }

    //第三步 获取决策

    public function getDecision($action=null)
    {
        if($action){
            $this->action = $action;
        }else{
            $this->action = self::ACTION;
        }
        $this->get(self::PROVIDER, $this->action);

        return $this;
    }

}