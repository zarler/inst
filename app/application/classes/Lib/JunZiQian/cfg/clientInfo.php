<?php
namespace org_mapu_themis_rop_cfg;
/**
 * 客户端配置信息
 * @author yfx 2015-03-10
 *
 */
class ClientInfo{
	/**
	 * 沙箱和正式环境的app_key、app_secret、services_url均不同 
	 * 前期测试请联系技术支持申请沙箱的：app_key、app_secret
	 * 生产上不要用于测试，请使用正规内容进行保全api操作。
	 * */
	//本地
	//static $services_url = 'http://localhost:8180/services';
	//static $services_url = 'http://www.99baoquan.dev:8084/certificate/affirmBack';
	//app_key对应从服务商申请到的appkey
	//static $app_key = '请输入appkey';
	//appkey对应的密钥,客户使用,不能公开
	//static $app_secret = '请输入appkey';
	
	//sandbox
	//static $services_url = 'http://sandbox.api.ebaoquan.org/services';
	//app_key对应从服务商申请到的appkey
	//static $app_key = '82c03da89c8123a5';
	//appkey对应的密钥,客户使用,不能公开
	//static $app_secret = '9a8ece59d58e1d30d1d4fb0772a9f7cc';
	
	//生产
	static $services_url = 'http://api.ebaoquan.org/services';
	//app_key对应从服务商申请到的appkey
	static $app_key = '1a5f573b31e437be';
	//appkey对应的密钥,客户使用,不能公开
	static $app_secret = '0b150fea356e809e4545e18f9b36faf5';

}
?>
