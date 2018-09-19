<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 2018/1/9
 * Time: 下午4:15
 */
class Lib_CardInfo_API extends Lib_Common
{
    private $cardNo;

    /**
     * 设置银行卡号
     * @param $cardNo
     * @return $this
     */
    private function cardNo($cardNo)
    {
        $this->cardNo = $cardNo;
        return $this;
    }

    /**
     * @param $request
     * @return $this
     */
    public function query($request)
    {
        if (!isset($request['card_no']) || $request['card_no'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 card_no');
        }
        $this->cardNo($request['card_no']);
        $this->method = 'LianLianPay';
        return $this;
    }

    public function execute()
    {
        if (!$this->response) {
            call_user_func_array('self::' . $this->method, ['data' => []]);
        }
        return $this;
    }

    public function LianLianPay()
    {
        $cardInfo = new Lib_CardInfo_API_LianLianPay_API();
        $this->request = ['card_no' => $this->cardNo];
        $cardInfo->card_info($this->request);

        $result = $cardInfo->postData($this->request);
        $response = json_decode($result, true);

        if (isset($response['ret_code'])) {
            if ($response['ret_code'] === '0000') {
                $this->response(true, [
                    'result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS,
                    'message' => '查询成功',
                    'info' => [
                        'bank_code' => $response['bank_code'],
                        'bank_name' => $response['bank_name'],
                        'card_type' => $response['card_type']
                    ]
                ]);
            } else {
                $this->response(true,
                    ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => $response['ret_msg']]);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '查询异常']);
        }
    }
}