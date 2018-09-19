<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Author:caojiabin
 * Date:2017-09-18 18.54
 */

class Lib_Risk_TONGDUN2_API_ApplyModel
{
    private $url = ''; // 请求URL
    public $params = []; // 请求参数数组

    public function __construct($event_data = []) 
    {
        if (empty($event_data['partner_code'])) {
            include_once realpath(dirname(__FILE__))."/config.php";
        }else{
            include_once realpath(dirname(__FILE__))."/config.php";
        }

        unset($event_data['partner_code']);
        $this->params['partner_code'] = APPLY_MODEL_PARTNER_CODE;
        $this->params['secret_key'] = APPLY_MODEL_SECRET_KEY;
        $this->params['event_id'] = APPLY_MODEL_EVENT_ID;
        $this->params['id_number'] = $event_data['id_number'];
        $this->params['account_mobile'] = $event_data['account_mobile'];
        $this->url = APPLY_MODEL_URL;
    }

	public function requestPost()
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		if (is_array($this->params)) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->params));
		}else{
			curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
		}
		if(substr($this->url, 0, 5) === 'https'){
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  //信任任何证书
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); //检查证书中是否设置域名
		}
		$data = curl_exec($curl);
		curl_close($curl);
        $_keys = array('provider', 'action', 'msg', 'req_data', 'resp_data', 'type','reference_id','create_time');
        $_values = array('TongDun2','ApplyModel','调用TongDun ApplyModel接口',json_encode($this->params), $data,'TongDunAPI',0,time());
        DB::insert('api_out_log', $_keys)->values($_values)->execute();
		return json_decode($data);
	}
}
