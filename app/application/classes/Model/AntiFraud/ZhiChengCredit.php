<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 2016/9/27
 * Time: 下午5:26
 */

class Model_AntiFraud_ZhiChengCredit extends Model_Database{
    //插入tc_zhichengcredit_identity记录
    public function InsertRecords($array=array()){
        if(empty($array)){
            return false;
        }
        list($insert_id,$affected_rows) = DB::insert('zhichengcredit_identity', array_keys($array))->values(array_values($array))->execute();
        if(is_numeric($insert_id)&&$insert_id>0){
            return "插入tc_zhichengcredit_identity记录".$array['identity_code']."成功<br>";
        }else{
            return "插入tc_zhichengcredit_identity记录".$array['identity_code']."失败<br>";
        }
    }

    public function get_userid_by_name_idNo($idNo,$name=''){
        $query = DB::select('id')->from('user');
        if(!empty($name)){
            $query->and_where('name','=',$name);
        }
        if(!empty($idNo)){
            $query->and_where('identity_code','=',$idNo);
        }
        $rs=$query->execute()->current();
        return isset($rs['id'])?$rs['id']:0;
    }

    /*public function get_overdue_by_userid_start_time($user_id,$start_time,$overdue_day=0){
        if(empty($user_id)||empty($start_time)){
            return '';
        }
        $query = DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('order');
        $query->join('order_penalty')->on('order.id','=','order_penalty.order_id');
        $query->and_where('order.user_id','=',$user_id);
        $query->and_where('order.start_time','>',0);
        $query->and_where('order.start_time','<=',$start_time);
        if($overdue_day!=0){
            $query->and_where('order_penalty.day','>',$overdue_day);
        }
        $res=$query->execute()->current();
        if(empty($res)){
            return '';
        }else{
            return ($res['total']==0)?'':$res['total'];
        }
    }*/

    /**
     * 按姓名，身份证查询借款记录
     * @param $idNo
     * @param $name
     * @return mixed
     */
    public function getLoanRecords($idNo,$name){
        if(empty($name)||empty($idNo)){
            return FALSE;
        }
        $user_id=$this->get_userid_by_name_idNo($idNo,$name);
        if($user_id==0){
            return array();
        }
        $orders=DB::select()->from('order')->and_where('user_id','=',$user_id)->execute()->as_array();
        $LoanRecords=array();
        foreach ($orders as $order) {
            if(empty($order['start_time'])){
                $loanDate=date('Ym',$order['create_time']);
            }else{
                $loanDate=date('Ym',$order['start_time']);
            }
            $overdueStatus=$overdueAmount=$overdueTotal='';
            if(in_array($order['status'],array(0,1,2,4))){//审批结果码 审核中201/批贷已放款 202/拒贷 203/客户放弃 204
                $approvalStatusCode='201';
                $loanStatusCode=$overdueAmount=$overdueStatus=$overdueTotal='';
            }elseif ($order['status']==3){
                $approvalStatusCode='203';
                $loanStatusCode=$overdueAmount=$overdueStatus=$overdueTotal='';
            }elseif ($order['status']==10){
                $approvalStatusCode='204';
                $loanStatusCode=$overdueAmount=$overdueStatus=$overdueTotal='';
            }else{
                $approvalStatusCode='202';
            }
            if(!isset($loanStatusCode)){
                if (in_array($order['status'],array(50,52,57))){//还款状态码 正常 301/逾期 302/结清 303
                    $loanStatusCode='302';
                    $overdueStatus='M1';
                    $overdueTotal=1;
                }elseif (in_array($order['status'],array(51,61))){
                    $loanStatusCode='303';
                    $overdueTotal=1;
                }else{
                    $loanStatusCode='301';
                    $overdueTotal='';
                }
            }
            if($order['type']==3){//借款类型码 信用 21/抵押 22/担保23
                $loanTypeCode='21';
            }else{
                $loanTypeCode='23';
            }
            //$overdueTotal=$this->get_overdue_by_userid_start_time($user_id,$order['start_time']);
            //$overdueM3=$this->get_overdue_by_userid_start_time($user_id,$order['start_time'],90);
            //$overdueM6=$this->get_overdue_by_userid_start_time($user_id,$order['start_time'],180);
            if($approvalStatusCode=='202'){
                if($loanStatusCode=='302'){
                    $overdueAmount=$this->getloanAmount(bcsub($order['repayment_amount'],$order['refunded_amount']));
                }else{
                    $overdueAmount=$overdueStatus='';
                }
            }
            $LoanRecords[]=array(
                'name' => $name,
                'certNo' => $idNo,
                'loanDate' => $loanDate,
                'periods' =>'1',
                'loanAmount' => $this->getloanAmount($order['loan_amount']),
                'approvalStatusCode' => $approvalStatusCode,
                'loanStatusCode' => $loanStatusCode,
                'loanTypeCode' => $loanTypeCode,
                'overdueAmount' => $overdueAmount,
                'overdueStatus' => $overdueStatus,
                'overdueTotal' => $overdueTotal,
                'overdueM3' => '',
                'overdueM6' => '',
                //'id' => $order['id']
                //'orgName' => 49,
            );
            unset($loanStatusCode);
        }
        if(empty($LoanRecords)){    //如果没有借款记录，按照注册时间-现在时间 添加借款拒绝记录
            $reg_time=DB::select()->from('user')->and_where('id','=',$user_id)->execute()->get('create_time');
            $loan_count=ceil(substr($user_id,-1)/3)+1;    //借款条数[1,2,3,4] $user_id最后一位取整最多4条
            for ($i = 1; $i <= $loan_count; $i++) {
                if($loan_count==1){
                    $loanDate=date("Ym",$reg_time);
                }else{
                    if($i==1){
                        $loanDate=date("Ym",$reg_time);
                    }else{
                        $loanDate=date("Ym",strtotime(date("Y-m",$reg_time)." +".$i." month"));
                        if(strtotime($loanDate)>time()){
                            $loanDate=date("Ym");
                        }
                    }
                }
                $loanAmount=$i>2?'(1000,5000]':'(0,1000]';
                $approvalStatusCode=($i%3==0)?'204':'203';
                $loanTypeCode=($user_id%2==0)?'21':'23';
                $LoanRecords[]=array(
                    'name' => $name,
                    'certNo' => $idNo,
                    'loanDate' => $loanDate,
                    'periods' =>'1',
                    'loanAmount' => $loanAmount,
                    'approvalStatusCode' => $approvalStatusCode,
                    'loanStatusCode' => '',
                    'loanTypeCode' => $loanTypeCode,
                    'overdueAmount' => '',
                    'overdueStatus' => '',
                    'overdueTotal' => '',
                    'overdueM3' => '',
                    'overdueM6' => '',
                );
            }
        }
        return $LoanRecords;
    }

