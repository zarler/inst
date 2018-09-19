<?php include Kohana::find_file('views', 'v1/public/head');?>
<script src="/static/js/inst.app.js"></script>
<script>
    seajs.config({
        vars: {
            seajsVer:'<?php echo isset($_VArray['seajsVer'])?$_VArray['seajsVer']:"";?>',
            month:'<?php echo isset($_VArray['life_of_loan'])?$_VArray['life_of_loan']:0;?>',
            bankcard_id:'<?php echo isset($_VArray['bankcard_id'])?$_VArray['bankcard_id']:0;?>',
            loan_amount:'<?php echo isset($_VArray['loan_amount'])?$_VArray['loan_amount']:0;?>',
            use_for_id:'<?php echo isset($_VArray['use_for_id'])?$_VArray['use_for_id']:0;?>',
            reqUrl:'<?php echo isset($_VArray['reqUrl'])?$_VArray['reqUrl']:"";?>',
            jumpUrl:'<?php echo isset($_VArray['jumpUrl'])?$_VArray['jumpUrl']:"";?>',
            _token:'<?php echo isset($_VArray['_token'])?$_VArray['_token']:"";?>',
        }
    });
    seajs.use('js/seajs/check');
    var $AppInst = new $.AppInst();
</script>

<!--<div class="div1">1111</div>-->
<!--<div class="div2">222</div>-->
<!--<div class="div3">333</div>-->

<section class="t-login-nav">
    <div class="t-login-nav-1">
        <a class="return_i i_public" href="?#jump=no"></a><?php echo isset($_VArray['title'])?$_VArray['title']:'';?>
    </div>
</section>
<div class="t-top-vacancy"></div>

<section class="t-check-1">
    <p class="">借款人：<?php echo $_VArray['name'] ?></p>
    <p class="">身份证号：<?php echo $_VArray['identity_code'] ?></p>
</section>

<section class="t-check-1 t-margintop-5">
    <p class="">借款金额：<?php echo $_VArray['loan_amount'] ?>元</p>
    <p class="">借款日期：<?php echo $_VArray['life_of_loan'] ?>个月</p>
    <p class=""><label>应还总额：<?php echo $_VArray['total_repay'] ?>元</label></p>
    <p class=""><label>总利息：<?php echo $_VArray['total_interest'] ?>元</label></p>
    <p class=""><label>总管理费：<?php echo $_VArray['total_platform_manage'] ?>元</label></p>
    <p class=""><label>每月应还：<?php echo $_VArray['month_repay'] ?>元</label></p>
</section>


<section class="t-check-1 t-margintop-5">
    <p class=""><label>截止时间：<?php echo $_VArray['start_end_date'] ?></label></p>
    <p class=""><label>首次还款日：<?php echo $_VArray['first_repay_date'] ?></label></p>
    <p class=""><label>还款日：<?php echo $_VArray['repay_date'] ?></label></p>
</section>

<section class="t-check-1 t-margintop-5">
    <p class="">借款用途：<?php echo $_VArray['use_for']['name'] ?></p>
    <p class="">收款银行卡：<?php echo $_VArray['card_loan'] ?></p>
</section>
<?php echo $_VArray['foot_html'] ?>

<section class="contractabroad">
    <label>本人已经阅读并同意</label>
    <a href="/v1/v/Protocol/content?num=2&goback=1">《借款服务协议》</a>
    <a href="/v1/v/Protocol/content?num=5&goback=1">《借款协议》</a>
</section>
<button class="submitButton t-blue-btn" onclick="submit()">确定</button>
</body>
</html>