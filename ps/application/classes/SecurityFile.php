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
        $filepath = Tool::factory('Dir')->dir_path(self::$config['security_path']).$path;
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
        $filepath = Tool::factory('Dir')->dir_path(self::$config['security_path']).$path;
        //Tool::factory("Debug")->D($filepath);
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
        echo $file_content;
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

    #保存附件信息并上传文件,
    #$file=array(name:名称，size:大小，binary:文件二进制吗)；
    #返回hash值，
    public static function upLoad($client_id,$file){

        if(!isset(self::$config['security_path'])) {
            self::init();
        }
        //储存文件的地址
        $tc_upload_path = self::$config['security_path'];
        //后缀名验证
        $suffix_name = $file->name?substr(strrchr($file->name,'.'),1): '';
        if(!in_array($suffix_name,self::$config['file_file_arr'])){
            $file_str ='';
            foreach(self::$config['file_file_arr'] as $key=>$value) {
                $file_str .= $value.',';
            }
            $file_error =  "友情提示：文件格式错误，请上传'".$file_str."' 等类型的文件！";
            return array('status'=>false,'msg'=>$file_error);
        }# end of if

       // $new_name = md5(time().rand(1, 10000)).".".$suffix_name;
        $new_name = time().Text::random('alnum',8).".".$suffix_name;
        $relative_path = date("Y").'/'.date("md").'/';
        $flash_dir =  Tool::factory('Dir')->dir_path(self::$config['security_path'].$relative_path);
        //'\'转'/'
        $absolutely_path_file = $flash_dir.$new_name;
        $relative_path = $relative_path.$new_name;
        //判断目录是否存在不存在生成

        if( !is_dir($flash_dir) ){
            Tool::factory('Dir')->dir_create($flash_dir,'755');
        }
        $hash = md5($absolutely_path_file);
        $img_content = base64_decode($file->binary);
        $file_parh = fopen($absolutely_path_file,"w");
        if(fwrite($file_parh,$img_content)){
            fclose($file_parh);
            //保存数据
            //重新获取文件大小
            if(is_file($absolutely_path_file)){
                $file->size = filesize($absolutely_path_file);
            }
            list($insert_id, $total_rows) = DB::insert('file',array('client_id','file','new_file','extension','uri','hash','size','create_time'))->values(array($client_id,$file->name,$new_name,$suffix_name,$relative_path,$hash,$file->size,date('y-m-d H:i:s',time())))->execute();
            if($insert_id){
                return array('status'=>true,'msg'=>'上传成功!','hash'=>$hash);
            }else{
                return array('status'=>false,'msg'=>'入库失败!');
                //删除文件操作下面补充
            }
        }else{
            fclose($file_parh);
            return array('status'=>false,'msg'=>'上传失败!');
        };
    }
} // End
