<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/1/6
 * Time: 下午6:18
 */
Class Tool_Dir{

    public function createFolder($path){
        if (!file_exists($path))
        {
            $result = mkdir($path, 0777,true);
            return $result;
        }
        return true;
    }
    /**
     * 创建目录
     *
     * @param	string	$path	路径
     * @param	string	$mode	属性
     * @return	string	如果已经存在则返回true，否则为flase
     */
    function dir_create($path, $mode = 0777){
        if(is_dir($path)) return TRUE;
        $path = $this->dir_path($path);
        $temp = explode('/', $path);
        $cur_dir = '';
        $max = count($temp) - 1;
        for($i=0; $i<$max; $i++){
            $cur_dir .= $temp[$i].'/';
            if (@is_dir($cur_dir)) continue;
            @mkdir($cur_dir, 0777,true);
            @chmod($cur_dir, 0777);
        }
        return is_dir($path);
    }
    /**
     * 转化 \ 为 /
     * @param	string	$path	路径
     * @return	string	路径
     */
    function dir_path($path){
        $path = str_replace('\\', '/', $path);
        if(substr($path, -1) != '/') $path = $path.'/';
        return $path;
    }
}