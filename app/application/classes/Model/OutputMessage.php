<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/6/14
 * Time: 下午8:19
 */
class Model_OutputMessage extends Model_Database {


    //身份证错误匹配msg
    const IDENTITY_CODE_ERROR = '校验失败';
    const IDENTITY_NAME_DIFFERENT = '姓名不一致';
    const IDENTITY_QUERYING = '查询中';
    const IDENTITY_EMPTY = '服务结果';

    //身份证错误输出
    const IDENTITY_OUTPUT_ERROR = '您输入的姓名或身份证号有误,请重新输入';
    const IDENTITY_OUTPUT_EMPTY = '未查询到您的身份信息';
    const IDENTITY_OUTPUT_QUERYING = '正在查询中，请不要重复提交';
    const IDENTITY_OTHER = '信息校验失败,请联系客服';

    public $IDENTITY_ERROR_MESSAGE = array(
        self::IDENTITY_CODE_ERROR,
        self::IDENTITY_NAME_DIFFERENT,
    );

    //卡信息接口错误提示
    const CARD_INFO_QUERY_ERROR = '您提交的银行卡信息有误,请重新输入';
    const CARD_INFO_OTHER = '卡信息查询失败,请联系客服';
    public $CARD_INFO_MESSAGE = array(
       '5001' => self::CARD_INFO_QUERY_ERROR,
    );


    //储蓄卡错误提示
    const AUTH_CARD_NO_MATCH = '您提交的银行卡信息有误,请重新输入';
    const AUTH_CARD_OVERRUN = '您已达到今日提交次数限制,请明天再试';
    const AUTH_CARD_INVALID_CARD = '您提交的银行卡信息有误,请重新输入';
    const AUTH_CARD_NO_SUPPORT = '目前暂不支持您添加的银行卡';
    const AUTH_CARD_FAIL = '您提交的银行卡信息有误,请重新输入';
    const AUTH_CARD_TYPE_ERROR = '请确认您添加的是借记卡';
    const AUTH_CARD_OTHER = '卡信息提交失败,请联系客服';

    public $AUTH_CARD_MESSAGE = array(
        '2011' => self::AUTH_CARD_NO_MATCH,
        '2013' => self::AUTH_CARD_OVERRUN,
        '2007' => self::AUTH_CARD_INVALID_CARD,
        '2008' => self::AUTH_CARD_NO_SUPPORT,
        '3014' => self::AUTH_CARD_FAIL,
        '5062' => self::AUTH_CARD_TYPE_ERROR,
    );


    //信用卡错误提示
    const CREDIT_AUTH_CARD_NO_MATCH = '您提交的银行卡信息有误,请重新输入';
    const CREDIT_AUTH_CARD_OVERRUN = '您已达到今日提交次数限制,请明天再试';
    const CREDIT_AUTH_CARD_INVALID_CARD = '您提交的银行卡信息有误,请重新输入';
    const CREDIT_AUTH_CARD_NO_SUPPORT = '目前暂不支持您添加的银行卡';
    const CREDIT_AUTH_CARD_FAIL = '您提交的银行卡信息有误,请重新输入';
    const CREDIT_AUTH_CARD_EXPIRE_DATE = '您提交的银行卡信息有误,请重新输入';
    const CREDIT_AUTH_CARD_OTHER = '卡信息提交失败,请联系客服';

    public $CREDIT_AUTH_CARD_MESSAGE = array(
        '2011' => self::CREDIT_AUTH_CARD_NO_MATCH,
        '2013' => self::CREDIT_AUTH_CARD_OVERRUN,
        '2007' => self::CREDIT_AUTH_CARD_INVALID_CARD,
        '2001' => self::CREDIT_AUTH_CARD_FAIL,
        '3014' => self::CREDIT_AUTH_CARD_NO_SUPPORT,
    );

    const PUBLIC_ERROR_MESSAGE = '系统异常';

    public function get_identity_message($msg){

        if(strpos($msg, self::IDENTITY_EMPTY) !== false){
            return self::IDENTITY_OUTPUT_EMPTY;
        }

        if(strpos($msg, self::IDENTITY_QUERYING) !== false){
            return self::IDENTITY_OUTPUT_QUERYING;
        }

        foreach($this->IDENTITY_ERROR_MESSAGE as $value){
            if(strpos($msg, $value) !== false){
                return self::IDENTITY_OUTPUT_ERROR;
            }
        }
        return self::IDENTITY_OTHER;
    }

    public function get_card_info_message($code){

        if(isset($this->CARD_INFO_MESSAGE[$code])){
            return $this->CARD_INFO_MESSAGE[$code];
        }
        return self::CARD_INFO_OTHER;
    }

    public function get_auth_card_message($code){
        if(isset($this->AUTH_CARD_MESSAGE[$code])){
            return $this->AUTH_CARD_MESSAGE[$code];
        }
        return self::AUTH_CARD_OTHER;
    }

    public function get_credit_auth_card_message($code){
        if(isset($this->CREDIT_AUTH_CARD_MESSAGE[$code])){
            return $this->CREDIT_AUTH_CARD_MESSAGE[$code];
        }
        return self::CREDIT_AUTH_CARD_OTHER;
    }





}