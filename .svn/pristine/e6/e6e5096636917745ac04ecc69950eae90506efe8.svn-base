<?php
/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2017/12/20
 * Time: 上午10:30
 *
 * 魔蝎 公共部分model
 */

defined('SYSPATH') or die('No direct script access.');
class Model_Moxie_Common extends Model
{

    //query 等待查询
    const QUERY_NONE = 0;   //无
    const QUERY_WAIT = 1;   //等待查询
    const QUERY_RUNNING = 2;   //查询中
    const QUERY_SUCCESS = 3;   //查询成功
    const QUERY_FAILED = 4;    //查询失败
    const QUERY_CLOSED = 5;    //查询关闭(提交时直接扣款成功,超过次数上线,订单状态非扣款中,或者手工关闭)


    public function add_api_log($data){
        $table = 'api_out_log';
        $_data = [
            'provider'    => $data['provider'],
            'action'         => $data['action'],
            'req_data'  => $data['req_data'],
            'type' => $data['type'],
            'reference_id' => $data['reference_id'],
            'create_time'   => time(),
        ];
        return $this->_add($table, $_data);
    }

    //api_log修改
    public function api_log_update($id=0,$array=NULL){
        if(!$array){
            return FALSE;
        }
        unset($array['id']);
        $affected_rows = DB::update('api_out_log')->set($array)->where('id','=',$id)->execute();
        return $affected_rows!==NULL;
    }

}
