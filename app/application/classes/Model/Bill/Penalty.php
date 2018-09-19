<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 18/1/13
 * Time: 下午11:11
 *
 *  逾期罚息与滞纳金记录表Model
 *  还款顺序:
 *      滞纳金
 *      罚息
 *      管理费
 *      利息
 *      本金
 *
 */
class Model_Bill_Penalty extends Model_Database {

    //订单费率模型
    protected static $order_type_rate = [
        Model_Order::TYPE_INST => 'Order_Inst_Rate',
    ];

    protected $product_rate = null;

    /** 创建一条罚息记录
     * @param $bill_id
     * @param array $array
     * @return bool
     */
    public function create($bill_id,array $array = array())
    {

        if($bill_id<1 || !is_array($array)){
            return false;
        }
        if($bill = Model::factory('Bill')->getOne($bill_id)){
            $order_id = (int)$bill['order_id'];
            $user_id = (int)$bill['user_id'];
            $dateline =  isset($array['dateline']) ? $array['dateline'] : date('Y-m-d');                                //罚息计算日
            $day = isset($array['day']) && $array['day']>0 ? (int)$array['day'] : 1 ;                                   //逾期第几天(天数)



            $day_repaid_amount = isset($array['day_repaid_amount']) ? (float)$array['day_repaid_amount'] : 0.00 ;                   //当日还款
            $day_repaid_late_fee = isset($array['day_repaid_late_fee']) ? (float)$array['day_repaid_late_fee'] : 0.00 ;             //归还滞纳金(当日)
            $day_repaid_penalty = isset($array['day_repaid_penalty']) ? (float)$array['day_repaid_penalty'] : 0.00 ;                //归还滞纳金(当日)
            $day_repaid_management = isset($array['day_repaid_management']) ? (float)$array['day_repaid_management'] : 0.00 ;       //归还滞纳金(当日)
            $day_repaid_interest = isset($array['day_repaid_interest']) ? (float)$array['day_repaid_interest'] : 0.00 ;             //归还利息(当日)
            $day_repaid_principal = isset($array['day_repaid_principal']) ? (float)$array['day_repaid_principal'] : 0.00 ;


            $repay_amount = isset($array['repay_amount']) ? (float)$array['repay_amount'] : 0.00 ;                      //累计应还
            $repay_late_fee = isset($array['repay_late_fee']) ? (float)$array['repay_late_fee'] : 0.00 ;                //应还滞纳金
            $repay_penalty = isset($array['repay_penalty']) ? (float)$array['repay_penalty'] : 0.00 ;                   //应还罚息
            $repay_management = isset($array['repay_management']) ? (float)$array['repay_management'] : 0.00 ;          //应还管理费
            $repay_interest = isset($array['repay_interest']) ? (float)$array['repay_interest'] : 0.00 ;                //应还利息
            $repay_principal = isset($array['repay_principal']) ? (float)$array['repay_principal']  : 0.00 ;            //应还本金

            $repaid_amount = isset($array['repaid_amount']) ? (float)$array['repaid_amount'] : 0.00 ;                   //累计还款
            $repaid_late_fee = isset($array['repaid_late_fee']) ? (float)$array['repaid_late_fee'] : 0.00 ;             //归还滞纳金
            $repaid_penalty = isset($array['repaid_penalty']) ? (float)$array['repaid_penalty'] : 0.00 ;                //归还滞纳金
            $repaid_management = isset($array['repaid_management']) ? (float)$array['repaid_management'] : 0.00 ;       //归还滞纳金
            $repaid_interest = isset($array['repaid_interest']) ? (float)$array['repaid_interest'] : 0.00 ;             //归还利息
            $repaid_principal = isset($array['repaid_principal']) ? (float)$array['repaid_principal'] : 0.00 ;          //归还本金

            $day_penalty_rate = isset($array['day_penalty_rate']) ? (float)$array['day_penalty_rate'] : 0.000 ;                     //罚息利率
            $day_late_fee_rate = isset($array['day_late_fee_rate']) ? (float)$array['day_late_fee_rate'] : 0.000 ;                  //滞纳金利率
            $day_penalty = isset($array['day_penalty']) ? (float)$array['day_penalty'] : 0.00 ;                         //当日罚息
            $day_late_fee = isset($array['day_late_fee']) ? (float)$array['day_late_fee'] : 0.00 ;                      //当日滞纳金

//            $bill_repay_amount = isset($array['bill_repay_amount']) ? (float)$array['bill_repay_amount'] : 0.000 ;      //账单应还:账单当日表现与主表同步
//            $bill_repaid_amount = isset($array['bill_repaid_amount']) ? (float)$array['bill_repaid_amount'] : 0.000 ;   //账单已还:账单当日表现与主表同步

            $create_time = time();

            list($insert_id,$rows) = DB::insert('bill_overdue_penalty',[
                'bill_id','order_id', 'user_id', 'dateline', 'day',
//                'init_amount',  'init_late_fee', 'init_penalty', 'init_management', 'init_interest','init_principal',
                'day_repaid_amount',  'day_repaid_late_fee', 'day_repaid_penalty', 'day_repaid_management', 'day_repaid_interest','day_repaid_principal',
                'repay_amount',  'repay_late_fee', 'repay_penalty', 'repay_management', 'repay_interest','repay_principal',
                'repaid_amount',  'repaid_late_fee', 'repaid_penalty', 'repaid_management', 'repaid_interest','repaid_principal',
//                'bill_repay_amount', 'bill_repaid_amount',
                'day_penalty_rate', 'day_late_fee_rate', 'day_penalty', 'day_late_fee',
                'create_time'
            ])
                ->values([
                $bill_id, $order_id, $user_id, $dateline, $day,
//                    $init_amount,  $init_late_fee, $init_penalty, $init_management, $init_interest,$init_principal,
                    $day_repaid_amount,  $day_repaid_late_fee, $day_repaid_penalty, $day_repaid_management, $day_repaid_interest, $day_repaid_principal,
                    $repay_amount,  $repay_late_fee, $repay_penalty, $repay_management, $repay_interest,$repay_principal,
                    $repaid_amount,  $repaid_late_fee, $repaid_penalty, $repaid_management, $repaid_interest, $repaid_principal,
//                    $bill_repay_amount, $bill_repaid_amount,
                    $day_penalty_rate, $day_late_fee_rate, $day_penalty, $day_late_fee,
                    $create_time,
                ])
                ->execute();

            if($insert_id>0){
                return $insert_id;
            }

        }

        return false;
    }


