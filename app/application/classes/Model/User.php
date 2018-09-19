<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/5/4
 * Time: 上午2:09
 */
class Model_User extends Model_Database
{

    const STATUS_NORMAL = 1;            //正常
    const STATUS_DENY = 2;                //禁止登录 已废弃 参见tc_user.allow_login
    const STATUS_LOANDENY = 3;            //禁止借款,允许登录
    const STATUS_DENYFOREVER = 4;        //永久拒绝,允许登录,拒绝授信,拒绝借款
    const STATUS_DENYTEMP = 5;          //暂时拒绝,允许登录,拒绝授信,拒绝借款
    const STATUS_DELETED = 9;            //已删除

    //登录开关
    const ALLOW_LOGIN__ALLOWED = 1;     //允许
    const ALLOW_LOGIN__DISALLOW = 2;    //禁止

    //降担保授信
    const CREDIT_AUTH_STATUS_READY = 1;     //降担保授信:准备就绪
    const CREDIT_AUTH_STATUS_ONSUBMIT = 2;  //降担保授信:开始提交
    const CREDIT_AUTH_STATUS_SUBMITED = 3;  //降担保授信:已提交
    const CREDIT_AUTH_STATUS_CHECKING = 4;  //降担保授信:审查中
    const CREDIT_AUTH_STATUS_VERIFIED = 5;  //降担保授信:验证通过
    const CREDIT_AUTH_STATUS_FAILED = 6;    //降担保授信:失败
    const CREDIT_AUTH_STATUS_BACK = 7;      //降担保授信:退回补充

    //基础授信
    const CREDIT_AUTH_BASE_READY = 11;     //基础授信:准备就绪
    const CREDIT_AUTH_BASE_ONSUBMIT = 12;  //基础授信:开始提交
    const CREDIT_AUTH_BASE_SUBMITED = 13;  //基础授信:已提交
    const CREDIT_AUTH_BASE_CHECKING = 14;  //基础授信:审查中
    const CREDIT_AUTH_BASE_VERIFIED = 15;  //基础授信:验证通过
    const CREDIT_AUTH_BASE_FAILED = 16;    //基础授信:失败
    const CREDIT_AUTH_BASE_BACK = 17;      //基础授信:退回补充


    const IDENTITY_STATUS_VERIFIED = 1;        //已验证
    const IDENTITY_STATUS_FAILED = 2;            //验证失败
    const IDENTITY_STATUS_READY = 3;            //待审核
    const IDENTITY_STATUS_CHECKING = 4;        //审核中

    const MOBILE_STATUS_VERIFIED = 1;        //已验证
    const MOBILE_STATUS_FAILED = 2;            //验证失败
    const MOBILE_STATUS_READY = 3;            //待审核
    const MOBILE_STATUS_CHECKING = 4;        //审核中


    const API_RESULT_IDENTITY_SUCCESS = "1000"; //身份证验证成功
    const IDENTITY_CHECK_MAX = 3;               //每个TOKEN只允许验证3次
    const LOGIN_CHECK_PER_DAY_MAX = 10;         //每个TOKEN每天只允许登录验证10次
    const RESETPWD_CHECK_PER_DAY_MAX = 5;       //每个TOKEN每天只允许 重置密码5次
    const RESETPWD_EXPIRE_PERIOD = 1800;        //验证成功后,修改密码的有效时间
    const EMAIL_CHECK_PER_DAY_MAX = 20;         //每个TOKEN每天只允许发送20次邮件
    const VERIFY_ERROR_MAX = 10;                //每个TOKEN 每个验证码只能错误 10次

    const CREDIT_EXPIRE_PERIOD = 15552000;         //降担保授信提交有效期(超过180天需要重新提交不管是不是已经提交了)
    const CREDIT_REJECT_EXPIRE_PERIOD = 2592000;   //降担保授信拒绝期(被拒30天后才可以重新提交,重新提交只能充填工作信息,家庭信息,紧急联系人)

    const REDUCE_ENSURE_BAIRONG_AUTOPASS = 700;//大于等于 自动过
    const REDUCE_ENSURE_BAIRONG_AUDIT = -1;//小于REDUCE_ENSURE_BAIRONG_AUTOPASS  大于等于REDUCE_ENSURE_BAIRONG_AUDIT 人工
    const REDUCE_ENSURE_BAIRONG_REJECT = -99;//小于 自动拒绝(负数不做拒绝)

