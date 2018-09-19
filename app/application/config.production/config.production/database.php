<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'default' => array(
        'type'          => 'PDO',
        'connection'    => array(
            'dsn'   => 'mysql:host=rm-2ze5n27f3kej99xxe.mysql.rds.aliyuncs.com;dbname=inst',
            'database'  => 'inst',
            'username'  => 'inst_prod',
            'password'  => 'WXzasxbYyed2TV0o',
            'persistent'=> FALSE,
            'options' => NULL,
        ),
        'table_prefix'  => 'tc_',
        'charset'       => 'utf8',
        'caching'       => FALSE,
        'profiling'     => TRUE
    ),

);
