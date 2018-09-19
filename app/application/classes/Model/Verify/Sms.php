<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/3
 * Time: 下午11:43
 *
 *  一天,每种 TYPE 内只能发送 RETRY_MAX 次
 *
 *
 */
class Model_Verify_Sms extends Model_Database{

    const RESEND_PERIOD = 60;           //重发间隔
    const RETRY_MAX = 5;                //最多重发5次
    const EXPIRE_PERIOD = 1800;          //有效周期
    const FAILED_MAX = 9;               //最多失败9次,更换验证码后清零

    const STATUS_VALID = 1;             //状态:有效
    const STATUS_VALITED = 2;           //状态:已验证
    const STATUS_INVALID = 3;           //状态:无效(注销)
    const STATUS_REMOVE = 4;            //状态:删除

    const TYPE_REG = 'reg';             //类型:注册
    const TYPE_REPAY = 'repay';         //类型:还款
    const TYPE_RESETPWD = 'resetpwd';   //类型:重置密码
    const TYPE_BANKCARD = 'bankcard';   //类型:更换银行卡



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
        $mobile = $array['mobile'];
        $verify_code = $array['verify_code'];
        $token_id =(int) $array['token_id'] ;
        $type = $array['type'];
        $verify_no = isset($array['verify_no']) ?  : '';
        $user_id = isset($array['user_id']) && $array['user_id']>0  ? (int) $array['user_id'] : 0 ;
        $create_time = isset($array['create_time']) && $array['create_time']>0 ? (int) $array['create_time'] : time() ;
        $send_time = isset($array['send_time']) && $array['send_time']>0 ? (int) $array['send_time'] : time() ;
        $expire_time = isset($array['expire_time']) && $array['expire_time']>0? (int) $array['expire_time'] :  time()+self::RESEND_PERIOD ;

