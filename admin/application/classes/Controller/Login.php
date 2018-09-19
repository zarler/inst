<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 15/12/15
 * Time: 下午9:53
 */
class Controller_Login extends Controller{


    protected $user = NULL;
    protected $post = NULL;
    protected $get = NULL;
    protected $remember_day = 720;

    public function before() {
        parent::before();
        $this->post = $this->request->post();
        $this->get = $this->request->query();
    }


    public function action_index() {
        $admin = Model::factory('Admin');
        $user = $admin->getUserSessArray();
        if(isset($user['id'])){
            Http::redirect('/');
        }
        if(isset($this->post['submit']) && isset($this->post['account']) && isset($this->post['password'])) {
            $type = 'username';
            $valid = Validation::factory($this->post)
                ->rule('account', 'not_empty')
                ->rule('account','min_length',array(':value','3'))
                ->rule('password','not_empty')
                ->rule('password','min_length',array(':value','6'));
            if(strpos($this->post['account'],'@')>0) {
                $valid->rule('account','Valid::email');
                $type = 'email';
            }

            if($valid->check()) {
                $user_id = Model::factory('Admin_User')->checkPassword($this->post['account'],$this->post['password'],$type);
                if($user_id) {//验证密码成功
                    $admin->login($user_id);
                    Http::redirect('/');
                }else{ //记录密码错误日志
                    Model::factory('Admin_Log_Login')->create( array('user_id'=>0, 'username'=>$this->post['account'],'type'=>Model_Admin_Log_Login::TYPE_FAIL_PASSWORD, 'ip'=>Request::$client_ip, 'user_agent'=>Request::$user_agent));
                }
            }
            $valid->errors('valid');
            Template::factory()->message(array(
                'title'=>'登录失败',
                'message'=>'账号或密码错误，账号只能是用户名或者邮箱，密码最少6位字符.',
                'back'=>true,
            ));

        }else{
            Template::factory('Login')->display();
        }

    }

    public function action_Out(){
        $bool = Model::factory('Admin')->logout();
        if($bool){
            Template::factory()->message(array(
                'type'=>'success',
                'title'=>'退出成功',
                'message'=>'建议您关闭浏览器后再离开.',
                'redirect'=>'/Login',
                'back'=>FALSE,
            ));
        }else{
            Template::factory()->message(array(
                'type'=>'notice',
                'title'=>'已退出,但为能清除会话',
                'message'=>'建议您清除浏览器缓存与COOKIES记录后再离开.',
                'redirect'=>'/Login',
                'back'=>FALSE,
            ));
        }

    }



}