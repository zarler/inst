<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 2017/4/12
 * Time: 下午5:19
 */
require_once ("config.php");
require_once ("lib/llpay_apipost_submit.class.php");
require_once ("lib/llpay_rsa.function.php");
require_once ("lib/llpay_core.function.php");


class Lib_CardInfo_API_LianLianPay_API {

    private $_request;

    private $_request_url;

    private $_response;

    private $_config;

    private $_LianLianPay;

    public function __construct(){
        $this->_config = Kohana::$config->load('api')->get('LianLianPay');
        $this->_LianLianPay = new LLpaySubmit();
        $this->_request['version'] = '1.0';                                 //版本号
        $this->_request['oid_partner'] = LIANLIANPAY_MERCHANT_ID;           //商户编号
        $this->_request['app_request'] = '3';                               //请求应用标识 3-WAP
        $this->_request['sign_type'] = 'RSA';                               //签名方式
        $this->_request['sign'] = '';                                       //签名方式
    }

    /**
     * 卡信息查询
     * @param $data
     * @return $this
     */
    public function card_info($data){
        $this->_request['card_no'] = $data['card_no'];
        $this->_request_url = LIANLIANPAY_BANKCARDBIN_URL;
        return $this;
    }

    /**
     * 签约
     * @param $data
     * @return $this
     */
    public function sign($data){
        $this->_request['user_id'] = $data['user_id'];
        $this->_request['id_type'] = '0';                                   //证件类型0身份证
        $this->_request['id_no'] = $data['identity_code'];                  //证件号码
        $this->_request['acct_name'] = $data['holder'];                     //持卡人姓名
        $this->_request['card_no'] = $data['card_no'];                      //银行卡号
        $this->_request['pay_type'] = 'I';                                  //签约方式
        $this->_request['risk_item'] = $this->_format_risk($data);          //风控标识
        $this->_request['url_return'] = $this->_config['return_url'];       //通知地址
        $this->_request_url = LIANLIANPAY_SIGN_URL;
        return $this;
    }

    /**
     * 构造自动提交表单
     * @return string
     */
    function create_html() {
        $tmp = $this->_LianLianPay->buildRequestPara($this->_request);
        $html = <<<eot
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body  onload="javascript:document.pay_form.submit();">
    <form id="pay_form" name="pay_form" action="{$this->_request_url}" method="post">
	
eot;
        $html .= '    <input type="hidden" name="req_data" id="req_data" value=\''.json_encode($tmp).'\'/>';
        $html .= <<<eot
        
        <!-- <input type="submit" type="hidden">-->
    </form>
</body>
</html>
eot;
        return $html;
    }

    /**
     * 还款计划
     * @param $data
     * @return $this
     */
    public function plan($data){
        $this->_request['user_id'] = $data['user_id'];
        $this->_request['api_version'] = '1.0';                                                     //版本号
        $this->_request['repayment_plan'] = $this->_format_repayment_plan($data);                   //还款计划
        $this->_request['repayment_no'] = $data['repayment_no'];                                                      //还款计划编号
        $this->_request['sms_param'] = $this->_format_sms_param();                                  //短信参数
        $this->_request['pay_type'] = 'D';                                                          //支付方式
        $this->_request['no_agree'] = $data['sign_no'];                                             //签约协议号
        $this->_request_url = LIANLIANPAY_AGREEN_PLAN_URL;
        return $this;
    }

    /**
     * 修改还款计划
     * @param $data
     * @return $this
     */
    public function change_plan($data){
        $this->_request['user_id'] = $data['user_id'];
        $this->_request['repayment_plan'] = $this->_format_repayment_plan($data);                   //还款计划
        $this->_request['repayment_no'] = $data['repayment_no'];                                    //还款计划编号
        $this->_request['sms_param'] = $this->_format_sms_param();                                  //短信参数
        $this->_request_url = LIANLIANPAY_CHANGE_PLAN_URL;
        return $this;
    }

