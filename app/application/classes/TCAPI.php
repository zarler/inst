<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/1/28
 * Time: 上午11:27
 *
 * 快金API 入口类 ver0.7
 *
 *
 * 调用例子
 *  res = TCAPI::factory('SMS/Register')->post(array('mobile'=>'13888888888'))->execute()->body();
 *  如果返回是JSON 可以使用 as_array 获得数组
 *  res = TCAPI::factory('SMS/Register','api')->post(array('mobile'=>'13888888888'))->execute()->as_array();
 *  res = TCAPI::factory()->config('api-pay')->url('...')->post(array('mobile'=>'13888888888'))->execute()->as_array();
 *
 * 2016-2-16 21:36 by majin
 *   https://网址自动调用ssl()
 *   增加 compile()方法,将配置CURL的操作从execute()方法中切出去
 *
 * 2017-4-28 10:00 by majin
 *  mysql数据表增加url字段,逐渐放弃service_name使用
 *
 * 2017-7-4 10:00 by majin
 *  底层更新,支持file文件流传输
 *
 * 2017-11-23 10:00 by majin
 *  更换配置文件tcapi.php
 *
 * 2017-12-13 23:40 by majin
 *  增加TCAPIDBLog 同时支持mysql mongodb
 *
 * 2018-1-8 20:33 by majin
 *  增加LogServer方式存储,底层封装MONGODB对外提供HTTP接口,减少对PHP对MONGODB的并发压力
 *
 * 2018-1-18 22:24 by majin
 *   增加兼容驼峰写法的方法名
 *
 */
class TCAPI{

    protected $_curl;
    protected $_res;
    protected $_body;
    protected $_header;
    protected $_url;
    protected $_service;
    protected $_query;
    protected $_file;
    protected $_postdata;
    protected $_method;
    protected $_error=array('no'=>0,'msg'=>'');
    //protected $_log_enable = FALSE;
    //protected $_log=NULL;


    protected $_api_root = 'http://api.timecash.cn/';
    protected $_user_agent = 'timecash client v0.7';
    protected $_referrer = '';
    protected $_timeout = 180;
    protected $_connect_timeout = 20;


    protected $_id; //标识 一般用于记录数据ID 以便返回更新数据表
    protected $_ext = NULL;//一些附件信息
    protected $_log = 1;//日志开关,默认开启
    //2017-12-13 removed:   protected $_db_log_table = 'tcapi_log_admin';
    protected $_log_type = 'file';//mysql\mongodb\file
    protected $_log_config = NULL;//数据库使用特殊配置组
    protected $_log_err_file_path = APPPATH.'logs/tcapi';//数据库日志存储失败后使用文本记录在本地的路径
    protected $_local = ['server'=> ['name' => 'tcapi-client']];
    protected $_encoding = 'UTF-8';
    protected $client_id = '';
    protected $client_key = '';
    protected $_sign = TRUE;//验证开关
    protected $_token;//成对检索

    public function __construct($service=NULL,$config=NULL) {
        $this->_token=uniqid().Text::random('alnum',8);

        $this->config($config);

        if($service){
            $this->url($this->_api_root.'/'.$service);
            $this->_service = $service;
        }

        $this->_curl = curl_init();
        curl_setopt($this->_curl, CURLOPT_HEADER, TRUE);
        curl_setopt($this->_curl, CURLOPT_NOBODY, FALSE);
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->_curl, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
    }



    public static function factory($service=NULL,$config=NULL){
        return new TCAPI($service,$config);
    }


    public function config($config){
        if(is_array($config) && $config) {
            $this->client_id = isset($config['client_id']) ? $config['client_id']:'' ;
            $this->client_key = isset($config['client_key']) ? $config['client_key']:'' ;
            if(isset($config['api_url'])){
                $this->_api_root = $config['api_url'];
            }
            if(isset($config['log_type'])){
                $this->_log_type = $config['log_type'];
            }
            if(isset($config['log_path']) && $config['log_path']){
                //$this->_log = new MyLog($config['log_path']);
                //$this->_log_enable = TRUE;
                $this->_log_err_file_path = $config['log_path'];
            }else{
                //$this->_log_enable = FALSE;
            }

        }else{
            if($config===NULL){
                $config_array = Kohana::$config->load('tcapi')->get('api');
            }else{
                $config_array = Kohana::$config->load('tcapi')->get($config);
            }

            $this->client_id = isset($config_array['client_id']) ? $config_array['client_id'] : '';
            $this->client_key = isset($config_array['client_key']) ? $config_array['client_key'] : '';
            if (isset($config_array['api_url'])) {
                $this->_api_root = $config_array['api_url'];
            }
            if (isset($config_array['log_type'])) {
                $this->_log_type = $config_array['log_type'];
            }
            if (isset($config_array['log_path']) && $config_array['log_path']) {
                //$this->_log = new MyLog($config_array['log_path']);
                //$this->_log_enable = TRUE;
                $this->_log_err_file_path = $config_array['log_path'];
            } else {
                //$this->_log_enable = FALSE;
            }
        }

        $local_server = Kohana::$config->load('local')->get('server');//读取本地属性 local['server']['name'];
        if (isset($local_server['name'])) {
            $this->_local['server']['name'] = $local_server['name'];
        }

        return $this;
    }




