<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 16/1/28
 * Time: 下午14:19
 */
class Model_SMS extends Model_Database
{

    const VALID = 1;                                //有效
    const INVALID = 2;                            //无效
    const DELETED = 3;                            //删除

    const TYPE_NORMAL = 1;
    const TYPE_OVERDUE = 2;
    const TYPE_AD = 3;

    public $status_array = array(
        self::VALID => '有效',
        self::INVALID => '无效',
        self::DELETED => '已取消(删除)',
    );

    public $types_array = array(
        self::TYPE_NORMAL => '普通',
        self::TYPE_OVERDUE => '催收',
        self::TYPE_AD => '营销',
    );

    //状态名
    public function getStatusName($status_key = 0)
    {
        return isset($this->status_array[$status_key]) ? $this->status_array[$status_key] : null;
    }

    public function getOne($id = 0)
    {
        return DB::select()->from('sms')->where('id', '=', $id)->execute()->current();
    }

    //创建
    public function create($array = null)
    {
        if (isset($array['code']) && isset($array['title'])) {
            $create_array = array(
                'title' => isset($array['title']) ? $array['title'] : '',
                'code' => isset($array['code']) ? $array['code'] : '',
                'body' => isset($array['body']) ? $array['body'] : '',
                'provider_id' => isset($array['provider_id']) ? $array['provider_id'] : '',
                'type' => isset($array['type']) ? intval($array['type']) : self::INVALID,
                'status' => isset($array['status']) ? intval($array['status']) : self::TYPE_NORMAL,
                'create_time' => time(),
            );

            list($insert_id, $affected_rows) = DB::insert("sms",
                array('title', 'code', 'body', 'status', 'type', 'provider_id', 'create_time'))
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
        $affected_rows = DB::update('sms')->set($array)->where('id', '=', intval($id))->execute();
        return $affected_rows !== null;
    }

    //取消(删除)
    public function delete($id = 0)
    {
        return DB::update('sms')->set(array('status' => self::DELETED))->where('id', '=', $id)->execute();
    }

    //构造查询条件
    private function queryBuilder($query, $array = array())
    {

        if (isset($array['title']) && $array['title']) {
            $query->where('title', 'like', '%' . trim($array['title']) . '%');
        }

        if (isset($array['provider_id']) && $array['provider_id']) {
            $query->where('provider_id', '=', $array['provider_id']);
        }

        if (isset($array['type']) && $array['type']) {
            $query->where('type', '=', $array['type']);
        }

        if (isset($array['code']) && $array['code']) {
            $query->where('code', 'like', '%' . trim($array['code']) . '%');
        }

        if (isset($array['status']) && $array['status']) {
            $query->where('status', '=', $array['status']);
        } else {
            $query->where('status', 'IN', array(self::VALID, self::INVALID));
        }

        return $query;
    }


    //查询分页
    public function getList($array = array(), $perpage = 20, $page = 1)
    {

        $query = DB::select()->from('sms');
        $query = $this->queryBuilder($query, $array);
        if ($page < 1) {
            $page = 1;
        }
        $rs = $query->order_by('sms.id',
            'DESC')->offset($perpage * ($page - 1))->limit($perpage)->execute()->as_array();
        return $rs;
    }


    //查询统计
    public function getTotal($array = array())
    {
        $query = DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('sms');
        if (count($array) > 0) {
            $query = $this->queryBuilder($query, $array);
        }
        $rs = $query->execute()->current();
        return isset($rs['total']) ? $rs['total'] : 0;
    }
}