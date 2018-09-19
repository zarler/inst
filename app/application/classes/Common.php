<?php defined('SYSPATH') or die('No direct script access.');
	/*
		H5页面测试用
	*/
    class Common extends Controller {

        protected $_controller = NULL;
        protected $_action = NULL;
        //ip
        protected $_ip = null;
        //互动变量
        public static $_VArray = null;

		protected $_site_config= null;
		protected $_session = null;
		protected $_api = null;
		protected $_model = null;


		//view模板版本
		protected $_vv = null;


        public function before(){
			parent::before();
			self::init();
		}
		protected function init(){
			$this->_controller = Request::current()->controller();
			$this->_action = Request::current()->action();
			$this->_site_config = Kohana::$config->load('site');
            //获取ip
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR'])&&$_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:(isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'');
            $this->_ip = explode(",",$ip)[0];

			if(isset($this->_site_config['config']['view_version'])&&!empty($this->_site_config['config']['view_version'])){
				$this->_vv = $this->_site_config['config']['view_version'].'/';
			}else{
				$this->_vv = 'v1/';
			}
		}


		//curl 发送post数据 或者请求地址 用于各个接口调用
		protected function curlopen($url, $data = array(),$head=array()){
			$HTTPAPPID = Kohana::$config->load('wx.HTTPAPPID');
			$HTTPSECRET = Kohana::$config->load('wx.HTTPSECRET');
			if(empty($data)){
				foreach($data as $k=>$v){
					$data[$k]=(string)$v;
				}
			}
			$head[]="APPSIGN:".md5(json_encode($data).$HTTPSECRET);
			$head[]="APPID:".$HTTPAPPID;
			return HttpClient::factory($url)->post($data)->httpheader($head)->execute()->body();
		}

		//公共session检测
		protected function check_session($key){
			$data = $this->_session->sessionGet($key);
			if($data){
				return array('status'=>true,'data'=>$data);
			}else{
				return array('status'=>false);
			}
		}

	//错误处理 发送到错误页面
	protected function error($error,$type=null){
		$view = View::factory($this->_vv.'Error/index');
		$view->error = $error;
		$view->type = $type;
		$view->url = '/User/index?jump=no';
		$out = $view->render();
		exit( $out);
	}
    //错误处理 发送到错误页面
    protected function errorElse($error,$url='#',$type=null){
        $view = View::factory($this->_vv.'Error/index');
        $view->error = $error;
        $view->type = $type;
        $view->url = $url;
        $out = $view->render();
        exit( $out);
    }
	//错误处理 app进入该页面的报错信息
	protected function error_app(){
		$view = View::factory('Error/indexApp');
		$view->error = '快金已简化降担保流程,无需填写本项信息,请搜索关注快金微信公众号,下载新版APP,申请降担保';
		$view->type = null;
		$view->url = '/User/index?jump=no';
		$out = $view->render();
		exit( $out);
	}
	
	//上传图片url专用
	protected function curl($url,$json,$post){
		$ch = curl_init();
		$post['json'] = $json;
		curl_setopt($ch, CURLOPT_NOBODY, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 90);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
		//CURLFile这个类只支持php5.5+版本
		$post['pic1'] = new CURLFile($post['pic1']);
		$post['pic2'] = new CURLFile($post['pic2']);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$res = curl_exec($ch);
		curl_close($ch);
		if(empty($res) ){
			$ListArray=false;
		}else{
			$ListArray = json_decode($res,true);
		}
		return $ListArray;
	}

	//金钱点截取函数
	protected function MoneyStrStr($money=null){
		if(empty($money)){
			return 0;
		}
		if(substr(strstr($money, '.',0),-2)==00){
			return (int)$money;
		}else{
			return $money;
		}
	}
	

	//ip限制
	public function ip_limit(){
		$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
		$ip = explode(",",$ip)[0];
		if(in_array($ip,array('61.51.129.141','61.51.129.138'))){
		}else{
			echo '维护中.....';
			die;
		}
	}
	public function response_json($data){

		header('Content-type: application/json');
		exit(json_encode($data));

	}

	//加载中
    public function Load(){
        echo '<body style="margin: 0;padding: 0"><div class="t-mask-loading" style="display: block;position: fixed;width: 100%;height: 100%;background: white;z-index: 100;"><img style="width:10%;margin: 60% auto;display: -webkit-box;" src="/static/ui_bootstrap/layer/skin/default/loading-2.gif"></div></body>';
    }

    //组装请求接口验证
    public function getVerification(){
        return 'app_id='.App::$id.'&ver='.App::$ver.'&os='.App::$os.'&unique_id='.App::$unique_id.'&ip='.App::$ip.'&token='.App::$token;
    }

}