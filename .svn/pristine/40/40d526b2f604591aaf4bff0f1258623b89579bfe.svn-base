<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 2018/1/9
 * Time: 下午5:57
 */
class Model_Emay_Short extends Model
{
    const EMAY_MODULE = 'riskservice';
    const STATUS_YES = 1;
    const STATUS_NO = 2;


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

        $table = 'emay_short';
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
        return DB::select("*")->from("emay_short")->where('user_id','=',$user_id)->execute()->current();
    }

    public function change($id,$data){
        if(!$id){
            return FALSE;
        }
        list($id, $num) = DB::update('emay_short')->set($data)->where('id', '=', $id)->execute();
        return $num;
    }

}