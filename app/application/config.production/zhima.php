<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * 芝麻信用配置信息
 * Created by PhpStorm.
 * User: caojiabin
 * Date: 16/6/19
 * Time: 下午3:30
 */
return array(

	'gatewayUrl' 		=> 'https://zmopenapi.zmxy.com.cn/openapi.do',
	'appId' 			=> '1000481',
	'charset' 			=> 'UTF-8',
	'shPrivateKeyFile' 	=> DOCROOT.'application/classes/Lib/ZhiMaCredit/KeyFile/sh_private_key.pem',
	'zmPublicKeyFile' 	=> DOCROOT.'application/classes/Lib/ZhiMaCredit/KeyFile/zm_public_key.pem',
);