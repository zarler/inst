<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 15/12/15
 * Time: 上午11:43
 *
 * 权限MODEL
 */
class Model_Config extends Model_Database
{

    const TASK = 1;
    const FRONT = 2;
    const SITE = 3;
    const FRAUD = 4;
    const AUDIT = 5;         //活体识别
    const CREDIT = 6;  //后去授信额度
    const AGENT = 7;  //代理商

    //全部设置
    public function getConfigNav()
    {
        return DB::select('typeid,typename')->from('config')->where('is_show', '=',
            1)->group_by('typeid,typename')->order_by('typeid', 'asc')->execute()->as_array();
    }

    //单类设置
    public function getConfigByTypeID($typeid)
    {
        return DB::select()->from('config')->where('typeid', '=', intval($typeid))->and_where('is_show', '=',
            1)->order_by('id', 'asc')->execute()->as_array();
    }

    //获取所有隐藏设置
    public function getConfigHide()
    {
        return DB::select()->from('config')->where('is_show', '=', 0)->order_by('typeid,id',
            'asc')->execute()->as_array();
    }

    //添加设置
    public function create($array = array())
    {
        if (isset($array)) {
            list($insert_id) = DB::insert("config",
                array('name', 'typeid', 'typename', 'value', 'val_type', 'description'))
                ->values(array(
                    $array['name'],
                    $array['typeid'],
                    $array['typename'],
                    $array['value'],
                    $array['val_type'],
                    $array['description']
                ))->execute();
            if ($insert_id) {
                return $insert_id;
            }
        }
        return false;
    }

    //更改设置
    public function updateConfig($id, $array = array())
    {
        if (!$array) {
            return false;
        }
        $affected_rows = DB::update('config')->set($array)->where('id', '=', intval($id))->execute();
        return $affected_rows !== null;
    }


    public function getConfig($name, $type = Model_Config::CREDIT)
    {
        $rs = DB::select('value')->from('config')->where('typeid', '=', $type)->where('name', '=',
            $name)->execute()->current();
        return isset($rs['value']) ? $rs['value'] : null;
    }

    public function getOne($id = 0)
    {
        if ($id < 1) {
            return false;
        }
        return DB::select()->from('config')->where('id', '=', $id)->limit(1)->execute()->current();
    }

}
