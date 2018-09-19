<?php defined('SYSPATH') or die('No direct script access.');

/*
 * 订单核对页面
 *  * Lib::factory('Debug')->D($this->controller);
 * Tool::factory('Dir')->dir_path(self::$config['security_path']).$result[0]['uri']);
 * Tool::factory('Debug')->array2file(array(1,2,3,4,5), APPPATH.'../static/liu_test.php');
 *
 *
 * */

class Controller_Ver1_H5_APP_Loan extends AppCore {
//class Controller_Ver1_H5_APP_Loan extends Common {
    //订单
    //审核中
    const STATUSGROUP_ORDERAUDIT = [
        0,1,2,4,12,13,14,15
    ];

    //审核失败
    const STATUSGROUP_ORDERAUDITFAILURE = [
        3
    ];
    //订单关闭
    const STATUSGROUP_ORDERCLOSED = [
        10
    ];
    //已逾期
    const STATUSGROUP_ORDERBEYONDTHE = [
        50
    ];
    //已结算
    const STATUSGROUP_ORDERSETTLEMENT = [
        61,71
    ];
    //可还款
    const STATUSGROUP_ORDERREPAYMENT = [
        5
    ];

    //账单
    //不显示
    const STATUSGROUP_BILLNOSHOW = [
        0,4,10,15
    ];
    //待还款
    const STATUSGROUP_BILLREIMBURSEMENT = [
        5
    ];
    //还款处理中
    const STATUSGROUP_BILLPROCESSING = [
        6,8,9,11,56,59,58,511,30,31,530,531
    ];
    //已逾期
    const STATUSGROUP_BILLWTTL = [
        50
    ];
    //可还款
    const STATUSGROUP_BILLRREPAYMENT = [
        51,61,71
    ];
    public function before(){
        parent::before();
    }