    /** 更新
     * @param $id
     * @param array $array
     * @return bool
     */
    public function update($id,$array = array())
    {
        if($id<1 || !is_array($array)){
            return false;
        }
        return null!== DB::update('bill_overdue_penalty')->set($array)->where('id','=',$id)->execute();
    }


    /** 获取账单罚息列表
     * @param int       $bill_id
     * @param mixed     $date
     * @return mixed
     */
    public function getByBillId($bill_id,$date=null)
    {
        if($bill_id<1){
            return false;
        }
        $query = DB::select()->from('bill_overdue_penalty')->where('bill_id','=',$bill_id);
        if($date!==null){
            $query->and_where('dateline','=',$date);
        }
        return $query->order_by('dateline','ASC')->execute()->as_array();
    }

    /** 获取单条罚息记录
     * @param int       $bill_id
     * @param string    $date eg:2016-06-16
     * @return mixed
     */
    public function getOneByBillId($bill_id,$date=null)
    {
        if($bill_id<1){
            return false;
        }
        $query = DB::select()->from('bill_overdue_penalty')->where('bill_id','=',$bill_id);
        if($date!==null){
            $query->and_where('dateline','=',$date);
        }
        return $query->order_by('dateline','DESC')->limit(1)->execute()->current();
    }


    /**
     * 返回order_type对应产品费率实例
     * @param $order_type
     * @return bool|object
     */
    protected function rate($order_type)
    {
        if(isset(self::$order_type_rate[$order_type])) {
            return Model::factory(self::$order_type_rate[$order_type]);
        }
        return false;
    }

    
    

