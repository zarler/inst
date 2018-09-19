<?php include Kohana::find_file('views', 'v1/public/head');?>
<script src="/static/js/inst.app.js"></script>
<script>
    seajs.config({
        vars: {
            seajsVer:'<?php echo isset($_VArray['seajsVer'])?$_VArray['seajsVer']:0;?>',
        }
    });
    var $AppInst = new $.AppInst();

</script>



<!--<script src="http://libs.baidu.com/jquery/1.9.0/jquery.min.js"></script>-->

<br>
<br>
<br>
<a href="/v/Index1/Index">11111111111111111</a>

<br>
<br>
<br>
<br>
<br>
<br>

<button onclick="reqest()">接口请求</button>

<br>
<br>
<br>
<br>
<br>
<br>
<button onclick='$AppInst.WebJump({"type":"web_within", "par":"http://www.baidu.com"});'>内跳</button>
<br>
<br>
<br>
<br>
<br>
<br>

<button onclick='$AppInst.WebJump({"type":"web_abroad", "par":"http://www.baidu.com"});'>外跳</button>

<br>
<br>
<br>
<br>
<br>
<br>
<button onclick='$AppInst.WebJump({"type":"savePicture", "par":"http://test33.m.timecash.cn/static/images/v2/activity/2weima.png"});'>保存图片</button>


<br>
<br>
<br>
<br>
<br>
<br>
<button onclick='$AppInst.WebJump({"type":"copyWxNum", "par":"耀盛小额贷款"});'>保存微信号</button>

<br>
<br>
<br>
<br>
<br>
<br>
<button onclick='callbackPhone("耀盛小额贷款");'>回调测试</button>