<?php
/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/24
 * Time: 下午8:41
 */
class Lib_CreditReview_BaiRong extends Lib_CreditReview_RiskCommon

{
    protected $action;

    const PROVIDER = "BaiRong";

    //特殊名单查询
    public function SpecialList(){

        $user_data = Model::factory('User')->get_one($this->user_id);
        if(empty($user_data)){
            return false;
        }
        // 组织数据
        $data = [
            'targetList'  => [
                [
                    'user_id'       => $user_data['id'],
                    'name'          => $user_data['name'],
                    'id'            => $user_data['identity_code'],
                    'cell'          => $user_data['mobile'],
                ]
            ],
            'headerTitle' => ['SpecialList_c']
        ];
        $response_data = Lib::factory("Risk_API")->anti('BaiRong')->query(
            'execute', $data
        )->execute()->body();

        Model::factory('AntiFraud_BaiRong')->add_bairong_data(['user_id' => $this->user_id, 'module' => 'SpecialList_c', 'request_data' => json_encode($data), 'response_data' => json_encode($response_data['api_result']['api_result']), 'create_time' => time()]);
        if(is_array($response_data)){
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '接口请求成功']);
        }else{
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

        $Original = Model::factory("BaiRong_Data")->get_data($this->user_id);

        if (isset($Original['response_data'])) {
            $data = [
                'data' => [
                    'Original' => json_decode($Original['response_data'],true),
                ],
                'provider' => self::PROVIDER,
                'action' => $action,
            ];
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