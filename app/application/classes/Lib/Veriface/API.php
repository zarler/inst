<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 2018/1/11
 * Time: 下午7:23
 */
class Lib_Veriface_API extends Lib_Common
{
    private $headImgHash;
    private $bestImgHash;
    private $headImg;
    private $bestImg;
    private $provider;
    private $userName;
    private $identityCode;
    private $tmpDir = DOCROOT . 'protected/tmp/';

    /**
     * 设置最网纹照片
     * @param $headImgHash
     */
    private function headImgHash($headImgHash)
    {
        $this->headImgHash = $headImgHash;
    }

    /**
     * 设置最清晰照片
     * @param $bestImgHash
     */
    private function bestImgHash($bestImgHash)
    {
        $this->bestImgHash = $bestImgHash;
    }

    /**
     * 设置姓名
     * @param $userName
     */
    private function userName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * 设置身份证号
     * @param $identityCode
     */
    private function identityCode($identityCode)
    {
        $this->identityCode = $identityCode;
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
     * 对比图片
     * @param $request
     * @return $this
     */
    public function verify($request)
    {
        if (!isset($request['best_img']) || $request['best_img'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 best_img');
        }
        if (!isset($request['head_img']) || $request['head_img'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 head_img');
        }
        if (!isset($request['name']) || $request['name'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 name');
        }
        if (!isset($request['identity_code']) || $request['identity_code'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 identity_code');
        }
        $this->headImgHash($request['head_img']);
        $this->bestImgHash($request['best_img']);
        $this->userName($request['name']);
        $this->identityCode($request['identity_code']);
        $this->bestImg = $this->getPhoto('最优', $this->bestImgHash);
        $this->headImg = $this->getPhoto('网纹', $this->headImgHash);
        $this->method = $this->provider = 'FaceID';
        return $this;
    }

    /**
     * 对比图片
     * @return $this
     */
    private function FaceID()
    {
        $faceID = new Lib_Veriface_API_FaceID_API();

        $bestImgTmpFile = $this->tmpDir . 'detect_' . $this->bestImg['file'];
        //写入临时目录
        file_put_contents($bestImgTmpFile, base64_decode($this->bestImg['binary']));
        //添加图片到FaceID库
        $detectResponse = $faceID->add(['img' => $bestImgTmpFile]);
        //删除临时图片
        unlink($bestImgTmpFile);
        $detectResult = json_decode($detectResponse['body'], true);

        if (isset($detectResponse['http_code'])) {
            if ($detectResponse['http_code'] == '400') {
                if ($detectResult['error'] == 'IMAGE_ERROR_UNSUPPORTED_FORMAT') {
                    $this->response(true,
                        ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => '最优照片格式不支持']);
                    return;
                } else {
                    $this->response(true, [
                        'result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION,
                        'message' => '最优照片提交异常 error:' . $detectResult['error']
                    ]);
                    return;
                }
            } elseif ($detectResponse['http_code'] == '200') {
                if (!isset($detectResult['faces'][0]['token'])) {
                    $this->response(true,
                        ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => '最优照片未检测到人脸']);
                    return;
                }
            } else {
                $this->response(true, [
                    'result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION,
                    'message' => '最优照片提交异常 http_code:' . $detectResult['http_code']
                ]);
                return;
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '最优照片提交异常']);
            return;
        }

        $verify_request['face_token'] = $detectResult['faces'][0]['token'];
        $verify_tmp = $this->tmpDir . 'verify_' . $this->headImg['file'];
        //v1 faceid接口
        $verify_request['name'] = $this->userName;
        $verify_request['identity_code'] = $this->identityCode;
        $verify_request['img_idcard'] = $verify_tmp;

        //写入临时目录
        file_put_contents($verify_tmp, base64_decode($this->headImg['binary']));
        $verifyResponse = $faceID->faceid_verify($verify_request);
        //使用完删除临时图片
        unlink($verify_tmp);
        $verifyResult = json_decode($verifyResponse['body'], true);
        if (isset($verifyResponse['http_code'])) {
            if ($verifyResponse['http_code'] == '400') {
                if (strpos($verifyResult['error_message'], 'IMAGE_ERROR_UNSUPPORTED_FORMAT') !== false) {
                    $this->response(true,
                        ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => '网纹照片格式不支持']);
                    return;
                } else {
                    $this->response(true, [
                        'result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION,
                        'message' => '最优照片提交异常 error:' . $verifyResult['error_message']
                    ]);
                    return;
                }
            } elseif ($verifyResponse['http_code'] == '200') {

            } else {
                $this->response(true, [
                    'result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION,
                    'message' => '网纹照片提交异常 http_code:' . $verifyResponse['http_code']
                ]);
                return;
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '网纹照片提交异常']);
            return;
        }

        $response = [];
        if (isset($verifyResult['result_idcard']) && isset($verifyResult['result_idcard']['confidence']) && isset($verifyResult['result_idcard']['thresholds']) && is_array($verifyResult['result_idcard']['thresholds'])) {
            $response['request_id'] = $verifyResult['request_id'];
            $response['confidence'] = $verifyResult['result_idcard']['confidence'];
            foreach ($verifyResult['result_idcard']['thresholds'] as $key => $value) {
                $response[$key] = $value;
            }
            $this->response(true,
                ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => '对比成功', 'info' => $response]);
        } else {
            $this->response(true,
                ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => '对比失败', 'info' => $verifyResult]);

        }
    }

    /**
     * 从PS服务器提取图片
     * @param $hash
     * @param $type
     * @return $this
     */
    private function getPhoto($type, $hash)
    {
        $data = ['hash' => $hash];
        $config = Kohana::$config->load('site')->get('default');
        $response = HttpClient::factory($config['ps_site'] . '/API/Operation/Json')->get($data)->httpheader(array(
            "CLIENTID:" . $config['client_id'],
            "CLIENTSIGN:" . md5(json_encode($data) . $config['client_key'])
        ))->execute()->body();
        $result = json_decode($response, true);

        if (!isset($result['status']) || !$result['status']) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_DATA_EXCEPTION, '获取' . $type . '图片异常');
        }
        return $result;
    }
}