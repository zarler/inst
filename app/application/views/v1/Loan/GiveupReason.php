<?php include Kohana::find_file('views', 'v1/public/head');?>
<style type="text/css">
    .giveupreason_p{
        font-size: .7rem;
        color: #666666;
        margin: .7rem 3%;
    }
    .giveupreason_ul{
        font-size: .81rem;
    }
    .giveupreason_ul li{
        float: left;
        font-size: .81rem;
        width: 29%;
        text-align: center;
        height: 2.62rem;
        line-height: 2.62rem;
        background: white;
        margin-left: 3%;
        margin-bottom: 1rem;
        border: 1px solid white;
    }
    .giveupreason_ul .on{
        border: 1px solid #85AAFA;
    }
    textarea{
        font-size: .81rem;
        background: white;
        margin: 0 3%;
        width: 90%;
        height: 9.91rem;
        padding: .6rem 2%;
        margin-bottom: 2rem;
    }
    textarea::-webkit-input-placeholder {
        font-size: .81rem;
    }
    textarea::-moz-placeholder {
        font-size: .81rem;
    }
</style>
<script>
    $(document).ready(function(){
        $('.giveupreason_ul li').click(function (){
            if($(this).hasClass('on')){
                $(this).removeClass('on');
            }else{
                $(this).addClass('on');
            }
        });
    });

</script>


<section class="t-login-nav">
    <div class="t-login-nav-1">
        <a class="return_i i_public" href="?#jump=no"></a><?php echo isset($_VArray['title'])?$_VArray['title']:'';?>
    </div>
</section>
<div class="t-top-vacancy"></div>

<p class="giveupreason_p">放弃原因(可多选)</p>
<ul class="giveupreason_ul">
    <li>额度太低</li>
    <li>费用太高</li>
    <li>不想借了</li>
    <li>体验太差</li>
    <li>批复太慢</li>
    <li>其他原因</li>
</ul>
<p class="giveupreason_p">意见建议：</p>
<textarea placeholder="请留下您宝贵的意见，我们会更加努力。"></textarea>


<!--<button class="submitButton" onclick="submit()">确定</button>-->
<a style="text-align: center;line-height: 2rem;" class="submitButton t-blue-btn" href="<?php echo isset($_VArray['jumpUrl'])?$_VArray['jumpUrl']:"#";?>">确定</a>
</body>
</html>