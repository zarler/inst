<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: liujinsheng
 * Date: 16/2/15
 * Time: 下午6:33
 * 调接口类
 */
class Tool_API{
    public function getApiArrays( $module , $mode , $getArray=array() , $postArray=array(),$v = null) {
        $communicUrl= Kohana::$config->load('url.communic_url');
        $communicUrl_api = $communicUrl['timecash_api'].$module.'/'.$mode.'/';

//        $apiStr= Kohana::$config->load('url.communic_url.timecash_api').$module.'/'.$mode.'/';

        $ListJson = HttpClient::factory($communicUrl_api)->get($getArray)->post($postArray)->execute()->body();

        //Tool::factory('Debug')->D($ListJson);
//        Tool::factory('Debug')->array2file(array($apiStr,$getArray,$postArray,$ListJson), APPPATH.'../static/liu_test.php');
        if(!empty($v)){
            Tool::factory('Debug')->array2file(array($communicUrl_api,$postArray,$ListJson), APPPATH.'../static/liu_test.php');

        }
        if(empty($ListJson) ){
            $ListArray = array('code'=>'5080','message'=>Kohana::message('wx','404_error'));
        }else{
            $ListArray = json_decode($ListJson,true);
            //保存接口调取数据
            $req_data = isset($postArray['json'])?$postArray['json']:NULL;
            //unset($ListArray['result']);
            Model::factory('Home')->insert_log(array('controller'=>$module,'action'=>$mode,'status'=>$ListArray['code'],'msg'=>$ListArray['message'],'req_data'=>$req_data,'resp_data'=>''));
        }
        return $ListArray;
    }
    //适应v1版本
    public function getApiArraysVersion( $module , $mode , $getArray=array() , $postArray=array(),$v = null) {
        $apiStr= Kohana::$config->load('url.communic_url.timecash_api_v').$module.'/'.$mode.'/';
        $ListJson = HttpClient::factory($apiStr)->get($getArray)->post($postArray)->execute()->body();
        if(!empty($v)){
            Tool::factory('Debug')->array2file(array($apiStr,$postArray,$ListJson), APPPATH.'../static/liu_test.php');
        }
        if(empty($ListJson) ){
            $ListArray = array('code'=>'5080','message'=>Kohana::message('wx','404_error'));
        }else{
            $ListArray = json_decode($ListJson,true);
            //保存接口调取数据
            $req_data = isset($postArray['json'])?$postArray['json']:NULL;
            Model::factory('Home')->insert_log(array('controller'=>$module,'action'=>$mode,'status'=>$ListArray['code'],'msg'=>$ListArray['message'],'req_data'=>$req_data,'resp_data'=>json_encode($ListArray['result'])));
        }
        return $ListArray;
    }

    public function getApiArraysUrl( $url, $getArray=array() , $postArray=array(),$v = null) {
//        $apiStr= $url.'?';
//        exit(json_encode(array('status' => false,'msg'=>$apiStr)));

        $ListJson = HttpClient::factory($url)->get($getArray)->post($postArray)->execute()->body();
        if(!empty($v)){
            Tool::factory('Debug')->array2file(array($url,$postArray,$ListJson), APPPATH.'../static/liu_test.php');
        }
        if(empty($ListJson) ){
            $ListArray = array('code'=>'5080','data'=>Kohana::message('wx','404_error'));
        }else{
            $ListArray = array('code'=>'1000','data'=>json_decode($ListJson,true));
            //保存接口调取数据
           // $req_data = isset($postArray['json'])?$postArray['json']:NULL;
//            Model::factory('Home')->insert_log(array('status'=>$ListArray['code'],'msg'=>$ListArray['data'],'req_data'=>$req_data,'resp_data'=>json_encode($ListArray['result'])));
        }
        return $ListArray;
    }
    public function getApiArraysOld( $module , $mode , $getArray=array() , $postArray=array(),$v = null) {
        $communicUrl= Kohana::$config->load('url.communic_url');
        $communicUrl_in = $communicUrl['timecash_api_in'].$module.'/'.$mode.'/';
        //$apiStr= 'http://wx.capi.timecash.cn/'.$module.'/'.$mode.'/';
        $ListJson = HttpClient::factory($communicUrl_in)->get()->post($postArray)->execute()->body();
        //Tool::factory('Debug')->D($ListJson);
//        Tool::factory('Debug')->array2file(array($apiStr,$getArray,$postArray,$ListJson), APPPATH.'../static/liu_test.php');
        if(!empty($v)){
            Tool::factory('Debug')->array2file(array($communicUrl_in,$getArray,$postArray,$ListJson), APPPATH.'../static/liu_test.php');
        }
        if(empty($ListJson) ){
            $ListArray = array('code'=>'5080','message'=>Kohana::message('wx','404_error'));
        }else{
            $ListArray = json_decode($ListJson,true);
            //保存接口调取数据
            $req_data = isset($postArray['json'])?$postArray['json']:NULL;
            //unset($ListArray['result']);
            Model::factory('Home')->insert_log(array('controller'=>$module,'action'=>$mode,'status'=>$ListArray['code'],'msg'=>$ListArray['message'],'req_data'=>$req_data,'resp_data'=>''));
        }
        return $ListArray;
    }
}