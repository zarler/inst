<?php
header("Content-Type: text/html; charset=UTF-8");
defined('SYSPATH') or die('No direct script access.');
/**
 * ---------------百融风险决策API---------------
 *
 * Created by PhpStorm.
 * User: guorui
 * Date: 2016/1/8
 * Time: 15:48
 */
include_once realpath(dirname(__FILE__))."/config.php";
include_once realpath(dirname(__FILE__))."/Util.php";

class Lib_Risk_API_BaiRong_API_RiskService {
    private $user_id;
    private $username;
    private $password;
    private $apicode;
    private $br_login_url;
    private $tokenid;
    private $ctitle = array();

    public $br_data_url;
    public $res_login;
    public $userList;    //列表
    private $pass = true; //是否含有查询的必填字段 name,cell,id

    //API 登录接口返回码
    public $login_code_array = array(
        '00'	    =>  '登录成功',
        '100004'	=>  '商户不存在或用户名错误',
        '100005'	=>  '登陆密码不正确',
        '100008'	=>  '客户端api调用码不能为空',
        '100011'	=>  '账户停用',
    );

    //API 报告打包调用接口返回码
    public $result_code_array = array(
        '00'        =>	'操作成功',
        '100001'    =>  '程序错误',
        '100002'	=>  '匹配结果为空',
        '100003'	=>  '必选key值缺失或不合法',
        '100006'	=>  '请求参数格式错误',
        '100007'	=>  'Tokenid过期',
        '100009'	=>  'IP地址错误',
        '100010'	=>  '超出当天访问次数',
        '100012'	=>  '请求套餐为空',
        '1000015'	=>  '请求参数其他错误',
        '1000016'	=>  '捕获请求json异常，无法解析的错误',
    );

    //API 报告单独调用接口返回码
    public $alone_result_code_array = array(
        '00'        =>	'登录成功',
        '100001'    =>  '程序错误',
        '100004'	=>  '商户不存在',
        '100005'	=>  '登录密码错误',
        '100006'	=>  '请求参数格式错误',
        '100007'	=>  'Tokenid过期',
        '100009'	=>  'IP地址错误',
        '100011'	=>  '账户停用',
        '600000'    =>  '操作成功',
        '600001'    =>  '参数错误',
        '600002'    =>  'tokenid错误',
        '600003'    =>  '无该接口查询权限',
        '600004'    =>  '无该模块查询权限',
        '600005'    =>  '操作失败',
        '600006'    =>  '接口查询次数已用完',
        '1000015'	=>  '请求参数其他错误',
    );

    function __construct($event_data){
        if(empty($event_data['partner_code'])){
            $this->username = BaiRong_CONFIG::$account;
            $this->password = BaiRong_CONFIG::$password;
            $this->apicode = BaiRong_CONFIG::$apicode;
        }else{
            $this->username = BaiRong_CONFIG::$pufubao_account;
            $this->password = BaiRong_CONFIG::$pufubao_password;
            $this->apicode = BaiRong_CONFIG::$pufubao_apicode;
        }
        $this->br_login_url = BaiRong_CONFIG::$login_url;
        $this->br_data_url = BaiRong_CONFIG::$data_url;
        $this->login();
    }

    public function login(){
        $postData = array(
            "userName" => $this->username,
            "password" => $this->password,
            "apiCode" => $this->apicode
        );

        //echo $this->br_login_url."<br />";

        $res_login = Util::post($this->br_login_url,$postData);
        if(BaiRong_CONFIG::isDebug()){
            echo '<h3>登录结果</h3>';
            echo $res_login;
        }


        if($res_login){
            $loginData = json_decode($res_login,true);
            if($loginData['code']!='00') {
                return array('status'=>false,'msg'=>'登录异常','error'=>'8888','api_result'=>array('result'=>$loginData['code'],'msg'=>$this->login_code_array[$loginData['code']]));
                exit;
            }
            //var_dump($loginData);
            $this->tokenid = $loginData['tokenid'];      //取得tokenid
        }
    }

