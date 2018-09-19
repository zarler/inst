<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: caojiabin
 * Date: 16/10/20
 * Time: 下午5:09
 */
class Model_AntiFraud_TongDun extends Model_Database
{

    // 同盾基础表
    public function get_fraud($user_id=0){
        return DB::select()
            ->from('tongdun')
            ->where('user_id', '=', $user_id)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->execute()
            ->current();
    }

    // 同盾明细
    public function get_fraud_rule($seq_id=0){
        return DB::select()
            ->from('tongdun_rules')
            ->where('seq_id', '=', $seq_id)
            ->execute()
            ->as_array();
    }

    //查询同盾明细所有
    public function get_fraud_rule_all($user_id){
        return DB::select()
            ->from('tongdun_rules')
            ->where('user_id', '=', $user_id)
            ->execute()
            ->as_array();
    }

    // 百融简表添加数据
    public function add_short($data){
        $key    = array_keys($data);
        $value  = array_values($data);
        list($id, $num) = DB::insert('tongdun_short', $key)->values($value)->execute();
        return $num;
    }

    // 百融简表修改
    public function edit_short($id, $data){
        if(!$id){
            return FALSE;
        }
        list($id, $num) = DB::update('tongdun_short')->set($data)->where('id', '=', $id)->execute();
        return $num;
    }

    // 百融简表查询
    public function get_short_one_by_user_id($user_id=0, $model=''){
        return DB::select()
            ->from('tongdun_short')
            ->where('user_id', '=', $user_id)
            ->and_where('module', '=', $model)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->execute()
            ->current();
    }

    // 百融主表添加数据
    public function add_fraud($data){
        $key    = array_keys($data);
        $value  = array_values($data);
        list($id, $num) = DB::insert('fraud_tongdun', $key)->values($value)->execute();
        return $num;
    }

    // 获取设备反欺诈信息
    public function get_equipment_data_by_user_id($user_id=0){
        return DB::select()
            ->from('tongdun_equipment')
            ->where('user_id', '=', $user_id)
            ->order_by('id', 'DESC')
            ->execute()
            ->current();
    }

    // 添加同盾记录
    public function add_tongdun_data($data){
        $key    = array_keys($data);
        $value  = array_values($data);
        list($id, $num) = DB::insert('tongdun_data', $key)->values($value)->execute();
        return $num;
    }

    // 查询同盾最近一条成功数据
    public function get_one_success_data($user_id, $life_time = 0){
        $query = DB::select()
            ->from('tongdun_data')
            ->where('user_id', '=', $user_id);
        if($life_time > 0){
            $query->where('create_time', '>', time()-$life_time);
        }

        $rs = $query->and_where('type', '=', '1')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->execute()
            ->current();
        return $rs;
    }

    // 查询同盾失败数据
    public function get_time_limit_error_num($user_id, $life_time = 86400){
        $rs = DB::select(array(DB::expr('COUNT(*)'), 'total'))
                ->from('tongdun_data')
                ->where('user_id', '=', $user_id)
                ->and_where('create_time', '>', time()-$life_time)
                ->and_where('type', '=', '2')
                ->execute()
                ->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }

    // 查询同盾失败数据
    public function get_error_data($user_id){
        return DB::select()
            ->from('tongdun_data')
            ->where('user_id', '=', $user_id)
            ->and_where('type', '=', '2')
            ->execute()
            ->as_array();
    }
    public function add_equipment($data){
        $key    = array_keys($data);
        $value  = array_values($data);
        list($id, $num) = DB::insert('tongdun_equipment', $key)->values($value)->execute();
        return $num;
    }

}