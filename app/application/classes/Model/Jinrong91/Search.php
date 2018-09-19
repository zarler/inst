<?php
/**
 * Created by IntelliJ IDEA.
 * User: yangyuexin
 * Date: 2018/1/16
 * Time: 下午5:23
 */
class Model_Jinrong91_Search extends Model_Database{

    //构造查询条件
    private function queryBuilder($query, $array=array()) {


        if(isset($array['name']) && $array['name']!='') {
            $query->where('user.name','=',$array['name']);
        }

        if(isset($array['identity_code']) && $array['identity_code']!='') {
            $query->where('user.identity_code','=',$array['identity_code']);
        }

        return $query;
    }


    //查询
    public function getOne($array=array()) {

        $query=DB::select(
            'user.id','user.name','user.identity_code','user.status','user.credit_auth',
            'order.loan_amount','order.repay_amount',array('order.status','order_status')
        )->from('user');

        $query->join('order','LEFT')->on('order.user_id','=','user.id');

        if(count($array)>0) {
            $query = $this->queryBuilder($query,$array);
        }

        $rs=$query->execute()->as_array();


        return $rs;
    }



}