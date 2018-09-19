<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * 从database中查询所需执行的crontab
 * 在服务器上执行,装入crontab
 * 每分钟执行一次,如有修改,装入新加入的task
 */

class Task_DBToCron extends Minion_Task {


    const TMP_FILE_NAME = "/tmp/cron_file.conf";
    const PROJECT_BEGIN_TAG = "#inst_begin#";
    const PROJECT_END_TAG = "#inst_end#";

    protected $_options = array(
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function _execute(array $param)
    {
        while(true){
            $this->getDbCmdWriteCron();
            sleep(30);
        }
    }

    public function getDbCmdWriteCron()
    {
        $cron = DB::select()->from(DB::expr("__crontab"))->where("status","=",1)//status:{1:可执行,0:不可执行}
            ->execute()->as_array();
        $cmd_arr = [];
        if (!empty($cron)){
            foreach ($cron as $key => $value) {
                $cmd_arr[] = $value['time_format'] . " " . $value['task'];

                /**
                 * 更新执行task的时间和结果,取后五行
                 */
                if(strpos($value['task'], '>') !== false){
                    $log_file = trim(explode(">", $value['task'])[1]);
                    if(file_exists($log_file)){
                        //清除之前的stat缓存
                        clearstatcache();
                        $file_attr = stat($log_file);
                        //清除之前的exec缓存
                        unset($exec_return_arr);
                        @exec("tail -n 5 ".$log_file, $exec_return_arr, $result);
                        if($result == 0){
                            DB::update(DB::expr("__crontab"))
                                ->set(['last_execute_time'=>$file_attr['mtime'],'last_execute_result'=>implode("\n",$exec_return_arr)])
                                ->where("id","=",$value['id'])
                                ->execute();
                        }
                    }
                }

            }
        }
        // 保留之前的crontab, 在当前项目中的起始标签和结束标签内覆盖之前的task
        unset($exec_cron_arr);
        exec("crontab -l", $exec_cron_arr, $result_cron);
        if(!empty($exec_cron_arr)){
            foreach ($exec_cron_arr as $key => $value) {
                if($value == self::PROJECT_BEGIN_TAG){
                    $begin = $key;
                }
                if($value == self::PROJECT_END_TAG){
                    $end = $key;
                }
            }
        }
        if(isset($begin) && isset($end)){
            for ($i = $begin; $i <= $end; $i ++) {
                unset($exec_cron_arr[$i]);
            }
        }
        $new_cron_arr = array_merge($exec_cron_arr, [self::PROJECT_BEGIN_TAG], $cmd_arr, [self::PROJECT_END_TAG]);
        // 写入临时文件,并通过系统调用crontab读取临时文件,执行新的crontab,并删除临时文件
        file_put_contents(self::TMP_FILE_NAME, implode("\n", $new_cron_arr));
        exec("crontab ".self::TMP_FILE_NAME);
        unlink(self::TMP_FILE_NAME);
    }



}

