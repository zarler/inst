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
    public function getApiArrays( $module , $mode , $getArray=array() , $postArray=array() ) {
        $apiStr= Kohana::$config->load('url.communic_url.timecash_api').$module.'/'.$mode.'/';
        $ListJson = HttpClient::factory($apiStr)->get($getArray)->post($postArray)->execute()->body();
        //超时判断
        Tool::factory('Debug')->array2file(array($apiStr,$postArray,$ListJson), APPPATH.'../static/liu_test.php');
        if($ListJson){
            $ListArray = json_decode($ListJson,true);
        }else{
            if($ListJson === false){
                //超时
                $ListArray = array('code'=>'5079','message'=>Kohana::message('wx','timeout'));
            }else{
                $ListArray = array('code'=>'5080','message'=>Kohana::message('wx','404_error'));
            }
        }
        return $ListArray;
    }
}



