<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/5/26
 * Time: 下午6:45
 */

include_once realpath(dirname(__FILE__))."/config.php";
include_once realpath(dirname(__FILE__))."/api/lib/nusoap.php";
include_once('GongAnBu_XML.php');

class Lib_Identity_GongAnBu_API
{

    private $xml;

    const API_STATUS_SUCCESS = '0000';          //请求成功
    const API_STATUS_LOCAL_ERROR = '3000';      //本地失败
    const API_STATUS_REMOTE_ERROR = '4000';     //远端失败
    const API_STATUS_RESULT_ERROR = '5000';     //返回结果异常
    const API_STATUS_PARAMS_ERROR = '6000';     //参数异常

    public $_api_status = array(
        self::API_STATUS_SUCCESS => '发送成功',
        self::API_STATUS_LOCAL_ERROR => '接口请求异常',
        self::API_STATUS_REMOTE_ERROR => '接口返回异常',
        self::API_STATUS_RESULT_ERROR => '接口返回结果异常',
        self::API_STATUS_PARAMS_ERROR => '请求参数异常',
    );

    const API_RESULT_SUCCESS = '1000';          //验证成功
    const API_RESULT_DIFFERENT = '2000';        //验证不一致
    const API_RESULT_EMPTY = '3000';            //库中无此号
    const API_RESULT_PHOTO_FAIL = '4000';       //证件照异常

    public $_api_result = array(
        self::API_RESULT_SUCCESS => '一致',
        self::API_RESULT_DIFFERENT => '不一致',
        self::API_RESULT_EMPTY => '服务结果:库中无此号，请到户籍所在地进行核实',
        self::API_RESULT_PHOTO_FAIL => '证件照异常',
    );


    public function __construct () {
        $this->xml = new GongAnBu_XML();
    }

    /**
     * @param $name
     * @param $identify
     * @return array
     * @desc 返回认证信息
     */
    public function doVerify ($name, $identify) {

        if($name == '' || $identify == '' ){
            return array('status'=>false,'msg'=>$this->_api_status[self::API_STATUS_LOCAL_ERROR],'code'=> self::API_STATUS_PARAMS_ERROR);
        }

        //格式化数据
        $params = array(
            array(  //可以多个同时认证
                'name' => $name,
                'identity' => $identify,
            )
        );

        $condition = $this->xml->nciicCheck($params);

        $result = $this->postData(array(
            'inLicense' => LICENSE,
            'inConditions' => $condition,
        ));

        return $result;
    }

    /**
     * 公安部接口 验证身份证信息
     * @param $params
     * @return array
     */
    private function postData ($params) {

        try {
            $soap = new nusoap_client(WSDL, 'wsdl');
            $soap->soap_defencoding = 'UTF-8';
            $soap->decode_utf8 = false;
            $soap->certRequest;

            //文件日志
            $log = new MyLog(DOCROOT.'protected/logs');
            //随机ID
            $id = uniqid(Text::random('alnum',8));
            //IP地址
            $ip = Request::$client_ip;
            //记录发送数据
            $log->write(array(array('body'=>json_encode($params),'time'=>time())),"time [{$id}] - IP:{$ip} --- REQUEST: \r\nbody \r\n");

            $result = $soap->call('nciicCheck',$params);

            //记录返回数据
            $log->write(array(array('body'=>json_encode($result),'time'=>time())),"time [{$id}] --- RESPONSE: \r\nbody\r\n");
        }catch (Exception $e){
            //记录返回数据
            $log->write(array(array('body'=>json_encode($result),'time'=>time())),"time [{$id}] --- RESPONSE: \r\nbody\r\n");
            return array('status'=>false,'msg'=>self::API_STATUS_LOCAL_ERROR,'code'=> $this->_api_status[self::API_STATUS_LOCAL_ERROR],'api_result'=>array('error'=>$e->getMessage()));
        }

        return $this->formatRemoteData($result);

    }

    private function formatRemoteData($return_str){
        if(isset($return_str['out']) && $return_str['out'] != '') {
            $result_array = json_decode(json_encode(simplexml_load_string($return_str['out'])), TRUE);
//            $result_array = json_decode($return_str, TRUE);
            if (json_last_error() == JSON_ERROR_NONE) {
                if (isset($result_array['ROW']['INPUT']['gmsfhm']) && isset($result_array['ROW']['INPUT']['xm'])){
                    $info['identity_code'] = $result_array['ROW']['INPUT']['gmsfhm'];
                    $info['name'] = $result_array['ROW']['INPUT']['xm'];
                    if(isset($result_array['ROW']['OUTPUT']['ITEM'])){
                        if(isset($result_array['ROW']['OUTPUT']['ITEM']['0']['result_gmsfhm']) && isset($result_array['ROW']['OUTPUT']['ITEM']['1']['result_xm'])){
                            if($result_array['ROW']['OUTPUT']['ITEM']['0']['result_gmsfhm'] == $result_array['ROW']['OUTPUT']['ITEM']['1']['result_xm']){

                                if(isset($result_array['ROW']['OUTPUT']['ITEM']['2']['xp']) && $result_array['ROW']['OUTPUT']['ITEM']['2']['xp'] != ''){
                                    $info['photo'] = $result_array['ROW']['OUTPUT']['ITEM']['2']['xp'];
                                    $info['msg'] = $this->_api_result[self::API_RESULT_SUCCESS];
                                    $info['result'] = self::API_RESULT_SUCCESS;
                                    return array('status'=>true,'msg'=>$this->_api_status[self::API_STATUS_SUCCESS],'code'=>self::API_STATUS_SUCCESS,'api_result'=>$info);
                                } else {
                                    $info['msg'] = $this->_api_result[self::API_RESULT_PHOTO_FAIL];
                                    $info['result'] = self::API_RESULT_PHOTO_FAIL;
                                    return array('status'=>true,'msg'=>$this->_api_status[self::API_STATUS_SUCCESS],'code'=>self::API_STATUS_SUCCESS,'api_result'=>$info);
                                }
                            } else {
                                $info['msg'] = $this->_api_result[self::API_RESULT_DIFFERENT];
                                $info['result'] = self::API_RESULT_DIFFERENT;
                                return array('status'=>true,'msg'=>$this->_api_status[self::API_STATUS_SUCCESS],'code'=>self::API_STATUS_SUCCESS,'api_result'=>$info);
                            }
                        } elseif (isset($result_array['ROW']['OUTPUT']['ITEM']['0']['errormesage'])){
                            $info['msg'] = $this->_api_result[self::API_RESULT_EMPTY];
                            $info['result'] = self::API_RESULT_EMPTY;
                            return array('status'=>true,'msg'=>$this->_api_status[self::API_STATUS_SUCCESS],'code'=>self::API_STATUS_SUCCESS,'api_result'=>$info);
                        }
                    }
                }
            }
        }
        return array('status'=>false,'msg'=>$this->_api_status[self::API_STATUS_RESULT_ERROR],'code'=>self::API_STATUS_RESULT_ERROR,'api_result'=>array('error'=>$return_str));
    }

}