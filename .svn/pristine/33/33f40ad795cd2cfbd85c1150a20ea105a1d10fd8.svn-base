<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>信息提示</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1,target-densitydpi=medium-dpi">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="email=no">
    <?php echo HTML::style('static/css/public.css'); ?>
    <?php echo HTML::style('static/css/t-cash.css'); ?>
	<?php echo HTML::script('static/js/lockPhoneBackBtn.js'); ?>
</head>
<body>
<style>
/* 404 */
.x-404{position: fixed; left: 0; top: 0; right: 0; bottom: 0; text-align: center; color: #ff7200; font-size: 0.85rem; font-weight: lighter;}
.x-404 img{width: 8.9rem; margin:3.5rem 0 3rem;}
</style>
<section class="x-404">
	<?php if(isset($type)&&!empty($type)){ echo HTML::image('static/images/not_conform.jpg',array("style"=>"width:5rem"));}else{echo HTML::image('static/images/x-404.png');}?>
	<p style="line-height:1.3rem;margin:0 1rem"><?php echo $error?></p><br><br>
	<section class="m-toborrow" style="margin: 0 2rem">
		<?php if(isset($type)&&!empty($type)){ ?>
			<a href="/Borrowmoney/borrow" class="t-red-btn">去申请担保借款</a><!-- 新用户,暂时不能借款 !--><br><br>
		<?php } ?>
		<a href="<?php echo $url;?>" class="t-red-btn">返回</a><!-- 新用户,暂时不能借款 !--><br>
	</section>
</section>

</body>
</html>