    /**
     * 计算并保存当日罚息与滞纳金 (用于生成当日罚息记录)
     * @param int   $bill_id
     * @param mixed $date
     * @param array $operation
        * (bool) must  强制生成(超过最大罚息周期后的还款,必须使用此项)
        * (bool) free 减免当日息费
        *
     * @return bool
     *  说明
     *  lp = last penalty
     *
     */
    public function dayCreate($bill_id, $date=null, $operation=['must'=>true,'free'=>false])
    {
        if ($bill_id<1){
            return false;
        }
        if($date===null){
            $date = date('Y-m-d');
        }
        $bill = null;
        $order = null;
        $rate = null;
        if($bill = Model::factory('Bill')->getOne($bill_id)) {
            if($order = Model::factory('Order')->getOne($bill['order_id'])) {
                //条件通过
                $rate = $this->rate($order['type']);
            }else{
                return false;
            }
        }else{
            return false;
        }

        if($rs = DB::select()->from('bill_overdue_penalty')->where('bill_id','=', $bill_id)->and_where('dateline','=',$date)->execute()->current()) {
            return false;//已经存在当日罚息记录
        }


        if($rate) {
            $user_id = $bill['user_id'];
            $order_id = $bill['order_id'];
            $expire_day = Lib::factory('Date')->countDayDay($bill['expire_time'],strtotime($date));//计算距离逾期第一天有多少个有效天数
            //初始化
            $day_late_fee_rate = 0.00;//日滞纳金率
            $day_penalty_rate = 0.00;//日罚息率

            $day_repay_late_fee = 0.00;//当日滞纳金
            $day_repay_penalty = 0.00;//当日罚息
            $day_repay_management = 0.00;//当日应还管理费
            $day_repay_interest = 0.00;//当日日累计利息
            $day_repay_principal = 0.00;//当日日应还本金
            $day_repay_amount = 0.00;//当日应还金额
            $day_repaid_late_fee = 0.00;//当日已还滞纳金
            $day_repaid_penalty = 0.00;//当日已还罚息
            $day_repaid_management = 0.00;//当日已还管理费
            $day_repaid_interest = 0.00;//当日已还利息
            $day_repaid_principal = 0.00;//当日已还本金
            $day_repaid_amount = 0.00;//应还金额与bill
            $repay_late_fee = 0.00;//累计应还滞纳金
            $repay_penalty = 0.00;//累计应还欠款
            $repay_management = 0.00;//累计应还管理费
            $repay_interest = 0.00;//累计应还利息
            $repay_principal = 0.00;//累计应还本金
            $repay_amount = 0.00;//累计应还金额
            $repaid_late_fee = 0.00;//累计已还滞纳金
            $repaid_penalty = 0.00;//累计已还欠款
            $repaid_management = 0.00;//累计已还管理费
            $repaid_interest = 0.00;//累计已还利息
            $repaid_principal = 0.00;//累计已还本金
            $repaid_amount = 0.00;//累计已还金额

            $lp = DB::select()->from('bill_overdue_penalty')->where('bill_id','=', $bill_id)->order_by('dateline','DESC')->limit(1)->execute()->current();
            if($lp){
                //有前一次罚息记录,属于已经有罚息的延续型账单
                $repay_late_fee = $lp['repay_late_fee'];//累计应还滞纳金
                $repay_penalty = $lp['repay_penalty'];//累计应还罚息
                $repay_management = $lp['repay_management'];//累计应还管理费
                $repay_interest = $lp['repay_interest'];//累计应还利息
                $repay_principal = $lp['repay_principal'];//累计应还本金

                $repaid_late_fee = $lp['repaid_late_fee'];//累计应还滞纳金
                $repaid_penalty = $lp['repaid_penalty'];//累计应还罚息
                $repaid_management = $lp['repaid_management'];//累计应还管理费
                $repaid_interest = $lp['repaid_interest'];//累计应还利息
                $repaid_principal = $lp['repaid_principal'];//累计应还本金

                $day_repay_late_fee = bcsub($lp['repay_late_fee'],$lp['repaid_late_fee'],2);//当日应还滞纳金
                $day_repay_penalty = bcsub($lp['repay_late_fee'],$lp['repaid_penalty'],2);//当日应还罚息
                $day_repay_management = bcsub($lp['repay_management'],$lp['repaid_management'],2);//当日应还管理费
                $day_repay_interest = bcsub($lp['repay_interest'],$lp['repaid_interest'],2);//当日应还利息
                $day_repay_principal = bcsub($lp['repay_principal'],$lp['repaid_principal'],2);//当日应还本金

                $repay_amount = $lp['repay_amount'];
                $repaid_amount = $lp['repaid_amount'];

            }else{
                //还没有过罚息的新账单
                $bo = Model::factory('Bill_RepaidLog')->repaidSumByArray($bill_id,['overdue'=>'2']);//逾期前还款
                if($bo) {
                    //有逾期前还款
                    $day_repay_management = bcsub($bill['repay_management'],(float)$bo['repaid_management'],2);//未还管理费
                    $day_repay_interest = bcsub($bill['repay_interest'],(float)$bo['repaid_interest'],2);//未还利息
                    $day_repay_principal = bcsub($bill['repay_principal'],(float)$bo['repaid_principal'],2);//未还本金
                } else {
                    //没有逾期前还款
                    $day_repay_management = $bill['repay_management'];//未还管理费
                    $day_repay_interest = $bill['repay_interest'];//未还利息
                    $day_repay_principal = $bill['repay_principal'];//未还本金
                }
                $day_repay_late_fee = 0.00;
                $day_repay_penalty = 0.00;
                $day_repaid_late_fee = 0.00;
                $day_repaid_penalty = 0.00;

                $repay_late_fee = 0.00;
                $repay_penalty = 0.00;
                $repay_management = $bill['repay_management'];
                $repay_interest = $bill['repay_interest'];
                $repay_principal = $bill['repay_principal'];

                $repaid_late_fee = 0.00;
                $repaid_penalty = 0.00;
                $repaid_management = (float)$bo['repaid_management'];
                $repaid_interest = (float)$bo['repaid_interest'];
                $repaid_principal = (float)$bo['repaid_principal'];

                $repay_amount = bcadd(bcadd($repay_principal,$repay_interest,2),$repay_management,2);
                $repaid_amount = (float)$bo['repaid_amount'];

            }

            //[根据产品类型选额费率]-----------------------------------------------------------
            $product_rate = $this->rate($order['type']);
            if(!$product_rate) {
                return false;
            }

            $penalty_max_day = $product_rate->getPenaltyMaxDay();//最大逾期罚息天数
            if($expire_day>0 && $expire_day<=$penalty_max_day) {
                $day_late_fee = $product_rate->dayLateFee($day_repay_principal,1);//滞纳金
                $day_penalty = $product_rate->dayPenalty($day_repay_principal,1);//罚息
                $day_late_fee_rate = $product_rate->getDayLateFeeRate();//滞纳金率
                $day_penalty_rate = $product_rate->getDayPenaltyRate();//罚息

            } elseif($expire_day>$penalty_max_day && (isset($operation['mast']) && $operation['mast'])) {
                //超过罚息最大期限,轻质生成罚息记录是当日按0费率计息
                $day_late_fee = 0.00;//当日滞纳金
                $day_penalty = 0.00;//当日罚息
                $day_late_fee_rate = 0.0000;//滞纳金率
                $day_penalty_rate = 0.0000;//罚息率

            } else {
                return false;

            }

            if(isset($operation['free']) && $operation['free']){
                //当日免息
                $day_late_fee = 0.00;//当日滞纳金
                $day_penalty = 0.00;//当日罚息
                $day_late_fee_rate = 0.0000;//滞纳金率
                $day_penalty_rate = 0.0000;//罚息率
            }


            $day_repay_penalty = bcadd($day_repay_penalty,$day_penalty,2);
            $repay_penalty = bcadd($repay_penalty,$day_penalty,2);
            $day_repay_late_fee = bcadd($day_repay_late_fee,$day_late_fee,2);
            $repay_late_fee = bcadd($repay_late_fee,$day_late_fee,2);
            $day_repay_amount =  bcadd(bcadd(bcadd(bcadd($day_repay_late_fee,$day_repay_penalty,2),$day_repay_management,2),$day_repay_interest,2),$day_repay_principal,2);
            $repay_amount = bcadd(bcadd($day_late_fee,$day_penalty,2),$repay_amount,2);



            $create_array =[
                'user_id' => $user_id,
                'order_id' => $order_id,
                'dateline' => $date,
                'day' => $expire_day,

                'day_repaid_amount'     => $day_repaid_amount,
                'day_repaid_late_fee'   => $day_repaid_late_fee,
                'day_repaid_penalty'    => $day_repaid_penalty,
                'day_repaid_management' => $day_repaid_management,
                'day_repaid_interest'   => $day_repaid_interest,
                'day_repaid_principal'  => $day_repaid_principal,

                'repay_amount'          => $repay_amount,
                'repay_late_fee'        => $repay_late_fee,
                'repay_penalty'         => $repay_penalty,
                'repay_management'      => $repay_management,
                'repay_interest'        => $repay_interest,
                'repay_principal'       => $repay_principal,

                'repaid_amount'         => $repaid_amount,
                'repaid_late_fee'       => $repaid_late_fee,
                'repaid_penalty'        => $repaid_penalty,
                'repaid_management'     => $repaid_management,
                'repaid_interest'       => $repaid_interest,
                'repaid_principal'      => $repaid_principal,

                'day_penalty'           => $day_penalty,
                'day_late_fee'          => $day_late_fee,
                'day_penalty_rate'      => $day_penalty_rate,
                'day_late_fee_rate'     => $day_late_fee_rate,
            ];

            if($insert_id = $this->create($bill_id,$create_array)) {
               $update_result = Model::factory('Bill')->update($bill_id,[
                    'repay_amount'      => $repay_amount,
                    'repay_late_fee'    => $repay_late_fee,
                    'repay_penalty'     => $repay_penalty,
                    'repay_management'  => $repay_management,
                    'repay_interest'    => $repay_interest,
                    'repay_principal'   => $repay_principal,
                    'penalty_time'      => time(),
                ]);
                if($update_result) {
                    return true;
                }

            }

        }
        return false;
    }


