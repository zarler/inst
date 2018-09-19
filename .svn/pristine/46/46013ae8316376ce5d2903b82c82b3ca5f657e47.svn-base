<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: guorui
 * Date: 2016/1/8
 * Time: 15:48
 */
class Model_Fraud_TongDun extends Model
{
    public static function checkdatabase($id_number)
    {
        $user = DB::select()->from('fraud_tongdun')->where('id_number', '=',
            $id_number)->limit(1)->execute()->current();

        return $user;
    }

    public static function get_hit_rules($user_id)
    {
        $hit_rules = DB::select('id,has_rules,response_data')->from('tongdun_data')->where('user_id', '=',
            $user_id)->where('type', '=', 1)->order_by('id', 'desc')->execute()->current();

        if (empty($hit_rules)) {
            $hit_rules_old = DB::select('id,hit_rules,seq_id,has_rules')->from('fraud_tongdun')->where('user_id', '=',
                $user_id)->order_by('id', 'desc')->execute()->current();
            if (empty($hit_rules_old)) {
                return '';
            } else {  //TongDun
                $result = json_decode($hit_rules_old['hit_rules'], true);
                $result[0]['seq_id'] = $seq_id = $hit_rules_old['seq_id'];;
                if ($hit_rules_old['has_rules'] == 0 && !empty($result)) {
                    foreach ($result as $k => $v) {
                        foreach ($v['hit_rules'] as $kk => $vv) {
                            $event_data = array('sequence_id' => $seq_id, 'rule_uuid' => $vv['uuid']);
//                            $apiout = TCAPI::factory('TongDun/HitRuleDetail')->post($event_data)->execute()->body();
                            $apiout = Lib::factory("Risk_API")->anti('TongDunRules')->query('execute',
                                $event_data)->execute()->body();
                            $apiarr = json_decode($apiout, true);
                            if ($apiarr['success']) {
                                $rulesinfo = $apiarr['策略列表'][0]['规则列表'][0]['规则详情'];
                                foreach ($rulesinfo as $key => $val) {
                                    //将每个子规则的描述挪到前面
                                    $arrrank = array(
                                        '描述', '个数', '详情', '借款人手机个数', '借款人手机详情', '借款人身份证个数', '借款人身份证详情', '借款人手机',
                                    );
                                    $rulesinfo[$key] = Tool::factory('Array')->use_sortarr_tosort_array($arrrank, $val);
                                }
                                $data = array();
                                $data['seq_id'] = $seq_id;
                                $data['policy_uuid'] = $v['policy_uuid'];
                                $data['uuid'] = $vv['uuid'];
                                $data['hit_rules'] = json_encode($rulesinfo);
                                $data['create_time'] = time();
                                $data['user_id'] = $user_id;
                                list($insert_id, $affected_rows) = DB::insert('tongdun_rules', array(
                                    'seq_id', 'policy_uuid', 'uuid', 'hit_rules', 'create_time', 'user_id',
                                ))->values($data)->execute();
                                unset($data);
                            }
                        }
                    }
                    $rows = DB::update('fraud_tongdun')->set(array('has_rules' => 1))->where('id', '=',
                        $hit_rules_old['id'])->execute();
                }

                return $result;
            }
        } else {      //TongDun2
            $result = json_decode($hit_rules['response_data'], true);

            if (isset($result['status']) && $result['status'] == false){
                return $result;
            }
            if ($result) {
                $seq_id = $result['seq_id'];
                if ($hit_rules['has_rules'] == 0) {
                    foreach ($result['policy_set'] as $k => $v) {
                        if (isset($v['hit_rules'])) {
                            foreach ($v['hit_rules'] as $kk => $vv) {
                                $event_data = array('sequence_id' => $seq_id, 'rule_uuid' => $vv['uuid']);
//                                $apiout = TCAPI::factory('TongDun2/HitRuleDetail')->post($event_data)->execute()->body();
                                $apiout = Lib::factory("Risk_API")->anti('TongDunRules')->query('execute',
                                    $event_data)->execute()->body();
                                $apiarr = $apiout['api_result']['info']['data'];
//                                var_dump($apiarr);die;
                                if ($apiarr['success']) {
                                    $rulesinfo = $apiarr['策略列表'][0]['规则列表'][0]['规则详情'];
                                    foreach ($rulesinfo as $key => $val) {
                                        //将每个子规则的描述挪到前面
                                        $arrrank = array(
                                            '描述', '个数', '详情', '借款人手机个数', '借款人手机详情', '借款人身份证个数', '借款人身份证详情', '借款人手机',
                                        );
                                        $rulesinfo[$key] = Tool::factory('Array')->use_sortarr_tosort_array($arrrank,
                                            $val);
                                    }
                                    $data = array();
                                    $data['seq_id'] = $seq_id;
                                    $data['policy_uuid'] = $v['policy_uuid'];
                                    $data['uuid'] = $vv['uuid'];
                                    $data['hit_rules'] = json_encode($rulesinfo);
                                    $data['create_time'] = time();
                                    $data['user_id'] = $user_id;
                                    list($insert_id, $affected_rows) = DB::insert('tongdun_rules', array(
                                        'seq_id', 'policy_uuid', 'uuid', 'hit_rules', 'create_time', 'user_id',
                                    ))->values($data)->execute();

                                    unset($data);
                                }
                            }
                        }
                    }
                    $rows = DB::update('tongdun_data')->set(array('has_rules' => 1))->where('id', '=',
                        $hit_rules['id'])->execute();
                }
                $result['policy_set'][0]['seq_id'] = $seq_id;

                return $result['policy_set'];
            } else {
                return '';
            }

        }
    }

