<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>这是一个html</title>
</head>

<body>

这是一个有用户信息的html a
<br>
<br>
<br>
<br>

<a href="http://test.app.inst.timecash.cn/v1/Info/View2">跳转到有用户信息的html</a>
<br>
<br>
<br>
<br>
<button>button测试ajax</button>
<?php echo $_SERVER['HTTP_APP_INFO'];?>
<br>
<br>
<br>
<br>


<section>1111111</section>





<script type="text/javascript" src="http://libs.baidu.com/jquery/1.9.0/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    var info = '<?php echo $_SERVER['HTTP_APP_INFO'];?>';
    $("button").click(function () {
        $.ajax({
            url:'http://test.app.inst.timecash.cn/v1/Info/Index',
            type:'POST',
            data:{one:11111},
            dataType:'json',
            async: true,  //同步发送请求
            beforeSend: function(xhr){
                xhr.setRequestHeader('app-info', info);
            },//这里设置header

            success:function(result){
                if(result.code==1000){
                    $('section').text('<br> ajax请求接口信息：' + result.code);
                }else{
                    alert('error1' + ' ' + result.code + ' ' + result.message);
                }
            },
            error:function(){
                alert('error');
            }
        });
    });


    $.post("http://app.test/v1/Info/Index",{},function(result){
        $('body').append('<br> 接口信息：' + result.user.mobile);
    });


});

</script>
</body>
</html>