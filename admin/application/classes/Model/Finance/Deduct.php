<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/6/21
 * Time: 下午11:28
 *
 *
 */
class Model_Finance_Deduct extends Model_Database{

    const API_RESULT_SUBMITTED = '100';     //已提交 待扣款
    const API_RESULT_SUCCESS = '200';  //成功还款
    const API_RESULT_FAILED = '300';  //还款失败
    const API_RESULT_WAIT_QUERY = '500';   //需要等待

    const STATUS_SUBMITTED  = '100';  // 已提交 待扣款
    const STATUS_SUCCESS    = '200';  // 成功还款
    const STATUS_FAILED     = '300';  // 还款失败
    const STATUS_WAIT_QUERY = '500';  // 需要等待

    const API_ERROR_NO_WAY = '7000';//没有扣款通道
    const API_ERROR_AMOUNT = '8000';//金额错误(扣款超过订单应还金额)

    const DEDUCT_TYPE_DEFAULT = 1; //正常扣款
    const DEDUCT_TYPE_BULL = 2; //逾期扫扣

    public $status_array = array(
        self::API_RESULT_SUBMITTED => '待扣款',
        self::API_RESULT_SUCCESS => '扣款成功',
        self::API_RESULT_FAILED => '扣款失败',
        self::API_RESULT_WAIT_QUERY => '需等待',
    );


    /**
     * 生成TCNO
     * @return string
     */
    public function makeNo(){
        return strtoupper('tcno'.date('YmdHis').Text::random('alnum',8));
    }


    // 获取扣款主表全部记录
    public function getAll($order_id,$deduct_type=NULL){
        if($deduct_type!==NULL){
            if(is_array($deduct_type) && in_array(2,$deduct_type)){

                return DB::select()->from('finance_deduct')->where('order_id', '=', $order_id)
                    ->and_where_open()
                    ->or_where_open()->where("deduct_type", "=", self::DEDUCT_TYPE_DEFAULT)->or_where_close()
                    ->or_where_open()->where("deduct_type", "=", self::DEDUCT_TYPE_BULL)->and_where("status","!=",self::API_RESULT_FAILED)->or_where_close()
                    ->and_where_close()
                    ->order_by("id","DESC")
                    ->execute()->as_array();

            }else{
                return DB::select()->from('finance_deduct')->where('order_id', '=', $order_id)->and_where('deduct_type','IN',(is_array($deduct_type) ? $deduct_type:[$deduct_type]) ) ->order_by("id","DESC")->execute()->as_array();
            }
        }
        return DB::select()->from('finance_deduct')->where('order_id', '=', $order_id) ->order_by("id","DESC")->execute()->as_array();
    }



    public function getOne($id){
        return DB::select()->from("finance_deduct")->where("id","=",$id)->execute()->current();
    }

    public function getOneByDeductNo($deduct_no){
        return DB::select()->from("finance_deduct")->where("deduct_no","=",$deduct_no)->execute()->current();
    }

    public function getOneByTcNo($tc_no){
        return DB::select()->from("finance_deduct")->where("tc_no","=",$tc_no)->execute()->current();
    }

    public  function getOneByOrderQuery($order_id){
        return DB::select()->from("finance_deduct")->where("order_id","=",$order_id)->where('status',"=",self::STATUS_SUBMITTED)->execute()->current();
    }

    public function updateById($id,$array){
        return NULL !== DB::update('finance_deduct')->set($array)->where('id','=',$id)->execute();
    }

    /**
     * 更新
     * @param $where_arr
     * @param $update_arr
     * @return bool
     */
    public function update ($where_arr, $update_arr) {
        $upd = DB::update('finance_deduct')->set($update_arr);
        foreach($where_arr as $k => $v){
            $upd->where($k,"=",$v);
        }
        return $upd->execute();
    }

    public function editMainDeductStatus($id, $data) {
        return DB::update('finance_deduct')->set(['status' => $data['status']])->where('id', '=', $id)->execute();
    }

    /**
     * 更新deduct 子表
     * @param $provider
     * @param $deduct_id
     * @param $update_arr
     * @return object
     */
    public function updateProviderDeduct ($provider,$deduct_id, $update_arr) {

        $upd = DB::update('finance_deduct_'.strtolower($provider))->set($update_arr);
        $upd->where('deduct_id', '=',$deduct_id);

        return $upd->execute();
    }

    /**
     * 添加记录
     * @param $table
     * @param $data
     * @return mixed
     */
    public function addProvider($table, $data) {
        $_data = array_keys($data);
        $_value = array_values($data);

        list($insert_id, $affected_rows) = DB::insert($table, $_data)
            ->values($_value)
            ->execute();
        return $insert_id;
    }

    // 扣款主表添加记录
    public function addMain($data) {
        $table = 'finance_deduct';
        return $this->addProvider($table, $data);
    }

    //子表 手工入账
    public function addTCF($data) {
        $table = 'finance_deduct_tcf';
        return $this->addProvider($table, $data);
    }

    //子表 联动优势
    public function addUMPay($data) {
        $table = 'finance_deduct_umpay';
        return $this->addProvider($table, $data);
    }

    //子表 翼支付
    public function addBestPay($data) {
        $table = 'finance_deduct_bestpay';
        return $this->addProvider($table, $data);
    }

    //子表 银生宝
    public function addUNSPay($data) {
        $table = 'finance_deduct_unspay';
        return $this->addProvider($table, $data);
    }

    //子表 宝付
    public function addBaoFoo($data) {
        $table = 'finance_deduct_baofoo';
        return $this->addProvider($table, $data);
    }

    //子表 融宝
    public function addReapal($data) {
        $table = 'finance_deduct_reapal';
        return $this->addProvider($table, $data);
    }

    //子表 苏宁易付宝
    public function addSuNingPay($data) {
        $table = 'finance_deduct_suningpay';
        return $this->addProvider($table, $data);
    }

    //子表 连连支付
    public function addLianLianPay($data) {
        $table = 'finance_deduct_lianlianpay';
        return $this->addProvider($table, $data);
    }

    //子表 普付宝一码付-支付宝
    public function addPuFuBaoALIPay($data) {
        $table = 'finance_deduct_pufubao_alipay';
        return $this->addProvider($table, $data);
    }

    //子表 普付宝一码付-微信支付
    public function addPuFuBaoWechatPay($data) {
        $table = 'finance_deduct_pufubao_wechatpay';
        return $this->addProvider($table, $data);
    }
}
