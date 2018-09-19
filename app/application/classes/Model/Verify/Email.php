<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/19
 * Time: 上午1:43
 *
 * Email验证码
 *
 *  一天,每种 TYPE 内只能发送 RETRY_MAX 次
 *
 *
 */
class Model_Verify_Email extends Model_Database{

    const RESEND_PERIOD = 60;           //重发间隔
    const RETRY_MAX = 10;               //最多重发5次
    const EXPIRE_PERIOD = 86400;         //有效周期
    const FAILED_MAX = 9;               //最多失败9次,更换验证码后清零

    const STATUS_VALID = 1;             //状态:有效
    const STATUS_VALITED = 2;           //状态:已验证
    const STATUS_INVALID = 3;           //状态:无效(注销)
    const STATUS_REMOVE = 4;            //状态:删除

    const QUEUE_SUCCESS = 1;   //完成
    const QUEUE_FAILED = 2;   //失败
    const QUEUE_READY = 3;    //等待执行
    const QUEUE_RUNNING = 4;  //执行中

    const TYPE_COMPANYEMAIL = 'company_email';          //类型:公司邮箱

    const TPL_VALIDCOMPANYEMAIL ='ValidCompanyEmail';   //邮箱模板:验证公司邮箱




    //生成验证码
    public function make_code(){
        return Text::random('nozero',1).Text::random('numeric',3);
    }

    //验证码编号
    public function code_no(){
        return Text::random('nozero',1).Text::random('numeric',5);
    }


    /** 验证码
     * @param array $array
     * @return bool
     */
    public function create($array = array()){
        if( !isset($array['token_id']) || !isset($array['mobile']) || !isset($array['verify_code']) || !isset($array['type']) ){
            return FALSE;
        }
        $email = $array['email'];
        $verify_code = $array['verify_code'];
        $token_id =(int) $array['token_id'] ;
        $type = $array['type'];
        $verify_no = isset($array['verify_no']) ?  : '';
        $user_id = isset($array['user_id']) && $array['user_id']>0  ? (int) $array['user_id'] : 0 ;
        $create_time = isset($array['create_time']) && $array['create_time']>0 ? (int) $array['create_time'] : time() ;
        $send_time = isset($array['send_time']) && $array['send_time']>0 ? (int) $array['send_time'] : time() ;
        $expire_time = isset($array['expire_time']) && $array['expire_time']>0? (int) $array['expire_time'] :  time()+self::RESEND_PERIOD ;

        list($insert_id,$affected_rows)=DB::insert("verify_email",array(
            'email','token_id','type','verify_code','verify_no','user_id',
            'create_time','send_time','expire_time','status','send_count','failed_count'
        ))
            ->values(array($email,$token_id,$type,$verify_code,$verify_no,$user_id,
                $create_time,$send_time,$expire_time,Model_Verify_Email::STATUS_VALID,0,0))
            ->execute();
        return $insert_id;
    }



    /** 更新记录
     * @param $id
     * @param array $array
     * @return bool
     */
    public function update($id,$array = array()){
        if(!$id){
            return FALSE;
        }
        return  NULL !== DB::update('verify_email')->set($array)->where('id','=',(int)$id)->execute() ;
    }


    /** 单条数据
     * @param $id
     * @return mixed
     */
    public function get_one($id){
        return DB::select()->from('verify_email')->where('id','=',$id)->execute()->current();
    }


    /** 单条数据
     * @param $array
     * @return mixed
     */
    public function get_one_by_array($array){
        $query = DB::select()->from('verify_email')->order_by('id','DESC')->limit(1);
        if(isset($array['email']) && $array['email'] ){
            $query->where('email','=', $array['email']);
        }
        if(isset($array['token_id']) && $array['token_id']>0 ){
            $query->where('token_id','=',(int) $array['token_id']);
        }
        if(isset($array['user_id']) && $array['user_id']>0 ){
            $query->where('user_id','=',(int) $array['user_id']);
        }
        if(isset($array['type']) && $array['type'] ){
            $query->where('type','=', $array['type']);
        }
        if(isset($array['expire']) && $array['expire']>0 ){
            $query->where('expire_time','>=', (int) $array['expire']);
        }
        if(isset($array['today']) && $array['today']>0 ){//today = strtotime(date('Y-m-d'))
            $query->where('create_time','>=', (int) $array['today']);
        }
        if(isset($array['status']) && $array['status']>0 ){
            $query->where('status','=', (int) $array['status']);
        }
        //var_dump($query->compile());
        return $query->execute()->current();
    }


    /** 单条数据
     * @param $mobile
     * @return mixed
     */
    public function today_email($email,$type){
        if(!$email){
            return FALSE;
        }
        return $this->get_one_by_array(array(
            'email'=>$email,
            'type'=>$type,
            'today'=>date('Y-m-d'),
            'status'=>Model_Verify_Email::STATUS_VALID,
        ));
    }