    public static function get_rulesinfo($seqid, $uuid)
    {
        $hit_rules = DB::select('hit_rules')->from('fraud_tongdun_rules')->where('seq_id', '=',
            $seqid)->and_where('uuid', '=', $uuid)->order_by('id', 'desc')->execute()->current();

        return $hit_rules;
    }

    public static function insertlog($data = array(), $result)
    {
        $tdlog_map = array(
            'provider', 'action', 'msg', 'req_data', 'resp_data', 'type', 'reference_id', 'create_time', 'update_time',
        );
        $td_map = array(
            'account_name', 'id_number', 'account_mobile', 'final_decision', 'final_score', 'hit_rules', 'seq_id',
            'success', 'create_time', 'user_id',
        );
        $result = json_decode($result, true);
        if ($result['success']) { //接口查询成功
            $td_data['account_name'] = $data['account_name'];
            $td_data['id_number'] = $data['id_number'];
            $td_data['account_mobile'] = $data['account_mobile'];
            $td_data['final_decision'] = $result['final_decision'];
            $td_data['final_score'] = $result['final_score'];
            if (isset($result['policy_set'])) {
                foreach ($result['policy_set'] as $k => $v) {
                    if (!isset($result['policy_set'][$k]['hit_rules'])) { //如果没有命中了此策略集
                        unset($result['policy_set'][$k]);
                    }
                }
                $td_data['hit_rules'] = json_encode($result['policy_set']);
            } else {
                $td_data['hit_rules'] = '';
            }
            $td_data['seq_id'] = $result['seq_id'];
            $td_data['success'] = $result['success'];
            $td_data['create_time'] = time();
            $td_data['user_id'] = $data['user_id'];
            list($id) = DB::insert('fraud_tongdun', $td_map)->values($td_data)->execute();
            $tdlog_data = array(
                'TongDun', 'Search',
                '调用TongDun接口：'.$td_data['final_decision'].'|'.$td_data['final_score'].'|'.$td_data['create_time'],
                json_encode($data), json_encode($result), 'TongDunAPI', $id, time(), time(),
            );
            $td_data['id'] = $id;
        } else {
            $td_data['account_name'] = $data['account_name'];
            $td_data['id_number'] = $data['id_number'];
            $td_data['account_mobile'] = $data['account_mobile'];
            $td_data['final_decision'] = '';
            $td_data['final_score'] = '';
            $td_data['seq_id'] = $result['seq_id'];
            $td_data['create_time'] = time();
            $tdlog_data = array(
                'TongDun', 'Search', '调用TongDun接口返回失败', json_encode($data), json_encode($result), 'TongDunAPI', 0,
                time(), time(),
            );
        }
        DB::insert('api_out_log', $tdlog_map)->values($tdlog_data)->execute();

        return $td_data;
    }

    public function get_task_tongdunrules_list($size = 50)
    {
        $rs = DB::select('id', 'response_data')->from('tongdun_data')->where('has_rules', '=',
            0)->limit($size)->execute()->as_array();

        return $rs;
    }

    public function get_task_tongdunrules_total()
    {
        $rs = DB::select(array(DB::expr('COUNT(*)'), 'total'))->from('tongdun_data')->where('has_rules', '=',
            0)->execute()->current();

        return isset($rs['total']) ? $rs['total'] : 0;
    }
}