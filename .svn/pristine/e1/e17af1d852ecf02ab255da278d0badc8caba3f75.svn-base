<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * ---------------同盾风险决策API---------------
 *
 * Created by PhpStorm.
 * User: guorui
 * Date: 2016/1/8
 * Time: 15:48
 */
include_once realpath(dirname(__FILE__))."/config.php";
class Lib_Risk_API_ZhiCheng_RiskService {
    private $tx='101';
    //所有发送数据
    private $allRequestData = [];

    //API 报告接口返回码
    public $result_code_array = array(
        '0000'   =>	'成功',
        '0001'   => '查询成功但无数据',
        '0005'	=>  '查询机构接收数据成功',
        '-1001'	=>  '当日查询达到上限',
        '-1002'	=>  '服务器繁忙',
        '4001'	=>  '用户名解密出现异常',
        '4002'	=>  '参数解密出现异常',
        '4005'	=>  '请求的接口不存在',
        '4006'	=>  '字符集解码失败',
        '4008'	=>  '输入的参数部分格式不正确,需json格式',
        '4009'	=>  '用户调用参数不正确',
        '4011'	=>  '参数长度不正确',
        '4012'	=>  '传入的参数存在空值！',
        '4100'	=>  '超出机构单次身份证号上传上限',
        '4101'	=>  'API用户不存在',
        '4102'	=>  '用户已停用',
        '4104'	=>  '用户所在机构已停用',
        '4105'	=>  '查询数最已全部用完,如需添加请联系客服',
        '4106'	=>  '没有查询该API的权限',
        '4107'	=>  'IP地址无权限',
        '4108'	=>  '用户没有查询权限',
        '4110'	=>  '查询人次已全部用完,如需添加请联系客服',
        '4111'	=>  '超过API访问频次限制',
        '4200'	=>  '参数错误',
        '5000'	=>  '出现系统错误,需要检查错误日志',
    );

    public function get_rc4key() {
        return RC4_KEY;
    }

    function getuserid() {
        $public_content=file_get_contents(APPPATH.'classes/Lib/Risk/API/ZhiCheng/ZC_PublicKey_V2.pem');
        $public_key=openssl_get_publickey($public_content);
        openssl_public_encrypt(USER_NAME,$encrypted,$public_key);//rsa公钥加密
        return urlencode(base64_encode($encrypted));
    }

    public function getallRequestData(array $data = []) {
        $json_arr=array('tx'=>$this->tx, 'data'=>array('name'=>$data['name'], 'idNo'=>$data['idNo'], 'queryReason'=>$data['queryReason']));
        $rc4_str=$this->rc4(RC4_KEY,json_encode($json_arr));
        $post_data['params']=urlencode(base64_encode($rc4_str));
        $post_data['userid']=$this->getuserid();
        return $post_data;
    }

    /**
     * 发送请求
     */
    public function postData(array $data = []) {
        if(empty($this->allRequestData)){
            $this->allRequestData=$this->getallRequestData($data);
        }
        $result = $this->curlOpen(REQUEST_URL, $this->allRequestData);
        return $result;
    }

    function curlOpen($api_url, array $params) {
        $options = array(
            CURLOPT_POST => 1, // 请求方式为POST
            CURLOPT_URL => $api_url, // 请求URL
            CURLOPT_RETURNTRANSFER => 1, // 获取请求结果
            CURLOPT_POSTFIELDS => http_build_query($params) // 注入接口参数
        );
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /*
    * rc4加密算法
    * $pwd 密钥
    * $data 需加密字符串
    */
    function rc4 ($pwd, $data){
        $key[] ="";
        $box[] ="";
        $cipher="";
        $pwd_length = strlen($pwd);
        $data_length = strlen($data);
        for ($i = 0; $i < 256; $i++)
        {
            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++)
        {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $data_length; $i++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }
        return $cipher;
    }
}
