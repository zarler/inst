<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 17/3/13
 * Time: 下午3:48
 */
require_once APPPATH.'/classes/Lib/JunZiQian/tool/shaUtils.php';
require_once APPPATH.'/classes/Lib/JunZiQian/tool/ropUtils.php';
require_once APPPATH.'/classes/Lib/JunZiQian/model/pingRequest.php';
require_once APPPATH.'/classes/Lib/JunZiQian/model/contractFilePreservationCreateRequest.php';
require_once APPPATH.'/classes/Lib/JunZiQian/model/uploadFile.php';
require_once APPPATH.'/classes/Lib/JunZiQian/model/enum.php';
require_once APPPATH.'/classes/Lib/JunZiQian/model/certificateLinkGetRequest.php';
require_once APPPATH.'/classes/Lib/JunZiQian/model/contractFileDownloadUrlRequest.php';

use org_mapu_themis_rop_tool\RopUtils as RopUtils;
use org_mapu_themis_rop_tool\ShaUtils as ShaUtils;
use org_mapu_themis_rop_model\ContractFilePreservationCreateRequest as ContractFilePreservationCreateRequest;
use org_mapu_themis_rop_model\UploadFile as UploadFile;
use org_mapu_themis_rop_model\PreservationType as PreservationType;
use org_mapu_themis_rop_model\UserIdentiferType as UserIdentiferType;
use org_mapu_themis_rop_model\PingRequest as PingRequest;
use org_mapu_themis_rop_model\CertificateLinkGetRequest as CertificateLinkGetRequest;
use org_mapu_themis_rop_model\ContractFileDownloadUrlRequest as ContractFileDownloadUrlRequest;

class lib_JunZiQian_Contract{

    public function __construct(){
        $this->site_config = Kohana::$config->load('site')->get('default');
    }

    public function ping(){
        //组建请求参数
        $requestObj=new PingRequest();
        $response=RopUtils::doPostByObj($requestObj);
        $responseJson=json_decode($response);
        if($responseJson->success){
            return true;
        }else{
            return false;
        }
    }

    public function CheckUrl($id){
        $requestObj=new CertificateLinkGetRequest();
        $requestObj->preservationId=$id;
        $response=RopUtils::doPostByObj($requestObj);
        $responseJson=json_decode($response);
        if($responseJson->success){
            return $responseJson->link;
        }else{
            return false;
        }
    }
    public function DownLoadContract($id){
        $requestObj=new ContractFileDownloadUrlRequest();
        $requestObj->preservationId=$id;
        $response=RopUtils::doPostByObj($requestObj);
        $responseJson=json_decode($response);
        if($responseJson->success){
            return $responseJson->downUrl;
        }else{
            return false;
        }
    }
}

?>