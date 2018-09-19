<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/1/13
 * Time: 下午10:24
 *
 * 身份证有效验证
 * 男女计算
 *
 */
class Lib_IDCard
{


    /**
     * 功能：把15位身份证转换成18位
     *
     * @param string $idCard
     * @return newid or id
     */
    public static function get18($idCard)
    {
        // 若是15位，则转换成18位；否则直接返回ID
        if (15 == strlen ( $idCard )) {
            $W = array (7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,1 );
            $A = array ("1","0","X","9","8","7","6","5","4","3","2" );
            $s = 0;
            $idCard18 = substr ( $idCard, 0, 6 ) . "19" . substr ( $idCard, 6 );
            $idCard18_len = strlen ( $idCard18 );
            for($i = 0; $i < $idCard18_len; $i ++) {
                $s = $s + substr ( $idCard18, $i, 1 ) * $W [$i];
            }
            $idCard18 .= $A [$s % 11];
            return $idCard18;
        } else {
            return $idCard;
        }
    }


    // 检查是否是身份证号
    public static function isIDCard($number)
    {
        if( strlen($number)!=15 && strlen($number)!=18 ){
            return FALSE;
        }
        $number = self::get18($number);
        // 转化为大写，如出现x
        $number = strtoupper($number);
        //加权因子
        $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码串
        $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        //按顺序循环处理前17位
        $sigma = 0;
        for ($i = 0;$i < 17;$i++) {
            //提取前17位的其中一位，并将变量类型转为实数
            $b = (int) $number{$i};
            //提取相应的加权因子
            $w = $wi[$i];
            //把从身份证号码中提取的一位数字和加权因子相乘，并累加
            $sigma += $b * $w;
        }
        //计算序号
        $snumber = $sigma % 11;
        //按照序号从校验码串中提取相应的字符。
        $check_number = $ai[$snumber];
        if ($number{17} == $check_number) {
            return TRUE;
        }
        return FALSE;
    }


    public static function sex($number)
    {
        if (!self::isIDCard($number)) {
            return FALSE;
        }
        $sex = (int)substr($number,16,1);
        return $sex % 2 === 0 ? '女' : '男';
    }


    /** 身份证所在省
     * @param $number
     * @return bool
     */
    public static function province($number)
    {
        if (!self::isIDCard($number)) {
            return FALSE;
        }
        $code = substr($number,0,2);
        $area =Lib_IDCardData::getArray('area');
        if(isset($area[$code.'0000'])){
            return $area[$code.'0000'];
        }
        return FALSE;
    }


    /** 身份证所在 县级市
     * @param $number
     * @return bool
     */
    public static function city($number)
    {
        if (!self::isIDCard($number)) {
            return FALSE;
        }
        $code = substr($number,0,4);
        $area =Lib_IDCardData::getArray('area');
        if(isset($area[$code.'00'])){
            return $area[$code.'00'];
        }
        return FALSE;
    }


    /** 身份证所在 县级市
     * @param $number
     * @return bool
     */
    public static function county($number)
    {
        if (!self::isIDCard($number)) {
            return FALSE;
        }
        $code = substr($number,0,6);
        $area =Lib_IDCardData::getArray('area');
        if(isset($area[$code])){
            return $area[$code];
        }
        return FALSE;
    }


    /** 年龄
     * @param $number
     * @return bool|int
     */
    public static function age($number){
        if (!self::isIDCard($number)) {
            return FALSE;
        }
        $b = (int)substr($number,6,4);
        $y = (int)date('Y');
        if($b<=$y){
            return $y - $b;
        }
        return FALSE;
    }





}