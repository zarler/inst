<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 2016/10/20
 * Time: 2:14
 *
 * 用于定义重要属性
 *
 *
 */
class Model_AntiFraud extends Model {

    const INIT = 0;//生成记录时默认的值
    const PASS = 1;//通过
    const UNPASS = 2;//未通过
    const MANUAL = 3;//人工处理
    const NO_MESSAGE = -1;//没有返回信息




}