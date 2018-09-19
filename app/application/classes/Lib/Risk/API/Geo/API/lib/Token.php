<?php
 class Token{
	private $tokenId = array(); 
	private $getTokenTime = array() ; 
	private $digitalSignatureKey = array() ; 
	
    private static $_instance;
    
    private function __construct(){}
    
    private function __clone(){}
    
    public static function getInstance(){
        if(! (self::$_instance instanceof self) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function putTokenId($key,$value){
    	$keys=array($key);
        $new=array_fill_keys($keys,$value);
        $this->tokenId=array_merge($this->tokenId,$new);
    }
    
    function getTokenId($key){
    	$value = array_column(array($this->tokenId), $key) ;
    	if($value != null){
    		return $value[0] ;
    	}else{
    		return "" ;
    	}
    }
    
    function putGetTokenTime($key,$value){
    	$keys=array($key);
        $new=array_fill_keys($keys,$value);
        $this->getTokenTime=array_merge($this->getTokenTime,$new);
    }
    
    function getGetTokenTime($key){
    	$value = array_column(array($this->getTokenTime), $key) ;
    	if($value != null){
    		return $value[0] ;
    	}else{
    		return "" ;
    	}
    }
    
    function putDigitalSignatureKey($key,$value){
    	$keys=array($key);
        $new=array_fill_keys($keys,$value);
        $this->digitalSignatureKey=array_merge($this->digitalSignatureKey,$new);
    }
    
    function getDigitalSignatureKey($key){
    	$value = array_column(array($this->digitalSignatureKey), $key) ;
    	if($value != null){
    		return $value[0] ;
    	}else{
    		return "" ;
    	}
    }
 }
 /*
 $token = Token::getInstance() ;
 $token->putTokenId("a","avalue");
 $token->putTokenId("b","bvalue");
 $vv = $token->getTokenId("a") ;
 print_r ($vv) ;
 */
?>
