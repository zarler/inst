<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 2017/1/15
 * Time: 0:18
 */

class Controller_Query_Balance_Payment_UCFPay extends AdminController  {

    public function action_Index() {
        $message = NULL;
        $result = NULL;
        $result_array = NULL;
        $data = NULL;

        if(isset($this->post['submit']) ){

            $result=TCAPI::factory()->url('http://api-pay.timecash.cn/UCFPay/Balance')->post()->execute()->body();
            if(Tool::factory('Array')->is_json($result)){
                    $result_array = json_decode($result,TRUE);
                    if(isset($result_array['status']) && $result_array['status'] === TRUE && isset($result_array['api_result']['result']) && (string)$result_array['api_result']['result']==(string)Model_Finance_Payment_Ucfpay::API_RESULT_SUCCESS  && isset($result_array['api_result']['info']) ){
                        $data = $result_array['api_result']['info'];
                    }else{
                        $message = array('type' => 'error', 'message' => '查询失败:'.$result_array['msg']);
                    }
            }else{
                $message = array('type' => 'error', 'message' => '查询失败.');
            }
        }

        Template::factory('Query/Balance/Payment/UCFPay',array('data'=>$data,'result'=>$result,'result_array'=>$result_array,'message'=>$message))->response();
    }
}