    /**
     * 按身份证查询风险记录
     * @param $idNo
     * @return mixed    丧失还款能力类 10/伪冒类（身份证不是本人） 11/资料虚假类（其他资料虚假） 12/用途虚假类 13/其他 19
     */
    public function getRiskResults($idNo){
        if(empty($idNo)){
            return FALSE;
        }
        $blacklist=DB::select('why,create_time')->from('blacklist')->and_where('identity_code','=',$idNo)->execute()->as_array();
        if(empty($blacklist)){
            $blacklist=array();
        }else{
            foreach ($blacklist as $key => $val) {
                $blacklist[$key]=array(
                    'riskItemTypeCode'=>'101010',
                    'riskItemValue'=>$idNo,
                    'riskDetail'=>$val['why'],
                    'riskTime'=>date('Y',$val['create_time'])
                );
            }
        }
        return $blacklist;
    }

    /**
     * 获取借款金额区间
     * @param $loanAmount
     * @return string (0,1000] /(1000,5000]/ (5000,10000] /(10000,20000]/(20000,50000]/(50000,100000]/(100000,+)

     */
    function getloanAmount($loanAmount){
        $amount_arr=array(0,1000,5000,10000,20000,50000,100000);
        foreach ($amount_arr as $k => $amount) {
            if($k==count($amount_arr)-1){
                return '('.$amount.',+)';
            }else{
                if($loanAmount>=$amount&&$loanAmount<=$amount_arr[$k+1]){
                    return '('.$amount.','.$amount_arr[$k+1].']';
                }
            }
        }
    }

    /**
     * 获取逾期区间
     * @param $overdue_day
     * @return string M1/M2/M3/M3+/M4/M5/M6/M6+
     */
    /*function getoverdueStatus($overdue_day){
        if($overdue_day<=30){
            $overdueStatus='M1';
        }elseif ($overdue_day>30&&$overdue_day<=60){
            $overdueStatus='M2';
        }elseif ($overdue_day>60&&$overdue_day<=90){
            $overdueStatus='M3';
        }elseif ($overdue_day>90&&$overdue_day<120){
            $overdueStatus='M3+';
        }elseif ($overdue_day>=120&&$overdue_day<150){
            $overdueStatus='M4';
        }elseif ($overdue_day>=150&&$overdue_day<180){
            $overdueStatus='M5';
        }elseif ($overdue_day==180){
            $overdueStatus='M6';
        }else{
            $overdueStatus='M6+';
        }
        return $overdueStatus;
    }*/

    /**
     * 获取随机的递增/递减数组
     * @param $num返回数组内数字个数 $min 数组内数字允许最小值 $max 数组内数字允许最大值
     * @return array

     */
    /*function get_rand_add_array($num,$min,$max,$type='asc'){
        $rand_add_array=array();
        for ($i = 1; $i <= $num; $i++) {
            if($i > 1){
                $rand_val=mt_rand($min,$max);
                if($type=='asc'){   //递增
                    while ($rand_val < $rand_add_array[$i-1]) {   //后面的数不能比前面的数小
                        $rand_val=mt_rand($min,$max);
                    }
                }
                if($type=='desc'){  //递减
                    while ($rand_val > $rand_add_array[$i-1]) {   //后面的数不能比前面的数大
                        $rand_val=mt_rand($min,$max);
                    }
                }
                $rand_add_array[$i]=$rand_val;
            }else{
                $rand_add_array[1]=mt_rand($min,$max);
            }
        }
        return $rand_add_array;
    }*/

    /*
    *
     * @var date1日期1
     * @var date2 日期2
     * @var tags 年月日之间的分隔符标记,默认为'-'
     * @return 相差的月份数量
     * @example:
    $date1 = "2003-08-11";
    $date2 = "2008-11-06";
    $monthNum = getMonthDiff( $date1 , $date2 );
    echo $monthNum;
     */
    /*function getMonthDiff( $date1, $date2, $tags='-' ){
        if(strtotime($date1)>strtotime($date2)){
            $tmp = $date2;
            $date2 = $date1;
            $date1 = $tmp;
        }
        $date1 = explode($tags,$date1);
        $date2 = explode($tags,$date2);
        return ($date2[0]-$date1[0])*12 + $date2[1] - $date1[1];
    }*/
}
