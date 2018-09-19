<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/29
 * Time: 上午11:09
 *
 * 银行
 */
class Model_Bank extends Model_Database
{

    const STATUS_VALID = 1;                                //有效
    const STATUS_INVALID = 2;                            //无效
    const STATUS_DELETED = 3;                            //已取消(删除)


    //单条数据
    public function get_one($id, $status = null)
    {
        $query = DB::select()->from('bank')->where('id', '=', $id);
        if ($status !== null) {
            if (is_array($status)) {
                $query->and_where('status', 'IN', $status);
            } else {
                $query->and_where('status', '=', $status);
            }
        }

        return $query->execute()->current();
    }

    public function get_all($status = null)
    {
        $query = DB::select()->from('bank');
        if ($status !== null) {
            if (is_array($status)) {
                $query->and_where('status', 'IN', $status);
            } else {
                $query->and_where('status', '=', $status);
            }
        }

        return $query->order_by('rank', 'DESC')->execute()->as_array();
    }


    public function get_array($status = null)
    {
        $bank = $this->get_all($status);
        $array = [];
        foreach ($bank as $v) {
            $array[$v['id']] = $v;
        }

        return $array;
    }


    public function get_id_by_code($bank_code)
    {
        $rs = DB::select('id')->from('bank')->where('code', '=', $bank_code)->execute()->current();

        return isset($rs['id']) ? (int)$rs['id'] : 0;
    }


    public function valid($bank_id = 0, $bank_code = null)
    {
        if (!$bank_id) {
            return false;
        }
        if ($bank_code !== null) {
            $rs = DB::select()->from('bank')->where('id', '=', $bank_id)->and_where('code', '=', $bank_code)->execute()->current();
        } else {
            $rs = DB::select()->from('bank')->where('id', '=', $bank_id)->execute()->current();
        }

        if ($rs && isset($rs['status']) && (int)$rs['status'] == (int)Model_Bank::STATUS_VALID) {
            return true;
        } else {
            return false;
        }
    }


}