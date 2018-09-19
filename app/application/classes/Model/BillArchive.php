<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * Permission: chunyu
 * Date: 18/1/17
 * Time: 上午10:57
 */
class Model_BillArchive extends Model_Database {

    protected $table = 'bill_archive';

    public function create($array = [])
    {

        if (!isset($array['user_id']) || !isset($array['order_id']) ||
            !isset($array['bill_id']) ||
            !isset($array['repay_principal']) ||
            !isset($array['repay_interest'])
        ) {
            return false;
        }

        $data = [
            'bill_id' => (int)$array['bill_id'],
            'order_id' => (int)$array['order_id'],
            'user_id' => (int)$array['user_id'],
            'repay_principal' => $array['repay_principal'],//当期本金存档
            'repay_interest' => round($array['repay_interest'], 2),//当期利息存档
            'repay_management' => round($array['repay_management'], 2),//当期管理费存档
            'repay_damage' => round($array['repay_damage'], 2),//提前还款违约金存档
            'day_penalty_rate' => $array['day_penalty_rate'],//罚息日利率存档
            'day_late_fee_rate' => $array['day_late_fee_rate'],//滞纳金日利率存档
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
    protected function queryBuilder($query, $array = array())
    {

        if (isset($array['user_id']) && $array['user_id']) {
            if (is_array($array['user_id'])) {
                $query->where($this->table . '.user_id', 'in', $array['user_id']);
            } else {
                $query->where($this->table . '.user_id', '=', $array['user_id']);
            }
        }


        if (isset($array['order_id']) && $array['order_id']) {
            if (is_array($array['order_id'])) {
                $query->where($this->table . '.order_id', 'in', $array['order_id']);
            } else {
                $query->where($this->table . '.order_id', '=', $array['order_id']);
            }
        }


        if (isset($array['bill_id']) && $array['bill_id']) {
            if (is_array($array['bill_id'])) {
                $query->where($this->table . '.bill_id', 'in', $array['bill_id']);
            } else {
                $query->where($this->table . '.bill_id', '=', $array['bill_id']);
            }
        }


        return $query;
    }

}
