<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 2017/7/5
 * Time: 下午10:46
 *
 * 移动运营商通话记录查询
 *
 * 说明:
 *  1 先从API 获取跳转代码 HTML/URL
 *  2 用户完成运营商账号验证
 *  3 返回H5或微信显示成功。
 *  4 回调透传数 CAPI =》 API验证成功=》CAPI 记录tc_ci_step.mno=2|8|9 同时更新tc_mno_query_record表
 *
 *  2017-8-1 改为使用 天创接口
 *  2017-8-29 改为使用 融360
 *
 *
 */
class Lib_Risk_QueryRecord {

    protected static $model=[];
    protected static $provider = [
        Model_Risk_QueryRecord::PROVIDER_MOXIE =>[
            'class' => 'Lib_Risk_Query_Moxie',
            'func' => 'apply',
        ],
        Model_Risk_QueryRecord::PROVIDER_TCREDIT =>[
            'class' => 'Lib_MNO_Query_TCredit',
            'func' => 'apply',
        ],
    ];

    public function __construct() {
        self::$model['record'] = Model::factory('MNO_QueryRecord');
        self::$model['step'] = Model::factory('CreditInfo_Step');
        self::$model['user'] = Model::factory('User');

    }

    protected function make_no(){
        return self::$model['record']->make_no();
    }

    /** 申请
     * @param array $array
     * @return array|bool
     */
    public function apply($array =[]){
        if(isset($array['name']) && isset($array['mobile'])  && isset($array['identity_code']) && isset($array['provider']) && isset(self::$provider[$array['provider']]) ){
            $class = self::$provider[$array['provider']]['class'];
            $func  = self::$provider[$array['provider']]['func'];
            $array['tc_no'] = $this->make_no();

            if($newid = self::$model['record']->create($array)){

                $mc  = new $class;
                $res = $mc-> $func($array);


                if(is_array($res) && isset($res['status']) &&  $res['status']===Model_API::COMM_SUCC && isset($res['api_result']['result']) && $res['api_result']['result']==Model_API::RES_SUCC ){//这里应该有一套JSON返回的CODE判断。
                    $check_no = isset($res['api_result']['check_no'])? $res['api_result']['check_no']: '';
                    self::$model['record']->update($newid,[
                            'status' => Model_MNO_QueryRecord::STATUS_APPLIED,
                            'check_no' => $check_no,
                            'provider' => isset($res['api_result']['provider']) ? $res['api_result']['provider'] : '',
                            'code' => isset($data['api_result']['result']) ? $data['api_result']['result'] : (isset($data['error']) ? $data['error']:''),
                            'msg' => isset($data['api_result']['msg']) ? $data['api_result']['msg'] : (isset($data['msg']) ? $data['msg'] : ''),
                        ]
                    );
                    if(isset($res['api_result']['html']) && $res['api_result']['html']){
                        return ['html'=>$res['api_result']['html']];
                    }elseif(isset($res['api_result']['url']) && $res['api_result']['url']){
                        return ['url'=>$res['api_result']['url']];
                    }
                }else{
                    self::$model['record']->update($newid,[
                        'status' => Model_MNO_QueryRecord::STATUS_ERROR,
                        'code' => isset($data['api_result']['result']) ? $data['api_result']['result'] : (isset($data['error']) ? $data['error']:''),
                        'msg' => isset($data['api_result']['msg']) ? $data['api_result']['msg'] : (isset($data['msg']) ? $data['msg'] : ''),
                    ]);
                    return FALSE;
                }
            }
        }
        return FALSE;
    }

    public function apply_by_user_id($user_id,$provider){
        if($rs = self::$model['user']->get_one($user_id)){
            $array=[];
            $array['user_id'] = $rs['id'];
            $array['name'] = $rs['name'];
            $array['mobile'] = $rs['mobile'];
            $array['identity_code'] = $rs['identity_code'];
            $array['provider'] = $provider;
            return $this->apply($array);
        }
        return FALSE;
    }




    public function success($check_no,$tc_no=NULL,$array=[]){
        if(!$check_no){
            return FALSE;
        }
        $rs  = self::$model['record']->get_one_by_array(['check_no'=>$check_no]);
        if(!$rs){
            $rs = self::$model['record']->get_one_by_array(['tc_no'=>$tc_no]);
        }
        if($rs) {
            if(self::$model['record']->update($rs['id'],['status'=>Model_MNO_QueryRecord::STATUS_SUCCESS])){
                if( isset($rs['user_id']) && $rs['user_id']>0  &&  ($rs1 = self::$model['user']->get_one($rs['user_id'])) ) {
                    return TRUE;
                }
            }
        }else{
            //没找到申请记录
            if(isset($array['mobile']) && $array['mobile']) {
                $rs1 = self::$model['user']->get_one_by_array(['mobile' => $array['mobile']]);
                $newid = self::$model['record']->create([
                    'tc_no'=>isset($array['tc_no']) ? $array['tc_no'] : '',
                    'check_no'=>isset($array['check_no']) ? $array['check_no'] : '',
                    'user_id'=> $rs['user_id'],
                    'mobile'=> isset($array['mobile']) ? $array['mobile'] : '',
                    'provider'=> $rs['provider'] ? $rs['provider'] : ( isset($array['provider']) ? $array['provider'] : ''),
                    'status'=> Model_MNO_QueryRecord::STATUS_SUCCESS,
                ]);
                return TRUE;
            }
        }
        return FALSE;
    }


    public function update_status($check_no,$tc_no=NULL,$status){
        if(!$check_no){
            return FALSE;
        }
        if($rs  = self::$model['record']->get_one_by_array(['check_no'=>$check_no])){
            return self::$model['record']->update($rs['id'],['status'=>$status]);
        }elseif($tc_no && $rs  = self::$model['record']->get_one_by_array(['tc_no'=>$tc_no]) ){
            return self::$model['record']->update($rs['id'],['status'=>$status]);
        }
        return FALSE;
    }






}