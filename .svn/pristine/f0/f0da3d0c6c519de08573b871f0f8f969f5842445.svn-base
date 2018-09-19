<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/22
 * Time: 下午8:41
 * 魔蝎-H5 通知
 */
class Lib_Moxie_Notify
{

    public function H5Query($data)
    {
//        var_dump($data);die;
        $response_array = [];
        if (!empty($data)) {
            $res = Model::factory('Moxie_DataItem')->get_data_item($data['userId']);

            if ($res) {
                if ($data['taskType'] == 'taobao') {
                    $module = Model_CreditInfo_Step::TAOBAO;
                } elseif ($data['taskType'] == 'jingdong') {
                    $module = Model_CreditInfo_Step::JINGDONG;
                } elseif ($data['taskType'] == 'bank') {
                    $module = Model_CreditInfo_Step::BANK;
                } elseif ($data['taskType'] == 'chsi') {
                    $module = Model_CreditInfo_Step::CHSI;
                } elseif ($data['taskType'] == 'fund') {
                    $module = Model_CreditInfo_Step::FUND;
                } elseif ($data['taskType'] == 'security') {
                    $module = Model_CreditInfo_Step::SOCIAL_SECURITY;
                } elseif ($data['taskType'] == 'zhixing') {
                    $module = Model_CreditInfo_Step::ZHIXING;
                } elseif ($data['taskType'] == 'email') {
                    $module = Model_CreditInfo_Step::EMAIL;
                } elseif ($data['taskType'] == 'carrier') {
                    $module = Model_CreditInfo_Step::MNO;
                } else {
                    return json_encode(['code'=>'4006','message'=>'参数错误']);
                }

//                var_dump($rs);die;
                $a = Model::factory('CreditInfo_Step')->update($res['extends'],
                    ['status' => 2, 'pass_time' => time()], $module);   //更新ci_step 状态为2
//                var_dump($a);die;
                $ci_step = Model::factory('User')->get_ci_step($res['extends']);

                if ($data['taskType'] == 'taobao') {
                    if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::TAOBAO] == Model_CreditInfo_Step::COMPLETE) {
                        $response_array['query_result'] = 'pass';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    } else {
                        $response_array['query_result'] = 'unknown';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    }
                } elseif ($data['taskType'] == 'jingdong') {
                    if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::JINGDONG] == Model_CreditInfo_Step::COMPLETE) {
                        $response_array['query_result'] = 'pass';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    } else {
                        $response_array['query_result'] = 'unknown';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    }
                } elseif ($data['taskType'] == 'bank') {
                    if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::BANK] == Model_CreditInfo_Step::COMPLETE) {
                        $response_array['query_result'] = 'pass';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    } else {
                        $response_array['query_result'] = 'unknown';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    }
                } elseif ($data['taskType'] == 'chsi') {
                    if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::CHSI] == Model_CreditInfo_Step::COMPLETE) {
                        $response_array['query_result'] = 'pass';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    } else {
                        $response_array['query_result'] = 'unknown';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    }
                } elseif ($data['taskType'] == 'fund') {
                    if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::FUND] == Model_CreditInfo_Step::COMPLETE) {
                        $response_array['query_result'] = 'pass';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    } else {
                        $response_array['query_result'] = 'unknown';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    }
                } elseif ($data['taskType'] == 'security') {
                    if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::SOCIAL_SECURITY] == Model_CreditInfo_Step::COMPLETE) {
                        $response_array['query_result'] = 'pass';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    } else {
                        $response_array['query_result'] = 'unknown';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    }
                } elseif ($data['taskType'] == 'zhixing') {
                    if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::ZHIXING] == Model_CreditInfo_Step::COMPLETE) {
                        $response_array['query_result'] = 'pass';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    } else {
                        $response_array['query_result'] = 'unknown';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    }
                } elseif ($data['taskType'] == 'email') {
                    if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::EMAIL] == Model_CreditInfo_Step::COMPLETE) {
                        $response_array['query_result'] = 'pass';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    } else {
                        $response_array['query_result'] = 'unknown';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    }
                } elseif ($data['taskType'] == 'carrier') {
                    if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::MNO] == Model_CreditInfo_Step::COMPLETE) {
                        $response_array['query_result'] = 'pass';
//                        var_dump($response_array);die;
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    } else {
                        $response_array['query_result'] = 'unknown';
                        return json_encode(['code'=>'1000','message'=>'查询成功']);
                    }
                }
            } else {
                return json_encode(['code'=>'4000','message'=>'流水号查询失败']);
            }
        } else {
            return json_encode(['code'=>'4006','message'=>'参数错误']);
//            $this->response_json([], "4006", "参数错误");
        }
    }

}