<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 15/12/31
 * Time: 下午5:03
 *
 *
 */

class Tool_String {

    /**
     * 查询字符是否存在于某字符串
     *
     * @param $haystack 字符串
     * @param $needle 要查找的字符
     * @return bool
     */
    function str_exists($haystack, $needle){
        return !(strpos($haystack, $needle) === FALSE);
    }

    /** 字符串中部分长度用符号替代 ,用于遮盖隐私信息
     * @param $str
     * @param $start
     * @param int $len
     * @param string $char
     * @return mixed
     */
    function str_shield($str,$start,$len=1,$char='*'){
        if(!function_exists('mb_substr')){
            return substr_replace($str,str_repeat($char,$len),$start,$len);//不支持中文
        }
        if(mb_strlen($str)>$start){
            $str1 = mb_substr($str,0,$start);
            $mbstrlen = mb_strlen($str)-(mb_strlen($str1)+$len);
            $str2 = mb_substr($str,-1*($mbstrlen),$mbstrlen);
            return $str1.str_repeat($char,$len).$str2;

        }else{
            return $str;
        }
    }

    /** 设置左右各保留多少位
     * @param $str
     * @param $left_len
     * @param $right_len
     * @param string $char
     * @return mixed
     */
    function str_shield_middle($str,$left_len,$right_len,$char='*'){
        if(!function_exists('mb_substr')) {
            return $this->str_shield($str,$left_len,strlen($str)-$left_len-$right_len,$char);//不支持中文
        }else{
            return $this->str_shield($str,$left_len,mb_strlen($str)-$left_len-$right_len,$char);
        }
    }


    /** 过滤非utf8编码的字符
     * @param $str
     * @return string
     */
    function filter_utf8($str) {
        /*utf8 编码表：
        * Unicode符号范围           | UTF-8编码方式
        * u0000 0000 - u0000 007F   | 0xxxxxxx
        * u0000 0080 - u0000 07FF   | 110xxxxx 10xxxxxx
        * u0000 0800 - u0000 FFFF   | 1110xxxx 10xxxxxx 10xxxxxx
        *
        */
        $re = '';
        $str = str_split(bin2hex($str), 2);

        $mo =  1<<7;
        $mo2 = $mo | (1 << 6);
        $mo3 = $mo2 | (1 << 5);         //三个字节
        $mo4 = $mo3 | (1 << 4);          //四个字节
        $mo5 = $mo4 | (1 << 3);          //五个字节
        $mo6 = $mo5 | (1 << 2);          //六个字节


        for ($i = 0; $i < count($str); $i++) {
            if ((hexdec($str[$i]) & ($mo)) == 0) {
                $re .= chr(hexdec($str[$i]));
                continue;
            }

            //4字节 及其以上舍去
            if ((hexdec($str[$i]) & ($mo6)) == $mo6) {
                $i = $i + 5;
                continue;
            }

            if ((hexdec($str[$i]) & ($mo5)) == $mo5) {
                $i = $i + 4;
                continue;
            }

            if ((hexdec($str[$i]) & ($mo4)) == $mo4) {
                $i = $i + 3;
                continue;
            }

            if ((hexdec($str[$i]) & ($mo3)) == $mo3) {
                $i = $i + 2;
                if (((hexdec($str[$i]) & ($mo)) == $mo) && ((hexdec($str[$i - 1]) & ($mo)) == $mo)) {
                    $r = chr(hexdec($str[$i - 2])) .
                        chr(hexdec($str[$i - 1])) .
                        chr(hexdec($str[$i]));
                    $re .= $r;
                }
                continue;
            }

            if ((hexdec($str[$i]) & ($mo2)) == $mo2) {
                $i = $i + 1;
                if ((hexdec($str[$i]) & ($mo)) == $mo) {
                    $re .= chr(hexdec($str[$i - 1])) . chr(hexdec($str[$i]));
                }
                continue;
            }
        }
        return $re;
    }



    //过滤电话必须是手机号
    public function string2mobile($phone){
        if(!preg_match('/^[\+]?[0-9][0-9\- \.\,#]{4,25}[0-9]$/',$phone)){
            return '';
        }
        if(strpos($phone,'#')!==FALSE){
            $phone = substr($phone,0,strpos($phone,'#'));
        }
        $phone = str_replace(array(' ','-','.','+',','),array(''),$phone);
        if(substr($phone,0,2)=='86'){
            $phone = substr($phone,2,strlen($phone)-2);
        }
        if(substr($phone,0,3)=='086'){
            $phone = substr($phone,3,strlen($phone)-3);
        }
        if(substr($phone,0,1)=='0'){
            $phone = substr($phone,1,strlen($phone)-1);
        }
        if(preg_match('/^1[3-9][0-9]{9}$/',$phone)){
            return $phone;
        }else{
            return '';
        }

    }


    //映射JSON
    public function is_json($str){
        return Tool::factory('Array')->is_json($str);
    }



    public function numeric($int){
        $code = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九','十','十一','十二');
        foreach ($code as $key => $value)
        {
            if ($int == $key)
            {
                return $code[$int];
            }
        }
    }









}