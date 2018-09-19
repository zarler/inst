<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/10
 * Time: 上午10:31
 */
class Lib_Risk_API_Moxie_API extends Lib_Common
{
    /**
     * @param $request
     * @return $result_data
     *
     * 魔蝎接口
     */
    public function html($request)
    {

        $userId = $request['userId'];
        if ($request['action'] == 'Bank') {
            $obj = new Lib_Risk_API_Moxie_API_Bank();
            $result_data = $obj->curlGet(array('userId' => $userId));
        } elseif ($request['action'] == 'Chsi') {
            $obj = new Lib_Risk_API_Moxie_API_Chsi();
            $result_data = $obj->curlGet(array('userId' => $userId));
        } elseif ($request['action'] == 'Email') {
            $obj = new Lib_Risk_API_Moxie_API_Email();
            $result_data = $obj->curlGet(array('userId' => $userId));
        } elseif ($request['action'] == 'Fund') {
            $obj = new Lib_Risk_API_Moxie_API_Fund();
            $result_data = $obj->curlGet(array('userId' => $userId));
        } elseif ($request['action'] == 'JD') {
            $obj = new Lib_Risk_API_Moxie_API_JD();
            $result_data = $obj->curlGet(array('userId' => $userId));
        } elseif ($request['action'] == 'Mno') {
            $obj = new Lib_Risk_API_Moxie_API_Mno();
            $result_data = $obj->curlGet(array('userId' => $userId));
        } elseif ($request['action'] == 'SocialSecurity') {
            $obj = new Lib_Risk_API_Moxie_API_SocialSecurity();
            $result_data = $obj->curlGet(array('userId' => $userId));
        } elseif ($request['action'] == 'Taobao') {
            $obj = new Lib_Risk_API_Moxie_API_Taobao();
            $result_data = $obj->curlGet(array('userId' => $userId));
        } elseif ($request['action'] == 'Zhixing') {
            $obj = new Lib_Risk_API_Moxie_API_Zhixing();
            $result_data = $obj->curlGet(array('userId' => $userId));
        } else {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 action');
        }

        return $result_data;
    }

}