    function mapping($array){
        if(!($this->pass)){return;}
        $this->pushTargetList($array['targetList']);
        $this->user_id=isset($array['targetList']['user_id'])?$array['targetList']['user_id']:0;
        //正式环境套餐字段固定
        if(STATUS == 1){
            $this->headerTitle = $array['headerTitle'];
        }
        /*if(is_array($modules)){
            foreach ($modules as $key => $value) {
                echo $key."=".$value."<br />";
            }
        }*/
        return $this->query();
    }

    function rule_array_move($result,$rulestr_array,$rulestr_array2){
        foreach($result as $k =>$v) {
            foreach ($rulestr_array as $kk => $vv) {
                if (!(strpos($k, 'Rule_name_'.$kk) === false) || !(strpos($k, 'Rule_weight_'.$kk) === false)) {
                    if(empty($$vv)){
                        $$vv=array();
                    }
                    ${$vv}[$k] = $v;
                    unset($result[$k]);
                }
            }
            foreach ($rulestr_array2 as $kk => $vv) {
                if(strpos($k,$kk)===0){
                    if(empty($$vv)){
                        $$vv=array();
                    }
                    ${$vv}[$k]=$v;
                    unset($result[$k]);
                }
            }
        }
        foreach($result as $k =>$v) {
            if(!(strpos($k,'flag_')===false)&&($v==1)){
                foreach ($rulestr_array as $kk => $vv) {
                    if(strcasecmp(explode('flag_',$k)[1],$vv)==0){
                        $result[$vv]=$$vv;
                    }
                }
                foreach ($rulestr_array2 as $kk => $vv) {
                    if(strcasecmp(explode('flag_',$k)[1],$vv)==0){
                        $result[$vv]=$$vv;
                    }
                }
            }
        }
        return $result;
    }

