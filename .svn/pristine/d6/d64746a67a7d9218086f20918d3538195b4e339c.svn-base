<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/1/11
 * Time: 下午1:19
 *
 * 短信队列数据MODEL
 *
 */
class Model_SMS_Queue extends Model_Database {

    const SUCCESS = 1;   //完成
    const FAILED = 2;   //失败
    const READY = 3;    //等待执行
    const RUNNING = 4;  //执行中

    public $status_array =array(
        self::SUCCESS=>'发送成功',
        self::FAILED=>'发送失败',
        self::RUNNING=>'进行中',
        self::READY=>'待发送',
    );


    public function get_one($id=0) {
        return DB::select()->from('sms_queue')->where('id','=',$id)->execute()->current();
    }


    //插入
    public function create($array=array()){
        if(isset($array['user_id']) && isset($array['service_name']) && isset($array['mobile']) ){
            if(!isset($array['data'])){
                $array['data']=array();
            }
            $array['data']['mobile']=$array['mobile'];
            $create_array = array(
                'user_id'=>  intval($array['user_id']) ,
                'service_name'=> $array['service_name'],
                'mobile'=> $array['mobile'],
                'status'=> self::READY,
                'request_data'=> json_encode($array['data']),
                'create_time'=> time(),
                'times'=>0,
            );

            list($insert_id,$affected_rows)=DB::insert("sms_queue",array('user_id','service_name','mobile','status','request_data','create_time','times'))
                ->values($create_array)
                ->execute();
            return $insert_id;
        }
        return FALSE;
    }


    //更改
    public function update($id=0,$array=NULL) {
        if(!$array){
            return FALSE;
        }
        unset($array['id']);
        $affected_rows = DB::update('sms_queue')->set($array)->where('id','=',intval($id))->execute();
        return $affected_rows!==NULL;
    }


    //删除
    public function delete($id,$array=array()) {
        if(is_array($id)){
            return DB::delete('sms_queue')->where('id','IN',$id)->execute();
        }
        return DB::delete('sms_queue')->where('id','=',intval($id))->execute();
    }


    //队列 启动占位(利用handle字段和一条更新语句实现多进程列)
    public function queue_start_handle($handle=NULL,$size=30,$status=Model_SMS_Queue::READY) {
        $array = array('status'=>Model_SMS_Queue::RUNNING);
        if($handle!==NULL){
            $array['handle']= $handle;
        }
        return NULL !== DB::update('sms_queue')->set($array)->where('status','=',$status)->order_by('id','ASC')->limit(intval($size))->execute();
    }

    //队列 完成启动后,获取占位的处理记录
    public function queue_get_running($handle=NULL,$status=Model_SMS_Queue::RUNNING) {
        $query = DB::select()->from('sms_queue')->where('status','=',$status);
        $array = array('status'=>Model_SMS_Queue::RUNNING);
        if($handle!==NULL){
            $query->and_where('handle','=',$handle);
        }
        return $query->execute()->as_array();
    }

    //队列处理返回 更新状态
    public function queue_back_update($id,$new_status,$response_data) {
        $affected_rows = DB::update('sms_queue')->set(array('status'=>$new_status,'response_data'=>$response_data,'times'=>DB::expr('times + 1')))->where('id','=',intval($id))->execute();
        return $affected_rows!==NULL;
    }

    //队列 任务完成后,释放队列占位 [正常情况下也可以不清除队列进程标记,这并不影响后续进程,因为status字段处理完成后不会再出现RUNNING状态]
    public function queue_end_clear($handle=NULL) {
        return NULL !== DB::update('sms_queue')->set(array('handle'=>NULL))->where('handle','=',$handle)->execute();
    }





    //构造查询条件
    private function query_builder($query, $array=array()) {
        if(isset($array['time_start']) && $array['time_start']>0) {
            $query->and_where('create_time','>=',$array['time_start']);
        }
        if(isset($array['time_end']) && $array['time_end']>0) {
            $query->and_where('create_time','<=',$array['time_end']);
        }
        if(isset($array['mobile']) && $array['mobile'] ) {
            $query->and_where('mobile','=',$array['mobile']);
        }
        if(isset($array['service_name']) && $array['service_name'] ) {
            $query->and_where('service_name','=',$array['service_name']);
        }
        if(isset($array['status']) && $array['status'] ) {
            $query->and_where('status','=',intval($array['status']));
        }
        if(isset($array['handle']) && $array['handle'] ) {
            $query->and_where('handle','=',$array['handle']);
        }
        return $query;
    }


    //查询分页
    public function get_list($array=array(),$perpage=20,$page=1) {
        $query = DB::select()->from('sms_queue');
        if(count($array)>0) {
            $query = $this->query_builder($query,$array);
        }
        if($page<1) {
            $page=1;
        }
        $rs=$query->order_by('sms_queue.id','DESC')->offset($perpage*($page-1))->limit($perpage)->execute()->as_array();
        return $rs;
    }


    public function get_total($array=array()) {
        $query=DB::select(array(DB::expr('COUNT(*)'),'total'))->from('sms_queue');
        if(count($array)>0) {
            $query = $this->query_builder($query,$array);
        }
        $rs=$query->execute()->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }







}