    /*****************************************************************
     *订单确定页面
     * *************************************************************/
	public function action_Check()
	{
        $this->Load();
//        $this->ip_limit();
//        Lib::factory('TCCache_User')->user_id(6)->uri('app::$_user')->set('');
	    //判断用户是否登录
//        Tool::factory('Debug')->array2file($this->getVerification(), APPPATH.'../static/liu_test.php');
//        测试
//        App::$_token['user_id'] = 6;
//        App::$_user['identity_code'] = '372325198606260831';
//        App::$_user['name'] = "刘金生";
//        $this->_data['month'] = 6;
//        $this->_data['loan_amount'] = 1000;
//        $this->_data['bankcard_id'] = 100;
//        $this->_data['use_for_id'] = 102;
        if(!isset(App::$_token['user_id'])||!Valid::not_empty(App::$_token['user_id'])){
            $this->error("用户未登录！");
        }

//        Tool::factory('Debug')->D(array(App::$token,App::$_token,App::$_user,$_REQUEST,$_SERVER));
        parent::$_VArray['life_of_loan'] = isset($this->_data['month']) && $this->_data['month'] > 0 ? (int)$this->_data['month'] : 0;
        parent::$_VArray['loan_amount'] = isset($this->_data['loan_amount']) && $this->_data['loan_amount'] > 0 ? $this->_data['loan_amount'] : 0;
        parent::$_VArray['use_for_id'] = isset($this->_data['use_for_id']) ? (int)$this->_data['use_for_id'] : 0;

        if (!in_array((int)parent::$_VArray['life_of_loan'], Model_Order_Inst::MONTH_MAP) || parent::$_VArray['use_for_id'] < 1 || parent::$_VArray['loan_amount']<0
        ) {
            $this->error("参数错误");
        }

        //用户基本信息
//        $formula = Model::factory('Order_Formula_Inst');
        parent::$_VArray['name'] = App::$_user['name'];
        parent::$_VArray['identity_code'] = substr_replace(App::$_user['identity_code'], '*****', 6, 4);

        //首次借款判断与费率
        $is_first = Model::factory('Order')->firstByUserId(App::$_token['user_id']);
        $formula = Model::factory('Order_Formula_Inst');
        $charge_rate = 1.0;
        if ($is_first) {
            $fee = $formula->first(parent::$_VArray['loan_amount'], parent::$_VArray['life_of_loan'], $charge_rate);
            $item = Model::factory('Order_Charge')->make_fee_item(parent::$_VArray['loan_amount'], parent::$_VArray['life_of_loan'], $fee);
        } else {
            $fee = $formula->again(parent::$_VArray['loan_amount'], parent::$_VArray['life_of_loan'], $charge_rate);
            $item = Model::factory('Order_Charge')->make_fee_item(parent::$_VArray['loan_amount'], parent::$_VArray['life_of_loan'], $fee);

        }

        //借款用途
        parent::$_VArray['use_for'] = Model::factory('LoanUseFor')->getOne(parent::$_VArray['use_for_id']);
        if(!Valid::not_empty(parent::$_VArray['use_for'])){
            $this->error("参数错误");
        }


//        Lib::factory('Debug')->D(array(App::$_token['user_id'], parent::$_VArray['bankcard_id']));

        //借记卡
        $bankcard = Model::factory('BankCard')->get_one_by_user_id(App::$_token['user_id']);
        if(isset($bankcard['id']) && $bankcard['id'] > 0){
            parent::$_VArray['bankcard_id'] = $bankcard['id'];
        }else{
            $this->error("储蓄卡错误");
        }
        //卡信息
        $bank = Model::factory('Bank')->get_one($bankcard['bank_id']);
        if(empty($bank)){
            $this->error("储蓄卡错误");
        }

        parent::$_VArray['total_repay'] = bcadd($fee,parent::$_VArray['loan_amount'],2);
        parent::$_VArray['total_interest'] = $item[Model_Order_Charge::TYPE_INTEREST['code']]['amount'];
        parent::$_VArray['total_platform_manage'] = $item[Model_Order_Charge::TYPE_PLATFORM_MANAGE['code']]['amount'];
        parent::$_VArray['month_repay'] = bcdiv(parent::$_VArray['total_repay'], parent::$_VArray['life_of_loan'], 2);
        parent::$_VArray['start_end_date'] = date('Y/m/d') . '-' . $formula->date_formula(parent::$_VArray['life_of_loan'] + 1);
        parent::$_VArray['first_repay_date'] = $formula->date_formula(1);
        parent::$_VArray['repay_date'] = '每月' . date('d') . '日';

        $sub_card_no = substr($bankcard['card_no'], -4);
        parent::$_VArray['card_loan'] = $bank['name'] . '（' . $sub_card_no . '）';

        //借款类型
        $type = Model_Order::TYPE_INST;
        //费率说明和活动内容HTML
        $order_rate_html = Lib::factory('Helper_OrderFeeRate')->html($type);
        parent::$_VArray['foot_html'] = $order_rate_html ? $order_rate_html : '';
        parent::$_VArray['title'] = '确认借款';

//        请求地址
        parent::$_VArray['reqUrl'] = '/v1/Inst_Loan/Apply';
//        跳转页面
        parent::$_VArray['jumpUrl'] = '/v1/v/Loan/CheckStatic';
        //请求接口验证token
        parent::$_VArray['_token'] = Lib::factory('Target_Token')->make();
        parent::$_VArray['seajsVer'] = $this->getVerification();
//        parent::$_VArray['seajsVer'] = 'app_id=android&ver=1.0.0&os=5.1&unique_id=869609022752180&ip=61.51.129.138&token=TA20180115214638PVEyiIFphAhsTtmR';
        $view = View::factory($this->_vv.'Loan/Check');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
	}

