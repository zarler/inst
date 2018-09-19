<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/23
 * Time: 上午11:13
 */
class Lib_Logic_API
{

    private $user_id;
    private $param;
    private $action;
    private $provider;
    private $response;
    private $method;

    const MOXIE_MNO = 'MoxieMno';      //魔蝎运营商

    public function user_id($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * 发送请求
     * @return $this
     */
    public function execute()
    {

        if (!$this->response) {
            call_user_func_array('self::'.$this->method, ['data' => []]);
        }

        return $this;
    }


    /**
     * @param $action
     * @return $this
     *
     */
    public function query($action)
    {
        $this->method = $action;

        return $this;
    }

    /**
     * 魔蝎运营商
     */
    public function MoxieMno(){
        $res = Model::factory('Moxie_Queue')->get_one($this->user_id);
        if($res){
            $data = Lib::factory('Moxie_GetReport')->query(
                [
                    'task_id' => $res['task_id'],
                    'email_id' => $res['email_id'],
                    'user_id' => $res['user_id'],
                    'action' => $res['action'],
                ]
            );
            var_dump($data);

        }

    }


}