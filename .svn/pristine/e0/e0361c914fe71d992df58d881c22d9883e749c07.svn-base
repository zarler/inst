<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: wangxuesong
 * Date:  
 * Time: 下午9:53
 */
class Controller_Seal extends AdminController{


     private static $_seal  = NULL;
     private static $_config = NULL;
     private static $_status =NULL;
     private static $_seal_array=NULL;
 
    //权限映射
    var $permission_array = array(
        'map' => array(
            'List' => array('MakeContract','GetPdfFlow')
        )
    );
    public function before() {
        parent::before();
        self::$_config = Kohana::$config->load('cfca')->get('default');
        self::$_seal = Model::factory('Seal'); 
        self::$_status = Model::factory('Seal')->status_array;
        self::$_seal_array = Model::factory('Seal')->seal_array;
         
    }
    
    public function action_List() {
       
        $page = isset($this->get['page']) ? intval($this->get['page']) : 1;
        $perpage = isset($this->get['pagesize']) ? intval($this->get['pagesize']) : 20;
        
        $array = [];
        if(isset($this->get['name']) && $this->get['name']){
            $array['name'] = trim(addslashes($this->get['name']));
        }

        if(isset($this->get['order_no']) && $this->get['order_no']){
            $array['order_no'] = trim(intval($this->get['order_no']));
        }

         if(isset($this->get['order_id']) && $this->get['order_id']){
            $array['order_id'] = trim(intval($this->get['order_id']));
        }

        //获取全部符合条件的订单
        $list = self::$_seal->getList($array,$perpage,$page);

        $total = self::$_seal->getTotal($array);
        $pagination = Pagination::factory(
            array(
                'total_items' => $total,
                'items_per_page' => $perpage,
            ));
        Template::factory('Seal/List', array(
                'list' => $list,
                '_status'=>self::$_status,
                '_seal_array'=>self::$_seal_array,
                'pagination' => $pagination,
            )
        )->response();
    }
  



}