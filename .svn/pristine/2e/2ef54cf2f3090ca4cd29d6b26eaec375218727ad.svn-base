<?php
defined('SYSPATH') or die('No direct script access.');
/**
 *
 *
 */

class Model_Finance_Payment extends Model_Database {

    const WAIT = 100;     //等待放款
    const SUCCESS = 200;  //成功放款
    const FAILED = 300;  //放款失败
    const WARNING = 400; //警告


    public $provider_array = array(
        'UNSPay' => '银生宝代付',
        'UCFPay' => '先锋支付',
    );

    public $status_array = array(
        self::WAIT => '待付款',
        self::SUCCESS => '付款成功',
        self::FAILED => '付款失败',
    );


    //构造查询条件
    private function queryBuilder($query, $array=array()) {
        if(isset($array['status'])) {
            $query->and_where('finance_payment.status', '=', $array['status']);
        }
        if(isset($array['order_id']) && $array['order_id'] ) {
            $query->and_where('finance_payment.order_id','=',trim($array['order_id']));
        }
        if(isset($array['order_no']) && $array['order_no'] ) {
            $query->and_where('order.order_no','=',trim($array['order_no']));
        }
        if(isset($array['mobile']) && $array['mobile']) {
            $query->and_where('finance_payment.mobile','=',$array['mobile']);
        }

        if(isset($array['holder']) && $array['holder'] ){
            $query->and_where('finance_payment.holder','=',$array['holder']);
        }

        if(isset($array['provider']) && $array['provider'] ){
            $query->and_where('finance_payment.provider','=',$array['provider']);
        }

        if(isset($array['identity_code']) && $array['identity_code'] ){
            $query->and_where('user.identity_code','=',$array['identity_code']);
        }
        if(isset($array['datetime'])){
            $query->and_where('finance_payment.create_time', 'BETWEEN', array($array['datetime'], $array['datetime']+86399));
        }
        if(isset($array['time_start'])&& $array['time_start']>0) {
            $query->and_where('finance_payment.create_time','>=',strtotime($array['time_start'].' 00:00:00'));
        }

        if(isset($array['time_end'])&& $array['time_end']>0) {
            $query->and_where('finance_payment.create_time','<=',strtotime($array['time_end'].' 23:59:59'));
        }
        return $query;
    }


    
    public function getOne($payment_id){
        return DB::select()->from("finance_payment")->where("id","=",$payment_id)->execute()->current();
    }


    //根据order_id获取
    public function getOneByOrderId($order_id=0){
        return DB::select()->from('finance_payment')->where('finance_payment.order_id','=',$order_id)->execute()->as_array();
    }


    public function getOneByPaymentNo($payment_no){
        return DB::select()->from("finance_payment")->where("payment_no","=",$payment_no)->execute()->current();
    }

    public function getOneByTcNo($tc_no){
        return DB::select()->from("finance_payment")->where("tc_no","=",$tc_no)->execute()->current();
    }


    public function getPaymentByPaymentId($provider='',$payment_id=0){

        if(in_array($provider,$this->provider_array)){

            return FALSE;
        }

        return DB::select('out_order_no')->from('finance_payment_'. strtolower($provider))->where('payment_id','=',$payment_id)->execute()->current();

    }
 
    //查询分页
    public function getList($array=array(),$perpage=20,$page=1) {

         $query=DB::select('finance_payment.*','order.order_no')->from('finance_payment');

        $query->from('finance_payment');

        $query->join('order')->on('finance_payment.order_id','=','order.id');

        if(count($array)>0) {
            $query = $this->queryBuilder($query,$array);
        }
        if($page<1) {
            $page=1;
        } 
 
        $rs = $query->offset($perpage*($page-1))->limit($perpage)->execute()->as_array();



        //获取子表记录
        if($rs){
            foreach ($rs as $key => $val) {
          
                $payment_data = $this->getPaymentByPaymentId($val['provider'], $val['id']);
                $rs[$key]['out_order_no'] = isset($payment_data['out_order_no']) ? $payment_data['out_order_no'] : ''; 
                $rs[$key]['msg'] = isset($payment_data['msg']) ? $payment_data['msg'] : ''; 
            }
        }
       
        return $rs;
    }





    /**
     * 更新
     * @param $where_arr
     * @param $update_arr
     */
    public function update ($where_arr, $update_arr) {
        $upd = DB::update('finance_payment')->set($update_arr);
        foreach($where_arr as $k => $v){
            $upd->where($k,"=",$v);
        }

        return $upd->execute();
    }

  


    // 放款主表添加记录
    public function addMain($data) {
        $table = 'finance_payment';
        return $this->add_provider($table, $data);
    }

    //子表 手工放款
    public function addHand($data) {
        $table = 'finance_payment_hand';
        return $this->add_provider($table, $data);
    }
 

    //子表 银生宝
    public function addUNSPay($data) {
        $table = 'finance_payment_unspay';
        return $this->add_provider($table, $data);
    }
 

    //子表 先锋支付
    public function addUCFPay($data) {
        $table = 'finance_payment_ucfpay';
        return $this->add_provider($table, $data);
    }



    public function updateMain( $search_type='id', $search_value, $data){
        $table = 'finance_payment';
        return $this->update_provider($table, $search_type, $search_value, $data);
    }

    //子表 手工放款
    public function updateHand( $search_type='payment_id', $search_value, $data) {
        $table = 'finance_payment_hand';
        return $this->update_provider($table, $search_type, $search_value, $data);
    }
 

    //子表 银生宝
    public function updateUNSPay($search_type='payment_id', $search_value, $data) {
        $table = 'finance_payment_unspay';
        return $this->update_provider($table,  $search_type, $search_value,  $data);
    }

     

    //子表 先锋支付
    public function updateUCFPay( $search_type='payment_id', $search_value, $data) {
        $table = 'finance_payment_ucfpay';
        return $this->update_provider($table, $search_type, $search_value, $data);
    }



    /**
     * 添加记录
     * @param $table
     * @param $data
     * @return mixed
     */
     private function addProvider($table, $data) {

        $_data = array_keys($data);
        $_value = array_values($data);

        list($insert_id, $affected_rows) = DB::insert($table, $_data)
            ->values($_value)
            ->execute();

        return $insert_id;
    }




    /** 更新放款表
     * @param $table
     * @param $id
     * @param $data
     * @return bool
     */
    private function updateProvider($table, $search_type='payment_no', $search_value, $data){
        if(!in_array($search_type,['tc_no','payment_no','id','payment_id'])){
            return FALSE;
        }
        return NULL !== DB::update($table)->set($data)->where($search_type,'=',$search_value)->order_by('id','DESC')->limit(1)->execute();
    }


    /**
     * 生成TCNO
     * @return string
     */
    public function makeNo(){
        return strtoupper('tcno'.date('YmdHis').Text::random('alnum',8));
    }



}
