<?php
/*
 * @param   array  $data
 *
 * @return  json
 *
 * 调用方式：$res = Lib::factory("JPush_Client")->chose("Notification")->push($request_array)->asJson();
 *
 *
 * */


include_once realpath(dirname(__FILE__))."/autoload.php";

use JPush\Client as JPush;

class Lib_JPush_Client extends Lib_Common
{
    private $config = [];
    private $client = '';
    private $push = '';
    protected $type;

    const JPUSH_TYPE_NOTIFICATION = 'Notification';
    const JPUSH_TYPE_MESSAGE = 'Message';

    public function __construct()
    {

        $this->config = Kohana::$config->load('api')->get('JPush');
        $this->client = new JPush($this->config['app_key'], $this->config['master_secret']);
        $this->push = $this->client->push();

        return $this;

    }
    public function getPush()
    {
        return $this->push;
    }

    public function chose($type)
    {
        if ($type) {
            $this->type = $type;
        } else {
            $this->type = self::JPUSH_TYPE_NOTIFICATION;
        }
        return $this;
    }


    public function push($data)
    {

        $title = isset($data['title']) ? $data['title'] : '';
        $subtitle = isset($data['subtitle']) ? $data['subtitle'] : '';
        $body = isset($data['body']) ? $data['body'] : '';
        $extras = isset($data['extras']) ? $data['extras'] : [];


        if (empty($title) or empty($body)) {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_MISS_PARAM, '标题和内容均不能为空');
        }
        try {
            $this->setPlatform('all');

            if (isset($data['all_audience']) and !empty($data['all_audience'])) {

                $this->setAllAudience();
            } else {

                if (isset($data['tag']) and !empty($data['tag'])) {

                    $this->setTag($data['tag']);
                }

                if (isset($data['alias']) and !empty($data['alias'])) {

                    $this->setAlias($data['alias']);
                }

            }
            $params = [
                'title' => $title,
                'subtitle' => $subtitle,
                'body' => $body,
                'extras' => $extras,
            ];
            if ($this->type == 'Notification') {
                $result = $this->pushNotification($params);
            } else {
                $result = $this->pushMessage($params);
            }
            if (is_array($result) && isset($result['body']['sendno']) && isset($result['body']['msg_id'])) {
                $this->response(true, $result, Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, '发送成功');
            } else {
                $this->response(false, $result, Lib_Common::LIB_COMMON_API_DATA_EXCEPTION, '发送失败');
            }
        } catch (Exception $e) {
            $msg = '请求失败';
            $api_result = $e->getMessage();
            $this->response(false, $api_result, Lib_Common::LIB_COMMON_API_RESULT_FAIL, $msg);
        }
        return $this;
    }


    public function pushNotification($data)
    {
        return $this->push
            ->iosNotification(
                ['title' => $data['title'], 'subtitle' => $data['subtitle'], 'body' => $data['body']],
                [
                    'sound' => 'sound.caf',
                    'badge' => '+1',
                    'category' => 'jiguang',
                    'extras' => $data['extras'],
                ]
            )
            ->androidNotification(
                $data['body'],
                [
                    'title' => $data['title'],
                    'extras' => $data['extras'],
                ]
            )
            ->options(['apns_production' => $this->config['apns_production']])
            ->send();
    }


    public function pushMessage(array $data)
    {
        return $this->push
            ->message(
                $data['body'],
                [
                    'title' => $data['title'],
                    'extras' => $data['extras'],
                ]
            )
            ->options(['apns_production' => $this->config['apns_production']])
            ->send();
    }


    public function setPlatform($plat_form = 'all')
    {
        $this->push
            ->setPlatform($plat_form);

        return $this;
    }

    public function setAllAudience()
    {
        $this->push
            ->addAllAudience();

        return $this;
    }

    public function setTag(array $tag = [])
    {
        $this->push
            ->addTag($tag);

        return $this;
    }

    public function setAlias(array $alias = [])
    {
        $this->push
            ->addAlias($alias);

        return $this;
    }

    public function setRegistrationId(array $registrationId = [])
    {
        $this->push
            ->addRegistrationId($registrationId);

        return $this;
    }

}
