<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/12/7
 * Time: 下午9:14
 *
 * 本类将HttpClient当作容器封装CURL请求,并进行批量并发处理,原有HttpClient的使用习惯可不变.
 *
 */
class HttpClientQueue{

    protected $_count = 0;
    protected $_timeout = 90;
    protected $_connect_timeout = 20;
    protected $_requests = array();
    protected $_map = array();
    protected $callback = NULL;
    protected $_error = array();


    public function __construct($callback = NULL)
    {
        $this->callback = $callback;
    }


    public static function factory($callback = NULL)
    {
        return new HttpClientQueue($callback);
    }


    public function add( HttpClient $request)
    {
        $this->_requests [] = $request;
        return TRUE;
    }


    public function execute($callback = NULL)
    {
        if($callback!==NULL){
            $this->callback = $callback;
        }
        $this->rolling_curl();
    }


    //高效多线CURL
    protected function rolling_curl()
    {
        $this->_count = count($this->_requests);
        if($this->_count<1){
            return FALSE;
        }
        $master = curl_multi_init();
        //添加cURL连接资源句柄到map索引
        for($i = 0; $i < $this->_count; $i ++) {
            $ch = $this->_requests[$i]->curl();
            curl_multi_add_handle ( $master, $ch );
            $key = ( string ) $ch;
            $this->_map[$key] = $i;
        }

        $active = $done = null;
        do {
            while (($execrun = curl_multi_exec($master, $active)) == CURLM_CALL_MULTI_PERFORM)
                ;
            if ($execrun != CURLM_OK)
                break;
            //有一个请求完成则回调
            while ($done = curl_multi_info_read($master)) {
                //$done 完成的请求句柄
                $info = curl_getinfo($done ['handle']);
                $output = curl_multi_getcontent($done ['handle']);
                $error = curl_error($done ['handle']);

                $this->set_error($error);

                $key = ( string )$done ['handle'];
                if (isset($this->_requests[$this->_map[$key]])) {
                    $request = $this->_requests[$this->_map[$key]];
                    $request->import($done ['handle'])->analyze();
                    unset ($this->_map[$key]);
                }
                //调用回调函数,如果存在的话
                $callback = $this->callback;
                if (is_callable($callback)) {
                    call_user_func($callback, $output, $info, $error, $request);
                }else{
                    echo $callback.' is not called!';
                };
                curl_close($done ['handle']);
                //从列队中移除已经完成的request
                curl_multi_remove_handle($master, $done ['handle']);
            }
            //等待所有cURL批处理中的活动连接,或者5秒后超时再次循环等待
            if ($active) {
                curl_multi_select($master, 5);
            }
        } while ( $active );
        //完成关闭
        curl_multi_close ( $master );
        return true;

    }


    public function setError($msg)
    {
        return $this->set_error($msg);
    }
    public function set_error($msg)
    {
        if (! empty ( $msg ))
            $this->_error [] = $msg;
    }


    public function getError()
    {
        return $this->get_error();
    }
    public function get_error()
    {
        return $this->_error;
    }


}