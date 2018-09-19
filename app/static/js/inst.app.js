//app对接接口

//操作系统
(function($){
    $.AppInst = function (){
        var system = '';
        var u = navigator.userAgent;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        if(isAndroid){
             system = 'android';
        }
        if(isiOS){
             system = 'ios';
        }
        //调用手机函数
        this.WebJump = function (arr) {     //this.testFun方法，加上了this，就是公有方法了，外部可以访问。
            if(!arr.type||typeof(arr.type)=="undefined"||arr.type==0|| !isNaN(arr.type)){
                console.log('数据错误！');
                return false;
            }
            if(system == 'android'){
                window.android.JsAppInteraction(arr.type,arr.par);
            }else if(system == 'ios'){
                JsAppInteraction(arr.type,arr.par);
            }
        };

    };
})(jQuery);
