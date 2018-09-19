<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2017/5/26
 * Time: 上午9:50
 *
 *  魔蝎 公共部分LIB
 */
class Lib_Risk_API_Moxie_Common
{
    protected $_ApiKey = null;

    private $_request;

    protected $_userId = null;
    protected $_backUrl = null;
    protected $_themeColor = 'FF0000';
    protected $_url = null;
    protected $_FundPath = null;
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

        $this->_token = $this->_config['Token'];

    }

    public function get_request()
    {
        return $this->_request;
    }

    //获取报告
    public function GetReport($url, $task_id)
    {
        return $this->curl($url.$task_id);
    }

    //获取报告Task_id = {Task_id}
    public function GetReportTask_id($url, $task_id)
    {
        return $this->curl($url.'?task_id='.$task_id);
    }

    public function curl($url)
    {
        $header = array(
            'Content-Type:application/json',
            'Authorization: token '.$this->_token,
        );
        //初始化
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');

        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        //打印获得的数据
        return $output;
    }

    //获取运营商原始数据Task_id = {Task_id}
    public function GetOriginalMno($url, $mobile, $task_id)
    {
        return $this->curl($url.$mobile.'/mxdata-ex?task_id='.$task_id);
    }

    //获取运营商原始数据Task_id = {Task_id}
    public function GetReportMno($url, $mobile)
    {
        return $this->curl($url.$mobile.'/mxreport');
    }

    //获取邮箱信用卡报告
    public function GetReportEmail($url, $task_id, $email_id)
    {
        return $this->curl($url.$email_id.'/'.$task_id);
    }

    //获取邮箱信用卡报告
    public function GetReportShixin($url, $name, $cardNum)
    {
        return $this->curl($url.'?name='.$name.'&cardNum='.$cardNum);
    }


}