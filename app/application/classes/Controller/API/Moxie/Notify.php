<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/22
 * Time: 下午8:41
 * 魔蝎-APP 通知
 */
class Controller_API_Moxie_Notify extends AppCore
{
    //SDK方式 返回需要检测用户
    public function action_AppQuery()
    {
        if (!empty($this->_data)) {
            if (!isset($this->_data['provider']) || !isset($this->_data['action']) || !isset($this->_data['task_id']) || !isset(App::$_user['id'])) {
                $this->response_json(array(), "4005", "缺少参数");
            }
            $data['provider'] = isset($this->_data['provider']) ? $this->_data['provider'] : '';
            $data['action'] = isset($this->_data['action']) ? $this->_data['action'] : '';
            $data[''] = isset($this->_data['task_id']) ? $this->_data['task_id'] : '';
            $data['extends'] = isset(App::$_user['id']) ? App::$_user['id'] : '';
            $data['userid'] = isset($this->_data['userid']) ? $this->_data['userid'] : '';
            $data['state'] = 1;

//            $item = Model::factory('Moxie_DataItem')->add_data_item($data);
//            $queue = Model::factory('Moxie_Queue')->add_data_queue([
//                'task_id' => $data['task_id'], 'user_id' => $data['userid'], 'state' => 5, 'action' => $data['action'],
//                'mobile' => '', 'extends' => $data['extends'],
//            ]);

            $ci = Model::factory('CreditInfo_Step')->update($data['extends'],
                ['status' => 2, 'pass_time' => time()], $data['action']);   //更新ci_step 状态为2
            if ($ci) {
                $this->response_json(array(), '1000', '数据保存成功');
            } else {
                $this->response_json(array(), "4000", "数据库异常");
            }
        }
    }
    /**
     * @$action
     */
    //SDK方式 APP通知后台user_id
    public function action_UserID()
    {

        if (!isset($this->_data['userId']) || !isset($this->_data['action']) || !isset($this->_data['provider'])) {
            $this->response_json(array(), "4005", "缺少参数");
        }

        $id = Model::factory('Moxie_Data')->add_data([
            'req_data' => json_encode(array('userId' => $this->_data['userId'])), 'provider' => 'Moxie',
            'type' => 'html',
            'state' => 1, 'userid' => $this->_data['userId'], 'action' => $this->_data['action'],
        ]);

        Model::factory('Moxie_DataItem')->add_data_item([
            'extends' => App::$_user['id'], 'userid' => $this->_data['userId'],
            'state' => 1,
        ]);

        $rs = Model::factory('Risk_QueryRecord')->get_by_array([
            'user_id' => App::$_user['id'], 'provider' => 'Moxie', 'action' => $this->_data['action'],
        ], 11);

        if (count($rs) >= 10) {
            $this->response_json([], "4006", "重试次数已超上限");
        }
        //记录重试次数
        Model::factory('Risk_QueryRecord')->create([
            'user_id' => App::$_user['id'], 'provider' => $this->_data['provider'], 'action' => $this->_data['action'],
        ]);

        $this->response_json([], "1000", "保存成功");

    }
}