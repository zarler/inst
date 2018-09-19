<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Redis core.
 *
 * You may get a redis instance using `Redis_String::instance('name')` where
 * name is the [config](redis/config) group.
 *
 */

abstract class Kohana_Redis {

	/**
	 * @var  string  default instance name
	 */
	public static $default = 'default';

	/**
	 * @var  array  Redis instances
	 */
	public static $instances = array();


	public static $type = 'String';


	/**
	 * Get a singleton Redis instance. If configuration is not specified,
	 * it will be loaded from the redis configuration file using the same
	 * group as the name.
	 *
	 *     // Load the default redis
	 *     $rd = RedisLibrary::instance();
	 *
	 *     // Create a custom configured instance
	 *     $rd = RedisLibrary::instance('custom', $config);
	 *
	 * @param   string   $name    instance name
	 * @param   array    $config  configuration parameters
	 * @return  Redis
	 */
	public static function instance($name = NULL, array $config = NULL)
	{
		if ($name === NULL)
		{
			// Use the default instance name
			$name = Kohana_Redis::$default;
		}

		$redis_name = $name.'_'.Kohana_Redis::$type;

		if ( ! isset(Kohana_Redis::$instances[$redis_name]))
		{
			if ($config === NULL)
			{
				// Load the configuration for this redis
				$config = Kohana::$config->load('redis')->$name;
			}

			if ( ! isset($config['connection']['hostname']))
			{
				throw new Kohana_Exception('Redis not defined in :name configuration',
					array(':name' => $name));
			}


			// Set the driver class name
			$driver = 'Redis_'.Kohana_Redis::$type;//var_dump($driver);

			// Create the redis connection instance
			$driver = new $driver($name, $config);

			// Store the redis instance
			Kohana_Redis::$instances[$redis_name] = $driver;
		}

		return Kohana_Redis::$instances[$redis_name];
	}

	/**
	 * @var  string  the last query executed
	 */
	public $last_query;

	// Instance name
	protected $_instance;

	// Raw server connection
	protected $_connection;

	// Configuration array
	protected $_config;

	/**
	 * Stores the redis configuration locally and name the instance.
	 *
	 * [!!] This method cannot be accessed directly, you must use [Redis::instance].
	 *
	 * @return  void
	 */
	public function __construct($name, array $config)
	{
		// Set the instance name
		$this->_instance = $name;

		// Store the config locally
		$this->_config = $config;
	}

	/**
	 * Disconnect from redis when the object is destroyed.
	 *
	 *     // Destroy the redis instance
	 *     unset(RedisLibrary::instances[(string) $rd], $rd);
	 *
	 * [!!] Calling `unset($rd)` is not enough to destroy the redise, as it
	 * will still be stored in `RedisLibrary::$instances`.
	 *
	 * @return  void
	 */
	public function __destruct()
	{
		$this->disconnect();
	}

	/**
	 * Returns the redis instance name.
	 *
	 *     echo (string) $rd;
	 *
	 * @return  string
	 */
	public function __toString()
	{
		return $this->_instance;
	}

	/**
	 * Connect to redis. This is called automatically when the first
	 * query is executed.
	 *
	 *     $rd->connect();
	 *
	 * @throws  Redis_Exception
	 * @return  void
	 */
	abstract public function connect();

	/**
	 * Disconnect from redis. This is called automatically by [RedisLibrary::__destruct].
	 * Clears the redis instance from [RedisLibrary::$instances].
	 *
	 *     $rd->disconnect();
	 *
	 * @return  boolean
	 */
	public function disconnect()
	{
		unset(Kohana_Redis::$instances[$this->_instance]);

		return TRUE;
	}
	
	public function expire($key, $seconds) 
	{
	
	}

} // End
