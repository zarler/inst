<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: liujinsheng 合同生成model
 * Date: 15/12/15
 * Time: 上午11:43
 *  Tool::factory('Debug')->D($this->controller);
 * 权限MODEL
 */

class Model_Seal extends Model_Database {

    public $table = null;
    public $config = null;

    const SEAL = 1;     //未盖章
    const SEALING = 2;  //等待盖章
    const SEALED = 3;  //已盖章

    public $status_array = array(
        self::SEAL => '未盖章',
        self::SEALING => '等待盖章',
        self::SEALED => '已盖章',
    );

    public $seal_array = array(
        '0102010030' => '星果科技',
        '0102010000' => '耀盛汇融投资管理（北京）有限公司',
        '0102010020' => '耀盛商业保理有限公司',
    );

    public function __construct($config = array()) {
        $this->session = Session::instance('database');
        $this->config = Kohana::$config->load('database')->get('default');
        if(!isset($this->config['table_prefix']) || empty($this->config['table_prefix'])  ) {
            echo 'site config (config/database.php)  table_prefix is null.';
            exit;
        }
    }
    private function queryBuilder($query,$array = array()) {
       
        if (isset($array['status']) && $array['status'] != '') {
            if(is_array($array['status'])){
                $query->where('status', '<>', intval($array['status']));
            }else{
                $query->where('status', '=', intval($array['status']));
            }

        }
        if (isset($array['file']) && $array['file'] != '') {
            $query->and_where('file', '=', $array['file']);
        }

        if (isset($array['filename']) && $array['filename'] != '') {
            $query->and_where('filename', '=', $array['filename']);
        }

        if (isset($array['extension']) && $array['extension'] != '') {
            $query->and_where('extension', '=', $array['extension']);
        }
        if (isset($array['hash']) && $array['hash'] != '') {
            $query->and_where('hash', '=', $array['hash']);
        }
        if(isset($array['time_start']) && $array['time_start']>0) {
            $query->and_where('create_time','>=',$array['time_start']);
        }
        if(isset($array['time_end']) && $array['time_end']>0) {
            $query->and_where('create_time','<=',$array['time_end']);
        }
        if(isset($array['name']) && $array['name']) {
            $query->and_where('name','=',$array['name']);
        }
        if(isset($array['orderid']) && $array['orderid']>0) {
            $query->and_where('id','=',$array['orderid']);
        }
        return $query;
    }

    public function  getList($array = array(), $perpage = 20, $page = 1) {

        $query = DB::select(

            array('order.id','order_id'),array('order.name','name'),array('pdf_record.id','id'),array('pdf_record.docid','docid'),
            array('pdf_record.filename','filename'),array('pdf_record.user_id','user_id'),
            array('pdf_record.outputdir','outputdir'),array('pdf_record.create_time','create_time'),
            array('pdf_record.status','status')

        )->from('order')->join('pdf_record','LEFT')->on('order.id',"=","pdf_record.order_id");
        
        if (count($array) > 0) {
            $query = $this->queryBuilder($query,$array);
        }

        $query->where('order.pay_lock',"=",Model_Order::LOCK); 

         $rs = $query->order_by('order.id', 'desc')->offset($perpage * ($page - 1))->limit($perpage)->execute()->as_array();

        if($rs){

            foreach ($rs as $key => $value) {
               
                $docid  = $this->getSealIdByDocId($value['docid']);
                $issign = $this->getSealIdByDocId($value['docid']);
                $rs[$key]['seal_name'] = isset($this->seal_array[$docid])?$this->seal_array[$docid]:'';
                $rs[$key]['issign'] = isset($getIsSign['issign'])?$getIsSign['issign']:'';
               
            }
        }
 
        
        return $rs;
    }




    public function getTotal($array) {
        
        $query = DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('order')->join('pdf_record','LEFT')->on('order.id',"=","pdf_record.order_id");
        
        if (count($array) > 0) {
            $query = $this->query_builder($query,$array);
        }

        $query->where('order.pay_lock',"=",Model_Order::LOCK); 
        $rs = $query->execute()->current();
        return isset($rs['total']) ? $rs['total'] : 0;
    }

    private function getSealIdByDocId($docid=NULL){
         
         if(!$docid){
            return false;
         }

         $rs = DB::select()->from('signinfo')->where('docid','=',$docid)->execute('cfca')->current();

         return isset($rs['sealid'])?$rs['sealid']:'';


    }
  

  //查询盖章编号
    private function getIsSign($docid=NULL){
        return   DB::select('issign')->from('signdoc')->where('docid','=',$docid)->execute('cfca')->current();

    }
 
    
}