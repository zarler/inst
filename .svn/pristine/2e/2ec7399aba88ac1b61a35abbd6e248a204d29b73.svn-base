<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 15/12/14
 * Time: 下午3:53
 */

class Model_Admin_User extends Model_Database {

	protected $hash_method = 'sha256';
	protected $hash_key ='4F63EA59F9A5A7739C7EECF63167D396F8F8BBEF8704A48DE2C6A259FA094686';


    const STATUS_DENY = 2;          //禁用
    const STATUS_ALLOW =1;          //正常,被允许工作
    const STATUS_DELETE = 3;        //删除

	//检验密码 正确返回user_id 失败返回 false
	public function checkPassword($account,$password,$type='username'){
		if(!in_array($type,array('user_id', 'username', 'email'))) {
			return NULL;
		}
		$query = DB::select('id','username','password','salt')->from('user');
		switch($type) {
			case 'user_id' :
				$query->where('id',"=",$account);
				break;
			case 'email' :
				$query->where('email',"=",$account);
				break;
			default :
				$query->where('username',"=",$account);
		}
		$rs = $query->execute('admin')->current();
		if(isset($rs['id'])) {
			if( $rs['password'] === $this->hashPassword($password.$rs['salt']) ){
				return $rs['id'];
			}
		}
		return false;
	}


	//获取加密salt
	public function getSalt($user_id) {
		$rs=DB::select('salt')->from('user')->where('id',"=",$user_id)->execute('admin')->current();
		if(isset($rs['salt'])){
			return $rs['salt'];
		}
		return false;
	}


	//密码加密
	public function hashPassword($str)	{
		return hash_hmac( $this->hash_method ? $this->hash_method :'sha256' , $str, $this->hash_key);
	}



	//创建帐号
	public function create($data) {

	    $salt=Text::random('alnum',12);
		$rs=DB::select()->from('user')->where('username',"=",$data['username'])->execute('admin')->current();

		$array = array();
		$array['username'] = $data['username'];

		if(isset($rs['id'])) {
			return false;
		} else {
			$array['password'] = $this->hashPassword($data['password'].$salt);
			$array['email'] = isset($data['email']) ? $data['email'] : '';
			$array['name'] = isset($data['name']) ? $data['name'] : '';
            $array['number'] = isset($data['number']) ? $data['number'] : '';
            $array['mobile'] = isset($data['mobile']) ? $data['mobile'] : '';
            $array['phone'] = isset($data['phone']) ? $data['phone'] : '';
			$array['department'] = isset($data['department']) ? $data['department'] :'';
			$array['job'] = isset($data['job']) ? $data['job'] :'';
            $array['description'] = isset($data['description']) ? $data['description'] :'';
			$array['create_time'] = time();
			$array['status'] = isset($data['status']) ? $data['status'] : 0;


			list($insert_id,$affected_rows)=DB::insert("user",array(
					'username',	'password',	'email', 'salt',
					'name', 'number', 'mobile', 'phone', 'department', 'job','description','create_time', 'status'))
					->values(array(
									$array['username'],
									$array['password'],
									$array['email'],
									$salt,
									$array['name'],
                                    $array['number'],
                                    $array['mobile'],
                                    $array['phone'],
									$array['department'],
									$array['job'],
                                    $array['description'],
									$array['create_time'],
									$array['status'],
							)
					)
					->execute('admin');
			return $insert_id;
		}
	}


	//更改用户信息
	public function update($user_id=0,$array=NULL) {
		if(!$array){
			return FALSE;
		}
		unset($array['id']);
		$affected_rows = DB::update('user')->set($array)->where('id','=',intval($user_id))->execute('admin');
		return $affected_rows!==NULL;
	}


    //后台账号因一般不直接删除,只做删除标记
	public function delete($user_id=0){
		if($user_id>0) {
			return $this->update($user_id,array('status'=>self::STATUS_DELETE));
		}
	}


	//修改密码
	public function changePassword($user_id,$oldpassword='',$newpassword='') {
		if( $this->check_password($user_id,  $oldpassword, 'user_id') ) {
			$rs = $this->update($user_id, array('password'=>$this->hashPassword( $newpassword.$this->getSalt($user_id))));
			return $rs !== NULL;
		}
		return FALSE;
	}


	//单条用户数据
	public function getOne($user_id) {
		return DB::select()->from('user')->where('id','=',$user_id)->execute('admin')->current();
	}

	public function checkUserName($username) {
		return DB::select('id')->from('user')->where('username','=',$username)->execute('admin')->current();
	}

	//构造查询条件
	private function queryBuilder($query, $array=array()) {

		if(isset($array['group_id']) && $array['group_id']>0 ){
			$query->join('grouplink')->on('user.id','=','grouplink.user_id');
			$query->where('grouplink.group_id','=', $array['group_id']);
		}

		if(isset($array['status']) && $array['status']>0){
			$query->where('status','=',$array['status']);
		}
		if(isset($array['username']) && $array['username']!=''){
			$query->where('username','=',$array['username']);
		}

		if(isset($array['email']) && $array['email']!=''){
			$query->where('email','=',$array['email']);
		}
		if(isset($array['name']) && $array['name']!=''){
			$query->where('name','like','%'.$array['name'].'%');
		}
		if(isset($array['department'])&&$array['department']!=''){
			$query->where('department','like','%'.$array['department'].'%');
		}
		if(isset($array['job'])&&$array['job']!=''){
			$query->where('job','like','%'.$array['job'].'%');
		}
		//id not in(为Controller_Overdue_Reminder::action_Add服务)
		if(isset($array['id_not']) && !empty($array['id_not'])){
			$query->where("id","not in",$array['id_not']);
		}
		return $query;
	}


	//查询分页
	public function getList($array=array(),$perpage=20,$page=1) {
		$query=DB::select()->from('user');
		if(count($array)>0) {
			$query = $this->queryBuilder($query,$array);
		}
		if($page<1) {
			$page=1;
		}
		$rs=$query->order_by('id','DESC')->offset($perpage*($page-1))->limit($perpage)->execute('admin')->as_array();
		return $rs;
	}


	//查询统计
	public function getTotal($array=array()) {
		$query=DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('user');
		if(count($array)>0) {
			$query = $this->queryBuilder($query,$array);
		}
		$rs=$query->execute('admin')->current();
		return isset($rs['total']) ? $rs['total'] : 0 ;
	}

	/*
	 *
	 */
	public function getAll($params=array()){
		$query = DB::select("*")->from('user');
		$query = $this->queryBuilder($query,$params);
		return $query->order_by("id","DESC")->execute("admin")->as_array();
	}








}