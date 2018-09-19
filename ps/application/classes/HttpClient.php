<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/1/28
 * Time: 上午11:27
 * 该类修改自 timecash:admin/application/classes/TimeCashAPI.php
 *
 * 调用例子
 *  res = HttpClient::factory('http://www.baidu.com')->post(array('mobile'=>'13888888888'))->execute()->body();
 *  如果返回是JSON 可以使用 as_array 获得数组
 *  res = HttpClient::factory('http://www.baidu.com')->post(array('mobile'=>'13888888888'))->execute()->as_array();
 *
 */
class HttpClient{

    protected $_curl;
    protected $_res;
    protected $_body;
    protected $_header;
    protected $_query;
    protected $_file;
    protected $_method;
    protected $_error = array('no'=>0,'msg'=>'');

    protected $_url = 'http://127.0.0.1:7444';
    protected $_user_agent = 'timecash client v0.3';
    protected $_referrer;
    protected $_timeout = 120;
    protected $_connect_timeout = 30;


    public function __construct($url=NULL) {
        if($url){
            $this->url($url);
        }
        $this->_curl = curl_init();
        curl_setopt($this->_curl, CURLOPT_HEADER, TRUE);
        curl_setopt($this->_curl, CURLOPT_NOBODY, FALSE);
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, TRUE);
        //curl_setopt($this->_curl, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
        curl_setopt($this->_curl, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($this->_curl, CURLOPT_CONNECTTIMEOUT, $this->_connect_timeout);


    }

    public static function factory($url=NULL){

        return new HttpClient($url);
    }

    public function url($url){
        $this->_url = $url;
        return $this;
    }


    public function execute(){
        if(strtolower(substr($this->_url,0,8))=='https://' ){
            $this->ssl();
        }
        curl_setopt($this->_curl, CURLOPT_URL, $this->_url);
        $this->_res = curl_exec($this->_curl);
        if (curl_errno($this->_curl) > 0) {
            $this->_error['no'] = curl_errno($this->_curl);
            $this->_error['msg'] = curl_error($this->_curl);
        }
        $header_size = curl_getinfo( $this->_curl, CURLINFO_HEADER_SIZE );
        $this->_header = substr( $this->_res, 0, $header_size );
        $this->_body = substr( $this->_res, $header_size );
        curl_close($this->_curl);
        //数据记录
        return $this;
    }


    public function referrer($referrer=NULL){
        if($referrer){
            $this->_referrer=$referrer;
            curl_setopt($this->_curl, CURLOPT_REFERER, $this->_referrer);
        }
        return $this;
    }

    public function user_agent($user_agent) {
        if($user_agent){
            $this->_user_agent=$user_agent;
        }
        return $this;
    }

    public function timeout($timeout){
        if($timeout){
            $this->_timeout=intval($timeout);
            curl_setopt($this->_curl, CURLOPT_TIMEOUT, $this->_timeout);

        }
        return $this;
    }

    public function connect_timeout($timeout){
        if($timeout){
            $this->_connect_timeout=intval($timeout);
            curl_setopt($this->_curl, CURLOPT_CONNECTTIMEOUT, $this->_connect_timeout);

        }
        return $this;
    }

    public function body(){
        return $this->_body;
    }

    public function header(){
        return $this->_header;
    }

    public function as_array(){
        if($this->is_json($this->_body)){
            return json_decode($this->_body,TRUE);
        }
        return FALSE;
    }


    public function ssl(){
        curl_setopt($this->_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        return $this;
    }

    public function file($files = array()){
        if($files){
            foreach ($files as $k => $v){

                if(is_file($v)){
                    $this->_file[$k] = curl_file_create($v);
                }
            }
        }
        return $this;
    }


    public function post($query = array()) {
        $this->_method = 'POST';

        curl_setopt($this->_curl, CURLOPT_POST, TRUE);
        if(is_array($query) && $this->_file) {
            curl_setopt($this->_curl, CURLOPT_SAFE_UPLOAD, FALSE);
            curl_setopt($this->_curl, CURLOPT_BINARYTRANSFER, TRUE);
            foreach ($this->_file as $k => $v){
                $query[$k] = $v;
            }
            curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $query);
        }else{
            curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $query);

        }

        $this->_query = $query;
        return $this;
    }

    public function get($query = array()) {
        $this->_method = 'GET';
        if(!empty($query)) {
            $this->_url .= strpos ( $this->_url, '?' ) === false ? '?' : '&';
            $this->_url .= is_array ( $query ) ? http_build_query ( $query ) : $query;
        }
        return $this;
    }

    public function put($query = array()) {
        $this->_method = 'PUT';
        curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        return $this->post($query);
    }

    public function delete($query = array()) {
        $this->_method = 'DELETE';
        curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this->post($query);
    }

    public function head($query = array()) {
        $this->_method = 'HEAD';
        curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, 'HEAD');

        return $this->post($query);
    }

    public function options($query = array()) {
        $this->_method = 'OPTIONS';
        curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
        return $this->post($query);
    }

    public function trace($query = array()) {
        $this->_method = 'TRACE';
        curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, 'TRACE');
        return $this->post($query);
    }


    public function httpheader($array=NULL) {
        if($array!==NULL) {
            $this->curlopt(CURLOPT_HTTPHEADER, $array);
        }
        return $this;
    }

    /**
     * @param null|array|string $opt 数组方式 array(array(CURLOPT_CUSTOMREQUEST,'OPTIONS'),array('CURLOPT_CUSTOMREQUEST','TRACE'));
     * @param null|string $value 单条方式 $opt为CURL配置名 $value为值
     */
    public function curlopt($opt=NULL,$value=NULL){
        if($opt!==NULL) {
            if(is_array($opt)) {
                foreach($opt as $v){
                    if(isset($v[0]) && isset($v[1]) ){
                        curl_setopt($this->_curl, $v[0], $v[1]);
                    }

                }
            }elseif($value!==NULL){
                curl_setopt($this->_curl, $opt, $value);
            }
        }
        return $this;
    }


    public function error(){
        return $this->_error;
    }


    public function __destruct() {
        // TODO: Implement __destruct() method.
        if(is_object($this->_curl)){
            curl_close($this->_curl);
        }
    }


    public function is_json($json_string){
        $test=json_decode($json_string);
        return (json_last_error() == JSON_ERROR_NONE);
    }



}