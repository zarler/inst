<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: isNum
 * Date: 16/5/27
 * Time: 上午10:14
 */

include_once realpath(dirname(__FILE__))."/config.php";

class GongAnBu_XML {

    private $Dom;

    private $stream;

    public function __construct () {
        $this->build_xml_header();
    }

    public function nciicCheck($data){

        $ROW_item = $this->_create_element_null_value('ROW',array('FSD'=>FSD,'YWLX'=>YWLX));

        foreach($data as $value){
            $this->_create_element_with_value_by_item('GMSFHM',$value['identity'],$ROW_item);
            $this->_create_element_with_value_by_item('XM',$value['name'],$ROW_item);
        }

        return $this->Dom->saveXML();
    }

    private function build_xml_header(){
        //  创建一个XML文档并设置XML版本和编码。。
        $this->Dom = new DomDocument('1.0', 'UTF-8');
        //  创建根节点
        $this->stream = $this->Dom->createElement('ROWS');
        $this->Dom->appendchild($this->stream);

        $INFO_item = $this->Dom->createElement('INFO');
        $this->stream->appendchild($INFO_item);

        $this->_create_element_with_value_by_item('SBM',date('YmdHis'),$INFO_item);

        $ROW_item = $this->Dom->createElement('ROW');
        $this->_create_element_with_value_by_item('GMSFHM','公民身份号码',$ROW_item);
        $this->_create_element_with_value_by_item('XM','姓名',$ROW_item);
        $this->stream->appendchild($ROW_item);
    }

    private function _create_element_null_value($key,$attribute_array=null){

        //创建元素
        $item = $this->Dom->createElement($key);
        $this->stream->appendchild($item);

        foreach($attribute_array as $k=>$value){
            //  创建属性节点
            $attribute = $this->Dom->createAttribute($k);
            // 创建属性值节点
            $attribute_value = $this->Dom->createTextNode($value);
            $item->appendchild($attribute);
            $attribute->appendchild($attribute_value);
        }

        return $item;
    }

    private function _create_element_with_value_by_item($key,$value,$item){
        //创建元素
        $_item = $this->Dom->createElement($key);
        $item->appendchild($_item);
        //创建元素值
        $text = $this->Dom->createTextNode($value);
        $_item->appendchild($text);
    }

}