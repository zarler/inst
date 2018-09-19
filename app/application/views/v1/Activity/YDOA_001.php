<?php include Kohana::find_file('views', 'v1/public/achead');?>
<link rel="stylesheet" type="text/css" href="/static/Activity/YDOA_001/css/reset.css?11111">
<style type="text/css">
    body{
        background: rgba(125, 202, 250, 1);
    }
</style>
<script>
    seajs.config({
        vars: {
            token: '<?php echo isset($_VArray['token']) ? $_VArray['token'] : "";?>',
            reqSMS: '<?php echo isset($_VArray['reqSMS']) ? $_VArray['reqSMS'] : "";?>',
            reqUrl: '<?php echo isset($_VArray['reqUrl']) ? $_VArray['reqUrl'] : "";?>',
            seajsVer: "<?php echo isset($_VArray['seajsVer']) ? $_VArray['seajsVer'] : "";?>",
            jumpUrl: "<?php echo isset($_VArray['jumpUrl']) ? $_VArray['jumpUrl'] : "";?>",
            channelCode: "<?php echo isset($_VArray['channelCode']) ? $_VArray['channelCode'] : "";?>",
            channelType: "<?php echo isset($_VArray['channelType']) ? $_VArray['channelType'] : "";?>",
        }
    });
    seajs.use('js/seajs/activity/YDOA_001');
</script>
<article>
    <img src="/static/Activity/YDOA_001/images/background1.png" class="img">
    <div class="promotion-box">
        <img src="/static/Activity/YDOA_001/images/register.png" style="width: 50%;margin: 0 auto;display: -webkit-box;"></img>
        <div class="promotion-box-r">
            <p><input type="text" name="mobile" placeholder="请输入手机号"></p>
            <p><input type="text" name="code" style="width: 50%" placeholder="请输入验证码"><button class="t-pwd-code">获取验证码</button></p>
            <p><input type="password" name="passage" placeholder="请输入密码"></p>
            <button class="loan"><STRONG>申请借款</STRONG></button>
        </div>
        <div style="text-align: center;color: white;font-size: .6rem;margin-top: 1.2rem;">广州耀盛网络小额贷款有限公司</div>
    </div>
</article>

<!--<div class="div1"></div>-->
<!--<div class="div2"></div>-->
<!--<div class="div3"></div>-->


<?php if(isset($_VArray['clientType'])){ ?>
    <script type="text/javascript" src="//js.users.51.la/19393049.js"></script>
<?php } ?>
</body>
</html>