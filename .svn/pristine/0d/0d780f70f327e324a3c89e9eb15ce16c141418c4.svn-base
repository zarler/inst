(function($){
    $(function() {
        var times = 60;
        $('.t-pwd-code').click(function () {
            commonUtil.lockup();
            if(commonUtil.isnull(seajs.data.vars.orderid,'数据异常！')==false) {
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
                data:{order_id:seajs.data.vars.orderid},
                dataType:'json',
                async: false,  //同步发送请求
                beforeSend:function(xhr){
                    xhr.setRequestHeader('app-info', seajs.data.vars.seajsVer);
                },
                success:function(result){
                    commonUtil.unlock();
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
        $('.submitButton').click(function () {
            commonUtil.lockup();
            if(commonUtil.isnull(seajs.data.vars.orderid,'数据异常！')==false) {
                commonUtil.unlock();
                return false;
            }
            if(commonUtil.isnull(seajs.data.vars.billId,'数据异常！')==false) {
                commonUtil.unlock();
                return false;
            }

            var code = $("input[name='code']").val();
            if(commonUtil.authcode(code)==false) {
                commonUtil.unlock();
                return false;
            }
            var post_data = "bill_id="+seajs.data.vars.billId+'&order_id='+seajs.data.vars.orderid+'&verify_code='+code;
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
                    //$('.test').text(JSON.stringify(result));
                    if(result.code == 1000){
                        location.href = seajs.data.vars.jumpUrl;
                    }else{
                        commonUtil.unlock();
                        layerPhone.BombBox(result.message);layerPhone.changeCssBombBox();
                    }
                },
                error:function(){
                    commonUtil.unlock();
                    layerPhone.BombBox('表单校验失败');layerPhone.changeCssBombBox();
                }
            });
        })
    })


})(jQuery);