<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: chunyu
 * Date: 18/01/13
 * Time: 下午2:15
 *
 * 借款用途
 */
class Model_LoanUseFor extends Model_Database
{
    const STATUS_AVAILABLE = 1;
    const STATUS_UNAVAILABLE = 2;


    private $table = 'loan_use_for';


    public function getList(){
        $data = DB::select('id, name')->from($this->table)
            ->where('status','=', self::STATUS_AVAILABLE)
            ->execute()
            ->as_array();
        return $data;
    }

    public function getOne($id){
        $data = DB::select('id, name')->from($this->table)
            ->where('id','=', $id)
            ->execute()
            ->current();
        return $data;
    }

}