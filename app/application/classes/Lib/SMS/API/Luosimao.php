<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 15/12/31
 * Time: 上午9:47
 */

class Lib_SMS_API_Luosimao {

    //短信通道密文
    private static $key;

    //发送短信地址
    private static $send_url;

    ////查询短信剩余条数地址
    private static $status_url;

    function __construct () {
        $config = Kohana::$config->load('api')->get('SMS');
        self::$key = $config['key'];
        self::$send_url = $config['send_url'];
        self::$status_url = $config['status_url'];
    }

    /**
     * 发送短信
     * @param array $data = array(mobile,message)
     * @return mixed
     */
    public static function sendSMS($data){
        //文件日志
        $log = new MyLog(DOCROOT.'protected/logs');
        //随机ID
        $id = uniqid(Text::random('alnum',8));
        //IP地址
        $ip = Request::$client_ip;

        $log->write(array(array('body'=>json_encode($data),'time'=>time())),"time [{$id}] - IP:{$ip} --- SMS REQUEST: \r\nbody \r\n");

        $result = self::postData(self::$send_url,$data);

        //记录返回信息
        $log->write(array(array('body'=>json_encode($result),'time'=>time())),"time [{$id}] --- SMS RESPONSE: \r\nbody\r\n");

        return $result;
    }

    /**
     * 查询短信剩余条数
     * @param
     * @return mixed
     */
    public static function getSMSStatus(){
        //文件日志
        $log = new MyLog(DOCROOT.'protected/logs');
        //随机ID
        $id = uniqid(Text::random('alnum',8));
        //IP地址
        $ip = Request::$client_ip;

        $log->write(array(array('body'=>json_encode(self::$status_url),'time'=>time())),"time [{$id}] - IP:{$ip} --- SMS REQUEST: \r\nbody \r\n");
        $result = self::postData(self::$status_url);
        //记录返回信息
        $log->write(array(array('body'=>json_encode($result),'time'=>time())),"time [{$id}] --- SMS REQUEST: \r\nbody\r\n");
        return $result;
    }

    /**
     * 发送请求到接口
     * @param string $url
     * @param array $data = array(mobile,message)
     * @return mixed
     */
    private static function postData($url,$data = null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-'.self::$key);


        if(isset($data)){
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $data['mobile'],'message' => $data['message']));
        }

        $res = curl_exec( $ch );

        if (curl_errno($ch)) {
            $data['error'] = "-999";
            $data['msg'] = curl_error($ch);
            $res = json_encode($data);
        }
        curl_close( $ch );
        return $res;
    }

}