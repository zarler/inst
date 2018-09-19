<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/11
 * Time: 下午9:15
 *
 * 魔蝎-回调
 */
class Controller_Notify_Moxie extends Controller
{

    public function _init()
    {
        header("HTTP/1.1 201 Created");

    }

    public function action_Index()
    {
        echo 'index';
    }

    //任务创建通知
    public function action_TaskSubmit()
    {

        $action = $_SERVER;

        $this->_init();
        $post = file_get_contents("php://input", true);
        $data = json_decode($post, true);
        //文件日志
        $log = new MyLog(DOCROOT.'protected/logs');
        //随机ID
        $id = uniqid(Text::random('alnum', 8));
        //IP地址
        $ip = Request::$client_ip;
        //记录发送数据
        $log->write(array(array('body' => json_encode($data), 'time' => time())),
            "time [{$id}] - IP:{$ip} --- MoxieCallback_".$action['HTTP_ACTION']."_TaskSubmit REQUEST: \r\nbody \r\n");

        Model::factory('Moxie_Data')->add_resp_data([
            'resp_data' => json_encode($data), 'provider' => 'Moxie', 'type' => 'TaskSubmit', 'state' => 2,
            'userid' => $data['user_id'], 'tc_no' => '', 'action' => $action['HTTP_ACTION'],
        ]);

        $res = Model::factory('Moxie_DataItem')->get_data_item($data['user_id']);

//        if ($res) {
//            Model::factory('CreditInfo_Step')->change($res['extends'], Model_CreditInfo_Step::TAOBAO,
//                Model_CreditInfo_Step::COMPLETE);
//        }
        exit;

    }

    //任务授权登录状态通知
    public function action_Task()
    {

        $action = $_SERVER;
        $this->_init();
        $post = file_get_contents("php://input", true);
        $data = json_decode($post, true);
        //文件日志
        $log = new MyLog(DOCROOT.'protected/logs');
        //随机ID
        $id = uniqid(Text::random('alnum', 8));
        //IP地址
        $ip = Request::$client_ip;
        //记录发送数据
        $log->write(array(array('body' => json_encode($data), 'time' => time())),
            "time [{$id}] - IP:{$ip} --- MoxieCallback_".$action['HTTP_ACTION']."_Task REQUEST: \r\nbody \r\n");

        Model::factory('Moxie_Data')->add_resp_data([
            'resp_data' => json_encode($data), 'provider' => 'Moxie', 'type' => 'Task', 'state' => 3,
            'userid' => $data['user_id'], 'tc_no' => '', 'action' => $action['HTTP_ACTION'],
        ]);
        exit;
    }

    //任务采集失败通知
    public function action_TaskFail()
    {
        $action = $_SERVER;
        $this->_init();
        $post = file_get_contents("php://input", true);

        $data = json_decode($post, true);
        //文件日志
        $log = new MyLog(DOCROOT.'protected/logs');
        //随机ID
        $id = uniqid(Text::random('alnum', 8));
        //IP地址
        $ip = Request::$client_ip;
        //记录发送数据
        $log->write(array(array('body' => json_encode($data), 'time' => time())),
            "time [{$id}] - IP:{$ip} --- MoxieCallback_".$action['HTTP_ACTION']."_TaskFail REQUEST: \r\nbody \r\n");

        Model::factory('Moxie_Data')->add_resp_data([
            'resp_data' => json_encode($data), 'provider' => 'Moxie', 'type' => 'TaskFail', 'state' => 32,
            'userid' => $data['user_id'], 'tc_no' => '', 'action' => $action['HTTP_ACTION'],
        ]);
        exit;
    }

