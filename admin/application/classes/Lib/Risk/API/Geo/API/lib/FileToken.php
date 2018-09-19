<?php
/*
 * Created on 2016-7-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class FileToken{
 		
    private static $_instance;
    
    private function __construct(){}
    
    private function __clone(){}
    
    public static function getInstance(){
        if(! (self::$_instance instanceof self) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function putFile($type,$key,$value){
    	// 写文件
    	$fp = fopen($type."-".$key, 'cb');
		flock($fp, LOCK_EX | LOCK_NB);
		fwrite($fp, $value);
		flock($fp, LOCK_UN);
		fclose($fp);
    }
    
    function getFile($type,$key){
    	$filename = "./".$type."-".$key;
    	if(file_exists($filename)){
    		$handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
	        if($handle!=null){
	        	//通过filesize获得文件大小，将整个文件一下子读到一个字符串中
	        	$filesize = filesize($filename) ;
	        	if($filesize>0){
	        		$contents = fread($handle, $filesize);
	                fclose($handle);
	                return $contents ;
	        	}else{
	        		return "" ;
	        	}
	        }else{
	        	return "" ;
	        }
    	}else{
    		return "" ;
    	}
    }
    
    function putTokenId($key,$value){
    	$this->putFile("tokenId",$key,$value) ;
    }
    
    function getTokenId($key){
    	return $this->getFile("tokenId",$key) ;
    }
    
    function putGetTokenTime($key,$value){
    	$this->putFile("getTokenTime",$key,$value) ;
    }
    
    function getGetTokenTime($key){
    	return $this->getFile("getTokenTime",$key) ;
    }
    
    function putDigitalSignatureKey($key,$value){
    	$this->putFile("digitalSignatureKey",$key,$value) ;
    }
    
    function getDigitalSignatureKey($key){
    	return $this->getFile("digitalSignatureKey",$key) ;
    }
 }
 /*
 $token = FileToken::getInstance() ;
 $token->putTokenId("a","avalue");
 $token->putTokenId("b","bvalue");
 $vv = $token->getTokenId("c") ;
 print_r ($vv) ;*/
?>
