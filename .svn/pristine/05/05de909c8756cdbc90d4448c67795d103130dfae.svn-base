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
class Tool_Debug {
    /**********************************************************
     *  目的：测试时使用的输出。只有符合ip条件才能能看见输出语句
     *  参数：
     *  返回：打印
     ***********************************************************/
    function E($temp=NULL){
        //echo $_SERVER[ 'REMOTE_ADDR' ];
        if($temp === NULL)
        {
            $temp = $_REQUEST;
        }
        //echo "sdfdsf";
        //print_r($_SERVER['REMOTE_ADDR']);
        //if(strpos(TEST_IP,$_SERVER['REMOTE_ADDR'])){
            echo '<pre> <b><div style=" overflow: auto;border:2px black dashed; font-family:微软雅黑; background-color:#FFE4C4; color :#DC143C";width:700px;>测试数据:<br><hr><p>';
            print_r($temp);
            echo '</p><hr></div></b>';
            //echo TEST_IP;
            //echo strpos(TEST_IP,$_SERVER['REMOTE_ADDR']);
            //echo "pre";
            //print_r($_SERVER);
            die;
        //}
    }
    /**********************************************************
     *  目的：测试时使用的输出。只有符合ip条件才能能看见输出语句
     *  参数：
     *  返回：打印后die
     ***********************************************************/
    function D($temp=NULL){
        if($temp === NULL)
        {
            $temp = $_REQUEST;
        }
        $this->E($temp);
        die;
    }
    /*************************
     *作用：js提示错误信息并返回
     *variable： String $msg提示信息，$url 跳转地址
     **************************/
    function message($msg,$url=''){
        if($msg){
            echo "<script  charset=\"gb2312\">alert(\"$msg\");</script>";
        }
        if(!$url){
            exit("<script  charset=\"gb2312\">history.go(-1);</script>");
        }else{
            exit("<script  charset=\"gb2312\">window.location.href='{$url}';</script>");
        }
    }

    /**
     * 调试，用于保存数组到txt文件 正式生产删除 array2file($info, SITE_PATH.'/post.txt');
     */
    function array2file($array, $filename) {
        file_exists($filename) or touch($filename);
        //file_put_contents($filename, var_export($array, TRUE),FILE_APPEND);
        file_put_contents($filename,"\r\n"."-----------------------------".Date("Y-m-d H:i:s",time())."\r\n".var_export($array, TRUE),FILE_APPEND);
    }
    
}