    /**
     * 还款计划
     * @param $data
     * @return $this
     */
    public function deduct($data){
        $this->_request['user_id'] = $data['user_id'];
        $this->_request['busi_partner'] = '101001';                                                 //商户业务类型
        $this->_request['api_version'] = '1.0';                                                     //版本号
        $this->_request['no_order'] = $data['out_order_no'];                                        //外部订单号
        $this->_request['money_order'] = $data['amount'];                                           //还款金额
        $this->_request['dt_order'] = substr($data['out_order_no'],2,14);                           //date('Ymdhis');//外部订单号
        $this->_request['notify_url'] = $this->_config['notify_url'];                               //回调通知地址
        $this->_request['name_goods'] = $this->_config['trade_desc'];                               //外部订单号
        $this->_request['repayment_no'] = $data['repayment_no'];                                    //还款计划编号
        $this->_request['risk_item'] = $this->_format_risk($data);                                  //风控标识
        $this->_request['schedule_repayment_date'] = $data['repayment_date'];                       //计划还款日期
        $this->_request['pay_type'] = 'D';                                                          //支付方式
        $this->_request['no_agree'] = $data['sign_no'];                                             //签约协议号

        $this->_request_url = LIANLIANPAY_DEDUCT_URL;
        return $this;
    }

    /**
     * 交易查询
     * @param $data
     * @return $this
     */
    public function query($data){
        $this->_request['no_order'] = $data['out_order_no'];                                        //外部订单号
        $this->_request['dt_order'] = substr($data['out_order_no'],2,14);                           //订单发送时间
        $this->_request['query_version'] = '1.1';                                                   //查询版本号
        $this->_request_url = LIANLIANPAY_QUERY_URL;
        return $this;
    }

    /**
     * 风控审核信息
     * @param $data
     * @return $this
     */
    private function _format_risk($data){
        $risk_item = [
            'frms_ware_category' => '2010',
            'user_info_mercht_userno' => $data['user_id'],
            'user_info_bind_phone' => $data['mobile'],
            'user_info_dt_register' => date('Ymdhis',$data['register_time']),
            'user_info_full_name' => $data['holder'],
            'user_info_id_type' => '0',
            'user_info_id_no' => $data['identity_code'],
            'user_info_identify_state' => '1',
            'user_info_identify_type' => '4'
        ];
        return json_encode($risk_item);
    }

    /**
     * 风控审核信息
     * @param $data
     * @return $this
     */
    private function _format_repayment_plan($data){
        $repayment_plan = [
            'repayment_plan' => ['date'=>$data['repayment_date'],'amount'=>$data['amount']]
        ];
        return json_encode($repayment_plan);
    }

    /**
     * 短信参数
     */
    private function _format_sms_param(){
        $sms_param = ['contract_type'=>$this->_config['company_name'],'contact_way'=>$this->_config['company_contact_way']];
        return json_encode($sms_param);
    }

    /**
     * 发送请求到接口
     */
    public function postData(){
        $html_text = $this->_LianLianPay->buildRequestJSON($this->_request,$this->_request_url);
        return $html_text;
    }

    /**
     * 获取提交参数
     * @return mixed
     */
    public function get_request(){
        return $this->_request;
    }

    /**
     * 绑卡列表
     * @param $data
     * @return $this
     */
    public function query_bankcard_list($data){
        $this->_request['user_id'] = $data['user_id'];
        $this->_request['pay_type'] = 'D';                                                        //支付方式
        $this->_request['offset'] = '0';                                                          //0 代表从 0 条开始查，若不分页查询就传 0
        $this->_request_url = LIANLIANPAY_BINGDLIST_URL;
        return $this;
    }

    /**
     * 验证远端返回签名
     * @param $param
     * @return boolean
     */
    public function verify_sign($param,$sign){
        //除去待签名参数数组中的空值和签名参数
        $param_filter = paraFilter($param);
        //对待签名参数数组排序
        $param_sort = argSort($param_filter);
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $param_str = createLinkstring($param_sort);
        return Rsaverify($param_str,$sign);
    }
}