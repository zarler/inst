<?php
/**
 * Created by PhpStorm.
 * Permission: liujinsheng
 * Effect:文件上传接口
 */
class Controller_API_Operation extends AdminController {
    public function before() {
        parent::before();
    }
    //获得Json数据信息
    public function action_Json(){
        $hash = isset($this->get['hash'])?addslashes($this->get['hash']):"";
        $id = isset($this->get['id'])?intval($this->get['id']):0;
        //http自定义头验证
        $this->download_check();
        //组合数据，插入数据
        if(!empty($id) || !empty($hash)){
            //默认先用id查找
            if(!empty($id)){
                $field = array('uri','file','size','extension');
                $where = array('id'=>$id,'status'=>'1');
                $result = Model::factory('Upload_DbOperation')->get_list_sql('',$where,$field,'','file');
                //$result = DB::select('uri','file','size','extension')->from('file')->where('id','=',$id)->where('status','=','1')->execute()->current();
            }else{
                $field = array('uri','file','size','extension');
                $where = array('hash'=>$hash,'status'=>'1');
                $result = Model::factory('Upload_DbOperation')->get_list_sql('',$where,$field,'','file');
                //$result = DB::select('uri','file','size','extension')->from('file')->where('hash','=',$hash)->where('status','=','1')->execute()->current();
            }
            if(!empty($result[0]['uri'])){
                //下载文件
                //'\'转'/'
                $absolutely_path = Tool::factory('Dir')->dir_path(self::$config['security_path']).$result[0]['uri'];
                if(is_file($absolutely_path)) {
                    $this->_render(array('status'=>true,'msg'=>$this->message['Upload::get_success'],'extension'=>$result[0]['extension'],'file'=>$result[0]['file'],'size'=>$result[0]['size'],'url'=>$result[0]['uri'],'binary'=>base64_encode(file_get_contents($absolutely_path))));
                }else{
                    $this->_render(array('status'=>false,'msg'=>$this->message['Path::empty']));
                }
            }else{
                $this->_render(array('status'=>false,'msg'=>$this->message['File::nofile']));
            }
        }else{
            $this->_render(array('status'=>false,'msg'=>$this->message['Request::empty']));
        }
    }
    //直接下载文件
    public function action_Get() {
        $hash = isset($this->get['hash'])?addslashes($this->get['hash']):"";
        $id = isset($this->get['id'])?(int)$this->get['id']:0;
        $this->download_check();
        //组合数据，插入数据
        if(!empty($id) || !empty($hash)){
            //默认先用id查找
            if(!empty($id)){
                $field = array('uri','file','size','extension');
                $where = array('id'=>$id,'status'=>'1');
                $result = Model::factory('Upload_DbOperation')->get_list_sql('',$where,$field,'','file');
                //$result = DB::select('uri','file','size','extension')->from('file')->where('id','=',$id)->where('status','=','1')->execute()->current();
            }else{
                $field = array('uri','file','size','extension');
                $where = array('hash'=>$hash,'status'=>'1');
                $result = Model::factory('Upload_DbOperation')->get_list_sql('',$where,$field,'','file');
               // $result = DB::select('uri','file','size','extension')->from('file')->where('hash','=',$hash)->where('status','=','1')->execute()->current();
            }
            if(!empty($result[0]['uri'])){
                $absolutely_path = Tool::factory('Dir')->dir_path(self::$config['security_path']).$result[0]['uri'];
                if(is_file($absolutely_path)) {
                    $content = file_get_contents($absolutely_path);
                    $mime =  File::mime_by_ext($result[0]['extension']);
                    //$this->response->body($content)->headers('content-type',$mime);
                    //$this->response->body($content)->headers(array('content-type'=>$mime,'content-size'=>$result[0]['size'],'content-extension'=>$result[0]['extension'],'content-file'=>$result[0]['file']));
                    $arr_header = array('type'=>$mime,'size'=>$result[0]['size'],'extension'=>$result[0]['extension'],'file'=>$result[0]['file']);
                    $this->response->body($content)->headers(array('CLIENTSIGN'=>json_encode($arr_header)));
                    //Tool::factory('Debug')->D($this->response->headers('CLINK_SKIN'));
                    //$this->response->body(json_encode(array('status'=>true,'msg'=>'获取成功','extension'=>$result['extension'],'file'=>$result['file'],'size'=>$result['size'],'binary'=>base64_encode(file_get_contents($absolutely_path)))))->headers('content-type', 'application/json');
                }else{
                    $this->_render(array('status'=>false,'msg'=>$this->message['Path::empty']));
                }
            }else{
                $this->_render(array('status'=>false,'msg'=>$this->message['File::nofile']));
            }
        }else{
            $this->_render(array('status'=>false,'msg'=>$this->message['Request::nofile']));
        }

    }
    #上传文件
    #变量json_encode(_file=｛'name':文件名，'size':文件大小，'binary':文件二进制码｝)（post）
    #
    public function action_Save() {

        //Tool::factory('Debug')->D(array($_SERVER['HTTP_CLIENTSIGN'],md5(json_encode($_POST).'xqP8MuCMZEhJuOaSbL')));
        //上传验证
        $this->save_check();
        //获取client_key,权限认证

        if(!isset($this->client_arr['id'])){
            $this->_render(array('status'=>false,'msg'=>$this->message['Upload::nopower']));
        }
        //组合数据，插入数据
        $result = SecurityFile::upLoad($this->client_arr['id'],$this->file);
        if($result['status']){
            $data = array('status'=>true,'msg'=>$result['msg'],'file'=>$this->file->name,'hash'=>$result['hash'],'size'=>$this->file->size);
            $this->_render($data);
        }else{
            $data = array('status'=>false,'msg'=>$result['msg']);
            $this->_render($data);
        }
         //$this->response->body(json_encode($data))->headers('content-type', 'application/json');
    }

