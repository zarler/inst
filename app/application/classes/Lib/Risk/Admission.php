<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: xuhao
 * Date: 2018/1/17
 * Time: 下午3:01
 *
 *
 * 准入规则
 *
 */
class Lib_Risk_Admission
{

    protected $user_id = 0;
    protected $user    = [];


    public function getUserId($user_id = 0) {
        $this->user_id = (int)$user_id;
        $this->user = Model::factory('User')->get_one($this->user_id);
        return $this;
    }

    /**
     * 准入规则
     * 不做二次调用的处理，若有需求，后期再加
     */
    public function base($user_id = 0){
        $invalid = Model_AdmissionShort::STATUS_INVALID;
        $ret_end = Model_AdmissionShort::STATUS_VALID;

        // 已存在准入数据，则不再判断，直接返回失败
        $old_end = Model::factory('AdmissionShort')->getOldShortEnd($user_id);
        if($old_end){
            return $old_end;
        }

        $short_end_arr = [];
        foreach(Model_AdmissionShort::RULERS as $k => $v){
            $short_end_arr[$k] = Model_AdmissionShort::STATUS_VALID;
        }
        unset($short_end_arr[Model_AdmissionShort::RULER_ZRQ10007]);

        if ($user_id >= 0) {
            $this->getUserId($user_id);
        }

        if (!$this->user) {
            return $invalid;
        }

        // 工作信息
        $work   = Model::factory('CreditInfo_Work')->get_one($this->user_id);
        // 设备信息
        $device = Model::factory('Location_BaiduMap')->get_one_by_user_id($this->user_id);
        // 居住地
        $home   = Model::factory('CreditInfo_Home')->get_one($this->user_id);

        $check_city_arr = [$work, $device, $home];

        // RULER_ZRQ10001  => '年龄小于22周岁或大于45周岁',
        $age = (int) Lib::factory('IDCard')->age($this->user['identity_code']);
        if($age < 22 || $age > 45){
            $short_end_arr[Model_AdmissionShort::RULER_ZRQ10001] = $invalid;
            $ret_end = $invalid;
        }

        // RULER_ZRQ10002  => '工作地定位、现居住地定位、当前设备定位省份任意项是新疆或西藏'
        $city_names = Model_AdmissionShort::RULER_ZRQ10002_WORK;
        $short_end_arr[Model_AdmissionShort::RULER_ZRQ10002] = $this->workCheck($check_city_arr, $city_names, 'province', $ret_end);


        // RULER_ZRQ10003  => '工作地定位、现居住地定位、当前设备定位省份任意项是延边朝鲜族自治州',
        $city_names = Model_AdmissionShort::RULER_ZRQ10003_WORK;
        $short_end_arr[Model_AdmissionShort::RULER_ZRQ10003] = $this->workCheck($check_city_arr, $city_names, 'city', $ret_end);


        // RULER_ZRQ10004  => '用户填写单位名称中，若字段中包含“小额贷款、小贷、担保、互联网 金融、互联网金融服务、金融服务 OR金融信息服务、金融信息咨询、 金融科技”任一词',
        $word_intratype = Model_AdmissionShort::RULER_ZRQ10004_WORK;
        $short_end_arr[Model_AdmissionShort::RULER_ZRQ10004] = $this->workCheck([$work], $word_intratype, 'company_name', $ret_end);


        // RULER_ZRQ10005  => '用户填写单位名称中，若字段中包“公安局、派出所、检察院、法院、监狱、看守所、拘留所”任一词'
        $word_pso = Model_AdmissionShort::RULER_ZRQ10005_WORK;
        $short_end_arr[Model_AdmissionShort::RULER_ZRQ10005] = $this->workCheck([$work], $word_pso, 'company_name', $ret_end);


        // RULER_ZRQ10006  => '用户填写单位名称中，若字段中包“解放军、武警及陆、海、空军”，且不包含“医院”;',
        $word_army = Model_AdmissionShort::RULER_ZRQ10006_WORK;
        $have_hospital = mb_strpos($work['company_name'], Model_AdmissionShort::RULER_ZRQ10006_WORK_TWO);

        foreach($word_army as $wv){
            if(mb_strpos($work['company_name'], $wv) !== false && $have_hospital === false){
                $short_end_arr[Model_AdmissionShort::RULER_ZRQ10006] = $invalid;
                $ret_end = $invalid;
                break;
            }
        }


        // RULER_ZRQ10008  => '用户发起授信申请时，对用户注册时间进行判断，注册时间为凌晨3:00(含)-5:00(含)时间段内',
        $user_reg_hour   = (int)date('H', $this->user['create_time']);
        $user_reg_second = (int)date('s', $this->user['create_time']);

        if($user_reg_hour >= 3 && ($user_reg_hour < 5 || ($user_reg_hour == 5 && $user_reg_second == 0))){
            $short_end_arr[Model_AdmissionShort::RULER_ZRQ10008] = $invalid;
            $ret_end = $invalid;
        }


        // RULER_ZRQ10009  => '若用户申请授信时间为凌晨1:00(含)-6:00(含)时间段内',
        $where_arr = [
            'type'  => Model_EquipmentEvent::TYPE_CREDIT_INFO,
            'type2' => Model_EquipmentEvent::TYPE2_CREDIT_INFO,
        ];
        $equipment_event = Model::factory('EquipmentEvent')->getOneByUserId($this->user_id, $where_arr, [['id', 'asc']]);

        if($equipment_event){
            $now_hour   = (int)date('H', $equipment_event['create_time']);
            $now_second = (int)date('s', $equipment_event['create_time']);

            if($now_hour >= 1 && ($now_hour < 6 || ($now_hour == 6 && $now_second == 0)) ){
                $short_end_arr[Model_AdmissionShort::RULER_ZRQ10009] = $invalid;
                $ret_end = $invalid;
            }

        }else{
            $short_end_arr[Model_AdmissionShort::RULER_ZRQ10009] = $invalid;
            $ret_end = $invalid;
        }


        // RULER_ZRQ100010 => '通过OCR对身份证反面有效期进行验证，若有效期截止 日期距离申请日期小于等于30天',
        $idauth    = Model::factory('User_FaceIDAuth')->get_one_by_user_id($this->user_id);
        if($idauth['valid_date']){
            $validity  = explode('-', $idauth['valid_date']);
            $valid_day = (int)(strtotime(str_replace('.', '-', $validity[1])) - time());

            if($valid_day <= 2592000){
                $short_end_arr[Model_AdmissionShort::RULER_ZRQ100010] = $invalid;
                $ret_end = $invalid;
            }

        }else{
            $short_end_arr[Model_AdmissionShort::RULER_ZRQ100010] = $invalid;
            $ret_end = $invalid;
        }



        // RULER_ZRQ100011 => '手机号或身份证号命中我司自有黑名单',
        if(Model::factory('BlackList')->check(['mobile' => $this->user['mobile'], 'identity_code' => $this->user['identity_code']]) === TRUE){
            $short_end_arr[Model_AdmissionShort::RULER_ZRQ100011] = $invalid;
            $ret_end = $invalid;
        }


        // 增加准入简报
        $db = Database::instance();
        $db->begin();
        try{
            foreach($short_end_arr as $sk => $sv){
                $qr_end = Model::factory("AdmissionShort")->addShort($this->user_id, $sk, $sv);
                if(!$qr_end){
                    $db->rollback();
                    return Model_AdmissionShort::STATUS_RETRY;
                }
            }
            $db->commit();

        }catch(Exception $e){
            $db->rollback();
            return Model_AdmissionShort::STATUS_RETRY;
        }

        return $ret_end;
    }

    /**
     * 字符串检测
     * 工作地定位、现居住地定位、当前设备定位中文字符串检测
     * 公司名中文字符串检测
     */
    public function workCheck($check_arr, $names, $key_name, &$ret_end)
    {
        foreach($check_arr as $cv){
            foreach($names as $word){
                if(mb_strpos($cv[$key_name], $word) !== false){
                    $ret_end = false;
                    return Model_AdmissionShort::STATUS_INVALID;
                }
            }
        }

        return Model_AdmissionShort::STATUS_VALID;
    }

}