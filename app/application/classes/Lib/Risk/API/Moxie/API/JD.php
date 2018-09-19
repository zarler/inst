<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2017/12/20
 * Time: 下午6:38
 *
 * 魔蝎 -- 京东LIB
 */
class Lib_Risk_API_Moxie_API_JD
{
    protected $_ApiKey = null;

    private $_request;

    protected $_userId = null;
    protected $_backUrl = null;
    protected $_themeColor = 'FF0000';
    protected $_url = null;
    protected $_JDPath = null;

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
        $this->_JDPath = $this->_config['JDPath'];
        $this->_backUrl = $this->_config['backUrl'];

    }

    public function get_request()
    {
        return $this->_request;
    }

    public function curlGet($Data)
    {
        return $this->_url.$this->_JDPath.'?apiKey='.$this->_ApiKey.'&userId='.$Data['userId'].'&backUrl='.$this->_backUrl.'&themeColor='.$this->_themeColor;
    }

}