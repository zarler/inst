<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/3
 * Time: 下午11:43
 */
class Model_Token_Session extends Model_Database{


    protected static $session_data = NULL;
    protected static $token_id =0;

    public function __construct($token_id=NULL) {
        if($token_id!==NULL){
            $this->init($token_id);
        }
    }


    /** 初始化
     * @param $token_id
     */
    public function init($token_id,$data=NULL){
        if( $token_id && $data!==NULL && is_array($data)){
            self::$token_id = $token_id;
            self::$session_data = $data;
        }elseif($token_id){
            self::$token_id = $token_id;
            self::$session_data = $this->get_data($token_id);
            if(!is_array(self::$session_data)){
                if($this->create($token_id,array())){
                    self::$session_data = array();
                }
            }
        }
        return $this;
    }

    /** 设置
     * @param $key
     * @param $value
     */
    public function set($key,$value) {
        self::$session_data[$key] = $value;
        return $this;
    }

    /** 移除
     * @param $key
     */
    public function remove() {
        $vars = func_get_args();
        if($vars){
            foreach($vars as $v){
                if(isset(self::$session_data[$v])){
                    unset(self::$session_data[$v]);
                }
            }
        }
        return $this;
    }

    /** 保存
     * @return bool
     */
    public function save(){
        return $this->update(self::$token_id,self::$session_data);
    }


    public function get($key){
        return isset(self::$session_data[$key]) ? self::$session_data[$key] : NULL ;
    }
    public function get_array(){
        return self::$session_data;
    }



    /** 创建
     * @param $token_id
     * @param array $data
     * @return bool
     */
    public function create($token_id,$data=array()){
        if(!$token_id){
            return FALSE;
        }
        $data_string = is_array($data) ? json_encode($data): $data;
        $create_time = isset($array['create_time']) && $array['create_time']>0 ? (int) $array['create_time'] : time() ;
        list($insert_id,$affected_rows)=DB::insert("app_token_session",array('token_id','data','create_time'))
            ->values(array($token_id, $data_string, $create_time))
            ->execute();
        return $insert_id;
    }


    /** 更新token记录
     * @param $token
     * @param array $array
     * @return bool
     */

    public function update($token_id,$data=array()){
        if(!$token_id){
            return FALSE;
        }
        Lib::factory('TCCache_TokenSession')->token_id($token_id)->remove();//清除缓存
        $data_string = is_array($data) ? json_encode($data): $data;
        return  NULL !== DB::update('app_token_session')->set(array('data'=>$data_string))->where('token_id','=',(int)$token_id)->execute() ;
    }

    /** 获取单条
     * @param $token_id
     * @return bool
     */
    public function get_data($token_id=NULL){
        if(!$token_id){
            return FALSE;
        }
        $rs = DB::select()->from('app_token_session')->where('token_id','=',(int)$token_id)->execute()->current();
        if(isset($rs['data'])){
            return json_decode($rs['data'],TRUE);
        }else{
            return NULL;
        }
    }


    /** 删除
     * @param $token_id
     * @return bool|object
     */
    public function delete($token_id){
        if($token_id){
            return FALSE;
        }
        Lib::factory('TCCache_TokenSession')->token_id($token_id)->remove();//清除缓存
        return DB::delete('app_token_session')->where('token_id','=',(int)$token_id)->execute();
    }




}