    const REDUCE_ENSURE_RONG360_SCORE_AUTOPASS = 26900000;//小于等于 自动过 0.0729
    const REDUCE_ENSURE_RONG360_SCORE_AUDIT = 30980000;//大于REDUCE_ENSURE_RONG360_SCORE_AUTOPASS  小于等于REDUCE_ENSURE_RONG360_SCORE_AUDIT 人工
    const REDUCE_ENSURE_RONG360_SCORE_REJECT = 30980000;//大于 自动拒绝(负数不做拒绝)

    const REDUCE_ENSURE_TCREDIT_RISKRESULT_AUTOPASS = 1;//等于 自动过
    const REDUCE_ENSURE_TCREDIT_RISKRESULT_AUDIT = 3;// 等于 人工
    const REDUCE_ENSURE_TCREDIT_RISKRESULT_REJECT = 2;//等于 自动拒绝(负数不做拒绝)


    //用户状态名
    public $status_array = array(
        self::STATUS_NORMAL => '正常',
        self::STATUS_DENY => '禁止登录',
        self::STATUS_LOANDENY => '禁止借款',
        self::STATUS_DENYFOREVER => '永久拒绝',
        self::STATUS_DENYTEMP => '暂时拒绝',
        self::STATUS_DELETED => '已删除',
    );


    //被禁止的高危身份证号段
    public $deny_idcard = array(
        '3304', '3509', '3508', '3703', '1304',
    );


    //单条用户数据
    public function get_one($user_id)
    {
        return DB::select()->from('user')->where('id', '=', $user_id)->execute()->current();
    }


    public function get_one_by_array($array)
    {
        $query = DB::select()->from('user')->order_by('id', 'DESC')->limit(1);
        if (isset($array['mobile']) && $array['mobile']) {
            $query->and_where('mobile', '=', $array['mobile'])->and_where('validated_mobile', '=',
                Model_User::MOBILE_STATUS_VERIFIED);
        }
        if (isset($array['identity']) && $array['identity']) {
            $query->and_where('identity_code', '=', $array['identity'])->and_where('validated_identity', '=',
                Model_User::IDENTITY_STATUS_VERIFIED);
        }
        if (isset($array['id']) && $array['id'] > 0) {
            $query->and_where('id', '=', (int)$array['id']);
        }
        if (isset($array['status']) && $array['status']) {
            $query->and_where('status', '=', (int)$array['status']);
        }
        if (isset($array['identity_code']) && $array['identity_code']) {//today = strtotime(date('Y-m-d'))
            $query->and_where('identity_code', '=', $array['identity_code']);
        }
        if (isset($array['name']) && $array['name']) {
            $query->and_where('name', '=', $array['name']);
        }
        if (isset($array['allow_login']) && $array['allow_login']) {
            $query->and_where('allow_login', '=', $array['allow_login']);
        }

        return $query->execute()->current();

    }


    public function get_one_by_mobile($mobile, $allow_login = null)
    {
        if (!$mobile) {
            return false;
        }
        if ($allow_login === null) {
            return $this->get_one_by_array(array('mobile' => $mobile));
        }

        return $this->get_one_by_array(array('mobile' => $mobile, 'allow_login' => $allow_login));
    }

    public function get_one_by_identity($identity)
    {
        if (!$identity) {
            return false;
        }

        return $this->get_one_by_array(array('identity_code' => $identity));
    }


    /** 密码加密
     * @param $password
     * @param $salt
     * @return bool|string
     */
    public function password($password, $salt)
    {
        if (empty($password) || empty($salt)) {
            return false;
        }

        return md5($password.$salt);
    }