    /** 单条数据
     * @param $mobile
     * @param $token_id
     * @return mixed
     */
    public function today_email_token($email,$token_id,$type){
        if(!$email || !$token_id){
            return FALSE;
        }
        return $this->get_one_by_array(array(
            'email'=>$email,
            'token_id'=>$token_id,
            'type'=>$type,
            'today'=>date('Y-m-d'),
            'status'=>Model_Verify_Email::STATUS_VALID,
        ));
    }



    /** 单条数据
     * @param $mobile
     * @param $type
     * @param $time
     * @return bool|mixed
     */
    public function period_email($email,$type,$time=NULL){
        if(!$email){
            return FALSE;
        }
        if($time===NULL){
            $time = time();
        }
        return $this->get_one_by_array(array(
            'email'=>$email,
            'type'=>$type,
            'expire'=>$time,
            'status'=>Model_Verify_Email::STATUS_VALID,
        ));
    }

    public function period_email_token($email,$token_id,$type,$time=NULL){
        if(!$email || !$token_id){
            return FALSE;
        }
        if($time===NULL){
            $time = time();
        }
        return $this->get_one_by_array(array(
            'email'=>$email,
            'token_id'=>$token_id,
            'type'=>$type,
            'expire'=>$time,
            'status'=>Model_Verify_Email::STATUS_VALID,
        ));
    }


    /** 只对比不更新
     * @param $mobile
     * @param $token_id
     * @param $verify_code
     * @param $type
     * @param null $time
     * @return bool
     */
    public function check($email,$token_id,$verify_code,$type,$time=NULL){
        if(!$email || !$token_id){
            return FALSE;
        }
        if($time===NULL){
            $time = time();
        }
        if($rs = $this->period_email_token($email,$token_id,$type,$time)){
            if( (string)$verify_code == (string)$rs['verify_code'] ){
                return (int)$rs['id'];
            }
        }
        return FALSE;
    }


    /** 检测验证码 一次过期
     * @param $mobile
     * @param $token_id
     * @param $verify_code
     * @param $type
     * @param null $time
     * @return bool
     */
    public function check_once($email,$token_id,$verify_code,$type,$time=NULL){
        if(!$email || !$token_id){
            return FALSE;
        }
        if($time===NULL){
            $time = time();
        }
        if($rs = $this->period_email_token($email,$token_id,$type,$time)){
            if( (string)$verify_code == (string)$rs['verify_code'] ){
                $this->success($rs['id']);
                return TRUE;
            }else{
                $this->failed($rs['id']);
            }
        }
        return FALSE;
    }





    /** 失败处理
     * @param $id
     * @return bool
     */
    public function failed($id){
        if(!$id){
            return FALSE;
        }
        if($rs = $this->get_one($id)){
            $failed_count = $rs['failed_count'] + 1;
            if( $failed_count >= Model_Verify_Email::FAILED_MAX ){
                return NULL !== $this->update($id,array('failed_count'=>$failed_count,'status'=>Model_Verify_Email::STATUS_INVALID));
            }else{
                return NULL !== $this->update($id,array('failed_count'=>$failed_count));
            }
        }
        return FALSE;
    }

    /** 成功处理
     * @param $id
     * @return bool
     */
    public function success($id){
        return NULL !== $this->update($id,array('status'=>Model_Verify_Email::STATUS_VALITED));
    }


    /** 清理
     * @param $time
     * @return bool
     */
    public function gc($time=NULL){
        if($time===NULL){
            $time = strtotime(date('Y-m-d'));
        }
        return NULL !== DB::delete('verify_email')
            ->where('expire_time','<',$time)
            ->execute();
    }


    /** 添加发送队列
     * @param array $array :server_name =>邮件模板code(tc_mail.code,)
     * @return bool
     */
    public function queue_add($array=array()){
        if(isset($array['user_id']) && isset($array['service_name']) && isset($array['email']) ){
            if(!isset($array['data']) && !is_array($array['data'])){
                $array['data']=array();
            }
            $create_array = array(
                'user_id'=>  intval($array['user_id']) ,
                'service_name'=> $array['service_name'],
                'email'=> $array['email'],
                'status'=> Model_Verify_Email::QUEUE_READY,
                'request_data'=> json_encode($array['data']),
                'create_time'=> time(),
                'times'=>0,
            );

            list($insert_id,$affected_rows)=DB::insert("mail_queue",array('user_id','service_name','email','status','request_data','create_time','times'))
                ->values($create_array)
                ->execute();
            return $insert_id;
        }
        return FALSE;
    }



}