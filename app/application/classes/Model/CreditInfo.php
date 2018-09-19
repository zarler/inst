<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * Permission: chunyu
 * Date: 18/1/17
 * Time: 上午10:57
 */
class Model_CreditInfo extends Model_Database
{

    const RESULT_INIT = 0; //0初始
    const RESULT_PASS = 1; //1通过
    const RESULT_REJECT = 2; //2未通过，拒绝
    const RESULT_HUNG = 3; //3挂起(超过最大重试次数)
    const RESULT_UNDO_REJECT = 4; //4不处理(前面已经被拒绝)
    const RESULT_UNDO_HUNG = 5; //5不处理(前面的被挂起)
    const RESULT_UNDO_REJECT_PASS = 6; //6由于一些模块已经付钱，所以状态是4的接着跑 通过
    const RESULT_UNDO_REJECT_REJECT = 7; //6由于一些模块已经付钱，所以状态是4的接着跑 拒绝
    const RESULT_UNDO_REJECT_HUNG = 8; //6由于一些模块已经付钱，所以状态是4的接着跑 挂起

    const MAX_TIMES = 3;
    const RUN_PAID_MAX_TIMES = 10;

    const ADMISSION = 'Admission';
    const BAIRONG = 'BaiRong';
    const ZHICHENG = 'ZhiCheng';
    const QIANHAI = 'QianHai';
    const JINRONG91 = 'Jinrong91';
    const MNO = 'MNO';
    const EMAY = 'Emay';
    const TONGDUN = 'TongDun';

    const CARDBILL = Model_CreditInfo_Step::CREDITCARDBILL;
    const JINGDONG = Model_CreditInfo_Step::JINGDONG;
    const TAOBAO = Model_CreditInfo_Step::TAOBAO;
    const SOCIAL_SECURITY = Model_CreditInfo_Step::SOCIAL_SECURITY;
    const FUND = Model_CreditInfo_Step::FUND;

    const MODULE_CONF = [
        //芝麻信用  目前未接入

        //准入
        self::ADMISSION => 1,

        //百融 BaiRong
        self::BAIRONG => 1,

        //宜信致诚 ZhiCheng
        self::ZHICHENG => 1,

        //前海征信 QianHai +91征信 Jinrong91
        self::QIANHAI => 1,
        self::JINRONG91 => 1,

        //非必须 （邮箱信用卡账单 京东 淘宝 社保 公积金）Model_CreditInfo_Step::CREDITCARDBILL, Model_CreditInfo_Step::JINGDONG, Model_CreditInfo_Step::TAOBAO, Model_CreditInfo_Step::FUND, Model_CreditInfo_Step::SOCIAL_SECURITY
        self::CARDBILL => 0,
        self::JINGDONG => 0,
        self::TAOBAO => 0,
        self::SOCIAL_SECURITY => 0,
        self::FUND => 0,

        //运营商 MNO
        self::MNO => 1,

        //伊美软通 Emay
        self::EMAY => 1,

        //同盾 TongDun
        self::TONGDUN => 1,

        //人工额度 后台

    ];


    //已经付钱的model, 宜信致诚、运营商、京东、淘宝、信用卡、社保、公积金
    const MODULE_MONEY_PAID = [
        self::ZHICHENG,
        self::MNO,
        self::JINGDONG,
        self::TAOBAO,
        self::CARDBILL,
        self::SOCIAL_SECURITY,
        self::MNO,
    ];

    protected $table = 'credit_info';

    public function getModules($choose_models)
    {
        return array_keys(
            array_filter(self::MODULE_CONF, function ($v, $k) use ($choose_models)  {
                return $v == 1 || in_array($k, $choose_models);
            }, ARRAY_FILTER_USE_BOTH));
    }

    public function create($array = [])
    {

        if (!isset($array['user_id'])
        ) {
            return false;
        }

        $data = [
            'user_id' => (int)$array['user_id'],
            'module' => $array['module'],
            'result' => 0,
            'create_time' => time(),
        ];

        list($new_id, $row) = DB::insert($this->table, array_keys($data))
            ->values(array_values($data))
            ->execute();

        return $new_id;

    }

    /**
     * 多条件查询
     * @param $user_id
     * @param array $array
     * @param array $order_by
     * @param int $limit
     * @return mixed
     * demo $ci = $this->model['credit_info']->getByArray(['result' => 1], [['id', 'desc']], 1);
     */
    public function getByArray($array = [], $order_by = [], $limit = 0)
    {
        $query = $this->queryBuilder(DB::select_array()->from($this->table), $array);
        if ($order_by) {
            foreach ($order_by as $ob) {
                $query->order_by($ob[0], $ob[1]);
            }
        } else {
            $query->order_by($this->table . '.id', 'ASC');
        }
        if ($limit > 0) {
            $query->limit($limit);
        }
        if ($limit == 1) {
            return $query->execute()->current();
        }

        return $query->execute()->as_array();
    }


    //构造查询条件
    protected function queryBuilder($query, $array = [])
    {

        if (isset($array['user_id']) && $array['user_id']) {
            if (is_array($array['user_id'])) {
                $query->where($this->table . '.user_id', 'in', $array['user_id']);
            } else {
                $query->where($this->table . '.user_id', '=', $array['user_id']);
            }
        }


        if (isset($array['module']) && $array['module']) {
            if (is_array($array['module'])) {
                !empty($array['result']) && $query->where($this->table . '.module', 'in', $array['module']);
            } else {
                $query->where($this->table . '.module', '=', $array['module']);
            }
        }


        if (isset($array['retry'])) {
            if (is_array($array['retry'])) {
                !empty($array['retry']) && $query->where($this->table . '.retry', 'in', $array['retry']);
            } else {
                $query->where($this->table . '.retry', '=', $array['retry']);
            }
        }

        if (isset($array['result'])) {
            if (is_array($array['result'])) {
                !empty($array['result']) && $query->where($this->table . '.result', 'in', $array['result']);
            } else {
                $query->where($this->table . '.result', '=', $array['result']);
            }
        }

        //start_time (>= __start | <=__end)
        if (isset($array['create_time__start']) && $array['create_time__start'] > 0) {
            $query->and_where($this->table . '.create_time', '>=', $array['create_time__start']);
        }
        if (isset($array['create_time__end']) && $array['create_time__end'] > 0) {
            $query->and_where($this->table . '.create_time', '<=', $array['create_time__end']);
        }


        return $query;
    }


    public function update($where = null, $array = null)
    {
        if (!$where || !$array) {
            return false;
        }

        $query = $this->queryBuilder(DB::update($this->table)->set($array), $where);
        return null !== $query->execute();

    }

    public function getCount($where = null)
    {
        if (!$where) {
            return 0;
        }
        $rs = $this->queryBuilder(DB::select([DB::expr('count(*)'), 'count'])->from($this->table), $where)->execute()->current();

        return isset($rs['count']) ? $rs['count'] : 0;

    }

}