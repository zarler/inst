<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 2016/9/27
 * Time: 23:11
 *
 * 发欺诈操作记录
 *
 *
 */
class Model_AntiFraud_Log extends Model {
    //表:tc_anti_fraud_log.type
    const TYPE_TONGDUN = 1;//同盾反欺诈
    const TYPE_BAIRONG_ = 2;//百融反欺诈(作废)

    const TYPE_TONGDUN_SCORE = 30;              //(首次)同盾评分
    const TYPE_TONGDUN_SPECIALLIST = 31;        //(首次)同盾特殊名单
    const TYPE_TONGDUN_APPLYLOAN = 32;          //(首次)同盾都头借贷
    const TYPE_TONGDUN_SCORE_AGAIN = 33;        //(再次)同盾评分
    const TYPE_TONGDUN_SPECIALLIST_AGAIN = 34;  //(再次)同盾特殊名单
    const TYPE_TONGDUN_APPLYLOAN_AGAIN = 35;    //(再次)同盾多头借贷
    const TYPE_TONGDUN_EQUIPMENT = 36;          //(首次)同盾设备反欺诈
    const TYPE_TONGDUN_EQUIPMENT_AGAIN = 37;    //(再次)同盾设备反欺诈


    const TYPE_BAIRONG_SPECIALLIST_C = 40;  //(首次)百融反欺诈:特殊名单
    const TYPE_BAIRONG_SPECIALLIST_C_CONTACT = 41;  //(首次)百融反欺诈:特殊名单&联系人
    const TYPE_BAIRONG_APPLYLOAN = 42;  //(首次)百融反欺诈:多次申请
    const TYPE_BAIRONG_EXECUTION= 43;  //(首次)百融反欺诈:法院执行名单

    const TYPE_BAIRONG_SPECIALLIST_C_AGAIN = 45;  //(再次)百融反欺诈:特殊名单
    const TYPE_BAIRONG_SPECIALLIST_C_CONTACT_AGAIN = 46;  //(再次)百融反欺诈:特殊名单&联系人
    const TYPE_BAIRONG_APPLYLOAN_AGAIN = 47;  //(再次)百融反欺诈:多次申请
    const TYPE_BAIRONG_EXECUTION_AGAIN= 48;  //(再次)百融反欺诈:法院执行名单

    const TYPE_BAIRONG_SCOREPETTYCASHV1_REDUCE_ENSURE = 44;//(首次)百融小额现金贷黑盒评分  降担保
    const TYPE_BAIRONG_SCOREPETTYCASHV1_FAST_LOAN = 50;//(首次)百融小额现金贷黑盒评分  极速贷
    const TYPE_BAIRONG_SCOREPETTYCASHV1_REDUCE_ENSURE_AGAIN = 51;//(再次)百融小额现金贷黑盒评分  降担保
    const TYPE_BAIRONG_SCOREPETTYCASHV1_FAST_LOAN_AGAIN = 52;//(再次)百融小额现金贷黑盒评分  极速贷
    const TYPE_BAIRONG_SCOREPETTYCASHV1_AGAIN = 49;//(再次)百融小额现金贷黑盒评分

    const TYPE_BAIRONG_TELCHECK = 53;   //(首次)百融反欺诈:手机号码实名认证 基础授信
    const TYPE_BAIRONG_TELCHECK_AGAIN =   54;//(再次)百融反欺诈:手机号码实名认证 基础授信

    const TYPE_BAIRONG_TELPERIOD = 57;//(首次)百融反欺诈:手机号码在网时长 基础授信
    const TYPE_BAIRONG_TELPERIOD_AGAIN = 58;//(再次)百融反欺诈:手机号码在网时长 基础授信


    const TYPE_RONG360_SCORE_REDUCE_ENSURE = 60;         //(首次)融360天机 西瓜分 黑盒评分  降担保
    const TYPE_RONG360_SCORE_REDUCE_ENSURE_AGAIN = 61;   //(再次)融360天机 西瓜分 黑盒评分  降担保
    const TYPE_RONG360_SCORE_FAST_LOAN = 62;             //(首次)融360天机 西瓜分 黑盒评分  极速贷
    const TYPE_RONG360_SCORE_FAST_LOAN_AGAIN = 63;       //(再次)融360天机 西瓜分 黑盒评分  极速贷

    const TYPE_TCREDIT_RISK_RESULT_REDUCE_ENSURE = 64;  //(首次)天创决策  降担保
    const TYPE_TCREDIT_RISK_RESULT_FAST_LOAN = 65;      //(首次)天创决策  降担保
    const TYPE_TCREDIT_RISK_RESULT_REDUCE_ENSURE_AGAIN = 66;    //(首次)天创决策  降担保
    const TYPE_TCREDIT_RISK_RESULT_FAST_LOAN_AGAIN = 67;        //(首次)天创决策  降担保


    const TYPE_JUXINLI_BLACKLIST = 5;//聚信立黑名单
    const TYPE_TIMECASH_BLACKLIST = 8;//快金黑名单

    const TYPE_RISK_AGE = 55;//高危年龄
    const TYPE_RISK_REGION = 56;//高危地区


    const TYPE_TCCREDIT_PHONEBOOK_REPEAT25 = 71;     //快金反欺诈:电话本与他人电话本重复25及以上
    const TYPE_TCCREDIT_PHONEBOOK_M2_P1 = 72;          //快金反欺诈:电话本码命中M2名单1个以上
    const TYPE_TCCREDIT_CALLHISTORY_M2_P1 = 73;        //快金反欺诈:电话本码命中M2名单1个以上
    const TYPE_TCCREDIT_CONTACT_NOT_IN_PHONEBOOK = 74;        //快金反欺诈:联系人未出现在电话本中


    const TYPE_BAIQISHI_BLACKLIST = 81;          //(首次)白骑士反欺诈云风险名单
    const TYPE_BAIQISHI_BLACKLIST_AGAIN = 82;    //(再次)白骑士反欺诈云风险名单

    //表:tc_anti_fraud_log.result
    const RESULT_PASS = Model_AntiFraud::PASS;//通过
    const RESULT_UNPASS = Model_AntiFraud::UNPASS;//未通过


    //表:tc_anti_fraud_log.user_status   .source_user_status
    //look for : Model_User







    public function get_by_user_id($user_id){
        return DB::select()->from('anti_fraud_log')->where('user_id','=',$user_id)->execute()->as_array();
    }

    public function get_one($user_id,$type=NULL){
        return DB::select("id")->from('anti_fraud_log')->where('user_id','=',$user_id)->and_where("type","=",$type)->execute()->current();
    }



    public function create($user_id=0,$type=NULL,$result=NULL,$source_user_status=NULL,$user_status=NULL,$message=NULL) {
        if($user_id<1|| $type===NULL || $result===NULL ||  $user_status===NULL  ){
            return FALSE;
        }
        $add = array('user_id','type','result','source_user_status','user_status','message','create_time');
        $data['user_id']=(int) $user_id;
        $data['type']=(int) $type;
        $data['result']=(int) $result;
        $data['source_user_status']=(int) $source_user_status;
        $data['user_status']=(int) $user_status;
        $data['message'] = $message;
        $data['create_time']=time();
        list($id,$row) = DB::insert('anti_fraud_log', $add)->values($data)->execute();
        return $id?:0;
    }

    //更改
    public function update($id = 0, $array = NULL) {
        if (!$array || empty($id)) {
            return FALSE;
        }
        unset($array['id']);
        $affected_rows = DB::update('anti_fraud_log')->set($array)->where('id', '=', intval($id))->execute();
        return $affected_rows !== NULL;
    }

}