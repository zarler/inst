//弹框提示

var bomb={
    //状态，显示，按钮
    MsgButton:function(arr){

        if(!arr.status||typeof(arr.status)=="undefined"||arr.status==0|| !isNaN(arr.status)){
            console.log('数据错误！');
            return false;
        }

        if(arr.status = 'yes'){
            var urlImg = '/static/images/v1/icon_duihao.png';
        }

        if(arr.status = 'no'){
            var urlImg = '/static/images/v1/icon_duihao.png';
        }

        var str = '<div class="bomb-box"><div class="bomb-box-show"><img class="bomb-box-img" src="'+urlImg+'"><p>'+arr.msg+'</p><button class="submitButton" onclick="$AppInst.WebJump({\'type\':\'phone\', \'par\':\'13643176531\'})">'+arr.msgButton+'</button></div></div>\n';
        $('body').append(str);
    },

};
