<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 2016/10/31
 * Time: 下午7:10
 *
 * 极速贷放款单数数据渲染
 */

class Lib_Helper_Inst
{

    protected $redis = NULL;
    protected $data = NULL;
    protected $real_total = 0;
    protected $today_new = 0;

    public function __construct() {
        $this->redis =Redis_Hash::instance();
        $data = $this->redis->get(Model_Order_Inst::KEY_STATUS);
        $date = date('Y-m-d');
        if(isset($data['date']) && $data['date']==$date ){
            $this->data['date'] = $data['date'];
            $this->data['first_today_total'] = isset($data['first_today_total']) ? $data['first_today_total'] : 0 ;
            $this->data['first_draw_count'] = isset($data['first_draw_count']) ? $data['first_draw_count'] : 0;
            $this->data['again_today_total'] = isset($data['again_today_total']) ? $data['again_today_total']: 0 ;
            $this->data['again_draw_count'] = isset($data['again_draw_count']) ? $data['again_draw_count'] : 0;

        }else{
            $this->data['date'] = $date;
            $this->data['first_today_total'] = 0;
            $this->data['first_draw_count'] = 0;
            $this->data['again_today_total'] = 0;
            $this->data['again_draw_count'] = 0;
            $this->today_new=1;

        }

    }

    /** 极速贷数据渲染
     * @param int $max
     * @param int $real_max
     * @return null
     */
    public function loan_draw($max=1000,$real_max=100,$prefix='again'){
        $real_total = $this->data[$prefix.'_draw_count'];
        $new_total = $this->data[$prefix.'_today_total'];

        if( $this->today_new) {//
            $new_total=0;
        }elseif($real_total+1 >= $real_max ) {//真实统计超过真实上限,立刻满单,确保不会出现负数
            $new_total = $max;
        }else{
            $real_surplus = $real_max - $real_total ;//真实剩余
            $surplus = $max - $this->data[$prefix.'_today_total'];//虚拟剩余
            if($real_surplus+1 >= $surplus) {
                $new_total = $max - $real_surplus +1;
            }else{
                if($surplus/$real_surplus > 15){
                    $add = rand(1, (int)$surplus / 15 /rand(1,5));
                }elseif($surplus/$real_surplus > 11) {
                    $add = rand(1,(int)$surplus/11/rand(3,15));
                }elseif($surplus/$real_surplus > 7) {
                    $add = rand(1,(int)$surplus/7 /rand(3,7));
                }elseif($surplus/$real_surplus > 5) {
                    $add = rand(1,(int)$surplus/5 /rand(1,15));
                }elseif($surplus/$real_surplus > 3) {
                    $add = rand(1,(int)$surplus/3 /rand(3,7));
                }elseif($surplus/$real_surplus > 1) {
                    $add = rand(1,(int)($surplus - $real_surplus)/rand(15,95));
                }else{
                    $add =1;
                }
                if($add<1){
                    $add=1;
                }
                $new_total = $this->data[$prefix.'_today_total'] + $add;
                if($max - $new_total <= $real_surplus){
                    $new_total = $max - $real_surplus;
                }
            }
        }

        if($real_max<1){
            $new_total = $max;
        }

        $this->data['date'] = date('Y-m-d');
        $this->data[$prefix.'_today_total'] = $new_total;
        $this->data[$prefix.'_draw_count']++;
        $this->data['last_update'] = time();
        $this->redis->set(Model_Order_Inst::KEY_STATUS,$this->data);
        return $this->data;
    }





}