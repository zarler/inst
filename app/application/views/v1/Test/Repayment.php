<?php include Kohana::find_file('views', 'v1/public/head');?>
<script src="/static/js/inst.app.js"></script>
<script>
    seajs.config({
        vars: {
            orderid: '<?php echo isset($_VArray['orderid']) ? $_VArray['orderid'] : "";?>',
            billId: '<?php echo isset($_VArray['billId']) ? $_VArray['billId'] : "";?>',
            seajsVer: '<?php echo isset($_VArray['seajsVer']) ? $_VArray['seajsVer'] : "";?>',
            reqSMS: '<?php echo isset($_VArray['reqSMS']) ? $_VArray['reqSMS'] : "";?>',
            reqUrl: '<?php echo isset($_VArray['reqUrl']) ? $_VArray['reqUrl'] : "";?>',
            jumpUrl: '<?php echo isset($_VArray['jumpUrl']) ? $_VArray['jumpUrl'] : "";?>',
        }
    });
    seajs.use('js/seajs/repayment');
</script>
<section class="t-login-nav">
    <div class="t-login-nav-1">
        <a class="return_i i_public" href="javascript:history.go(-1);"></a><?php echo isset($_VArray['title'])?$_VArray['title']:'';?>
    </div>
</section>
<div class="t-top-vacancy"></div>


<section class="t-homepage" style="background: white">
    <div class="d1">还款金额</div>
    <div class="d2">￥100,000</div>
</section>

<section class="t-repayment-list">
    <p>2018/04/13(第三期)<label>￥990.00</label></p>
    <p>2018/04/13(第四期)<label>￥990.00</label></p>
    <p>2018/04/13(第五期)<label>￥990.00</label></p>
</section>
<div style="height: .6rem"></div>
<section class="t-repayment-list">
    <p>还款银行卡：工商银行1234</label></p>
    <div class="m-bo1px">
        <!-- <p class="t-login-center-1 m-addheight"><input type="text" placeholder="请输入短信验证码" class="t-w240px"> <span class="t-pwd-code m-position">获取验证码</span></p> -->
        <p class="t-h t-code-p"><input style="height: 2.3rem" type="text" placeholder="请输入短信验证码"  name="code"><span class="t-icon-close t-mr t-mt1"></span> <button type="button" class="t-pwd-code">获取验证码</button></p>
    </div>
</section>
<div style="height: 1.2rem"></div>
<!--<button class="submitButton" onclick="submit()">确定</button>-->
<button class="submitButton t-blue-btn">确定</button>

<p class="t-foot-p">如果还款失败，请尝试&nbsp;<strong ONCLICK="$AppInst.WebJump({'type':'phone', 'par':'13643176531'})">联系客服获取帮助</strong></p>

<!--<button class="submitButton" onclick="layerPhone.BombBox('水电费的说法');layerPhone.changeCssBombBox()">确定</button>-->

<!--<div class="bomb-box"><div class="bomb-box-show"><img class="bomb-box-img" src="/static/images/v1/icon_duihao.png"><p>恭喜，您已成功注册</p><button class="submitButton" onclick="layerPhone.BombBox('水电费的说法');layerPhone.changeCssBombBox()">立即去借款</button></div></div>-->


</body>
</html>