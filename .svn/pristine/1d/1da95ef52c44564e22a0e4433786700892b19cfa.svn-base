(function($){
    $(function() {
       //提交按钮
        $('.submit').click(function () {
            commonUtil.lockup();
            //紧急联系人
            var conname1 = $("input[name='conname1']").val();
            var contact1 = $("select[name='contact1']").val();
            var ccomtell1 = $("input[name='ccomtell1']").val();
            var conname2 = $("input[name='conname2']").val();
            var contact2 = $("select[name='contact2']").val();
            var ccomtell2 = $("input[name='ccomtell2']").val();
            if(commonUtil.isnull(conname1)!=true ||commonUtil.isnull(conname2)!=true) {
                commonUtil.unlock();
                layerPhone.BombBox('联系人姓名不能为空');layerPhone.changeCssBombBox();
                return false;
            }
            if(commonUtil.isnull(contact1)!=true ||commonUtil.isnull(contact2)!=true) {
                commonUtil.unlock();
                layerPhone.BombBox('关系选择不能为空');layerPhone.changeCssBombBox();
                return false;
            }
            if(commonUtil.phone(ccomtell1)!=true ||commonUtil.phone(ccomtell2)!=true) {
                commonUtil.unlock();
                // layerPhone.BombBox('关系选择不能为空');layerPhone.changeCssBombBox();
                return false;
            }
            var post_data = "";
            post_data = {"contact": [{"name": conname1,"mobile": ccomtell1,"relation": contact1}, {"name": conname2,"mobile": ccomtell2,"relation": contact2}]};
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
                    // commonUtil.tips("表单校验失败");
                }
            });

        });
    })


})(jQuery);
