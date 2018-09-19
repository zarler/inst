<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * Permission: majin
 * Date: 16/1/4
 * Time: 下午21:19
 */

class Model_BankCard extends Model_Database {

    const STATUS_VALID = 1;						//有效
    const STATUS_INVALID = 2;					//无效
    const STATUS_DELETED = 4;                   //删除
    const STATUS_BACKSTAGE_DEDUCT = 3;          //后台代扣
    const STATUS_SUBSTITUTE_DEDUCT = 9;         //他人代扣


    public $status_array = array(
        self::STATUS_VALID =>'有效',
        self::STATUS_INVALID =>'无效',
        self::STATUS_DELETED =>'已取消(删除)',
    );

    //状态名
    public function getStatusName($status_key=0){
        return isset($this->status_array[$status_key]) ? $this->status_array[$status_key] : NULL;
    }

    public function getOne($id=0) {
        return DB::select()->from('bankcard')->where('id','=',$id)->execute()->current();
    }

    public function getOneByUserId($user_id=0,$status=NULL) {
        return DB::select()->from('bankcard')->where('user_id','=',$user_id)->and_where('status','=', ( $status!==NULL ? $status : self::STATUS_VALID ) )->execute()->current();
    }


    //更改
    public function update($id=0, $array=NULL) {
        if(!$array){
            return FALSE;
        }
        unset($array['id']);
        //清除TCCache_User
        if(isset($array['user_id']) && $array['user_id']>0 ){
            Lib::factory('TCCache_User')->user_id((int)$array['user_id'])->remove();
        }
        $affected_rows = DB::update('bankcard')->set($array)->where('id','=',intval($id))->execute();
        return $affected_rows!==NULL;
    }


    //取消(删除)
    public function delete($id=0) {
        return $this->update($id,array('status'=>self::STATUS_DELETED));
    }


    //构造查询条件
    private function queryBuilder($query, $array=array()) {

        if(isset($array['identity_code']) && $array['identity_code'] ) {
            $query->where('user.identity_code','=',trim($array['identity_code']));
        }

        if(isset($array['holder']) && $array['holder']) {
            $query->where('bankcard.holder','=',$array['holder']);
        }

        if(isset($array['mobile']) && $array['mobile'] ){
            $query->where('bankcard.mobile','=',$array['mobile']);
        }

        if(isset($array['status']) && $array['status'] ){
            $query->where('bankcard.status','=',$array['status']);
        }

        if(isset($array['bank_id']) && $array['bank_id'] ){
            $query->where('bankcard.bank_id','=',$array['bank_id']);
        }
        return $query;
    }


    //查询分页
    public function getList($array=array(),$perpage=20,$page=1) {
        $query=DB::select('bankcard.*', 'user.identity_code', array('bank.name', 'bank_name'))->from('bankcard');
        $query->join('user')->on('user.id','=','bankcard.user_id');
        $query->join('bank')->on('bank.id','=','bankcard.bank_id');
        if(count($array)>0) {
            $query = $this->queryBuilder($query,$array);
        }
        //echo $query->__tostring();
        //exit();
        if($page<1) {
            $page=1;
        }
        $rs=$query->order_by('bankcard.id','DESC')->offset($perpage*($page-1))->limit($perpage)->execute()->as_array();
        return $rs;
    }


    //查询统计
    public function getTotal($array=array()) {
        $query=DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('bankcard');
        $query->join('user')->on('user.id','=','bankcard.user_id');
        $query->join('bank')->on('bank.id','=','bankcard.bank_id');
        if(count($array)>0) {
            $query = $this->queryBuilder($query,$array);
        }
        $rs=$query->execute()->current();
        return isset($rs['total']) ? $rs['total'] : 0 ;
    }

    //根据卡号获取开户行，无效的银行卡也显示
    public function getBanknameByCardNo($card_no){
        $query=DB::select('bank.short_name')->from('bank');
        $query->join('bankcard')->on('bankcard.bank_id','=','bank.id');
        $query->and_where('bankcard.card_no', '=', $card_no);
        //$query->and_where('bankcard.status', '=', 1);
        $rs=$query->execute()->current();
        return $rs['short_name'];
    }

    // 根据用户id获取帮过的卡号
    public function getBankcardInfoByUserId($user_id=0, $status=[1, 3]){
        return DB::select(
            ['bank.name' ,'bank_name'],
            ['bankcard.id' ,'id'],
            ['bankcard.user_id' , 'user_id'],
            ['bankcard.card_no' , 'card_no'],
            ['bankcard.mobile' , 'mobile'],
            ['bankcard.holder' , 'holder'],
            ['bankcard.issue' , 'issue'],
            ['bankcard.status' , 'status']
            )
            ->from('bankcard')
            ->join('bank', 'left')
            ->on('bankcard.bank_id', '=', 'bank.id')
            ->where('bankcard.user_id', '=', $user_id)
            ->and_where('bankcard.status', 'in', $status)
            ->execute()
            ->as_array();
    }

    // 添加借记卡
    public function add($data){
        list($insert_id, $affected_rows) = DB::insert('bankcard', array_keys($data))->values(array_values($data))->execute();
        //清除TCCache_User
        if($insert_id>0 && isset($data['user_id']) && $data['user_id']>0 ){
            Lib::factory('TCCache_User')->user_id((int)$data['user_id'])->remove();
        }
        return $insert_id;
    }


    // 根据卡号查找匹配的银行卡信息
    public function getOneByBankNo($card_no){
        return DB::select(
            ['bank.name' ,'bank_name'],
            ['bankcard.id' ,'id'],
            ['bankcard.user_id' , 'user_id'],
            ['bankcard.card_no' , 'card_no'],
            ['bankcard.mobile' , 'mobile'],
            ['bankcard.holder' , 'holder'],
            ['bankcard.issue' , 'issue'],
            ['bankcard.status' , 'status']
        )
            ->from('bankcard')
            ->join('bank', 'left')
            ->on('bankcard.bank_id', '=', 'bank.id')
            ->and_where('bankcard.card_no', '=', $card_no)
            ->limit(1)
            ->execute()
            ->current();
    }
}