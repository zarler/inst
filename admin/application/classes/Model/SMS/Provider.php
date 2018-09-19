<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * Permission: isNum
 * Date: 17/11/24
 * Time: 下午17:27
 */
class Model_SMS_Provider extends Model_Database
{

    public function getOne($id = 0)
    {
        return DB::select()->from('sms_provider')->where('id', '=', $id)->execute()->current();
    }

    //创建
    public function create($array = null)
    {
        if (isset($array['name']) && isset($array['provider'])) {
            $create_array = array(
                'name' => $array['name'],
                'desc' => isset($array['desc']) ? $array['desc'] : '',
                'provider' => $array['provider'],
                'create_time' => time(),
            );

            list($insert_id, $affected_rows) = DB::insert("sms_provider",
                array('name', 'desc', 'provider', 'create_time', 'create_time'))
                ->values($create_array)
                ->execute();
            return $insert_id;
        }
        return false;

    }

    //更改
    public function update($id = 0, $array = null)
    {
        if (!$array) {
            return false;
        }
        unset($array['id']);
        $affected_rows = DB::update('sms_provider')->set($array)->where('id', '=', intval($id))->execute();
        return $affected_rows !== null;
    }

    //构造查询条件
    private function queryBuilder($query, $array = array())
    {
        return $query;
    }


    //查询分页
    public function getList($array = array(), $perpage = 20, $page = 1)
    {

        $query = DB::select()->from('sms_provider');
        $query = $this->queryBuilder($query, $array);
        if ($page < 1) {
            $page = 1;
        }
        $rs = $query->order_by('sms_provider.id','DESC')->offset($perpage * ($page - 1))->limit($perpage)->execute()->as_array();
        return $rs;
    }


    //查询统计
    public function getTotal($array = array())
    {
        $query = DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('sms_provider');
        if (count($array) > 0) {
            $query = $this->queryBuilder($query, $array);
        }
        $rs = $query->execute()->current();
        return isset($rs['total']) ? $rs['total'] : 0;
    }

    //获取全部数据
    public function getAll()
    {
        return DB::select()->from('sms_provider')->execute()->as_array();
    }
}