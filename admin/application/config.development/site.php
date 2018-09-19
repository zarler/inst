<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * 站点配置 config 写在这里面可多纬
 */
return array(

	'default' => array(
		'security_path' => APPPATH.'../../protected/',			//隐私文件存放目录
		'static_path' => APPPATH.'../static/',			//静态文件存放目录

		'api_site'	=> 'http://test23.api.timecash.cn',			//API站点
		'api_logs_path' => APPPATH.'logs/tcapi/',	//API日志

		//api.timecash.cn
		'api_app_id'	=> 'Admin',							//CLIENTID
		'api_app_secret'	=> 'yfKSCEzojXSPTGGGyIQJeOjVvcxTOjTr',		//CLIENT_KEY 配置client键值

		//ps.timecash.cn
		'ps_site'	=> 'http://test23.ps.timecash.cn',			//PS站点
		'client_id'	=> 'admin',							//APP_ID
		'client_key'	=> 'xqP8MuCMZEhJuOaSbL',		//APP_KEY

		//合同保存地址
		'cfca_path' => APPPATH.'../../cfca/upload/',			//隐私文件存放目录


		'baidu_api_key'	=> '1b75c635c2575a281c4b2e3a0bed611b',		//百度通用apikey，用于身份证查地区，手机号查地区等API


	),

    'wx' => array(
        'app_id' =>     'wxb6606932ddd5f1fa',			//微信 appId
        'app_secret' => '12b55a75c9af1d9a0855d11c49473c36',			//微信 appSecret
        'api_url'	=>  'https://api.weixin.qq.com',		//API站点

    ),

    'supervise' => array(
        'app_id' => 'timecash',
        'app_key' => 'F254A8A520E168617A62A9ADB05D6D8E',
        'api_url' => 'http://test.supervise.timecash.cn',
    ),





);

