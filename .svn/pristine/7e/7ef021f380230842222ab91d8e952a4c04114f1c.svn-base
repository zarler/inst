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
class Lib_TONGDUN2_RiskService {
    //通用数据
    private $event_id;

    private $secret_key;

    private $user_id;

    private $defaulData = [];
    //场景：用于限制发送数据
    private $scenario = 'default';
    //是否过滤
    private $isFilter = false;
    //事件数据
    private $Optional = [];

    function __construct($event_data = [], $isFilter = false) {
        $this->isFilter = $isFilter;
        if(empty($event_data['partner_code'])){
            include_once realpath(dirname(__FILE__))."/config.php";
        }else{
            include_once realpath(dirname(__FILE__))."/pufubao_config.php";
        }
        unset($event_data['partner_code']);
        $this->defaulData['partner_code'] = PARTNER_CODE;
        if($event_data['event_type']=='WECHAT'){
            $this->event_id = WEB_EVENT_ID;
            $this->secret_key = WEB_SECRET_KEY;
            $this->defaulData['token_id'] = $event_data['token_id'];
        }elseif ($event_data['event_type']=='IOS'){
            $this->event_id = IOS_EVENT_ID;
            $this->secret_key = IOS_SECRET_KEY;
            $this->defaulData['black_box'] = $event_data['black_box'];
        }elseif ($event_data['event_type']=='ANDROID'){
            $this->event_id = ANDROID_EVENT_ID;
            $this->secret_key = ANDROID_SECRET_KEY;
            $this->defaulData['black_box'] = $event_data['black_box'];
        }else{
            $this->defaulData['token_id'] = $event_data['token_id'];
            $this->event_id = WEB_EVENT_ID;
            $this->secret_key = WEB_SECRET_KEY;
        }
        $this->defaulData['secret_key'] = $this->secret_key;
        $this->defaulData['event_id'] = $this->event_id;
        //$this->defaulData['geoip'] = self::getIp();
        //$this->defaulData['resp_detail_type'] = strtolower($_SERVER['HTTP_USER_AGENT']); //User-Agent
        //$this->defaulData['refer_cust'] = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER']; //Referer
        //$this->defaulData['event_occur_time']=date('Y-M-d H:i:s');
        $this->setOptional($event_data);
        $this->user_id=$event_data['user_id'];
    }

    /**
     * 场景[登陆注册、借款、理财 等]
     * 给同盾 发送场景内的字段
     * 默认只发送必填字段
     */
    private function getScenarioSendKeys($scenarioKey) {
        //借款必填传送数据
        $loan_field = ['account_name','id_number','account_mobile','loan_amount','loan_term','loan_purpose','apply_province','apply_city','apply_channel','event_occur_time','card_number','work_phone','home_phone','qq_number','account_email','diploma','marriage','house_property','house_type','registered_address','home_address','contact_address','career','applyer_type','work_time','organization_address','company_address','industry','company_type','occupation','annual_income','is_cross_loan','cross_loan_count','is_liability_loan','liability_loan_count','realname_consistence','contact1_relation','contact1_name','contact1_id_number','contact1_mobile','contact1_addr','contact1_com_name','contact1_com_addr','contact2_relation','contact2_name','contact2_id_number','contact2_mobile','contact2_addr','contact2_com_name','contact2_com_addr','contact3_relation','contact3_name','contact3_id_number','contact3_mobile','contact3_addr','contact3_com_name','contact3_com_addr','contact4_relation','contact4_name','contact4_id_number','contact4_mobile','contact4_addr','contact4_com_name','contact4_com_addr','contact5_relation','contact5_name','contact5_id_number','contact5_mobile','contact5_addr','contact5_com_name','contact5_com_addr','ip_address','token_id','black_box','resp_detail_type'];
        $scenario = [
            $this->event_id => array_merge($loan_field, array_keys($this->defaulData)), //  web借款
        ];
        if (isset($scenario[$scenarioKey])){
            return $scenario[$scenarioKey];
        }else{
            return array_keys($this->defaulData);
        }
    }

