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

class Controller_Ver1_H5_APP_Test extends Common {

    protected $_header_data = NULL;
    public function before(){
        parent::before();
    }

	public function action_Index()
	{
        parent::$_VArray['title'] = '手机互交测试';
        $view = View::factory($this->_vv.'Index/Test');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);

	}

    //魔蝎
    public function action_Moxie()
    {

        $arr = [
            'taskType' => 'carrier',
            'userId' => '20180123185829Zs84',
            'taskId' => '5cfa51f0-002c-11e8-b0c4-00163e10becc',
        ];
        $result = Lib::factory('Moxie_Notify')->H5Query($arr);
        if(Valid::not_empty($result)){
            $result = json_decode($result,true);
            if(is_array($result)){
                if(isset($result['code'])&&$result['code']=1000){
                    $this->redirect('/?#jump=yes');
                }else{
                    $this->error("获取失败！");
                }
            }else{
                $this->error("获取失败！");
            }
        }else{
            $this->error("获取失败！");
        }

//        Lib::factory('Debug')->array2file(array($_REQUEST,$arr,$result), APPPATH.'../static/liu_test.php');
//        parent::$_VArray['title'] = '手机互交测试';

    }

}
