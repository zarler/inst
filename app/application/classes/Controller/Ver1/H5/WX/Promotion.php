
<?php defined('SYSPATH') or die('No direct script access.');
/*
 * app现在页面
 *  * Tool::factory('Debug')->D($this->controller);
 * Tool::factory('Dir')->dir_path(self::$config['security_path']).$result[0]['uri']);
 * Tool::factory('Debug')->array2file(array(1,2,3,4,5), APPPATH.'../static/liu_test.php');
 *
 * */
    class Controller_Ver1_H5_Wx_Promotion extends Common
    {
        //分期下载
        public function action_InstandroidUpload(){
            $view = View::factory($this->_vv.'Promotion/InstandroidUpload');
            //判断是否是微信浏览器
            if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
                $view->is_weixin =  true;
            }else{
                $view->is_weixin =  false;
            }
            $view->url = 'http://download-cdn.timecash.cn/android/release/inst/inst_1.0.2.apk';
            $view->title=Kohana::$config->load('url.title.quota');
            $this->response->body($view);
        }
        //分期下载
        public function action_InstIosUpload(){
            $this->errorElse("iOS暂不支持下载！");
        }
}