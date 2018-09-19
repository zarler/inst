<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * 站点配置 config 写在这里面可多纬
 */
return array(

//	'default' => array(
//		'security_path' => APPPATH.'../../protected/',			//隐私文件存放目录
//		'static_path' => APPPATH.'../static/',			//静态文件存放目录
//		'api_site'	=> 'http://api.timecash.cn',		//API站点
//	),
	'default' => array(
		'fileType' => APPPATH.'../../upload/',			//隐私文件存放目录
		'api_site'	=> 'http://api.timecash.cn',		//API站点
		'cutting'	=> FALSE,							//是否需要缩略图
		'scale'	=> TRUE,							    //是否等比缩略
		'addBg'	=> FALSE,							    //是否需要填充背景
		'cutWidth'	=> 0,							    //默认等比例缩微切割宽度
		'cutHeight'	=> 0,							    //默认等比例缩微切割高度
		'allowType'	=> array ( 'jpg' , 'gif' , 'jpeg' , 'png' , 'bmp' ),		//可以上传的图片类型
		'checkType'	=> TRUE,							 //是否检查图片格式
		'checkSize'	=> TRUE,							 //是否检查图片大小
		'maxSize'	=> TRUE,							 //是否检查图片大小
		'maxSize'	=> 10485760,							 //是否检查图片大小
		'autoDir'	=> FALSE,							 //默认开启目录自动分割;只上传原图有效
		'cutLayer'	=> 2,							 //目录分割;文件名宽度
		'fileName'	=> '',							 //定义上传之后的文件名称
		'convert'	=> FALSE,						//开启Linuxconvert缩图，目前只支持gif;其他类型用程序缩
		'fileName'	=> '',							 //定义上传之后的文件名称

		#水印设置项
		'imgMark'	=> FALSE,							 //上传时是否开启图片水印；
		'im_water'	=> '',							 //水印图文件；
		'im_waterPos'	=> 9,						//水印位置；
		'transparent'	=> 50,						//水印透明度；
		'fontMark'	=> FALSE,						//上传时是否开启文字水印；
		'font_waterPos'	=> 9,						//水印位置；
		'font'	=> 'upload/simsun.ttc',				//字体库；
		'font_text'	=> 'timecash',				    //文本；
		'font_size'	=> 12,				    //字体大小
		'font_color'	=> 12,				    //字体颜色
		'_config'	=> array(),				    //配置信息 即将上面的属性以数组形式定义，初始化值
		'img_data'	=> array(),				    //图片数据信息
		'dir_sep'	=> DIRECTORY_SEPARATOR,				    //图片数据信息
	),
);

