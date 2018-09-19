<?php defined('SYSPATH') or die('No direct script access.');
//入口 触发主体
class Controller_Ver1_H5_WX_Index extends Common {

    public function before(){
        parent::before();
    }


	public function action_Index()
	{

	    $b = 0;
	    $c = 1+(++$b);
	    $d = 1+$b++;
	    echo $c;
	    echo $d;
	    echo $b;
	    die;


	    //加载
        $this->Load();
        parent::$_VArray['title'] = '测试';
        $view = View::factory($this->_vv.'Index/Index');
        $view->_VArray = parent::$_VArray;
		$this->response->body($view);
	}

}
