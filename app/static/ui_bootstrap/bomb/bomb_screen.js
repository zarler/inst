
//自定义这层
var bomob_screen = {
		firstready:function(){
			$('body').append('<section  id="bomb_screen" style="z-index:100;position: fixed;top:0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);display: none"></section>');
		},
		newpeople : function(is_show,url){
			if(is_show == true){
				event.preventDefault()
				$('#bomb_screen').fadeIn(1000);
			}else{
				$('#bomb_screen').fadeOut(1000);
			}

			$('#bomb_screen').html('<img src="/static/ui_bootstrap/bomb/img/icon_box.png"  width="65%" style="margin: 35% auto .1rem auto;display: block;" /><a href="'+url+'"><img src="/static/ui_bootstrap/bomb/img/icon_button.png"  width="35%" style="margin: .6rem auto .1rem auto;display: block;" /></a><div style="height: 3rem;background: white;width: 1px;margin: 0 auto;z-index: -2;"></div><img src="/static/ui_bootstrap/bomb/img/icon_cha.png" id="bomb_close"  width="10%" style="margin: 0 auto .1rem auto;display: block;z-index: -1;" />');
		},
		bomobremove:function(){
			$('#bomb_screen').html('');
			$('#bomb_screen').fadeOut(1000);
		},
		//兑换优惠券
		exchangeCouponOk : function(is_show,money){
			if(is_show == true){
				// event.preventDefault()
				$('#bomb_screen').fadeIn(1000);
			}else{
				$('#bomb_screen').fadeOut(1000);
			}
			$('#bomb_screen').html('<section  id="bomb_screen" style="z-index:100;position: fixed;top:0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);"><img src="/static/ui_bootstrap/bomb/img/clock.png" onclick="bomob_screen.bomobremove();" width="8%" style="margin:25% 0 .1rem 75%;;display: block;" /><p style="text-align: center;background: url(/static/ui_bootstrap/bomb/img/7.png) no-repeat;background-size: cover;width: 60%;height: 2.6rem;margin: 0 auto;line-height: .3rem;font-size: 1rem;padding-top: 4rem;margin-left: 23%;color: white;"><strong>'+money+'</strong><span style="font-size: .4rem;">&nbsp;元</span><br /><button onclick="StatisticsClick();" style="background: white;width: 2.6rem;margin-top: .3rem;font-size: .3rem;padding: .1rem .1rem;border-radius: .1rem;color: red;letter-spacing:.05rem;height: .8rem;margin-left: -.23rem;">立即去借款</button></p><p style="text-align: center;font-size: .33rem;color: #d8d4d4;line-height: .5rem; margin: 0 0 0 .3rem;letter-spacing: .02rem;">'+money+'元减息券已塞入您的账户<br>快去撸钱吧！</p>');
		},
		//分享遮罩
		showMask : function(is_show){
			if(is_show == true){
				// event.preventDefault()
				$('#bomb_screen').fadeIn(1000);
			}else{
				$('#bomb_screen').fadeOut(1000);
			}
			$('#bomb_screen').html('<div id="fullbg"> <div id="dialog"> <img style="position:fixed;top:.1rem;right: 0;width: 5rem;z-index:100" src="/static/images/v2/activity/WholePoitGo/02_03.png"> </div> </div>');
		}
	};


		


