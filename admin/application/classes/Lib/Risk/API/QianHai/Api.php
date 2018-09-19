<?php
/**
 * Created by PhpStorm.
 * User: yangge
 * Date: 2018/1/10
 * Time: 下午3:04
 */
class Lib_Risk_API_QianHai_Api extends Lib_Common
{
    private $user_id = 0;

    // 初始化用户
    public function user_id($user_id){
        $this->user_id = $user_id;
        return $this;
    }
    public function execute($info){
        $colusion = Model::factory("QianHai")->getOne($this->user_id);
        if($colusion){
            if($colusion['status'] == Model_QianHai::USER_QIANHAI_STATUS_PASS){
                return true;
            }
            return false;
        }
        $response_data = Lib::factory("Risk_API_QianHai_Api_Client")->execute($info);
        return $response_data;
        exit;


        if(isset($result['status']) && $result['status'] == true){
            Model::factory("QianHai")->create($this->user_id,$data,$response_data);
            Model::factory("QianHai")->create_short($this->user_id,Model_QianHai::USER_QIANHAI_STATUS_INIT);
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => ['status'=>true]]);
        }
        if(!$result){
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => ['status'=>false]]);
        }else{
            Model::factory("QianHai")->create($this->user_id,$data,$response_data);
            $res = TRUE;
            $resmark = array("B1","B2","B3");
            foreach ($response_data['data']['records'] as $key=>$val){
                if(in_array($val['rskMark'],$resmark)){
                    $res = FALSE;
                }
                if($val['rskScore']>=10 && $val['rskScore']<=45){
                    $res = FALSE;
                }
            }
            if($res){
                Model::factory("QianHai")->create_short($this->user_id,Model_QianHai::USER_QIANHAI_STATUS_PASS);
                return true;
            }else{
                Model::factory("QianHai")->create_short($this->user_id,Model_QianHai::USER_QIANHAI_STATUS_REJECT);
                return false;
            }
            $this->response(true, ['result' => Lib_Common::LIB_COMMON_API_RESULT_SUCCESS, 'message' => ['status'=>true]]);
        }
    }
}