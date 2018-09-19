<?php
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 15/12/15
 * Time: 下午9:15
 *  Tool::factory('Debug')->D($this->controller);
 * client_id 为配置文件里面site.php配置文件里账号
 * client_key 为配置文件里面site.php配置文件里key值
 *
 *
 */
class Controller_Demo_Index1 extends AdminController {

    public function before(){

        //parent::before();
    }
    public function action_Index(){
            echo 404;
            die;
//        $data = DB::select('file','hash')->from('file')->where('status','=','1')->execute()->as_array();
//        Template::factory('Index',array(
//            '_controller'=>$this->controller,
//            '_data'=>$data
//        )
//        )->response();
    }
    //图片上传入口
    public function action_Save() {
        //判断是否是post过来的文件
        if(isset($_FILES['upfile']['tmp_name'])&&is_uploaded_file($_FILES['upfile']['tmp_name'])){
            // Tool::factory('Debug')->D('是post过来的');
        }else{
            Tool::factory('Debug')->message($this->message['Upload:Illegal']);
        };
        if(!isset(self::$config['client_id'])){
            Tool::factory('Debug')->message($this->message['Upload:client_id_nonull']);
        }
        //组装file数组(name文件名称，size文件大小，binary文件二进制码)
        $file['name'] = $_FILES['upfile']['name'];
        $file['size'] = $_FILES['upfile']['size'];
        $file['binary'] = base64_encode(file_get_contents($_FILES['upfile']['tmp_name']));
        //数组转化为json字符串
        $json_file = json_encode($file);
        //接口调用
        $data = array('_file'=>$json_file);
        $res = HttpClient::factory('http://ps.timecash.cn/API/Operation/Save')->post($data)->httpheader(array("CLIENTID:".self::$config['client_id'],"CLIENTSIGN:".md5(json_encode($data).self::$config['client_key'])))->execute()->body();

        if(!empty($res)){
            $res_obj = json_decode($res);
            if($res_obj->status){
                 Tool::factory('Debug')->message($res_obj->msg);
            }else{
                 Tool::factory('Debug')->message($res_obj->msg);
            }
        }else{
            Tool::factory('Debug')->message($this->message['Upload::fail']);
        }
    }

    #直接获得下载文件
    #hash:识别符和文件存储id选其一（get传值），传入key值（读取验证）
    public function action_Get(){
//        $content = file_get_contents('F:/wamp/www/timecash/ps/application/../../upload/20160225/7ae3329e1101919eee6262a260947ff3.jpg');
//        echo $content;
//        die;
        if(!empty($this->get['hash']) || !empty($this->get['id'])){
            if(!empty($this->get['hash'])){
                $data['hash'] = $this->get['hash'];
            }
            if(!empty($this->get['id'])){
                $data['id'] = $this->get['id'];
            }
        }else{
            Tool::factory('Debug')->message($this->message['Upload::idorhash']);
        }

        //client_id值
        if(empty(self::$config['client_id'])){
            Tool::factory('Debug')->message($this->message['Upload::client_id_nonull']);
        }
        //client_key值
        if(empty(self::$config['client_key'])){
            Tool::factory('Debug')->message($this->message['Upload::client_key_nonull']);
        }
        $result = HttpClient::factory('http://ps.timecash.cn/API/Operation/Get')->get($data)->httpheader(array("CLIENTID:".self::$config['client_id'],"CLIENTSIGN:".md5(json_encode($data).self::$config['client_key'])))->execute();
        //header("Content-type:image/jpeg");
        //echo $result->body();
        //Tool::factory('Debug')->D($result->body());
        //获取头信息并分解
        $result_header = $result->header();
        //获得数据：Clientsign: {"type":"image\/jpeg","size":"85992","extension":"jpg","file":"1.jpg"}
        $str = trim(substr(strstr($result_header,"Clientsign:"),11));
        if(strpos($str, 'Connection:')){
            $str = trim(strstr($str,"Connection:",true),11);
        }
        $arr_header = json_decode($str);
        if($result_header && is_object($arr_header)){
            if(in_array(trim($arr_header->extension),self::$config['file_img_arr'])){
                header("Content-type:".trim($arr_header->type));
                echo $result->body();
                die;
            }else{
                header("Content-type:".trim($arr_header->type));
                header('Content-Disposition:attachment; filename='.trim($arr_header->file));
                header('Content-Length:'.trim($arr_header->size));
                if(!empty($result->body())){
                    //获取二进制信息
                    echo $result->body();
                }else{
                    Tool::factory('Debug')->message($this->message['Upload::get_fail']);
                }
            }
        }else{
            Tool::factory('Debug')->message(json_decode($result->body())->msg);
        }
        //获得文件二进制
        if(!empty($result->body())){
            //获取二进制信息
            echo $result->body();
        }else{
            Tool::factory('Debug')->message($this->message['Upload::get_fail']);
        }
    }
    #直接获得json组合
    #hash:识别符和文件存储id选其一（get传值），传入key值（读取验证）
    #返回：｛file:文件名，size：大小，binary：文件二进制编码}
    public function action_Json(){
        if(!empty($this->get['hash']) || !empty($this->get['id'])){
            if(!empty($this->get['hash'])){
                $data['hash'] = $this->get['hash'];
            }
            if(!empty($this->get['id'])){
                $data['id'] = $this->get['id'];
            }
        }else{
            Tool::factory('Debug')->message($this->message['idorhash']);
        }
        if(empty(self::$config['client_id'])){
            Tool::factory('Debug')->message($this->message['Upload::client_id_nonull']);
        }
        //client_key值
        if(empty(self::$config['client_key'])){
            Tool::factory('Debug')->message($this->message['Upload::client_key_nonull']);
        }
        $res = HttpClient::factory('http://ps.timecash.cn/API/Operation/Json')->get($data)->httpheader(array("CLIENTID:".self::$config['client_id'],"CLIENTSIGN:".md5(json_encode($data).self::$config['client_key'])))->execute()->body();
        if(!empty($res)){
            $res_arr= json_decode($res);

            if($res_arr->status){
                $mime =  File::mime_by_ext($res_arr->extension);
                if(in_array($res_arr->extension,self::$config['file_img_arr'])){
                    header("Content-type:".$mime);
                }else{
                    header("Content-type:".$mime);
                    header('Content-Disposition:attachment; filename='.$res_arr->file);
                    header('Content-Length:'.$res_arr->size);
                }
                $file_content = base64_decode($res_arr->binary);
                echo $file_content;
                die;
            }else{
                echo Tool::factory('Debug')->message($res_arr->msg);
            }
        }
    }
    #删除文件
    #hash:识别符（get）
    #返回：响应行数
    public function action_Delete(){
        $res = HttpClient::factory('http://ps.timecash.cn/API/Operation/Delete')->get($_GET)->httpheader(array("CLIENTID:".self::$config['client_id'],"CLIENTSIGN:".md5(json_encode($_GET).self::$config['client_key'])))->execute()->body();
        if(!empty($res)){
            $res_obj= json_decode($res);
            if($res_obj->status){
                 Tool::factory('Debug')->message($res_obj->msg);
            }else{
                 Tool::factory('Debug')->message($res_obj->msg);
            }
        }else{
            Tool::factory('Debug')->message($this->message['Delete::fail']);
        }
    }

