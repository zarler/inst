
<?php defined('SYSPATH') or die('No direct script access.');

class Task_Sync_AppToDB extends Minion_Task
{
    protected  $_options = array(
        'off' => '0',
    );

    public $redisHash;
    public $model;

    public function __construct() {
        parent::__construct();
        $this->redisHash = Redis_Hash::instance();

    }


    protected function _execute(array $params)
    {
        $this->phone_book();
        $this->sms_record();
        $this->call_history();


    }

    /**
     * 异步将电话本存入数据库
     */
    public function phone_book(){

        $keys = $this->redisHash->find('inst_phone_add_*');
        if($keys){
            $this->model['phone_book'] = Model::factory('App_PhoneBook');
            foreach($keys as $key){
                $pb = $this->redisHash->get($key);
                echo date('Y-m-d H:i:s')." ".__CLASS__;
                $phonebook = json_decode($pb['phonebook'],TRUE);
                $user_id = $pb['user_id'];
                echo "\tkey:".$key."\tphonebook:".count($phonebook);
                if(isset($pb['user_id']) && $pb['user_id']>0  && is_array($phonebook) && $phonebook ){
                    $res = $this->model['phone_book']->create($pb['user_id'],$pb['phonebook']);
                    echo "\t导入成功";
                    $this->redisHash->del($key);
                }else{
                    $this->redisHash->rename($key,'error_'.$key);
                    echo "\t导入失败";
                }
                unset($pb);
                echo "\r\n";
                ob_flush();
            }
        }
    }


    /**
     * 异步将短信保存到数据库
     */
    public function sms_record(){
        $keys = Redis_Hash::instance()->find('inst_sms_add_*');
        if($keys){
            $this->model['sms_record'] = Model::factory('App_SMSRecord');
            foreach($keys as $key){
                $sms = $this->redisHash->get($key);
                echo date('Y-m-d H:i:s')." ".__CLASS__;
                $message = json_decode($sms['message'],TRUE);
                $user_id = $sms['user_id'];
                echo "\tkey:".$key."\tsms_record:".count($message);
                if( $user_id>0  && is_array($message) && $message){
                    $this->model['sms_record']->create($sms['user_id'],$sms['message']);
                    echo "\t导入成功";
                    $this->redisHash->del($key);
                }else{
                    echo "\t导入失败";
                    $this->redisHash->rename($key,'error_'.$key);
                }
                unset($sms);
                echo "\r\n";
                ob_flush();
            }
        }
    }


    /**
     * 异步将通话记录保存到数据库
     */
    public function call_history(){
        $keys = Redis_Hash::instance()->find('inst_call_add_*');
        if($keys){
            $this->model['call_history'] = Model::factory('App_CallHistory');
            foreach($keys as $key){
                $call = $this->redisHash->get($key);
                echo date('Y-m-d H:i:s')." ".__CLASS__;
                $history_list = json_decode($call['history_list'],TRUE);
                $user_id = $call['user_id'];
                echo "\tkey:".$key."\tcall_history:".count($history_list);
                if( $user_id>0  && is_array($history_list) && $history_list ){
                    $this->model['call_history']->create($user_id,$call['history_list']);
                    echo "\t导入成功";
                    $this->redisHash->del($key);
                }else{
                    echo "\t导入失败";
                    $this->redisHash->rename($key,'error_'.$key);
                }
                unset($call);
                echo "\r\n";
                ob_flush();
            }
        }
    }




}