    /** 创建账号
     * @param array $array
     * @return bool
     */
    public function create($array = array())
    {
        if (!isset($array['mobile']) || !isset($array['password']) ||
            !isset($array['salt']) || !isset($array['validated_mobile']) || !isset($array['status'])
        ) {
            return false;
        }
        $mobile = $array['mobile'];
        $name = isset($array['name']) ? $array['name'] : '';
        $identity_code = isset($array['identity_code']) ? $array['identity_code'] : '';
        $password = isset($array['password']) ? $array['password'] : '';
        $salt = isset($array['salt']) ? $array['salt'] : '';
        $validated_mobile = isset($array['validated_mobile']) ? (int)$array['validated_mobile'] : 0;
        $validated_identity = isset($array['validated_identity']) ? (int)$array['validated_identity'] : 0;

        $sex = isset($array['sex']) ? $array['sex'] : '';
        $status = isset($array['status']) ? (int)$array['status'] : 0;
        $credit_auth = isset($array['credit_auth']) ? (int)$array['credit_auth'] : Model_User::CREDIT_AUTH_BASE_READY;
        $identity_face = isset($array['identity_face']) ? $array['identity_face'] : '';
        $reg_ip = isset($array['reg_ip']) ? $array['reg_ip'] : '';
        $reg_app = isset($array['reg_app']) ? $array['reg_app'] : '';
        $reg_unique_id = isset($array['unique_id']) ? trim($array['unique_id']) : '';
        $allow_login = isset($array['allow_login']) ? (int)$array['allow_login'] : Model_User::ALLOW_LOGIN__ALLOWED;
        $create_time = isset($array['create_time']) && $array['create_time'] > 0 ? (int)$array['create_time'] : time();
        $partner_code = isset($array['partner_code']) ? $array['partner_code'] : Def::PARTNER_CODE;


        list($insert_id, $affected_rows) = DB::insert("user", array(
            'mobile', 'name', 'identity_code', 'password', 'salt', 'validated_mobile', 'validated_identity', 'status',
            'credit_auth', 'identity_face', 'sex', 'reg_ip', 'reg_app', 'reg_unique_id', 'allow_login', 'create_time',
            'partner_code',
        ))
            ->values(array(
                $mobile, $name, $identity_code, $password, $salt, $validated_mobile, $validated_identity, $status,
                $credit_auth, $identity_face, $sex, $reg_ip, $reg_app, $reg_unique_id, $allow_login, $create_time,
                $partner_code,
            ))
            ->execute();

        return $insert_id;
    }

    /** 更改数据
     * @param $id
     * @param array $array
     * @return bool
     */
    public function update($id, $array = array())
    {
        if (!$id) {
            return false;
        }
        Lib::factory('TCCache_User')->user_id($id)->remove();//CACHE ----- [DELETE]
        return null !== DB::update('user')->set($array)->where('id', '=', $id)->execute();
    }


    /** 账号登录
     * @param int $token_id
     * @param int $user_id
     * @return bool
     */
    public function log_on($user_id = 0, $token_id = 0)
    {
        if ($token_id < 1 || $user_id < 1) {
            return false;
        }

        return Model::factory('Token')->update_by_id($token_id,
            array('user_id' => (int)$user_id, 'expire_time' => time() + Model_Token::LOGIN_PERIOD));
    }


    /** 账号退出
     * @param int $user_id
     * @param int $token_id
     * @return bool
     */
    public function log_out($user_id = 0, $token_id = 0)
    {
        if ($user_id < 1) {
            return false;
        }
        $query = DB::update('app_token')->set(array('user_id' => 0))->where('user_id', '=', $user_id);
        if ($token_id > 0) {
            $query->and_where('id', '=', $token_id);
        }

        return null !== $query->execute();
    }


    /** 登录密码检测
     * @param $mobile
     * @param $password
     * @return bool|int
     */
    public function log_chk($mobile, $password)
    {
        if (!$mobile || !$password) {
            return false;
        }
        $rs = DB::select()->from('user')
            ->where('mobile', '=', $mobile)
            ->and_where('validated_mobile', '=', Model_User::MOBILE_STATUS_VERIFIED)
            ->and_where('allow_login', '=', Model_User::ALLOW_LOGIN__ALLOWED)
            ->and_where('status', 'IN', array(
                Model_User::STATUS_NORMAL,
                Model_User::STATUS_LOANDENY,
                Model_User::STATUS_DENYFOREVER,
                Model_User::STATUS_DENYTEMP,
            ))//正常,禁止借款
            ->limit(1)->execute()->current();
        if ($rs) {
            $salt = $rs['salt'];
            if ($this->password($password, $salt) === $rs['password']) {
                return (int)$rs['id'];
            }
        }

        return false;
    }


