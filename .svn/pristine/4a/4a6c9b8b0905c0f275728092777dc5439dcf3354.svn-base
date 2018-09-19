<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/6/1
 * Time: 上午11:15
 */

class Controller_APIPay_Deduct_UNSPay_Notify extends Controller {

    public function action_Index() {

//        if(!empty($_REQUEST)) {
            $env = Kohana::$config->load('env')->get('url');
            $res = TCAPI::factory()
                ->config('api-pay')
                ->url($env['api-pay'].'/Notify_Payment_Inst_UNSPay_Notify/')
                ->post($_REQUEST)
                ->execute()->body();
            echo $res;
//        }
    }
}