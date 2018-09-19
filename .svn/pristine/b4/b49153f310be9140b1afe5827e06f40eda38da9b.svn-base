<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/1/27
 * Time: 下午5:00
 */
class Lib_SMS_API extends Lib_Common
{
    private $code = null;
    private $mobile;
    private $param;
    private $templateCode;
    private $template;
    private $smsBody;
    private $type;

    /**
     * 设置发送手机号
     * @param $mobile
     * @return $this
     */
    private function mobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * 设置发送模板
     * @param $templateCode
     * @return $this
     */
    private function template($templateCode)
    {
        $this->templateCode = $templateCode;
        return $this;
    }

    /**
     * 设置短信主体 待替换
     * @param $param
     * @return $this
     */
    private function param($param)
    {
        $this->param = $param;
        return $this;
    }

    /**
     * 设置验证码
     * @param $code
     * @return $this
     */
    private function code($code)
    {
        $this->code = $code;
        array_push($this->param, $this->code);
        return $this;
    }

    /**
     * 发送短信
     * @param $request
     * @return $this
     */
    public function send($request)
    {
        if (!isset($request['mobile']) || $request['mobile'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 mobile');
        }

        if (!isset($request['template']) || $request['template'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 template');
        }

        if (isset($request['param']) || is_array($request['param'])) {
            $this->param($request['param']);
        }

        if (isset($request['code']) || $request['code'] != '') {
            $this->code($request['code']);
        }

        $this->template($request['template']);
        $this->mobile($request['mobile']);

        if ($rs = Model::factory('SMS')->get_one_by_template_code($this->templateCode)) {
            if ($rs['status'] == Model_SMS::STATUS_VALID) {
                if ($rs['body'] == '') {
                    $this->response(false, [], Lib_Common::LIB_COMMON_API_DATA_EXCEPTION, '短信模板为空');
                } else {
                    if (!$rs['provider']) {
                        $this->response(false, [], Lib_Common::LIB_COMMON_API_DATA_EXCEPTION, '未发现开启的短信通道');
                    }
                    $this->method = $rs['provider'];
                    $this->type = $rs['type'];
                    $this->template = $rs['body'];
                }
            } else {
                $this->response(false, [], Lib_Common::LIB_COMMON_API_DATA_EXCEPTION, '短信状态不可用');
            }
        } else {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_DATA_EXCEPTION, '未发现开启的短信通道');
        }
        return $this;
    }

    /**
     * 查询余额
     * @param $request
     * @return $this
     */
    public function balance($request)
    {
        if (!isset($request['provider'])) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_DATA_EXCEPTION, '无效的服务提供商');
        } else {
            $this->method = $request['provider'] . 'Balance';
        }
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

    private function Luosimao()
    {
        $this->smsBody = $this->format($this->template, $this->param) . '【快金】';
        $this->request = ['mobile' => $this->mobile, 'message' => $this->smsBody];
        $luosimao = new Lib_SMS_API_Luosimao();
        $result = $luosimao->sendSMS($this->request);
        $response = json_decode($result, true);

        if (isset($response['error'])) {
            if ($response['error'] === 0) {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '短信发送成功']);
            } else {
                $this->response(true,
                    ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => $response['error']]);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '短信发送异常']);
        }
    }

    private function MengXin()
    {
        $this->smsBody = $this->format($this->template, $this->param) . '【快金】';
        $this->request = ['mobile' => $this->mobile, 'content' => $this->smsBody];

        if ($this->type == Model_SMS::TYPE_NORMAL) {
            $mengxin = new Lib_SMS_API_NewMengXin();
            $result = $mengxin->chose($this->type)->sendSMS($this->request);
            $response = json_decode($result, true);

            if (isset($response['status'])) {
                if ($response['status'] === 0) {
                    $this->response(true,
                        ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '短信发送成功']);
                } else {
                    $this->response(true, [
                        'result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL,
                        'message' => Lib_SMS_API_MengXin::$status[$response['status']]
                    ]);
                }
            } else {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '短信发送异常']);
            }
        } elseif ($this->type == Model_SMS::TYPE_OVERDUE) {
            $mengxin = new Lib_SMS_API_NewMengXin();
            $result = $mengxin->chose($this->type)->sendSMS($this->request);
            $response = json_decode($result, true);

            if (isset($response['status'])) {
                if ($response['status'] === 0) {
                    $this->response(true,
                        ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '短信发送成功']);
                } else {
                    $this->response(true, [
                        'result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL,
                        'message' => Lib_SMS_API_MengXin::$status[$response['status']]
                    ]);
                }
            } else {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '短信发送异常']);
            }
        } elseif ($this->type == Model_SMS::TYPE_AD) {
            $mengxin = new Lib_SMS_API_MengXin();
            $result = $mengxin->chose($this->type)->sendSMS($this->request);
            $response = json_decode($result, true);

            if (isset($response['returnstatus'])) {
                if ($response['returnstatus'] === 'Success') {
                    $this->response(true,
                        ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '短信发送成功']);
                } else {
                    $this->response(true,
                        ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => $response['message']]);
                }
            } else {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '短信发送异常']);
            }
        } else {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_DATA_EXCEPTION, '未发现开启的短信通道');
        }
    }

    private function LuosimaoBalance()
    {
        $luosimao = new Lib_SMS_API_Luosimao();
        $result = $luosimao->getSMSStatus();
        $response = json_decode($result, true);

        if (isset($response['error'])) {
            if ($response['error'] === 0) {
                $this->response(true,
                    ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '查询成功', 'info' => $response]);
            } else {
                $this->response(true,
                    ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => $response['error']]);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '查询异常']);
        }
    }

    private function MengXinBalance()
    {
        $mengxin = new Lib_SMS_API_MengXin();
        $result = $mengxin->chose(Model_SMS::TYPE_NORMAL)->getStatus();
        $normal = json_decode($result, true);

        $result = $mengxin->chose(Model_SMS::TYPE_OVERDUE)->getStatus();
        $overdue = json_decode($result, true);

        $result = $mengxin->chose(Model_SMS::TYPE_AD)->getStatus();
        $ad = json_decode($result, true);
        $info = [];
        if (isset($normal['returnstatus']) && $overdue['returnstatus'] && $ad['returnstatus']) {
            $info['normal'] = $normal;
            $info['overdue'] = $overdue;
            $info['ad'] = $ad;
            $this->response(true,
                ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '查询成功', 'info' => $info]);
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '查询异常']);

        }
    }

    /**
     * 格式化短信内容
     * @param $template
     * @param $data
     * @return mixed
     */
    private function format($template, $data)
    {
        try {
            if (is_array($data)) {
                $params = [$template];
                foreach ($data as $value) {
                    array_push($params, $value);
                }
                return call_user_func_array("sprintf", $params);
            } else {
                return call_user_func_array("sprintf", [$template, $data]);
            }
        } catch (Exception $e) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_DATA_EXCEPTION, '模板与参数不匹配');
        }
    }
}