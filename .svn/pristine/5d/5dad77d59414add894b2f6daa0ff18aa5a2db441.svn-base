<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 2017/2/28
 * Time: 上午1:28
 *
 * APP端H5、WEBVIEW 助手
 *
 *
 */

class Lib_Helper_AppHtml
{
    protected $app_id;
    protected static $foot_html = <<<EOF
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv='Content-Type'>
    <meta content='text/html; charset=utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1,target-densitydpi=medium-dpi">
    <style type='text/css'>
        .t-login-center{
            background: white;
            margin-bottom: 1rem;
        }
        .t-loan2{ color: #868686; font-size:12px;padding: 2%;}
        .t-loan2 .dt1{
            position: absolute;
            width: .3rem;
            height: .3rem;
            background: #ff8470;
            border-radius: .3rem;
            margin-top: .3rem;
        }
        .t-loan2 .dt2{
            position: absolute;
            width: .3rem;
            height: .3rem;
            background: #ff8470;
            border-radius: .3rem;
            margin-top: .5rem;
        }
        .t-loan2 dd{ margin-left: .6rem; line-height: 1rem;margin-bottom:.03rem;}
        .t-loan2 p{ margin: 2px auto}
        .button_p{
            width: 30%;
            background: #ff8470;
            border-radius: .3rem;
            font-size:12px;
            color: white;
            margin-left: 5%;
            margin-right: 5%;
            border:none;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <section class='t-login-center'>
        <dl class='t-loan2'>
            <dt class='dt1'></dt>
            <dd>请于还款日之前确保还款卡内余额充足</dd>
            <dt class='dt2'></dt>
            <dd>
                <p>若未按期还款将影响您在的信用评级,并会造成如下影响：</p>
                <p>1.逾期罚息：按未还金额×3‰/天</p>
                <p>2.逾期滞纳金：按未还金额×1‰/天</p>
                <p>3.征信黑名单：将影响您在互联网征信共享组织的信用评级</p>
            </dd>
        </dl>
    </section>
    <div id="wxFllow">
        <p style="margin: 0;text-align: center;font-size: 14px;letter-spacing: .06rem;">关注快金微信</p>
        <!-- 头信息end -->
        <img style="margin: 0 auto;display: block;width: 35%;" src='http://a-cdn.timecash.cn/banner/top-banner/2weima.png'>
        <p style="margin-top: .4rem;text-align: center;font-size: .35rem;color: gray;">及时获取最新贷款信息</p>
        <p style="margin-top: .4rem;text-align: center;font-size: .35rem;color: gray;">一键关注快金微信公众号</p>
        <div style="text-align: center">
            <button onclick="copyWxNum();" class="button_p">复制微信号</button>
            <button onclick="savePic();" class="button_p">保存二维码</button>
        </div>
    </div>
    <div style="height: 20px"></div>
    <script>
        var u = navigator.userAgent;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        var wx = u.toLowerCase();
        if(wx.match(/MicroMessenger/i)=="micromessenger") {
            document.getElementById("wxFllow").style.display="none";
        }
        function copyWxNum(){
            if(isAndroid){
                window.android.JsAppInteraction('copyWxNum','快金');
            }
            if(isiOS){
                JsAppInteraction('copyWxNum','快金');
            }
        }
        function goActivity(href){
            if(isAndroid){
                window.android.JsAppInteraction('web_within', href);
            }
            if(isiOS){
                JsAppInteraction('web_within', href);
            }
        }
        function savePic(){
            if(isAndroid){
                window.android.JsAppInteraction('savePicture','http://a-cdn.timecash.cn/banner/top-banner/2weima.png');
            }
            if(isiOS){
                JsAppInteraction('savePicture','http://a-cdn.timecash.cn/banner/top-banner/2weima.png');
            }
        }
    </script>
</body>
</html>
EOF;

    public function __construct() {


    }


    public function app_id($app_id){
        $this->app_id = $app_id;
        return $this;
    }

    /** APP页面中的WEBVIEW
     * 订单详情(/Order/Detail) 当前订单(/Order/Current)  授信列表(/FastLoan_Credit/List|/FullPreAuthLoan_Credit/List|/PreAuthLoan_Credit/List)
     * @param array $array
     * @return string
     */
    public function foot_html($array=[]){
        return self::$foot_html;
    }






}