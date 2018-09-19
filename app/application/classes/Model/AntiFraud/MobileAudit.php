<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: majin
 * Date: 17/5/10
 * Time: 下午7:31
 */
class Model_AntiFraud_MobileAudit extends Model_Database
{
    // 欺诈模块对应的状态
    const STATUS_DEFAULT      = 0; // 默认
    const STATUS_HIT          = 1; // 命中
    const STATUS_NOT_HIT      = 2; // 未命中

    // 百融简表添加数据
    public function add_short($data){
        $key 	= array_keys($data);
        $value 	= array_values($data);
        list($id, $num) = DB::insert('credit_mobile_audit_short', $key)->values($value)->execute();
        return $num;
    }

    // 百融简表修改
    public function edit_short($id, $data){
        if(!$id){
            return FALSE;
        }
        list($id, $num) = DB::update('credit_mobile_audit_short')->set($data)->where('id', '=', $id)->execute();
        return $num;
    }

    // 百融简表查询
    public function get_short_one_by_user_id($user_id=0, $model=''){
        return DB::select()
            ->from('credit_mobile_audit_short')
            ->where('user_id', '=', $user_id)
            ->and_where('module', '=', $model)
            ->execute()
            ->current();
    }

}