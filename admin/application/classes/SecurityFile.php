<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 16/1/5
 * Time: 上午10:49
 *
 * 读取受到保护的隐私类文件
 *  调用前请确保你的方法里已经做好了严格的权限审核机制!!!
 *
 *
 * 调用方法
 *      SecurityFile::response(文件路径,文件不存在是显示的文件);
 *      SecurityFile::response('abc/123/aaa.jpg');
 *      SecurityFile::response('abc/123/aaa.jpg','not_found.jpg');
 *      数组方式
 *      SecurityFile::response(array(
 *                                  'path'=>'abc/123/aaa.jpg',
 *                                  'not_exist' =>'....',
 *                              ));
 *
 */
class SecurityFile  {

    public static $config;

    public static function init() {
        self::$config = Kohana::$config->load('site')->get('default');
        if(!isset(self::$config['security_path']) || empty(self::$config['security_path'])  ) {
            echo 'site config (config/site.php)  security_path is null.';
            exit;
        }
    }

    //输出文件
    public static function response_download($path_or_array = '',$not_exist_path=NULL) {
        if(is_array($path_or_array)){
            $path = isset($path_or_array['path']) ? $path_or_array['path'] : NULL;
            $not_exist = isset($path_or_array['not_exist']) ? $path_or_array['not_exist'] : NULL;
        }else{
            $path = $path_or_array;
            $not_exist = $not_exist_path;
        }
        if(!$path) {
            throw HTTP_Exception::factory(404, 'File path is empty!');
        }

        if(!isset(self::$config['security_path'])) {
            self::init();
        }
        $response = Response::factory();
        $filepath = self::$config['security_path'].$path;
        //var_dump($filepath);exit;
        if(is_file($filepath)) {
            $response->send_file($filepath);
        }elseif($not_exist){
            if(is_file($not_exist)){
                $response->send_file($not_exist);
            }else{
                Http::redirect($not_exist);
            }
        }else{
            throw HTTP_Exception::factory(404, 'File not found!');
        }
        exit;

    }

    public static function response($path_or_array = '',$not_exist_path=NULL) {
        if(is_array($path_or_array)){
            $path = isset($path_or_array['path']) ? $path_or_array['path'] : NULL;
            $not_exist = isset($path_or_array['not_exist']) ? $path_or_array['not_exist'] : NULL;
        }else{
            $path = $path_or_array;
            $not_exist = $not_exist_path;
        }
        if(!$path) {
            throw HTTP_Exception::factory(404, 'File path is empty!');
        }

        if(!isset(self::$config['security_path'])) {
            self::init();
        }

        $filepath = self::$config['security_path'].$path;
        if(is_file($filepath)){
            self::file_content($filepath);
        }else{
            if(is_file($not_exist)){
                self::file_content($not_exist);
            }else{
                Http::redirect($not_exist);
            }
            throw HTTP_Exception::factory(404, 'File not found!');
        }
        exit;

    }


    public static function file_content($filepath){
        $array = pathinfo($filepath);
        $mime =  File::mime_by_ext($array['extension']);
        if($mime===NULL){
            $mime = 'text/html';
        }
        header("Content-type: ".$mime);
        $file_content = file_get_contents($filepath);
        echo iconv('gb2312','utf-8',$file_content);
        exit;
    }



    //读取 $p = SecurityFile::security_path();
    //设置 SecurityFile::security_path($p);
    public static function security_path($path=NULL) {
        if($path) {
            self::$config['security_path'] = $path;
        }else{
            if(!isset(self::$config['security_path'])) {
                self::init();
            }
        }
        return self::$config['security_path'];
    }



//

} // End
