<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/2/15
 * Time: 下午10:32
 */



class Curl_request {
    public $url         = '';
    public $method      = 'GET';
    public $post_data   = null;
    public $headers     = null;
    public $options     = null;
    /**
     *
     * @param string $url
     * @param string $method
     * @param string $post_data
     * @param string $headers
     * @param array $options
     * @return void
     */
    public function __construct($url, $method = 'GET', $post_data = null, $headers = null, $options = null) {
        $this->url = $url;
        $this->method = strtoupper( $method );
        $this->post_data = $post_data;
        $this->headers = $headers;
        $this->options = $options;
    }
    /**
     * @return void
     */
    public function __destruct() {
        unset ( $this->url, $this->method, $this->post_data, $this->headers, $this->options );
    }
}

/**
 * 包含请求列队处理
 */
class MCurl {

    private $size           = 5;
    private $timeout        = 5;
    private $callback       = null;
    private $options        = array (CURLOPT_SSL_VERIFYPEER => 0,CURLOPT_RETURNTRANSFER => 1,CURLOPT_CONNECTTIMEOUT => 30 );
    private $headers        = array ();
    private $requests       = array ();//请求组
    private $request_map    = array ();//请求所以呢
    private $errors         = array ();

    public function __construct($callback = null) {
        $this->callback = $callback;
    }
    /**
     * 添加一个请求对象到列队
     * @access public
     * @param object $request
     * @return boolean
     */
    public function add($request) {
        $this->requests [] = $request;
        return TRUE;
    }
    /**
     * 创建一个请求对象并添加到列队
     * @access public
     * @param string $url
     * @param string $method
     * @param string $post_data
     * @param string $headers
     * @param array $options
     * @return boolean
     */
    public function request($url, $method = 'GET', $post_data = null, $headers = null, $options = null) {
        $this->requests [] = new Curl_request ( $url, $method, $post_data, $headers, $options );
        return TRUE;
    }
    /**
     * 创建GET请求对象
     * @access public
     * @param string $url
     * @param string $headers
     * @param array $options
     * @return boolean
     */
    public function get($url, $headers = null, $options = null) {
        return $this->request ( $url, "GET", null, $headers, $options );
    }
    /**
     * 创建一个POST请求对象
     * @access public
     * @param string $url
     * @param string $post_data
     * @param string $headers
     * @param array $options
     * @return boolean
     */
    public function post($url, $post_data = null, $headers = null, $options = null) {
        return $this->request ( $url, "POST", $post_data, $headers, $options );
    }
    /**
     * 执行cURL
     * @access public
     * @param int $size 最大连接数
     * @return Ambigous <boolean, mixed>|boolean
     */
    public function execute($size = null) {
        if (sizeof ( $this->requests ) == 1) {
            return $this->single_curl ();
        } else {
            return $this->rolling_curl ( $size );
        }
    }
    /**
     * 单个url请求
     * @access private
     * @return mixed|boolean
     */
    private function single_curl() {
        $ch = curl_init ();
        $request = array_shift ( $this->requests );
        $options = $this->get_options ( $request );
        curl_setopt_array ( $ch, $options );
        $output = curl_exec ( $ch );
        $info = curl_getinfo ( $ch );

        // it's not neccesary to set a callback for one-off requests
        if ($this->callback) {
            $callback = $this->callback;
            if (is_callable ( $this->callback )) {
                call_user_func ( $callback, $output, $info, $request );
            }
        } else
            return $output;
        return true;
    }
    /**
     * 多个url请求
     * @access private
     * @param int $size 最大连接数
     * @return boolean
     */
    private function rolling_curl($size = null) {
        if ($size)
            $this->size = $size;
        else
            $this->size = count($this->requests);
        if (sizeof ( $this->requests ) < $this->size)
            $this->size = sizeof ( $this->requests );
        if ($this->size < 2)
            $this->set_error ( 'size must be greater than 1' );
        $master = curl_multi_init ();
        //添加cURL连接资源句柄到map索引
        for($i = 0; $i < $this->size; $i ++) {
            $ch = curl_init ();
            $options = $this->get_options ( $this->requests [$i] );
            curl_setopt_array ( $ch, $options );
            curl_multi_add_handle ( $master, $ch );

            $key = ( string ) $ch;
            $this->request_map [$key] = $i;
        }

        $active = $done = null;
        do {
            while ( ($execrun = curl_multi_exec ( $master, $active )) == CURLM_CALL_MULTI_PERFORM )
                ;
            if ($execrun != CURLM_OK)
                break;
            //有一个请求完成则回调
            while ( $done = curl_multi_info_read ( $master ) ) {
                //$done 完成的请求句柄
                $info = curl_getinfo ( $done ['handle'] );//
                $output = curl_multi_getcontent ( $done ['handle'] );//
                $error = curl_error ( $done ['handle'] );//

                $this->set_error ( $error );

                //调用回调函数,如果存在的话
                $callback = $this->callback;
                if (is_callable ( $callback )) {
                    $key = ( string ) $done ['handle'];
                    $request = $this->requests [$this->request_map [$key]];
                    unset ( $this->request_map [$key] );
                    call_user_func ( $callback, $output, $info, $error, $request );
                }
                curl_close ( $done ['handle'] );
                //从列队中移除已经完成的request
                curl_multi_remove_handle ( $master, $done ['handle'] );
            }
            //等待所有cURL批处理中的活动连接
            if ($active)
                curl_multi_select ( $master, $this->timeout );
        } while ( $active );
        //完成关闭
        curl_multi_close ( $master );
        return true;
    }
    /**
     * 获取没得请求对象的cURL配置
     * @access private
     * @param object $request
     * @return array
     */
    private function get_options($request) {
        $options = $this->__get ( 'options' );
        if (ini_get ( 'safe_mode' ) == 'Off' || ! ini_get ( 'safe_mode' )) {
            $options [CURLOPT_FOLLOWLOCATION] = 1;
            $options [CURLOPT_MAXREDIRS] = 5;
        }
        $headers = $this->__get ( 'headers' );

        if ($request->options) {
            $options = $request->options + $options;
        }

        $options [CURLOPT_URL] = $request->url;

        if ($request->post_data && strtolower($request->method) == 'post' ) {
            $options [CURLOPT_POST] = 1;
            $options [CURLOPT_POSTFIELDS] = $request->post_data;
        }
        if ($headers) {
            $options [CURLOPT_HEADER] = 0;
            $options [CURLOPT_HTTPHEADER] = $headers;
        }

        return $options;
    }
    /**
     * 设置错误信息
     * @access public
     * @param string $msg
     */
    public function set_error($msg) {
        if (! empty ( $msg ))
            $this->errors [] = $msg;
    }
    /**
     * 获取错误信息
     * @access public
     * @param string $open
     * @param string $close
     * @return string
     */
    public function display_errors($open = '<p>', $close = '</p>') {
        $str = '';
        foreach ( $this->errors as $val ) {
            $str .= $open . $val . $close;
        }
        return $str;
    }
    /**
     * @access public
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public function __set($name, $value) {
        if ($name == 'options' || $name == 'headers') {
            $this->{$name} = $value + $this->{$name};
        } else {
            $this->{$name} = $value;
        }
        return TRUE;
    }
    /**
     *
     * @param string $name
     * @return mixed
     * @access public
     */
    public function __get($name) {
        return (isset ( $this->{$name} )) ? $this->{$name} : null;
    }
    /**
     * @return void
     * @access public
     */
    public function __destruct() {
        unset ( $this->size, $this->timeout, $this->callback, $this->options, $this->headers, $this->requests, $this->request_map, $this->errors );
    }
}