    //账单通知
    public function action_Bill()
    {
        $action = $_SERVER;
        $this->_init();
        $post = file_get_contents("php://input", true);
        $data = json_decode($post, true);
        //文件日志
        $log = new MyLog(DOCROOT.'protected/logs');
        //随机ID
        $id = uniqid(Text::random('alnum', 8));
        //IP地址
        $ip = Request::$client_ip;
        //记录发送数据
        $log->write(array(array('body' => json_encode($data), 'time' => time())),
            "time [{$id}] - IP:{$ip} --- MoxieCallback_".$action['HTTP_ACTION']."_TaskBill REQUEST: \r\nbody \r\n");

        $data_item = Model::factory('Moxie_DataItem')->get_data_item(['userid' => $data['user_id']]);
        if ($data_item) {
            Model::factory('CreditInfo_Step')->update($data_item['extends'],
                ['status' => 2, 'pass_time' => time()], $action['HTTP_ACTION']);   //更新ci_step 状态为2
        }

        if ($action['HTTP_ACTION'] == 'Zhixing') {
            if ($data['count'] > 0) {
                $data_item = Model::factory('Moxie_DataItem')->get_data_item(['userid' => $data['user_id']]);
                if ($data_item) {
                    //写入队列
                    Model::factory('Moxie_Queue')->add_data_queue([
                        'task_id' => $data['task_id'], 'user_id' => $data['user_id'], 'state' => 5,
                        'action' => 'Zhixing', 'mobile' => '',
                        'extends' => $data_item['extends'],
                    ]);
                }
            }
        } elseif ($action['HTTP_ACTION'] == 'Chsi') {
            $data_item = Model::factory('Moxie_DataItem')->get_data_item(['userid' => $data['user_id']]);
            Model::factory('Moxie_Queue')->add_data_queue([
                'task_id' => $data['task_id'], 'user_id' => $data['user_id'], 'state' => 5, 'action' => 'Chsi',
                'mobile' => '', 'extends' => $data_item['extends'],
            ]);
        }

        Model::factory('Moxie_Data')->add_resp_data([
            'resp_data' => json_encode($data), 'provider' => 'Moxie', 'type' => 'Bill', 'state' => 4,
            'userid' => $data['user_id'], 'tc_no' => '', 'action' => $action['HTTP_ACTION'],
        ]);
        exit;
    }

    //用户报告通知
    public function action_Report()
    {

        $action = $_SERVER;
//        $action['HTTP_ACTION'] = 'JD';
        $this->_init();
        $post = file_get_contents("php://input", true);
//        $post = '{"timestamp":1516787473322,"result":true,"message":"0l5c1mDOaur7Ylo%2BFWrWGeyXrxABmu4%2FmArVb6DLv4ob96LGEHEJIFv%2BxVyxCF1l4b6w35lX9mJHbR7cjPurAY30aU763b0MSKYv797ACvyIs6JqxGSOeQ%3D%3D","task_id":"143d3e8a-00ec-11e8-a4d7-00163e0c310d","user_id":"27c7e4bc518c48d095d9caf544771876"}';
        $data = json_decode($post, true);
        //文件日志
        $log = new MyLog(DOCROOT.'protected/logs');
        //随机ID
        $id = uniqid(Text::random('alnum', 8));
        //IP地址
        $ip = Request::$client_ip;
        //记录发送数据
        $log->write(array(array('body' => json_encode($data), 'time' => time())),
            "time [{$id}] - IP:{$ip} --- MoxieCallback_".$action['HTTP_ACTION']."_Report REQUEST: \r\nbody \r\n");


        if ($action['HTTP_ACTION'] == 'Taobao') {
            $module = Model_CreditInfo_Step::TAOBAO;
        } elseif ($action['HTTP_ACTION'] == 'JD') {
            $module = Model_CreditInfo_Step::JINGDONG;
        } elseif ($action['HTTP_ACTION'] == 'Bank') {
            $module = Model_CreditInfo_Step::BANK;
        } elseif ($action['HTTP_ACTION'] == 'Chsi') {
            $module = Model_CreditInfo_Step::CHSI;
        } elseif ($action['HTTP_ACTION'] == 'Fund') {
            $module = Model_CreditInfo_Step::FUND;
        } elseif ($action['HTTP_ACTION'] == 'SocialSecurity') {
            $module = Model_CreditInfo_Step::SOCIAL_SECURITY;
        } elseif ($action['HTTP_ACTION'] == 'Zhixing') {
            $module = Model_CreditInfo_Step::ZHIXING;
        } elseif ($action['HTTP_ACTION'] == 'Email') {
            $module = Model_CreditInfo_Step::EMAIL;
        } elseif ($action['HTTP_ACTION'] == 'Mno') {
            $module = Model_CreditInfo_Step::MNO;
        }

        Model::factory('Moxie_Data')->add_resp_data([
            'resp_data' => json_encode($data), 'provider' => 'Moxie', 'type' => 'Report', 'state' => 5,
            'userid' => $data['user_id'], 'action' => $module,
        ]);
        $data_item = Model::factory('Moxie_DataItem')->get_data_item(['userid' => $data['user_id']]);
//        var_dump($action['HTTP_ACTION']);die;
        if ($data_item) {


            Model::factory('Moxie_Message')->add_message([
                'user_id' => $data_item['extends'], 'message' => $data['message'], 'action' => $module,
            ]);
            //写入队列
            if ($action['HTTP_ACTION'] == 'Email') {
                $a = Model::factory('Moxie_Queue')->add_email_data_queue([
                    'task_id' => $data['task_id'], 'email_id' => $data['email_id'], 'user_id' => $data['user_id'],
                    'state' => 5, 'action' => 'Email', 'mobile' => '',
                    'extends' => $data_item['extends'],
                ]);
//                var_dump($a);die;
            } elseif ($action['HTTP_ACTION'] == 'Mno') {
                Model::factory('Moxie_Queue')->add_data_queue([
                    'task_id' => $data['task_id'], 'mobile' => $data['mobile'], 'user_id' => $data['user_id'],
                    'state' => 5, 'action' => 'Mno', 'extends' => $data_item['extends'],
                ]);
            } else {
                Model::factory('Moxie_Queue')->add_data_queue([
                    'task_id' => $data['task_id'], 'user_id' => $data['user_id'], 'state' => 5,
                    'action' => $module, 'mobile' => '',
                    'extends' => $data_item['extends'],
                ]);
            }

        }
        exit;
    }

