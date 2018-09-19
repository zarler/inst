<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: guorui
 * Date: 2016/2/14
 * Time: 15:48
 */
class Controller_Ver2_AntiFraud_ZhiChengCredit extends Controller{
    public function action_EchoRequest(){
        $this->model['APIOutLog'] = Model::factory('APIOutLog');
        $this->lib['ZhiChengCredit'] = Lib::factory('AntiFraud_ZhiChengCredit');//宜信请求快金查询用户借款和风险信息
        if(empty($this->request->post('params'))){
            $result=array('errorCode'=>'4009','message'=>$this->lib['ZhiChengCredit']->result_code_array['4009'],'params'=>'');
            $this->_render($result);
            exit;
        }else{
            $params=$this->request->post('params');
            if(!is_array($params)){
                $params=json_decode($params,true);
            }
        }
        $rc4_str=base64_decode(urldecode($params['params']));
        $rc4_str=$this->rc4($this->lib['ZhiChengCredit']->get_rc4key(),$rc4_str);
        $res_arr=json_decode($rc4_str,true);
        if(empty($res_arr['tx'])||empty($res_arr['data'])||empty($res_arr['data']['name'])||empty($res_arr['data']['idNo'])){
            $result=array('errorCode'=>'4009','message'=>$this->lib['ZhiChengCredit']->result_code_array['4009'],'params'=>'');
            $this->_render($result);
            exit;
        }else{
            if($res_arr['tx']!='201'){
                $result=array('errorCode'=>'4009','message'=>$this->lib['ZhiChengCredit']->result_code_array['4009'],'params'=>'');
                $this->_render($result);
                exit;
            }
        }
        //数据库日志记录
        $log_id = $this->model['APIOutLog']->create(array(
            'provider' => 'ZhiCheng',
            'action' => 'EchoRequest',
            'type' => 'ZhiCheng',
            'req_data' => json_encode($this->request->post()),
            'reference_id' => 0,
        ));
        $response = $this->lib['ZhiChengCredit']->postData($this->request->post());
        $result = json_decode($response, true);
        if(isset($result['errorCode'])){
            if($result['errorCode']==='0000'){
                $msg = '查询成功';
            }else{
                $msg = $result['message'];
            }
        }else{
            $msg = '返回异常';
        }
        //更新日志表写入接口返回
        $this->model['APIOutLog']->update_by_id($log_id, array(
            'msg' => $msg,
            'resp_data' => $response,
        ));
        $this->_render($result);
    }

    public function _render($data) {
        $this->response->body(json_encode($data))->headers('content-type', 'application/json');
    }

    /*
    * rc4加密算法
    * $pwd 密钥
    * $data 需加密字符串
    */
    function rc4 ($pwd, $data){
        $key[] ="";
        $box[] ="";
        $cipher="";
        $pwd_length = strlen($pwd);
        $data_length = strlen($data);
        for ($i = 0; $i < 256; $i++)
        {
            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++)
        {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $data_length; $i++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }
        return $cipher;
    }
}