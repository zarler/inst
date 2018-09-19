<?php
/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/12
 * Time: 下午3:26
 */

class Lib_Risk_API_BaiRong_API extends Lib_Common
{

    public function query($param){
        $event_data['headerTitle'] = $param['headerTitle'];
        $event_data['targetList'] = $param['targetList'];

        $pass = 1;
        foreach ($event_data['targetList'] as $key => $value) {
            if(!in_array($event_data['headerTitle'][0],array('RuleSpecialList','RuleApplyLoan','RuleExecution','RuleAccountChange','RuleScore','RuleEquipmentCheck','RuleLoan_web','RuleLoan_android','RuleLoan_ios','RuleRegister_web','RuleRegister_android','RuleRegister_ios','RuleLog_web','RuleLog_android','RuleLog_ios','RuleCallDetail','SpecialList_c','ApplyLoan','LoanEquipment','RegisterEquipment','SignEquipment','Execution','EquipmentCheck','TelCheck','TelCheck_s','CallDetail','Stability_c','Consumption_c','Media_c','AccountChangeDer','PayConsumption','TelPeriod','scorepettycashv1'))){

                return array('status'=>false,'msg'=>'百融没有这个模块module','error'=>'9999');
                $pass = 0;
                break;
            }else{
                if(in_array($event_data['headerTitle'][0],array('RuleSpecialList','RuleApplyLoan','RuleExecution','RuleScore','RuleEquipmentCheck', 'RuleLoan_web', 'RuleLoan_android', 'RuleLoan_ios', 'RuleRegister_web', 'RuleRegister_android', 'RuleRegister_ios', 'RuleLog_web', 'RuleLog_android', 'RuleLog_ios','RuleCallDetail','EquipmentCheck','LoanEquipment','RegisterEquipment','SignEquipment'))){
                    if(empty($value['af_swift_number'])){
                        return array('status'=>false,'msg'=>'af_swift_number为必填字段','error'=>'9999');
                        $pass = 0;
                        break;
                    }
                    if(in_array($event_data['headerTitle'][0],array('RuleRegister_web', 'RuleRegister_android', 'RuleRegister_ios'))){
                        $event_data['headerTitle'][]='RegisterEquipment';
                        $event_data['targetList'][$key]['event']='antifraud_register';//注册事件
                    }elseif (in_array($event_data['headerTitle'][0],array('RuleLog_web', 'RuleLog_android', 'RuleLog_ios'))){
                        $event_data['headerTitle'][]='SignEquipment';
                        $event_data['targetList'][$key]['event']='antifraud_login';//登录事件
                    }elseif (in_array($event_data['headerTitle'][0],array('RuleLoan_web', 'RuleLoan_android', 'RuleLoan_ios'))){
                        $event_data['headerTitle'][]='RuleEquipmentCheck';
                        $event_data['headerTitle'][]='LoanEquipment';
                        $event_data['headerTitle'][]='EquipmentCheck';
                        $event_data['targetList'][$key]['event']='antifraud_lend';//借款事件
                    }
                    if(!empty($event_data['targetList'][$key]['partner_code'])){
                        $event_data['partner_code']=$event_data['targetList'][$key]['partner_code'];
                        unset($event_data['targetList'][$key]['partner_code']);
                    }
                }
            }
            $event_data['headerTitle']=array_unique($event_data['headerTitle']);
            if($pass==1){
                $br = new Lib_Risk_API_BaiRong_API_RiskService($event_data);
                $result = $br->mapping($event_data);


            }
        }

        return $result;
    }
}