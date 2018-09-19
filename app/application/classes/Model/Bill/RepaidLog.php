<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 18/1/12
 * Time: 上午11:59
 *
 * 账单还款日志Model
 */
class Model_Bill_RepaidLog extends Model_Database{

    const OVERDUE_IN = 1 ;          //已逾期
    const NOT_OVERDUE = 2 ;         //未逾期

    /**
     * 记录日志
     * @param int $bill_id
     * @param array $array
     * @return bool
     */
    public function log($bill_id = 0,$array = []){
        try {
            return null !== DB::insert('bill_repaid_log',[
                'bill_id','user_id','order_id','dateline','overdue','repaid_time','deduct_id',
                'repaid_amount','repaid_principal','repaid_interest','repaid_management','repaid_penalty','repaid_late_fee','repaid_damage','create_time',
            ])
                ->values([
                    $bill_id ? intval($bill_id) : 0,
                    isset($array['user_id']) ? $array['user_id'] : '0',
                    isset($array['order_id']) ? $array['order_id'] : '0',
                    isset($array['dateline']) ? $array['dateline'] : date('Y-m-d'),
                    isset($array['overdue']) ? $array['overdue'] : '0',
                    isset($array['repaid_time']) ? $array['repaid_time'] : time(),
                    isset($array['deduct_id']) ? $array['deduct_id'] : '0',

                    isset($array['repaid_amount']) ? $array['repaid_amount'] : '0',
                    isset($array['repaid_principal']) ? $array['repaid_principal'] : '0',
                    isset($array['repaid_interest']) ? $array['repaid_interest'] : '0',
                    isset($array['repaid_management']) ? $array['repaid_management'] : '0',
                    isset($array['repaid_penalty']) ? $array['repaid_penalty'] : '0',
                    isset($array['repaid_late_fee']) ? $array['repaid_late_fee'] : '0',
                    isset($array['repaid_damage']) ? $array['repaid_damage'] : '0',
                    time(),
                ])
                ->execute();

        } catch (Exception $e) {
            echo $e->getMessage();
            return false;

        }
    }


    /**
     * 统计
     * @param $bill_id
     * @param array $array
     * @return array
     */
    public function repaidSumByArray($bill_id,$array=[])
    {
        $array['bill_id'] = $bill_id;
        $rs = $this->queryBuilder(DB::select_array([
            [DB::expr('sum(repaid_amount)'), 'repaid_amount'],
            [DB::expr('sum(repaid_late_fee)'), 'repaid_late_fee'],
            [DB::expr('sum(repaid_penalty)'), 'repaid_penalty'],
            [DB::expr('sum(repaid_management)'), 'repaid_management'],
            [DB::expr('sum(repaid_interest)'), 'repaid_interest'],
            [DB::expr('sum(repaid_principal)'), 'repaid_principal'],
            [DB::expr('sum(repaid_damage)'), 'repaid_damage'],
        ])->from('bill_repaid_log'), $array)
            ->execute()
            ->current();
        return $rs;
    }


    /**
     * 构造查询条件
     *  说明:注意dateline__start的查询 (>= dateline__start | <=dateline__end)
     * @param $query
     * @param array $array
     * @return mixed
     */
    protected function queryBuilder($query, $array = [])
    {

        if (isset($array['user_id']) && $array['user_id']) {
            $query->where('bill_repaid_log.user_id', '=', trim($array['user_id']));
        }

        if (isset($array['order_id']) && $array['order_id']) {
            if (is_array($array['order_id'])) {
                $query->where('bill_repaid_log.order_id', 'in', $array['order_id']);
            } else {
                $query->where('bill_repaid_log.order_id', '=', $array['order_id']);
            }
        }

        if (isset($array['bill_id']) && $array['bill_id']) {
            if (is_array($array['bill_id'])) {
                $query->where('bill_repaid_log.bill_id', 'in', $array['bill_id']);
            } else {
                $query->where('bill_repaid_log.bill_id', '=', $array['bill_id']);
            }
        }

        if (isset($array['overdue']) && $array['overdue']) {
            $query->where('bill_repaid_log.overdue', '=', $array['overdue']);
        }

        //dateline (>= __start | <=__end)
        if (isset($array['dateline__start']) && $array['dateline__start'] > 0) {
            $query->and_where('bill_repaid_log.dateline', '>=', $array['dateline__start']);
        }
        if (isset($array['dateline__end'])  && $array['dateline__end']>0) {
            $query->and_where('bill_repaid_log.dateline', '<=', $array['dateline__end']);
        }


        //repaid_time (>= __start | <=__end)
        if (isset($array['repaid_time__start']) && $array['repaid_time__start'] > 0) {
            $query->and_where('bill_repaid_log.repaid_time', '>=', $array['repaid_time__start']);
        }
        if (isset($array['repaid_time__end'])  && $array['repaid_time__end']>0) {
            $query->and_where('bill_repaid_log.repaid_time', '<=', $array['repaid_time__end']);
        }

        return $query;
    }


    /**
     * 按日期获取总和
     * @param int $bill_id
     * @param int $overdue
     * @param null $date1
     * @param null $date2
     * @return bool
     */
    public function getAmount($bill_id, $overdue=0, $date1=NULL, $date2=NULL)
    {
        if($bill_id<1) {
            return false;
        }
        $array['bill_id'] = $bill_id;
        if($overdue>0) {
            $array['bill_id'] = $overdue;
        }
        if($date1) {//>=
            $array['dateline__start'] = $date1;
        }
        if($date2) {//<=
            $array['dateline__end'] = $date2;
        }

        $query = $this->queryBuilder(DB::select(array(DB::expr('SUM(repaid_amount)'), 'sum_amount'))->from('bill_repaid_log'),$array);
        //echo $query->compile();
        $rs = $query->execute()->current();
        return isset($rs['sum_amount']) ?  $rs['sum_amount'] : 0 ;
    }


    /**
     * 获取有还款的日期列表
     * @param $bill_id
     * @param int $overdue
     * @param null $date1
     * @param null $date2
     * @return bool
     */
    public function getDistDate($bill_id, $overdue=0, $date1=NULL, $date2=NULL)
    {
        if($bill_id<1) {
            return false;
        }
        $array['bill_id'] = $bill_id;
        if($overdue>0) {
            $array['bill_id'] = $overdue;
        }
        if($date1) {//>=
            $array['dateline__start'] = $date1;
        }
        if($date2) {//<=
            $array['dateline__end'] = $date2;
        }
        $query = $this->queryBuilder(DB::select('dateline')->from('bill_repaid_log'),$array);
        return $query->group_by('dateline')
            ->order_by('dateline','ASC')
            ->execute()
            ->as_array();
    }








}