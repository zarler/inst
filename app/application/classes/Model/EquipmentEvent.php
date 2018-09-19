<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 17/6/26
 * Time: 下午3:00
 *
 * 设备行为记录
 *
 */
class Model_EquipmentEvent extends Model_Database {

    const TYPE_REG = 'reg';
    const TYPE_CARD_AUTH = 'card_auth';
    const TYPE_FACEID_AUTH = 'faceid_auth';
    const TYPE_LOAN = 'loan';
    const TYPE_LOGIN = 'login';
    const TYPE_CREDIT_INFO = 'credit_info';

    const TYPE2_INSTLOAN = 'instloan';
    const TYPE2_CREDIT_INFO = 'apply';


    public function create($array=[]){
        $equipment_id = isset($array['equipment_id']) && $array['equipment_id'] ? $array['equipment_id'] :'';
        $type = isset($array['type']) && $array['type'] ? $array['type'] :'';

        if(!$equipment_id || !$type ){
            return FALSE;
        }
        $md5 = md5($equipment_id);
        $type2 = isset($array['type2']) && $array['type2'] ? $array['type2'] :'';
        $create_time = isset($array['create_time']) && $array['create_time'] ? (int)$array['create_time'] : time();
        $user_id = isset($array['user_id']) && $array['user_id'] ? (int)$array['user_id'] :0;
        if(isset($array['extend']) && $array['extend'] && $array['extend']!==NULL){
            if(is_array($array['extend'])){
                $extend = json_encode($array['extend']);
            }else{
                $extend = $array['extend'];
            }
        }else{
            $extend = '';
        }
        $token =  isset($array['token']) && $array['token'] ? $array['token'] :'';

        list($insert_id,$rows) = DB::insert('equipment_event_log',['md5','equipment_id','type','type2','user_id','create_time','token','extend'])
            ->values([$md5, $equipment_id, $type, $type2, $user_id, $create_time, $token, $extend])
            ->execute();
        return $insert_id;
    }


    public function getByEquipmentId($equipment_id,$limit=100) {
        return DB::select()->from('equipment_event_log')->where('md5','=',md5($equipment_id))->order_by('id','desc')->execute()->as_array();
    }

    public function has($equipment_id,$type1=NULL,$type2=NULL) {
        $query = DB::select('id')->from('equipment_event_log')->where('md5','=',md5($equipment_id));
        if($type1){
            $query->and_where('type','=',$type1);
            if($type2){
                $query->and_where('type2','=',$type2);
            }
        }
        $rs = $query->limit(1)->execute()->current();
        if(isset($rs['id'])){
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 根据用户id获得最后一条数据
     * @param $user_id
     * @param array $where
     * @param array $order_by
     * @return mixed
     */
    public function getOneByUserId($user_id, $where = [], $order_by = []) {
        $query = DB::select()->from('equipment_event_log')->where('user_id','=',$user_id);

        if(count($where) > 0){
            foreach($where as $k => $v){
                $query->and_where($k, '=', $v);
            }
        }
        if(count($order_by) > 0){
            foreach ($order_by as $ob) {
                $query->order_by($ob[0], $ob[1]);
            }
        }

        $ret = $query->order_by('id','desc')->execute()->current();

        return $ret;
    }
}
