<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 16/1/9
 * Time: 下午18:19
 */

class Model_Fraud_Log extends Model_Database {

	//查询分页
	public function get_list($array=array(),$perpage=20,$page=1) {
		$query=DB::select()->from('fraud_log');
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
		$query=DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('fraud_log');
		if(count($array)>0) {
			$query = $this->query_builder($query,$array);
		}
		$rs=$query->execute()->current();
		return isset($rs['total']) ? $rs['total'] : 0 ;
	}
}