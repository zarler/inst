<?php
defined('SYSPATH') or die('No direct script access.');
return array(
	'default' => array(
		'type'			=> 'PDO',
		'connection'	=> array(
            'dsn'	=> 'mysql:host=114.55.103.7;dbname=inst',
			'username'	=> 'inst_test',
			'password'	=> '7xI2LocCN3yx3Rju',
			'persistent'=> FALSE,
            'options' => NULL,
		),
		'table_prefix'	=> 'tc_',
		'charset'		=> 'utf8',
		'caching'		=> FALSE,
		'profiling'		=> TRUE
	),
	'admin' => array(
		'type'			=> 'PDO',
		'connection'	=> array(
			'dsn'	=> 'mysql:host=114.55.103.7;dbname=inst',
			'username'	=> 'inst_test',
			'password'	=> '7xI2LocCN3yx3Rju',
			'persistent'=> FALSE,
			'options' => NULL,
		),
		'table_prefix'	=> 'tc_admin_',
		'charset'		=> 'utf8',
		'caching'		=> FALSE,
		'profiling'		=> TRUE
	),
	'cfca' => array(
            'type'			=> 'PDO',
            'connection'	=> array(
                'dsn'	=> 'mysql:host=114.55.103.7;dbname=inst',
                'username'	=> 'inst_test',
                'password'	=> '7xI2LocCN3yx3Rju',
                'persistent'=> FALSE,
                'options' => NULL,
            ),
            'table_prefix'	=> '',
            'charset'		=> 'utf8',
            'caching'		=> FALSE,
            'profiling'		=> TRUE
	),
    'wx' => array(
        'type'          => 'PDO',
        'connection'    => array(
            'dsn'   => 'mysql:host=114.55.103.7;dbname=inst_wx',
            'username'  => 'inst_test',
            'password'  => '7xI2LocCN3yx3Rju',
            'persistent'=> FALSE,
            'options' => NULL,
        ),
        'table_prefix'  => 'tcwx_',
        'charset'       => 'utf8',
        'caching'       => FALSE,
        'profiling'     => TRUE
    ),
	'test' => array(
		'type'			=> 'MySQL',
		'connection'	=> array(
			'hostname'	=> 'localhost',
			'database'	=> 'test',
			'username'	=> 'root',
			'password'	=> '',
			'persistent'=> FALSE
		),
		'table_prefix'	=> '',
		'charset'		=> 'utf8',
		'caching'		=> FALSE,
		'profiling'		=> TRUE
	),


);
