<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 2017/11/24
 * Time: 上午11:56
 */
class Lib_SMS_API_NewMengXin{

    private static $user;
    private static $password;
    //发送短信地址
    private static $send_url;

    ////查询短信剩余条数地址
    private static $status_url;

    function __construct () {
        $config = Kohana::$config->load('api')->get('newmengxin');
        self::$password = $config['password'];
        self::$send_url = $config['send_url'];
        self::$status_url = $config['status_url'];
        $this->http = new HttpClient(self::$send_url);
    }

    public function chose($type){
        $config = Kohana::$config->load('api')->get('newmengxin');
        $info = $config[$type];
        self::$user = $info['user'];
        return $this;
    }

    public function sendSMS($data)
    {
        $log = new MyLog(DOCROOT . 'protected/logs');
        $request = array(
            'account' => self::$user,
            'pswd' => self::$password,
            'mobile' => $data['mobile'],
            'msg' => $data['content'],
            'needstatus' => 'true',
            'extno' => '666'
        );
        //随机ID
        $id = uniqid(Text::random('alnum', 8));
        //IP地址
        $ip = Request::$client_ip;
        $log->write(array(array('body' => json_encode($data), 'time' => time())), "time [{$id}] - IP:{$ip} --- SMS REQUEST: \r\nbody \r\n");
        $response = $this->http->get($request)->execute()->body();
        $log->write(array(array('body' => $response, 'time' => time())), "time [{$id}] --- SMS RESPONSE: \r\nbody\r\n");
        $array = explode(',',$response);
        if (isset($array[1]) && $array[1] == 0){
            $msg = explode("\n",$array[1]);
            $result = ['time'=>$array[0],'status'=>$msg[0],'msgId'=>$msg[1]];
        } else {
            $result = ['time'=>$array[0],'status'=>$array[1]];
        }
        return json_encode($result);
    }

}