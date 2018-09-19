<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 2017/12/28
 * Time: 下午6:03
 */
class Model_QianHai_Short extends Model_Database{

    const USER_QIANHAI_STATUS_INIT = 0;
    const USER_QIANHAI_STATUS_PASS = 1;
    const USER_QIANHAI_STATUS_REJECT = 2;

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

        $table = 'qianhai_short';
        $_data = [
            'user_id' => $data['user_id'],
            'module' => $data['module'],
            'result' => $data['result'],
            'create_time' => time(),
            'pass_time' => time(),
        ];

        return $this->_add($table, $_data);

    }

    public function get_one($user_id)
    {
        return DB::select("*")->from("qianhai_short")->where('user_id','=',$user_id)->execute()->current();
    }

    public function change($id,$data){
        if(!$id){
            return FALSE;
        }
        list($id, $num) = DB::update('qianhai_short')->set($data)->where('id', '=', $id)->execute();
        return $num;
    }



}