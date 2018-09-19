<?php
/*
 * Created on 2016-7-14
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 require_once 'HttpClient.class.php';
 require_once 'FileToken.php';
 require_once 'CryptAES.php';
 require_once 'Thelog.php';
 class Lib_Risk_API_Geo_API_lib_Client {
    var $server = "" ; // http://yz.geotmt.com��https://yz.geotmt.com
	var $encrypted = 1 ; // 是否加密传输
	var $encryptionType = "AES2" ; // 只支持AES2(秘钥长度16个字符)
	var $encryptionKey = "" ; // 需要同时告知GEO在后台进行配置
	var $username = "" ; // 向GEO获取
	var $password = "" ;
	var $uno = "" ;
	var $etype = "" ;
	var $dsign = 0 ; // 不支持签名
	
	
	var $httpTimeout = 10 ; // 超时时间
	
	private $token ;
	private $getTokenTime ;
	private $digitalSignatureKey ;
	
	var $tokenCycle = 86400000 ;
	var $tokenCyc = 35000 ;  // 避免高并发时刚好token过期造成同时多个进程一起申请新token，如果在此时间内有过更新那么直接返回文件里面的token
 
 	public function __construct($server,$encrypted,$encryptionType,$encryptionKey,$username,$password,$uno){
 		$this->server = $server ;
 		$this->encrypted = $encrypted ;
 		$this->encryptionType = $encryptionType ;
 		$this->encryptionKey = $encryptionKey ;
 		$this->username = $username ;
 		$this->password = $password ;
 		$this->uno = $uno ;
 		$token = FileToken::getInstance() ;
 		$this->token = $token ;
 	}
 	function getNewToken(){
		$tokenTime = $this->token->getGetTokenTime($this->username) ;
		$getTokenTime = $tokenTime==null||$tokenTime=="" ? 0 : $tokenTime;
		$tokenId = $this->token->getTokenId($this->username) ;
		return $tokenId==null||""==$tokenId||$this->getMillisecond()-$getTokenTime>=$this->tokenCycle ;
	}
	public function getTokenId($first){
		if($first){
			if($this->getNewToken()){
				return $this->getToken() ; 
			}else{
				return $this->token->getTokenId($this->username) ;
			}
		}else{
			$tokenTime = $this->token->getGetTokenTime($this->username) ;
			$getTokenTime = $tokenTime==null||$tokenTime=="" ? 0 : $tokenTime;
			if($this->getMillisecond()-$getTokenTime>=$this->tokenCyc){
				// 避免高并发
				$this->token->putTokenId($this->username,""); // 使token失效
			}
			$tokenId = $this->getToken() ;
			return $tokenId ;
		}
	}
 	public function getToken(){
 		if($this->getNewToken()){
 			$path = $this->server."/civp/getview/api/o/login" ;
 			$username = $this->username ;
 			$password = $this->password ;
 			$uno = $this->uno ;
 			$encrypted = $this->encrypted ;
 			$dsign = $this->dsign ;
 			$aes = new CryptAES();
			$aes->set_key($this->encryptionKey);
			$aes->require_pkcs5();
 			if($encrypted == 1){
 				$username = $aes->encrypt($username);
 				$password = $aes->encrypt($password);
 				$dsign = $aes->encrypt($dsign);
 			}
	 	    $params = array('username' => $username,
			'password' => $password,
			'uno'=>$uno,
			'encrypted'=>$encrypted,
			'dsign'=>$dsign
			);

			$rs = HttpClient::quickPost($path, $params);
			Thelog::printLog("raw:".$rs);
			if($this->startsWith($rs, '{')){
				Thelog::printLog("unencrypted:".$rs);
			}else{
				$rs = $aes->decrypt($rs);
		        Thelog::printLog("deciphering:".$rs);
			}
			$data=json_decode($rs,true);
			if("200" == $data['code']){
				$getTokenTime = $this->getMillisecond() ;
				$tokenId = $data['tokenId'] ;
				$this->token->putTokenId($this->username,$tokenId);
				$this->token->putGetTokenTime($this->username,$getTokenTime);
				$rsdata = $data['data'] ;
				if($rsdata!=null){
					if(array_key_exists('digitalSignatureKey', $rsdata)){
						$digitalSignatureKey = $rsdata['digitalSignatureKey'] ;
						$this->token->putDigitalSignatureKey($this->username,$digitalSignatureKey);
					}
				}
				return $tokenId ;
			}else{
				Thelog::printLog("登录失败!code=".$data['code']);
				return "" ;
			}
 		}else{
 			return $this->token->getTokenId($this->username) ;
 		}
 	}
 	public function getData($path,$params){
		$tokenId = $this->getTokenId(true) ; 

		$keys=array("tokenId");
        $new=array_fill_keys($keys,$tokenId);
        
        $encrypted = $this->encrypted ;
        if($encrypted == 1){
        	$aes = new CryptAES();
		    $aes->set_key($this->encryptionKey);
		    $aes->require_pkcs5();
        	foreach ($params as $k=>$v) {
		        // echo 'key=' . $k . ', value=' . $v ;
		        $params[$k] = $aes->encrypt($v);
		    }
        }
        
        $newParams=array_merge($params,$new);
		
		$data = $this->getDataByTokenId($path,$newParams) ;
		if(isset($data['data'])){
            $code = $data['data'] ;
		}else{
            $code='-100';
		}

		if("-100"==$code||"-200"==$code||"-300"==$code){
			Thelog::printLog("tokenId无效重新获取tokenId");
			$tokenId = $this->getTokenId(false) ;  
			
			$keys=array("tokenId");
            $new=array_fill_keys($keys,$tokenId);
            $newParams=array_merge($params,$new);
			
			$data = $this->getDataByTokenId($path,$newParams) ;
		}
		return $data ;
	}
 	public function getDataByTokenId($path,$params){
 		$rs = HttpClient::quickPost($path, $params);
 		Thelog::printLog("raw:".$rs);
 		if($this->startsWith($rs, '{')){
 			Thelog::printLog("unencrypted:".$rs);
 		}else{
 			$aes = new CryptAES();
		    $aes->set_key($this->encryptionKey);
		    $aes->require_pkcs5();
		    $rs = $aes->decrypt($rs);
		    Thelog::printLog("deciphering:".$rs);
 		}
 		$data=json_decode($rs,true);
 		if("200" != $data['code']){
			Thelog::printLog("数据请求失败!code=".$data['code']);
		}
		return $data ;
 	}
 	function getMillisecond() {
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
	}
	function startsWith($haystack, $needle) {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }
 }
?>
