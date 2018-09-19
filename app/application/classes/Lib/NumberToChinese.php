<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: wangxuesong
 * Date: 15/12/31
 * Time: 下午5:03
 *
 *
 */

class Lib_NumberToChinese
{


    /**
     * 数字转换为中文
     * @param $num
     * @param string $type
     * @param bool $mode
     * @param bool $sim
     * @return string
     */

    public function number2chinese($num,$type='',$mode = true,$sim = true)
    {
        if(!is_numeric($num)) return '含有非数字非小数点字符！';
        $char    = $sim ? array('零','一','二','三','四','五','六','七','八','九')
            : array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖');
        $unit    = $sim ? array('','十','百','千','','万','亿','兆')
            : array('','拾','佰','仟','','萬','億','兆');
        $retval  = (!empty($mode) && $type=='amount')?"元":"";
        //小数部分
        if(strpos($num, '.')){

            list($num,$dec) = explode('.', $num);

            $dec = strval(round($dec,2));

            if($mode){
                $retval_angngle = ((isset($dec[0]) && isset($char[$dec[0]]) && $dec[0]>0)?$char[$dec[0]]."角":"");
                $retval_points = ((isset($dec[1]) && isset($char[$dec[1]]) &&  $dec[1]>0)?$char[$dec[1]]."分":"");
                $retval .= $retval_angngle.$retval_points;
            }else{
                for($i = 0,$c = strlen($dec);$i < $c;$i++) {
                    $retval .= $char[$dec[$i]];
                }
            }
        }
        //整数部分
        $str = $mode ? strrev(intval($num)) : strrev($num);
        for($i = 0,$c = strlen($str);$i < $c;$i++) {
            $out[$i] = $char[$str[$i]];
            if($mode){
                $out[$i] .= $str[$i] != '0'? $unit[$i%4] : '';
                if($i>1 and $str[$i]+$str[$i-1] == 0){
                    $out[$i] = '';
                }
                if($i%4 == 0){
                    $out[$i] .= $unit[4+floor($i/4)];
                }
            }
        }
        $retval = join('',array_reverse($out)) . $retval;
        return $retval;
    }

}
