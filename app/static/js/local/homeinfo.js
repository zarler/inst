(function($){
    $(function() {
        //提交按钮
        $('.submit').click(function () {
            commonUtil.lockup();
            var province = $("#province1").val();
            var city = $("#city1").val();
            var district = $("#district1").val();
            if(commonUtil.isnull(province,'省不能为空')!=true||commonUtil.isnull(city,'市不能为空')!=true||commonUtil.isnull(district,'区域不能为空')!=true){
                commonUtil.unlock();
                return false;
            }
            var homedetail = $("input[name='homedetail']").val();
            if(commonUtil.details_isnull(homedetail,'详细居住地址格式错误','详细居住地址不能为空')!=true) {
                commonUtil.unlock();
                return false;
            }
            var post_data = "home_province="+province+'&home_city='+city+'&home_county='+district+'&home_address='+homedetail;
            // var post_data = "home_province="+1111111+'&home_city='+city+'&home_county='+district+'&home_address='+homedetail;
            $.ajax({
                url:seajs.data.vars.reqUrl+"/?abc=11",
                type:'POST',
                data:post_data,
                dataType:'json',
                async: false,  //同步发送请求t-mask
                beforeSend:function(xhr){
                    xhr.setRequestHeader('app-info', seajs.data.vars.seajsVer);
                },
                success:function(result){
                    // $('.test').text(JSON.stringify(result));
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