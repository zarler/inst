<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * 站点配置 config 写在这里面可多纬
 */
return array(

    'api' => array(
        'client_id' => 'Admin',
        'client_key'	=> 'yfKSCEzojXSPTGGGyIQJeOjVvcxTOjTr',
        'log_type' => 'mongodb',
        'logs_path' => APPPATH.'logs/tcapi/',
        'api_url'	=> 'http://api.timecash.cn',
    ),

    'api-pay' => array(
        'client_id' => 'Admin',
        'client_key'	=> 'yfKSCEzojXSPTGGGyIQJeOjVvcxTOjTr',
        'log_type' => 'mongodb',
        'logs_path' => APPPATH.'logs/tcapi/',
        'api_url'	=> 'http://api.timecash.cn',
    ),

);

