<?php defined('SYSPATH') OR die('No direct script access.');


return array(

    'native' => array(
        'name' => 'PHPSESSID',
        'lifetime' => 43200,
    ),

    'cookie' => array(
        'name' => 'PHPSESSID',
        'encrypted' => FALSE,
        'lifetime' => 43200,
    ),

    'database' => array(
        'name' => 'PHPSESSID',
        'encrypted' => FALSE,
        'lifetime' => 43200,
        'group' => 'admin',//database.php:group=admin
        'table' => 'session',//tc_admin_session
        'columns' => array(
            'session_id'  => 'session_id',
            'last_active' => 'last_active',
            'contents'    => 'contents'
        ),
        'gc' => 500,
    ),
);
