<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: yangyuexin
 * Date: 2018/1/10
 * Time: 下午2:15
 */
class Lib_Risk_API_Geo_API extends Lib_Common
{
    private $authcode;
    public function __construct(){
        $this->authcode =time()."00".Lib_Risk_API_Geo_API_config::$uno."".Text::random('alnum', 22).time();//目前要求：50位，接口调用唯一标识，前10位时间戳，中间8位：00+用户编码（就是uid），后面32位唯一标识码（或id）
    }


    public function phoneinfo($request)
    {
        if (!isset($request['mobile'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 mobile');
        }

        if (!isset($request['name'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 name');
        }

        if (isset($request['identity'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 identity');
        }

        $client = new Lib_Risk_API_Geo_API_lib_Client(Lib_Risk_API_Geo_API_config::$server,Lib_Risk_API_Geo_API_config::$encrypted,Lib_Risk_API_Geo_API_config::$encryptionType,Lib_Risk_API_Geo_API_config::$encryptionKey,Lib_Risk_API_Geo_API_config::$username,Lib_Risk_API_Geo_API_config::$password,Lib_Risk_API_Geo_API_config::$uno);
        $path = $client->server."/civp/getview/api/u/queryUnify" ;
        $params = array('innerIfType' => 'A4,B7',
            'cid' => $request['mobile'],
            'idNumber'=>$request['identity'],
            'realName'=>$request['name'],
            'authCode'=>$this->authcode
        );
        $data = $client->getData($path,$params) ;
        return$data;
    }

    /**
     * 验卡四要素
     * @param $request
     * @return mixed
     */
    public function authcard($request)
    {
        if (!isset($request['phone'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 phone');
        }

        if (!isset($request['name'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 name');
        }

        if (isset($request['id_card'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 id_card');
        }
        if (isset($request['card_no'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 card_no');
        }

        $client = new Lib_Risk_API_Geo_API_lib_Client(Lib_Risk_API_Geo_API_config::$server,Lib_Risk_API_Geo_API_config::$encrypted,Lib_Risk_API_Geo_API_config::$encryptionType,Lib_Risk_API_Geo_API_config::$encryptionKey,Lib_Risk_API_Geo_API_config::$username,Lib_Risk_API_Geo_API_config::$password,Lib_Risk_API_Geo_API_config::$uno);
        $path = $client->server."/civp/getview/api/u/queryUnify" ;
        $params = array('innerIfType' => 'Z4',
            'cid' => $request['phone'],
            'idNumber'=>$request['id_card'],
            'realName'=>$request['name'],
            'cardNo'=>$request['card_no'],
            'authCode'=>$this->authcode
        );
        $data = $client->getData($path,$params) ;
        return$data;

    }



}