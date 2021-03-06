<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: liujinsheng
 * 文件上传读库模型
 */
class Model_PdfOperation extends Model_Database {
    public $table = null;
    public $config = null;

    public function load_config(){
        $this->config = Kohana::$config->load('database')->get('default');
        if(!isset($this->config['table_prefix']) || empty($this->config['table_prefix'])  ) {
            echo 'site config (config/database.php)  table_prefix is null.';
            exit;
        }
    }
    //$type为合同类型(1为盖过章的,0为没有盖过章的)
    public function  get_seal_path($id,$issign=0) {
        return DB::select('tc_pdf_record.docid','tc_pdf_record.filename','tc_pdf_record.outputdir','tc_pdf_record.inputdir','tc_pdf_record.status','signdoc.issign')->from('tc_pdf_record')
            ->join('signdoc')->on('signdoc.docid','=','tc_pdf_record.docid')
            ->where('tc_pdf_record.order_id','=',$id)
            ->where('signdoc.issign','=',$issign)
            ->execute('cfca')->current();
    }


    //运单获取pdf文件
    public function  get_seal_path_wl($id,$issign=1) {

        return DB::select('docid','filename','inputdir','status')->from('pdf_record')
            ->where('order_id','=',$id)
            ->where('status','=',$issign)
            ->execute('pdf')->current();
    }
    
    
    //获得pdf文件
    public function get_path($type,$docID){
        return DB::select('path')->from($type)->where('docid','=',trim($docID))->execute('cfca')->current();
    }
    //获得pdf文件
    public function get_pdf_record($docid = null){
        if(empty($docid)){
            return false;
        }
        return DB::query(Database::SELECT,'SELECT outputdir,inputdir,size,filename FROM tc_pdf_record WHERE docid =\''.$docid.'\'')->execute()->current();
//        return DB::query(Database::SELECT,'SELECT outputdir,inputdir,size,filename FROM tc_pdf_record WHERE docid =\''.$docid.'\'')->execute()->current();

//        return DB::select('outputdir,inputdir,size,filename')
//            ->from('tc_pdf_record')
//            ->where('docid','=',$docid)->compile();
//            ->execute('cfca')
//            ->current();
    }
    //修改tc_pdf_record
    public function update_pdf_record($docid=null,$array = null){
        if(empty($docid)||empty($array)){
            return false;
        }
        return DB::update('pdf_record')->set($array)->where('docid','=',$docid)->execute('pdf');
    }

    //api操作记录
    public function message_pdf_log($array = null) {
        if($array){
            foreach($array as $key => $val){
                switch($key){
                    case 'resp_data':
                        $keyarr[] = 'msg';
                        $valarr[] = $array['resp_data']['msg'];
                        $keyarr[] = 'status';
                        $valarr[] = $array['resp_data']['status'];
                        $keyarr[] = 'resp_data';
                        $valarr[] = json_encode($array['resp_data']);
                        break;
                    case 'req_data':
                        $keyarr[] = 'req_data';
                        $valarr[] = json_encode($array['req_data']);
                        break;
                    default:
                        $keyarr[] = $key;
                        $valarr[] = $val;
                        break;
                }
            }
            $keyarr[] = 'create_time';
            $valarr[] = date('y-m-d h:i:s',time());
            list($insert_id, $total_rows) = DB::insert('log',$keyarr)->values($valarr)->execute();
        }
    }

}
