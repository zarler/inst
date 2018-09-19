<?php
/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/29
 * Time: 下午2:10
 */
defined('SYSPATH') or die('No direct script access.');

class Model_ZhiCheng_Data extends Model
{
    private function _add($table, $data)
    {
        $_keys = array_keys($data);
        $_vals = array_values($data);

        $insert = DB::insert($table)
            ->columns($_keys)
            ->values($_vals);

        list($insert_id, $affected_rows) = $insert->execute();

        return $insert_id;
    }

    public function create($data)
    {
        $table = 'zhicheng_data';
        $_data = [
            'request_data' => $data['request_data'],
            'module' => $data['module'],
            'response_data' => $data['response_data'],
            'user_id' => $data['user_id'],
            'create_time' => time(),
        ];

        return $this->_add($table, $_data);
    }

    public function get_data($user_id, $module)
    {

        if ($user_id < 1) {
            return false;
        }

        return DB::select()->from('zhicheng_data')->where('user_id', '=', $user_id)->and_where('module', '=',
            $module)->execute()->current();
    }

}