    //h5端返回需要检测用户
    public function action_H5Query()
    {

        //$response_array['query_result'] = 'unknown';
        //$this->response_json($response_array,"1000","查询成功");

        $response_array = [];
        if (!empty($_REQUEST)) {
            $res = Model::factory('Moxie_DataItem')->get_data_item($_REQUEST['userId']);

            if ($res) {
                $rs = Model::factory('Risk_QueryRecord')->get_one_by_array(['user_id' => $res['extends']]);
                if ($rs) {
                    if ($_REQUEST['taskType'] == 'taobao') {
                        $module = Model_CreditInfo_Step::TAOBAO;
                    } elseif ($_REQUEST['taskType'] == 'jingdong') {
                        $module = Model_CreditInfo_Step::JINGDONG;
                    } elseif ($_REQUEST['taskType'] == 'bank') {
                        $module = Model_CreditInfo_Step::BANK;
                    } elseif ($_REQUEST['taskType'] == 'chsi') {
                        $module = Model_CreditInfo_Step::CHSI;
                    } elseif ($_REQUEST['taskType'] == 'fund') {
                        $module = Model_CreditInfo_Step::FUND;
                    } elseif ($_REQUEST['taskType'] == 'security') {
                        $module = Model_CreditInfo_Step::SOCIAL_SECURITY;
                    } elseif ($_REQUEST['taskType'] == 'zhixing') {
                        $module = Model_CreditInfo_Step::ZHIXING;
                    } elseif ($_REQUEST['taskType'] == 'email') {
                        $module = Model_CreditInfo_Step::EMAIL;
                    } elseif ($_REQUEST['taskType'] == 'carrier') {
                        $module = Model_CreditInfo_Step::MNO;
                    } else {
                        $this->response_json([], "4006", "参数错误");
                    }

                    Model::factory('CreditInfo_Step')->update($res['extends'],
                        ['status' => 2, 'pass_time' => time()], $module);   //更新ci_step 状态为2
                    $ci_step = Model::factory('User')->get_ci_step($rs['user_id']);

                    if ($_REQUEST['taskType'] == 'taobao') {
                        if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::TAOBAO] == Model_CreditInfo_Step::COMPLETE) {
                            $response_array['query_result'] = 'pass';
                            $this->response_json($response_array, "1000", "查询成功");
                        } else {
                            $response_array['query_result'] = 'unknown';
                            $this->response_json($response_array, "1000", "查询成功");
                        }
                    } elseif ($_REQUEST['taskType'] == 'jingdong') {
                        if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::JINGDONG] == Model_CreditInfo_Step::COMPLETE) {
                            $response_array['query_result'] = 'pass';
                            $this->response_json($response_array, "1000", "查询成功");
                        } else {
                            $response_array['query_result'] = 'unknown';
                            $this->response_json($response_array, "1000", "查询成功");
                        }
                    } elseif ($_REQUEST['taskType'] == 'bank') {
                        if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::BANK] == Model_CreditInfo_Step::COMPLETE) {
                            $response_array['query_result'] = 'pass';
                            $this->response_json($response_array, "1000", "查询成功");
                        } else {
                            $response_array['query_result'] = 'unknown';
                            $this->response_json($response_array, "1000", "查询成功");
                        }
                    } elseif ($_REQUEST['taskType'] == 'chsi') {
                        if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::CHSI] == Model_CreditInfo_Step::COMPLETE) {
                            $response_array['query_result'] = 'pass';
                            $this->response_json($response_array, "1000", "查询成功");
                        } else {
                            $response_array['query_result'] = 'unknown';
                            $this->response_json($response_array, "1000", "查询成功");
                        }
                    } elseif ($_REQUEST['taskType'] == 'fund') {
                        if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::FUND] == Model_CreditInfo_Step::COMPLETE) {
                            $response_array['query_result'] = 'pass';
                            $this->response_json($response_array, "1000", "查询成功");
                        } else {
                            $response_array['query_result'] = 'unknown';
                            $this->response_json($response_array, "1000", "查询成功");
                        }
                    } elseif ($_REQUEST['taskType'] == 'security') {
                        if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::SOCIAL_SECURITY] == Model_CreditInfo_Step::COMPLETE) {
                            $response_array['query_result'] = 'pass';
                            $this->response_json($response_array, "1000", "查询成功");
                        } else {
                            $response_array['query_result'] = 'unknown';
                            $this->response_json($response_array, "1000", "查询成功");
                        }
                    } elseif ($_REQUEST['taskType'] == 'zhixing') {
                        if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::ZHIXING] == Model_CreditInfo_Step::COMPLETE) {
                            $response_array['query_result'] = 'pass';
                            $this->response_json($response_array, "1000", "查询成功");
                        } else {
                            $response_array['query_result'] = 'unknown';
                            $this->response_json($response_array, "1000", "查询成功");
                        }
                    } elseif ($_REQUEST['taskType'] == 'email') {
                        if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::EMAIL] == Model_CreditInfo_Step::COMPLETE) {
                            $response_array['query_result'] = 'pass';
                            $this->response_json($response_array, "1000", "查询成功");
                        } else {
                            $response_array['query_result'] = 'unknown';
                            $this->response_json($response_array, "1000", "查询成功");
                        }
                    } elseif ($_REQUEST['taskType'] == 'carrier') {
                        if (isset($ci_step) && $ci_step[Model_CreditInfo_Step::MNO] == Model_CreditInfo_Step::COMPLETE) {
                            $response_array['query_result'] = 'pass';
                            $this->response_json($response_array, "1000", "查询成功");
                        } else {
                            $response_array['query_result'] = 'unknown';
                            $this->response_json($response_array, "1000", "查询成功");
                        }
                    }
                } else {
                    $this->response_json([], "4000", "流水号查询失败");
                }

            } else {
                $this->response_json([], "4000", "接口异常");

            }
        } else {
            $this->response_json([], "4006", "参数错误");
        }
    }


    //格式化JSON返回
    public function response_json($result, $code = true, $message = null)
    {
        $time_string = date('Y-m-d H:i:s');
        $data = array(
            'code' => $code,
            'message' => $message ? $message : '',

            'result' => $result,
            'service' => array(
                'time' => $time_string,
            ),
        );

        $json = json_encode($data);

        header('Content-type: application/json');
        echo $json;
        ob_flush();
        exit;

    }


}