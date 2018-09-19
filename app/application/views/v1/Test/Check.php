<?php include Kohana::find_file('views', 'v1/public/head');?>
<link rel="stylesheet" type="text/css" href="/static/css/bomb.css" />
<script src="/static/js/bomb.js"></script>
<script src="/static/js/inst.app.js"></script>
<script>
    seajs.config({

    });
    seajs.use('js/seajs/check');
//    bomb.MsgButton({
//        status:'yes',
//        msg:"恭喜，您已成功注册",
//        msgButton:"立即去借款"
//    });
    var $AppInst = new $.AppInst();

</script>




<section class="t-login-nav">
    <div class="t-login-nav-1">
        <a class="return_i i_public" href="?#jump=no"></a><?php echo isset($_VArray['title'])?$_VArray['title']:'';?>
    </div>
</section>
<div class="t-top-vacancy"></div>


<section class="t-check-1">
    <p class="">借款人：刘金生</p>
    <p class="">身份证号：1309821******238</p>
</section>

<section class="t-check-1 t-margintop-5">
    <p class="">借款金额：2000.00</p>
    <p class="">借款日期：6个月</p>
    <p class=""><label>应还总额：2600.00</label></p>
    <p class=""><label>总利息：600.00</label></p>
    <p class=""><label>每月应还：150.00</label></p>
</section>


<section class="t-check-1 t-margintop-5">
    <p class=""><label>截止时间：2108/02/03-2108/02/03</label></p>
    <p class=""><label>首次还款日：2108/02/03</label></p>
    <p class=""><label>还款日：16日</label></p>
</section>

<section class="t-check-1 t-margintop-5">
    <p class="">借款用途：购物</p>
    <p class="">收款银行卡：工商银行（12345）</p>
    <p class="">还款银行卡：工商银行（12345）</p>
</section>

<!--<section class="t-check-1 t-margintop-5">-->
<!--    --><?php //echo $_VArray['foot_html'] ?>
<!--</section>-->

<section class="t-check-1 t-margintop-5">
    <p class="">1.请于还款日之前确保还款卡内余额充足</p>
    <p class="">2.请于还款日之前确保还款卡内余额充足范德萨范德萨发</p>
    <p class="">3.请于还款日之前确保还款卡内余额充足范德萨范德萨发，冯绍峰的爽肤水发师傅师傅</p>
</section>

<section class="contractabroad">
    <label>本人已经阅读并同意</label>
    <a href="">《合同1》</a>
    <a href="">《合同2》</a>
</section>

<div class="abc"></div>

<!--<button class="submitButton" onclick="submit()">确定</button>-->
<a style="text-align: center;line-height: 2rem;" class="submitButton" href="/v/Loan/CheckStatic">确定</a>
<!--<button class="submitButton" onclick="layerPhone.BombBox('水电费的说法');layerPhone.changeCssBombBox()">确定</button>-->

<!--<div class="bomb-box"><div class="bomb-box-show"><img class="bomb-box-img" src="/static/images/v1/icon_duihao.png"><p>恭喜，您已成功注册</p><button class="submitButton" onclick="layerPhone.BombBox('水电费的说法');layerPhone.changeCssBombBox()">立即去借款</button></div></div>-->


</body>
</html>