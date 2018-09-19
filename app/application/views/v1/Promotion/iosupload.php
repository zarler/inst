<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1,target-densitydpi=medium-dpi">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="email=no">
	<?php echo HTML::style('static/css/uploadapp.css?1231111'); ?>
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
</style>
<body>
<div style="font-size: 18px">
	<section>
		<?php echo HTML::image('/static/images/promotion/icon_jiantou.png',array('class'=>'image1')) ?>
	</section>
	<section style="margin: 5rem 5rem">
		<dl class="t-loan2 t-add">
			<dt>1、点击右上角 <em>•••</em></dd>
			<dt>2、选择在"Safari"中打开</dt>
		</dl>
	</section>
	<section style="text-align: center;">
			<?php echo HTML::image('/static/images/promotion/icon_logo.png',array('class'=>'image2')) ?>
	</section>
</div>
</body>
</html>
<script>
	direct = function(){
		<?php if($is_weixin){?>
		<?php }else{?>
			location.href = 'https://itunes.apple.com/cn/app/kuai-jin/id1130326523?mt=8';
		<?php }?>
	}
	direct();
</script>