    //文件删除
    public function action_Delete(){
        $hash = isset($this->get['hash'])?addslashes($this->get['hash']):"";
        $id = isset($this->get['id'])?(int)$this->get['id']:0;
        $this->download_check();

        if(!empty($id) || !empty($hash)){
            if(!empty($id)){
                $total_rows = DB::update('file')->set(array('status'=>'3',))->where('id','=',$id)->where('status','!=','3')->execute() ;
            }else{
                $total_rows = DB::update('file')->set(array('status'=>'3',))->where('hash','=',$hash)->where('status','!=','3')->execute() ;
            }
            if($total_rows){
                $this->_render(array('status'=>true,'msg'=>$this->message['Delete::success']));
                //删除成功
            }else{
                $this->_render(array('status'=>false,'msg'=>$this->message['Delete::fail']));
                //删除失败
            }
        }else{
            $this->_render(array('status'=>false,'msg'=>$this->message['Delete::get_empty']));
        }
    }
    //修改不显示图片
    public function action_ModifyPicture(){
        $this->modify_check();
        $uploadModel = Model::factory('Upload_DbOperation');
        $hash = isset($this->post['hash'])?addslashes($this->post['hash']):"";
        $imageBinary = isset($this->post['imageBinary'])?$this->post['imageBinary']:0;
        $arr_file = $uploadModel->get_image_path($hash);
        $path = self::$config['security_path'].$arr_file['uri'];
        /*------------------备份开始---------------*/
        $flash_dir = self::$config['security_path'].'backups/';
        if( !is_dir($flash_dir) ){
            Tool::factory('Dir')->dir_create($flash_dir,'755');
        }
        $backups_path_file_name = $flash_dir.$arr_file['hash'].'.jpg';
        //只备份一次图片(如果存在跳过)
        if(file_exists($backups_path_file_name)){
        }else{
            $file_parh = fopen($backups_path_file_name,"w");
            if(fwrite($file_parh,file_get_contents($path))){
                fclose($file_parh);
            }else{
                fclose($file_parh);
                $this->_render(array('status'=>false,'msg'=>'备份失败!'));
            };
        }
        /*------------------备份结束---------------*/

        /*------------------修改图片开始---------------*/
        $file_parh = fopen($path,"w");
        //if(fwrite($file_parh,file_get_contents($path))){
        if(fwrite($file_parh,base64_decode($imageBinary))){
            fclose($file_parh);
            //修改图片大小
            $uploadModel->set_imageinfo_tohash(array('size'=>filesize($path)),$hash);
            $this->_render(array('status'=>true,'msg'=>'成功!','binary'=>base64_encode(file_get_contents($path))));
        }else{
            fclose($file_parh);
            $this->_render(array('status'=>false,'msg'=>'修改失败!'));
        };
        /*------------------修改图片结束---------------*/

    }


}