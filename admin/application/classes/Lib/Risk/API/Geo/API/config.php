<?php
/**
 * Created by IntelliJ IDEA.
 * User: yangyuexin
 * Date: 2018/1/10
 * Time: 下午2:19
 */
header("Content-Type: text/html; charset=UTF-8");
class Lib_Risk_API_Geo_API_config{
    /**
     * 集奥配置
     * 目前开通权限有
     * 银行卡信息验证Z4，Z5
     * 手机号身份证号验证 A4，B7
     * 姓名身份证是否匹配 Y1
     */
    public static $server = "http://yz.geotmt.com" ; // http://yz.geotmt.com��https://yz.geotmt.com
    public static $encrypted = 1 ; // 是否加密传输
    public static $encryptionType = "AES2" ; // 只支持AES2(秘钥长度16个字符)
    public static $encryptionKey = "Kuaijin@geo12345" ; // 需要同时告知GEO在后台进行配置
    public static $username = "kuaijin" ; // 向GEO获取
    public static $password = "Kuaijin@geo123" ;
    public static $uno = "200824" ;

};