<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 2017/12/26
 * Time: 下午9:39
 *
 * 集成  LongTerm MediumTerm ShortTerm
 * target_token 周期类型分3类,为了混淆效果,随机范围内选数字,
 * 100~999999 奇数:短周期ShortTerm  偶数:中周期MediumTerm
 * 1000000~1999999 长周期LongTerm
 */
class Lib_Target_Token {
    const TYPE_LONG = 'long';
    const TYPE_MEDIUM = 'medium';
    const TYPE_SHORT = 'short';
    const SECRET_KEY = '94800cae02fa582706953c87f3587b87';

    protected $type = self::TYPE_SHORT;
    protected $token_id = 0;
    protected $user_id = 0;
    protected $core = '';
    protected $random = '';
    protected $result = NULL;
    protected $secret = self::SECRET_KEY;
    protected $target_token = '';


    public function __construct($type=self::TYPE_SHORT,$key=self::SECRET_KEY) {
        $this->type($type);
        $this->secret($key);
    }

    public function type($type){
        $this->type = $type;
        return $this;
    }

    public function secret($key){
        $this->secret = $key;
        return $this;
    }

    public function user_id($user_id){
        $this->user_id = $user_id;
        return $this;
    }

    public function token_id($token_id){
        $this->token_id = $token_id;
        return $this;
    }



    //生成 target_token
    public function make(){
        $lib = NULL;
        $type_value = 0;
        switch($this->type){
            case self::TYPE_SHORT:
                $lib = Lib::factory('Target_ShortTerm');
                $type_value = rand(100,999998);
                if($type_value%2==0) {
                    $type_value = $type_value + 1;//确保是奇数
                }
                break;
            case self::TYPE_MEDIUM:
                $lib = Lib::factory('Target_MediumTerm');
                $type_value = rand(100,999998);
                if($type_value%2==1) {
                    $type_value = $type_value + 1;//确保是偶数
                }
                break;
            case self::TYPE_LONG:
                $lib = Lib::factory('Target_LongTerm');
                $type_value = rand(1000000,1999999);
                break;
            default:
                //short
                $lib = Lib::factory('Target_ShortTerm');
                $type_value = rand(100,999998);
                if($type_value%2==0) {
                    $type_value = $type_value + 1;//确保是奇数
                }
        }


        $this->random = Text::random('hexdec',32);
        $this->core = $lib->hash_random($this->token_id.'-'.$this->user_id,$this->random);
        $this->target_token = $this->core.'-'.$this->token_id.'-'.$this->user_id.'-'.$type_value.'-'.$this->random;
        $target_token_base64 = $this->encrypt($this->target_token,$this->secret);
        return $this->base64_url_encode($target_token_base64);
    }


    //核验+解析
    public function auth($string){
        $string = $this->base64_url_decode($string);
        //var_dump($string);
        if( $destr= $this->decrypt($string,$this->secret)){
            $array = explode('-',$destr);
            if(count($array)<5){
                return FALSE;
            }
            $this->target_token = $destr;
            $this->core =  substr($array[0],0,32);
            $this->token_id = $array[1];
            $this->user_id = $array[2];
            $type_value = $array[3];
            $this->random = $array[4];
            $bool = FALSE;
            if($type_value<=999999){
                if($type_value%2==1){//奇数 短期
                    $this->type = self::TYPE_SHORT;
                    $bool = Lib::factory('Target_ShortTerm')->valid_random($this->core,$this->token_id.'-'.$this->user_id,$this->random);
                }else{//偶数 中期
                    $this->type = self::TYPE_MEDIUM;
                    $bool = Lib::factory('Target_MediumTerm')->valid_random($this->core,$this->token_id.'-'.$this->user_id,$this->random);
                }
            }else{//长期
                $this->type = self::TYPE_LONG;
                $bool = Lib::factory('Target_LongTerm')->valid_random($this->core,$this->token_id.'-'.$this->user_id,$this->random);
            }

            if($bool){
                return [
                    'core'=>$this->core,
                    'token_id'=>$this->token_id,
                    'user_id'=>$this->user_id,
                    'random'=>$this->random,
                    'target_token'=>$this->target_token];
            }
            return FALSE;
        }

        return FALSE;//解析失败
    }







    //加密
    protected function encrypt($string,$key){
        if(empty($string)){
            return FALSE;
        }
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $string = $this->pkcs5_pad($string, $size);

        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_ECB));
    }

    /* 解密
        php  AES加密后不会在字符串后面补位x00和记位符
        ios 和 java(android)
    */
    protected function decrypt($string,$key){
        if(empty($string)){
            return FALSE;
        }
        $decrypted =  mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($string), MCRYPT_MODE_ECB);
        return $this->pkcs5_unpad($decrypted);
    }



    function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }



    public function base64_url_encode($string)
    {
        return str_replace(array('+','/'),array('-','_'),$string);
    }
    public function base64_url_decode($string)
    {
        return str_replace(array('-','_'),array('+','/'),$string);
    }


}