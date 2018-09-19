<?php
/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/26
 * Time: 下午3:46
 */
class Model_BaiRong_Data extends Model
{

    //query 等待查询
    const QUERY_NONE = 0;   //无
    const QUERY_WAIT = 1;   //等待查询
    const QUERY_RUNNING = 2;   //查询中
    const QUERY_SUCCESS = 3;   //查询成功
    const QUERY_FAILED = 4;    //查询失败
    const QUERY_CLOSED = 5;    //查询关闭(提交时直接扣款成功,超过次数上线,订单状态非扣款中,或者手工关闭)

    private function _add($table, $data)
    {
        $_keys = array_keys($data);
        $_vals = array_values($data);

        $insert = DB::insert($table)
            ->columns($_keys)
            ->values($_vals);

        list($insert_id, $affected_rows) = $insert->execute();

        return $insert_id;
    }

    public function add_data($data)
    {
        $table = 'moxie_data';
        $_data = [
            'req_data' => $data['req_data'],
            'provider' => $data['provider'],
            'type' => $data['type'],
            'state' => $data['state'],
            'userid' => $data['userid'],
            'action' => $data['action'],
            'create_time' => time(),
        ];

        return $this->_add($table, $_data);
    }

    public function add_resp_data($data)
    {
        $table = 'moxie_data';
        $_data = [
            'resp_data' => $data['resp_data'],
            'provider' => $data['provider'],
            'type' => $data['type'],
            'state' => $data['state'],
            'userid' => $data['userid'],
            'action' => $data['action'],
            'create_time' => time(),
        ];

        return $this->_add($table, $_data);
    }

    public function get_data($user_id){

        if($user_id<1){
            return false;
        }
        return DB::select()->from('bairong_data')->where('user_id','=',$user_id)->execute()->current();
    }
}
