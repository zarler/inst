<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/1/6
 * Time: 下午5:15
 */

return array(
    'file' => array(
        'Upload::not_empty' => '请选择要上传的文件',
        'Upload::valid' => 'valid msg',
        'Upload::type' => '上传文件类型不匹配',
        'Upload::size' =>'上传文件大小超过限制',
        'default' => 'default msg'
    ),
    'message' => array(
        'Upload:Illegal' => '非法上传！',
        'Upload::fail' => '上传失败！',
        'Upload::idorhash'=>'id和hash不能都为空',
        'Upload::get_fail'=> '获取失败',
        'Upload::client_id_nonull'=> 'client_id不能为空',
        'Upload::client_key_nonull'=> 'client_key不能为空',
        'Upload::nopower'=> '无权上传文件',
        'Delete::fail'=> '删除失败',
        'Delete::success'=> '删除成功',
        'Upload::get_success'=> '获取成功',
        'Upload::get_empty'=> '获取为空',
        'Path::empty'=> '路径为空',
        'File::nofile'=> '无该文件',
        'Request::nofile'=> '无该文件',
        'Save::success'=> '保存成功'
    )
);