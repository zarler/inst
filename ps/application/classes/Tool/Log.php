<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/2/15
 * Time: 下午6:33
 *
 * 套用KOHANA日志格式
 *
 * $log = new Tool_Log(DOCROOT.'/protected/logs');
 * $log->write(array(array('body'=>$string,'time'=>time())),"time --- REQUEST: \r\nbody");
 *
 *
 *
 */
class Tool_Log extends Log_Writer {


    protected $_directory;
    protected $_format='time --- level: body in file:line';


    public function __construct($directory=NULL)
    {
        if($directory){
            $this->directory($directory);
        }
    }

    //检测创建目录
    public function directory($directory)
    {
        if ( ! is_dir($directory) OR ! is_writable($directory))
        {
            throw new Kohana_Exception('Directory :dir must be writable',
                array(':dir' => Debug::path($directory)));
        }
        // Determine the directory path
        $this->_directory = realpath($directory).DIRECTORY_SEPARATOR;
    }


    /**
     * 写日志目录结构与文件
     *  `YYYY/MM/DD.log.php`
     *调用
     *     $writer->write($messages,$format);
     *
     * @param   array   $messages
     * @param   string  $format_message
     * @return  void
     */
    public function write(array $messages , $format_message=NULL)
    {
        // Set the yearly directory name
        $directory = $this->_directory.date('Y');

        if ( ! is_dir($directory))
        {
            // Create the yearly directory
            mkdir($directory, 02777);

            // Set permissions (must be manually set to fix umask issues)
            chmod($directory, 02777);
        }

        // Add the month to the directory
        $directory .= DIRECTORY_SEPARATOR.date('m');

        if ( ! is_dir($directory))
        {
            // Create the monthly directory
            mkdir($directory, 02777);

            // Set permissions (must be manually set to fix umask issues)
            chmod($directory, 02777);
        }

        // Set the name of the log file
        $filename = $directory.DIRECTORY_SEPARATOR.date('d').EXT;

        if ( ! file_exists($filename))
        {
            // Create the log file
            file_put_contents($filename, Kohana::FILE_SECURITY.' ?>'.PHP_EOL);

            // Allow anyone to write to log files
            chmod($filename, 0666);
        }
        if($format_message){
            foreach ($messages as $message) {
                // Write each message into the log file
                if(!isset($message['level'])){
                    $message['level'] = Kohana_Log::INFO;
                }
                file_put_contents($filename, PHP_EOL . $this->format_message($message,$format_message), FILE_APPEND);
            }
        }else {
            foreach ($messages as $message) {
                // Write each message into the log file
                if(!isset($message['level'])){
                    $message['level'] = Kohana_Log::INFO;
                }
                file_put_contents($filename, PHP_EOL . $this->format_message($message), FILE_APPEND);
            }
        }
    }




}



