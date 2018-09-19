<?php defined('SYSPATH')  or die();


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
	protected $_database;
    protected $_manager;
    protected $result;
    protected $_cmd='$';

    private $wheres=[];
    private $updates;
    private $limit = 999999;
    private $sorts;

    protected $_reserved = NULL;

    protected  $options = ['W'=>false,'fsync'=>false];




    public static function factory($model,  $config='default')
	{

		return new Model_Mongo($model,  $config);
	}
	
	public function __construct($model,  $config='default')
	{
		$model = strtolower($model);
		
		$this->_model = $model;

        $this->_collection = Mongo_Connection::instance($config);

        //获取当前连接的数据库
        $this->_database = $this->_collection->database();
        $this->_manager = $this->_collection->manager();

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
            $this->updates[$fields] = $value;
        } elseif (is_array($fields)) {
            foreach ($fields as $field => $value) {
                $this->updates[$field] = $value;
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

        if(!empty($val)){

            $this->limit = $val;

        }

        return $this;
    }

    /**
     * 排序
     * @param array $array
     * @return $this
     */
    public  function sort($array=[]){

        $this->sorts = $array;
        return $this;
    }

    public  function  options($array=[]){

        $this->options = array_merge($this->options,$array);

        return $this;
    }


    public function exception($error=false){

        $this->_error= $error;
        return $this;
    }




    /**
     *  增 (MongoCollection::insert)
     * @param array $document
     * @return bool
     */

    public function save($document = array())
    {

        if(empty($document)){

            return false;
        }


        try {

            if(!empty($this->_database) || !empty($this->_manager)){

                $collection = $this->_getCol();
                $collection ->insert($document,$this->options);

                return isset($document['_id'])?$document['_id']:false;
            }

            return false;

        } catch (\Exception $e) {

            $this->showError($e);

            return false;
        }
    }


    /**
     *  删（MongoCollection::remove）
     * @param bool $justOne
     * @return bool
     */

    public function remove($justOne=true)
    {


        $this->options['justOne']    = $justOne;


        try {

            if(!empty($this->_database) || !empty($this->_manager)){

                $collection = $this->_getCol();

                $collection->remove($this->wheres,$this->options);

                return true;

            }

            return false;

        } catch
        (\Exception $e) {

            $this->showError($e);

            return false;
        }
    }


    /***
     * 更新 (MongoCollection::save)
     * @return int|null
     */
    public function update($option='set',$upAll=false,$upsert=true)
    {

        $this->options['multiple']  = $upAll;
        $this->options['upsert']    = $upsert;

        try {

            if(!empty($this->_database) || !empty($this->_manager)){

                $collection = $this->_getCol();

                if($option != 'replace'){
                    $this->updates = array($this->_cmd($option) => $this->updates);
                }

                $collection->update($this->wheres,$this->updates,$this->options);

                return true;
            }

            return false;

        } catch
        (\Exception $e) {

            $this->showError($e);

            return false;
        }
    }


    /**
     * 查 (MongoCollection::find)
     * @return mixed
     */
    public function find()
    {



        try {

            if(!empty($this->_database) || !empty($this->_manager)){

                $collection = $this->_getCol();

                $rs =  $collection->find($this->wheres);


                if($this->sorts && is_array($this->sorts)){
                    $rs->sort($this->sorts);
                }


                if($this->limit){
                    $rs->limit($this->limit);
                }



                $result = array();
                foreach ($rs as $value) {
                    $result[] = $this->_parseArr($value);
                 }

                 return $result;
            }

            return false;


        } catch
        (\Exception $e) {

            $this->showError($e);

            return false;
        }

    }


    /**
     * 统计文档记录数
     * @param array $query
     * @param int $limit
     * @param int $skip
     * @return int
     */
    public function count($query=array(),$limit=0,$skip=0){



        try {

            if(!empty($this->_database) || !empty($this->_manager)){

                $collection = $this->_getCol();

                $count = $collection->count($query,$limit,$skip);

                return $count;

            }

            return false;


        } catch
        (\Exception $e) {

            $this->showError($e);

            return false;
        }

    }

    /**
     * 查询单条 (MongoCollection::findOne)
     * @return mixed|null
     */
    public function findOne()
    {

        try {

            if(!empty($this->_database) || !empty($this->_manager)){

                $collection = $this->_getCol();

                $rs = $collection->findOne($this->wheres);

                return $rs;

            }

            return false;


        } catch
        (\Exception $e) {

            $this->showError($e);

            return false;
        }

    }


    /**
     * $colName
     * @return mixed
     */
    private function _getCol(){
        return $this->_manager->selectCollection($this->_database,$this->_model);
    }



    /**
     * 解析数组
     * @param $arr
     * @return mixed
     */
    private function _parseArr($arr){
        if(!empty($arr)) {
            $ret = (array)$arr['_id'];
            $arr['_id'] = $ret['$id'];
        }
        return $arr;
    }

    /**
     * 返回命令或命令前缀
     *
     * @param string $option 命令，如果为空时则返回命令前缀
     *
     * @return string
     */
    public function _cmd($option=''){
        // 只返回命令前缀
        if($option == ''){
            return $this->_cmd;
        }
        // 如果是操作符
        if(isset($this->_condMap[$option])){
            $option = $this->_condMap[$option];
        }
        return $this->_cmd.$option;
    }


    /**
     * 抛出异常
     * @param $e
     */
    public function showError($e)
    {

        if($this->_error){
            echo  $this->showError($e);
        }
    }


    public  function  __destruct()
    {
        if($this->_manager){
            $this->_manager->close();
        }

    }

}
