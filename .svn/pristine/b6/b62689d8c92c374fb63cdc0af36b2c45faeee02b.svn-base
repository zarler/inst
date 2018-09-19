<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Lib_PHPMailer_PHPMailer
{

    protected $phpMailer;


    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->phpMailer = new PHPMailer(true);
        $config = Kohana::$config->load("mail.mailer");
        //Server settings
        $this->phpMailer->CharSet = "UTF-8";                    //set body coding as UTF-8
        $this->phpMailer->SMTPDebug = $config['SMTPDebug'];                                 // Enable verbose debug output
        $this->phpMailer->isSMTP();                                      // Set this->mailer to use SMTP
        $this->phpMailer->SMTPAuth = $config['SMTPDebug'];                               // Enable SMTP authentication
        $this->phpMailer->Host = $config['Host'];  // Specify main and backup SMTP servers
        $this->phpMailer->Port = $config['Port'];                                    // TCP port to connect to
        $this->phpMailer->Username = $config['Username'];                 // SMTP username
        $this->phpMailer->Password = $config['Password'];                           // SMTP password
        $this->phpMailer->SMTPSecure = $config['SMTPSecure'];                            // Enable TLS encryption, `ssl` also accepted
        $this->phpMailer->setFrom($config['From'], $config['FromName']);
        return $this;
    }

    public function addAddress($address, $name = '')
    {
        $res = $this->phpMailer->addAddress($address, $name = '');
        return $this;
    }

    public function addReplyTo($address, $name = '')
    {
        $res = $this->phpMailer->addReplyTo($address, $name = '');
        return $this;
    }

    public function addCC($address, $name = '')
    {
        $res = $this->phpMailer->addCC($address, $name = '');
        return $this;
    }

    public function addBCC($address, $name = '')
    {
        $res = $this->phpMailer->addBCC($address, $name = '');
        return $this;
    }

    public function addAttachment($path, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment')
    {
        $res = $this->phpMailer->addAttachment($path, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment');
        return $this;
    }

    public function addSubject($subject)
    {
        $this->phpMailer->Subject = "=?utf-8?B?".base64_encode($subject)."?=";//set subject coding as UTF-8
        return $this;
    }

    public function addBody($body)
    {
        $this->phpMailer->Body = $body;
        return $this;
    }

    public function send()
    {
        $res = $this->phpMailer->send();
        return $this;
    }

}
