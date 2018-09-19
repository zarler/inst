<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: chunyu
 * Date: 18/01/29
 * Time: 下午3:05
 * 极光推送模板
 *
 */
class Model_JPush_Template extends Model_Database
{

    const JPUSH_NOTE = array(
        'title' => '快金声明',
        'body' => '警惕诈骗，还款谨防上当！',
        'img_url' => 'http://a-cdn.timecash.cn/banner/top-banner/sm-tc1.png',
        'push_type' => 'message',
        'open_page' => 'app://app.timecash/AppHome/Index',
        'go_page' => 'http://m.timecash.cn/Notice',
    );


    public function getOneByCode($code){
        $data = constant('self::' . $code);
        /*$config = Kohana::$config->load('env')->get('url');

        if(isset($data['go_page']) && substr($data['go_page'], 0, 5) == '/app/'){
            $data['go_page'] = $config['wx'] . $data['go_page'];
        }
        if(isset($data['open_page']) && substr($data['open_page'], 0, 5) == '/app/'){
            $data['open_page'] = $config['wx'] . $data['open_page'];
        }*/

        return $data;
    }

}