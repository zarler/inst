<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(

    //大内存reids
    'timecash22' => array
    (
        'connection' => array(
            'hostname'   => '101.201.208.151',
            'port'		 => 6379,
            'timeout'    => 3.0,
            'password'   => 'timecash2016',
            'persistent' => FALSE,
        ),
        'charset'      => 'utf8',
        'caching'      => FALSE,
    ),
    //正式
    'default' => array
    (
        'connection' => array(
            'hostname'   => 'd218c449513c4b98.redis.rds.aliyuncs.com',
            'port'		 => 6379,
            'timeout'    => 3.0,
            'password'   => 'KZ2ypnli9cFyxphZ',
            'persistent' => FALSE,
        ),
        'charset'      => 'utf8',
        'caching'      => FALSE,
    )
);
