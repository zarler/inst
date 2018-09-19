<?php include Kohana::find_file('views', 'v1/public/head');?>
<script src="/static/js/inst.app.js"></script>
<script>
    seajs.config({
        vars: {
        }
    });
    seajs.use('js/seajs/detail');
    var $AppInst = new $.AppInst();
</script>
<section class="t-login-nav">
    <div class="t-login-nav-1"
    <label><?php echo isset($_VArray['title'])?$_VArray['title']:'';?></label>
    <a class="returnRight" href="javascript:alert(11111);" >还清所有</a>
    </div>
</section>
<div class="t-top-vacancy"></div>
<section class="detail-static">
    <p style="padding-top: 1.7rem;font-size: .8rem;color: red;">已逾期</p>
<!--    <p style="padding-top:1rem;font-size: .8rem;color: red;">已逾期 <br><label style="margin-top: .3rem;display: block">您已逾期，逾期会产生罚息与违约金，请尽快还款</label></p>-->
<!--    <p style="color: rgba(128, 128, 128, 0.78);">您已逾期，逾期会产生罚息与违约金，请尽快还款</p>-->
</section>

<section class="detail-plan">
    <p >还款计划<strong style="font-size: .6rem;color: #E23200;margin-left: .6rem;height: 1rem;font-weight: 100">实际还款日按实际放款日计算结果为准</strong></p>
    <ul  style="margin: .5rem;">
        <li class="canClick t-fontcolor-red"><span><i class="listatic  listatic-ok"></i><label>2018/02/13</label></span><span>已逾期</span><span>￥983.33</span></li>
        <li class="canClick t-fontcolor-blue"><span><i class="listatic listatic-no"></i><label>2018/02/13</label></span><span>待还款</span><span>￥983.33</span> <div class="detail-borderbottom"></div></li>
        <li><span><i class="listatic"></i><label>2018/02/13</label></span><span>已还清</span><span>￥983.33</span> <div class="detail-borderbottom"></div></li>
    </ul>
    <div class="clear"></div>
</section>

<section style="width: 100%">
    <button class="detail-more">更多借款详情<i class="iup"></i></button>
    <div class="detail-more-d">
        借款总额：5000.00元<br>
        借款期限：6个月（期）<br>
        收还款银行卡：工商银行（1010）<br>
        <a href="<?php echo $_VArray['downUrl'] ?>">查看合同</a><br><br>
        1.请于还款日之前确保还款卡内余额充足<br>
        2.请于还款日之前确保还款卡内余额充足范德萨范德萨发<br>
        3.请于还款日之前确保还款卡内余额充足范德萨范德萨发，冯绍峰的爽肤水发师傅师傅
        <div style="clear: both" class="t-top-vacancy"></div>
    </div>
</section>

<section class="detail-foot">
    <strong>共计:￥990.00</strong>
<!--    <button >立即还款</button>-->
    <a href="/v/Loan/Repayment">立即还款</a>
</section>


<!--<button class="submitButton" onclick="submit()">确定</button>-->
</body>
</html>