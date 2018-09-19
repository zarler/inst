<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 2018/1/9
 * Time: 下午4:15
 */
class Lib_Identity_API extends Lib_Common
{
    private $idKey;
    private $sex;
    private $userName;
    private $identityCode;
    private $provider = 'GongAnBu';

    /**
     * 设置银行卡号
     * @param $userName
     * @return $this
     */
    private function userName($userName)
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * 设置银行卡号
     * @param $identityCode
     * @return $this
     */
    private function identityCode($identityCode)
    {
        $this->identityCode = $identityCode;
        return $this;
    }

    /**
     * 实名验证
     * @param $request
     * @return $this
     */
    public function verify($request)
    {
        if (!isset($request['realName']) || $request['realName'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 realName');
        }
        if (!isset($request['identityCard']) || $request['realName'] == '') {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '缺少参数 identityCard');
        }
        $this->userName($request['realName']);
        $this->identityCode($request['identityCard']);
        $this->idKey = md5($this->userName . $this->identityCode);
        $this->getSex();
        $this->find();
        $this->method = $this->provider;
        return $this;
    }

    public function execute()
    {
        if (!$this->response) {
            call_user_func_array('self::' . $this->method, ['data' => []]);
        }
        return $this;
    }

    public function GongAnBu()
    {
        $identity = new Lib_Identity_GongAnBu_API();
        $this->request = ['realName' => $this->userName, 'identityCard' => $this->identityCode];
        $result = $identity->doVerify($this->userName, $this->identityCode);
        $hash = '';
        if (isset($result['api_result']['photo'])) {
            if (!$hash = $this->savePhoto($result['api_result']['photo'])) {
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_DATA_EXCEPTION, 'message' => '网纹照片存储异常']);
                return ;
            }
        }

        if (isset($result['api_result']['result'])) {
            try {
                $db = Database::instance();
                $db->begin();
                if ($result['api_result']['result'] == Lib_Identity_GongAnBu_API::API_RESULT_SUCCESS) {
                    Model::factory('User_Identity')->update($this->idKey,
                        ['status' => Model_User_Identity::STATUS_VALID, 'identity_face' => $hash]);
                    $db->commit();
                    $this->response(true, [
                        'result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS,
                        'message' => $result['api_result']['msg'],
                        'info' => [
                            'name' => $this->userName,
                            'identity_code' => $this->identityCode,
                            'sex' => $this->sex,
                            'identity_face' => $hash
                        ]
                    ]);
                } elseif ($result['api_result']['result'] == Lib_Identity_GongAnBu_API::API_RESULT_PHOTO_FAIL) {
                    Model::factory('User_Identity')->update($this->idKey,
                        ['status' => Model_User_Identity::STATUS_EXCEPTION]);
                    $this->response(true, [
                        'result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL,
                        'message' => $result['api_result']['msg']
                    ]);
                    $db->commit();
                } else {
                    Model::factory('User_Identity')->update($this->idKey,
                        ['status' => Model_User_Identity::STATUS_INVALID]);
                    $db->commit();
                    $this->response(true, [
                        'result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL,
                        'message' => $result['api_result']['msg']
                    ]);
                }
            } catch (Exception $e) {
                $db->rollback();
                $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '查询异常']);
            }
        } else {
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '查询异常']);
        }
    }

    private function getSex()
    {
        $this->sex = substr($this->identityCode, 16, 1) % 2 === 0 ? '女' : '男';
    }

    private function find()
    {
        try {
            $db = Database::instance();
            $db->begin();
            if ($identity = Model::factory('User_Identity')->find_one_by_idKey($this->idKey, [
                Model_User_Identity::STATUS_INIT,
                Model_User_Identity::STATUS_VALID,
                Model_User_Identity::STATUS_INVALID
            ])
            ) {
                if ($identity['status'] == Model_User_Identity::STATUS_VALID) {
                    $this->response(true, [
                        'result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS,
                        'message' => '一致',
                        'info' => [
                            'name' => $identity['name'],
                            'identity_code' => $identity['code'],
                            'sex' => $this->sex,
                            'identity_face' => $identity['identity_face']
                        ]
                    ]);
                } elseif ($identity['status'] == Model_User_Identity::STATUS_INIT) {
                    $this->response(true,
                        ['result' => Lib_Common::LIB_COMMON_API_RESULT_PROCESSING, 'message' => '查询中']);
                } else {
                    if ($identity['status'] == Model_User_Identity::STATUS_INIT) {
                        $this->response(true,
                            ['result' => Lib_Common::LIB_COMMON_API_RESULT_FAIL, 'message' => '验证失败']);
                    }
                }
            } else {
                Model::factory('User_Identity')->create($this->idKey,
                    ['name' => $this->userName, 'identity_code' => $this->identityCode, 'sex' => $this->sex]);
            }
            Model::factory('User_Identity')->timeout();
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, 'message' => '查询异常']);
        }
    }

    private function savePhoto($photo_str = '')
    {
        $file_upload = Kohana::$config->load('site')->get('default');
        $file['name'] = time() . Text::random('alnum', 6) . '.jpg';
        $file['size'] = 2000;
        $file['binary'] = $photo_str;
        $json_file = json_encode($file);
        $response = HttpClient::factory($file_upload['ps_site'] . '/API/Operation/Save')->post(array('_file' => $json_file))->httpheader(array(
            "CLIENTID:" . $file_upload['client_id'],
            "CLIENTSIGN:" . md5(json_encode(array('_file' => $json_file)) . $file_upload['client_key'])
        ))->execute()->body();
        $result = json_decode($response, true);
        if (isset($result['status']) && $result['status']) {
            return $result['hash'];
        }
        return false;
    }

}