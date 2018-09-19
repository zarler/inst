<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'default' => array(
        'type'          => 'PDO',
        'connection'    => array(
            'dsn'   => 'mysql:host=127.0.0.1;port=45678;dbname=timecash',
            'username'  => 'kuaijin_read',
            'password'  => 'XNQOByaepmpHUrHZ',
            'persistent'=> FALSE,
            'options' => NULL,
        ),
        'table_prefix'  => 'tc_',
        'charset'       => 'utf8',
        'caching'       => FALSE,
        'profiling'     => TRUE
    ),
    'admin' => array(
        'type'          => 'PDO',
        'connection'    => array(
            'dsn'   => 'mysql:host=127.0.0.1;port=45678;dbname=timecash',
            'username'  => 'kuaijin_read',
            'password'  => 'XNQOByaepmpHUrHZ',
            'persistent'=> FALSE,
            'options' => NULL,
        ),
        'table_prefix'  => 'tc_admin_',
        'charset'       => 'utf8',
        'caching'       => FALSE,
        'profiling'     => TRUE
    ),
    'cfca' => array(
            'type'          => 'PDO',
            'connection'    => array(
                'dsn'   => 'mysql:host=127.0.0.1;port=45678;dbname=timecash',
                'username'  => 'kuaijin_read',
                'password'  => 'XNQOByaepmpHUrHZ',
                'persistent'=> FALSE,
                'options' => NULL,
            ),
            'table_prefix'  => '',
            'charset'       => 'utf8',
            'caching'       => FALSE,
            'profiling'     => TRUE
    ),
    'wx' => array(
        'type'          => 'PDO',
        'connection'    => array(
            'dsn'   => 'mysql:host=127.0.0.1;port=45678;dbname=timecash_wx',
            'username'  => 'kuaijin_read',
            'password'  => 'XNQOByaepmpHUrHZ',
            'persistent'=> FALSE,
            'options' => NULL,
        ),
        'table_prefix'  => 'tcwx_',
        'charset'       => 'utf8',
        'caching'       => FALSE,
        'profiling'     => TRUE
    ),
    'partner_rong360' => array(
        'type'          => 'PDO',
        'connection'    => array(
            'dsn'   => 'mysql:host=127.0.0.1;port=45678;dbname=timecash',
            'username'  => 'kuaijin_read',
            'password'  => 'XNQOByaepmpHUrHZ',
            'persistent'=> FALSE,
            'options' => NULL,
        ),
        'table_prefix'  => 'tc_',
        'charset'       => 'utf8',
        'caching'       => FALSE,
        'profiling'     => TRUE
    ),
    'partner_51loan' => array(
        'type'          => 'PDO',
        'connection'    => array(
            'dsn'   => 'mysql:host=127.0.0.1;port=45678;dbname=timecash',
            'username'  => 'kuaijin_read',
            'password'  => 'XNQOByaepmpHUrHZ',
            'persistent'=> FALSE,
            'options' => NULL,
        ),
        'table_prefix'  => 'tc_',
        'charset'       => 'utf8',
        'caching'       => FALSE,
        'profiling'     => TRUE
    ),


);
