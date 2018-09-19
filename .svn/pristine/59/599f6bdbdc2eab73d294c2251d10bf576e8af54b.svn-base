<?PHP

/**
 * Class Facepp - Face++ PHP SDK
 *
 * @author Tianye
 * @author Rick de Graaff <rick@lemon-internet.nl>
 * @since  2013-12-11
 * @version  1.1
 * @modified 16-01-2014
 * @copyright 2013 - 2015 Tianye
 **/
class Facepp
{
    ######################################################
    ### If you choose Amazon(US) server,please use the ###
    ### http://apius.faceplusplus.com/v2               ###
    ### or                                             ###
    ### https://apius.faceplusplus.com/v2              ###
    ######################################################

    public $server          = 'http://api-faceid-com.http.p.timecash.cn/faceid/v1';
    public $server_v2          = 'http://api-megvii-com.https.p.timecash.cn/faceid/v2';
 	#public $server         = 'http://apicn.faceplusplus.com/v2';
    #public $server         = 'https://apicn.faceplusplus.com/v2';
    #public $server         = 'http://apius.faceplusplus.com/v2';
    #public $server         = 'https://apius.faceplusplus.com/v2';


    public $api_key         = '';        // set your API KEY or set the key static in the property
    public $api_secret      = '';        // set your API SECRET or set the secret static in the property

    private $useragent      = 'Faceplusplus PHP SDK/1.1';

    private $image_type = array(
        '2' => 'image/jpeg',
        '3' => 'image/png',
        '4' => 'image/bmp',
        '6' => 'image/gif'
    );

    public function __construct () {
        $this->api_key = APP_KEY;
        $this->api_secret = APP_SECRET;
        $this->server = SERVER;
        $this->server_v2 = SERVER_V2;
    }


    /**
     * @param $method - The Face++ API
     * @param array $params - Request Parameters
     * @return array - {'http_code':'Http Status Code', 'request_url':'Http Request URL','body':' JSON Response'}
     * @throws Exception
     */
    public function execute($method, array $params) {
        if( ! $this->apiPropertiesAreSet()) {
            throw new Exception('API properties are not set');
        }
        $params['api_key']      = $this->api_key;
        $params['api_secret']   = $this->api_secret;

        return $this->request("{$this->server}{$method}", $params);
    }

    public function faceid_execute($method, array $params) {
        if( ! $this->apiPropertiesAreSet()) {
            throw new Exception('API properties are not set');
        }
        $params['api_key']      = $this->api_key;
        $params['api_secret']   = $this->api_secret;

        return $this->request("{$this->server}{$method}", $params);
    }
    
    public function megvii_execute($method, array $params) {
        if( ! $this->apiPropertiesAreSet()) {
            throw new Exception('API properties are not set');
        }
        $params['api_key']      = $this->api_key;
        $params['api_secret']   = $this->api_secret;
        return $this->request("{$this->server_v2}{$method}", $params);
    }

    public function megvii_raw_execute($method, array $params) {
        if( ! $this->apiPropertiesAreSet()) {
            throw new Exception('API properties are not set');
        }
        $params['api_key']      = $this->api_key;
        $params['api_secret']   = $this->api_secret;
        return $this->request("{$this->server_v2}{$method}", $params);
    }

    private function request($request_url, $request_body) {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $request_url);
        curl_setopt($curl_handle, CURLOPT_FILETIME, true);
        curl_setopt($curl_handle, CURLOPT_FRESH_CONNECT, false);
        if(version_compare(phpversion(),"5.5","<=")){
            curl_setopt($curl_handle, CURLOPT_CLOSEPOLICY,CURLCLOSEPOLICY_LEAST_RECENTLY_USED);
        }else{
            curl_setopt($curl_handle, CURLOPT_SAFE_UPLOAD, false);
        }
        curl_setopt($curl_handle, CURLOPT_MAXREDIRS, 5);
        curl_setopt($curl_handle, CURLOPT_HEADER, false);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($curl_handle, CURLOPT_NOSIGNAL, true);
        curl_setopt($curl_handle, CURLOPT_REFERER, $request_url);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, $this->useragent);

        if (extension_loaded('zlib')) {
            curl_setopt($curl_handle, CURLOPT_ENCODING, '');
        }

        if (array_key_exists('img', $request_body)) {
            $image_type = $this->getImageType($request_body['img']);
            $request_body['image'] = curl_file_create($request_body['img'], $image_type, 'image');
//            $request_body['image'] = '@' . $request_body['img'];
            unset($request_body['img']);
        }

        if (array_key_exists('img_idcard', $request_body)) {
            $image_type = $this->getImageType($request_body['img_idcard']);
            $request_body['image_idcard'] = curl_file_create($request_body['img_idcard'], $image_type, 'image_idcard');
//            $request_body['image'] = '@' . $request_body['img'];
            unset($request_body['img_idcard']);
        }

        if (array_key_exists('image_ref1', $request_body)) {
            $image_type = $this->getImageType($request_body['image_ref1']);
            $request_body['image_ref1'] = curl_file_create($request_body['image_ref1'],$image_type,'image');
//                $request_body['image_ref1'] = '@' . $request_body['image_ref1'];
        }

        if(array_key_exists('image_ref2', $request_body)){
            $request_body['image_ref2'] = '@' . $request_body['image_ref2'];
        }

        if(array_key_exists('image_ref3', $request_body)){
            $request_body['image_ref3'] = '@' . $request_body['image_ref3'];
        }


//        echo '<pre>';
//        print_r($request_body);

        //文件日志
        $log = new MyLog(DOCROOT.'protected/logs');
        //随机ID
        $id = uniqid(Text::random('alnum',8));
        //IP地址
        $ip = Request::$client_ip;
        //记录发送数据
        $log->write(array(array('body'=>json_encode($request_body),'time'=>time())),"time [{$id}] - IP:{$ip} --- FaceID REQUEST: \r\nbody \r\n");


        curl_setopt($curl_handle, CURLOPT_POST, true);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $request_body);

        $response_text      = curl_exec($curl_handle);
        $response_header    = curl_getinfo($curl_handle);

        //记录返回数据
        $log->write(array(array('body'=>json_encode($response_text),'time'=>time())),"time [{$id}] --- FaceID RESPONSE: \r\nbody\r\n");
        
        if(curl_errno($curl_handle)){
            return array (
                'http_code'     => $response_header['http_code'],
                'request_url'   => $request_url,
                'body'          => curl_error($curl_handle)
            );
        }
        curl_close($curl_handle);

        return array (
            'http_code'     => $response_header['http_code'],
            'request_url'   => $request_url,
            'body'          => $response_text
        );
    }

    private function apiPropertiesAreSet() {
        if( ! $this->api_key) {
            return false;
        }

        if( ! $this->api_secret) {
            return false;
        }
        
        return true;
    }

    private function getImageType($filename){
        $img_type = exif_imagetype($filename);
        if(isset($this->image_type[$img_type])){
            return $this->image_type[$img_type];
        }
        return false;
    }
}
