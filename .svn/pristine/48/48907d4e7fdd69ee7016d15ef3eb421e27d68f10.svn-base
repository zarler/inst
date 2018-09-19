<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 2018/1/9
 * Time: 下午4:15
 */
class Lib_AuthCard_API extends Lib_Common
{
    private $cardNo;
    private $holder;
    private $identityCode;
    private $mobile;

    private $provider;
    private $providerConfig = [
        'jiudouyu',
        'geo',
    ];

    /**
     * 选择验卡提供商
     * @param null $provider
     * @return $this
     */
    private function provider($provider = null)
    {
        if ($provider) {
            if (in_array($provider, $this->providerConfig)) {
                $this->provider = $provider;
            } else {
                $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '服务提供商不支持');
            }
        } else {
            $this->provider = 'geo';
        }
    }

    /**
     * 设置手机号
     * @param $mobile
     * @return $this
     */
    private function mobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * 设置卡号
     * @param $cardNo
     * @return $this
     */
    private function cardNo($cardNo)
    {
        $this->cardNo = $cardNo;
        return $this;
    }

    /**
     * 设置持卡人
     * @param $holder
     * @return $this
     */
    private function holder($holder)
    {
        $this->holder = $holder;
        return $this;
    }

    /**
     * 设置身份证号
     * @param $identityCode
     * @return $this
     */
    private function identityCode($identityCode)
    {
        $this->identityCode = $identityCode;
        return $this;
    }

    /**
     * 储蓄卡
     * @param $request
     * @return $this
     */
    public function auth($action, $request)
    {
        if (!isset($request['phone']) || $request['phone'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 mobile');
        }

        if (!isset($request['card_no']) || $request['phone'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 cardNo');
        }

        if (!isset($request['card_holder']) || $request['phone'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 holder');
        }

        if (!isset($request['identity_code']) || $request['identity_code'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 identityCode');
        }
        $this->mobile($request['phone']);
        $this->cardNo($request['card_no']);
        $this->holder($request['card_holder']);
        $this->identityCode($request['identity_code']);
        $this->provider($action);
        $this->method = $this->provider;
        return $this;
    }

    /**
     * 发送请求
     * @return $this
     */
    public function execute()
    {
        if (!$this->response) {
            call_user_func_array('self::' . $this->method, ['data' => []]);
        }
        return $this;
    }

    /**
     * 九斗鱼验卡
     */
    private function jiudouyu()
    {
        $this->request = [
            'card_no' => $this->cardNo,
            'name' => $this->holder,
            'id_card' => $this->identityCode,
            'phone' => $this->mobile
        ];

        $auth_card = new Lib_AuthCard_API_9douyu();
        $this->request['sign'] = $auth_card->getSign($this->request);
        $result = $auth_card->postData($this->request);
        $response = json_decode($result, true);

        if (isset($response['data']['status']) && isset($response['data']['status'])) {
            if ($response['data']['status'] == 'success' && $response['data']['result_code'] === '0000') {
                if ($response['data']['bank_card_type'] == 1) {
                    $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => '卡类型不符']);
                } else {
                    $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '鉴权成功']);
                }
            } else {
                $this->response(true,
                    ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => $response['data']['msg']]);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '查询异常']);
        }
    }

    /**
     * 集奥四要素验卡
     */
    private function geo()
    {
        $this->request = [
            'card_no' => $this->cardNo,
            'name' => $this->holder,
            'id_card' => $this->identityCode,
            'phone' => $this->mobile
        ];

        $auth_card = Lib::factory("Risk_API_Geo_API");
        $result = $auth_card->authcard($this->request);
        $response = $result;

        if (isset($response['code'])) {
            if ($response['msg'] == '成功' && $response['code'] === '200') {
                if ($response['data']['RSL'][0]['RS']['code'] == 0) {
                    $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '鉴权成功']);
                } else {
                    $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => '鉴权失败']);
                }
            } else {
                $this->response(true,
                    ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => $response['msg']]);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '查询异常']);
        }
    }
}