    /** 手机登录密码检测
     * @param $mobile
     * @param $password
     * @return bool|int
     */
    public function log_mobile_password($mobile, $password)
    {
        if (!$mobile || !$password) {
            return false;
        }
        $rs = DB::select('id', 'mobile', 'password', 'salt')->from('user')
            ->where('mobile', '=', $mobile)
            ->and_where('validated_mobile', '=', Model_User::MOBILE_STATUS_VERIFIED)
            ->limit(1)
            ->execute()
            ->current();
        if ($rs) {
            $salt = $rs['salt'];
            if ($this->password($password, $salt) === $rs['password']) {
                return (int)$rs['id'];
            }
        }

        return false;
    }

    /** 更改密码
     * @param $mobile
     * @param $new_password
     * @return bool
     */
    public function change_password($mobile, $new_password)
    {
        if (!$mobile || !$new_password) {
            return false;
        }
        $rs = $this->get_one_by_array(array('mobile' => $mobile));
        if ($rs) {
            return $this->update($rs['id'], array('password' => $this->password($new_password, $rs['salt'])));
        }

        return false;
    }


    /** 创建附属表
     * @param $user_id
     * @return bool
     */
    public function create_after($user_id)
    {

        $fp = Model::factory('Finance_Profile')->create($user_id);
        $ci = Model::factory('CreditInfo_Step')->create($user_id);

        return true;
    }


    /** 更改用户的信用信息状态
     * @param $user_id
     * @param $status
     * @return bool
     */
    public function credit_auth($user_id, $status)
    {
        if ($user_id < 1 || !in_array($status, array(
                self::CREDIT_AUTH_STATUS_CHECKING,
                self::CREDIT_AUTH_STATUS_FAILED,
                self::CREDIT_AUTH_STATUS_ONSUBMIT,
                self::CREDIT_AUTH_STATUS_READY,
                self::CREDIT_AUTH_STATUS_SUBMITED,
                self::CREDIT_AUTH_STATUS_VERIFIED,

                self::CREDIT_AUTH_BASE_READY,
                self::CREDIT_AUTH_BASE_ONSUBMIT,
                self::CREDIT_AUTH_BASE_SUBMITED,
                self::CREDIT_AUTH_BASE_CHECKING,
                self::CREDIT_AUTH_BASE_VERIFIED,
                self::CREDIT_AUTH_BASE_FAILED,
                self::CREDIT_AUTH_BASE_BACK,
            ))
        ) {
            return false;
        }
        $update_array = array('credit_auth' => $status);

        return $this->update($user_id, $update_array);
    }


    /** 读取用户金融属性表
     * @param $user_id
     * @return mixed
     */
    public function get_finance_profile($user_id)
    {
        if ($rs = Model::factory('Finance_Profile')->get_one((int)$user_id)) {
            return $rs;
        } else {
            Model::factory('Finance_Profile')->create((int)$user_id);

            return Model::factory('Finance_Profile')->get_one((int)$user_id);
        }
    }


    public function get_bankcard($user_id)
    {
        return Model::factory('BankCard')->get_by_user_id((int)$user_id);
    }

    public function get_creditcard($user_id)
    {
        return Model::factory('CreditCard')->get_by_user_id((int)$user_id);
    }


    public function get_ci_step($user_id)
    {
        if ($rs = Model::factory('CreditInfo_Step')->get_all((int)$user_id)) {
            return $rs;
        } else {
            Model::factory('CreditInfo_Step')->create((int)$user_id);

            return Model::factory('CreditInfo_Step')->get_all((int)$user_id);
        }
    }

    public function get_pass_time($user_id){
        if ($rs = Model::factory('CreditInfo_Step')->get_pass((int)$user_id)) {
            return $rs;
        } else {
            Model::factory('CreditInfo_Step')->create((int)$user_id);

            return Model::factory('CreditInfo_Step')->get_pass((int)$user_id);
        }
    }

    // 根据用户id查询联系人信息
    public function get_user_contact_by_user_id($user_id)
    {
        $data = DB::select()->from('ci_contact')
            ->where('user_id', '=', $user_id)
            ->order_by('id', 'DESC')
            ->limit(2)
            ->execute()
            ->as_array();

        return $data;
    }

    //获取用户居住地
    public function get_address($user_id){
        $data = DB::select('province','city')->from('ci_home')
            ->where('user_id', '=', $user_id)
            ->execute()
            ->current();
        return $data;
    }


}