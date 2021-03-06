<?php
/**
 * Created by PhpStorm.
 * Permission: liujinsheng
 * Effect:文件上传接口
 * *  Tool::factory('Debug')->D($this->controller);
 *  Tool::factory('Debug')->array2file($array, $filename);
 *  Tool::factory('Debug')->array2file($this->post, APPPATH.'../static/ui_bootstrap/liu_test.txt');
 *
 */
class Controller_API_PdfOp extends AdminController {

    protected $_model = null;

    public function before() {

        parent::before();
        $this->_model['pdf'] = Model::factory('PdfOperation');
    }
    //直接下载文件
    public function action_Get(){

        $this->download_check();
        $order_id = isset($this->get['order_id'])?(int)$this->get['order_id']:0;
        $issign = isset($this->get['type'])?(int)$this->get['type']:1;   //0为准备盖章的,1为盖过章的
        //组合数据，插入数据
        if(!empty($order_id)){
            //查找传过来的订单是否有盖章合同
            $record = $this->_model['pdf']->get_seal_path_wl($order_id,$issign);
            if(!empty($record)){
                //$issign1为盖章合同
                //$path = $this->_model['pdf']->get_pdf_path('tc_pdf_record',$record['docid']);
                $path = $record['inputdir'].$record['filename'];
                $pathDir = self::$config['security_path'].$path;
                //读取二进制流
                if(is_file($pathDir)) {
                    $content = file_get_contents($pathDir);
                    //大小
                    $fsize = filesize($pathDir);
                    $arr_header = array('name'=>$record['filename'],'size'=>$fsize,'extension'=>'pdf','path'=>$path);
                    $this->response->body($content)->headers(array('CLIENTSIGN'=>json_encode($arr_header)));
                }else{
                    $this->_render(array('status'=>false,'msg'=>$this->message['Path::empty']));
                }
            }else{
                $this->_render(array('status'=>false,'msg'=>$this->message['Request::nofile']));
            }
        }else{
            $this->_render(array('status'=>false,'msg'=>$this->message['Request::nofile']));
        }

    }
    //保存pdf文件(订单id,图片二进制流)
    public function action_Save() {

        $this->modify_check();

        if(!isset($this->post['pdf'])||empty($this->post['pdf'])){
            $this->_render(array('status'=>false,'msg'=>$this->message['Request::nofile']));
        }
        $pdf = json_decode($this->post['pdf'],true);

        $recordInfo = $this->_model['pdf']->get_pdf_record($pdf['docid']);

        if(empty($recordInfo)){
            $this->_render(array('status'=>false,'msg'=>$this->message['Request::nofile']));
        }
        $inputDir = self::$config['security_path'].$recordInfo['inputdir'];
        if( !is_dir($inputDir) ){
            Tool::factory('Dir')->dir_create($inputDir,'755');
        }
        $outputDir = self::$config['security_path'].$recordInfo['outputdir'];
        if( !is_dir($outputDir) ){
            Tool::factory('Dir')->dir_create($outputDir,'755');
        }


        //插入pdf文件
        $pdfInputdirFile = $inputDir.$recordInfo['filename'];
        $pdfFile = fopen($pdfInputdirFile,"w");

        if(fwrite($pdfFile,base64_decode($pdf['inputdir']['binary']))){
            fclose($pdfFile);
            //保存数据
            //重新获取文件大小
            if(is_file($pdfInputdirFile)){
                $inputFilesize = filesize($pdfInputdirFile);
            }
        }else{
            fclose($pdfFile);
            $this->_pdf_render(array('status'=>false,'msg'=>$pdf['docid'].'上传失败!'));
        };
        //插入合同文件
        $pdfOutputdirFile = $outputDir.$recordInfo['filename'];
        $pdfFile = fopen($pdfOutputdirFile,"w");
        if(fwrite($pdfFile,base64_decode($pdf['outputdir']['binary']))){
            fclose($pdfFile);
            //保存数据
            //重新获取文件大小
            if(is_file($pdfInputdirFile)){
                $outputFilesize = filesize($pdfInputdirFile);
            }
        }else{
            fclose($pdfFile);
            $this->_pdf_render(array('status'=>false,'msg'=>$pdf['docid'].'上传失败!'));
        };



        if(isset($inputFilesize)&&isset($outputFilesize)){
            //修改record表(大小保存最大的)

            $result = $this->_model['pdf']->update_pdf_record($pdf['docid'],array('status'=>2,'size'=>$outputFilesize));
            if($result){
                $this->_pdf_render(array('status'=>true,'msg'=>$pdf['docid'].'上传成功!'));
            }else{
                $this->_pdf_render(array('status'=>false,'msg'=>$pdf['docid'].'上传失败!'));
            }
        }else{
            $this->_pdf_render(array('status'=>false,'msg'=>$pdf['docid'].'上传失败!'));
        }
       // Tool::factory('Debug')->array2file(array(self::$config,$pdf['inputdir']['path']), APPPATH.'../static/ui_bootstrap/liu_test.php');
        
    }


    //单文件上传
    public function action_SaveOne() {

        $this->modify_check();
        if(!isset($this->post['pdf'])||empty($this->post['pdf'])){
            $this->_render(array('status'=>false,'msg'=>$this->message['Request::nofile']));
        }
        $pdf = json_decode($this->post['pdf'],true);
        $recordInfo = $this->_model['pdf']->get_pdf_record($pdf['docid']);
        if(empty($recordInfo)){
            $this->_render(array('status'=>false,'msg'=>$this->message['Request::nofile']));
        }
        $inputDir = self::$config['security_path'].$recordInfo['inputdir'];
        if( !is_dir($inputDir) ){
            Tool::factory('Dir')->dir_create($inputDir,'755');
        }

        //插入pdf文件
        $pdfInputdirFile = $inputDir.$recordInfo['filename'];
        $pdfFile = fopen($pdfInputdirFile,"w");

        if(fwrite($pdfFile,base64_decode($pdf['inputdir']['binary']))){
            fclose($pdfFile);
            //保存数据
            //重新获取文件大小
            if(is_file($pdfInputdirFile)){
                $inputFilesize = filesize($pdfInputdirFile);
            }
        }else{
            fclose($pdfFile);
            $this->_pdf_render(array('status'=>false,'msg'=>$pdf['docid'].'上传失败!'));
        };

        if(isset($inputFilesize)){
            //修改record表(大小保存最大的)
            $result = $this->_model['pdf']->update_pdf_record($pdf['docid'],array('size'=>$inputFilesize));
            if($result){
                $this->_pdf_render(array('status'=>true,'msg'=>$pdf['docid'].'上传成功!'));
            }else{
                $this->_pdf_render(array('status'=>false,'msg'=>$pdf['docid'].'上传失败!'));
            }
        }else{
            $this->_pdf_render(array('status'=>false,'msg'=>$pdf['docid'].'上传失败!'));
        }

    }





    public function _pdf_render($data){

        //$this->response->body(json_encode($data))->headers('content-type', 'application/json');
        //记录调用接口信息
        $data_lig['req_data'] = null;
        if($data){
            $data_lig['resp_data'] = $data;
            if(count($data)>2){
                unset($data_lig['resp_data']['binary']);
            }
        }

        if($this->controller){
            $data_lig['provider'] = $this->controller;
        }
        if($this->action){
            $data_lig['action'] = $this->action;
        }

        if(isset($_SERVER['HTTP_CLIENTID'])){
            $data_lig['clientid'] = $_SERVER['HTTP_CLIENTID'];
        }
        Model::factory('PdfOperation')->message_pdf_log($data_lig);
        echo json_encode($data);
        die;
    }

}