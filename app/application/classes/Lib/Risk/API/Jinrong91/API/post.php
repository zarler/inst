<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangyuexin
 * Date: 2017/12/20
 * Time:
 */
include_once realpath(dirname(__FILE__))."/config.php";
class Lib_Risk_API_Jinrong91_API_post
{
    private $namecode;//我司的代号码
    private $sign;//签名
    private $url;//请求的URL


    function __construct ($rule='') {
        $this->namecode = Lib_Risk_API_Jinrong91_API_config::$namecode;
        $this->sign = Lib_Risk_API_Jinrong91_API_config::$sign;
        $this->url = Lib_Risk_API_Jinrong91_API_config::$url;
        $this->rule = $rule;

    }


    function post($url, $data)
    {
        $header[] = "Content-Type: application/octet-stream";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            printcurl_error($ch);
        }
        curl_close($ch);
        return $response;
    }

    /**
     * 异步获取
     * @param $name
     * @param $idcard
     * @return string
     */
    function getinfo($name,$idcard)
    {
        $array = array(
            "realName"=>$name,
            "idCard"=>$idcard,
        );
        $msgBody = base64_encode(json_encode($array));
        $pkgReq = "01|".$this->namecode."|01|1001|01|01|" . $msgBody . "|||".$this->sign;
        $result = $this->post($this->url, $pkgReq);
        $pkgRsp = explode("|", $result);

        $rspStr = array(
            "version" => $pkgRsp[0],    //默认01
            "custNo" => $pkgRsp[1],        //请求源
            "encode" => $pkgRsp[2],     //01.UTF8 02.GBK
            "trxCode" => $pkgRsp[3],    //报文编号 默认四位 例:0001
            "encryptType" => $pkgRsp[4],//加密类型 01.不加密 02.RSA
            "msgType" => $pkgRsp[5],    //01.JSON 02.XML 03.Protobuf
            "msgBody" => $pkgRsp[6],    //报文主体为Base64编码的字节数组
            "retCode" => $pkgRsp[7],    //返回代码
            "retMsg" => $pkgRsp[8],     //返回消息
            "sign" => $pkgRsp[9]        //签名
        );
        $info['msgbody']=base64_decode($rspStr["msgBody"]);
        $info['retmsg']=base64_decode($rspStr["retMsg"]);
        $info['retcode']=$rspStr["retCode"];
        return $info;
    }

    /**
     * 主动获取
     * @param $trxno
     * @return string
     */
    function getinfoactive($trxno){
        $array = array(
            "trxNo"=>$trxno,
        );
        $msgBody = base64_encode(json_encode($array));
        $pkgReq = "01|".$this->namecode."|01|1002|01|01|" . $msgBody . "|||".$this->sign;
        $result = $this->post($this->url, $pkgReq);
        $pkgRsp = explode("|", $result);

        $rspStr = array(
            "version" => $pkgRsp[0],    //默认01
            "custNo" => $pkgRsp[1],        //请求源
            "encode" => $pkgRsp[2],     //01.UTF8 02.GBK
            "trxCode" => $pkgRsp[3],    //报文编号 默认四位 例:0001
            "encryptType" => $pkgRsp[4],//加密类型 01.不加密 02.RSA
            "msgType" => $pkgRsp[5],    //01.JSON 02.XML 03.Protobuf
            "msgBody" => $pkgRsp[6],    //报文主体为Base64编码的字节数组
            "retCode" => $pkgRsp[7],    //返回代码
            "retMsg" => $pkgRsp[8],     //返回消息
            "sign" => $pkgRsp[9]        //签名
        );
        $info=base64_decode($rspStr["msgBody"]);
        return $info;
    }




}
?>