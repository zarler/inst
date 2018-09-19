(function($){
    $(function() {
       //提交按钮

    })


})(jQuery);
function submit() {
    commonUtil.lockup();
    if(commonUtil.isnull(seajs.data.vars.month)==false||commonUtil.isnull(seajs.data.vars.bankcard_id)==false||commonUtil.isnull(seajs.data.vars.loan_amount)==false||commonUtil.isnull(seajs.data.vars.use_for_id)==false){
        commonUtil.unlock();
        layerPhone.BombBox('参数错误！');layerPhone.changeCssBombBox();
        return false;
    }
    $.ajax({
        url:seajs.data.vars.reqUrl,
        type:'POST',
        data:{month:seajs.data.vars.month,bankcard_id:seajs.data.vars.bankcard_id,loan_amount:seajs.data.vars.loan_amount,use_for_id:seajs.data.vars.use_for_id,_token:seajs.data.vars._token},
        dataType:'json',
        async: true,  //同步发送请求
        beforeSend: function(xhr){
            xhr.setRequestHeader('app-info', seajs.data.vars.seajsVer);
            // response.addHeader("Set-Cookie","token=" + seajs.data.vars.token);
        },//这里设置header
        success:function(result){
            if(result.code==1000){
                location.href = seajs.data.vars.jumpUrl+'?orderId='+result.result.order_id;
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
            layerPhone.BombBox('请求错误！');layerPhone.changeCssBombBox();
        }
    });
}