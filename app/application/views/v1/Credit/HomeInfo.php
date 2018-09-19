<?php include Kohana::find_file('views', 'v1/public/head');?>
<script>
    seajs.config({
        vars: {
            reqUrl: '<?php echo isset($_VArray['reqUrl']) ? $_VArray['reqUrl'] : "";?>',
            seajsVer: '<?php echo isset($_VArray['seajsVer']) ? $_VArray['seajsVer'] : "";?>',
            jumpUrl: '<?php echo isset($_VArray['jumpUrl']) ? $_VArray['jumpUrl'] : "";?>',
        }
    });
    seajs.use('js/seajs/homeinfo');
</script>
<script src="/static/js/larea/distpicker.data.js"></script>
<script src="/static/js/larea/distpicker.js"></script>

<section class="t-login-nav">
    <div class="t-login-nav-1">
        <a class="return_i i_public" href="?#jump=no"></a><?php echo isset($_VArray['title'])?$_VArray['title']:'';?>
    </div>
</section>
<div class="t-top-vacancy"></div>
<p class="tip-p">请输入您的居住信息</p>

<section class="t-credit-w contact1">
    <p class="t-credit-input border-bottom" data-toggle="distpicker">
        <span style="z-index: 1; height: 26px;float: left;margin-right:10px;color: #222222">现居住所在地</span>
        <select class="sp" style="z-index: 1; height: 26px;max-width: 70px;background: #9F9F9F;" id="province1"><option value="" data-code="" disabled>—— 省 ——</option><option value="北京市" data-code="110000" selected="">北京市</option><option value="天津市" data-code="120000">天津市</option><option value="河北省" data-code="130000">河北省</option><option value="山西省" data-code="140000">山西省</option><option value="内蒙古自治区" data-code="150000">内蒙古自治区</option><option value="辽宁省" data-code="210000">辽宁省</option><option value="吉林省" data-code="220000">吉林省</option><option value="黑龙江省" data-code="230000">黑龙江省</option><option value="上海市" data-code="310000">上海市</option><option value="江苏省" data-code="320000">江苏省</option><option value="浙江省" data-code="330000">浙江省</option><option value="安徽省" data-code="340000">安徽省</option><option value="福建省" data-code="350000">福建省</option><option value="江西省" data-code="360000">江西省</option><option value="山东省" data-code="370000">山东省</option><option value="河南省" data-code="410000">河南省</option><option value="湖北省" data-code="420000">湖北省</option><option value="湖南省" data-code="430000">湖南省</option><option value="广东省" data-code="440000">广东省</option><option value="广西壮族自治区" data-code="450000">广西壮族自治区</option><option value="海南省" data-code="460000">海南省</option><option value="重庆市" data-code="500000">重庆市</option><option value="四川省" data-code="510000">四川省</option><option value="贵州省" data-code="520000">贵州省</option><option value="云南省" data-code="530000">云南省</option><option value="西藏自治区" data-code="540000">西藏自治区</option><option value="陕西省" data-code="610000">陕西省</option><option value="甘肃省" data-code="620000">甘肃省</option><option value="青海省" data-code="630000">青海省</option><option value="宁夏回族自治区" data-code="640000">宁夏回族自治区</option><option value="新疆维吾尔自治区" data-code="650000">新疆维吾尔自治区</option><option value="台湾省" data-code="710000">台湾省</option><option value="香港特别行政区" data-code="810000">香港特别行政区</option><option value="澳门特别行政区" data-code="820000">澳门特别行政区</option></select>
        <select class="sp" style="z-index: 1; height: 26px;max-width: 70px;background: #9F9F9F;" id="city1"><option value="" data-code="" disabled>—— 市 ——</option><option value="北京市市辖区" data-code="110100" selected="">北京市市辖区</option></select>
        <select class="sp" style="z-index: 1;height: 26px;max-width: 70px;background: #9F9F9F;" id="district1"><option value="" data-code="" disabled>—— 区 ——</option><option value="东城区" data-code="110101" selected="">东城区</option><option value="西城区" data-code="110102">西城区</option><option value="朝阳区" data-code="110105">朝阳区</option><option value="丰台区" data-code="110106">丰台区</option><option value="石景山区" data-code="110107">石景山区</option><option value="海淀区" data-code="110108">海淀区</option><option value="门头沟区" data-code="110109">门头沟区</option><option value="房山区" data-code="110111">房山区</option><option value="通州区" data-code="110112">通州区</option><option value="顺义区" data-code="110113">顺义区</option><option value="昌平区" data-code="110114">昌平区</option><option value="大兴区" data-code="110115">大兴区</option><option value="怀柔区" data-code="110116">怀柔区</option><option value="平谷区" data-code="110117">平谷区</option><option value="密云区" data-code="110118">密云区</option><option value="延庆区" data-code="110119">延庆区</option></select>
    </p>
    <p class="t-credit-input">
        <?php echo Form::input('homedetail','',array('class'=>'form-control','placeholder'=>'请输入详细居住地址'));?>
</section>


<div class="test"></div>


<div style="height: 1.2rem"></div>
<button class="submitButton t-blue-btn submit">提交</button>

</body>
</html>