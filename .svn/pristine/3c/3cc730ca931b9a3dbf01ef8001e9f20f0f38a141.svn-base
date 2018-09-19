<?php defined('SYSPATH') or die('No direct script access.');
//入口 触发主体

/*
 * 活动页面
 *  * Lib::factory('Debug')->D($this->controller);
 * Lib::factory('Dir')->dir_path(self::$config['security_path']).$result[0]['uri']);
 * Lib::factory('Debug')->array2file(array(1,2,3,4,5), APPPATH.'../static/liu_test.php');
 *
 *
 * */

class Controller_Ver1_H5_APP_Index extends Common {

    protected $_header_data = NULL;
    public function before(){
        parent::before();
    }

	public function action_Index()
	{


//        Lib::factory('Debug')->D($_SERVER);

        //用cookie保存token（基类）
//        parse_str($_SERVER['HTTP_APP_INFO'], $this->_header_data);
//        setcookie("token", "", time() - 3600);
//        setcookie('token', $this->_header_data['token']);
//        Lib::factory('Debug')->D(array($this->_header_data['token'],$_COOKIE['token'],$this->_header_data,$_SERVER));

//        Lib::factory('Debug')->array2file(array($_SERVER['HTTP_APP_INFO']), APPPATH.'../static/liu_test.php');
//
//        echo $_SERVER['HTTP_APP_INFO'];

        parent::$_VArray['seajsVer']= 'app_id=android&os=5.1&ver=1.0.0&develop_ver=1.0.0&unique_id=869609022752180&time=2018-01-10 17:36:44&ip=172.16.3.25&token=TA20180110024325cFulhvlVyKXnAyfS';
//
//        parent::$_VArray['seajsVer'] = $_SERVER['HTTP_APP_INFO'];
            $this->Load();
        parent::$_VArray['title'] = '测试';
        $view = View::factory($this->_vv.'Index/Index');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);

//		$view = View::factory('User/index');
//		$view->signPackage = '22222';
//		$this->response->body($view);
	}

}
