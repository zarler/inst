<?php
/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/26
 * Time: 下午5:29
 */

class Model_ZhiCheng_Short extends Model
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

        $table = 'zhicheng_short';
        $_data = [
            'user_id' => $data['user_id'],
            'module' => $data['module'],
            'result' => $data['result'],
            'create_time' => time(),
        ];

        return $this->_add($table, $_data);

    }

    public function get_one($user_id, $module)
    {
        if ($user_id < 1 || !$module) {
            return false;
        }

        return DB::select()->from('zhicheng_short')->where('user_id', '=', $user_id)->and_where('module', '=',
            $module)->execute()->current();
    }

    public function change($id,$data){
        if(!$id){
            return FALSE;
        }
        list($id, $num) = DB::update('zhicheng_short')->set($data)->where('id', '=', $id)->execute();
        return $num;
    }

}