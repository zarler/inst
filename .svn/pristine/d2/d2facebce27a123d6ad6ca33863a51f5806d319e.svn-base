<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 17/3/14
 * Time: 下午8:05
 */
class Model_JunZiQian extends Model_Database{

    public function getInfo($order_id){
        $contract = DB::select("contract_id")->from("junziqian")->where('order_id','=',$order_id)->execute()->current();
        if($contract){
            return $contract;
        }
        return false;
    }
}