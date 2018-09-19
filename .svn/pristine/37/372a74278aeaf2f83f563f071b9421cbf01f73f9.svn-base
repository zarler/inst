<?php
/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/11
 * Time: 下午2:36
 */

defined('SYSPATH') or die('No direct script access.');
class Model_Moxie_DataItem extends Model
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

    public function get_data_item($userid)
    {

        $search = DB::select('extends')
            ->from('moxie_data_item');
        if(isset($userid)){
            $search->where('userid', '=', $userid);
        }

        $data = $search->execute()
            ->current();

        return $data;
    }

    public function get_data_item_task($task_id)
    {

        $search = DB::select('extends')
            ->from('moxie_data_item');
        if(isset($task_id)){
            $search->where('task_id', '=', $task_id);
        }

        $data = $search->execute()
            ->current();

        return $data;
    }

    public function add_data_item($data)
    {
        $table = 'moxie_data_item';
        $_data = [
            'extends'   => isset($data['extends']) ? $data['extends'] : '',
            'userid' => isset($data['userid']) ? $data['userid'] : '',
            'task_id' => isset($data['task_id']) ? $data['task_id'] : '',
            'state' => isset($data['state']) ? $data['state'] : '',
            'create_time'   => time(),
        ];

        return $this->_add($table, $_data);
    }


    // 构造查询条件
    private function query_builder($query, $array=array())
    {

        if(isset($array['time_start']) && $array['time_start']>0) {
            $query->and_where('create_time','>=',$array['time_start']);
        }
        if(isset($array['time_end']) && $array['time_end']>0) {
            $query->and_where('create_time','<=',$array['time_end']);
        }

        if(isset($array['handle']) && $array['handle'] ) {
            $query->and_where('handle','=',$array['handle']);
        }

        if(isset($array['query_status']) && $array['query_status'] ) {
            $query->and_where('query_status','IN', is_array($array['query_status'])? $array['query_status'] : array($array['query_status']) );
        }
        if(isset($array['query_time']) && $array['query_time'] ) {
            $query->and_where('query_time','<=',$array['query_time']);
        }

        return $query;
    }

    public function get_userId(){

    }
}