<?php

/**
 * Created by PhpStorm.
 * User: wangchao
 * Date: 2018/1/10
 * Time: 下午4:27
 */
class Lib_Risk_API extends Lib_Common
{
    private $user_id;
    private $param;
    private $action;
    private $provider;
    private $providerConfig = [
        'credit' => 'Moxie',
        'anti' => 'QianHai',
    ];

    /**
     * 授信部分
     * @param $provider
     * @return $this
     */
    public function credit($provider = null)
    {
        if (!empty($provider)) {
            $this->provider = $provider;
        } else {
            $this->provider = $this->providerConfig['credit'];
        }

        return $this;
    }

    /**
     * 反欺诈部分
     * @param $provider
     * @return $this
     */
    public function anti($provider)
    {
        if (!empty($provider)) {
            $this->provider = $provider;
        } else {
            $this->provider = $this->providerConfig['anti'];
        }

        return $this;
    }

    public function user_id($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * 发送请求
     * @return $this
     */
    public function execute()
    {
        if(!$this->response){
            call_user_func_array('self::'.$this->method, ['data'=>[]]);
        }
        return $this;
    }

    /**
     * @param $action
     * @param $param
     * @return $this
     *
     */
    public function html($action, $param)
    {
        $this->method = $this->provider.'Html';
        $this->action = $action;
        $this->param = $param;

        return $this;
    }

    /**
     * 调用魔蝎接口
     * @return $this
     */
    private function MoxieHtml()
    {
        $Moxie = new Lib_Risk_API_Moxie_API();
        $this->param['action'] = $this->action;
        $result_data = $Moxie->html($this->param);
        //处理返回数据
        if ($result_data) {
            $this->response(true,
                [
                    'result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS,
                    'message' => '提交成功',
                    'info' =>
                        [
                            'html' => '<html> <head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> </head> <body onload="javascript:document.login_form.submit();"> <form id="login_form" name="login_form" action='.$result_data.' method="get"></form> </body> </html>',
                            'jump' => 1,    //jump =1 跳出， jump = 2 html
                            'provider' => 'Moxie',
                        ],
                ]
            );
        } else {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, '接口异常');
        }

        return $this;
    }

    /*
     * 反欺诈路由
     * $action 请求方法
     * $param 参数
     *
     **/

    public function query($action, $param)
    {
        $this->method = $this->provider;
        $this->action = $action;
        $this->param = $param;
        return $this;
    }


    private function QianHai()
    {
        $qianhai = new Lib_Risk_API_QianHai_Api();
        $action = $this->action;
        $result = $qianhai->$action($this->param);
        $this->response(true, $result, Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, '接口请求成功');
        return $this;
        //处理返回数据
    }

    /**
     * 集奥
     * @return $this
     */
    private function Geo()
    {
        $geo = Lib::factory("Risk_API_Geo_API");
        $action = $this->action;
        $result = $geo->$action($this->param);
        if ($result) {
            $this->response(true, $result, Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, '接口请求成功');
        } else {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, '接口异常');
        }
        return $this;
    }

    /**
     * 91金融
     * @return $this
     */
    private function Jinrong91()
    {
        $jinrong = Lib::factory("Risk_API_Jinrong91_API");
        $action = $this->action;
        $result = $jinrong->$action($this->param);
        if ($result) {
            $this->response(true, $result, Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, '接口请求成功');
        } else {
            $this->response(false, [], Lib_Common::LIB_COMMON_API_RESULT_EXCEPTION, '接口异常');
        }        return $this;
    }
}