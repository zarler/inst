<?php
defined('SYSPATH') or die('No direct script access.');
/**
 *
 *  直扣 放款接口
 * Created by PhpStorm.
 * User: wangxuesong
 * Date: 17/8/28
 * Time: 上午11:21
 *
 * 20171127  测试与正式配置隔离与自动适配
 */
class  Lib_Payment{


       //定义
        protected static $_allow_provider = [
            Model_Payment_Provider::CITICBANK =>'CITIC', //中信支付
            Model_Payment_Provider::UNSPAY    =>'UNS',   //银生宝
            Model_Payment_Provider::UCFPAY_PUFUBAO =>'UCFPay_PuFuBao', //联动优势(9斗鱼)
        ];

        //

        //定义返回code
        const CODE_WAIT = 100;     //查询中
        const CODE_SUCCESS = 200;  // 付款成功
        const CODE_FAILED = 300;   //  付款失败
        const CODE_WARNING = 400;  //返回异常

        //请求接口
        private static $_payment;
        private static $_order;
        private static $_user;
        private static $_provider;
        protected static $_env;



        public function __construct(){

            //定义引用
            self::$_env = Kohana::$config->load('env');
            
            self::$_payment = Model::factory('Finance_Payment');
            self::$_order = Model::factory('Order');
            self::$_user = Model::factory('User');
            self::$_provider = Model::factory('Finance_Payment')->provider_array();
        }


    /***
     * 查询中信或银生宝记录是否全不全则补记录
     * @param $tc_no
     * @return array
     */
    public  function query($tc_no){


        //获取付款记录表信息
        $finance_payment  = Model::factory('Finance_Payment')->getOneByTcNo($tc_no);

        if(empty($finance_payment)){

            return ['status'=>false,'msg'=>'放款记录不存在','code'=>self::CODE_WARNING];

        }

        $tc_no    = isset($finance_payment['tc_no'])?$finance_payment['tc_no']:"";
        $provider = isset($finance_payment['provider'])?$finance_payment['provider']:'';


        if(empty($provider)){
            return ['status'=>false,'msg'=>'支付通道不存在','code'=>self::CODE_WARNING];
        }


        if(!in_array($provider,self::$_provider)){

            return ['status'=>false,'msg'=>'支付类型错误','code'=>self::CODE_WARNING];
        }

        $payment_post_data=[
            'payment_no' => $finance_payment['payment_no'],
            'order_no' => $finance_payment['tc_no'],
        ];

         $result = TCAPI::factory()
            ->url(self::$_env['url']['api-pay'].'/'.$provider.'/Query')
            ->post($payment_post_data)
            ->execute()
            ->as_array();

        if (isset($result['api_result']['result']) ) {

                self::$_payment->update(['tc_no' => $tc_no],['status' => Model_Finance_Payment::SUCCESS]);
                self::payment_success_after($order_id);

                return ['status'=>true,'msg'=>'放款成功','code'=>self::CODE_SUCCESS];
        }else{
                
                self::$_payment->update(['tc_no' => $tc_no],['status' => Model_Finance_Payment::FAILED]);
                self::payment_failed_after($order_id);

                return ['status'=>false,'msg'=>'放款失败','code'=>self::CODE_FAILED];
        }

    }
  
    /***
      *放款成功成后,订单数据维护
      * @param $order_id
      * @return bool
      *
      */
    public static function payment_success_after($order_id) {
        return DB::update('order')
            ->set(['status'=>Model_Order::STATUS_PAYMENT_SUCCESS])
            ->where('id', '=', intval($order_id))
            ->and_where('status', '=', Model_Order::STATUS_PAYMENT_RUNNING)
            ->execute();
    }
  
    
    /***
      *放款失败成后,订单数据维护
      * @param $order_id
      * @return bool
      *
      */
    public static function payment_failed_after($order_id) {
    
        return DB::update('order')
            ->set(['status'=>Model_Order::STATUS_PAYMENT_FAILED])
            ->where('id', '=', intval($order_id))
            ->and_where('status', '=', Model_Order::STATUS_PAYMENT_RUNNING)
            ->execute();
    }


    /**
     * 放款成功后发送短信
     * @param $order_id
     * @param $refund_amount
     * @return bool
     */
    public static function success_send_sms($order_id,$payment_amount){
        if(bccomp($payment_amount,0,2)<=0){
            return FALSE;
        }
        if($order = self::$_order->get_one($order_id)) {
            return Lib::factory('Pufubao_SMS')->execute($order['mobile'],Model_Pufubao_SMS::TPL_PAYMENT_SUCCESS,[date('Y-m-d'),$payment_amount,substr($order['bankcard_no'],-4)]);
        }
        return FALSE;
    }
 

}