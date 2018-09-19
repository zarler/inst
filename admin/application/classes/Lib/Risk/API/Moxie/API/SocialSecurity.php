<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2017/5/26
 * Time: 上午9:50
 *
 * 魔蝎社保 LIB
 */
class Lib_Risk_API_Moxie_API_SocialSecurity
{
    protected $_ApiKey = null;

    private $_request;
    private $_request_url;

    protected $_userId = null;
    protected $_backUrl = null;
    protected $_themeColor = 'FF0000';
    protected $_url = null;
    protected $_SocialSecurityPath = null;
    protected $_token = null;

    protected $_config = null;

    public function __construct()
    {
        $this->_init();
    }

    private function _init()
    {
        //加载配置
        $this->_config = Kohana::$config->load('api')->get('Moxie');

        $this->_ApiKey = $this->_config['ApiKey'];
        $this->_url = $this->_config['url'];
        $this->_SocialSecurityPath = $this->_config['SocialSecurityPath'];
        $this->_backUrl = $this->_config['backUrl'];
        $this->_token = $this->_config['Token'];

    }

    public function get_request()
    {
        return $this->_request;
    }

    //获取报告
    public function GetReport($url, $task_id)
    {
        $header = array(
            'Content-Type:application/json',
            'Authorization: token '.$this->_token,
        );
        //初始化
        $ch = curl_init();
        // https://api.51datakey.com/report/api/v1/security/
        //设置选项，包括URL/
        curl_setopt($ch, CURLOPT_URL, $url.$task_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        //打印获得的数据
        return $output;
    }


    public function curlGet($Data)
    {
        return $this->_url.$this->_SocialSecurityPath.'?apiKey='.$this->_ApiKey.'&userId='.$Data['userId'].'&backUrl='.$this->_backUrl.'&themeColor='.$this->_themeColor;
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL,
            $this->_url.$this->_SocialSecurityPath.'?apiKey='.$this->_ApiKey.'&userId='.$Data['userId'].'&backUrl='.$this->_backUrl.'&themeColor='.$this->_themeColor);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        //打印获得的数据
        return $output;
    }


    public function crulPost($postData)
    {


        if (empty($this->_request_url)) {
            return false;
        }

        try {
            $this->_postData = http_build_query($postData);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->_request_url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_postData);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSLVERSION, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            //文件日志
            $log = new MyLog(DOCROOT.'protected/logs');
            //随机ID
            $id = uniqid(Text::random('alnum', 8));
            //IP地址
            $ip = Request::$client_ip;
            //记录发送数据
            $log->write(array(array('body' => json_encode($postData), 'time' => time())),
                "time [{$id}] - IP:{$ip} --- TianJi REQUEST: \r\nbody \r\n");

            $res = curl_exec($curl);

            //记录返回信息
            $log->write(array(array('body' => $res, 'time' => time())),
                "time [{$id}] --- TianJi RESPONSE: \r\nbody\r\n");

            $errno = curl_errno($curl);
            $curlInfo = curl_getinfo($curl);
            $errInfo = 'curl_err_detail: '.curl_error($curl);
            $errInfo .= ' curlInfo:'.json_encode($curlInfo);


            curl_close($curl);
        } catch (Exception $e) {
            return array('status' => true, 'msg' => '请求异常 : '.$e->getMessage(), 'error' => '9999');
        }

        return $res;
    }
}