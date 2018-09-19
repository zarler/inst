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

class Controller_Ver1_H5_APP_Index1 extends Common {

    protected $_header_data = NULL;
    public function before(){
        parent::before();
    }

	public function action_Index()
	{
//        Lib::factory('Debug')->D($_SERVER);
//        $this->Load();
//        parent::$_VArray['title'] = '测试';
//        $view = View::factory($this->_vv.'Index/Index');
//        $view->_VArray = parent::$_VArray;
//        $this->response->body($view);



//	    echo 'Controller_Ver1_View_APP_Index1';
//        //用cookie保存token（基类）
//        if(isset($_SERVER['HTTP_APP_INFO'])){
//            parse_str($_SERVER['HTTP_APP_INFO'], $this->_header_data);
//        }



        //setcookie('token', $this->_header_data['token']);
        Lib::factory('Debug')->D(array($_SERVER));



//        $this->Load();
//        parent::$_VArray['title'] = '测试';
//        $view = View::factory($this->_vv.'Index/Index');
//        $view->_VArray = parent::$_VArray;
//        $this->response->body($view);

//		$view = View::factory('User/index');
//		$view->signPackage = '22222';
//		$this->response->body($view);
	}

}
