(function($){
    $(function() {



        var times = 60;
        $('.t-pwd-code').click(function () {
            commonUtil.lockup();
            var mobile = $("input[name='mobile']").val();
            if(commonUtil.phone(mobile)==false) {
                commonUtil.unlock();
                return false;
            }
            timer = setInterval(function() {
                times--;
                if(times > 0) {
                    $('.t-pwd-code').text(times +'秒后重发');
                    $('.t-pwd-code').attr('disabled','disabled');
                } else {
                    times = 60;
                    $('.t-pwd-code').text('重发验证码');
                    $('.t-pwd-code').removeAttr('disabled');
                    clearInterval(timer);
                }
            }, 1000);

            $.ajax({
                url:seajs.data.vars.reqSMS,  //<?php echo URL::site('Functions/repayCode');?>
                type:'POST',
                data:{mobile:mobile},
                dataType:'json',
                async: false,  //同步发送请求
                beforeSend:function(xhr){
                    // console.log(seajs.data.vars.seajsVer);
                    xhr.setRequestHeader('app-info', seajs.data.vars.seajsVer);
                },
                success:function(result){
                    commonUtil.unlock();
                    console.log(result);
                    if(result.code==1000){
                        return true;
                    }else{
                        if(result.code==='10023'){
                            layerPhone.BombBox('操作过于频繁');layerPhone.changeCssBombBox();
                        }else{
                            layerPhone.BombBox(result.message);layerPhone.changeCssBombBox();
                        }
                        times = 60;
                        clearInterval(timer);
                        $('.t-pwd-code').text('重发验证码');
                        $('.t-pwd-code').removeAttr('disabled');
                        return false;
                    }
                },
                error:function(){
                    commonUtil.unlock();
                    times = 60;
                    layerPhone.BombBox('手机校验失败');layerPhone.changeCssBombBox();
                    $('.t-pwd-code').text('重发验证码');
                    $('.t-pwd-code').removeAttr('disabled');
                    return false;
                }
            });
        });


        //提交按钮
        $('.loan').click(function () {

            commonUtil.lockup();
            var mobile = $("input[name='mobile']").val();
            if(commonUtil.phone(mobile)==false) {
                commonUtil.unlock();
                return false;
            }
            var code = $("input[name='code']").val();
            if(commonUtil.authcode(code)==false) {
                commonUtil.unlock();
                return false;
            }
            var passage = $("input[name='passage']").val();
            if(commonUtil.isnull(passage,'密码不能为空')==false){
                commonUtil.unlock();
                return false;
            }
            var post_data = "mobile="+mobile+'&password='+passage+'&verify_code='+code+'&channel_code='+seajs.data.vars.channelCode+'&channel_type='+seajs.data.vars.channelType;
            $.ajax({
                url:seajs.data.vars.reqUrl,
                type:'POST',
                data:post_data,
                dataType:'json',
                async: false,  //同步发送请求t-mask
                beforeSend:function(xhr){
                    xhr.setRequestHeader('app-info', seajs.data.vars.seajsVer);
                },
                success:function(result){
                    // console.log(result);
                    //$('.test').text(JSON.stringify(result));
                    if(result.code == 1000){
                        timedownLoad = 3;
                        layerPhone.Inquiryok('注册成功！','下载APP('+timedownLoad+'秒)',seajs.data.vars.jumpUrl);
                        layerPhone.changeCssInquiry();
                        timerdownLoad = setInterval(function() {
                            timedownLoad--;
                            if(timedownLoad > 0) {
                                $('.layui-m-layerbtn span').text('下载APP('+timedownLoad+"秒)");
                                $('.t-pwd-code').attr('disabled','disabled');
                            } else {
                                $('.layui-m-layerbtn span').text('下载APP('+timedownLoad+"秒)");
                                timedownLoad = 3;
                                location.href = seajs.data.vars.jumpUrl;
                                clearInterval(timerdownLoad);
                            }
                        }, 1000);
                    }else{
                        commonUtil.unlock();
                        layerPhone.BombBox(result.message);layerPhone.changeCssBombBox();
                    }
                },
                // error:function(XMLHttpRequest, textStatus, errorThrown){
                error:function(){
                    // $('.div1').text(JSON.stringify(XMLHttpRequest));
                    // $('.div2').text(XMLHttpRequest.readyState);
                    // $('.div3').text(textStatus);
                    commonUtil.unlock();
                    layerPhone.BombBox('表单校验失败');layerPhone.changeCssBombBox();
                }
            });
        })


    })
})(jQuery);
