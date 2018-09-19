<?php
/*
 * Created on 2016-7-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  class Thelog {
  	  /**
  	   * $str 日志内容
  	   * $dir 日志打印位置，根据情况进行修改
  	   */
      public static function printLog($str,$dir="phpIfCall.log"){
//          $log_id = Model::factory('Log')->create(
//              [
//                  'provider' => 'Geo',
//                  'action' => 'Index',
//                  'req_data' => date("[Y-m-d H:i:s]")." - [".$_SERVER['REQUEST_URI']."] : ".$str."\n",
//                  'type' => 'index',
//                  'reference_id' => 0,
//              ]
//          );
      	//date_default_timezone_set('PRC'); //中国时区
        //error_log(date("[Y-m-d H:i:s]")." - [".$_SERVER['REQUEST_URI']."] : ".$str."\n",3,$dir);
      }
  }
?>
