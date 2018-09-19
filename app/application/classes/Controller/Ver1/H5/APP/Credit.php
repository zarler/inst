<?php defined('SYSPATH') or die('No direct script access.');

/*
 * 授信流程
 *  * Lib::factory('Debug')->D($this->controller);
 * Lib::factory('Dir')->dir_path(self::$config['security_path']).$result[0]['uri']);
 * Lib::factory('Debug')->array2file(array(1,2,3,4,5), APPPATH.'../static/liu_test.php');
 *
 *
 * */
class Controller_Ver1_H5_APP_Credit extends AppCore {
//class Controller_Ver1_H5_APP_Chedit extends Common {


    public function before(){
        parent::before();
    }

	public function action_Index()
	{
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
        $this->Load();

        if(!isset(App::$_token['user_id'])||!Valid::not_empty(App::$_token['user_id'])){
            $this->error("用户未登录！");
        }

        parent::$_VArray['title'] = '工作信息';
        parent::$_VArray['reqUrl'] = '/v1/CreditInfo/WorkInfo';
        parent::$_VArray['jumpUrl'] = '?#jump=yes';
//        parent::$_VArray['seajsVer'] = 'app_id=android&ver=1.0.0&os=5.1&unique_id=869609022752180&ip=61.51.129.138&token=TA20180115214638PVEyiIFphAhsTtmR';
        parent::$_VArray['seajsVer'] = $this->getVerification();;
        $view = View::factory($this->_vv.'Credit/WorkInfo');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }
    /************************************************
     * 家庭信息
     ************************************************/
    public function action_HomeInfo()
    {


//        Lib::factory('Debug')->D(array($this->getVerification(),App::$_token));


        $this->Load();

        if(!isset(App::$_token['user_id'])||!Valid::not_empty(App::$_token['user_id'])){
            $this->error("用户未登录！");
        }

        parent::$_VArray['title'] = '居住信息';
        parent::$_VArray['reqUrl'] = '/v1/CreditInfo/HomeInfo';
        parent::$_VArray['jumpUrl'] = '?#jump=yes';
        parent::$_VArray['seajsVer'] = $this->getVerification();
//        parent::$_VArray['seajsVer'] = 'app_id=android&ver=1.0.0&os=5.1&unique_id=869609022752180&ip=61.51.129.138&token=TA20180115214638PVEyiIFphAhsTtmR';
        $view = View::factory($this->_vv.'Credit/HomeInfo');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }
    /************************************************
     * 紧急联系人
     ************************************************/
    public function action_EmergencyContact()
    {
        $this->Load();

        if(!isset(App::$_token['user_id'])||!Valid::not_empty(App::$_token['user_id'])){
            $this->error("用户未登录！");
        }

        parent::$_VArray['title'] = '紧急联系人';
        parent::$_VArray['reqUrl'] = '/v1/CreditInfo/Contact';
        parent::$_VArray['jumpUrl'] = '?#jump=yes';
        parent::$_VArray['seajsVer'] = $this->getVerification();
//        parent::$_VArray['seajsVer'] = 'app_id=android&ver=1.0.0&os=5.1&unique_id=869609022752180&ip=61.51.129.138&token=TA20180115214638PVEyiIFphAhsTtmR';
        $view = View::factory($this->_vv.'Credit/EmergencyContact');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }

}
