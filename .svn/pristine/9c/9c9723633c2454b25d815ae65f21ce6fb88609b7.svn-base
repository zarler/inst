<?php
/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2017/12/21
 * Time: 下午1:39
 *
 * 魔蝎 -- 网银LIB
 */

class Lib_Risk_API_Moxie_API_Bank
{
    protected $_ApiKey = null;

    private $_request;

    protected $_userId = null;
    protected $_backUrl = null;
    protected $_themeColor = 'FF0000';
    protected $_url = null;
    protected $_BankPath = null;

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
        $this->_BankPath = $this->_config['BankPath'];
        $this->_backUrl = $this->_config['backUrl'];

    }

    public function get_request()
    {
        return $this->_request;
    }

    public function curlGet($Data)
    {
        return $this->_url . $this->_BankPath . '?apiKey=' . $this->_ApiKey . '&userId=' . $Data['userId'] . '&backUrl=' . $this->_backUrl . '&themeColor=' . $this->_themeColor;
    }

}