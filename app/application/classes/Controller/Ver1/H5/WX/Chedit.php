<?php defined('SYSPATH') or die('No direct script access.');

/*
 * 授信流程
 *  * Lib::factory('Debug')->D($this->controller);
 * Lib::factory('Dir')->dir_path(self::$config['security_path']).$result[0]['uri']);
 * Lib::factory('Debug')->array2file(array(1,2,3,4,5), APPPATH.'../static/liu_test.php');
 *
 *
 * */

class Controller_Ver1_H5_WX_Chedit extends Common {

    protected $_header_data = NULL;
    public function before(){
        parent::before();
    }

	public function action_Index()
	{

//        parse_str($_SERVER['HTTP_APP_INFO'], $this->_header_data);
//        Lib::factory('Debug')->D(array($this->_header_data['token'],$this->_header_data,$_SERVER));

        $this->Load();
        parent::$_VArray['title'] = '确认借款';
        $view = View::factory($this->_vv.'LoanProcess/Check');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);

	}
    /************************************************
     * 工作信息
     ************************************************/
    public function action_WorkInfo()
    {
//        $this->Load();
        parent::$_VArray['title'] = '工作信息';
        parent::$_VArray['reqUrl'] = '/v1/CreditInfo/WorkInfo';
        parent::$_VArray['jumpUrl'] = '?#jump=yes';
        parent::$_VArray['seajsVer'] = 'app_id=android&ver=1.0.0&os=5.1&unique_id=869609022752180&ip=61.51.129.138&token=TA20180115214638PVEyiIFphAhsTtmR';
        $view = View::factory($this->_vv.'Test/WorkInfo');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }
    /************************************************
     * 家庭信息
     ************************************************/
    public function action_HomeInfo()
    {
//        $this->Load();
        parent::$_VArray['title'] = '居住信息';
        parent::$_VArray['reqUrl'] = '/v1/CreditInfo/WorkInfo';
        parent::$_VArray['jumpUrl'] = '?#jump=yes';
        $view = View::factory($this->_vv.'Test/HomeInfo');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }
    /************************************************
     * 紧急联系人
     ************************************************/
    public function action_EmergencyContact()
    {

        $this->Load();
        parent::$_VArray['reqUrl'] = '/v1/CreditInfo/Contact';
        parent::$_VArray['title'] = '紧急联系人';
        parent::$_VArray['seajsVer'] = 'app_id=android&ver=1.0.0&os=5.1&unique_id=869609022752180&ip=61.51.129.138&token=TA20180115214638PVEyiIFphAhsTtmR';
        $view = View::factory($this->_vv.'Test/EmergencyContact');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }


}
