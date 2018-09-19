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
class Lib_TONGDUN2_HitRuleDetail {

    private $api_url = ''; // 请求URL
    public $event_data = []; // 请求参数数组

    function __construct($event_data = []) {
        if(empty($event_data['partner_code'])){
            include_once realpath(dirname(__FILE__))."/config.php";
        }else{
            include_once realpath(dirname(__FILE__))."/pufubao_config.php";
        }
        unset($event_data['partner_code']);
        $event_data['partner_code']=PARTNER_CODE;
        $event_data['partner_key']=PARTNER_KEY;
        $this->event_data=$event_data;
        $this->api_url=RULES_REQUESTURL.'?'.http_build_query($event_data);
    }

    public function curlGetOpen() {
        $options = array(
            CURLOPT_URL => $this->api_url, // 请求URL
            CURLOPT_RETURNTRANSFER => 1, // 获取请求结果
            CURLOPT_HEADER => 0,		 //设置头文件的信息作为数据流输出
            // -----------请确保启用以下两行配置------------
            CURLOPT_SSL_VERIFYPEER => 1, // 验证证书
            CURLOPT_SSL_VERIFYHOST => 2, // 验证主机名
        );
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
        $tdlog_map = array('provider', 'action', 'msg', 'req_data', 'resp_data', 'type','reference_id','create_time');
        $tdlog_data = array('TongDun2','HitRuleDetail','调用TongDun HitRuleDetail接口',json_encode($this->event_data), $response,'TongDunAPI',0,time());
        DB::insert('api_out_log', $tdlog_map)->values($tdlog_data)->execute();
        return json_decode($response, true);
    }
}
