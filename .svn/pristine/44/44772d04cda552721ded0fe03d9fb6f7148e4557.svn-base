<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 16/1/5
 * Time: 下午5:17
 */

class Model_User_Identity extends Model_Database {


	const STATUS_VERIFIED = 1;		//已验证
	const STATUS_FAILED= 2;			//验证失败
	const STATUS_READY = 3;			//待审核
	const STATUS_CHECKING = 4;		//审核中
	const STATUS_REPLACED = 9;		//已更换(愿记录保留)



	public $status_array = array(
			self::STATUS_VERIFIED =>'已审',
			self::STATUS_FAILED =>'失败',
			self::STATUS_READY => '待审',
			self::STATUS_CHECKING => '审核中',
			self::STATUS_REPLACED => '已更换(愿记录保留)',
	);

	//状态名
	public function get_status_name($status_key=0){
		return isset($this->status_array[$status_key]) ? $this->status_array[$status_key] : NULL;
	}

	//单条数据
	public function get_one($id) {
		return DB::select()->from('user_identity')->where('id','=',$id)->execute()->current();
	}

	//单条数据
	public function get_one_by_code($code=0,$status=NULL) {
		if($status===NULL){
			$status = Model_User_Identity::STATUS_VERIFIED;
		}
		return DB::select()->from('user_identity')->where('code','=',$code)->and_where('status','=',$status)->execute()->current();
	}



	//创建帐号
	public function create($data=array()) {
		$rs=DB::select()->from('user_identity')->where('code',"=",$data['code'])->execute()->current();//已有身份证号不能再添加

		$array = array();
		if(isset($rs['id'])) {
			return FALSE;
		} else {
			$array['name'] = trim($data['name']);
			$array['code'] = trim($data['code']);
			$array['sex'] = $data['sex'] ? trim($data['sex']) :'' ;
            $array['identity_face'] = isset($data['identity_face']) ? $data['identity_face'] : '';
			$array['status'] = isset($data['status']) ? intval($data['status']) : 0;

			list($insert_id,$affected_rows)=DB::insert("user_identity",array('name','code','sex','identity_face','status','create_time'))
					->values(array(
									$array['name'],
									$array['code'],
									$array['sex'],
									$array['identity_face'],
                                    $array['status'],
                                    $array['validated_identity'],
									time(),
							)
					)
					->execute();
			return $insert_id;
		}
	}

/*
	//更改信息 条件 user_id
	public function update_by_user_id($user_id=0,$array=NULL,$status=NULL) {
		if(!$array){
			return FALSE;
		}
		if($status===NULL){
			$status = Model_User_Identity::STATUS_VERIFIED;
		}
		$affected_rows = DB::update('user_identity')->set($array)->where('user_id','=',$user_id)->execute();
		return $affected_rows!==NULL;
	}*/

	//更改信息
	public function update($id=0,$array=NULL) {
		if(!$array){
			return FALSE;
		}
		$affected_rows = DB::update('user_identity')->set($array)->where('id','=',$id)->execute();
		return $affected_rows!==NULL;
	}






	//构造查询条件
	private function query_builder($query, $array=array()) {
		if(isset($array['identity'])){
			$query->where('identity_code','=',trim($array['identity']));
		}

		if(isset($array['name']) && $array['name']!='') {
			$query->where('name','=',$array['name']);
		}

		if(isset($array['username']) && $array['username']!=''){
			$query->where('username','=',$array['username']);
		}

		if(isset($array['email']) && $array['email']!=""){
			$query->where('email','=',$array['email']);
		}

		if(isset($array['name']) && $array['name']!=""){
			$query->where('name','=',$array['name']);
		}

		return $query;
	}


	//查询分页
	public function get_list($array=array(),$perpage=20,$page=1) {
		$query=DB::select()->from('user');
		if(count($array)>0) {
			$query = $this->query_builder($query,$array);
		}
		if($page<1) {
			$page=1;
		}
		$rs=$query->offset($perpage*($page-1))->limit($perpage)->execute()->as_array();
		return $rs;
	}


	//查询统计
	public function get_total($array=array()) {
		$query=DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('user');
		if(count($array)>0) {
			$query = $this->query_builder($query,$array);
		}
		$rs=$query->execute()->current();
		return isset($rs['total']) ? $rs['total'] : 0 ;
	}
}