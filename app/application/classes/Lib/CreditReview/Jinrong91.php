<?php

/**
 * Created by IntelliJ IDEA.
 * User: yangyuexin
 * Date: 2018/1/24
 * Time: 下午5:57
 */
class Lib_CreditReview_Jinrong91 extends Lib_CreditReview_RiskCommon

{

    protected $action;

    const PROVIDER = "Jinrong91";


    //第一步远程请求
    public function getData($action)
    {
        $result = Lib::factory("Risk_API")->anti("Jinrong91")->query("getTrxno", [
            'user_id' => $this->user_id,
            'action' => $action,
        ])->execute();
        return $result;

    }

    //第二步 获取数据并发送

    public function sendData($action)
    {
        $result = Lib::factory("Risk_API")->anti("Jinrong91")->query("getInfo", [
            'user_id' => $this->user_id,
            'action' => $action,
        ])->execute()->body();

        if ($result['status'] == true && isset($result['api_result']) && count($result['api_result']['data']) > 0) {
            $re = json_decode($result['api_result']['data'], true);
            $data = [
                'data' => [
                    'Original' => $re,
                    'Report' => []
                ],
                'provider' => self::PROVIDER,
                'action' => $action
            ];
        } else {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, isset($result['api_result']['message']) ? $result['api_result']['message'] : '获取91内容失败');
        }
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
