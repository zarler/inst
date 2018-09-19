<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/1/21
 * Time: 下午3:05
 */
class Lib_AuthCard_API_9douyu {

    private $auth_key;

    private $auth_url;

    function __construct () {
        $config = Kohana::$config->load('api')->get('9douyu');
        $this->auth_url = $config['auth_url'];
        $this->auth_key = $config['auth_key'];
    }

    /**
     * 获取远程验证安全码
     * @param $data
     * @return string
     */
    function getSign($data) {

        ksort($data);   //排序关联数组
        $str        = '';
        foreach($data as $key=>$val){
            if($val){
                $str   .= $key.'='.$val.'&';
            }
        }
        $str        = substr($str,0,-1).$this->auth_key;

        $genSign    = md5($str);
        return $genSign;

//        $list = array();
//        foreach($data as $key=>$value){
//            $list[$key] = $value;
//        }
//        ksort($list);   //排序关联数组
//        $list = array_values($list);    //只要值
//        $setSign = md5(implode("",$list));
//        return $setSign;
    }

    //模拟发送post请求
    /**
     * @param $data
     * @return mixed
     */
    public function postData($data)
    {

        print_r($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->auth_url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        //文件日志
        $log = new MyLog(DOCROOT.'protected/logs');
        //随机ID
        $id = uniqid(Text::random('alnum',8));
        //IP地址
        $ip = Request::$client_ip;
        //记录发送数据
        $log->write(array(array('body'=>json_encode($data),'time'=>time())),"time [{$id}] - IP:{$ip} --- REQUEST: \r\nbody \r\n");

        $res = curl_exec( $ch );

        //记录返回信息
        $log->write(array(array('body'=>json_encode($res),'time'=>time())),"time [{$id}] --- RESPONSE: \r\nbody\r\n");

        if (curl_errno($ch)) {
            $data['error'] = "-999";
            $data['msg'] = curl_error($ch);
            $res = json_encode($data);
        }
        curl_close( $ch );
        return $res;
    }
}