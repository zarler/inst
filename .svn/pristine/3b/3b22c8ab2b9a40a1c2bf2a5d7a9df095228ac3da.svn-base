<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/5/19
 * Time: 下午1:43
 */
class Model_BankCard extends Model_Database{

    const STATUS_ON = 1;        //状态:有效
    const STATUS_OFF = 2;          //状态:无效


    const AUTH_ERROR_COUNT_MAX = 10;//最多允许验卡错误10次(成功一次清零)


    public function get_one($id){
        return DB::select()->from('bankcard')->where('id','=',(int)$id)->execute()->current();
    }

    public function get_one_by_user_id($user_id,$id=NULL,$status = array(self::STATUS_ON) ){
        if($id===NULL){
            return DB::select()->from('bankcard')->where('user_id','=',(int)$user_id)->and_where('status','IN',$status)->limit(1)->execute()->current();
        }
        return DB::select()->from('bankcard')->where('user_id','=',(int)$user_id)->and_where('id','=',$id)->and_where('status','IN',$status)->execute()->current();
    }

    public function get_one_by_card_no($card_no,$status = array(self::STATUS_ON) ){
        return DB::select()->from('bankcard')->where('card_no','=',$card_no)->and_where('status','IN',$status)->limit(1)->execute()->current();
    }


    public function get_by_user_id($user_id,$status = array(self::STATUS_ON)){
        return DB::select()->from('bankcard')->where('user_id','=',(int)$user_id)->and_where('status','IN',$status)->execute()->as_array();
    }

    public function get_bank_by_id($id){
        return DB::select()
            ->from('bank')
            ->where('id','IN', DB::select('bank_id')
                ->from('bankcard')
                ->where('id','=',$id)
            )
            ->execute()
            ->current();
    }


    public function get_by_array($array,$rows=0){
        $query = DB::select()->from('bankcard');
        if(isset($array['user_id'])){
            $query->where('user_id','=',(int)$array['user_id']);
        }
        if(isset($array['card_no'])){
            $query->where('card_no','=',(int)$array['card_no']);
        }
        if(isset($array['status'])){
            if(is_array($array['status'])){
                $query->where('status','IN',$array['status']);
            }else{
                $query->where('status','=',(int)$array['status']);
            }
        }

        if($rows=0) {
            return $query->order_by('id', 'ASC')->execute()->as_array();
        }elseif($rows=1){
            return $query->order_by('id','ASC')->limit(1)->execute()->current();
        }else{
            return $query->order_by('id','ASC')->limit($rows)->execute()->current();
        }
    }

    public function get_one_by_array ($array) {
        return $this->get_by_array($array,1);
    }






    /** 创建
     * @param $array
     * @return bool
     */
    public function create($user_id,$array){
        if( !isset($array['bank_id'])
            || !isset($array['card_no'] ) || !isset($array['mobile'])
            || !isset($array['holder']) ){
            return FALSE;
        }
        if($rs = $this->get_one_by_user_id($user_id)){
            return FALSE;
        }

        $bank_id = $array['bank_id'];
        $card_no = $array['card_no'];
        $mobile = $array['mobile'];
        $holder = $array['holder'];
        $identity_code = (isset($array['identity_code']) && $array['identity_code']) ? $array['identity_code'] : '';
        $issue = (isset($array['issue']) && $array['issue']) ? $array['issue'] : '';
        $status = isset($array['status']) ? (int)$array['status'] : Model_BankCard::STATUS_ON;
        $create_time = isset($array['create_time']) ? (int)$array['create_time'] : time();

        list($insert_id,$affected_rows)=DB::insert("bankcard",array(
            'user_id','bank_id','card_no','mobile','holder','identity_code','issue','status','create_time'))
            ->values(array(
                $user_id, $bank_id, $card_no, $mobile, $holder, $identity_code, $issue, $status, $create_time ))
            ->execute();
        Lib::factory('TCCache_User')->user_id($user_id)->remove();//CACHE ----- [DELETE]

        return $insert_id;
    }


    /** 更改
     * @param $array
     * @return bool
     */
    public function update($user_id,$array){
        if( !isset($array['bank_id'])
            || !isset($array['card_no'])
            || !isset($array['mobile'])
            || !isset($array['holder']) ){
            return FALSE;
        }

        if($rs = $this->get_one_by_user_id($user_id)){

        }else{
            return FALSE;//无有效卡时返回失败;
        }

        $bank_id = $array['bank_id'];
        $card_no = $array['card_no'];
        $mobile = $array['mobile'];
        $holder = $array['holder'];
        $identity_code = (isset($array['identity_code']) && $array['identity_code']) ? $array['identity_code'] : '';
        $issue = (isset($array['issue']) && $array['issue']) ? $array['issue'] : '';
        $status = isset($array['status']) ? (int)$array['status'] : Model_BankCard::STATUS_ON;
        $create_time = isset($array['create_time']) ? (int)$array['create_time'] : time();
        DB::update('bankcard')->set(array('status'=>Model_BankCard::STATUS_OFF))
            ->where('user_id','=',$user_id)
            ->and_where('status','=',Model_BankCard::STATUS_ON)
            ->limit(1)//取消一张
            ->execute();
        list($insert_id,$affected_rows)=DB::insert("bankcard",array(
            'user_id','bank_id','card_no','mobile','holder','identity_code','issue','status','create_time'))
            ->values(array(
                $user_id, $bank_id, $card_no, $mobile, $holder, $identity_code, $issue, $status, $create_time))
            ->execute();
        Lib::factory('TCCache_User')->user_id($user_id)->remove();//CACHE ----- [DELETE]
        return $insert_id;
    }



    public function bank_id_check($id,$bank_code){
        return DB::select()->from('bank')->where('id','=',$id)->and_where('code','=',$bank_code)->execute()->current();
    }


    /**
     * 是否支持 bank_id
     * @param $bank_id
     * @param $bank_code 兼容验证 银行编号需求 默认可不不传
     */
    public function supported($bank_id,$bank_code=NULL){
        return Model::factory('Bank')->valid($bank_id,$bank_code);
    }








}