<?php defined('SYSPATH') or die();

/***
 * Class Model_Mongo
 * User: wangxuesong
 * Date: 17/12/13
 * Time: 上午11:07
 *  利用php 反射类插入空数据 (MongoDB\Driver\Manager::executeBulkWrite)
 *
 *
 */
class Model_MongoRef
{

    protected $_model;
    protected $_config;


    public static function factory($model,  $config='default')
    {

        return new Model_MongoRef($model,  $config);
    }

    public function __construct($model,  $config='default')
    {
        $this->_model = $model;
        $this->_config = $config;
    }

    /**
     * 增
     * @param string $wstring
     * @param int $wtimeout
     * @return mixed
     */
    public function save($wstring = \MongoDB\Driver\WriteConcern::MAJORITY, $wtimeout = 1000)
    {

        $document =$this->get_public_vars();
        try {
           return Model_Mongo::factory($this->_model,$this->_config)->save($document);
        } catch (\Exception $e) {
            $this->showError($e);
            return false;
        }

    }

    public function get_public_vars()
    {

        $ref = new ReflectionObject($this);
        $pros = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
        $result = array();
        foreach ($pros as $pro) {
            false && $pro = new ReflectionProperty();
            $result[$pro->getName()] = $pro->getValue($this);
        }

        return $result;
    }

    /**
     * 抛出异常
     * @param $e
     */
    public function showError($e)
    {
         $e->getMessage();
    }

}
