<?php

/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 2018/1/10
 * Time: 下午7:02
 */
class Model_User_Identity extends Model_Database
{
    const STATUS_INIT = 0;
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;
    const STATUS_TIMEOUT = 3;
    const STATUS_EXCEPTION = 4;

    public function create($id_key, $array)
    {
        if ($id_key == '' || !isset($array['name']) || !isset($array['identity_code']) || !isset($array['sex'])) {
            return false;
        }

        $name = $array['name'];
        $identity_code = $array['identity_code'];
        $sex = $array['sex'];
        $create_time = time();

        list($insert_id, $affected_rows) = DB::insert("user_identity", ['idkey', 'name', 'code', 'sex', 'status', 'create_time'])->values([$id_key, $name, $identity_code, $sex, self::STATUS_INIT, $create_time])->execute();
        return $insert_id;
    }

    public function find_one_by_idKey($idKey,$status=[self::STATUS_VALID])
    {
        if ($idKey == '') {
            return false;
        }
        return DB::select()->from('user_identity')->where('idkey', '=', $idKey)->and_where('status', 'in', $status)->execute()->current();
    }

    /**
     * 更新
     * @param $idKey
     * @param $array
     * @return bool
     */
    public function update($idKey, $array)
    {
        if ($idKey == '') {
            return false;
        }
        $update = array();
        if (isset($array['status'])) {
            $update['status'] = $array['status'];
        }
        if (isset($array['identity_face'])) {
            $update['identity_face'] = $array['identity_face'];
        }
        DB::update('user_identity')->set($update)
            ->where('idkey', '=', $idKey)
            ->execute();
        return true;
    }

    public function timeout(){
        return DB::update('user_identity')->set(array('status'=>self::STATUS_TIMEOUT))->where('status','=',self::STATUS_INIT)->and_where('create_time','<',time()-600)->execute();
    }
}