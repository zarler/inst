<?php defined('SYSPATH') or die('No direct script access.');
/*
 * 活动页面
 *  * Lib::factory('Debug')->D($this->controller);
 * Lib::factory('Dir')->dir_path(self::$config['security_path']).$result[0]['uri']);
 * Lib::factory('Debug')->array2file(array(1,2,3,4,5), APPPATH.'../static/liu_test.php');
 *
 *
 * */

class Controller_Ver1_H5_WX_Activity extends Common {
    protected $model = null;
    public function before(){
        parent::before();
    }

    /*****************************************************************
     *H5页面注册（注册渠道）
     * *************************************************************/
	public function action_YDOA_001()
	{

	    //获取渠道识别
        parent::$_VArray['channelCode'] = isset($_GET['channelcode'])?$_GET['channelcode']:null;
        parent::$_VArray['channelType'] = isset($_GET['channeltype'])?$_GET['channeltype']:null;

	    //创建token
        $this->model['token'] = Model::factory('Token');
        $token = $this->model['token']->make_token();
        $c = 0;
        while ($this->model['token']->get_one($token)) {
            $c++;
            $token = $this->model['token']->make_token();
            if ($c > 3) {//碰撞3次失败
                $this->error("异常错误！");
            }
        }

        $array = [
            'token' => $token,
            'app_id' => 'h5',
            'app_ver' => '',
            'app_os' => 'h5',
            'unique_id' => '',
            'ip' => $this->_ip,
        ];

        if ($this->model['token']->create($array)) {
            parent::$_VArray['token'] = $token;
//            $this->response_json(['token' => $token, 'expire_in' => Model_Token::NOLOGIN_PERIOD], "1000", "token创建成功");
        } else {
            $this->error("异常错误！");
        }
        //组装token信息
        parent::$_VArray['seajsVer'] = "app_id=h5&ver=''&os=''&unique_id=''&ip={$this->_ip}&token={$token}";
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
            parent::$_VArray['jumpUrl'] = "/v1/w/Promotion/InstIosUpload";
            parent::$_VArray['system'] = "ios";
        }elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
            parent::$_VArray['jumpUrl'] = "/v1/w/Promotion/InstandroidUpload";
            parent::$_VArray['system'] = "android";
        }else{
            parent::$_VArray['system'] = "else";
            parent::$_VArray['jumpUrl'] = "/v1/w/Promotion/InstandroidUpload";
        }
        parent::$_VArray['title'] = '现金分期产品强势来袭-循环额度，闪电放款';
        parent::$_VArray['reqSMS'] = '/v1/User/RegisterVerifySMS';
        parent::$_VArray['reqUrl'] = '/v1/User/WebRegister';
	    //加载
//        $this->Load();
        $view = View::factory($this->_vv.'Activity/YDOA_001');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
	}

}