    //测试人脸识别
    public function action_Face(){





        //身份证和名称识别
//        $testurl = 'https://v1-auth-api.visioncloudapi.com/police/idnumber_verification?api_id=ba64913b3dd243cbb2541d2772674d6f&api_secret=a17214dfe3ed423497d67d80ca30fe79';
//        $post_data = array (
//            'id_number'=>'15555',
//            'name'=>'王明');
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $testurl);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
//        curl_setopt($ch, CURLOPT_POST,1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//        $output = curl_exec($ch);
//        $output_array = json_decode($output,true);
//        var_dump($output_array);
//        curl_close($ch);
//        die;



        //图片身份证识别

        $absolutely_path = Tool::factory('Dir')->dir_path(self::$config['security_path']);
        $testurl = 'https://v1-auth-api.visioncloudapi.com/identity/selfie_watermark_verification?api_id=ba64913b3dd243cbb2541d2772674d6f&api_secret=a17214dfe3ed423497d67d80ca30fe79';
        $post_data = array (
            'selfie_file'=>'@'.$absolutely_path.'20160302/49f17a0a76560eacfa7f3e18c40f42ac.jpg',
            'watermark_picture_file'=>$absolutely_path.'20160302/49f17a0a76560eacfa7f3e18c40f42ac.jpg'
        );

        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:multipart/form-data'));
        curl_setopt($ch, CURLOPT_URL, $testurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        echo '<pre>';
        var_dump($output);
        echo '</pre>';
        curl_close($ch);
//        $data['api_id'] = 'ba64913b3dd243cbb2541d2772674d6f';
//        $data['api_secret'] = 'a17214dfe3ed423497d67d80ca30fe79 ';
//        $data['selfie_file'] = intval()'@'.$absolutely_path.'20160302/49f17a0a76560eacfa7f3e18c40f42ac.jpg';
//        $data['historical_selfie_file'] = '@'.$absolutely_path.'20160302/49f17a0a76560eacfa7f3e18c40f42ac.jpg';
//        $res = HttpClient::factory('https://v1-auth-api.visioncloudapi.com/identity/historical_selfie_verification')->post($data)->execute()->body();
//        Tool::factory('Debug')->D($res);
    }





}