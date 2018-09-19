<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * Permission: wangxuesong
 * Date: 16/7/19
 * Time: 下午4:17
 *
 * 审核备注
 *
 */
class Model_User_AuditRemark extends Model_Database
{


    public function insert($array)
    {
        $_keys = array_keys($array);
        $_vals = array_values($array);

        $insert = DB::insert('user_audit_remark')
            ->columns($_keys)
            ->values($_vals);

        list($insert_id, $affected_rows) = $insert->execute();
        return $insert_id;

    }

    public function getListByUserId($user_id, $perpage = 20, $page = 1)
    {
        $query = DB::select(array('user_audit_remark.content', 'remarkcontent'), array('user_audit_remark.create_time', 'remarkcreate_time'),array('admin_user.name', 'remarkadminname'))->from('user_audit_remark')->join('admin_user')->on('admin_user.id', '=', 'user_audit_remark.admin_id')->where('user_audit_remark.user_id', '=', $user_id) ;
        if ($page < 1) {
            $page = 1;
        }

        $rs = $query->offset($perpage * ($page - 1))->limit($perpage)->execute()->as_array();


        return $rs;

    }

    public function getOne($id)
    {

        return DB::select(array('user_audit_remark.content', 'remarkcontent'), array('user_audit_remark.create_time', 'remarkcreate_time'),array('admin_user.name', 'remarkadminname'))->from('user_audit_remark')->join('admin_user')->on('admin_user.id', '=', 'user_audit_remark.admin_id')->where('user_audit_remark.id', '=', $id)->execute()->current();

    }


}