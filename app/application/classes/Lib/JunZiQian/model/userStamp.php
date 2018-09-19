<?php
namespace org_mapu_themis_rop_model;
use Exception as Exception;
class UserStamp{
	public $name;
	
	public $page;
	
	public $xAxis;
	
	public $yAxis;
	
	public function __construct($name,$page=0,$xAxis=0,$yAxis=0){
		$this->name=$name;
		$this->page=$page;
		$this->xAxis=$xAxis;
		$this->yAxis=$yAxis;
	}
	
	/**
	 * 默认的验证方法
	 * */
	function validate(){
		$this->name=self::trim($this->name);
		$this->page=self::trim($this->page);
		$this->xAxis=self::trim($this->xAxis);
		$this->yAxis=self::trim($this->yAxis);
		if($this->name==''){
			throw new Exception("name is null");
			return false;
		}
		return true;
	}
	

	/**
	 * 处理无效字符串
	 **/
	static function trim($str){
		if($str==null){
			if(is_numeric($str)){
				return '0';
			}
			return '';
		}else{
			return trim($str.'');
		}
	}
}
?>