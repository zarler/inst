<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 16/3/8
 * Time: 上午12:25
 */
class MyLog extends Log_Writer {

    /**
     * @var  string  Directory to place log files in
     */
    protected $_directory;
    protected $_format='time --- level: body in file:line';

    /**
     * Creates a new file logger. Checks that the directory exists and
     * is writable.
     *
     *     $writer = new Log_File($directory);
     *
     * @param   string  $directory  log directory
     * @return  void
     */
    public function __construct($directory=NULL)
    {
        if($directory){
            $this->directory($directory);
        }
    }

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
     * Writes each of the messages into the log file. The log file will be
     * appended to the `YYYY/MM/DD.log.php` file, where YYYY is the current
     * year, MM is the current month, and DD is the current day.
     *
     *     $writer->write($messages);
     *
     * @param   array   $messages
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