    /*****************************************************************
     *订单提交后页面
     * *************************************************************/
    public function action_CheckStatic(){
        $this->Load();

//        测试
//        App::$_token['user_id'] = 6;

        $orderId = isset($_GET['orderId'])?(Valid::not_empty($_GET['orderId'])?(int)$_GET['orderId']:''):'';
        if(!Valid::not_empty($orderId)){
            $this->error("获取数据失败！");
        }else{
            $orderInfo = Model::factory('Order')->getOneByUserId(App::$_token['user_id'],$orderId);
        }

        parent::$_VArray['loan_amount'] = $orderInfo['loan_amount'];
        //银行卡
        $bankcard = Model::factory('BankCard')->get_one_by_user_id(App::$_token['user_id'], $orderInfo['bankcard_id']);
        $bank = Model::factory('Bank')->get_one($bankcard['bank_id']);
        if(empty($bank)){
            $this->error("储蓄卡错误");
        }

        $sub_card_no = substr($orderInfo['bankcard_no'], -4);
        parent::$_VArray['bandcard'] = $bank['name'] . '（' . $sub_card_no . '）';
        parent::$_VArray['jumpUrl'] = '/v1/v/Loan/Detail?route=checkstatic&orderId='.$orderId;
        parent::$_VArray['title'] = '提交成功';
        $view = View::factory($this->_vv.'Loan/CheckStatic');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }
    /*****************************************************************
     *借款详情
     * *************************************************************/
    public function action_Detail(){
        $this->Load();
        //测试：
//        $orderId = 731471;
        $orderId = isset($_GET['orderId'])?(Valid::not_empty($_GET['orderId'])?(int)$_GET['orderId']:''):'';
        if(!Valid::not_empty($orderId)){
            $this->error("获取数据失败！");
        }else{
            $orderInfo = Model::factory('Order')->getOneByUserId(App::$_token['user_id'],$orderId);
        }

        if(!Valid::not_empty($orderInfo)){
            $this->error("订单错误");
        }

        //审核中红字提示
        if(isset($orderInfo['status'])&&in_array($orderInfo['status'],self::STATUSGROUP_ORDERAUDIT)){
            parent::$_VArray['redTip'] = '实际还款日按实际放款日计算结果为准';
        }
//        App::$_token['user_id'] = 6;
        //获得订单内容
        //获取分期订单信
        $billInfo = Model::factory('Bill')->getByArray(array('order_id' =>$orderInfo['id']),array(array('number','ASC')));
        if(Valid::not_empty($billInfo)){
            //组装订单
            //默认标识符
            $ide = true;
            parent::$_VArray['billStr'] = '';
            foreach ($billInfo as $key=>$val){
                $statusMsg = $this->getBillStatus($val);

                //应还金额判断
                $repay_amount = bcsub($val['repay_amount'],$val['repaid_amount'],2);
                if($repay_amount<0){
                    $repay_amount = 0;
                }
                //是否是首个（$ide判断）是否可还款（op控制）显示什么颜色（stype判断）
                if($statusMsg['op']&&$ide&&$repay_amount>0){
                    parent::$_VArray['billId'] = $val['id'];
                    parent::$_VArray['billRepayAmount'] = '￥'.$val['repay_amount'];
                    if($statusMsg['stype'] == 2){
                        parent::$_VArray['billStr'] .= '<li class="canClick t-fontcolor-red"><span><i class="listatic  listatic-ok" data-code="'.$val['id'].'"></i><label>'.date('Y/m/d',$val['expire_time']).'</label></span><span>'.$statusMsg['msg'].'</span><span id="money">￥'.$repay_amount.'</span>'.(($key==0)?"":'<div class="detail-borderbottom"></div>').'</li>';
                    }else if($statusMsg['stype'] == 3){
                        parent::$_VArray['billStr'] .= '<li class="canClick t-fontcolor-blue"><span><i class="listatic  listatic-ok" data-code="'.$val['id'].'"></i><label>'.date('Y/m/d',$val['expire_time']).'</label></span><span>'.$statusMsg['msg'].'</span><span id="money">￥'.$repay_amount.'</span>'.(($key==0)?"":'<div class="detail-borderbottom"></div>').'</li>';
                    }else{
                        parent::$_VArray['billStr'] .= '<li class="canClick" style="color: #9F9F9F;"><span><i class="listatic  listatic-ok" data-code="'.$val['id'].'"></i><label>'.date('Y/m/d',$val['expire_time']).'</label></span><span>'.$statusMsg['msg'].'</span><span id="money">￥'.$repay_amount.'</span>'.(($key==0)?"":'<div class="detail-borderbottom"></div>').'</li>';
                    }
                    $ide = false;
                }else{
                    if($statusMsg['op']&&$repay_amount>0){
                        if($statusMsg['stype'] == 2){
                            parent::$_VArray['billStr'] .= '<li class="canClick t-fontcolor-red"><span><i class="listatic  listatic-no" data-code="'.$val['id'].'"></i><label>'.date('Y/m/d',$val['expire_time']).'</label></span><span>'.$statusMsg['msg'].'</span><span id="money">￥'.$repay_amount.'</span>'.(($key==0)?"":'<div class="detail-borderbottom"></div>').'</li>';
                        }else if($statusMsg['stype'] == 3){
                            parent::$_VArray['billStr'] .= '<li class="canClick t-fontcolor-blue"><span><i class="listatic  listatic-no" data-code="'.$val['id'].'"></i><label>'.date('Y/m/d',$val['expire_time']).'</label></span><span>'.$statusMsg['msg'].'</span><span id="money">￥'.$repay_amount.'</span>'.(($key==0)?"":'<div class="detail-borderbottom"></div>').'</li>';
                        }else{
                            parent::$_VArray['billStr'] .= '<li class="canClick" style=" style="color: #9F9F9F;"><span><i class="listatic  listatic-no" data-code="'.$val['id'].'"></i><label>'.date('Y/m/d',$val['expire_time']).'</label></span><span>'.$statusMsg['msg'].'</span><span id="money">￥'.$repay_amount.'</span>'.(($key==0)?"":'<div class="detail-borderbottom"></div>').'</li>';
                        }
                    }else{
                        //对于已经还款完成的显示所有还款金额
                        if(in_array($val['status'],self::STATUSGROUP_BILLRREPAYMENT)){
                            parent::$_VArray['billStr'] .= '<li><span><i class="listatic"></i><label>'.date('Y/m/d',$val['expire_time']).'</label></span><span>'.$statusMsg['msg'].'</span><span>￥'.$val['repay_amount'].'</span>'.(($key==0)?"":'<div class="detail-borderbottom"></div>').'</li>';
                        }else{
                            parent::$_VArray['billStr'] .= '<li><span><i class="listatic"></i><label>'.date('Y/m/d',$val['expire_time']).'</label></span><span>'.$statusMsg['msg'].'</span><span>￥'.$repay_amount.'</span>'.(($key==0)?"":'<div class="detail-borderbottom"></div>').'</li>';
                        }
                    }
                }
            }
        }else{
            $this->error("账单获取数据失败！");
        }
        //判断是否开启还清所有按钮
        if($ide){
            //不能开启
            parent::$_VArray['repayAll'] = '';
            parent::$_VArray['submit'] = '';
        }else{
            //能开启
            parent::$_VArray['repayAll'] ='<a class="returnRight" href="/v1/v/Loan/Repayment?billId=all&orderId='.$orderId.'">还清所有</a>';
            parent::$_VArray['submit'] = '<button class="t-blue-btn submit">立即还款</button>';
        }

        //银行卡
        $bankcard = Model::factory('BankCard')->get_one_by_user_id(App::$_token['user_id'], $orderInfo['bankcard_id']);
        $bank = Model::factory('Bank')->get_one($bankcard['bank_id']);
        //   $resultStatus['stype'] = 3;
        $resultStatus = $this->getOrderStatus($orderInfo);
        if($resultStatus['stype'] == 2){
            $statusStr = '<p style="padding-top:1rem;font-size: .8rem;color: #E23200;"><strong>'.$resultStatus['msg'].'</strong></p><p style="color: #9F9F9F;">您已逾期，逾期会产生罚息与违约金，请尽快还款</p>';
//            $statusStr = '<p style="padding-top:1rem;font-size: .8rem;color: #9F9F9F;">'.$resultStatus['msg'].' <br><label style="margin-top: .3rem;display: block">您已逾期，逾期会产生罚息与违约金，请尽快还款</label></p>';
        }else if($resultStatus['stype'] == 3){
            $statusStr = '<p style="padding-top: 1.7rem;font-size: .8rem;color: #0085EC;"><strong>'.$resultStatus['msg'].'</strong></p>';
        }else{
            $statusStr = '<p style="padding-top: 1.7rem;font-size: .8rem;color: #9F9F9F;"><strong>'.$resultStatus['msg'].'</strong></p>';
        }

        //获取合同
//        $orderId = 731515;
        $result = Model::factory("JunZiQian")->getInfo($orderId);
        if(Valid::not_empty($result)){
            $url = Lib::factory("JunZiQian_Contract")->DownLoadContract($result['contract_id']);
            parent::$_VArray['downUrl'] = 'javascript:$AppInst.WebJump({\'type\':\'web_abroad\',\'par\':\''.$url.'\'});';
        }else{
            parent::$_VArray['downUrl'] = "javascript:layerPhone.BombBox('暂时未生成合同，请稍后再试');layerPhone.changeCssBombBox();";
        }
//        echo '<pre>';
//        print_r(parent::$_VArray);
//        echo '</pre>';
//        echo "<a href='?#jump=no'>退出</a>";
//        die;
        parent::$_VArray['orderInfo'] = array(
            'orderId'=>$orderInfo['id'],
            'loan_amount'=>$orderInfo['loan_amount'],
            'month'=>$orderInfo['month'],
            'bankcard_no'=>$bank['name'].'('.substr($orderInfo['bankcard_no'], -4).')',
            'status'=>$statusStr
        );

        //状态判断
        parent::$_VArray['title'] = '借款详情';
        parent::$_VArray['jumpUrl'] = '/v1/v/Loan/Repayment';
        parent::$_VArray['goback'] = (isset($_GET['route'])&&$_GET['route']=='checkstatic')?'?#jump=InstBorrowList':'?#jump=no';

        $view = View::factory($this->_vv.'Loan/Detail');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }
    /*****************************************************************
     *还款页面
     * *************************************************************/
    public function action_Repayment(){
        $this->Load();

//        测试
//        App::$_token['user_id'] = 6;

        parent::$_VArray['orderid'] = isset($_GET['orderId'])?(Valid::not_empty($_GET['orderId'])?(int)$_GET['orderId']:''):'';
        parent::$_VArray['billId'] = isset($_GET['billId'])?(Valid::not_empty($_GET['billId'])?$_GET['billId']:''):'';

        if(!Valid::not_empty(parent::$_VArray['orderid'])){
            $this->error("获取数据失败！");
        }else{
            $orderInfo = Model::factory('Order')->getOneByUserId(App::$_token['user_id'],parent::$_VArray['orderid']);
        }
        if(Valid::not_empty(parent::$_VArray['billId'])){
            if(parent::$_VArray['billId']=='all'){
                //全还
                $billInfo = Model::factory('Bill')->getByArray(array('order_id' =>parent::$_VArray['orderid'],'status'=>array_merge(self::STATUSGROUP_BILLREIMBURSEMENT,self::STATUSGROUP_BILLWTTL)),array(array('number','DESC')));
            }else{
                //部分还款
                $billInfo = Model::factory('Bill')->getByArray(array('order_id' =>parent::$_VArray['orderid'],'bill_id'=>parent::$_VArray['billId'],'status'=>array_merge(self::STATUSGROUP_BILLREIMBURSEMENT,self::STATUSGROUP_BILLWTTL)));
            }
        }else{

            $this->error("获取数据失败！");
        }
        parent::$_VArray['billStr'] = '';

        if (Valid::not_empty($billInfo)){
            //还款总金额
            parent::$_VArray['repayMoney'] = 0;
            $Tool = Tool::factory('String');
            foreach ($billInfo as $key => $val){
                //还款金额
                //应还金额判断
                $repay_amount = bcsub($val['repay_amount'],$val['repaid_amount'],2);
                if($repay_amount<0){
                    $repay_amount = 0;
                }
                parent::$_VArray['repayMoney'] = bcadd(parent::$_VArray['repayMoney'],$repay_amount,2);
                parent::$_VArray['billStr'] .= '<p>'.date('Y/m/d',$val['expire_time']).'(第'.$Tool->numeric($val['number']).'期)<label>￥'.$repay_amount.'</label></p>';
            }
        }else{
            $this->error("获取数据失败！");
        }

//        Lib::factory('Debug')->D(array($billId,$billInfo));

        //银行卡
        $bankcard = Model::factory('BankCard')->get_one_by_user_id(App::$_token['user_id'], $orderInfo['bankcard_id']);
        $bank = Model::factory('Bank')->get_one($bankcard['bank_id']);
        parent::$_VArray['bankcard_no'] = $bank['name'].'   '.substr($orderInfo['bankcard_no'], -4);

        parent::$_VArray['title'] = "还款";
        parent::$_VArray['reqSMS'] = '/v1/Order/RepayVerifySMS';
        parent::$_VArray['reqUrl'] = '/v1/Order/SubmitRepay';
        parent::$_VArray['jumpUrl'] = '/v1/v/Loan/RepaymentStatic';


        parent::$_VArray['seajsVer'] = $this->getVerification();
//        parent::$_VArray['seajsVer'] = 'app_id=android&ver=1.0.0&os=5.1&unique_id=869609022752180&ip=61.51.129.138&token=TA20180115214638PVEyiIFphAhsTtmR';
        $view = View::factory($this->_vv.'Loan/Repayment');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }

