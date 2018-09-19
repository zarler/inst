<?php defined('SYSPATH') or die();


/***
 * Class Model_Mongo
 * User: wangxuesong
 * Date: 17/12/13
 * Time: 上午11:07
 *
 *
 *   * 增 * 删 * 改 * 查
 *
 *  新增数据：
 *
 *    Model_Mongo::factory('sites')->save(array $data);
 *
 *  删除数据：
 *
 *    Model_Mongo::factory('sites')->where("num","=",100)->remove();
 *
 *   修改数据：
 *      Model_Mongo::factory('sites')->where(array("num"=>100))->set(array("bit"=>11248))->update();
 *  查询数据：
 *     查询一条：  Model_Mongo::factory('sites')->where(array("num"=>100))->findOne();
 *     查询所有：  Model_Mongo::factory('sites')->where(array("num"=>100))->sort()->limit(2)->find()

 * 查询条件映射关系help
 *  '<'  => 'lt'
 *  '<=' => 'lte'
 *  '>'  => 'gt'
 *  '>=' => 'gte'
 *  '!=' => 'ne'
 *  '%'  => 'mod'
 *  'in'  => 'in'
 *  'notin' => 'nin'
 *  'or'    => 'or'
 *  'not'   =>  'not'
 */
class Model_Mongo extends Kohana_Model
{
	protected $_model;	
	protected $_collection;
	protected $_loaded = false;
	protected $_id;
	protected $_error;
	protected $database;
    protected $manager;
    protected $result;

    private $selects;
    private $wheres=[];
    private $updates;
    private $limit = 999999;
    private $offset = 0;
    private $sorts;

    protected $_reserved = NULL;

    protected  $options = [];




    public static function factory($model,  $config='default')
	{

		return new Model_Mongo($model,  $config);
	}
	
	public function __construct($model,  $config='default')
	{
		$model = strtolower($model);
		
		$this->_model = $model;

        $this->_collection = Mongo_Connection::instance($config);

        $this->database = $this->_collection->database();

        $this->manager = $this->_collection->manager();

	}

     /*** ——------------------------条件(where) ——------------------------*/
    /**
     * 条件
     * @param $wheres
     * @param null $value
     * @return $this
     */
    public function where($wheres, $value = null)
    {
        if (is_array($wheres)) {
            foreach ($wheres as $wh => $val) {
                if(is_array($val)){
                    foreach ($val as $key =>$value){
                        $this->wheres[$wh]["$".$key] = $value;
                    }
                }else{
                    $this->wheres[$wh] = $val;
                }
            }
        } else {
            $this->wheres[$wheres] = $value;
        }
        return $this;
    }

    public function where_in($field = "", $in = array())
    {
        $this->wheres[$field]['$in'] = $in;
        return $this;
    }

    public function where_in_all($field = "", $in = array())
    {
        $this->wheres[$field]['$all'] = $in;
        return $this;
    }

    public function where_or($wheres = array())
    {
        foreach ($wheres as $wh => $val) {
            $this->wheres['$or'][] = array($wh => $val);
        }
        return $this;
    }


    public function where_not_in($field = "", $in = array())
    {
        $this->wheres[$field]['$nin'] = $in;
        return $this;
    }

    public  function where_map($field = "",$cmd="",$value=""){

        if(empty($field) || empty($cmd)){
            return $this;
        }

        if($cmd!="="){
            $this->wheres[$field][$cmd] = $value;
        }else{
            $this->wheres[$field] = $value;
        }

        return $this;
    }


    /*** ——------------------------更新(set) ——------------------------*/
    /**
     * 更新条件
     * @param array $updates
     */
    public function setUpdates(array $updates)
    {
        $this->updates = $updates;
    }



    /**
     * @param $fields
     * @param null $value
     * @return $this
     */
    public function set($fields, $value = NULL)
    {
        if (is_string($fields)) {
            $this->updates['$set'][$fields] = $value;
        } elseif (is_array($fields)) {
            foreach ($fields as $field => $value) {
                $this->updates['$set'][$field] = $value;
            }
        }
        return $this;
    }

    /*** ——------------------------limit() ——------------------------*/



    public  function returnKey($key=true){

        $this->options['returnKey'] = true;

        return $this;
    }

    /**
     * 获取条数
     * @param null $val
     * @return int|null
     */
    public function limit($val=null)
    {

        if(empty($val)){
            $this->options['limit']=$this->limit;
        }else{

            $this->options['limit'] = $val;

        }

        return $this;
    }

