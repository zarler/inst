<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/15
 * Time: 下午2:28
 */
class Controller_Notify_Risk extends Controller
{

    public function action_Index()
    {
        $check = file_get_contents("php://input", true);
        $ss = json_decode($check, true);
        $risk = Model::factory("RiskDecision")->getOneByNo('request_no', $ss['requestNo']);
        $request_data = array(
            'checkNo' => $risk['check_no'],
            'sign' => '213213213',
        );
        $re = DClient::factory("query")->post(json_encode($request_data))->execute()->body();
        $result = json_decode($re, true);
        $res = Model::factory($risk['provider']."_Short")->get_one($risk['user_id'], $risk['action']);
        if (!empty($res)) {
            if ($ss['isFlag'] == 1) {
                Model::factory($risk['provider']."_Short")->change(
                    $res['id'],
                    [
                        'pass_time' => time(),
                        'result' => Lib_CreditReview_RiskCommon::LIB_RISK_REVIEW_STATUS_PASS,
                    ]
                );
            } else {
                Model::factory($risk['provider']."_Short")->change(
                    $res['id'],
                    [
                        'pass_time' => time(),
                        'result' => Lib_CreditReview_RiskCommon::LIB_RISK_REVIEW_STATUS_UNPASS,
                    ]
                );
            }
        } else {
            if ($ss['isFlag'] == 1) {
                Model::factory($risk['provider']."_Short")->create(
                    [
                        'user_id' => $risk['user_id'],
                        'module' => $risk['action'],
                        'result' => Lib_CreditReview_RiskCommon::LIB_RISK_REVIEW_STATUS_PASS,
                    ]
                );
            } else {
                Model::factory($risk['provider']."_Short")->create(
                    [
                        'user_id' => $risk['user_id'],
                        'module' => $risk['action'],
                        'result' => Lib_CreditReview_RiskCommon::LIB_RISK_REVIEW_STATUS_UNPASS,
                    ]
                );
            }
        }
        if ($ss['black'] == 1) {
            $user = Model::factory("User")->get_one($risk['user_id']);
            Model::factory("BlackList")->addWithCheck(
                array(
                    'user_id' => $user['id'],
                    'name' => $user['name'],
                    'identity_code' => $user['identity_code'],
                    'mobile' => $user['mobile'],
                    'source' => Model_BlackList::SOURCE_SUNFOUD_CREDIT,
                    'why' => "风险规则",
                    'status' => Model_BlackList::IS_PENDING_STATUS,
                    'create_time' => time(),
                )
            );
        }
        $res1 = Model::factory("RiskDecision")->setCheck($risk, $risk['check_no'], $ss);
        $res2 = Model::factory("RiskDecision")->recordResponse($risk['decision_id'], $result);
        if ($res1 && $res2) {
            $response_data = [
                'code' => '1000',
                'data' => '',
                'msg' => "回调成功",
            ];
            echo json_encode($response_data);
            exit;
        }
        $response_data = [
            'code' => '1001',
            'data' => '',
            'msg' => "回调失败",
        ];
        echo json_encode($response_data);
    }

    public function action_BlackList()
    {
        $data = file_get_contents("php://input", true);;
        if (empty($data)) {
            $response_data = [
                'code' => '1001',
                'data' => '',
                'msg' => "缺少参数",
            ];
            echo json_encode($response_data);
            exit;
        }
        $list = json_decode($data, true);
        $result = array();
        for ($i = 0; $i < count($list['mobiles']); $i++) {
            $mobile['mobile'] = $list['mobiles'][$i];
            $res = Model::factory("BlackList")->check($result);
            if ($res) {
                $mobile['status'] = '1';
            } else {
                $mobile['status'] = '0';
            }
            $result[$i] = $mobile;
        }
        $response_data = [
            'code' => '1000',
            'data' => $result,
            'msg' => "查询成功",
        ];
        echo json_encode($response_data);
    }
}