<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 2016/10/20
 * Time: 上午3:08
 *
 * 添加银行卡时反欺诈规则
 *
 * lib::factory('Credit_BankCardAdd')->app_id(App::$id)->event_add_af(App::$_token['user_id');
 *
 */
class Lib_Credit_BankCard {

    const CREDIT_CODE = 'TC_FK_BK_20171130_001';//版本编号 ,授信每次发生变化,该号必须更新



    protected $user_id = 0;
    protected $last_update_time = 30*86400;// 30 day
    protected $last_repay_time = 30*86400;// 30 day
    protected $user = [];
    protected $app_id = '';
    protected $model = [];

    public function user_id($user_id=0){
        $this->user_id = (int)$user_id;
        $this->user = Model::factory('User')->get_one($this->user_id);
        return $this;
    }



    public function app_id($app_id){
        $this->app_id = $app_id;
        return $this;
    }






    //授信反欺诈通过
    public function event_add_af_passed($user_id=0){
       return TRUE;//银行卡实名反欺诈 不检查是否跑过 激活反欺诈方法 Controller_Ver2_BankCard::action_Add()
    }



    //检查授信反欺诈是否已通过
    public function has_event_add_af_pass($user_id=0) {
        return FALSE;
    }



    /**
     * 授信反欺诈
     * 异步执行,命中拒绝,未命中或查询失败不拒绝。
     */
    public function event_add_af($user_id=0){
        if($user_id>=0){
            $this->user_id($user_id);
        }
        if(!$this->user){
            return FALSE;
        }

        //快金:自有黑名单
        if(Model::factory('BlackList')->check(['mobile'=>$this->user['mobile'],'identity_code'=>$this->user['identity_code']])===TRUE){
            if((int)$this->user['status']!==(int)Model_User::STATUS_DENYFOREVER){
                if(Model::factory('User')->update($this->user_id,['status'=>Model_User::STATUS_DENYFOREVER])){
                    Model::factory('AntiFraud_Log')->create($this->user_id,Model_AntiFraud_Log::TYPE_TIMECASH_BLACKLIST,Model_AntiFraud_Log::RESULT_UNPASS,$this->user['status'],Model_User::STATUS_DENYFOREVER,'命中快金黑名单');
                }
            }
            return FALSE;
        }


        //白骑士:特殊名单 只跑数据不做决策 2017年11月服务已到期,取消。
        //$result=Lib::factory('TCCredit_AntiFraud')->user_id($this->user_id)->module('BaiQiShi','BlackList')->review(0,0,NULL);

        //百融:特殊名单   命中拒绝 同步黑名单
        $result=Lib::factory('TCCredit_AntiFraud')->user_id($this->user_id)->module('BaiRong','SpecialList_c')->review(0,0,Model_User::STATUS_DENYFOREVER);
        if(in_array($result,[Model_AntiFraud::UNPASS])){
            Model::factory("BlackList")->addWithCheck([
                'user_id'   => $this->user_id,
                'source'    =>Model_BlackList::SOURCE_BAIRONG,
                'why'   =>'百融反欺诈特殊名单命中',
                'name'  =>  isset($this->user['name']) ? $this->user['name'] : '',
                'identity_code' => isset($this->user['identity_code'])? $this->user['identity_code'] :'',
                'mobile'    => isset($this->user['mobile']) ?  $this->user['mobile'] : '',
                'create_time'   => time(),
            ]);
            return FALSE;
        }

        //百融:多次申请   命中暂时拒绝
        $result=Lib::factory('TCCredit_AntiFraud')->user_id($this->user_id)->module('BaiRong','ApplyLoan')->review(0,0,Model_User::STATUS_DENYTEMP);
        if(in_array($result,[Model_AntiFraud::UNPASS])){
            //多头借贷命中不进黑名单
            return FALSE;
        }

        /* 增加手机实名制反欺诈验证 2016-11-18 by majin */
        //百融:手机实名制  命中拒绝
        $result=Lib::factory('TCCredit_AntiFraud')->user_id($this->user_id)->module('BaiRong','TelCheck')->review(0,0,Model_User::STATUS_DENYFOREVER,FALSE);
        if(in_array($result,[Model_AntiFraud::UNPASS])){
            //未通过手机实名认证的直接拒绝,不添加黑名单
            return FALSE;
        }

        //同盾:特殊名单   命中拒绝
        $result=Lib::factory('TCCredit_AntiFraud')->user_id($this->user_id)->module('TongDun','SpecialList')->review(0,0,Model_User::STATUS_DENYFOREVER);
        if(in_array($result,[Model_AntiFraud::UNPASS])){
            Model::factory("BlackList")->addWithCheck([
                'user_id'   => $this->user_id,
                'source'    => Model_BlackList::SOURCE_TONGDUN,
                'why'   =>'同盾反欺诈特殊名单命中',
                'name'  =>  isset($this->user['name']) ? $this->user['name'] : '',
                'identity_code' => isset($this->user['identity_code'])? $this->user['identity_code'] :'',
                'mobile'    => isset($this->user['mobile']) ?  $this->user['mobile'] : '',
                'create_time'   => time(),
            ]);
            return FALSE;
        }

        //同盾:多次申请 2017-11-28 调整加入 命中暂时拒绝
        $result=Lib::factory('TCCredit_AntiFraud')->user_id($this->user_id)->module('TongDun','ApplyLoan')->review(0,0,Model_User::STATUS_DENYTEMP,FALSE);
        if(in_array($result,[Model_AntiFraud::UNPASS])){
            Model::factory('TCRisk_UserRecord')->exists_not_create([
                'user_id' => $this->user_id,
                'credit_code' => self::CREDIT_CODE,
                'summary' => 'user.status=5',
            ]);
            return FALSE;
        }

        //同盾:设备反欺诈 2017-6-27 调整加入 命中暂时拒绝
        $result=Lib::factory('TCCredit_AntiFraud')->user_id($this->user_id)->module('TongDun','Equipment')->review(0,0,Model_User::STATUS_DENYTEMP,FALSE);
        if(in_array($result,[Model_AntiFraud::UNPASS])){
            return FALSE;
        }


        //百融:在网时长   命中暂时拒绝
        $result=Lib::factory('TCCredit_AntiFraud')->user_id($this->user_id)->module('BaiRong','TelPeriodCheck')->review(0,0,Model_User::STATUS_DENYTEMP,FALSE);
        if(in_array($result,[Model_AntiFraud::UNPASS])){
            //在网时长不达标者只标记拒绝不进黑名单
            return FALSE;
        }

        //百融:法院执行名单     命中拒绝
        $result=Lib::factory('TCCredit_AntiFraud')->user_id($this->user_id)->module('BaiRong','Execution')->review(0,0,Model_User::STATUS_DENYFOREVER);
        if(in_array($result,[Model_AntiFraud::UNPASS])){
            Model::factory("BlackList")->addWithCheck([
                'user_id'   => $this->user_id,
                'source'    =>Model_BlackList::SOURCE_BAIRONG,
                'why'   =>'百融反欺诈法院执行名单命中',
                'name'  =>  isset($this->user['name']) ? $this->user['name'] : '',
                'identity_code' => isset($this->user['identity_code'])? $this->user['identity_code'] :'',
                'mobile'    => isset($this->user['mobile']) ?  $this->user['mobile'] : '',
                'create_time'   => time(),
            ]);
            return FALSE;
        }

        return TRUE;
    }




}
