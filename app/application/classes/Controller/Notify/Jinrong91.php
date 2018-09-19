<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: yangyuexin
 * Date: 18/1/31
 * Time: 下午5:08
 *
 * 借款订单
 *
 */
class Controller_Notify_Jinrong91 extends Controller
{
    private $baseArray = array(
        // 版本号
        "version" => "01",
        // 信息源（提交时请联系91金融获取）
        "custNo" => '',
        // 01.UTF8 02.GBK
        "encode" => '01',
        "trxCode"=>'',
        // 加密类型 01.不加密 02.RSA
        "encryptType" => '01',
        // 01.JSON 02.XML 03.Protobuf
        'msgType' => '01',
        'msgBody' => '',
        'retCode' => '',
        'retMsg' => '',
        'sign' => ''
    );
    /**
     *91金融返回查询数据（3002）和查询我司业务数据（3001）均使用此接口
     *
     */
    public function action_CallBack(){
        $data = file_get_contents('php://input');
        $dataArray = explode("|",$data);
        $trxCode = $dataArray[3];
        $msgBody =  json_decode(base64_decode($dataArray[6]),true);
        header("Content-type:text/html;charset=utf-8");
        switch ($trxCode) {
            case '3001':
                $realName = $msgBody['realName'];
                $idCard = $msgBody['idCard'];
                $companyCode = $msgBody['companyCode'];
                //M('chaxun')->add($cha_data);
                $this->seachData($realName,$idCard,$companyCode);
                break;
            case '3002':
                $trxNo = $msgBody['trxNo'];
                $borrowInfo_array = $msgBody['loanInfos'];
                $this->intAdd($trxNo,$borrowInfo_array);
                break;
            default:
                exit('您的操作发生了异常！');
                break;
        }
    }
    // 返回查询数据 3001
    public function seachData($realName,$idCard,$companyCode){
        //$borrowInfo = Model::factory('Jinrong91_Loanlist')->get_one($realName,$idCard);
        $borrowInfo = null;

        if (empty($borrowInfo)) {
            $identity_array=[];
            $identity_array['loanInfos'] = [];
            $msgBody = base64_encode(json_encode($identity_array));
        }else{
            $this->baseArray['trxCode'] = 4001;
            $identity_array=[];
            $identity_array['loanInfos'][] = array(
                "borrowType"=>$borrowInfo['borrowType'],
                "borrowState"=>$borrowInfo['borrowState'],
                "borrowAmount"=>$borrowInfo['borrowAmount'],
                "contractDate"=>$borrowInfo['contractDate'].'000',
                "loanPeriod"=>$borrowInfo['loanPeriod'],
                "repayState"=>$borrowInfo['repayState'],
                "arrearsAmount"=>$borrowInfo['arrearsAmount'],
                "companyCode"=>Lib_Risk_API_Jinrong91_API_config::$namecode,
            );
            $msgBody = base64_encode(json_encode($identity_array));
        }
        $this->baseArray['custNo'] = Lib_Risk_API_Jinrong91_API_config::$namecode;
        $this->baseArray['msgBody'] = $msgBody;
        $this->baseArray['retCode'] = '0000';
        $this->baseArray['sign'] = Lib_Risk_API_Jinrong91_API_config::$sign;

        $baseArray = implode('|',$this->baseArray);
        //$this->test('baseArray',$baseArray);
        exit($baseArray);
    }
    // 插入返回的查询数据
    public function intAdd($trxNo,$borrowInfo_array){
        $result=Model::factory('Jinrong91_DataItem')->getOneByTrxno($trxNo);
        if (empty($result)) {
            $now=date('Y-m-d H:i:s', time());
            foreach ($borrowInfo_array as $key => $value) {
                $value =array_change_key_case($value);
                $value['trxno'] = $trxNo;
                $value['created_time'] = $now;
                $insertid=Model::factory('Jinrong91_DataItem')->insert($value);
                //查找查询表里的姓名和身份证进行更新
                $info= Model::factory('Jinrong91_Data')->getOneByTrxno($trxNo);
                $array=['name'=>$info['name'],'identity_code'=>$info['identity_code'],'user_id'=>$info['user_id']];
                Model::factory('Jinrong91_DataItem')->update($insertid,$array);

            }
            $find= Model::factory('Jinrong91_Data')->getOneByTrxno($trxNo);
            $update=['selectstate'=>1];
            Model::factory('Jinrong91_Data')->update($find['id'],$update);
        }
        $this->baseArray['custNo'] = Lib_Risk_API_Jinrong91_API_config::$namecode;
        $this->baseArray['trxCode'] = 4002;
        $this->baseArray['msgBody'] = '';
        $this->baseArray['retCode'] = '0000';
        $this->baseArray['sign'] = Lib_Risk_API_Jinrong91_API_config::$sign;
        $baseArray = implode('|',$this->baseArray);
        exit($baseArray);
    }



}


