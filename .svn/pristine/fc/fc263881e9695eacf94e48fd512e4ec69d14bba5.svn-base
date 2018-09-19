<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 16/1/9
 * Time: 下午18:19
 */

class Model_Bank extends Model_Database {


	const STATUS_VALID = 1;								//有效
	const STATUS_INVALID = 2;							//无效
	const STATUS_DELETED = 3;                            //已取消(删除)


	public $status_array = array(
			self::STATUS_VALID =>'有效',
			self::STATUS_INVALID =>'无效',
			self::STATUS_DELETED =>'已取消(删除)',
	);





	public function getOne($id=0) {
		return DB::select()->from('bank')->where('id','=',$id)->execute()->current();
	}

	// 根据bank code 查询银行
	public function getOneByCode($code=0,$status=NULL) {
		return DB::select()->from('bank')->where('code', '=', $code)->and_where('status','=', ( $status!==NULL ? $status : self::STATUS_VALID ) )->execute()->current();
	}

	public function getAll() {
		return DB::select()->from('bank')->execute()->as_array();
	}

	public function getArray($field=''){
		$bank = $this->getAll();
		$array = array();
		foreach($bank as $v){
		    if(empty($field)){
                $array[$v['id']]=$v;
            }else{
                $array[$v['id']]=$v[$field];
            }
		}
		return $array;
	}


	//添加
	public function create($array=array()) {
		if(isset($array['name']) && $array['name'] ) {

			$name = trim($array['name']);
			$code = trim($array['code']);
			$unionpay_code = trim($array['unionpay_code']);
			$status = intval($array['status']);
			$rank = intval($array['rank']);

			list($insert_id,$affected_rows) = DB::insert("bank",array('name','code','unionpay_code','status','rank'))
					->values( array($name,$code,$unionpay_code,$status,$rank))->execute();
			if($insert_id) {
				return $insert_id;
			}
		}
		return FALSE;
	}

    //更改
    public function update($id=0, $array=NULL) {
        if(!$array){
            return FALSE;
        }
        unset($array['id']);
        $affected_rows = DB::update('bank')->set($array)->where('id','=',intval($id))->execute();
        return $affected_rows!==NULL;
    }




	//构造查询条件
	private function queryBuilder($query, $array=array()) {

		if(isset($array['name']) && $array['name']) {
			$query->where('bank.name','like','%'.$array['name'].'%');
		}

		return $query;
	}


	//查询分页
	public function getList($array=array(),$perpage=20,$page=1) {
		$query=DB::select()->from('bank');
		if(count($array)>0) {
			$query = $this->queryBuilder($query,$array);
		}
		if($page<1) {
			$page=1;
		}
		$rs=$query->order_by('bank.rank','DESC')->offset($perpage*($page-1))->limit($perpage)->execute()->as_array();
		return $rs;
	}


	//查询统计
	public function getTotal($array=array()) {
		$query=DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('bank');
		if(count($array)>0) {
			$query = $this->queryBuilder($query,$array);
		}
		$rs=$query->execute()->current();
		return isset($rs['total']) ? $rs['total'] : 0 ;
	}



	//读取还没有绑定到接口的银行列表
	public function getAvailableByProviderId($provider_id=0){
		$sub = DB::select('bank_id')->from('bank')->join('deduct_core','LEFT')->on('deduct_core.bank_id','=','bank.id')->where('deduct_core.provider_id','=',$provider_id);
		return DB::select()->from('bank')->where('id','NOT IN',$sub)->execute()->as_array();
	}




}