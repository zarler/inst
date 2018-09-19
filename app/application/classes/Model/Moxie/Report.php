<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/14
 * Time: 下午5:21
 */
class Model_Moxie_Report extends Model
{
    const TAOBAO = 'taobao';
    const JINGDONG = 'jingdong';
    const Bank = 'bank';    //魔蝎网银
    const Chsi = 'chsi';    //魔蝎学信网
    const Email = 'email';  //魔蝎邮箱
    const FUND = 'fund';    //魔蝎公积金
    const SOCIAL_SECURITY = 'socialsecurity';    //魔蝎社保
    const ZHIXING = 'zhixing';    //魔蝎法院被执行人


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
        $table = 'moxie_report';
        $_data = [
            'user_id' => $data['user_id'],
            'data' => $data['data'],
            'provider' => $data['provider'],
            'action' => $data['action'],
            'type' => $data['type'],
            'create_time' => time(),
        ];

        return $this->_add($table, $_data);

    }

    public function get_report($user_id, $action,$type)
    {
        if ($user_id < 1 || !$action || !$type) {
            return false;
        }

        return DB::select()->from('moxie_report')->where('user_id', '=', $user_id)->and_where('action', '=',
                $action)->and_where('type','=',$type)->order_by('create_time','desc')->execute()->current();

    }

}