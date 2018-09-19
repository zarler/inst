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
    public function make_no(){
        return strtoupper('tcno'.date('YmdHis').Text::random('alnum',8));
    }


    public function get_one($id){
        return DB::select()->from("finance_deduct")->where("id","=",$id)->execute()->current();
    }

    public function get_one_by_deduct_no($deduct_no){
        return DB::select()->from("finance_deduct")->where("deduct_no","=",$deduct_no)->execute()->current();
    }

    public function get_one_by_tc_no($tc_no){
        return DB::select()->from("finance_deduct")->where("tc_no","=",$tc_no)->execute()->current();
    }


    public function update_by_id($id,$array){
        return NULL !== DB::update('finance_deduct')->set($array)->where('id','=',$id)->execute();
    }


    /**
     * 添加记录
     * @param $table
     * @param $data
     * @return mixed
     */
    public function add_provider($table, $data) {

        $_data = array_keys($data);
        $_value = array_values($data);

        list($insert_id, $affected_rows) = DB::insert($table, $_data)
            ->values($_value)
            ->execute();

        return $insert_id;
    }


    // 扣款主表添加记录
    public function add_main($data) {
        $table = 'finance_deduct';
        return $this->add_provider($table, $data);
    }


    //子表 普付宝一码付-支付宝
    public function add_pufubao_alipay($data) {
        $table = 'finance_deduct_pufubao_alipay';
        return $this->add_provider($table, $data);
    }


    //子表 普付宝一码付-微信支付
    public function add_pufubao_wechatpay($data) {
        $table = 'finance_deduct_pufubao_wechatpay';
        return $this->add_provider($table, $data);
    }




}
