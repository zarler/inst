<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * user：liujinsheng
 * 模版页面和变量混合 pdf专用
 * $val['content'] = $this->get_tpl_content(APPPATH.'/views/smarty/admin/index1.tpl',$list_var,2);
 * 用法: $val['content'] = $this->get_tpl_content(APPPATH.'/views/smarty/admin/CFCA/index.html',$val,2);
 * public function  get_tpl_content($src_template,$var_arr,$type=1){
        $templates = new PdfTemplate($src_template);
        if($var_arr){
            foreach($var_arr as $key => $value){
                $templates->set_var($key,$value);
            }
        }
        if($type==2){
            $templates->match2();
        }else{
            $templates->match();
        }
        return $templates->get_content();
    }
 *
 */
class PdfTemplate{
    protected $_url = 'http://127.0.0.1:7444';
    protected $var_arr = null;
    protected $content = null;
    protected $tpl_file = null;


    public function __construct($file_name=NULL) {
        $this->tpl_file  = $file_name;
        $this->set_content(file_get_contents($this->tpl_file));
    }
    public static function factory($file_name=NULL){
        return new TplTemplateArr($file_name);
    }
    public function url($url){
        $this->_url = $url;
        return $this;
    }
    /**********************************************************
     *  访问：public
     *  目的：这里增加发布变量的引擎
     *  参数:
     *		@param   string    $var_name     		变量名称
     *		@param   string    $var_name_value     	变量值
     *  返回：void
     *  备注：
     ***********************************************************/
    function set_var($var_name,$var_name_value){
        $this->var_arr[$var_name] = $var_name_value;
    }
    /**********************************************************
     *  访问：public
     *  目的：获取所有设置的变量数组
     *  参数:
     *  返回：void
     *  备注：
     ***********************************************************/
    function get_var(){
        return $this->var_arr;
    }

    /**********************************************************
     *  访问：public
     *  目的：读取模版文件并设置内容
     *  参数:
     *  返回：void
     *  备注：
     ***********************************************************/
    function match($contents=''){
        //$element_arr=T_arr();    //element目录下所有的文件名
        if(!file_exists($this->tpl_file)){
            $content = false;
        }else{
            #echo "<h1>{$this->tpl_file}</h1>";
            $content  = $contents ? $contents : $this->content;
            if(!$content){
                return "Error<!--Error：模版文件不存在 {$this->tpl_file}-->";
            }# end of if
            preg_match_all ("|_timecash_var_(.*)}|U",$content,$out, PREG_PATTERN_ORDER);
            if($out[1]){
                foreach($out[1] as $key=>$value){
                    $tmp_var_name    = $out[1][$key];
                    if(Lib::factory('String')->strExists($tmp_var_name,'.')){
                        $tmp_var_name_arr = explode('.',$tmp_var_name);
                        $new_tmp_var_name = $tmp_var_name_arr[0];
                        unset($tmp_var_name_arr[0]);
                        $tmp_var_name_value = $this->var_arr[$new_tmp_var_name];
                        foreach($tmp_var_name_arr as $key => $value){
                            if(is_object($tmp_var_name_value)){
                                $tmp_var_name_value = $tmp_var_name_value->$value;
                            }else{
                                $tmp_var_name_value = $tmp_var_name_value[$value];
                            }
                        }
                    }else{
                        $tmp_var_name_value = $this->var_arr[$tmp_var_name];
                    }
                    $content = str_replace('{$_timecash_var_'.$tmp_var_name.'}',$tmp_var_name_value,$content);
                }# end of foreach
            }# end of if

        }

        $this->set_content($content);
    }
    /**********************************************************
     *  日期：2013年5月22日
     *  访问：public
     *  目的：设置模版文件内容
     *  参数:
     *  返回：void
     *  备注：
     ***********************************************************/
    function set_content($content){
        $this->content = $content;
    }

    /**********************************************************
     *  日期：2013年5月22日
     *  访问：public
     *  目的：获取模版文件内容
     *  参数:
     *  返回：string
     *  备注：
     ***********************************************************/
    function get_content(){
        return $this->content;
    }

    /**********************************************************
     *  日期：2013年07月25日
     *  访问：public
     *  目的：显示模版文件内容
     *  参数:
     *  返回：string
     *  备注：
     ***********************************************************/
    function display(){
        echo $this->get_content();
    }
    function match2($contents=""){
        $this ->match($contents);
        $this->content  = $contents ? $contents : $this->content;
        #$this->content = preg_replace("/\r|\n|\r\n/",' ',$this->content);
        $out=array();
        preg_match_all ("|<!--foreach\((.*)\)-->(.*)<!--end foreach-->|Us",$this->content,$out, PREG_PATTERN_ORDER);
        //E($out);
        if(!$out) return '';
        //E($out);
        foreach($out[1] as $out_key => $out_value){
            $out2= array();
            #循环前提
            $temp_content = $this->content;
            $data_id=str_replace('data','',$out[1][$out_key]);
            $data_id=str_replace('[k:v]','',$data_id);
            preg_match_all ("|(.*)\[(.*):(.*)\]|U",$out[1][$out_key],$out2, PREG_PATTERN_ORDER);
            #循环数组
            $temp_arr = $this->var_arr[$out2[1][0]];
            #循环体内容
            $temp_inner=$out[2][$out_key];
            preg_match_all ('|'.$out2[1][0].'_(.*)}|U',$temp_inner,$out3, PREG_PATTERN_ORDER);
            if($out3){
                //e($out3);
                $var_arr= array();
                foreach ($out3[1] as $obj){
                    if(stristr($obj,'.')){	 #变量为对象
                        $arr=explode('.',$obj);
                        $var_arr[]=$arr[1];
                    }
                }
            }
            $cont=count($var_arr);
            $temp_str="foreach(\$temp_arr as \${$out2[2][0]} => \${$out2[3][0]})";
            $inner_html='';
            if($temp_arr){
                foreach ($temp_arr as $k => $v){
                    #	str_replace('','',$temp_inner)
                    $temp_html=$temp_inner;
                    foreach($var_arr as $t_v){
                        $temp_html = str_replace('{$'.$out2[1][0].'_'."v.{$t_v}".'}',$v->$t_v,$temp_html);
                    }
                    $inner_html.=$temp_html;
                }
                $this->set_content(str_replace($out[0][$out_key],$inner_html,$temp_content ));
            }else{
                $this->set_content(str_replace($temp_inner,'<tr><td  colspan="'.(int)$cont.'"><div style="text-align: center; margin:95px;font:12px Microsoft YaHei;">暂无信息</div></td></tr>',$temp_content ));
            };
        }
    }

}