    //查询数据接口
    function query(){
        //echo 'query()';
        /*res = self.post(url, {
                    'tokenid': tid,
                    'interCommand': '1000',
                    'apiCode': apicode,
                    'jsonData': data,
                    'checkCode': util.get_md5(data+self.tmd5)
                    })*/
        $arr = array();     //查询结果
        $arr2 = array();
        $arr_pre1 = array(); //存储查询参数
        $arr_pre2 = array(); //存储默认flag

        $tid = $this->tokenid;
        $apicode = $this->apicode;
        //$alone_module_array=array('TelCheck','TelPeriod'); TelCheck模块调整为TelCheck_s
        if($this->headerTitle[0]=='TelCheck'){
            $this->headerTitle[0]='TelCheck_s';
        }
        $alone_module_array=array('TelCheck_s','TelPeriod');
        if(in_array($this->headerTitle[0],$alone_module_array)){    //调用单独调用模块
            $url = BaiRong_CONFIG::$data_one_url;
        }else{
            $url = $this->br_data_url;
        }
        $data = $this->userList;

        $headKey = array();

        if(STATUS == 2){
            $meal = '';
        }else{
            $meal = join(',',$this->headerTitle);
        }
        $reserveTitle = array(
            'code',
            'swift_number',
            //'Flag'
        );

        //参数字段需显示在csv 文件中
        foreach ($data as $key => $value) {
            if(STATUS == 2){
                $line_num = $value['line_num'];
            }

            foreach ($value as $key1 => $value1) {
                if($key1 == 'name'){
                    $data[$key][$key1] = $this->replaceLang($value1);
                }

                if($key1 == 'mail'){
                    $data[$key][$key1] = array($value1);
                }

                if($key1 == 'cell'){
                    if(in_array($this->headerTitle[0],$alone_module_array)){
                        $data[$key][$key1] = $value1;
                    }else{
                        $data[$key][$key1] = array($value1);
                    }
                }
                if(STATUS == 2 && preg_match('/meal/',$key1)){
                    $mealArr = explode('|',$value1);
                    $meal = join(',',$mealArr);
                }
            }


            $data[$key]['meal'] = $meal;

            unset($data[$key]['user_id']);
            $postData = array(
                'tokenid' => $tid,
                'interCommand' => '1000',
                'apiCode' => $apicode,
                'jsonData' => json_encode($data[$key]),
                'checkCode' => md5(json_encode($data[$key]).md5($apicode.$tid))
            );
            //查询返回值
            //json string 格式
            $temp_res = Util::post($url,$postData);
            $temp_res_arr = json_decode($temp_res,true);
            //重新登录
            if($temp_res_arr['code'] == 100004){
                $this->login();
                $this->mapping();
                return;
            }

            if(BaiRong_CONFIG::isDebug()){
                echo '<h3>post 参数</h3>';
                var_dump($postData);
                echo '<h3>post 返回值</h3>';
                var_dump($temp_res_arr);
            }

            if(STATUS == 1){

                $result=$temp_res_arr;

                //如果返回非json格式数据或者没有返回则记录警报
                if(empty($temp_res_arr)){
                    Model::factory('Alert')->create(Model_Alert::BaiRong_API_RETURN_ERROR,'百融API非json格式数据或者没有返回','api/BaiRong/RiskService');
                    return array('status'=>false,'msg'=>'返回非json格式数据或没有返回','error'=>'9999');
                }
                if(in_array($this->headerTitle[0],$alone_module_array)){    //调用单独调用模块
                    if($result['code']=='600000') { //接口查询成功
                        return $result;
                        $brlog_data = array('BaiRong','Search','调用百融API接口成功',json_encode($postData),json_encode($result),'BaiRongAPI',$this->user_id,time());
                        $api_out = array('status'=>true,'msg'=>'发送成功','error'=>'0000','api_result'=>array('result'=>$result['code'],'data'=>$result,'msg'=>'调用百融API接口成功'));
                    }else{
                        return $result;
                        $code_info=isset($this->alone_result_code_array[$result['code']])?$this->alone_result_code_array[$result['code']]:$result['code'];
                        $brlog_data = array('BaiRong','Search','调用百融API接口返回：'.$code_info,json_encode($postData),json_encode($result),'BaiRongAPI',$this->user_id,time());
                        $api_out = array('status'=>true,'msg'=>'发送成功','error'=>'0000','api_result'=>array('result'=>$result['code'],'data'=>'',                                            'msg'=>$code_info));
                    }
                }else{
                    if($result['code']=='00') { //接口查询成功
                        return $result;
                        $brlog_data = array('BaiRong','Search','调用百融API接口成功',json_encode($postData),json_encode($result),'BaiRongAPI',$this->user_id,time());
                        $rulestr_array=array(
                            'QJS'=>'RuleSpecialList',
                            'QJF'=>'RuleApplyLoan',
                            'QJE'=>'RuleExecution',
                            'XJA'=>'RuleAccountChange',
                            'XJS'=>'RuleScore',
                            'QJG'=>'RuleEquipmentCheck',
                            'QJW'=>'RuleLoan_web',
                            'QJA'=>'RuleLoan_android',
                            'QJI'=>'RuleLoan_ios',
                            'QZW'=>'RuleRegister_web',
                            'QZA'=>'RuleRegister_android',
                            'QZI'=>'RuleRegister_ios',
                            'QDW'=>'RuleLog_web',
                            'QDA'=>'RuleLog_android',
                            'QDI'=>'RuleLog_ios'
                        );
                        $rulestr_array2=array(
                            'sl_'	=>'SpecialList_c',
                            'al_'	=>'ApplyLoan',
                            'le_'	=>'LoanEquipment',
                            're_'	=>'RegisterEquipment',
                            'se_'	=>'SignEquipment',
                            'eqc_'	=>'EquipmentCheck',
                            'ex_'	=>'Execution',
                            'stab_'	=>'Stability_c',
                            'cons_'	=>'Consumption_c',
                            'media_'=>'Media_c',
                            'acd_'	=>'AccountChangeDer',
                            'pc_'	=>'PayConsumption',
                        );
                        $result=$this->rule_array_move($result,$rulestr_array,$rulestr_array2);
                        return $result;
                        $api_out = array('status'=>true,'msg'=>'发送成功','error'=>'0000','api_result'=>array('result'=>$result['code'],'data'=>$result,'msg'=>'调用百融API接口成功'));
                    }else{
                        $brlog_data = array('BaiRong','Search','调用百融API接口返回：'.$this->result_code_array[$result['code']],json_encode($postData),json_encode($result),'BaiRongAPI',$this->user_id,time());
                        $api_out = array('status'=>true,'msg'=>'发送成功','error'=>'0000','api_result'=>array('result'=>$result['code'],'data'=>'',
                            'msg'=>$this->result_code_array[$result['code']]));
                    }
                }
                $brlog_map = array('provider', 'action', 'msg', 'req_data', 'resp_data', 'type','reference_id','create_time');
                DB::insert('api_out_log', $brlog_map)->values($brlog_data)->execute();
                return $api_out;
                //加入csv默认需要显示的字段
                //$temp_res_arr = array_merge(BaiRong_CONFIG::defaultFlagTitle(),$temp_res_arr);
                //$this->ctitle = array_merge($value,BaiRong_CONFIG::fixSortTitle());
                //$temp_res_arr = array_merge($value,$temp_res_arr);
                //array_push($arr,$temp_res_arr);
            }else{
                //测试环境
                array_push($arr,$line_num.$temp_res);
            }

            //array_push($arr2,$temp_res);
        }

        //echo 'array_push($arr,$temp_res)';
        //echo '<h3>209</h3>';
        //var_dump($arr);
        //exit;

        if(STATUS == 2){
            $this->createTxt($arr);
        }else{
            $this->createCSV($arr);
        }
        //echo '<pre>';
        //var_dump($temp_res_arr);
    }

