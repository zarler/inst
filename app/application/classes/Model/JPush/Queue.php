<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: caojiabin
 * Date: 17/04/06
 * Time: 下午15:40
 *
 * 按照alias推送消息 20170413 {chunyu}
 * 极光推送修改 20171116 {chunyu}
 * 分期新队列集成优化 20180129 {majin}
 *
 */
class Model_JPush_Queue extends Model_Database {

    const PRE = 'user@';//极光alias前缀
    const TYPE_NOTIFICATION = 'Notification';
    const TYPE_MESSAGE = 'Message';

    const SUCCESS = 1;   //完成
    const FAILED = 2;   //失败
    const READY = 3;    //等待执行
    const RUNNING = 4;  //执行中

    public $status_array =array(
        self::SUCCESS => '发送成功',
        self::FAILED  =>'发送失败',
        self::RUNNING =>'进行中',
        self::READY   =>'待发送',
    );
    public $data;
    public $info;


    public function getOne($id=0) {
        return DB::select()->from('jpush_queue')->where('id', '=', $id)->execute()->current();
    }

    //初始数据
    public function initData($code, $user_info = []){
        //获取信息
        $this->info = Model::factory('JPush')->get_one_by_code($code);
        if(count($this->info) < 1){
            return false;
        }
        $this->data = array(
            'user_id' => isset($user_info['user_id']) ? $user_info['user_id'] : 0,
            'mobile' => isset($user_info['mobile']) ? $user_info['mobile'] : '',
            'code' => $code,
            'data' => array(
                'title' => $this->info['title'],
                'body' => $this->info['body'],
                'extras' => array(
                    'img_url' => $this->info['img_url'],
                    'push_type' => isset($this->info['push_type']) ? "" . $this->info['push_type'] : '',
                    'open_page' => isset($this->info['open_page']) ? "" . $this->info['open_page'] : '',
                    'go_page' => isset($this->info['go_page']) ? "" . $this->info['go_page'] : '',
                ),
            ),
        );
    }

    //获取广播消息
    public function getPushAllData($code){
        $this->initData($code);
        $this->data['data']['all_audience'] = true;

    }

    //广播Message
    public function pushMessage($code){
        $this->getPushAllData($code);
        $this->data['type'] = self::TYPE_MESSAGE;

        if($this->create($this->data))
            return true;
        else
            return false;

    }

    //广播Notification
    public function pushNotification($code){
        $this->getPushAllData($code);
        $this->data['type'] = self::TYPE_NOTIFICATION;

        if($this->create($this->data))
            return true;
        else
            return false;
    }


    //获取alias推送消息
    public function getPushAliasData($code, $user_info){
        $this->initData($code, $user_info);
        $this->data['data']['alias'] = array(self::PRE . $user_info['user_id']);

    }

    //按照alias推送Message
    public function pushMessageByAlias($code, $user_info){
        $this->getPushAliasData($code, $user_info);
        $this->data['type'] = self::TYPE_MESSAGE;

        if($this->create($this->data))
            return true;
        else
            return false;
    }

    //按照alias推送Notification
    public function pushNotificationByAlias($code, $user_info){
        $this->getPushAliasData($code, $user_info);
        $this->data['type'] = self::TYPE_NOTIFICATION;

        if($this->create($this->data))
            return true;
        else
            return false;
    }



    //插入
    public function create($array=array()) {

        if(isset($array['user_id']) && isset($array['type']) && isset($array['mobile']) ) {

            $create_array = array(
                'user_id'		=> intval($array['user_id']) ,
                'type'	        => $array['type'],
                'code'	        => isset($array['code']) ? $array['code'] : '',
                'mobile'		=> $array['mobile'],
                'status'		=> self::READY,
                'request_data'	=> json_encode($array['data']),
                'create_time'	=> time(),
                'times'			=>0,
            );

            list($insert_id,$affected_rows) = DB::insert("jpush_queue", array('user_id', 'type', 'code', 'mobile', 'status', 'request_data', 'create_time', 'times'))
                ->values($create_array)
                ->execute();
            return $insert_id;
        }
        return false;
    }


    //更改
    public function update($id=0, $array=null) {

        if(!$array){

            return false;
        }
        unset($array['id']);
        $affected_rows = DB::update('jpush_queue')->set($array)->where('id', '=', intval($id))->execute();
        return $affected_rows!==null;
    }


    //删除
    public function delete($id,$array=array()) {

        if(is_array($id)){

            return DB::delete('jpush_queue')->where('id', 'IN', $id)->execute();
        }
        return DB::delete('jpush_queue')->where('id', '=', intval($id))->execute();
    }














    //队列 启动占位(利用handle字段和一条更新语句实现多进程列)
    public function queueStartHandle($handle=null, $size=30, $status=self::READY) {

        $array = array('status'=>self::RUNNING);
        if($handle !== null) {

            $array['handle']= $handle;
        }
        return null !== DB::update('jpush_queue')->set($array)->where('status', '=', $status)->order_by('id', 'ASC')->limit(intval($size))->execute();
    }

    //队列 完成启动后,获取占位的处理记录
    public function queueGetRunning($handle=null, $status=self::RUNNING) {

        $query = DB::select()->from('jpush_queue')->where('status', '=', $status);
        $array = array('status' => self::RUNNING);
        if($handle !== null) {

            $query->and_where('handle', '=', $handle);
        }
        return $query->execute()->as_array();
    }

    //队列处理返回 更新状态
    public function queueBackUpdate($id, $new_status, $response_data) {

        $affected_rows = DB::update('jpush_queue')->set(array('status' => $new_status, 'response_data' => $response_data, 'times' => DB::expr('times + 1')))->where('id', '=', intval($id))->execute();
        return $affected_rows !== null;
    }

    //队列 任务完成后,释放队列占位 [正常情况下也可以不清除队列进程标记,这并不影响后续进程,因为status字段处理完成后不会再出现RUNNING状态]
    public function queueEndClear($handle=null) {
        return null !== DB::update('jpush_queue')->set(array('handle' => null))->where('handle','=',$handle)->execute();
    }





    //构造查询条件
    private function queryBuilder($query, $array=array()) {
        if(isset($array['time_start']) && $array['time_start']>0) {
            $query->and_where('create_time', '>=', $array['time_start']);
        }
        if(isset($array['time_end']) && $array['time_end']>0) {
            $query->and_where('create_time', '<=', $array['time_end']);
        }
        if(isset($array['mobile']) && $array['mobile'] ) {
            $query->and_where('mobile', '=', $array['mobile']);
        }
        if(isset($array['type']) && $array['type'] ) {
            $query->and_where('type', '=', $array['type']);
        }
        if(isset($array['status']) && $array['status'] ) {
            $query->and_where('status', '=', intval($array['status']));
        }
        if(isset($array['handle']) && $array['handle'] ) {
            $query->and_where('handle', '=', $array['handle']);
        }
        return $query;
    }


    //查询分页
    public function getList($array=array(), $perpage=20, $page=1) {
        $query = DB::select()->from('jpush_queue');
        if(count($array)>0) {
            $query = $this->queryBuilder($query, $array);
        }
        if($page<1) {
            $page=1;
        }
        $rs=$query->order_by('jpush_queue.id', 'DESC')->offset($perpage*($page-1))->limit($perpage)->execute()->as_array();
        return $rs;
    }


    public function getTotal($array=array()) {
        $query=DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('jpush_queue');
        if(count($array) > 0) {
            $query = $this->queryBuilder($query,$array);
        }
        $rs=$query->execute()->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }


}
