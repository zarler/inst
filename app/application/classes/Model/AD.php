<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: caojiabin
 * Date: 16/6/22
 * Time: 下午2:15
 *
 */
class Model_AD extends Model_Database
{

    const ALL_APP_ID = '@all';

    public function getOne($id)
    {
        $data = DB::select()->from('ad_banner')->and_where('id', '=', $id)->limit(1)->execute()->current();

        return $data;
    }


    //新版本APP3.0.0 适配
    public function get_by_app_id($app_ids = [self::ALL_APP_ID])
    {
        $time = date('Y-m-d H:i:s');
        $data = DB::select('ad_banner.*')->from('ad_banner')->join('ad_app', 'LEFT')->on('ad_banner.id', '=', 'ad_app.ad_id')
            ->where('ad_app.app_id', 'in', $app_ids)
            ->where('ad_banner.end_time', '>=', $time)
            ->order_by('ad_banner.rank', 'desc')
            ->execute()
            ->as_array();

        return $data;
    }


    //公告
    public function getNotice($type, $app_ids = [self::ALL_APP_ID])
    {
        $time = date('Y-m-d H:i:s');

        return DB::select('ad_banner.*')->from('ad_banner')->join('ad_app', 'LEFT')->on('ad_banner.id', '=', 'ad_app.ad_id')
            ->where('ad_app.app_id', 'in', $app_ids)
            ->where('ad_banner.type', '=', $type)
            ->where('ad_banner.start_time', '<', $time)
            ->where('ad_banner.end_time', '>', $time)
            ->limit(1)
            ->order_by('rank', 'desc')
            ->execute()->current();
    }

}