    /**
     * 还款
     * @param $bill_id
     * @param $amount
     * @param null $date
     * @return bool
     *
     * 说明: op = overdue penalty
     * 请在事务中使用本方法
     */
    public function in($bill_id,$amount,$date=null)
    {
        return $this->repaid($bill_id,$amount,$date);
    }
    public function repay($bill_id,$amount,$date=null)
    {
        return $this->repaid($bill_id,$amount,$date);
    }
    public function repaid($bill_id,$amount,$date=null)
    {
        if($bill_id<1 || $amount<0) {
            return false;
        }
        if($date===null){
            $date = date('Y-m-d');
        }
        $op = $this->getOneByBillId($bill_id,$date);
        if(!$op){
            $this->dayCreate($bill_id,$date,['must'=>true]);//创建罚息记录
            $op = $this->getOneByBillId($bill_id,$date);
            if(!$op){
                return false;
            }
        }

        $_amount = $amount;//复制一份金额作为下面计算临时值

        $user_id = $op['user_id'];
        $order_id = $op['order_id'];

        $day_repaid_amount = $op['day_repaid_amount'];
        $day_repaid_late_fee = $op['day_repaid_late_fee'];
        $day_repaid_penalty = $op['day_repaid_penalty'];
        $day_repaid_management = $op['day_repaid_management'];
        $day_repaid_interest = $op['day_repaid_interest'];
        $day_repaid_principal = $op['day_repaid_principal'];

        $repay_amount = $op['repay_amount'];
        $repay_late_fee = $op['repay_late_fee'];//滞纳金
        $repay_penalty = $op['repay_penalty'];//罚息
        $repay_management = $op['repay_management'];//管理费
        $repay_interest = $op['repay_interest'];//利息
        $repay_principal = $op['repay_principal'];//本金

        $repaid_amount = $op['repaid_amount'];
        $repaid_late_fee = $op['repaid_late_fee'];
        $repaid_penalty = $op['repaid_penalty'];
        $repaid_management = $op['repaid_management'];
        $repaid_interest =  $op['repaid_interest'];
        $repaid_principal =  $op['repaid_principal'];

        $day_unrepaid_late_fee = bcsub($repay_late_fee,$repaid_late_fee,2);//当日滞纳金
        $day_unrepaid_penalty = bcsub($repay_penalty,$repaid_penalty,2);//当日罚息
        $day_unrepaid_management = bcsub($repay_management,$repaid_management,2);//当日应还管理费
        $day_unrepaid_interest = bcsub($repay_interest,$repaid_interest,2);//当日日累计利息
        $day_unrepaid_principal = bcsub($repay_principal,$repaid_principal,2);//当日日应还本金

        $current_amount = 0.00;//本次扣款
        $current_late_fee = 0.00;
        $current_penalty = 0.00;
        $current_management = 0.00;
        $current_interest = 0.00;
        $current_principal = 0.00;
        $current_damage = 0.00;

        //滞纳金
        if(bccomp($_amount,$day_unrepaid_late_fee,2)>=0) {
            $_amount = bcsub($_amount,$day_unrepaid_late_fee,2);
            $day_repaid_late_fee = bcadd($day_repaid_late_fee,$day_unrepaid_late_fee,2);
            $repaid_late_fee = bcadd($repaid_late_fee,$day_unrepaid_late_fee,2);
            $current_late_fee = $day_unrepaid_late_fee;
            $day_unrepaid_late_fee = 0.00;
        } else {
            $day_unrepaid_late_fee = bcsub($day_unrepaid_late_fee,$_amount,2);
            $day_repaid_late_fee = bcadd($day_repaid_late_fee,$_amount,2);
            $repaid_late_fee  = bcadd($repaid_late_fee ,$_amount,2);
            $current_late_fee = $_amount;
            $_amount = 0.00;
        }

        //罚息
        if(bccomp($_amount,0,2)>0) {
            if(bccomp($_amount,$day_unrepaid_penalty,2)>=0) {
                $_amount = bcsub($_amount,$day_unrepaid_penalty,2);
                $day_repaid_penalty = bcadd($day_repaid_penalty,$day_unrepaid_penalty,2);
                $repaid_penalty = bcadd($repaid_penalty,$day_unrepaid_penalty,2);
                $current_penalty = $day_unrepaid_penalty;
                $day_unrepaid_penalty = 0.00;
            } else {
                $day_unrepaid_penalty = bcsub($day_unrepaid_penalty,$_amount,2);
                $day_repaid_penalty = bcadd($day_repaid_penalty,$_amount,2);
                $repaid_penalty = bcadd($repaid_penalty,$_amount,2);
                $current_penalty = $_amount;
                $_amount = 0.00;
            }
        }

        //管理费
        if(bccomp($_amount,0,2)>0) {
            if(bccomp($_amount,$day_unrepaid_management,2)>=0) {
                $_amount = bcsub($_amount,$day_unrepaid_management,2);
                $day_repaid_management = bcadd($day_repaid_management,$day_unrepaid_management,2);
                $repaid_management = bcadd($repaid_management,$day_unrepaid_management,2);
                $current_management = $day_unrepaid_management;
                $day_unrepaid_management = 0.00;
            } else {
                $day_unrepaid_management = bcsub($day_unrepaid_management,$_amount,2);
                $day_repaid_management = bcadd($day_repaid_management,$_amount,2);
                $repaid_management = bcadd($repaid_management,$_amount,2);
                $current_management = $_amount;
                $_amount = 0.00;
            }
        }

        //利息
        if(bccomp($_amount,0,2)>0) {
            if(bccomp($_amount,$day_unrepaid_interest,2)>=0) {
                $_amount = bcsub($_amount,$day_unrepaid_interest,2);
                $day_repaid_interest= bcadd($day_repaid_interest,$day_unrepaid_interest,2);
                $repaid_interest= bcadd($repaid_interest,$day_unrepaid_interest,2);
                $current_interest = $day_unrepaid_interest;
                $day_unrepaid_interest = 0.00;
            } else {
                $day_unrepaid_interest = bcsub($day_unrepaid_interest,$_amount,2);
                $day_repaid_interest = bcadd($day_repaid_interest,$_amount,2);
                $repaid_interest = bcadd($repaid_interest,$_amount,2);
                $current_interest = $_amount;
                $_amount = 0.00;
            }
        }

        //本金
        if(bccomp($_amount,0,2)>0){
            if(bccomp($_amount,$day_unrepaid_principal,2)>=0){
                $_amount = bcsub($_amount,$day_unrepaid_principal,2);
                $day_repaid_principal = bcadd($day_repaid_principal,$day_unrepaid_principal,2);
                $repaid_principal = bcadd($repaid_principal,$day_unrepaid_principal,2);
                $current_principal = $day_unrepaid_principal;
                $day_unrepaid_principal = 0.00;
            }else{
                $day_unrepaid_principal = bcsub($day_unrepaid_principal,$_amount,2);
                $day_repaid_principal = bcadd($day_repaid_principal,$_amount,2);
                $repaid_principal = bcadd($repaid_principal,$_amount,2);
                $current_principal = $_amount;
                $_amount = 0.00;
            }
        }

        $day_repaid_amount =  bcadd(bcadd(bcadd(bcadd($day_repaid_late_fee,$day_repaid_penalty,2),$day_repaid_management,2),$day_repaid_interest,2),$day_repaid_principal,2);
        $repaid_amount = bcadd($repaid_amount,$amount,2);




        $update_array = array(
            'day_repaid_amount'     => $day_repaid_amount,
            'day_repaid_late_fee'   => $day_repaid_late_fee,
            'day_repaid_penalty'    => $day_repaid_penalty,
            'day_repaid_management' => $day_repaid_management,
            'day_repaid_interest'   => $day_repaid_interest,
            'day_repaid_principal'  => $day_repaid_principal,
            'repaid_amount'         => $repaid_amount,
            'repaid_late_fee'       => $repaid_late_fee,
            'repaid_penalty'        => $repaid_penalty,
            'repaid_management'     => $repaid_management,
            'repaid_interest'       => $repaid_interest,
            'repaid_principal'      => $repaid_principal,
            'repay_amount'          => $repay_amount,
            'repay_late_fee'        => $repay_late_fee,
            'repay_penalty'         => $repay_penalty,
            'repay_management'      => $repay_management,
            'repay_interest'        => $repay_interest,
            'repay_principal'       => $repay_principal,
        );

        if( $this->update($op['id'],$update_array) ){

            Model::factory('Bill')->update($bill_id,[
                'repaid_amount'=>$repaid_amount,
                'repaid_late_fee'=>$repaid_late_fee,
                'repaid_penalty'=>$repaid_penalty,
                'repaid_management'=>$repaid_management,
                'repaid_interest'=>$repaid_interest,
                'repaid_principal'=>$repaid_principal,
                'penalty_time'=>time(),
            ]);
            return [
                'bill_id'               => $bill_id,
                'order_id'              => $order_id,
                'user_id'               => $user_id,
                'repaid_amount'         => $amount,
                'repaid_late_fee'       => $current_late_fee,
                'repaid_penalty'        => $current_penalty,
                'repaid_management'     => $current_management,
                'repaid_interest'       => $current_interest,
                'repaid_principal'      => $current_principal,
                'repaid_damage'         => $current_damage,
                'repaid_time'           => time(),
            ];
        }

        return false;
    }





