<?php include Kohana::find_file('views', 'v1/public/head');?>
<script>
    seajs.config({
        vars: {
        }
    });
    seajs.use('js/seajs/check');
</script>
<section class="t-login-nav">
    <div class="t-login-nav-1">
        <?php echo isset($_VArray['title'])?$_VArray['title']:'';?>
    </div>
</section>
<div class="t-top-vacancy"></div>
<section class="cs-section">
    <img class="cs-img" src="/static/images/v1/tijiao.png">
    <div class="cs-div">
        <p >借款申请已提交</p>
        <label>借款金额5,000元</label>
        <label>收款账户工商银行(1234)</label>
    </div>
    <div class="cs-div-n">到账成功</div>
</section>


<!--<button class="submitButton" onclick="submit()">确定</button>-->
<a style="text-align: center;line-height: 2rem;" class="submitButton" href="/v/Loan/Detail">确定</a>
</body>
</html>