        list($insert_id,$affected_rows)=DB::insert("verify_sms",array(
            'mobile','token_id','type','verify_code','verify_no','user_id',
            'create_time','send_time','expire_time','status','send_count'
        ))
            ->values(array($mobile,$token_id,$type,$verify_code,$verify_no,$user_id,
                $create_time,$send_time,$expire_time,Model_Verify_Sms::STATUS_VALID,0))
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
        return  NULL !== DB::update('verify_sms')->set($array)->where('id','=',(int)$id)->execute() ;
    }


    /** 单条数据
     * @param $id
     * @return mixed
     */
    public function get_one($id){
        return DB::select()->from('verify_sms')->where('id','=',$id)->execute()->current();
    }


    /** 单条数据
     * @param $array
     * @return mixed
     */
    public function get_one_by_array($array){
        $query = DB::select()->from('verify_sms')->order_by('id','DESC')->limit(1);
        if(isset($array['mobile']) && $array['mobile'] ){
            $query->where('mobile','=', $array['mobile']);
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
        if(isset($array['today']) && $array['today'] ){
            $query->where('create_time','>=', strtotime($array['today']));
            $query->where('create_time','<', strtotime($array['today'].' +1 day'));
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
    public function today_mobile($mobile,$type){
        if(!$mobile){
            return FALSE;
        }
        return $this->get_one_by_array(array(
            'mobile'=>$mobile,
            'type'=>$type,
            'today'=>date('Y-m-d'),
            'status'=>Model_Verify_Sms::STATUS_VALID,
        ));
    }


    /** 单条数据
     * @param $mobile
     * @return mixed
     */
    public function today_mobile_token($mobile,$token_id,$type){
        if(!$mobile || !$token_id){
            return FALSE;
        }
        return $this->get_one_by_array(array(
            'mobile' => $mobile,
            'token_id' => $token_id,
            'type' => $type,
            'today' => date('Y-m-d'),
            'status' => Model_Verify_Sms::STATUS_VALID,
        ));
    }



    /** 单条数据
     * @param $mobile
     * @param $type
     * @param $time
     * @return bool|mixed
     */
    public function period_mobile($mobile,$type,$time=NULL){
        if(!$mobile){
            return FALSE;
        }
        if($time===NULL){
            $time = time();
        }
        return $this->get_one_by_array(array(
            'mobile'=>$mobile,
            'type'=>$type,
            'expire'=>$time,
            'status'=>Model_Verify_Sms::STATUS_VALID,
        ));
    }

    public function period_mobile_token($mobile,$token_id,$type,$time=NULL){
        if(!$mobile || !$token_id){
            return FALSE;
        }
        if($time===NULL){
            $time = time();
        }
        return $this->get_one_by_array(array(
            'mobile'=>$mobile,
            'token_id'=>$token_id,
            'type'=>$type,
            'expire'=>$time,
            'status'=>Model_Verify_Sms::STATUS_VALID,
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
    public function check($mobile,$token_id,$verify_code,$type,$time=NULL){
        if(!$mobile || !$token_id){
            return FALSE;
        }
        if($time===NULL){
            $time = time();
        }
        if($rs = $this->period_mobile_token($mobile,$token_id,$type,$time)){
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
    public function check_once($mobile,$token_id,$verify_code,$type,$time=NULL){
        if(!$mobile || !$token_id){
            return FALSE;
        }
        if($time===NULL){
            $time = time();
        }
        if($rs = $this->period_mobile_token($mobile,$token_id,$type,$time)){
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
            if( $failed_count >= Model_Verify_Sms::FAILED_MAX ){
                return NULL !== $this->update($id,array('failed_count'=>$failed_count,'status'=>Model_Verify_Sms::STATUS_INVALID));
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
        return NULL !== $this->update($id,array('status'=>Model_Verify_Sms::STATUS_VALITED));
    }





    /** 统计手机号一天内发送总量
     * @param $mobile
     * @param null $type
     * @param null $now
     * @return bool|int
     */
 /*   public function mobile_today($mobile,$type=NULL,$now=NULL){
        if(!$mobile){
            return FALSE;
        }
        $query = DB::select(array(DB::expr('sum(send_count)'), 'total'))->from('verify_sms')->where('mobile','=',$mobile)->order_by('id','DESC')->limit(1);
        if($type!==NULL){
            $query->and_where('type','=',$type);
        }
        if($now!==NULL) {
            $min_time = strtotime(date('Y-m-d',$now));
        }else{
            $min_time = strtotime(date('Y-m-d'));
        }
        $max_time = $min_time+86400;
        $query->where('expire_time','>=',$min_time )->and_where('expire_time','<',$max_time);
        $rs = $query->execute()->current();
        return  isset($rs['total']) ? $rs['total'] : 0 ;
    }*/






    /** 清理
     * @param $time
     * @return bool
     */
    public function gc($time=NULL){
        if($time===NULL){
            $time = strtotime(date('Y-m-d'));
        }
        return NULL !== DB::delete('verify_sms')
            ->where('expire_time','<',$time)
            ->execute();
    }



    /** 发送
     * @param $mobile
     * @param $code
     * @return bool
     */
    public function send($mobile,$code,$type=self::TYPE_REG){
        if(!$mobile||!$code){
            return FALSE;
        }
        switch($type){
            case self::TYPE_REG:
                $service_name  = 'Register';
                break;
            case self::TYPE_REPAY:
                $service_name  = 'ActiveRepaymentCode';
                break;
            case self::TYPE_RESETPWD:
                $service_name  = 'ForgetPassword';
                break;
            case self::TYPE_BANKCARD:
                $service_name  = 'BankCardAuth';
                break;
            default:
                return FALSE;
        }
        $result = Lib::factory('SMS_API')->send([
            'mobile' => $mobile,
            'template'=>$service_name,
            'code' => $code,
            'param' => []
        ])->execute()->body();
        if( is_array($result) && isset($result['status']) && $result['status']===TRUE  ){
            return TRUE;
        }
        return FALSE;
    }




}