    /**
     * 发送请求
     */
    public function sendRequest() {
        $Optional = $this->getOptional();
        $allRequestData = array_merge($Optional, $this->defaulData);

        if ($this->isFilter) {
            $allowSendKeys = $this->getScenarioSendKeys($this->event_id);
            foreach ($allRequestData as $key => $value) {
                if (!in_array($key, $allowSendKeys)) {
                    unset($allRequestData[$key]);
                }
            }
        }
        $result = $this->curlOpen(REQUESTURL, $allRequestData);
        //如果同盾返回非json格式数据或者没有返回则记录警报
        if(empty($result)){
            $alert_data=array(Model_Alert::TONGDUN_API_RETURN_ERROR,'同盾API非json格式数据或者没有返回','api/TONGDUN2/RiskService');
            return Model::factory('Alert')->create($alert_data);
        }
        if($result['success']) { //接口查询成功
            $tdlog_data = array('TongDun2','Search','调用TongDun接口：'.$result['final_decision'].'|'.$result['final_score'],json_encode($Optional),json_encode($result),'TongDunAPI',$this->user_id,time());
            $api_out = array('status'=>true,'msg'=>'发送成功','error'=>'0000','api_result'=>array('result'=>$result['success'],'data'=>$result,'msg'=>'调用同盾API接口成功'));
        }else{
            $tdlog_data = array('TongDun2','Search','调用TongDun接口返回失败',json_encode($Optional),json_encode($result),'TongDunAPI',$this->user_id,time());
            $api_out = array('status'=>true,'msg'=>'发送成功','error'=>'0000','api_result'=>array('result'=>$result['success'],'data'=>$result,'msg'=>'调用同盾API接口返回：'.$result['reason_code']));
        }
        $tdlog_map = array('provider', 'action', 'msg', 'req_data', 'resp_data', 'type','reference_id','create_time');
        DB::insert('api_out_log', $tdlog_map)->values($tdlog_data)->execute();
        return $api_out;
    }

    /**
     * 获取当前事件数据
     * 
     */
    public function getOptional() {
        return $this->optional;
    }

    /**
     * 设置事件数据
     * @param array $event_data 事件数据数组
     * @return type
     */
    public function setOptional(array $event_data = []) {
        $this->optional = [];
        $eventData = [
            //借款
            $this->event_id => ['account_name','id_number','account_mobile','loan_amount','loan_term','loan_purpose','apply_province','apply_city','apply_channel','event_occur_time','card_number','work_phone','home_phone','qq_number','account_email','diploma','marriage','house_property','house_type','registered_address','home_address','contact_address','career','applyer_type','work_time','organization_address','company_address','industry','company_type','occupation','annual_income','is_cross_loan','cross_loan_count','is_liability_loan','liability_loan_count','realname_consistence','contact1_relation','contact1_name','contact1_id_number','contact1_mobile','contact1_addr','contact1_com_name','contact1_com_addr','contact2_relation','contact2_name','contact2_id_number','contact2_mobile','contact2_addr','contact2_com_name','contact2_com_addr','contact3_relation','contact3_name','contact3_id_number','contact3_mobile','contact3_addr','contact3_com_name','contact3_com_addr','contact4_relation','contact4_name','contact4_id_number','contact4_mobile','contact4_addr','contact4_com_name','contact4_com_addr','contact5_relation','contact5_name','contact5_id_number','contact5_mobile','contact5_addr','contact5_com_name','contact5_com_addr','ip_address','token_id','black_box','resp_detail_type'],
        ];
        if (isset($eventData[$this->event_id])) {
            $optional = $eventData[$this->event_id];
            foreach ($optional as $key => $value) {
                if (isset($event_data[$value])) {
                    $this->optional[$value] = $event_data[$value];
                } else {
                    $this->optional[$value] = '';
                }
            }
        }
        return $this->optional;
    }

    public function get_client_ip($type = 0) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    /**
     * 获得IP[必填]
     */
    public function getIp() {
        return $this->get_client_ip();
    }

    /**
     * 
     * @param type $api_url
     * @param array $params
     * @return type
     */
    function curlOpen($api_url, array $params) {
        $options = array(
            CURLOPT_POST => 1, // 请求方式为POST
            CURLOPT_URL => $api_url, // 请求URL
            CURLOPT_RETURNTRANSFER => 1, // 获取请求结果
            // -----------请确保启用以下两行配置------------
            CURLOPT_SSL_VERIFYPEER => 1, // 验证证书
            CURLOPT_SSL_VERIFYHOST => 2, // 验证主机名
            // -----------否则会存在被窃听的风险------------
            CURLOPT_POSTFIELDS => http_build_query($params) // 注入接口参数
        );
        if (defined(CURLOPT_TIMEOUT_MS)) {
            $options[CURLOPT_NOSIGNAL] = 1;
            $options[CURLOPT_TIMEOUT_MS] = 2000;  // 超时时间(ms)
        } else {
            $options[CURLOPT_TIMEOUT] = 2;  // 超时时间(s)
        }
        if (defined(CURLOPT_CONNECTTIMEOUT_MS)) {
            $options[CURLOPT_CONNECTTIMEOUT_MS] = 2000;  // 连接超时时间(ms)
        } else {
            $options[CURLOPT_CONNECTTIMEOUT] = 2;  // 连接超时时间(s)
        }
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

}
