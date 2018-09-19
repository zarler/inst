<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/1/6
 * Time: 上午11:04
 *
 *
 * 2016-1-9 by majin
 *  增加一个数组方法 export_array() 和 filter()
 *
 * 参数例子1 适用于一般数组
 *  $array = array(
 *          'head' => array('表头1','表头2',...),                      //表头
 *          'type' => 'array',
 *          'body' => array(                                          //表内容
 *                  '0'=> array('1','name','email',...),
 *                  '1'=> array('1','name','email',...),
 *                  )
 *              );
 * 参数例子2 适用于数据库查询返回的结果集
 *  *  $array = array(
 *          'head' => array('表头1','表头2',...),                     //表头
 *          'type' => 'database',                                    //内容类型:database => 查询数据库返回的结果集
 *          'body' => array(                                         //表内容
 *                  '0'=> array('id'=>'1', 'name'=>'name', 'email'=>'email',...),
 *                  '1'=> array('id'=>'2', 'name'=>'name2', 'email'=>'email2',...),
 *                  ),
 *          'field'=>array('id','name','email'),                     //使用字段,同时也将按此列顺序输出
 *              );
 *
 *
 *
 * 2016-1-11 by isNum
 *  增加 get_content()
 *
 * 2016-1-11 by majin
 *  增加 read_file()
 *
 *
 */
class Tool_CSV {

    public static function export_csv($filename,$data){
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
        die;
    }

    //支持两类数组 array database
    public static function export_array($filename=NULL,$array=NULL){
        if($filename===NULL || $array===NULL){
            return NULL;
        }
        $head = '';
        if(isset($array['head']) && count($array['head'])>0){
            foreach($array['head'] as $key =>$value){
                $head .= $head ? ','.self::filter($value) : self::filter($value);
            }
            $head .= $head ? "\n" :'';
            $head=iconv('utf-8','gb2312',$head);
        }
        $content = '';
        if(isset($array['type']) && $array['type']=='database'){
            if(isset($array['body']) && count($array['body'])>0) {
                foreach($array['body'] as $row) {
                    $colstr='';
                    if (isset($array['field'])) {
                        foreach($array['field'] as $key =>$col){//按 field 顺序将每行填充
                            if(isset($row[$col])) {
                                $col=iconv('utf-8','gbk',$row[$col]);
                                if(preg_match('/^\d{11,}$/',$col)){
                                    $colstr .=  $colstr ? "," . self::filter($col)."\t" : self::filter($col);
                                }else{
                                    $colstr .=  $colstr ? "," . self::filter($col) : self::filter($col);
                                }
                            }
                        }
                    } else {
                        foreach($row as $value){
                            $value=iconv('utf-8','gbk',$value);
                            if(preg_match('/^\d{11,}$/',$value)){
                                $colstr .=  $colstr ? "," . self::filter($value)."\t" : self::filter($value);
                            }else{
                                $colstr .=  $colstr ? "," . self::filter($value) : self::filter($value);
                            }
                        }
                    }
                    $content .=  $colstr."\n";
                }
            }
        }else{
            if(isset($array['body']) && count($array['body'])>0){
                foreach($array['body'] as $row) {
                    $colstr='';
                    foreach ($row as $value){
                        $value=iconv('utf-8','gbk',$value);
                        if(preg_match('/^\d{11,}$/',$value)){
                            $colstr .=  $colstr ? "," . self::filter($value)."\t" : self::filter($value);
                        }else{
                            $colstr .=  $colstr ? "," . self::filter($value) : self::filter($value);
                        }
                    }
                    $content .=  $colstr."\n";
                }
            }
        }

        self::export_csv($filename,$head.$content);

    }

    //按照标准CSV格式 过滤
    //字段中出现 半角逗号,需要用双引号包围字段.  半角双引号,转以为两个双引号用双引号包围字段.
    public static function filter($str){
        return (strpos($str,',')!==FALSE || strpos($str,'"')!==FALSE ) ? '"'.str_replace('"','""',$str).'"' : $str;
    }


    //带文件存在判断
    public static function read_file($file_path) {
        if(!file_exists($file_path)) {
            return FALSE;
        }
        return self::get_content($file_path);
    }



    public static function get_content($file_path){
        $file = fopen($file_path,'r');
        $file_list = array();
        $i = 0;
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            if($i != 0){
                $file_list[] = $data;
            }
            $i++;
        }
        fclose($file);
        return $file_list;
    }

}