    /**
     * @param array $array
     */
    public  function sort($array=[]){

        $this->options['sort'] = $array;

    }

    public  function  options($array=[]){

        $this->options = array_merge($this->options,$array);

        return $this;
    }




    /**
     * 增 (MongoDB\Driver\Manager::executeBulkWrite)
     * @param array $document
     * @param string $wstring
     * @param int $wtimeout
     * @return mixed
     */
    public function save($document = array(),$wstring = \MongoDB\Driver\WriteConcern::MAJORITY, $wtimeout = 1000)
    {

        try {

            $wc = new \MongoDB\Driver\WriteConcern($wstring, $wtimeout);
            $bulk = new \MongoDB\Driver\BulkWrite();
            $bulk->insert($document);

            $dbc = $this->database.'.'.$this->_model;

            $result =  $this->manager->executeBulkWrite($dbc, $bulk, $wc);


            $this->result = $result;

            //增加几条
            return $result->getInsertedCount();

        } catch (\Exception $e) {
            $this->showError($e);
            return false;
        }
    }


    /**
     * 删 (MongoDB\Driver\Manager::executeBulkWrite)
     * @param array $deleteOptions
     * @param string $wstring
     * @param int $wtimeout
     * @return mixed
     */
    public function remove($deleteOptions = ["limit" => 0], $wstring = \MongoDB\Driver\WriteConcern::MAJORITY, $wtimeout = 1000)
    {
        try {
            $wc = new \MongoDB\Driver\WriteConcern($wstring, $wtimeout);
            $bulk = new \MongoDB\Driver\BulkWrite();
            $filter = $this->wheres;
            if (count($filter) < 1 && $deleteOptions['limit'] == 1) {
                throw new \Exception('filter is error!');
            }
            $bulk->delete($filter, $deleteOptions);
            $dbc = $this->database.'.'.$this->_model;
            $result = $this->manager->executeBulkWrite($dbc, $bulk, $wc);
            $this->result = $result;
            //删除几条
            return $result->getDeletedCount();
        } catch
        (\Exception $e) {
            $this->showError($e);
            return false;
        }
    }


    /***
     * 更新 (MongoDB\Driver\Manager::executeBulkWrite)
     * @return int|null
     */
    public function update()
    {

        $updateOptions = ['multi' => true, 'upsert' => true];
        $wstring = \MongoDB\Driver\WriteConcern::MAJORITY;
        $wtimeout = 1000;

        try {
            $wc = new \MongoDB\Driver\WriteConcern($wstring, $wtimeout);
            $bulk = new \MongoDB\Driver\BulkWrite();
            $filter = $this->wheres;

            if (count($filter) < 1 && $updateOptions['multi'] == false) {
                throw new \Exception('filter is error!');
            }
            $newObj = $this->updates;
            $bulk->update(
                $filter,
                $newObj,
                $updateOptions
            );
            $dbc = $this->database.'.'.$this->_model;
            $result = $this->manager->executeBulkWrite($dbc, $bulk, $wc);
            $this->result = $result;
            return $result->getModifiedCount();
        } catch (\Exception $e) {
            $this->showError($e);
            return false;
        }
    }


    /**
     * 查 (MongoDB\Driver\Manager::executeQuery)
     * @return mixed
     */
    public function find()
    {
        $cursor = $this->findCursor();
        $cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
        return $cursor->toArray();
    }

    /**
     * 查 (MongoDB\Driver\Manager::executeQuery)
     * @return mixed
     */
    public function findCursor()
    {

        return $this->_find();
    }


    /**
     * 查询单条 (MongoDB\Driver\Manager::executeQuery)
     * @param null $id
     * @return mixed|null
     */
    public function findOne($id = null)
    {

        $cursor = $this->findOneCursor($id);
        $cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
        return $cursor->toArray();

    }

    /**
     * 查询单条 (MongoDB\Driver\Manager::executeQuery)
     * @param null $id
     * @return mixed|null
     */
    public function findOneCursor($id = null)
    {

        if ($id != null) {
            if(!is_object($id)){
                $this->where('_id', new \MongoDB\BSON\ObjectID($id));
            }else{
                $this->where('_id', $id);
            }
        }

        $this->offset = 0;
        $this->limit  = 1;
        return $this->_find();

    }


    protected  function _find(){

        try {


            $query = new \MongoDB\Driver\Query($this->wheres, $this->options);
            $dbc = $this->database.'.'.$this->_model;
            $documents = $this->manager->executeQuery($dbc, $query);

            return $documents;

        } catch (\Exception $e) {
            $this->showError($e);
            return false;
        }
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