    private function validator($arr){

        //echo '<pre>--validator--';
        //var_dump($arr);
        foreach ($arr as $key => $value) {
            if(empty($value['name'])){
                $this->pass = false;
                echo '<h3>提示:name为必填字段</h3>';
                break;
            }
            if(empty($value['cell'])){
                $this->pass = false;
                echo '<h3>提示:cell为必填字段</h3>';
                break;
            }
            if(empty($value['id'])){
                $this->pass = false;
                echo '<h3>提示:id为必填字段</h3>';
                break;
            }
        }
        return $this->pass;
    }

    function pushTargetList($targetList){
        //测试环境data在info.txt中获取
        if(STATUS == 2){
            $targetList = BaiRong_CONFIG::$targetList;
        }

        if($targetList && is_array($targetList)){
            if($this ->validator($targetList)){
                $this->userList = $targetList;
            }
        }
    }

    //生成txt文件
    private function createTxt($arr){
        $filename = 'php_result.txt';

        $myfile = fopen($filename, "w");
        // 添加 BOM 解决中文乱码问题
        //fwrite($myfile, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($myfile, chr(0xEF).chr(0xBB).chr(0xBF));
        foreach($arr as $value){
            fwrite($myfile, $value);
        }


        fclose($myfile);
    }

    //unicode 转 utf-8
    private function replaceLang($str){
        //if(is_string($str)){
        //$str = preg_replace("#\\\u([0-9a-f]+)#ie","iconv('UCS-2BE','UTF-8', pack('H4', '\\1'))",$str);
        return $str;
        //}

        /*

        $str = rawurldecode($str);
        preg_match_all("/(?:%u.{4})|&#x.{4};|&#\d+;|.+/U",$str,$r);
        $ar = $r[0];
        //print_r($ar);
        foreach($ar as $k=>$v) {
            if(substr($v,0,2) == "%u"){
                $ar[$k] = iconv("UCS-2BE","UTF-8",pack("H4",substr($v,-4)));
            }
            elseif(substr($v,0,3) == "&#x"){
                $ar[$k] = iconv("UCS-2BE","UTF-8",pack("H4",substr($v,3,-1)));
            }
            elseif(substr($v,0,2) == "&#") {

                $ar[$k] = iconv("UCS-2BE","UTF-8",pack("n",substr($v,2,-1)));
            }
        }
        return join("",$ar);

        */

    }

