<?php
/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/11
 * Time: 下午2:37
 */

defined('SYSPATH') or die('No direct script access.');
class Model_Moxie_Queue extends Model
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

    public function add_data_queue($data){
        $table = 'moxie_queue';
        $_data = [
            'task_id'        => $data['task_id'],
            'user_id'          => $data['user_id'],
            'state'   => $data['state'],
            'action'    => $data['action'],
            'mobile'  => $data['mobile'],
            'extends'  => $data['extends'],
            'query_status'  => self::QUERY_WAIT,
            'create_time'       => time(),
        ];
        return $this->_add($table, $_data);
    }

    public function add_email_data_queue($data){
        $table = 'moxie_queue';
        $_data = [
            'task_id'        => $data['task_id'],
            'email_id'        => $data['email_id'],
            'user_id'          => $data['user_id'],
            'state'   => $data['state'],
            'extends'   => $data['extends'],
            'action'    => $data['action'],
            'mobile'  => $data['mobile'],
            'query_status'  => self::QUERY_WAIT,
            'create_time'       => time(),
        ];
        return $this->_add($table, $_data);
    }


    public function get_one($id=0,$action)
    {
        return DB::select()->from('moxie_queue')->where('extends','=',$id)->and_where('action','=',$action)->execute()->current();
    }


    public function get_total($array=[])
    {
        $query=DB::select(array(DB::expr('COUNT(*)'),'total'))->from('moxie_queue');
        if(count($array)>0) {
            $query = $this->query_builder($query,$array);
        }
        $rs=$query->execute()->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }

    //队列 启动占位(利用handle字段和一条更新语句实现多进程列)
    public function start($dateline=NULL,$handle=NULL,$size=30,$status=Model_Moxie_Common::QUERY_WAIT,$query_time=NULL,$query_times=NULL)
    {
        $array['query_time'] = time();
        $array['handle'] = $handle;
        if($handle===NULL){
            return FALSE;
        }
        $update = DB::update('moxie_queue')
            ->set($array)
            ->and_where('query_status','=',$status);

        $update->and_where('query_times','<',50);
        $update->and_where_open()
            ->and_where('handle','',DB::expr('is null'))
            ->or_where('handle','=','')
            ->and_where_close();
        return NULL !== $update->order_by('id','ASC')->limit(intval($size))->execute();

    }


    // 队列 完成启动后,获取占位的处理记录
    public function running($handle=NULL,$status=Model_Moxie_Common::QUERY_WAIT,$query_time=NULL,$query_times=NULL)
    {
        if($handle===NULL){
            return FALSE;
        }
        $query =  DB::select()->from('moxie_queue')->where('query_status','=',$status);
        if($handle!==NULL){
            $query->and_where('handle','=',$handle);
        }

        $query->and_where('query_times','<',50);

        $query = $query->and_where('query_time','<=',($query_time ? $query_time : time()))->execute();
        $rs = $query->as_array();

        if($rs){
            $upid = [];
            foreach ($rs as $r){
                $upid[] = $r['id'];
            }
            DB::update('moxie_queue')->set(['query_status'=>Model_Moxie_Common::QUERY_RUNNING,'query_time'=>time()])->where('query_status','=',$status)->and_where('id','IN',$upid)->execute();
            return $rs;
        }
        return FALSE;
    }


    // 间隔N秒后重置查询
    public function retry($time=600,$query_status=[Model_Moxie_SocialSecurity::QUERY_RUNNING])
    {
        DB::update('moxie_queue')
            ->set(array('query_status'=>Model_Moxie_Common::QUERY_WAIT,'handle'=>''))
            ->where('query_status','IN',$query_status)
            ->and_where('query_time','<=',time()-$time)
            ->execute();
    }

    // 超过N次自动关闭
    public function times_close($times=50,$query_status=[Model_Moxie_Common::QUERY_WAIT,Model_Moxie_Common::QUERY_RUNNING])
    {
        DB::update('moxie_queue')
            ->set(array('query_status'=>Model_Moxie_Common::QUERY_CLOSED))
            ->where('query_status','IN',(is_array($query_status) ? $query_status : [$query_status]))
            ->and_where('query_times','>=',$times)
            ->execute();

    }

    //更改
    public function update($id=0,$array=NULL) {
        if(!$array){
            return FALSE;
        }
        unset($array['id']);
        $affected_rows = DB::update('moxie_queue')->set($array)->where('id','=',$id)->execute();
        return $affected_rows!==NULL;
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

}