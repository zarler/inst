(function($){
    $(function() {
       //提交按钮
        $('.submit').click(function () {
            commonUtil.lockup();
            var companyname = $("input[name='companyname']").val();
            if(commonUtil.cname(companyname,'company')!=true) {
                commonUtil.unlock();
                return false;
            }
            var province = $("#province1").val();
            var city = $("#city1").val();
            var district = $("#district1").val();
            if(commonUtil.isnull(province,'省不能为空')!=true||commonUtil.isnull(city,'市不能为空')!=true||commonUtil.isnull(district,'区域不能为空')!=true){
                commonUtil.unlock();
                return false;
            }

            var companydetail = $("input[name='companydetail']").val();
            if(commonUtil.details_isnull(companydetail,'公司详情地址格式错误','公司详情地址不能为空')!=true) {
                commonUtil.unlock();
                return false;
            }

            var companytell = $("input[name='companytell']").val();
            if(commonUtil.tell(companytell,2)!=true) {
                commonUtil.unlock();
                return false;
            }
            var post_data = "company_name="+companyname+'&company_province='+province+'&company_city='+city+'&company_county='+district+'&company_address='+companydetail+'&company_tel='+companytell;
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