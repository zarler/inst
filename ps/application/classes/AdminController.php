<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 15/12/14
 * Time: 下午1:51
 *
 *
 *
 *可配置变量
 * $not_login_uri:  未登录,跳转地址
 * $permission_array:   controller权限自定义配置数组
 *
 *permission_array 数据库中的权限优先级比配置数组高!!!
 *
 *  map:
 *  关于controller权限映射的理解
 *  如果多个action需要共用一个权限时,可以使用该配置数组,次数组默认不配置.
 *
 *  only_check_login:
 *  如果只需检查是否登录,请把对应action名字写到子数组中, '*'表示所有action.
 *
 *  no_permission_uri:
 *  没权限跳转不同地址,需置该子数组.  数组键名为action名字,键值为跳转地址, '*'表示所有action.
 *
 *
 *
 * 例子:
 *
 protected $permission_array = array(
    'only_check_login' => array('*','OnlyCheckLoginAction','ACTION2',... ),
    'map' => array(
                '数据库中有的ACTIONNAME' => array('适用该权限的其他ACTION名字1',适用该权限的其他ACTION名字2'...  ),
                'List'=>	array('Index','Search','ShowList'),
                'Edit'=>	array('Modify','Update','Change'),
                'Agree'=>	array('AgreeStep1','AgreeStep2',... ),
                ),
    'no_permission_uri' => array( '*'=> '/NoPermission/Index',
                'Action名字'=> '/OtherLogin',... ),

    );
 *
 *  Tool::factory('Debug')->D($this->controller);
 */
class AdminController extends Controller
{
    protected $user = array('id' => 0);
    protected $admin =NULL;
    protected $group_id = array();
    protected $not_login_uri = '/Login';
    protected $permission_array = array(
        'map' => array(),
        'only_check_login' => array(),
        'no_permission_uri' => array(),
        'no_permission_msg' => array('*' => 'no permission'),
    );

    public $_common = array(
        'menu'=>array(
            'tree'=>NULL,
            'data'=>NULL)
    );

    public $post = array();
    public $get = array();
    public $controller = NULL;
    public $action = NULL;
    public $session = NULL;
    public $site_config = NULL;
    public static $config = NULL;
    public $file = NULL;
    public $message = NULL;
    //获取的client验证数组值
    public $client_arr = NULL;



    function before() {
        parent::before();
//        $this->admin = Model::factory('Admin');
//        if (!$this->admin->logged_in()) {
//            HTTP::redirect($this->not_login_uri);
//        }
        $this->controller = Request::current()->controller();
        $this->action = Request::current()->action();
//        $this->user = $this->admin->get_user_sess_array();
//        $this->group_id = $this->group_id();
//        $this->check($this->permission_array);//检查权限
//        //初始化菜单数据
//        $this->_common['menu']['data'] = Model::factory('Admin_Menu')->get_all();
//        $this->_common['menu']['tree'] = Model::Factory('Admin_Menu')->get_tree($this->_common['menu']['data']);
//        Template::bind_global('_common',$this->_common);
//        Template::bind_global('_user',$this->user);
//        Template::bind_global('_group_id',$this->group_id);
//        Template::bind_global('_controller',$this->controller);
//        Template::bind_global('_action',$this->action);
        $this->post = $this->request->post();
        $this->get = $this->request->query();


        //$this->session = $this->admin->session();
        self::$config = Kohana::$config->load('site')->get('default');
        if(!isset(self::$config['security_path']) || empty(self::$config['security_path'])  ) {
            echo 'site config (config/site.php)  security_path is null.';
            exit;
        }
        $this->message = Kohana::message('Upload','message');

    }


    public function user_id() {
        if (isset($this->user['id'])) {
            return $this->user['id'];
        }
        return 0;
    }


    public function group_id() {
        return Model::factory('Admin_Group')->get_group_id_by_user_id($this->user_id());
    }


    //权限检查 : 参数array见文件开始部分注视
    public function check($array = array()) {
        if (isset($this->user['id'])) {
            $main_permission_array = Model::factory('Admin')->get_permission_array($this->user_id());//var_dump($main_permission_array);
            $controller_name = Request::current()->controller();
            $action_name = Request::current()->action();
            if ( isset($array['only_check_login']) && (in_array('*', $array['only_check_login']) || in_array($action_name, $array['only_check_login']))) {//只需检查登录，不用检查权限
                return TRUE;
            }
            if (isset($main_permission_array[$controller_name][$action_name])) {//数据库权限
                return TRUE;
            }
            if (isset($array['map'])) {//action权限映射关系
                foreach ($array['map'] as $k => $v) {
                    if (in_array($action_name, $v)) {
                        return TRUE;
                    }
                }
            }
            if (isset($array['no_permission_uri'][$action_name])) {//自定义跳转
                HTTP::redirect($array['no_permission_uri'][$action_name]);
            } elseif (isset($array['no_permission_uri']['*'])) {
                HTTP::redirect($array['no_permission_uri']['*']);
            }
            if (isset($array['no_permission_msg'][$action_name])) {//自定义信息
                echo $array['no_permission_msg'][$action_name];
                exit;
            } elseif (isset($array['no_permission_msg']['*'])) {
                echo $array['no_permission_msg']['*'];
                exit;
            }
            echo 'no permission';
            exit;
        } else {
            HTTP::redirect($this->not_login_uri);
            exit;
        }
    }

    //存取返回地址 $location TRUE 按未知记录  FALSE通用   |  如果只有第一个参数并且是BOOLEAN值时,认为$url是$location
    //存 $this->backurl(Request::referrer(),TRUE);
    //取 $this->backurl();
    public function backurl($url=NULL,$locaL=FALSE) {
        if(!is_bool($url) && $url !==NULL) {
            if($locaL===TRUE){
                $this->session->set('_backurl_'.$this->controller.'_'.$this->action, $url);
            }else{
                $this->session->set('_backurl', $url);
            }
            return TRUE;
        }
        if($url===TRUE) {
            return $this->session->get('_backurl_'.$this->controller.'_'.$this->action);
        }
        return $this->session->get('_backurl');
    }
    public function backurl_clear($local=FALSE) {
        if($local===TRUE){
            $this->session->delete('_backurl_'.$this->controller.'_'.$this->action);
        }
        $this->session->delete('_backurl');
        return TRUE;
    }

    public function admin(){
        return $this->admin;
    }

//上传各种验证
    public function save_check(){

        //是否为空判断
        if(isset($_SERVER['HTTP_CLIENTSIGN']) && isset($_SERVER['HTTP_CLIENTID']) && $_SERVER['HTTP_CLIENTID'] && $_SERVER['HTTP_CLIENTSIGN']){
        }else{
            $this->_render(array('status'=>false,'msg'=>'请求信息错误'));
        }

        //是否为空判断
        if(!empty($this->post['_file'])){
            $this->file = json_decode($this->post['_file']);
        }else{
            $this->_render(array('status'=>false,'msg'=>'上传数据为空'));
        }

        $this->client_arr = Model::factory('Upload_DbOperation')->get_list($_SERVER['HTTP_CLIENTID']);

        if($this->client_arr['client_key']){
            $check_sign = md5(json_encode($_POST).$this->client_arr['client_key']);

        }else{
            $this->_render(array('status'=>false,'msg'=>'验证信息失败'));
        }
        //CLIENT_SIGN验证
        if($check_sign != $_SERVER['HTTP_CLIENTSIGN']){

            $this->_render(array('status'=>false,'msg'=>'验证信息失败'));
        }
    }
    //上传各种验证
    public function upload_check(){

        //是否为空判断
        if(isset($_SERVER['HTTP_CLIENTSIGN']) && isset($_SERVER['HTTP_CLIENTID']) && $_SERVER['HTTP_CLIENTID'] && $_SERVER['HTTP_CLIENTSIGN']){
        }else{
            $this->_render(array('status'=>false,'msg'=>'请求信息错误'));
        }
        //是否为空判断
        if(!empty($_FILES)){
            $this->file = $_FILES['file'];
        }else{
            $this->_render(array('status'=>false,'msg'=>'上传数据为空'));
        }
        $this->client_arr = Model::factory('Upload_DbOperation')->get_list($_SERVER['HTTP_CLIENTID']);
        if($this->client_arr['client_key']){
            $check_sign = md5(md5(file_get_contents($_FILES['file']['tmp_name'])).$this->client_arr['client_key']);
        }else{
            $this->_render(array('status'=>false,'msg'=>'验证信息失败'));
        }
        //CLIENT_SIGN验证
        if($check_sign != $_SERVER['HTTP_CLIENTSIGN']){
            $this->_render(array('status'=>false,'msg'=>'验证信息失败'));
        }
    }

    //获取文件的时候各种验证
    public function download_check(){
        //是否为空判断
        if(isset($_SERVER['HTTP_CLIENTSIGN']) && isset($_SERVER['HTTP_CLIENTID']) && $_SERVER['HTTP_CLIENTID'] && $_SERVER['HTTP_CLIENTSIGN']){
        }else{

            $this->_render(array('status'=>false,'msg'=>'请求信息错误'));
        }

        //获取client_key值
        $result = Model::factory('Upload_DbOperation')->get_one($_SERVER['HTTP_CLIENTID']);
        if(!empty($result)){
            $check_sign = md5(json_encode($_GET).$result['client_key']);
        }else{
            $this->_render(array('status'=>false,'msg'=>'验证信息失败'));
        }
        //CLIENT_SIGN验证
        if($check_sign != $_SERVER['HTTP_CLIENTSIGN']){
            $this->_render(array('status'=>false,'msg'=>'验证信息失败'));
        }
    }
    /*************************
     *作用：str_replace只更改一个
     *variable： $needle知道更改内容 $replace更改后的内容，$haystack总字符串
     **************************/
    function str_replace_once($needle, $replace, $haystack) {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return $haystack;
        }
        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }
    /**
     * Render Response
     * @access	protected
     * @return	void
     */
    public function _render($data){

        //$this->response->body(json_encode($data))->headers('content-type', 'application/json');
        //记录调用接口信息
        if($this->get || $this->post){
            if(isset($_POST['_file'])){
                $data_lig['req_data'] = (array)json_decode($_POST['_file']);
                unset($data_lig['req_data']['binary']);
                $data_lig['req_data'] = json_encode($data_lig['req_data']);

            }else{
                $data_lig['req_data'] = json_encode(array($this->get,$this->post));
            }
        }
        if($data){
            $data_lig['resp_data'] = $data;
            if(count($data)>2){
                unset($data_lig['resp_data']['binary']);
            }
        }

        if($this->controller){
            $data_lig['provider'] = $this->controller;
        }
        if($this->action){
            $data_lig['action'] = $this->action;
        }

        if(isset($_SERVER['HTTP_CLIENTID'])){
            $data_lig['clientid'] = $_SERVER['HTTP_CLIENTID'];
        }
        Model::factory('Upload_DbOperation')->message_log($data_lig);
        echo json_encode($data);
        die;
    }
    public function modify_check(){

        //是否为空判断
        if(isset($_SERVER['HTTP_CLIENTSIGN']) && isset($_SERVER['HTTP_CLIENTID']) && $_SERVER['HTTP_CLIENTID'] && $_SERVER['HTTP_CLIENTSIGN']){
        }else{
            $this->_render(array('status'=>false,'msg'=>'请求信息错误'));
        }


        //是否为空判断
        if(!empty($_POST)){
            //$this->file = json_decode($this->post['_file']);
        }else{
            $this->_render(array('status'=>false,'msg'=>'上传数据为空'));
        }


        $this->client_arr = Model::factory('Upload_DbOperation')->get_list($_SERVER['HTTP_CLIENTID']);


        if($this->client_arr['client_key']){
            $check_sign = md5(json_encode($_POST).$this->client_arr['client_key']);

        }else{
            $this->_render(array('status'=>false,'msg'=>'验证信息失败'));
        }
        //CLIENT_SIGN验证
        if($check_sign != $_SERVER['HTTP_CLIENTSIGN']){
            $this->_render(array('status'=>false,'msg'=>'验证信息失败'));
        }
    }


}


