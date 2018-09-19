<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(

    //正式
    'default' => array
    (
        'connection' => array(
            'hostname'   => 'r-2zebd5e1ecbe05d4.redis.rds.aliyuncs.com',
            'port'	 => 6379,
            'timeout'    => 3.0,
            'password'   => 'rw1DD7mDGLScH1VB',
            'persistent' => FALSE,
        ),
        'charset'      => 'utf8',
        'caching'      => FALSE,
    )
);
