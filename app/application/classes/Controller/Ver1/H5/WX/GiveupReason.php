
<?php defined('SYSPATH') or die('No direct script access.');
/*
 * 放弃借款理由
 *  * Tool::factory('Debug')->D($this->controller);
 * Tool::factory('Dir')->dir_path(self::$config['security_path']).$result[0]['uri']);
 * Tool::factory('Debug')->array2file(array(1,2,3,4,5), APPPATH.'../static/liu_test.php');
 *
 * */
    class Controller_Ver1_H5_Wx_GiveupReason extends Common
    {
        //分期下载
        public function action_Index(){
            $this->Load();
            parent::$_VArray['title'] = '放弃借款';
            $view = View::factory($this->_vv.'Loan/GiveupReason');
            $view->_VArray = parent::$_VArray;
            $this->response->body($view);
        }

}