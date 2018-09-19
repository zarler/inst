<?php defined('SYSPATH') or die('No direct script access.');



class Controller_Demo extends Controller {

    public function action_Model()
    {
        $model = Model::factory("Demo");
        var_dump($model);exit;
    }


    public function action_CreateFile()
    {
        // 根据绝对路径创建文件,目录不存在则先创建目录
        $file = DOCROOT."/upload/aaaa/bbbb/cccc/dddd.txt";
        $res = Lib::factory('Filesystem_Filesystem')->init()->addFile($file);
        var_dump($res);exit;
    }

    public function action_SendMail()
    {
        // mail
        $mailer = Lib::factory('PHPMailer_PHPMailer')->init()
            ->addAddress('zhang.miao@timecash.cn', 'zhang.miao')
            ->addReplyTo('info@example.com', 'Information')
            ->addCC('info@example.com', 'Information')
            ->addBCC('info@example.com', 'Information')
            ->addSubject(date("Y-m-d H:i:s"))
            ->addBody('This is the HTML message body <b>in bold!</b>')
            ->addAttachment(DOCROOT.'/upload/overdue.csv')
            ->send();
        // var_dump($mailer);exit;
    }

    public function action_Valid()
    {
        $a = Valid::not_empty("aaaa");
        print_R($a);
    }

} // End Welcome
