<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: wangxuesong
 * Date: 18/01/19
 * Time: 上午12:50
 *  新增授信审核
 *
 */

class Controller_User_Audit extends AdminController {

    protected $permission_array = array(
        'map' => array(
            'List'=>	array('Detail','Change','SaveMark'),
        ),
    );  

    public function before() {
        parent::before(); // TODO: Change the autogenerated stub
        $this->_status = Model::factory('User')->credit_auth_array;
    }

    /**
     * 放款审核列表调用
     */
    public function action_List() {


        $page = isset($this->get['page']) ? intval($this->get['page']) : 1;
        $perpage = isset($this->get['pagesize']) ? intval($this->get['pagesize']) : 20;

        //定义data
        $array = [];
        $message = NULL;

        
        //判断是否提交了手机号搜索
        if(isset($this->get['mobile']) && $this->get['mobile'] ) {
            $array['mobile'] = trim($this->get['mobile']);
        }

        //判断是否提交了用户名搜索
        if(isset($this->get['name']) && $this->get['name'] ) {
            $array['name'] = trim($this->get['name']);
        }

        //判断是否提交了身份证搜索
        if(isset($this->get['identity_code']) && $this->get['identity_code'] ) {
            $array['identity_code'] = trim($this->get['identity_code']);
        }

        //判断是否提交了状态
        if(isset($this->get['credit_auth']) && $this->get['credit_auth'] ) {
            $array['credit_auth'] = intval($this->get['credit_auth']);
        }

        //判断是否提交了用户ID搜索
        if(isset($this->get['id']) && $this->get['id'] ) {
            $array['id'] = intval($this->get['id']);
        }

         //判断是否提交了状态
        if(isset($this->get['option']) && $this->get['option'] ) {
            $array['option'] = $this->get['option'];
        }else{
           $array['option'] = '';
        }

        $array['admin_id'] = $this->userId();

        //查询订单列表总数
        $total =  Model::factory('User_Audit')->getTotal($array);
        $pagination = Pagination::factory(
            array(
                'total_items' => $total,
                'items_per_page' => $perpage,
            ));

        //获取用户授权审核列表
        $list = Model::factory('User_Audit')->getList($array, $perpage, $page);
 
        //判断用户列表是否有数据
        Template::factory('User/Audit/List', array(
                'message' => $message,
                'list' => $list,
                'option' =>$array['option'],
                '_status'=>$this->_status,
                'pagination' => $pagination,
            )
        )->response();


    }

    /**
     * 授信审核详细页调用
     */
    public function action_Detail() {
        $message=null;
        $uid = isset($this->get['uid']) ? intval($this->get['uid']) : 0;

        //判断用户ID是否存在
        if(empty($uid) &&  $uid<1){
            Template::factory()->message(
                array(
                    'type' => 'error',
                    'title' => '用户不存在',
                    'message' => '用户不存在,1秒后返回列表.',
                    'redirect' => $this->backurl() ? $this->backurl() : '/' . $this->controller . '/List',
                    'redirect_time' => 1,
                    'back' => FALSE,
                    'none'=>$this->backurl_clear())//消除返回地址
            );
        }

         $audit = Model::factory('User_Audit')->getOneByUserId($uid);

         if(empty($audit)){

            Model::factory('User_Audit')->create(['user_id'=>$uid,'admin_id'=>$this->userId(),'status'=>Model_User_Audit::STATUS_AUDITING]);
         } 
 
        // 用户基本信息、faceidauth照片信息、备注信息
        $user = Model::factory('User')->getInformation($uid); 
        $faceid_auth = Model::factory('User')->getFaceIdAuth($uid, $user['identity_face']);
        $remarkinfo=Model::factory('User_AuditRemark')->getListByUserId($uid);

        Template::factory('User/Audit/Detail', array('user' => array_merge($user,$faceid_auth),'remarkinfo'=>$remarkinfo,'message' =>$message))->response();
    }


  
  public function action_Change(){

        $user_id = $this->request->post('user_id');
        $operation= $this->request->post('operation');
        $inst_amount = $this->request->post('amount');


         if(empty($user_id) || $user_id<0){

            echo  json_encode(array('success'=>false,'code'=>1001,'msg'=>'用户不存在请刷新页面重新提交'));
            die();

        }
      
         $user = Model::factory('User')->getOne(intval($user_id));


         $user_credit_auth = isset($user['credit_auth'])?$user['credit_auth']:0;
         $user_status  = isset($user['status'])?$user['status']:0;


        if($user_credit_auth!=Model_User::CREDIT_AUTH_STATUS_SUBMITED && $user_credit_auth!=Model_User::CREDIT_AUTH_STATUS_CHECKING ){

            echo   json_encode(array('success'=>false,'code'=>1003,'msg'=>'用户不在授信待审状态'));
            die();
        }

        $array = [
             'credit_auth_origin'=>$user_credit_auth,
             'status'  => Model_User_Audit::STATUS_FINISH,
             'audit_time'  =>  time()
         ];

  
         try{

            if($operation=='pass'){

             $array['amount'] = $inst_amount;
             $array['credit_auth']= Model_User::CREDIT_AUTH_STATUS_VERIFIED;
  
                 Model::factory('User_Audit')->updateByUser($user_id,$array);
                 //更改用户授信状态
                 Model::factory('User')->update($user_id, array('credit_auth '=>$array['credit_auth']));
                 Model::factory('Finance_Profile')->update($user_id, ['inst_amount'=>$inst_amount]);

             }elseif($operation=='refuse'){

                 $array['credit_auth']= Model_User::CREDIT_AUTH_STATUS_FAILED;
                 Model::factory('User')->update($user_id, array('credit_auth '=>$array['credit_auth']));
                 Model::factory('User_Audit')->updateByUser($user_id,$array);

             }

            

              echo  json_encode(array('success'=>true,'code'=>1000,'msg'=>'授信审核处理成功'));

         } catch (Exception $e) {
        
            echo  json_encode(array('success'=>false,'code'=>1100,'msg'=>'授信审核处理失败')); 
        }

 
  }


   
    /**
     * 存储备注
     */
    public function action_SaveMark()
    {
        $message=null;
        $uid = isset($this->post['user_id']) ? intval($this->post['user_id']) : 0;
        if(isset($this->post['remarkinfo'])) {
            $valid = Validation::factory($this->post)
                ->rule('remarkinfo','not_empty')
                ->rule('user_id','not_empty');
            if($valid->check()) {
                $new_id = Model::factory('User_AuditRemark')->insert(array(
                    'admin_id'=>$this->userId(),
                    'user_id'=>$uid,
                    'content'=>$this->post['remarkinfo'],
                    'create_time'=>time(),

                ));
                if($new_id) {
                    $oneinfo=Model::factory('User_AuditRemark')->getOne($new_id);
                    $oneinfo['remarkcreate_time']=date('Y-m-d H:i:s',$oneinfo['remarkcreate_time']);

                    if($oneinfo){
                        $info = array('type' => 'success', 'message' => '添加成功.','data'=>$oneinfo);
                    }else{
                        $info = array('type' => 'error', 'message' => '添加失败.','data'=>null);
                    }
                     echo json_encode($info,true);

                }else {
                    $info = array('type' => 'error', 'message' => '添加失败.','data'=>null);
                    echo json_encode($info,true);
                }
            }else {
                $info = array('type' => 'error', 'message' => '添加失败,内容不能为空','data'=>null);
                echo json_encode($info,true);
            }
        }
    }

    public function action_Report(){
         Template::factory('User/Audit/Report', array())->response();
    }    
     
    
}