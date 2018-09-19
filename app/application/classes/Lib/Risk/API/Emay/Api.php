<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 2018/1/9
 * Time: 下午5:27
 */
Class Lib_Risk_API_Emay_Api
{
    const GET_TOKEN_URL = 'http://opensdk-emay-cn.http.p.timecash.cn/HD_GetAccess_Token.asmx/GetACCESS_TOKEN';
    const GET_INFO_URL = 'http://opensdk-emay-cn.http.p.timecash.cn/MADE_API/emda_xdzh.asmx/GetEmda_xdzh_dt';

    const CYCLE = 12;
    const PLATFORM = 0;

    private $AppID = '0921C1A2W6EBBW4BC5W80EEW345CF945B301';
    private $AppSecret = 'ED78DE65L05A9L48D9L820AL81B142751AC3';
    private $key = '71B69DDEH78B8H4B7AHBAF6HFAEEFAC5EEF7';

    protected $user_id;

    public function user_id($user_id)
    {
        if($user_id){
            $this->user_id = $user_id;
        }
        return $this;
    }

    public function execute($data)
    {
        $token = $this->getToken();
        $request = 'Phone='.$data['Phone'].'&cycle='.self::CYCLE.'&Platform='.self::PLATFORM.'&ACCESS_TOKEN='.$token;
        $result = $this->postData($request,self::GET_INFO_URL);
        Model::factory("Emay_Data")->add_data($this->user_id,$data,$result);
        return $result;
    }

    public function getToken()
    {
        $result = Model::factory("Emay_Data")->getOne();
        if(!$result){
            $request = 'AppID'.'='.$this->AppID.'&AppSecret'.'='.$this->AppSecret.'&Key'.'='.$this->key;
            $response = $this->postData($request,self::GET_TOKEN_URL);
            Model::factory("Emay_Data")->create($response['access_token']);
            return $response['access_token'];
        }else{
            return $result['token'];
        }
    }


    private static function postData($data = null,$url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $res = curl_exec( $ch );

        if (curl_errno($ch)) {
            $data['error'] = "-999";
            $data['msg'] = curl_error($ch);
            $res = $data;
        }
        curl_close( $ch );
        $data = simplexml_load_string($res);
        $rese = json_decode(json_encode($data),true);
        return json_decode($rese[0],true);
    }
}