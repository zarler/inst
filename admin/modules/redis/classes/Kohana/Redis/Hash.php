<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Redis
 * Type: 	hashtable
 * Editor: 	majin
 * Updated:	2016-07-22
 * Des:		哈希表 pk(key1=>value1,key2=>value2)
 *
 * Example:
    Redis_Hash::instance()->set('h1',array('key3'=>'kkk1','key4'=>'kkkk'));
    Redis_Hash::instance()->get('h1');
    Redis_Hash::instance()->del_field('h1','key3');
    Redis_Hash::instance()->exists_field('h1','key4');
    Redis_Hash::instance()->get('h1');
    Redis_Hash::instance()->get_field('h1',array('key3','key4'));
 *
 *
 */
class Kohana_Redis_Hash extends Kohana_Redis_Connect {

	public static $type = 'Hash';


    public static function instance($name = NULL, array $config = NULL){
        Kohana_Redis::$type = Kohana_Redis_Hash::$type;
        return parent::instance($name,$config);
    }

    /** 是否存在
     * @param null $key | array $key
     * @return bool If the key exists, return TRUE, otherwise return FALSE.
     * @throws Redis_Exception
     */
    public function exists($key=NULL) {
        if($key===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        return $this->_connection->exists($key);
    }



    /** 字段是否存在
	 * @param null $key
     * @param null $field
	 * @return bool : If the member exists in the hash table, return TRUE, otherwise return FALSE.
	 * @throws Redis_Exception
	 */
	public function exists_field($key=NULL,$field=NULL) {
		if($key===NULL || $field===NULL){
			return FALSE;
		}
		$this->_connection or $this->connect();
        return $this->_connection->hExists($key,$field);
	}


	/** 类型
	 * @param null $key
	 * @return bool | Depending on the type of the data pointed by the key, this method will return the following
	 * value:
	 * 		string: Redis::REDIS_STRING set: Redis::REDIS_SET list: Redis::REDIS_LIST zset: Redis::REDIS_ZSET
	 * 		hash: Redis::REDIS_HASH
	 * 		other: Redis::REDIS_NOT_FOUND
	 * @throws Redis_Exception
	 */
	public function type($key=NULL) {
		if($key===NULL){
			return FALSE;
		}
		$this->_connection or $this->connect();
		return $this->_connection->type($key);
	}

	/** 元素数
	 * @param null $key
	 * @return bool | Int
	 * @throws Redis_Exception
	 */
	public function count($key=NULL) {
		if($key===NULL){
			return FALSE;
		}
		$this->_connection or $this->connect();
		return $this->_connection->hLen($key);
	}


	/** 单个hashtable
	 * @param null $key
	 * @return bool | array
	 * @throws Redis_Exception
	 */
	public function get($key=NULL) {
		if($key===NULL){
			return FALSE;
		}
		$this->_connection or $this->connect();
		return $this->_connection->hGetAll($key);
	}


    /** hashtable 中的字段
     * @param null $key
     * @param null $field | array $field
     * @return bool | array 如果某个字段不存在,字段值返回FALSE
     * @throws Redis_Exception
     *
     *
     */
    public function get_field($key=NULL,$field=NULL) {
        if($key===NULL || $field===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        if(is_array($field)){
            return  $this->_connection->hMGet($key,$field);
        }
        return $this->_connection->hGet($key,$field);
    }




	/** 单个设置
	 * @param null $key
	 * @param $value
	 * @param null $params
	 * @return bool
	 * @throws Redis_Exception
	 */
	public function set($key=NULL, $value=NULL, $params=NULL) {
		if($key===NULL || $value===NULL ){
			return FALSE;
		}
		$this->_connection or $this->connect();
        if(is_array($value)){
            if($params!==NULL){
                return $this->_connection->hMSet($key, $value, $params);
            }
            return $this->_connection->hMSet($key, $value);

        }
		if($params!==NULL){
			return $this->_connection->hSet($key, $value, $params);
		}else{
			return $this->_connection->hSet($key, $value);
		}
	}




    /** 删除字段
     * @param null $key
     * @param null $field
     * @return bool | Long Number of keys deleted.
     * @throws Redis_Exception
     */
    public function del_field($key=NULL,$field=NULL) {
        if($key===NULL || $field===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        return $this->_connection->hDel($key,$field);

    }



    /** 删除
	 * @param null $key | array $keys 支持数组可以删除多个
	 * @return bool | Long Number of keys deleted.
	 * @throws Redis_Exception
	 */
	public function del($key=NULL) {
		if($key===NULL){
			return FALSE;
		}
		$this->_connection or $this->connect();
		return $this->_connection->delete($key);

	}


	/** 设置过期时间
	 * @param null $key
	 * @param $seconds
	 * @return bool
	 * @throws Redis_Exception
	 */
	public function expire($key=NULL, $seconds) {
		if($key===NULL){
			return FALSE;
		}
		$this->_connection or $this->connect();
		return $this->_connection->setTimeout($key, $seconds);
	}


    /** 搜索KEY
     * @param null $key
     * @return bool | array
     * @throws Redis_Exception
     * find('user_*');//搜索user_开头的键
     * find('*');//搜索全部键
     */
    public function find($key=NULL) {
        if($key===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        return $this->_connection->keys($key);
    }


    /** 便利KEY下的所有字段
     * @param null $key
     * @return bool | array
     * @throws Redis_Exception
     */
    public function field($key=NULL) {
        if($key===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        return $this->_connection->hKeys($key);
    }


    /** 更改键名
     * @param null $key
     * @param null $newkey
     * @return bool
     * @throws Redis_Exception
     */
    public function rename($key=NULL,$newkey=NULL){
        if($key===NULL || $newkey===NULL){
            return FALSE;
        }
        $this->_connection or $this->connect();
        return $this->_connection->rename($key,$newkey);
    }


    /** 利用管道 或者 事务 批量添加
     * @param int $mode [Redis::PIPELINE|Redis::MULTI]
     * @param null $array [['key'=>'key1','value'=[],'params'=NULL],['key'=>'key2','value'=[],'params'=NULL]]
     * @return mixed
     */
    public function multi_set($mode=Redis::PIPELINE,$array=NULL){
        if($array===NULL ){
            return FALSE;
        }
        $this->_connection or $this->connect();
        $multi = $this->_connection->multi($mode);
        foreach ($array as $line) {

            if(isset($line['value']) ){
                if(is_array($line['value'])){
                    if(isset($line['params']) && $line['params']!==NULL){
                        $multi->hMSet($line['key'], $line['value'], $line['params']);
                    }
                    $multi->hMSet($line['key'], $line['value']);
                }
                if($line['params']!==NULL){
                    $multi->hSet($line['key'], $line['value'], $line['params']);
                }else{
                    $multi->hSet($line['key'], $line['value']);
                }
            }

        }
        return $multi->exec();
    }




} // End