    /*
    处理array(
        array("key"=>"asdf"),
        array("key1"=>"rwer")
    );
    @return asdf
    */
    private function getHeadKey($arr){
        $headArr = array();
        $values = array();
        foreach($arr as $key=>$val){
            foreach($val as $k=>$v){
                $headArr[$k] = '';
            }
        }

        foreach($arr as $i=>$d){
            $temp = json_encode(array_merge($headArr,$d));
            array_push($values,$temp);
        }
        return $values;
    }

    //生成csv文件
    private function createCSV($arr){
        //$arr = ('code'=>'0','flag_media'=>'0');
        $targetArr = $this->getHeadKey($arr);
        $csv_arr = array();
        //var_dump($targetArr);
        if(is_array($targetArr)){
            foreach ($targetArr as $key => $value) {
                $itemVal = $this->getKeyAndVal($value);
                if($key == 0){
                    array_push($csv_arr, $itemVal[0]);
                    foreach ($itemVal[1] as $k => $v) {
                        if(is_array($v)){
                            $itemVal[1][$k]=json_encode($v);
                        }
                    }
                    array_push($csv_arr, $itemVal[1]);
                }else{
                    foreach ($itemVal[1] as $k => $v) {
                        if(is_array($v)){
                            $itemVal[1][$k]=json_encode($v);
                        }
                    }
                    array_push($csv_arr, $itemVal[1]);
                }
            }
        }
        $filename = 'demo.csv';
        $myfile = fopen($filename, "w");
        // 添加 BOM 解决中文乱码问题
        fwrite($myfile, chr(0xEF).chr(0xBB).chr(0xBF));
        foreach($csv_arr as $value){
            fputcsv($myfile, $value);
        }
        fclose($myfile);
    }

    private function getKeyAndVal($str){
        //echo $str;
        if(is_string($str)){
            //var_dump($str);
            //转数组

            $str = str_replace('[]', '""', $str);


            //打平但未映射数组
            //$newArr = $this->setKeys($arrayData);
            //$newStr = $this->replaceLang(json_encode($newArr));


            //$newStr = $this->replaceLang($str);


            //删除多余废字段
            $newStr = $this ->delGarbageTitle(json_decode($str,true));

            //$fromMapDefaultTitle = $this->getArray($this->headerTitle);


            //加入默认字段
            //$newStr = json_encode(array_merge($fromMapDefaultTitle,json_decode($newStr,true)));


            //$newStr = $this->replaceLang($newStr);
            $newStr = str_replace('\\\\', '\\', $newStr);


            $map = BaiRong_CONFIG::map();

            foreach ($map as $key => $value) {
                $newStr = str_replace('"'.$key.'"', '"'.$value.'"', $newStr);;
                //$newStr = preg_replace('/\"'.$key.'\"/', '"'.$value.'"', $newStr);
            }

            $finalArr = json_decode($newStr,true);

            $finalArr = array_merge($this->ctitle,$finalArr);

            $keys = array();
            $values = array();
            $res = array();

            foreach ($finalArr as $key => $value) {
                array_push($keys, $key);
                array_push($values, $value);
            }

            $res = array(
                $keys,
                $values
            );
            //var_dump($res);
            return $res;
        }
    }

    //删除多余字段
    //从BaiRong_CONFIG::
    private function delGarbageTitle($targetArr){
        if(!is_array($targetArr)){return;}
        $arr = BaiRong_CONFIG::garbageTitle();

        //echo 'delGarbageTitle<br />';
        //echo($targetArr);
        $res = array();
        foreach ($targetArr as $key => $value) {
            # code...

            if(!in_array($key,$arr)){
                //echo $key.'<br>';
                $res[$key] = $value;
            }

        }
        return json_encode($res);
    }
}