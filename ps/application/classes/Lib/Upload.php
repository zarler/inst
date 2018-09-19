<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 2017/8/18
 * Time: 下午6:33
 */
require_once DOCROOT.'application/classes/HttpClient.php';

class Lib_Upload{
    protected $http = null;
    protected $api_root = 'http://ps.wl.timecash.cn/API/Upload/Upload';
    protected $data = [];
    protected $res = null;
    protected $file = [];
    protected $clientId = 'wuliu';
    protected $clientKey = 'xqP8MuCMZEhJuOaSbL';

    public function __construct() {
        $this->http = new HttpClient($this->api_root);

    }

    public function file(array $array){
        $this->file = $array;
        if(empty($this->data)){
            $this->data = json_encode(array('list'=>'wuliu'));
        }
        return $this;
    }

    public function query(){
            if($this->file){
                $this->http->file($this->file);
            }
            $header = $this->setsign();
            $body = $this->http->httpheader($header)->post(['json'=>$this->data])->execute()->body();
            $this->res['body'] = $this->http->body();
            $this->res['header'] = $this->http->header();
            if($this->http->is_json($body)){
                return json_decode($body,true);
            }else{
                return $body;
            }
        return FALSE;
    }

    public function get_error(){
        return $this->http->error();
    }

    public function setsign(){
        $file = file_get_contents($this->file['file']);
        $sign = md5(md5($file).$this->clientKey);
        $array = array("CLIENTID:".$this->clientId, "CLIENTSIGN:". $sign);
        return $array;
    }

}