    //更改网址
    public function url($url){
        $this->_url = $url;
        return $this;
    }

    //设置SSL属性
    public function ssl(){
        curl_setopt($this->_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        return $this;
    }

    //完成配置
    public function compile($sign=TRUE){

        if($sign){
            $this->httpheader(array('APPID:'.$this->client_id,'APPSIGN:'.md5(json_encode($this->_postdata).$this->client_key)));
        }
        curl_setopt($this->_curl, CURLOPT_CONNECTTIMEOUT, $this->_connect_timeout);
        curl_setopt($this->_curl, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($this->_curl, CURLOPT_REFERER, $this->_referrer);
        curl_setopt($this->_curl, CURLOPT_URL, $this->_url);
        if(strtolower(substr($this->_url,0,8))=='https://' ){
            $this->ssl();
        }
/*        if($this->_log_enable){//文件日志开关
            if(is_array($this->_query)){
                $query = json_encode($this->_query);
            }else{
                $query = $this->_query;
            }
            $this->_log->write(array(array('body'=>$query,'time'=>time())),"time [{$this->_token}] --- REQUEST:\r\nbody\r\n");
        }*/

        return $this;
    }

    //完成分析 在调用$this->curl($ch)后使用
    public function analyze(){
        $this->_error['no'] = curl_errno($this->_curl);
        if ($this->_error['no'] > 0) {
            $this->_error['msg'] = curl_error( $this->_curl );
            $this->_body = $this->_res;
        }else{
            $header_size = curl_getinfo( $this->_curl, CURLINFO_HEADER_SIZE );
            if( $header_size ){
                $this->_header = substr( $this->_res, 0, $header_size );
                $this->_body = substr( $this->_res, $header_size );
            }else{
                $this->_body = $this->_res;
            }
        }
        $body = $this->_body;

        if(function_exists('mb_detect_encoding')){//编码转换
            $encoding = mb_detect_encoding($this->_body,array('ASCII','ISO-8859-1','GB2312','BIG5','GBK','UTF-8'));
            if($encoding!=$this->_encoding){
                try{
                    $body = iconv($encoding,$this->_encoding,$this->_body);
                }catch(Exception $e){
                    $body = mb_convert_encoding($this->_body,$this->_encoding);
                }
            }
        }

/*        if($this->_log_enable){//文件日志开关
            $this->_log->write(array(array('body'=>$body,'time'=>time())),"time [{$this->_token}] --- RESPONSE:\r\nbody\r\n");
        }*/


/*        if($this->_log && $this->_log_table){//数据记录
            DB::insert($this->_log_table,array('service_name','method','request','response','create_time','errno','error','url') )
                ->values(array($this->_service,$this->_method,json_encode($this->_query),$body,time(),intval($this->_error['no']),$this->_error['msg'],substr($this->_url,0,400)))
                ->execute();
        }*/

        if($this->_log && $this->_log_type){
            $res = TCAPILog::factory($this->_log_type,$this->_log_config)->log([
                'method' => $this->_method,
                'request' => json_encode($this->_query),
                'response' => $body,
                'errno' => intval($this->_error['no']),
                'error' => $this->_error['msg'],
                'url' => $this->_url,
                'client_ip' => isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $this->_local['server']['name'],
                'hash' => $this->_token,
            ]);
            //var_dump($res,$this->_log_err_file_path);

            if(!$res &&  $this->_log_err_file_path){//如果数据库存储失败
                //echo "tcapi-error:['{$this->_log_type}'] log storage failed! &local file log!\r\n";
                TCAPILog::factory('file',$this->_log_err_file_path)->log([
                    'method' => $this->_method,
                    'request' => json_encode($this->_query),
                    'response' => $body,
                    'errno' => intval($this->_error['no']),
                    'error' => $this->_error['msg'],
                    'url' => $this->_url,
                    'client_ip' => isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $this->_local['server']['name'],
                    'hash' => $this->_token,
                ]);
            }
        }




        return $this;

    }


    //整合一套 sign开关
    public function execute($sign=TRUE){
        $this->compile($sign);
        $this->_res = curl_exec($this->_curl);
        $this->analyze();
        curl_close($this->_curl);

        return $this;
    }





    public function import($ch=NULL){//导入已经处理的CURL源 配合TCAPIQueue
        if(is_resource($ch)){
            $this->_curl = $ch;
            $this->_res =curl_multi_getcontent($this->_curl);
            return $this;
        }
        return $this;
    }




    public function referrer($referrer=NULL){
        if($referrer){
            $this->_referrer=$referrer;
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
        }
        return $this;
    }

    public function connect_timeout($timeout){
        if($timeout){
            $this->_connect_timeout=intval($timeout);
        }
        return $this;
    }

    public  function curl($ch=NULL){
        if(is_resource($ch)){
            $this->_curl = $ch;
            return $this;
        }
        return $this->_curl;
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

    public function error(){
        return $this->_error;
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
        $this->_query = $query;
        $this->_postdata = $this->value_string($this->_query);

        curl_setopt($this->_curl, CURLOPT_POST, TRUE);
        //url_setopt($this->_curl, CURLOPT_POSTFIELDS, http_build_query($this->_postdata));

        if(is_array($this->_postdata) ) {

            if($this->_file){
                $data = $this->_postdata;
                curl_setopt($this->_curl, CURLOPT_SAFE_UPLOAD, FALSE);
                curl_setopt($this->_curl, CURLOPT_BINARYTRANSFER, TRUE);
                foreach ($this->_file as $k => $v){
                    $data[$k] = $v;
                }
                curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $data);
            }else{
                curl_setopt($this->_curl, CURLOPT_POSTFIELDS, http_build_query($this->_postdata));
            }
        }else{
            curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $this->_postdata);

        }

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

    //自定义HTTP头部分
    public function httpheader($array=NULL) {
        if($array!==NULL) {
            $this->curlopt(CURLOPT_HTTPHEADER, $array);
        }
        return $this;
    }

    /**
     * @param null|array|string $opt
     * @param null|string $value
     * $opt 数组方式 array(array(CURLOPT_CUSTOMREQUEST,'OPTIONS'),array('CURLOPT_CUSTOMREQUEST','TRACE'));
     * $value 单条方式 $opt为CURL配置名 $value为值
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


    //记录一个标识
    public function id($id=NULL){
        if($id!==NULL){
            $this->_id = $id;
            return $this;
        }else{
            return $this->_id;
        }
    }

    //附带的一些数据
    public function ext($ext=NULL){
        if($ext!==NULL){
            $this->_ext = $ext;
            return $this;
        }else{
            return $this->_ext;
        }
    }

    public function _array(){
        return array(
            'curl'=>$this->_curl,
            'body'=>$this->_body,
            'header'=>$this->_header,
            'method'=>$this->_method,
            'url'=>$this->_url,
            'service'=>$this->_service,
            'referrer'=>$this->_referrer,
            'timeout'=>$this->_timeout,
            'connect_timeout'=>$this->_connect_timeout,
            'id'=>$this->_id,
            'ext'=>$this->_ext,
        );
    }


    public function log($off=NULL){
        $this->_log = $off;
        return $this;
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


    public function value_string($array = NULL){
        if(is_array($array)){
            foreach($array as $k => $v){
                $array[$k] = is_array($v) ? $this->value_string($v) : (string) $v;
            }
        }
        return $array;
    }




}

/**
 * Class TCAPILog
 * TCAPI
 */
class TCAPILog {

    protected $type = 'file';

    protected $file =  [
        'path' => APPPATH.'logs/tcapi'
    ];

    protected $mysql = [
        'table' => 'tcapi_log_tcapi',
        'config' => 'default',
    ];

    protected $mongodb = [
        'collection' => 'tcapi_log_tcapi_$Y$m',
        'config' => 'tcapi',

    ];

    protected $logserver = [
        'table' => 'tcapi_log_tcapi_$Y$m',
        'database' => 'tcapi',
        'config' => 'tcapi',
    ];


    public static function factory($type=NULL,$config=NULL)
    {
        return new TCAPILog($type,$config);
    }

    public function __construct($type=NULL,$config=NULL)
    {
        if($type!==NULL && $type){
            $this->type = $type;
            switch ($type){
                case 'file':
                    $this->file($config);
                    break;
                case 'mysql':
                    $this->mysql($config);
                    break;
                case 'mongodb':
                    $this->mongodb($config);
                    break;
                case 'logserver':
                    $this->logserver($config);
                    break;
                default:
                    throw new Kohana_Exception('tcapi: dblog storage error!');
                    break;
            }
        }

    }



    /** 初始化file类型日志
     * @param $path
     * @return $this
     */
    public function file($path)
    {
        if($path){
            $this->mysql['path'] = $path;
        }
        return $this;
    }

    /** 初始化mysql表类型日志
     * @param $config_group
     * @return $this
     */
    public function mysql($config_group)
    {
        if($config_group){
            $this->mysql['config'] = $config_group;
        }
        return $this;
    }

    /** 初始化mongodb类型日志
     * @param $config_group
     * @return $this
     */
    public function mongodb($config_group)
    {
        if($config_group){
            $this->mongodb['config'] = $config_group;
        }
        $this->mongodb['collection'] = self::date_name($this->mongodb['collection']);
        return $this;
    }

    /** 初始化mongodb类型日志
     * @param $config_group
     * @return $this
     */
    public function logserver($config_group)
    {
        if($config_group){
            $this->logserver['config'] = $config_group;
        }
        $this->logserver['table'] = self::date_name($this->logserver['table']);
        $config_array = Kohana::$config->load('logserver')->get($this->logserver['config']);

        $this->logserver['url'] = isset($config_array['url']) ? $config_array['url'] : '' ;
        $this->logserver['account'] = isset($config_array['account']) ? $config_array['account'] : '' ;
        $this->logserver['account_key'] = isset($config_array['account_key']) ? $config_array['account_key'] : '' ;
        $this->logserver['url'] = isset($config_array['url']) ? $config_array['url'] : '' ;

        return $this;
    }


    /** 按日期分配名称:一般用于分表的表明
     * @param $string
     * @return bool|string
     */
    public static function dateName($string)
    {
        return self::date_name($string);
    }
    public static function date_name($string)
    {
        $r1 = ['$Y','$m','$d','$H','$i','$s'];
        $r2 = explode(',',date('Y,m,d,H,i,s'));
        return str_replace($r1,$r2,$string);
    }



    public function log($array){

        switch ($this->type){
            case 'file':
                return $this->file_log($array);
                break;
            case 'mysql':
                return $this->mysql_log($array);
                break;
            case 'mongodb':
                return $this->mongodb_log($array);
                break;
            case 'logserver':
                return $this->logserver_log($array);
                break;
            default:
                throw new Kohana_Exception('tcapi: dblog storage error!');
                break;
        }


    }



    public function fileLog($array)
    {
        return $this->file_log($array);
    }
    public function file_log($array)
    {
        $hash =  isset($array['hash']) ? $array['hash'] : '';
        $url =  isset($array['url']) ? $array['url'] : '';
        $client_ip =  isset($array['client_ip']) ? $array['client_ip']: '';
        $method = isset($array['method']) ?$array['method'] :'';
        $create_time = time();
        $errno = isset($array['errno'])  ? intval($array['errno']) : 0;
        $error = isset($array['error'])  ? $array['error'] : '';

        if(isset($array['request'])){
            $log = new TCAPIFileLog($this->file['path']);
            $request_line = date("Y-m-d H:i:s",$create_time)." "."[client_ip:.".$client_ip."] [hash:".$hash."] [url:".$url."] [method:".$method."] -- REQUEST:\r\n".$array['request'];
            $log->write($request_line);
        }


        if(isset($array['response'])){
            $log = new TCAPIFileLog($this->file['path']);
            $response_line = date("Y-m-d H:i:s",$create_time)." "."[client_ip:.".$client_ip."] [hash:".$hash."] -- RESPONSE:\r\n".$array['response'];
            $log->write($response_line);
        }
        return TRUE;


    }


    public function mysqlLog($array)
    {
        return $this->mysql_log($array);
    }
    public function mysql_log($array)
    {
        if($array){//数据记录
            return DB::insert($this->mysql['table'],array('method','request','response','create_time','errno','error','url') )
                ->values(array(
                    isset($array['method']) ?$array['method'] :'' ,
                    isset($array['request'])  ? $array['request'] :'',
                    isset($array['response'])  ? $array['response'] :'',
                    time(),
                    isset($array['errno'])  ? intval($array['errno']) :0,
                    isset($array['error'])  ? $array['error'] :'',
                    isset($array['url'])  ? substr($array['url'],0,5000) :'',
                ))
                ->execute();
        }
        return FALSE;
    }


    public function mongodbLog($array)
    {
        return $this->mongodb_log($array);
    }
    public function mongodb_log($array){
        if($array){//数据记录
            return Model_Mongo::factory($this->mongodb['collection'],$this->mongodb['config'])->save([
                'method' => isset($array['method']) ?$array['method'] :'' ,
                'request' => isset($array['request'])  ? $array['request'] :'',
                'response' => isset($array['response'])  ? $array['response'] :'',
                'create_time' => time(),
                'errno' => isset($array['errno'])  ? intval($array['errno']) :0,
                'error' => isset($array['error'])  ? $array['error'] :'',
                'url' => isset($array['url'])  ? $array['url'] :'',
                'client_ip' => isset($array['client_ip'])  ? $array['client_ip'] :'',
                'hash' => isset($array['hash'])  ? $array['hash'] :'',

            ]);
        }
        return FALSE;
    }


    public function logServerLog($array)
    {
        return $this->logServerLog($array);
    }
    public function logserver_log($array)
    {
        if($array){//数据记录
            $data = json_encode([
                'method' => isset($array['method']) ?$array['method'] :'' ,
                'request' => isset($array['request'])  ? $array['request'] :'',
                'response' => isset($array['response'])  ? $array['response'] :'',
                'create_time' => time(),
                'errno' => isset($array['errno'])  ? intval($array['errno']) :0,
                'error' => isset($array['error'])  ? $array['error'] :'',
                'url' => isset($array['url'])  ? $array['url'] :'',
                'client_ip' => isset($array['client_ip'])  ? $array['client_ip'] :'',
                'hash' => isset($array['hash'])  ? $array['hash'] :'',
            ]);
            $sign = md5($data.$this->logserver['account_key']);


            $res = HttpClient::factory($this->logserver['url'].'/'.$this->logserver['table'])
                ->post([
                    'action' => 'insert',
                    'account' => $this->logserver['account'],
                    'data' => $data,
                    'sign' => $sign,
                ])
                ->execute()
                ->as_array();

            if($res && isset($res['success']) && $res['success']){
                return $res;
            }else{
                return FALSE;
            }
        }
        return FALSE;
    }

}
/**
 * Class TCAPIFileLog
 * TCAPI
 */
class TCAPIFileLog {

    /**
     * @var  string  Directory to place log files in
     */
    protected $_directory;
    protected $_format='time --- level: body in file:line';

    /**
     * Creates a new file logger. Checks that the directory exists and
     * is writable.
     *
     *     $writer = new Log_File($directory);
     *
     * @param   string  $directory  log directory
     * @return  void
     */
    public function __construct($directory=NULL)
    {
        if($directory){
            $this->directory($directory);
        }
    }

    public function directory($directory)
    {
        if ( ! is_dir($directory) OR ! is_writable($directory))
        {
            throw new Kohana_Exception('Directory :dir must be writable',
                array(':dir' => Debug::path($directory)));
        }
        // Determine the directory path
        $this->_directory = realpath($directory).DIRECTORY_SEPARATOR;
    }


    /**
     * Writes each of the messages into the log file. The log file will be
     * appended to the `YYYY/MM/DD.log.php` file, where YYYY is the current
     * year, MM is the current month, and DD is the current day.
     *
     *     $writer->write($messages);
     *
     * @param     $message
     * @return  void
     */
    public function write( $message )
    {
        // Set the yearly directory name
        $directory = $this->_directory.date('Y');

        if ( ! is_dir($directory))
        {
            // Create the yearly directory
            mkdir($directory, 02777);

            // Set permissions (must be manually set to fix umask issues)
            chmod($directory, 02777);
        }

        // Add the month to the directory
        $directory .= DIRECTORY_SEPARATOR.date('m');

        if ( ! is_dir($directory))
        {
            // Create the monthly directory
            mkdir($directory, 02777);

            // Set permissions (must be manually set to fix umask issues)
            chmod($directory, 02777);
        }

        // Set the name of the log file
        $filename = $directory.DIRECTORY_SEPARATOR.date('d').EXT;

        if ( ! file_exists($filename))
        {
            // Create the log file
            file_put_contents($filename, Kohana::FILE_SECURITY.' ?>'.PHP_EOL);

            // Allow anyone to write to log files
            chmod($filename, 0666);
        }

        file_put_contents($filename, PHP_EOL . $message, FILE_APPEND);

    }




}





