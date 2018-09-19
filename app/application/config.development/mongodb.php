<?php defined('SYSPATH') or die();

return [

    'tcapi' => [
        'connect' => true,
        'hosts' => [
            ['host' => 'dds-2ze0e5db0aad96d41.mongodb.rds.aliyuncs.com', 'port' => 3717],
            ['host' => 'dds-2ze0e5db0aad96d42.mongodb.rds.aliyuncs.com', 'port' => 3717],
        ],
        'database' => 'tcapi',
        'username' => 'tc_api_prod',
        'password' => 'Ik00oP3bVzacXtJ4',
        'replicaSet' => 'mgset-4893985',
    ],
    /*'default' =>[
        'host' => '114.55.103.7',
        'port' => 27017,
        'database' => 'timecash',
        'username' => 'timecash',
        'password' => 'uxgfJUNNEhM8ZNUH',
	],

    'tcapi' => array(
        'host' => '114.55.103.7',
        'port' => 27017,
        'database' => 'tcapi',
        'username' => 'tc_api_test',
        'password' => 'KPtFjZODgtTLq1my',
    ),
*/

/*
 * 单机版
    'tcapi' => array(
        'host' => '114.55.103.7',
        'port' => 27017,
        'database' => 'tcapi',
        'username' => 'tc_api_test',
        'password' => 'KPtFjZODgtTLq1my',
    ),
 *
 *
 * 主从集群版
    'tcapi' => [
        'hosts' => [
            ['host' => 'dds-2ze0e5db0aad96d41.mongodb.rds.aliyuncs.com', 'port' => 3717],
            ['host' => 'dds-2ze0e5db0aad96d42.mongodb.rds.aliyuncs.com', 'port' => 3717],
        ],
        'database' => 'tcapi',
        'username' => 'tc_api_prod',
        'password' => 'Ik00oP3bVzacXtJ4',
        'replicaSet' => 'mgset-4893985',
    ],
*/

];