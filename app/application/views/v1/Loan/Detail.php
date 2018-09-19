<?php include Kohana::find_file('views', 'v1/public/head');?>
<script src="/static/js/inst.app.js"></script>
<script>
    seajs.config({
        vars: {
            orderId:'<?php echo isset($_VArray['orderInfo']['orderId'])?$_VArray['orderInfo']['orderId']:0;?>',
            billId:'<?php echo isset($_VArray['billId'])?$_VArray['billId']:0;?>',
            jumpUrl:'<?php echo isset($_VArray['jumpUrl'])?$_VArray['jumpUrl']:'';?>',
        }
    });
    seajs.use('js/seajs/detail');
    var $AppInst = new $.AppInst();
</script>
<section class="t-login-nav">
    <div class="t-login-nav-1">
        <a class="return_i i_public" href="<?php echo isset($_VArray['goback'])?$_VArray['goback']:'';?>"></a>
    <label><?php echo isset($_VArray['title'])?$_VArray['title']:'';?></label>
        <?php echo isset($_VArray['repayAll'])?$_VArray['repayAll']:'';?>
    </div>
</section>
<div class="t-top-vacancy"></div>
<section class="detail-static">
    <?php echo $_VArray['orderInfo']['status'] ?>
</section>

<section class="detail-plan">
    <p >还款计划<strong style="font-size: .6rem;color: #E23200;margin-left: .6rem;height: 1rem;font-weight: 100"><?php echo isset($_VArray['redTip'])?$_VArray['redTip']:'' ?></strong></p>
    <ul  style="margin: .5rem;">
            <?php echo isset($_VArray['billStr'])?$_VArray['billStr']:'' ?>
    </ul>
    <div class="clear"></div>
</section>

<section style="width: 100%">
    <button class="detail-more">更多借款详情<i class="iup"></i></button>
    <div class="detail-more-d">
        借款总额：<?php echo $_VArray['orderInfo']['loan_amount'] ?>元<br>
        借款期限：<?php echo $_VArray['orderInfo']['month'] ?>个月（期）<br>
        收还款银行卡：<?php echo $_VArray['orderInfo']['bankcard_no'] ?><br>
        <a style="color:#0085EC " href="<?php echo $_VArray['downUrl'] ?>">查看合同</a><br><br>
        1.请于还款日之前确保还款卡内余额充足<br>
        2.实际还款日按实际放款日计算结果为准；还款日若不存在，以自然月最后一天为准提示；<br>
        3.逾期罚息：按当期未还金额×1%/天，逾期滞纳金：按当期未还金额×1%/天；
        <div style="clear: both" class="t-top-vacancy"></div>
    </div>
</section>

<section class="detail-foot">
    <strong>共计:<span><?php echo isset($_VArray['billRepayAmount'])?$_VArray['billRepayAmount']:0;?></span></strong>
    <?php echo isset($_VArray['submit'])?$_VArray['submit']:'';?>
<!--    <a href="/v/Loan/Repayment">立即还款</a>-->
</section>
</body>
</html>