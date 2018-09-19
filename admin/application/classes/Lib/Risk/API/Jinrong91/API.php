<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by IntelliJ IDEA.
 * User: yangyuexin
 * Date: 2018/1/10
 * Time: 下午2:15
 */
class Lib_Risk_API_Jinrong91_API extends Lib_Common
{
    /**
     * 获取trxno
     * @param $request
     * @return bool|mixed|string
     */
    public function gettrxno($request)
    {
        if (!isset($request['name'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 name');
        }
        if (!isset($request['idcard'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 idcard');
        }
        $re= new Lib_Risk_API_Jinrong91_API_post();
        $result = $re->getinfo($request['name'],$request['idcard']);

        if($result!=''&&$result!=null){
            $result=json_decode($result,true);
            //插入查询表
            $array=['trxno'=>$result["trxNo"],'name'=>$request['name'],'identity_code'=>$request['idcard'],'user_id'=>$request['user_id'],'create_time'=>time(),'selectstate'=>0,'trxnostate'=>0];
            $insert_id=Model::factory('Jinrong91_Data')->insert($array);
            if($insert_id>0){
                return $insert_id;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 通过trxno获取内容
     * @param $request
     * @return bool|mixed|string
     */
    public function getinfo($request)
    {
        if (!isset($request['trxno'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 trxno');
        }
        $re= new Lib_Risk_API_Jinrong91_API_post();
        $result = $re->getinfoactive($request['trxno']);

        if($result!=''&&$result!=null){
            $result=json_decode($result,true);
            $result['infoId']='1234567';
            $result['trxNo'];
            $result['userInfo']=['name'=>'陈水金','identity_code'=>'350424198301082038','user_id'=>'12345678'];
            return $result;
        }else{
            return false;
        }
    }


}