    /** 删除记录(用于重新生成罚息&还款记录)
     * @param $bill_id
     * @return mixed
     */
    public function deleteByBillId($bill_id,$begin=null,$end=null)
    {
        $delete = DB::delete('bill_overdue_penalty')->where('bill_id', '=', $bill_id);
        if($begin!=null){
            $delete->and_where('dateline','>=',$begin);
        }
        if($end!=null){
            $delete->and_where('dateline','<=',$end);
        }
        return $delete->execute();
    }





    /** 重建罚息记录
     * @param $bill_id
     * @param null $begin_date 重算起始时间,包含当天
     * @return  array | bool
     */
    public function rebuild($bill_id,$begin_date=null)
    {
        $order = null;
        if($bill = Model::factory('Bill')->getOne($bill_id)){
            $order = Model::factory('Order')->getOne($bill['order_id']);
        } else {
            return false;
        }
        if(!$order) {
            return false;
        }

        //[根据产品类型选额费率]-----------------------------------------------------------
        $product_rate = $this->rate($order['type']);
        $penalty_max_day = $product_rate->getPenaltyMaxDay();//最大逾期罚息天数

        if($begin_date===null) {
            $this->deleteByBillId($bill['id']);
            $begin_time = $bill['expire_time'];//没有开始时间,过期时间
        } else {
            if(strtotime($begin_date)<$bill['expire_time']) {//早于逾期时间,使用逾期时间
                $this->deleteByBillId($bill['id'], date('Y-m-d',$bill['expire_time']));
                $begin_time = $bill['expire_time'];
            } else {
                $this->deleteByBillId($bill['id'], $begin_date);
                $begin_time = strtotime($begin_date);
            }
        }

        $time = time();
        $count_day = Lib::factory('Date')->countDayDay($begin_time, $time);
        $calc_day = $count_day > $penalty_max_day? $penalty_max_day : $count_day;
        $res = ['count_day'=>$count_day, 'calc_day'=>$calc_day,'dateline'=>[]];
        $i = 1;
        $_date = date('Y-m-d',$begin_time);

        while($i<=$calc_day) {
            $res['dateline'][$_date] = [];
            $amount = Model::factory('Bill_RepaidLog')->getAmount($bill['id'],Model_Bill_RepaidLog::OVERDUE_IN,$_date,$_date);
            if($amount>0) {
                $this->repaid($bill['id'],$amount,$_date);
                $res['dateline'][$_date]=['repaid'=>$amount];
            } else {
                $this->dayCreate($bill['id'],$_date);
                $res['dateline'][$_date]=['repaid'=>0];
            }
            $i++;
            $_date = date('Y-m-d',strtotime($_date.' +1 day'));
        }
        //逾期最大罚息天数之后的还款记录
        $new_date = date('Y-m-d',strtotime(date('Y-m-d').' +1 day'));
        $new_amount = Model::factory('Bill_RepaidLog')->getAmount($bill['id'],Model_Bill_RepaidLog::OVERDUE_IN,$_date, $new_date);
        if($new_amount>0) {
            $datelines =  Model::factory('Bill_RepaidLog')->getDistDate($bill['id'],Model_Bill_RepaidLog::OVERDUE_IN,$_date, $new_date);
            foreach($datelines as $r) {
                $amount = Model::factory('Bill_RepaidLog')->getAmount($bill['id'],Model_Bill_RepaidLog::OVERDUE_IN,$r['dateline']);
                $this->repaid($order['id'],$amount,$r['dateline']);
                $res['dateline'][$_date]=['repaid'=>$amount];
            }
        }
        return $res;
    }





}
/**
 *
 * 例:
 *
 * 逾期扣款成功时调用
 *
 *  try {
      $res = Model::factory('Bill_Penalty')->repaid($bill_id,200.00);
 *    if($res) {
        Model::factory('Bill_RepaidLog')->log($bill_id,[
 *          'user_id'=> $bill['user_id'],
 *          'order_id'=> $bill['order_id'],
 *          'dateline'=> date('Y-m-d'),
 *          'overdue'=> $overdue,// 1 or 2;
 *          'repaid_time'=> time(),
 *          'deduct_id'=> $deduct_id,//tc_finance_deduct.id
 *          'repaid_amount'=> $res['repaid_amount'],
 *          'repaid_principal'=> $res['repaid_principal'],
 *          'repaid_interest'=> $res['repaid_interest'],
 *          'repaid_management'=> $res['repaid_management'],
 *          'repaid_penalty'=> $res['repaid_penalty'],
 *          'repaid_late_fee'=> $res['repaid_late_fee'],
 *          'repaid_damage'=> $res['repaid_damage'],
 *      ]);
 *  }
 * } catch (Exception $e) {

 * }
 *
 *
 *
 */