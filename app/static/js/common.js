
    var formarr = {
        "company":{10001:"公司名称不能为空", 10002:"请输入有效的公司名称"},
        "companydetail":{10001:"公司地址不能为空", 10002:"请输入有效的公司地址"},
        "family":{10001:"家庭住址不能为空", 10002:"请输入有效的家庭住址"},
        "contact":{10001:"名称不能为空",10002:"名称格式不对", 10003:"手机号不能为空",10004:"手机号格式不对"},
        "contact_people":{'parent':"父母",'brother':"兄弟姐妹",'spouse':"配偶",'children':"子女",'colleague':"同事",'classmate':"同学",'friend':"朋友"},
        "zname":{10001:"姓名不能为空",10002:"请输入有效的姓名"},
    };
    function prompt_hide(){
        $('.t-mask').hide();
        $('#t-bomb_box_prompt').hide();
    }
    function prompt_show(){
        $('.t-mask').show();
        $('#t-bomb_box_prompt').show();
    }

    // layer
    var layerPhone={
        BombBox:function (content) {
            layer.open({
                content: content
                ,skin: "msg"
                ,time: 2 //2秒后自动关闭
            });
        },
        changeCssBombBox:function () {
            $('.layui-m-layermain .layui-m-layersection').css({'vertical-align':'bottom'});
            $('.layui-m-layer-msg').css({'margin':'.3rem auto .5rem auto'});
            $('.layui-m-layercont').css({'text-align':'center','font-size':'.77rem','padding':'.42rem 1.2rem',"height":'1.4rem'});
        },
        //询问框
        Inquiry:function (msg,yes,no,url) {
            layer.open({
                content: msg
                ,btn: [yes, no]
                ,yes: function(index){
                    // location.reload();
                    location.href = url;
                    layer.close(index);
                }
            });
        },
        //询问框
        Inquiryok:function (msg,yes,url) {
            layer.open({
                content: msg
                ,btn: [yes]
                ,yes: function(index){
                    // location.reload();
                    location.href = url;
                }
            });
        },
        changeCssInquiry:function () {
            $('.layui-m-layerchild').css({'max-width':'10rem'});
            $('.layui-m-layercont').css({'text-align':'center','font-size':'0.67rem','padding':'.42rem 1.2rem',"height":'1.4rem'});
            $('.layui-m-layerbtn').css({'line-height':'1.5rem','height':'1.5rem'});
            $('.layui-m-layerbtn span').css({'font-size':'.6rem'});
        },
    };




    var  ajax= 'undefined';
    var commonUtil={
        expire_yeah:function(expire_yeah){
            expire_yeah=$.trim(expire_yeah);
            var myDate = new Date();
            var year = myDate.getYear()
            var year = year < 2000 ? year + 1900 : year
            var yy = year.toString().substr(2, 2);
            var pattern = /^\d{2}$/;
            if(expire_yeah =="" || expire_yeah == null ) {
                commonUtil.waring('有效期(年)不能为空');
                return false;
            }
            if(!expire_yeah.match(pattern) ) {
                commonUtil.waring('有效期(年)格式不正确');
                return false;
            }
            if(expire_yeah<yy){
                commonUtil.waring('有效期(年)不足');
                return false;
            }
            commonUtil.tips();
            return true;
        },
        expire_month:function(expire_month){
            expire_month=$.trim(expire_month);
            var pattern = /^0[1-9]|1[0-2]$/;
            if(expire_month =="" || expire_month == null ) {
                commonUtil.waring('有效期(月)不能为空');
                return false;
            }
            if(!expire_month.match(pattern)||expire_month.length!=2) {
                commonUtil.waring('有效期(月)格式不正确');
                return false;
            }
            commonUtil.tips();
            return true;
        },
        //时间
        timeYMD:function(expire_month,msgNull,msgError){
            expire_month=$.trim(expire_month);
            var pattern = /^[1-9]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;
            if(expire_month =="" || expire_month == null ) {
                commonUtil.waring(msgNull);
                return false;
            }
            if(!expire_month.match(pattern)) {
                commonUtil.waring(msgError);
                return false;
            }
            commonUtil.tips();
            return true;
        },
        
        //金钱
        money:function (money,msgNull,msgError) {
            money=$.trim(money);
            var pattern = /(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/;
            if(money =="" || money == null ) {
                commonUtil.waring(msgNull);
                return false;
            }
            if(!money.match(pattern)) {
                commonUtil.waring(msgError);
                return false;
            }
            commonUtil.tips();
            return true;
        },
        bank:function(bank){
            bank=$.trim(bank);

            if(bank =="" || bank == null ) {
                commonUtil.waring('请选择银行');
                return false;
            }
            commonUtil.tips();
            return true;
        },
        username:function(username,type){
            username=$.trim(username);
            var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[013678])\d{8}$/;
            if(username =="" || username == null ) {
                commonUtil.waring('请输入手机号/用户名');
                return false;
            }
            if(type==2){

            }else{
                if(!username.match(pattern)) {
                    commonUtil.waring('用户名是手机号');
                    return false;
                }
            }
            commonUtil.tips();
            return true;
        },
        is_time:function (times) {
            timer = setInterval(function() {
                times--;
                if(times > 0) {
                    $('.t-pwd-code1').text(times +'秒后重发');
                    $('.t-pwd-code1').attr('disabled','disabled');
                    $('.t-pwd-code1').css('background','gainsboro');
                } else {
                    times = 60;
                    $('.t-pwd-code1').text('重发验证码');
                    $('.t-pwd-code1').removeAttr('disabled');
                    $('.t-pwd-code1').css('background','#ff7200');
                    clearInterval(timer);

                }
            }, 1000);
        },
        is_searchcode:function(code){
            var code  = $.trim(code);
            var pattern = /[0-9a-zA-Z]*/;
            if(code == '') {
                commonUtil.waring('请输入网站密码');
                return false;
            }
            if(!code.match(pattern)) {
                commonUtil.waring('网站密码格式不正确，请重新输入');
                return false;
            }
            commonUtil.tips();
            return true;
        },
        is_number:function(number,msgNull,msgError){
            var number  = $.trim(number);
            var pattern = /^[0-9]{0,10}$/;
            if(number == "" || number == undefined || number == null) {
                commonUtil.waring(msgNull);
                return false;
            }
            if(!number.match(pattern)) {
                commonUtil.waring(msgError);
                return false;
            }
            commonUtil.tips();
            return true;
        },
        isnull:function(code,msg){
            var code  = $.trim(code);
            if(code == "" || code == undefined || code == null || code == 0 ) {
                layerPhone.BombBox(msg);layerPhone.changeCssBombBox();
                return false;
            }
            commonUtil.tips();
            return true;
        },
        tell:function(tell,msg){
            var tell  = $.trim(tell);
            var pattern = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/;
            if(tell == '') {
                if(msg==2){
                    layerPhone.BombBox('请输入公司联系电话');layerPhone.changeCssBombBox();
                }else{
                    layerPhone.BombBox('请输入联系电话');layerPhone.changeCssBombBox();
                }
                return false;
            }
            if(!tell.match(pattern)) {
                if(msg==2){
                    layerPhone.BombBox('联系电话号码格式不正确，请重新输入');layerPhone.changeCssBombBox();
                }else{
                    layerPhone.BombBox('号码格式不正确，请重新输入');layerPhone.changeCssBombBox();
                }
                return false;
            }
            commonUtil.tips();
            return true;
        },
        email:function(email){
            var email  = $.trim(email);
            var pattern = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
            if(email == '') {
                commonUtil.waring('请输入邮箱');
                return false;
            }
            if(!email.match(pattern)) {
                commonUtil.waring('邮箱格式不正确，请重新输入');
                return false;
            }
            commonUtil.tips();
            return true;
        },
        phone:function(phone){
            var phone  = $.trim(phone);
            var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[013678])\d{8}$/;
            var unique ='';
            if(phone == '') {
                layerPhone.BombBox('请输入手机号码');layerPhone.changeCssBombBox();
                // commonUtil.waring('请输入手机号码');
                return false;
            }
            if(!phone.match(pattern)) {
                layerPhone.BombBox('手机号码格式不正确，请重新输入');layerPhone.changeCssBombBox();
                // commonUtil.waring('手机号码格式不正确，请重新输入');
                return false;
            }
            commonUtil.tips();
            return true;

            //}else{
            //$.ajax({
            //	url:nameunique,
            //	type:'POST',
            //	data:{phone:phone,type:'mobile'},
            //	dataType:'json',
            //	async: false,  //同步发送请求
            //	success:function(result){
            //		unique=result.status;//alert(unique);
            //	},
            //	error:function(){
            //		commonUtil.tips("手机校验失败");
            //		return true;
            //	}
            //});
            //
            //    if(unique===false) {
            //        commonUtil.waring("手机号已经注册");
            //        return false;
            //    } else {
            //        commonUtil.tips();
            //        return true;
            //    }
            //}


        },
        is_number:function(number,msg){
            var number  = $.trim(number);
            var pattern = /^[0-9]{0,10}$/;
            if(!number.match(pattern)) {
                commonUtil.waring(msg);
                return false;
            }
            commonUtil.tips();
            return true;
        },
        passphone:function(passphone,msg){
            var reg = /^(\d{6})$/i;
            if(!passphone.match(reg)) {
                commonUtil.waring(msg);
                return false;
            }
            commonUtil.tips();
            return true;
        },
        aggreement:function(aggreement){
            if(aggreement){
                commonUtil.tips();
                return true;
            }else{
                commonUtil.waring('请同意协议');
                return false;
            }
        },
        //公司姓名
        cname:function(cname,type){
            cname=$.trim(cname);
            var pattern = /([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{4,}([a-zA-Z0-9]{0,})/;
            if(cname =="" || cname == null ) {
                layerPhone.BombBox(formarr[type][10001]);layerPhone.changeCssBombBox();
                return false;
            }
            if(!cname.match(pattern)) {
                layerPhone.BombBox(formarr[type][10002]);layerPhone.changeCssBombBox();
                return false;
            }
            // commonUtil.tips();
            return true;
        },
        details_four:function(cname,type){
            cname=$.trim(cname);
            var pattern = /([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{4,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{3,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{3,}([a-zA-Z0-9]{0,})/;
            if(cname =="" || cname == null ) {
                commonUtil.waring(formarr[type][10001]);
                return false;
            }
            if(!cname.match(pattern)) {
                commonUtil.waring(formarr[type][10002]);
                return false;
            }
            commonUtil.tips();
            return true;
        },
        details_two:function(cname,type){
            cname=$.trim(cname);
            //var pattern = /([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{0,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})/;
            var pattern = /([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{0,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})/;
            if(cname =="" || cname == null ) {
                commonUtil.waring(formarr[type][10001]);
                return false;
            }
            if(!cname.match(pattern)) {
                commonUtil.waring(formarr[type][10002]);
                return false;
            }
            commonUtil.tips();
            return true;
        },
        details_isnull:function(cname,msg,nullmsg){
            cname=$.trim(cname);
            //var pattern = /([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{0,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})/;
            var pattern = /([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{0,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})/;
            if(cname =="" || cname == null ) {
                layerPhone.BombBox(nullmsg);layerPhone.changeCssBombBox();
                return false;
            }
            if(!cname.match(pattern)) {
                layerPhone.BombBox(msg);layerPhone.changeCssBombBox();
                return false;
            }
            commonUtil.tips();
            return true;
        },
        namerule:function(cname,type){
            cname=$.trim(cname);
            var pattern = /([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{4,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{3,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{2,}([a-zA-Z0-9]{0,})|([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{1,}([a-zA-Z0-9]{0,})[\u4e00-\u9fa5]{3,}([a-zA-Z0-9]{0,})/;
            if(cname =="" || cname == null ) {
                commonUtil.waring(formarr[type][10001]);
                return false;
            }
            if(!cname.match(pattern)) {
                commonUtil.waring(formarr[type][10002]);
                return false;
            }
            commonUtil.tips();
            return true;
        },
        //真实姓名
        zname:function(zname,type){
            zname=$.trim(zname);
            var pattern = /^[\u4e00-\u9fa5]{2,5}(?:·[\u4E00-\u9FA5]{2,5})*$/;
            if(zname =="" || zname == null ) {
                commonUtil.waring(formarr[type][10001]);
                return false;
            }
            if(!zname.match(pattern)) {
                commonUtil.waring(formarr[type][10002]);
                return false;
            }
            commonUtil.tips();
            return true;
        },
        dynamiccode:function(authcode){
            authcode=$.trim(authcode);
            if(authcode.length>0){
                var pattern = /[0-9]{6,}/;
                if(!authcode.match(pattern)){
                    commonUtil.waring('动态验证码格式错误（6位以上数字组合）');
                    return false;
                }
            }
            commonUtil.tips();
            return true;
        },
        authcode:function(authcode,msg){
            authcode=$.trim(authcode);

            if(authcode =="" || authcode == null ) {
                if(msg==2){
                    layerPhone.BombBox('请填写邮箱验证码');layerPhone.changeCssBombBox();
                }else if(msg==3){
                    layerPhone.BombBox('请填写验证码');layerPhone.changeCssBombBox();
                }else{
                    layerPhone.BombBox('请填写手机验证码');layerPhone.changeCssBombBox();
                }

                return false;
            }
            if(msg==3){
                if(authcode.length!=6) {
                    layerPhone.BombBox('验证码必须是六位整数');layerPhone.changeCssBombBox();
                    return false;
                }
            }else{
                if(authcode.length!=4) {
                    if(msg==2){
                        layerPhone.BombBox('请填写正确邮箱验证码');layerPhone.changeCssBombBox();
                    }else{
                        layerPhone.BombBox('请填写正确手机验证码');layerPhone.changeCssBombBox();
                    }
                    return false;
                }
            }
            return true;
        },
        //身份证账号
        code:function(code){
            code=$.trim(code);
            var pattern = /^(\d{17}X|\d{18})$/i;
            if(code =="" || code == null ) {
                commonUtil.waring('身份证账号不能为空');
                return false;
            }
            if(!code.match(pattern)) {
                commonUtil.waring('身份证账号格式错误');
                return false;
            }
            commonUtil.tips();
            return true;
        },
        pwd:function(password,rep){
            if(rep == 1){
                password=$.trim(password);
                var pattern = /^(([a-z]+[0-9]+)|([0-9]+[a-z]+))[a-z0-9]*$/i;//[0-9a-zA-Z]{6,16}
                if(password == '') {
                    commonUtil.waring('密码不能为空');
                    return false;
                }
                if(!password.match(pattern)){
                    commonUtil.waring('登录密码（6-16位字母数字组合）');
                    return false;
                }
                if(password.length<6 || password.length>16){
                    commonUtil.waring('密码位数不正确');
                    return false;
                }
                if(password != $("input[name='password']").val()){
                    commonUtil.waring('两次密码不相同！');
                    return false;
                }
                commonUtil.tips();
                return true;
            }else{
                password=$.trim(password);
                var pattern = /^(([a-z]+[0-9]+)|([0-9]+[a-z]+))[a-z0-9]*$/i;//[0-9a-zA-Z]{6,16}
                if(password == '') {
                    commonUtil.waring('密码不能为空');
                    return false;
                }
                if(!password.match(pattern)){
                    commonUtil.waring('登录密码（6-16位字母数字组合）');
                    return false;
                }
                if(password.length<6 || password.length>16){
                    commonUtil.waring('密码位数不正确');
                    return false;
                }
                commonUtil.tips();
                return true;
            }

        },
        //信用卡
        credit_no:function(card_no){
            card_no=$.trim(card_no);
            var pattern = /^\d{16}$/;
            if(card_no =="" || card_no == null ) {
                commonUtil.waring('信用卡号不能为空');
                return false;
            }
            if(!card_no.match(pattern)) {
                commonUtil.waring('信用卡号格式不正确');
                return false;
            }
            commonUtil.tips();
            return true;

        },
        //信用卡安全码
        security_code:function(security_code){
            security_code=$.trim(security_code);
            var pattern = /^\d{3}$/;
            if(security_code =="" || security_code == null ) {
                commonUtil.waring('安全码不能为空');
                return false;
            }
            if(!security_code.match(pattern)) {
                commonUtil.waring('安全码格式不正确');
                return false;
            }
            commonUtil.tips();
            return true;
        },
        //银行卡
        card_no:function(card_no){
            card_no=$.trim(card_no);
            var pattern = /^(\d{16}|\d{17}|\d{18}|\d{19})$/;
            if(card_no =="" || card_no == null ) {
                commonUtil.waring('银行卡号不能为空');
                return false;
            }
            if(!card_no.match(pattern)) {
                commonUtil.waring('银行卡号格式不正确');
                return false;
            }
            commonUtil.tips();
            return true;
        },
        errorCode:function (code,msg) {
            switch (code){
                case '4005':
                case '5072':
                case '5113':
                case '5060':
                case '4012':
                case '4000':
                case '2000':
                case '2001':
                case '2010':
                case '2011':
                case '2012':
                case '2002':
                case '2005':
                case '2003':
                case '2022':
                case '5014':
                case '5013':
                case '4008':
                case '4009':
                case '5012':
                case '5066':
                case '5065':
                case '5064':
                case '5003':
                case '5116':
                    location.href = '/Error?code='+code;
                    break;
                case '5103':
                case '5123':
                    location.href = '/Error?code='+code;
                    break;
                default:
                    commonUtil.waring(msg);
                    commonUtil.unlock();
                    break;
            }
        },
        tips:function(){
            $(".t-error").text('');
        },
        waring:function(msg){
            $(".t-error").text(msg);

        },
        lockup:function(){
            $('.t-blue-btn').addClass('t-blue-btn-no');
            $(".t-blue-btn").attr('disabled',true);
            $('.t-blue-btn-no').removeClass('t-blue-btn');
            load = layer.open({type: 2,shade: true,scrollbar: false,shadeClose : false});
            // $('.t-mask').show();
        },
        unlock:function(){
            $('.t-blue-btn-no').addClass('t-blue-btn');
            $(".t-blue-btn").attr('disabled',false);
            $('.t-blue-btn').removeClass('t-blue-btn-no');
            layer.close(load);
            // $('.t-mask').hide();
        },
        //提示基础信息未过(首页)
        showconfirm:function (msg,buttonmsg) {
            layer.confirm(msg, {
                btn: [buttonmsg] //按钮
            }, function(){
                location.href = '/Promotion/';
            });
        },
        //确定和取消d
        showconfirmtwo:function (msg,buttonmsg) {
            layer.confirm(msg, {
                btn: ['确定'] //按钮
            }, function(){
                location.href = '/Borrowmoney/guaranteeadd?entrance=promote';
            }, function(){

            });
        },
        //去补充资料
        showconsupply:function (msg,buttonmsg) {
            layer.confirm(msg, {
                btn: [buttonmsg] //按钮
            }, function(){
                location.href = '/RegisterApp/ContactsExtreme';
            }, function(){

            });
        },
        //去补充资料
        showpublic:function (msg,buttonmsg,url) {
            layer.confirm(msg, {
                btn: [buttonmsg] //按钮
            }, function(){
                location.href = url;
            }, function(){

            });
        },
        //取消
        cancelShowMsg:function (msg,buttonmsg) {
            var showMsg = layer.confirm(msg, {
                btn: [buttonmsg] //按钮
            }, function(){
                layer.close(showMsg);
            }, function(){

            });
        },
        //修改css
        cancelrevisecss:function () {
            $('.layui-layer-dialog').css({'left':'10%','right':'10%'})
        },
        //修改px转rem
        revisecsstorem:function () {
            $('.layui-layer-dialog').css({'left':'10%','right':'10%','font-size':'0.1rem','width':'3rem'});
            $('.layui-layer-content').css({'font-size':'0.1rem','line-height':'.3rem'});
            $('.layui-layer-title').css({'font-size':'0.1rem','height':'.33rem','line-height':'.3rem'});
            $('.layui-layer-btn a').css({'padding':'0.03rem .1rem'});
            $('.layui-layer-setwin').css({'top':'.12rem','right':'.12rem'});
            $('.layui-layer-btn').css({'padding':'0 .1rem .1rem'});
        },
        showmsgMobile:function (msg) {
            layer.open({
                content: msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        },
        showmsgMobileRem:function (msg,button,url) {
            layer.open({
                title: [
                    '提示',
                    'background-color:#ff8470; color:#fff;'
                ]
                ,content: msg
                ,btn: [button]
                ,yes: function(index){
                    location.href = url;
                }
            });
        },
        revisecsstoremRem:function () {
            $('.layui-m-layerchild').css({'max-width':'4rem'});
            $('.layui-m-layerchild h3').css({'height':'.6rem','font-size':'.24rem'});
            $('.layui-m-layercont').css({'padding':'.3rem .2rem','font-size':'.2rem'});
            $('.layui-m-layerbtn').css({'height':'.6rem','font-size':'.2rem','line-height':2.5});
            $('.layui-m-layerbtn span').css({'font-size':'.2rem'});
        },
        showmsg:function (msg) {
            layer.msg(msg);
        },
        revisecss:function(){
            $(".layui-layer-dialog").css({left:"10%",right:"10%"})
        },
        timeregular:function(time,msg,nullmsg){
            time=$.trim(time);
            console.log(time);
            if(time == "" || time == undefined || time == null || time == 0 ) {
                commonUtil.waring(nullmsg);
                return false;
            }
            var pattern = /^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/;//[0-9a-zA-Z]{6,16}
            if(!time.match(pattern)) {
                commonUtil.waring(msg);
                return false;
            }
            commonUtil.tips();
            return true;
        }
    };
