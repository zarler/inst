<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: chunyu
 * Date: 18/01/13
 * Time: 下午2:15
 *
 * 借款用途和订单关联表
 */
class Model_OrderLoanUseFor extends Model_Database
{

    private $table = 'order_loan_user_for';

    public function create($array){

        $data = [
            'order_id' => intval($array['order_id']),
            'luf_id' => intval($array['luf_id']),
            'create_time' => time(),
        ];
        list($new_id, $row) = DB::insert($this->table, array_keys($data))
            ->values(array_values($data))
            ->execute();

        return $new_id;
    }


    public function getOne($id){
        $data = DB::select('id, name')->from($this->table)
            ->where('id','=', $id)
            ->execute()
            ->current();
        return $data;
    }

}