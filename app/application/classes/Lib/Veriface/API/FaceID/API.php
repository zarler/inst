<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/7/18
 * Time: 下午5:55
 */
include_once('api/facepp_sdk.php');
class Lib_Veriface_API_FaceID_API
{

    private $FaceID;

    function __construct () {
        $config = Kohana::$config->load('api')->get('FaceID');
        define('SERVER',$config['server']);
        define('SERVER_V2',$config['server_v2']);
        define('APP_KEY',$config['api_key']);
        define('APP_SECRET',$config['api_secret']);
        $this->FaceID = new Facepp();
    }

    public function add($data){
        $result = $this->FaceID->execute('/detect',$data);
        return $result;
    }

    public function verify($data){
        $result = $this->FaceID->megvii_execute('/verify',$data);
        return $result;
    }

    public function raw_verify($data){
        $result = $this->FaceID->megvii_raw_execute('/verify',$data);
        return $result;
    }

    public function faceid_verify($data){
        $result = $this->FaceID->faceid_execute('/verify',$data);
        return $result;
    }
}