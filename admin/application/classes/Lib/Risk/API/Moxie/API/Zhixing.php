<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2017/12/22
 * Time: 上午11:21
 *
 * 魔蝎 -- 法院被执行人LIB
 */
class Lib_Risk_API_Moxie_API_Zhixing
{
    protected $_ApiKey = null;

    private $_request;

    protected $_userId = null;
    protected $_backUrl = null;
    protected $_themeColor = 'FF0000';
    protected $_url = null;
    protected $_ZhixingPath = null;

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
        $this->_ZhixingPath = $this->_config['ZhixingPath'];
        $this->_backUrl = $this->_config['backUrl'];

    }

    public function get_request()
    {
        return $this->_request;
    }

    public function curlGet($Data)
    {
        return $this->_url.$this->_ZhixingPath.'?apiKey='.$this->_ApiKey.'&userId='.$Data['userId'].'&backUrl='.$this->_backUrl.'&themeColor='.$this->_themeColor;
    }

}