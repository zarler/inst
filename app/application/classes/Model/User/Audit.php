<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * Permission: wangxuesong
 * Date: 16/7/19
 * Time: 下午4:17
 *
 * 新增授信审核
 *
 */
class Model_User_Audit extends Model_Database
{


    //标记记录审核状态
    const STATUS_AUDITING = '1';      //审核中
    const STATUS_FINISH = '2';        //审核完成


    const PASS_VALIDITY_TIME = 86400 * 50;//50天，审核通过后50天未下单，需要重新授信
    const PASS_MNO_VALIDITY_TIME = 86400 * 30;//30天，运营商有效期是30天


    //构造查询条件
    private function queryBuilder($query, $array = [])
    {

        if (isset($array['credit_auth']) && $array['credit_auth'] != '') {
            $query->where('user.credit_auth', 'in', $array['credit_auth']);
        } else {
            $query->where('user.credit_auth', 'in', [Model_User::CREDIT_AUTH_BASE_SUBMITED, Model_User::CREDIT_AUTH_BASE_CHECKING]);
        }

        if (isset($array['name']) && $array['name'] != '') {
            $query->where('user.name', 'like', "%" . $array['name'] . "%");
        }

        if (isset($array['mobile']) && $array['mobile'] != "") {
            $query->where('user.mobile', '=', $array['mobile']);
        }

        if (isset($array['identity_code']) && $array['identity_code'] != '') {
            $query->where('user.identity_code', '=', $array['identity_code']);
        }

        if (isset($array['id']) && $array['id'] != '') {
            $query->where('user.id', '=', $array['id']);
        }

        //判断是列表分类  [show=all:调用所有列表,show=slef:调用自己审核的列表]
        if (isset($array['admin_id'])) {

            $query->where('user_audit.admin_id', '=', $array['admin_id']);

        }

        if (isset($array['status']) && $array['status'] != '') {
            $query->where('user_audit.status', '=', $array['status']);
        }

        if (isset($array['user_status']) && $array['user_status'] != '') {
            $query->where('user.status', '=', $array['user_status']);
        }


        return $query;
    }


    //查询分页
    public function getList($array = [], $perpage = 20, $page = 1)
    {


        $query = DB::select(
            'user.id', 'user.name', 'user.identity_code', 'user.mobile', 'user.credit_auth', 'user.mobile',
            'finance_profile.inst_amount', ['admin_user.name', 'admin_name'], ['user_audit.id', 'audit_id']
        )->from('user');

        $query->join('user_audit', 'LEFT')->on('user_audit.user_id', '=', 'user.id');
        $query->join('admin_user', 'LEFT')->on('user_audit.admin_id', '=', 'admin_user.id');

        $query->join('finance_profile', 'LEFT')->on('finance_profile.user_id', '=', 'user.id');


        if ($page < 1) {
            $page = 1;
        }

        if (count($array) > 0) {
            $query = $this->queryBuilder($query, $array);
        }

        $rs = $query->offset($perpage * ($page - 1))->limit($perpage)->execute()->as_array();


        return $rs;
    }


    //查询列表数量
    public function getTotal($array = [])
    {

        $query = DB::select([DB::expr('COUNT(*)'), 'total'])->from('user');
        $query->join('user_audit', 'LEFT')->on('user_audit.user_id', '=', 'user.id');
        $query->where('user.credit_auth', 'in', [Model_User::CREDIT_AUTH_STATUS_SUBMITED, Model_User::CREDIT_AUTH_STATUS_CHECKING]);
        $query->where('user.status', 'in', [Model_User::STATUS_NORMAL]);

        if (count($array) > 0) {
            $query = $this->queryBuilder($query, $array);
        }

        $rs = $query->execute()->current();

        return isset($rs['total']) ? $rs['total'] : 0;
    }


    //得到通过时间
    public function getPassTime($user_id)
    {

        $rs = DB::select('audit_time')->from('user_audit')
            ->where('user_id', '=', $user_id)
            ->where('credit_auth', '=', Model_User::CREDIT_AUTH_BASE_VERIFIED)
            ->where('status', '=', self::STATUS_FINISH)
            ->order_by('id', 'desc')
            ->limit(1)
            ->execute()->current();

        return isset($rs['audit_time']) ? $rs['audit_time'] : 0;
    }


}