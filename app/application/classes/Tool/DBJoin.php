<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/1/8
 * Time: 下午4:15
 *
 *
 *
 * 链表时按规则拼接时,同时也支持SELECT字段的映射
 * 对两表拼接后重名字段提前指定规则避免覆盖.
 *
 *

  例子
   $_rule = array(
       'user_identity'=>array(
           'type'=>'LEFT',
           'on'=>array('{main}.user_id','=','user_identity.user_id'),
           'column'=> array(
               array('user_identity.id','identity_id'),
               array('user_identity.code','identity_code'),
               array('user_identity.name','identity_name'),
               array('user_identity.status','identity_status'),
               array('user_identity.sex','identity_sex'),
               array('user_identity.identity_face','identity_face'),
           ),
       ),
       'user'=>array(
           'type'=>'LEFT',
           'on'=>array('{main}.user_id','=','user.id'),
           'column'=> array(
               array('user.id','user_id'),
               array('user.mobile','user_mobile'),
               array('user.name','user_name'),
               array('user.username','user_username'),
               array('user.email','user_email'),
               array('user.status','user_status'),
           ),
       ),
   );

载入配置
$join = Tool::factory('DBJoin')->query($query)->main_table('order')->rule($_rule);
$join = Tool::factory('DBJoin')->set('main_table','$_rule',$query);

查询字段
$query = DB::select_array($join->join_field(array('user','user_identity')))->from();
$query->where(...);
JOIN表
$query = $join->join_disinct($query,array('user','user_identity'));

$rs = $query->execute()->as_array();

*/
class Tool_DBJoin {


    protected $_rule;
    protected $_join = array();
    protected $_query;
    protected $_main_table;
    protected $_main_table_tag ='{main}';

    public function __construct($main_table=NULL,$rule=NULL) {
        if($main_table){
            $this->_main_table = $main_table;
        }
        if($rule){
            $this->_rule = $rule;
        }
    }


    public function main_table($main_table=NULL) {
        if($main_table!==NULL) {
            $this->_main_table = $main_table;
        }
        return $this;
    }

    public function rule($rule=NULL) {
        if($rule!==NULL && is_array($rule)){
            $this->_rule = $rule;
        }
        return $this;
    }

    public function query($query=NULL) {
        if($query!==NULL){
            $this->_query = $query;
        }
        return $this;
    }

    public function set($main_table=NULL,$rule=NULL,$query=NULL) {
        if($main_table!==NULL) {
            $this->_main_table = $main_table;
        }
        if($rule!==NULL && is_array($rule)) {
            $this->_rule = $rule;
        }
        if($query!==NULL) {
            $this->_query = $query;
        }
        return $this;
    }

    //检查是否已经 JOIN过
    public function has_join($table_name=NULL){
        return is_string($table_name) && is_array($this->_join) && in_array($table_name,$this->_join);
    }


    public function clear(){
        $this->_array = NULL;
        $this->_join = NULL;
        $this->_main_table = NULL;
        $this->_query = NULL;
        return $this;
    }

    //按照_rule的规则制造DB::select_array() 所需的字段数组
    public function join_field($join=NULL){
        if(is_string($join)){
            $join = array($join);
        }
        $cols[]=$this->_main_table.'.*';
        foreach($this->_rule as $k => $v){
            if(in_array($k,$join)) {
                if(isset($v['column'])){
                    foreach($v['column'] as $col) {
                        if(is_array($col)){
                            $cols[] = $col;
                        }else{
                            $cols[] = $this->_main_table.$col;
                        }
                    }
                }
            }
        }
        return $cols;
    }

    public function join_table($query=NULL,$join=NULL){
        if($query!==NULL  || $join !== NULL ) {
            if (is_object($query)) {
                $this->query($query);
            }
            if (is_string($join)) {
                $join = array($join);
            }

            if (is_array($query)) {
                $join = $query;
            } elseif (is_string($query)) {
                $join = array($query);
            }
            //var_dump($this->_query);
            foreach ($this->_rule as $k => $r) {
                if (in_array($k, $join)) {
                    if (isset($r['on'])) {
                        $this->_join[] = $k;
                        isset($r['type']) ? $this->_query->join($k, $r['type']) : $this->_query->join($k);
                        $this->_query->on(str_ireplace($this->_main_table_tag, $this->_main_table, $r['on'][0]), $r['on'][1], $r['on'][2]);
                    }
                }
            }
        }
        return $this->_query;
    }

    //集成检测,不重复join
    public function join_distinct($query=NULL,$join=NULL){
        if($query!==NULL  || $join !== NULL ){
            if(is_object($query)){
                $this->query($query);
            }
            if(is_string($join)) {
                $join = array($join);
            }

            if(is_array($query)) {
                $join = $query;
            }elseif(is_string($query)) {
                $join = array($query);
            }
            foreach($join as $j){
                if(!$this->has_join($j)){
                    $this->join_table(array($j));
                }
            }

        }
        return $this->_query;
    }

}