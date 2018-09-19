(function($){
    $(function() {
       $('.canClick').click(function () {
           $('.listatic-ok').addClass('listatic-no');
           $('.listatic-ok').removeClass('listatic-ok');
           var obj = $(this).find('.listatic');
           obj.removeClass('listatic-no');
           obj.addClass('listatic-ok');
           seajs.data.vars.billId = obj.data("code");
           console.log(seajs.data.vars.orderId);
           console.log(seajs.data.vars.billId);
           $('.detail-foot span').text($(this).find('#money').text());

       });
        $('.submit').click(function () {
            commonUtil.lockup();

            if(commonUtil.isnull(seajs.data.vars.orderId)==false){
                commonUtil.unlock();
                layerPhone.BombBox('参数错误');layerPhone.changeCssBombBox();
                return false;
            }
            if(commonUtil.isnull(seajs.data.vars.billId)==false){
                commonUtil.unlock();
                layerPhone.BombBox('暂无可还款分期');layerPhone.changeCssBombBox();
                return false;
            }
            // console.log(seajs.data.vars.jumpUrl+'?orderId='+seajs.data.vars.orderId+'&billId='+seajs.data.vars.billId);
            location.href = seajs.data.vars.jumpUrl+'?orderId='+seajs.data.vars.orderId+'&billId='+seajs.data.vars.billId;
            // layerPhone.BombBox('参数错误');layerPhone.changeCssBombBox();
        })



    $('.detail-more').click(function () {
        $('.detail-more-d').slideToggle();
        if($(".detail-more i").hasClass("iup")){
            $('.iup').addClass('idown');
            $('.iup').removeClass('iup');
        }else{
            $('.idown').addClass('iup');
            $('.idown').removeClass('idown');
        }
    });
    })
})(jQuery);
