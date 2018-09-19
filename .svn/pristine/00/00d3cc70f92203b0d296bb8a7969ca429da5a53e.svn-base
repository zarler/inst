<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 2017/1/15
 * Time: 0:18
 */

class Controller_Query_Balance_SMS_Luosimao extends AdminController  {

    public function action_Index() {
        $message = NULL;
        $result = NULL;
        $result_array = NULL;
        $data = NULL;

        if(isset($this->post['submit']) ){

            $result = Lib::factory('SMS_API')->balance(['provider' => 'Luosimao',])->execute()->asJson();

            if(Lib::factory('Array')->isJson($result)){
                    $result_array = json_decode($result,TRUE);
                    if(isset($result_array['status']) && $result_array['status'] === TRUE && isset($result_array['api_result']['result']) && $result_array['api_result']['result'] === '0000'){
                        $data = $result_array['api_result']['info'];
                        print_r($data);
                    }else{
                        $message = array('type' => 'error', 'message' => '查询失败:');
                    }
            }else{
                $message = array('type' => 'error', 'message' => '查询失败.');
            }
        }

        Template::factory('Query/Balance/SMS/Luosimao',array('data'=>$data,'result'=>$result,'result_array'=>$result_array,'message'=>$message))->response();
    }
}