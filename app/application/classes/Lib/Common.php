<?php

/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 2018/1/5
 * Time: 下午3:54
 */
class Lib_Common
{
    const LIB_COMMON_API_RESULT_SUCCESS = '0000';                       //接口请求成功
    const LIB_COMMON_API_RESULT_EXCEPTION = 'R0001';                    //接口请求异常
    const LIB_COMMON_API_RESULT_PROCESSING = 'R0002';                   //接口请求处理中
    const LIB_COMMON_API_RESULT_FAIL = 'R0005';                         //接口请求失败
    const LIB_COMMON_API_MISS_PARAM = 'L0005';                          //缺少参数
    const LIB_COMMON_API_DATA_EXCEPTION = 'L0004';                      //本地数据异常

    public $response;
    public $request;
    public $method;


    //格式化返回
    public function response($status,$result,$code='0000',$message=NULL){
        if (!$this->response) {
            $this->response = [
                'status' => $status,
                'error' => $code,
            ];

            if($result){
                $this->response['api_result'] = $result;
            }

            if($message !== NULL){
                $this->response['msg'] = $message;
            }
        }
    }

    public function asJson()
    {
        return json_encode($this->response);
    }

    public function body()
    {
        return $this->response;
    }
}