<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 18/1/12
 * Time: 上午11:59
 *
 * 订单更新日志Model
 */
class Model_Order_ModifyLog extends Model_Database{

    /** 记录日志
     * @param int $order_id
     * @param array $array
     * @return bool
     */
    public function log($order_id = 0,$array = []){
        try {
            return null !== DB::insert('order_modify_log',['order_id','method','data','location','client_ip','create_time'])
                ->values([
                    $order_id ? intval($order_id) : 0,
                    isset($array['method']) ? $array['method'] : '',
                    isset($array['data']) ? $array['data'] : '',
                    isset($array['location']) ? $array['location'] : (isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '' )  ,
                    isset($array['client_ip']) ? $array['client_ip'] : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''),
                    time(),
                ])->execute();

        } catch (Exception $e) {
            return false;

        }
    }




}