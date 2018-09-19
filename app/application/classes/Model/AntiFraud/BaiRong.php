<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: caojiabin
 * Date: 16/10/13
 * Time: 上午11:15
 */
class Model_AntiFraud_BaiRong extends Model_Database
{
    // 百融反欺诈模块对应的状态
    const STATUS_BAIRONG_MODEL_DEFAULT      = 0; // 默认
    const STATUS_BAIRONG_MODEL_HIT          = 1; // 命中
    const STATUS_BAIRONG_MODEL_NOT_HIT      = 2; // 未命中

    // 保存百融设备反欺诈信息
    public function add_equipment($data){
        $key 	= array_keys($data);
        $value 	= array_values($data);
        list($id, $num) = DB::insert('bairong_equipment', $key)->values($value)->execute();
        return $id;
    }

    // 百融简表添加数据
    public function add_short($data){
        $key 	= array_keys($data);
        $value 	= array_values($data);
        list($id, $num) = DB::insert('bairong_short', $key)->values($value)->execute();
        return $num;
    }

    // 百融简表修改
    public function edit_short($id, $data){
        if(!$id){
            return FALSE;
        }
        list($id, $num) = DB::update('bairong_short')->set($data)->where('id', '=', $id)->execute();
        return $num;
    }

    // 百融简表查询
    public function get_short_one_by_user_id($user_id=0, $model=''){
        return DB::select()
            ->from('bairong_short')
            ->where('user_id', '=', $user_id)
            ->and_where('module', '=', $model)
            ->execute()
            ->current();
    }

    // 添加百融记录
    public function add_bairong_data($data){
        $key 	= array_keys($data);
        $value 	= array_values($data);
        list($id, $num) = DB::insert('bairong_data', $key)->values($value)->execute();
        return $num;
    }

}