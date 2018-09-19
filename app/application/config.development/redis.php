<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'default' => array
	(
		'connection' => array(
			'hostname'   => 'test23.capi.timecash.cn',
			'port'		 => 6379,
			'timeout'    => 3.0,
			'password'   => 'timecash2016',
			'persistent' => FALSE,
		),
		'charset'      => 'utf8',
		'caching'      => FALSE,
	)
);
