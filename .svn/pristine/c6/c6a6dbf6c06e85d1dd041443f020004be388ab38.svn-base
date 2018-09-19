<?php defined('SYSPATH') or die();

class Mongo_Connection
{
    protected static $_instance=[];

    protected $_config;
    protected $hosts;
    protected $host;
    protected $port = 27017;

    protected $username;
    protected $password;
    protected $replicaSet;
    protected $database;
    private $dsn;
    protected $_conn;
    protected $_db;
    protected $manager;
    protected $maxPoolSize=2;

    protected $debug;

    /**
     * 预处理
     */
    private function config($name)
    {
        if(!$name) {
            $name = 'default';
        }
        //默认调用配置default
        $this->_config = Kohana::$config->load('mongodb.'.$name);

        //主从配置集群
        if (isset($this->_config['hosts'])) {
            $this->hosts = $this->_config['hosts'];
        }

        //单机配置集群
        if (isset($this->_config['host'])) {
            $this->host = $this->_config['host'];
        }
        if (isset($this->_config['port']) && $this->_config['port']>0 ) {
            $this->port = trim($this->_config['port']);
        }


        if (isset($this->_config['username'])) {
            $this->username = trim($this->_config['username']);
        }
        if (isset($this->_config['password'])) {
            $this->password = trim($this->_config['password']);
        }
        if (isset($this->_config['database'])) {
            $this->database = trim($this->_config['database']);
        }

        //replicaSet主从复制配置组
        if (isset($this->_config['replicaSet'])) {
            $this->replicaSet = $this->_config['replicaSet'];
        }

       if (isset($this->_config['maxPoolSize'])) {
           $this->maxPoolSize = $this->_config['maxPoolSize'];
       }


    }

    public function __construct($config='default')
    {

        $this->config($config);

        //transition 转换为url模式
        $this->dsn = 'mongodb://';
        if($this->username && $this->password){
            $this->dsn .= "{$this->username}:{$this->password}@";
        }elseif($this->username){
            $this->dsn .= "{$this->username}}@";
        }

        $hostlist ='';
        if($this->hosts) {
            $hosts=[];
            foreach ($this->hosts as $h) {
                if(!isset($h['host']) || !$h['host']) {
                    continue;
                }
                $hosts[] = $h['host'].':'. (isset($h['port']) ? $h['port'] : $this->port);
            }
            $hostlist = implode(',',$hosts);

        }elseif($this->host) {
            $hostlist = $this->host.":".$this->port;
        }
        $this->dsn .= $hostlist;
        $this->dsn .= "/{$this->database}";

        //options 可选参数
        $options = '?';
        if($this->replicaSet) {
            $options .= "replicaSet={$this->replicaSet}";
        }


        if($this->maxPoolSize && $this->replicaSet){
            $options .= "&maxPoolSize={$this->maxPoolSize}";
        }else{
            $options .= "maxPoolSize={$this->maxPoolSize}";
        }


        //可支持多种参数
        if($options!='?') {
            $this->dsn .= $options;
        }




        try {
            $this->manager = new \MongoDB\Driver\Manager($this->dsn);
        } catch (\Exception $e) {
            $this->showError($e);
            return false;
        }

    }

    public static function instance($config='default')
    {

        if(isset(self::$_instance[$config]))
        {
            return self::$_instance[$config];
        }
        self::$_instance[$config] = new Mongo_Connection($config);
        return self::$_instance[$config];

    }

    public function db()
    {

        //return $this->manager->{$this->database};
    }

    public  function  database()
    {

        return $this->database;
    }

    public  function manager()
    {

        return $this->manager;
    }

    public function dsn()
    {
        return $this->dsn;
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


