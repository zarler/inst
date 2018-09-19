<?php defined('SYSPATH') or die('No direct script access.');

/*
 * 订单核对页面
 *  * Lib::factory('Debug')->D($this->controller);
 * Tool::factory('Dir')->dir_path(self::$config['security_path']).$result[0]['uri']);
 * Tool::factory('Debug')->array2file(array(1,2,3,4,5), APPPATH.'../static/liu_test.php');
 *
 *
 * */

//class Controller_Ver1_H5_APP_Loan extends AppCore {
class Controller_Ver1_H5_Wx_Loan extends Common {
    //互动变量
    public static $_VArray = null;
    public  $_vv = null;
    protected $_data = null;


    protected $_header_data = NULL;
    public function before(){
        parent::before();
        $this->_vv = 'v1/';
    }

    /*****************************************************************
     *订单确定页面
     * *************************************************************/
	public function action_Check()
	{
        $this->Load();
        parent::$_VArray['title'] = '确认借款';
        $view = View::factory($this->_vv.'Text/Check');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
	}

    /*****************************************************************
     *订单提交后页面
     * *************************************************************/
    public function action_CheckStatic(){
        $this->Load();
        parent::$_VArray['title'] = '提交成功';
        $view = View::factory($this->_vv.'Test/CheckStatic');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }
    /*****************************************************************
     *借款详情
     * *************************************************************/
    public function action_Detail(){
//        $this->Load();
        parent::$_VArray['downUrl'] = 'javascript:$AppInst.WebJump({\'type\':\'web_abroad\',\'par\':\'http://www.baidu.com\'});';
        parent::$_VArray['title'] = '借款详情';
        $view = View::factory($this->_vv.'Test/Detail');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }
    /*****************************************************************
     *还款页面
     * *************************************************************/
    public function action_Repayment(){
//        $this->Load();
        parent::$_VArray['title'] = "还款";
        parent::$_VArray['orderid'] = $_GET['orderId'];
        parent::$_VArray['billId'] = $_GET['billId'];
        parent::$_VArray['reqSMS'] = '/v1/Order/RepayVerifySMS';
        parent::$_VArray['reqUrl'] = '/v1/Order/SubmitRepay';
        parent::$_VArray['seajsVer'] = 'app_id=android&ver=1.0.0&os=5.1&unique_id=869609022752180&ip=61.51.129.138&token=TA20180115214638PVEyiIFphAhsTtmR';
        $view = View::factory($this->_vv.'Test/Repayment');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }
    // 报错页面
    public function action_Error(){
//        $this->Load();
        parent::$_VArray['title'] = "出错啦";
        $view = View::factory($this->_vv.'Error/index');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }
    //组装请求接口验证
    public function getVerification(){
        return 'app_id='.App::$id.'&ver='.App::$ver.'&os='.App::$os.'&unique_id='.App::$unique_id.'&ip='.App::$ip.'&token='.App::$token;
    }


    /*****************************************************************
     *还款成功页面
     * *************************************************************/
    public function action_RepaymentStatic(){
        parent::$_VArray['title'] = '提交成功';
        parent::$_VArray['jumpUrl'] = '?#jump=yes';
        $view = View::factory($this->_vv.'Loan/RepaymentStatic');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }
}
