<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
use org_mapu_themis_rop_model\RichServiceRequest as RichServiceRequest;
use Exception as Exception;
/**
 * 合同模版请求查询
 * @edit yfx 2015.06.09
 */
class ContractTemplateCreateFileRequest extends RichServiceRequest{
	
	static $v="1.0";
	static $method="contract.template.create.file";
	
	/**
	 * 传入的参数
	 */
	public $replaceParams;
	
	/**
	 * 印章参数
	 */
	public $userStampParams;
	
	/**
	 * 合同模板编号
	 */
	public $tempLetId;
	
	//请求的版本号
	function getVersion(){
		return self::$v;
	}
	//请求的方法
	function getMethod(){
		return self::$method;
	}
	
	/**
	 * 默认的验证方法
	 * */
	function validate(){
		$this->tempLetId=$this->trim($this->tempLetId);
		if($this->tempLetId==''){
			throw new Exception("tempLetId is null");
			return false;
		}
		if($this->replaceParams!=null&&is_array($this->replaceParams)){//为字典数组
			//不能用json_encode因为后面还要对数据进行签名，前者会转码数据
			$replaceParams="{";
			foreach ($this->replaceParams as $key=>$value) {
				$replaceParams.="\"".$key."\":\"".$this->trim($value)."\",";
			}
			$replaceParams=rtrim($replaceParams,",")."}";
			$this->replaceParams=$replaceParams;
		}else{
			throw new Exception("replaceParams is null or not a array(key,value)");
			return false;
		}
		if($this->userStampParams!=null&&is_array($this->userStampParams)){//为字典数组
			$userStampParams="[";
			foreach ($this->userStampParams as $value) {
				if($value==null&&!is_a($value,'org_mapu_themis_rop_model\UserStamp')){
					throw new Exception("replaceParams has value is null or not org_mapu_themis_rop_model\UserStamp");
					return false;
				}else if(!$value->validate()){//校验不能过
					return false;
				}else{
					$userStampParams.="{\"name\":\"".$value->name."\",\"page\":\"".$value->page."\",\"xAxis\":\"".$value->xAxis."\",\"yAxis\":\"".$value->yAxis."\"},";
				}
			}
			$this->userStampParams=rtrim($userStampParams,",")."]";
		}
		return true;
	}
	
	function getIgnoreSign(){
		$ignoreSign=array('replaceParams','userStampParams');
		return $ignoreSign;
	}
}
?>