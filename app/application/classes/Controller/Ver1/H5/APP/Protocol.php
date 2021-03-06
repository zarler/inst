<?php defined('SYSPATH') or die('No direct script access.');

    class Controller_Ver1_H5_APP_Protocol extends Controller {
        protected $_vv = null;
        protected $_site_config= null;
        protected static $_VArray = null;

        public function before()
        {
            parent::before(); // TODO: Change the autogenerated stub
            $this->_site_config = Kohana::$config->load('site');
            if(isset($this->_site_config['wx']['view_version'])&&!empty($this->_site_config['wx']['view_version'])){
                $this->_vv = $this->_site_config['wx']['view_version'].'/';
            }else{
                $this->_vv = '';
            }
        }

        public function action_content(){
            $this->Load();
			$protocolid = $this->request->query('num');
            $type = (isset($_GET['type'])&&!empty($_GET['type']))?$_GET['type']:null;
			//$type = isset($this->request->query('type')?$this->request->query('type'):null;
			//$oid = $this->request->query('oid');
			if(empty($protocolid) || $protocolid>5){
				$this->error('非法请求');
			}
            $typeNum = intval($type);
            if($typeNum<0 || $typeNum>3){
                $this->error('非法请求');
            }
            if($typeNum == 0){
                $type = '';
            }
            //判断是Android还是ios
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
                    self::$_VArray['client'] = "else";
                }else{
                    self::$_VArray['client'] = "ios";
                }
                //$view->url = 'http://mp.weixin.qq.com/mp/redirect?url='.urlencode('https://itunes.apple.com/cn/app/kuai-jin/id1130326523?mt=8');
            }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
                    self::$_VArray['client'] = "else";
                }else{
                    self::$_VArray['client'] = "android";
                }
            }else{
                self::$_VArray['client'] = "else";
            }

            if(isset($_GET['goback'])){
                self::$_VArray['goback'] = 'javascript:history.go(-1);';
            }else{
                self::$_VArray['goback'] = '?#jump=no';
            }

            self::$_VArray['title'] = '协议';
            $view = View::factory($this->_vv.'Protocol/protocol'.$protocolid.$type);
            $view->_VArray = self::$_VArray;
            $this->response->body($view);

        }
        //常遇问题
        public function action_Problem(){
            $view = View::factory($this->_vv.'Protocol/problem');
            $view->title = '快金常见问题与帮助';
            $this->response->body($view);
        }
        //智齿聊天系统
        public function action_Sobot(){
            $view = View::factory($this->_vv.'Protocol/sobot');
            $this->response->body($view);
        }
        //错误处理 发送到错误页面
        protected function error($error, $url = null, $type = null){
            $view = View::factory($this->_vv . 'Error/index');
            $view->error = $error;
            $view->type = $type;
            if ($url) {
                $view->url = $url;
            } else {
                $view->url = '/?#jump=no';
            }
            $out = $view->render();
            exit($out);
        }

        //借款攻略
        public function action_Raiders(){
            $view = View::factory($this->_vv.'Protocol/raiders');
            $this->response->body($view);
        }
        //加载中
        public function Load(){
            echo '<body style="margin: 0;padding: 0"><div class="t-mask-loading" style="display: block;position: fixed;width: 100%;height: 100%;background: white;z-index: 100;"><img style="width:10%;margin: 60% auto;display: -webkit-box;" src="/static/ui_bootstrap/layer/skin/default/loading-2.gif"></div></body>';
        }

}