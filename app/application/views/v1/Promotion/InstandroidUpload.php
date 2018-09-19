<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1,target-densitydpi=medium-dpi">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="email=no">
    <link rel="stylesheet" type="text/css" href="/static/css/local/uploadapp.css?11111">
</head>
<style>
	.t-add dt{
		line-height: 20px;
		margin-bottom: 1rem;
	}
	.image1{
		margin: 0 auto;
		position: fixed;
		top: 2rem;
		right: 3.2rem;
		width:5.6rem;
	}
	.image2{
		margin:0 auto;
		width:5.2rem
	}
    a img{
        display:none;
    }
</style>
<body>
<div id="htmlL" style="font-size: 18px">
	<section>
        <img class="image1" src="/static/images/v1/icon_jiantou_share.png">
	</section>
	<section style="margin: 5rem 5rem">
		<dl class="t-loan2 t-add">
			<dt>1、点击右上角 <em>•••</em></dd>
			<dt>2、选择在"在浏览器打开"</dt>
		</dl>
	</section>
	<section style="text-align: center;">
        <img class="image2" src="/static/images/v1/instlogo.png">
	</section>
</div>
<script type="text/javascript" src="//js.users.51.la/19393049.js"></script>
</body>
</html>
<script>
	direct = function(){
		<?php if($is_weixin){?>
		<?php }else{?>
            location.href = '<?php echo $url; ?>';
		<?php }?>
	}
	direct();
</script>

