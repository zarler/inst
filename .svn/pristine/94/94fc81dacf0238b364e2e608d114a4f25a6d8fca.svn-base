<?php include Kohana::find_file('views', 'v1/public/head');?>
<script src="/static/js/inst.app.js"></script>
<script src="/static/js/plug-in/scrolltext.js"></script>
<script>
    seajs.config({
        vars: {
            seajsVer:'<?php echo isset($_VArray['seajsVer'])?$_VArray['seajsVer']:0;?>',
        }
    });
    seajs.use('js/seajs/homepage');
    var $AppInst = new $.AppInst();
</script>

<style type="text/css">
    .notice{width:387px;margin:20px auto;height:26px;overflow:hidden;background:url() no-repeat;}
    .noticTipTxt{color:#ff7300;height:22px;line-height:22px;overflow:hidden;margin:0 0 0 2.4rem;}
    .noticTipTxt li{height:22px;line-height:22px;}
    .noticTipTxt a{font-size:12px;text-decoration:none;}
    .noticTipTxt a:hover{text-decoration:underline;}
</style>

<section class="t-login-nav">
    <div class="t-login-nav-1">
        <?php echo isset($_VArray['info']['name'])?$_VArray['info']['name']:'';?>
    </div>
</section>
<div class="t-top-vacancy"></div>
<!--<section class="t-notice">-->
<!--    <p>【公告】银行系统升级公告<a class="return_r" href="javascript:;"></a></p>-->
<!--</section>-->


<div class="t-notice" style="height: 1.5rem;padding-top: .6rem;">
    <label style="margin-left: .6rem;line-height:1.2rem;float: left">【公告】</label>
    <ul id="jsfoot01" class="noticTipTxt">
        <li><a href="http://www.17sucai.com/" target="_blank">js无缝滚动制作js文字无缝滚动和js图片无缝滚动</a></li>
        <!-- 	<li><a href="http://www.17sucai.com/" target="_blank">js文字切换特效制作焦点文字带滤镜切换效果</a></li>
            <li><a href="http://www.17sucai.com/" target="_blank">js文字特效制作js文字闪烁与文字变色效果</a></li>
            <li><a href="http://www.17sucai.com/" target="_blank">js文字滚动制作js scroll单排文字滚动向上间隔滚动</a></li> -->
    </ul>
    <span class="return_r"></span>
</div>


<section class="t-homepage">
    <div class="d1">最高借款（元）</div>
    <div class="d2">￥<?php echo $_VArray['info']['max_amount']; ?></div>
    <div class="d1">总额度 ￥<?php echo $_VArray['info']['available_amount']; ?>元</div>
    <a class="d3" href='javascript:$AppInst.WebJump({"type":"web_within", "par":"<?php echo $_VArray['info']['button_url']; ?>"});'><?php echo $_VArray['info']['button_title']; ?></a>
<!--    <a class="d3" href='javascript:$AppInst.WebJump({"type":"web_within", "par":"app://app.inst/User/Login"});'>立1即授权</a>-->
    <br>
    <br>
    <br>
    <?php echo isset($_VArray['info']['sub_info'])?$_VArray['info']['sub_info']:'';  ?>
<!--    <p>您有&nbsp;<strong>2笔</strong>&nbsp;借款&nbsp;<strong>已逾期</strong>&nbsp;</p>-->
<!--    <a class="d3" href='javascript:$AppInst.WebJump({"type":"appJump", "par":"app://app.timecash/Login/Index"});'>进入登录页面</a>-->

    <!--    <a class="d3" href='javascript:$AppInst.WebJump({"type":"web_within", "par":"http://www.baidu.com"});'>立即授权</a>-->
</section>


<script type="text/javascript">
    // 演示一
    var scrollup = new ScrollText("jsfoot01");
    scrollup.LineHeight = 22;        //单排文字滚动的高度
    scrollup.Amount = 0;            //注意:子模块(LineHeight)一定要能整除Amount.
    scrollup.Delay = 20;           //延时
    scrollup.Start();             //文字自动滚动
    scrollup.Direction = "down"; //文字向下滚动

</script>

</body>
</html>