<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/24
 * Time: 下午7:16
 *
 * 反欺诈定义model
 *      反欺诈模块名字对应Lib类和方法
 *      一个反欺诈模块注入思路,曾经为实现银行卡绑卡根据不同产品类型执行不同的反欺诈。
 *      也可以在其他功能模块后注入对应代码,简单实现模块注入。
 */
class Model_Credit_AF extends Model_Database {


    const MODULES =[
        'FastLoan/event_credit_af' =>[
            'lib' => 'Credit_FastLoan',
            'has_func' => 'has_event_credit_af_pass',
            'chk_func' => 'event_credit_af',
            'pass_func' => 'event_credit_af_passed',
        ],
        'FullPreAuthLoan/event_credit_af' =>[
            'lib' => 'Credit_FullPreAuthLoan',
            'has_func' => 'has_event_credit_af_pass',
            'chk_func' => 'event_credit_af',
            'pass_func' => 'event_credit_af_passed',
        ],
        'PreAuthLoan/event_credit_af' =>[
            'lib' => 'Credit_PreAuthLoan',
            'has_func' => 'has_event_credit_af_pass',
            'chk_func' => 'event_credit_af',
            'pass_func' => 'event_credit_af_passed',
        ],
        'BankCard/event_add_af' =>[
            'lib' => 'Credit_BankCard',
            'has_func' => 'has_event_add_af_pass',
            'chk_func' => 'event_add_af',
            'pass_func' => 'event_add_af_passed',

        ],
    ];


    protected $_get = [];

    /*
     * 在其他Controller中注入实现例子
     */
    function action_sample(){
        /**
         * 调用反欺诈模块
         * /v2/BankCard/Add?af=FastLoan/event_credit_af
         */

        try{
            $af_modules = Model_Credit_AF::MODULES;
            $af_module = isset($this->_get['af']) ? $this->_get['af'] :'' ;
            if( isset($af_modules[$af_module]) ){
                $afm = $af_modules[$af_module];
                $lib_class = $afm['lib'];
                $has_func = $afm['has_func'];
                $chk_func = $afm['chk_func'];
                $pass_func = $afm['pass_func'];

                if(class_exists($lib_class)){
                    if(!Lib::factory($lib_class)->app_id(App::$id)->$has_func(App::$_token['user_id'])){//已通过授信反欺诈的用户不用再检查
                        $af = Lib::factory($lib_class)->app_id(App::$id)->$chk_func(App::$_token['user_id']);//授信反欺诈
                        if($af){
                            Lib::factory($lib_class)->app_id(App::$id)->$pass_func(App::$_token['user_id']);//授信反欺诈通过
                        }
                    }
                }
            }
        }catch (Exception $e){

        }
    }



}