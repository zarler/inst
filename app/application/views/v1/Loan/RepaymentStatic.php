<?php include Kohana::find_file('views', 'v1/public/head');?>
<section class="t-login-nav">
    <div class="t-login-nav-1">
        <?php echo isset($_VArray['title'])?$_VArray['title']:'';?>
    </div>
</section>
<div class="t-top-vacancy"></div>
<section class="cs-section">
    <img class="cs-img" src="/static/images/v1/tijiao.png">
    <div class="cs-div">
        <p >还款申请已提交</p>
        <label style="margin-top: .3rem;">稍后可查看还款结果</label>
    </div>
    <div class="cs-div-n">还款成功</div>
</section>
<!--<button class="submitButton" onclick="submit()">确定</button>-->
<a style="text-align: center;line-height: 2rem;" class="submitButton t-blue-btn" href="<?php echo isset($_VArray['jumpUrl'])?$_VArray['jumpUrl']:"#";?>">确定</a>
</body>
</html>