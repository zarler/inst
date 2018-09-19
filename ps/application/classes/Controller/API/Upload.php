<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 2017/8/18
 * Time: 下午4:39
 */
class Controller_API_Upload extends AdminController{

    public static $config;

    static function upload_status($error){
        $status = array(
            1 => '上传文件尺寸超限(php)',
            2 => '上传文件尺寸超限(html)',
            3 => '文件部分上传',
            4 => '没有文件上传',
            5 => '上传文件大小为0'
        );
        return $status[$error];
    }

    public function before() {
        self::$config = Kohana::$config->load('site')->get('default');
        parent::before();
    }




    public function action_Upload(){
        $this->upload_check();
        if(!isset(self::$config['security_path'])) {
            self::init();
        }

        $json_array = [];
        if(json_decode($this->post['json'])){
            $json_array = json_decode($this->post['json'],TRUE);
        }

        if($_FILES['file']['error'] == 0){
            if(!isset($this->client_arr['id'])){
                $this->_render(array('status'=>false,'msg'=>$this->message['Upload::nopower']));
            }
            $client_id = $this->client_arr['id'];
            $file = $_FILES['file'];
            //后缀名验证
            $suffix_name = $file['name']?substr(strrchr($file['name'],'.'),1): '';
            if(isset($json_array['filename']) && $json_array['filename'] ){
                $suffix_name = substr(strrchr($json_array['filename'],'.'),1);
            }

            if(!in_array($suffix_name,self::$config['file_file_arr'])){
                $file_str ='';
                foreach(self::$config['file_file_arr'] as $key=>$value) {
                    $file_str .= $value.',';
                }
                $file_error =  "友情提示：文件格式错误，请上传'".$file_str."' 等类型的文件！";
                $this->_render(array('status'=>false,'msg'=>$file_error));
            }
            $new_name = time().Text::random('alnum',8).".".$suffix_name;
            $relative_path = date("Y").'/'.date("md").'/';
            $flash_dir =  Tool::factory('Dir')->dir_path(self::$config['security_path'].$relative_path);
            $absolutely_path_file = $flash_dir.$new_name;
            $relative_path = $relative_path.$new_name;
            //判断目录是否存在不存在生成
            if( !is_dir($flash_dir) ){
                Tool::factory('Dir')->dir_create($flash_dir,'755');
            }
            $hash = md5($absolutely_path_file);
            if(move_uploaded_file($file['tmp_name'],$absolutely_path_file)){
                //保存数据
                //重新获取文件大小
                if(is_file($absolutely_path_file)){
                    $size = filesize($absolutely_path_file);
                }
                list($insert_id, $total_rows) = DB::insert('file',array('client_id','file','new_file','extension','uri','hash','size','create_time'))->values(array($client_id,$file['name'],$new_name,$suffix_name,$relative_path,$hash,$size,date('y-m-d H:i:s',time())))->execute();
                if($insert_id){
                    $this->_render(array('status'=>true,'msg'=>'上传成功!','hash'=>$hash));
                }else{
                    $this->_render(array('status'=>false,'msg'=>'入库失败!'));
                    unlink($absolutely_path_file);
                }
            }else{
                $this->_render(array('status'=>false,'msg'=>'上传失败!'));
            }
        }
        $this->_render(array('status'=>false,'msg'=>self::upload_status($_FILES['file']['error'])));
    }

}