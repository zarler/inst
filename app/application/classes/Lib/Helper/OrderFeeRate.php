<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 2017/2/28
 * Time: 上午1:28
 *
 * 费率助手
 *
 * v1 担保借款  罚息0.3% 滞纳金0.1%
 * v1 极速贷 罚息0.3% 滞纳金0.1%
 *
 * v2 担保借款  罚息0.3% 滞纳金0.1%
 * v2 极速贷 罚息1% 滞纳金 0.5%
 *
 *
 */

class Lib_Helper_OrderFeeRate
{

    const FASTLOAN_V1_END_ORDER_ID=175059;//极速贷老订单最后一个order_id


    protected $_inst_html = <<<EOF
<section class="t-check-1 t-margintop-5">
    <p class="">1.请于还款日之前确保还款卡内余额充足</p>
    <p class="">2.实际还款日按实际放款日计算结果为准；还款日若不存在，以自然月最后一天为准提示；</p>
    <p class="">3.逾期罚息：按当期未还金额×1%/天，逾期滞纳金：按当期未还金额×1%/天；</p>
</section>
EOF;



    public function __construct() {



    }


    /** 按订单类型和ID产生费率描述
     * @param $order_type
     * @param $order_id
     * @return string
     */
    public function html($order_type){

        return $this->_inst_html;
    }



}