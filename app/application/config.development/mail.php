<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Configuration for the mail module.
 *
 * @package   Mail
 * @author    Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2013, Hète.ca Inc.
 * @license   BSD-3-Clauses
 */
return array(
		'default' => array(
			/**
			 * Mail: simple mail() function wrapper.
			 *
			 * You will need the PEAR Mail package installed on your computer if
			 * you want to use any of the following mailer.
			 *
			 * PEAR_Sendmail: send mail through a sendmail server using PEAR.
			 *
			 * PEAR_SMTP: send mail through smtp protocol using PEAR.
			 *
			 * PEAR_Mail: PEAR wrapper for the mail() function.
			 *
			 * PHPMailer: send mail using PHPMailer.
			 */
				'sender' => 'PHPMailer_SMTP',
			/**
			 * Initialize headers.
			 */
				'headers' => array(),
			/**
			 * Options for the sender.
			 *
			 * For PEAR senders options, refer to their official documentation at http://pear.php.net/manual/en/package.mail.mail.factory.php
			 */
				'options' => array(
						'SMTPAuth' => TRUE,
						'Host'     => 'smtp.mxhichina.com',
						'Port'     => 465,
						'Username' => 'report@timecash.cn',
						'Password' => '1T2i3m4e5c6a7s8h9',
						'SMTPSecure' => 'ssl',
						'CharSet'	=> 'UTF-8',
						'From'		=> 'report@timecash.cn',
						'FromName'	=>	'快金日报',
					//'SMTPDebug'		=> 1,
						'Helo'		=> 'Hello smtp.mxhichina.com Server',


					/**
					 * Mail
					 *
					 * Specify an array of commands. They will be automatically joined
					 * by a space character.
					 *
					 * 'option_1', 'option_2',
					 */
					/**
					 * PEAR_Sendmail
					 *
					 * 'sendmail_path' => '/usr/bin/sendmail',
					 * 'sendmail_args' => '-i',
					 */
					/**
					 * PEAR_SMTP
					 *
					 * 'host' => 'localhost',
					 * 'port' => 25,
					 * 'auth' => FALSE,
					 * 'username' => NULL,
					 * 'password' => NULL,
					 * 'localhost' => 'localhost',
					 * 'timeout' => NULL,
					 * 'verp' => FALSE,
					 * 'debug' => FALSE,
					 * 'persist' => NULL,
					 * 'pipelining' => NULL
					 */
					/**
					 * PHPMailer_SMTP
					 *
					 * @link https://github.com/PHPMailer/PHPMailer
					 *
					 * 'SMTPAuth' => TRUE
					 * 'Host'     => 'localhost',
					 * 'Port'     => 26,
					 * 'Username' => NULL,
					 * 'Password' => NULL
					 */
				)
		),
		'sender' => array(
				'sender' => 'PHPMailer_SMTP',
				'headers' => array(),
				'options' => array(
						'SMTPAuth' => TRUE,
						'Host'     => 'smtp.mxhichina.com',
						'Port'     => 465,
						'Username' => 'report@timecash.cn',
						'Password' => '1T2i3m4e5c6a7s8h9',
						'SMTPSecure' => 'ssl',
						'CharSet'	=> 'UTF-8',
						'From'		=> 'report@timecash.cn',
						'FromName'	=>	'快金',
					//'SMTPDebug'		=> 1,
						'Helo'		=> 'Hello smtp.mxhichina.com Server',
				)
		)
);