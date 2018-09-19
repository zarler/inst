<?php defined('SYSPATH') or die();

/***
 * Class Model_Mongo
 * User: wangxuesong
 * Date: 17/12/13
 * Time: 上午11:07
 *
 * 执行数据库命令 MongoDB::command
 *
 * Model_MongoCmd::factory($config)->execute($commands);
 *
 */
class Model_MongoCmd extends Kohana_Model
{

    protected $_collection;
    protected $_loaded = false;
    protected $_error;
    protected $database;
    protected $manager;
    protected $result;
    protected $_model;


    public static function factory($model,$config='default')
    {

        return new Model_MongoCmd($model,$config);
    }

    public function __construct($model,$config='default')
    {
        $model = strtolower($model);

        $this->_model = $model;
        $this->_collection = Mongo_Connection::instance($config);
        $this->database = $this->_collection->database();
    }


    /**
     * command
     * @param $cmd
     * @return mixed
     */
    public function execute($cmd=NULL)
    {


        try {
            $collection = $this->_getCol();
            $cursor = $collection ->command($cmd);
            $this->result = $cursor;
            return $cursor;
        } catch (\Exception $e) {
            $this->showError($e);
            return false;
        }
    }

    /**
     * $colName
     * @return mixed
     */
    private function _getCol(){
        return $this->manager->selectCollection($this->database,$this->_model);
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