    /*****************************************************************
     *还款成功页面
     * *************************************************************/
    public function action_RepaymentStatic(){
        parent::$_VArray['title'] = '提交成功';
        parent::$_VArray['jumpUrl'] = '?#jump=yes';
        $view = View::factory($this->_vv.'Loan/RepaymentStatic');
        $view->_VArray = parent::$_VArray;
        $this->response->body($view);
    }




    //订单状态（type=1为订单，type=2为账单）
    public function getOrderStatus($order=null){
        if(!Valid::not_empty($order)){
            return false;
        }
        $resultStatus = '';
        $resultStatus['stype'] = 1; //1为灰色，2位红色，3位蓝色
        if(in_array($order['status'],self::STATUSGROUP_ORDERAUDIT)){
            $resultStatus['msg'] = '审核中';
            $resultStatus['op'] = false;
            //审核失败
        }elseif(in_array($order['status'],self::STATUSGROUP_ORDERAUDITFAILURE)){
            $resultStatus['msg'] = '审核失败';
            $resultStatus['op'] = false;
            //订单关闭
        }elseif(in_array($order['status'],self::STATUSGROUP_ORDERCLOSED)){
            $resultStatus ['msg']= '订单关闭';
            $resultStatus['op'] = false;
            //处理中订单
        }elseif(in_array($order['status'],self::STATUSGROUP_ORDERREPAYMENT)){
//            expire_time
            //获取账单
            $billStill = Model::factory('Bill')->getByArray(array('order_id' =>$order['id'],'status'=>array_merge(self::STATUSGROUP_BILLREIMBURSEMENT,self::STATUSGROUP_BILLPROCESSING)),array(array('number','ASC')),1);
            if (Valid::not_empty($billStill)){
                $resultStatus['msg'] = date('m月d日',$billStill['expire_time']).'应还'.$billStill['repay_amount'].'元';
                $resultStatus['op'] = true;
                $resultStatus['stype'] = 3;
            }else{
                $this->error("获取数据异常！");
            }
            //已逾期
        }elseif(in_array($order['status'],self::STATUSGROUP_ORDERBEYONDTHE)){
            $resultStatus['msg'] = '已逾期';
            $resultStatus['op'] = true;
            $resultStatus['stype'] = 2;
            //已结清
        }elseif(in_array($order['status'],self::STATUSGROUP_ORDERSETTLEMENT)){
            $resultStatus['msg'] = '已结清';
            $resultStatus['op'] = false;
        }else{
            $resultStatus['msg'] = '未知状态';
            $resultStatus['op'] = false;
        }

        return $resultStatus;
    }

