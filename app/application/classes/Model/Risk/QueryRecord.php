<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: majin
 * Date: 17/7/5
 * Time: 下午10:49
 */
class Model_Risk_QueryRecord extends Model_Database
{

    const NO_JUMP = 0;
    const JUMP_HTML = 1;
    const JUMP_URL = 2;

    const PROVIDER_MOXIE = 'Moxie';
    const PROVIDER_TCREDIT = 'TCredit';


    const STATUS_READY = 1;
    const STATUS_APPLIED = 2;
    const STATUS_ERROR = 3;
    const STATUS_FAILED = 4;
    const STATUS_CLOSED = 5;
    const STATUS_SUCCESS = 6;

    const STATUS_FIRST_READY = 1;
    const STATUS_FIRST_SUBMITTED = 2;
    const STATUS_FIRST_ERROR = 3;
    const STATUS_FIRST_FAILED = 4;
    const STATUS_FIRST_CLOSED = 5;
    const STATUS_FIRST_SUCCESS = 6;

    const STATUS_SECOND_READY = 1;
    const STATUS_SECOND_SUBMITTED = 2;
    const STATUS_SECOND_ERROR = 3;
    const STATUS_SECOND_FAILED = 4;
    const STATUS_SECOND_CLOSED = 5;
    const STATUS_SECOND_SUCCESS = 6;

    public function make_no()
    {
        return strtoupper('tcno'.date('YmdHis').Text::random('alnum', 8));
    }


    public function get_one($id)
    {
        return DB::select()->from('mno_query_record')->where('id', '=', (int)$id)->execute()->current();
    }

    public function get_by_array($array, $rows = 0)
    {
        if (!isset($array['user_id']) || !isset($array['user_id']) || !isset($array['user_id'])) {
            return false;
        }
        $query = DB::select()->from('risk_query_record')->where('user_id', '=',
            $array['user_id'])->and_where('provider', '=',
            $array['provider'])->and_where('action', '=', $array['action'])->execute()->as_array();

        return $query;

    }

    public function get_one_by_array($array)
    {
        return $this->get_by_array($array, 1);
    }


    /** 创建
     * @param $array
     * @return bool
     */
    public function create($array)
    {
        if (!isset($array['user_id']) || $array['user_id'] < 1) {
            return false;
        }

        $user_id = $array['user_id'];
//        $mobile = $array['mobile'];
        $provider = isset($array['provider']) ? $array['provider'] : '';
        $status = isset($array['status']) ? (int)$array['status'] : Model_Risk_QueryRecord::STATUS_READY;
        $create_time = isset($array['create_time']) ? (int)$array['create_time'] : time();
        $action = isset($array['action']) ? $array['action'] : '';

        list($insert_id, $affected_rows) = DB::insert("risk_query_record", array(
            'user_id', 'provider', 'status', 'create_time', 'action',
        ))
            ->values(array(
                $user_id, $provider, $status, $create_time, $action,
            ))
            ->execute();

        return $insert_id;
    }


    /** 更改装填
     * @param $id
     * @param $array
     * @return bool
     */
    public function update($id, $array = [])
    {
        if ($rs = $this->get_one($id)) {
            return null !== DB::update('mno_query_record')->set($array)
                    ->where('id', '=', $id)
                    ->execute();
        }

        return false;
    }

    /**
     * 根据user_id 获取tc_no
     * */
    public function get_tc_no_by_user_id($user_id = '')
    {
        $data = DB::select()
            ->from('mno_query_record')
            ->where('status', 'in', [self::STATUS_APPLIED, self::STATUS_ERROR, self::STATUS_SUCCESS])
            ->and_where('user_id', '=', $user_id)
            ->order_by('status', 'desc')
            ->execute()
            ->current();

        return $data;
    }


}
