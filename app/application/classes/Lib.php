<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: majin
 * Date: 15/12/22
 * Time: 下午9:28
 *
 * 参照Model系统类编写，方便调用更多的Tool类帮助完成工作
 * 2015-12-22
 * 功能类存放于Tool目录下
 */
class Lib {

	/**
	 * Create a new model instance.
	 *
	 *     $model = Model::factory($name);
	 *
	 * @param   string  $name   model name
	 * @return  Model
	 */
	public static function factory($name)
	{
		// Add the model prefix
		$class = 'Lib_'.$name;
		return new $class;
	}



} // End Tool