    //账单状态
    public function getBillStatus($bill=null){

        if(!Valid::not_empty($bill)){
            return false;
        }
        $resultStatus = '';
        $resultStatus['stype'] = 1; //1为灰色，2位红色，3位蓝色
        if(in_array($bill['status'],self::STATUSGROUP_BILLNOSHOW)){
            $resultStatus['msg'] = '';
            $resultStatus['op'] = false;
        }elseif(in_array($bill['status'],self::STATUSGROUP_BILLREIMBURSEMENT)){
            $resultStatus['stype'] = 3;
            $resultStatus['msg'] = '待还款';
            //判断该账单是否开启还款模式
            if($bill['start_time']<time()){
                $resultStatus['op'] = true;
            }else{
                $resultStatus['op'] = false;
            }
        }elseif(in_array($bill['status'],self::STATUSGROUP_BILLPROCESSING)){
            $resultStatus ['msg']= '还款处理中';
            $resultStatus['op'] = false;
        }elseif(in_array($bill['status'],self::STATUSGROUP_BILLWTTL)){
//            expire_time
            $resultStatus['stype'] = 2;
            $resultStatus['msg'] = '已逾期';
            if($bill['start_time']<time()){
                $resultStatus['op'] = true;
            }else{
                $resultStatus['op'] = false;
            }
            //已逾期
        }elseif(in_array($bill['status'],self::STATUSGROUP_BILLRREPAYMENT)){
            $resultStatus['msg'] = '已还款';
            $resultStatus['op'] = false;
        }else{
            $resultStatus['msg'] = '未知状态';
            $resultStatus['op'] = false;
        }
        return $resultStatus;
    }
//ip限制
    public function ip_limit(){
        $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
        $ip = explode(",",$ip)[0];
        if(in_array($ip,array('61.51.129.141','61.51.129.138'))){
        }else{
            echo '维护中.....';
            die;
        }
    }
}
