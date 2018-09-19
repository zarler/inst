<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: guorui
 * Date: 2016/2/18
 * Time: 15:48
 */
class Controller_Config extends AdminController
{
    public function action_List()
    {
        $message = null;
        $typeid = isset($this->get['typeid']) ? intval($this->get['typeid']) : 1;
        if (isset($this->post['submit'])) {
            if ($this->post['modidstr'] == '') {
                $message = array('type' => 'error', 'message' => '没有修改.');
            } else {
                $modidstr = substr($this->post['modidstr'], 1);
                $modarr = explode('|', $modidstr);
                for ($i = 0; $i < count($modarr); $i++) {
                    $updatedata = array(
                        'name' => $this->post['name_' . $modarr[$i]],
                        'value' => $this->post['val_' . $modarr[$i]],
                        'val_type' => $this->post['val_type_' . $modarr[$i]],
                        'description' => $this->post['des_' . $modarr[$i]],
                    );
                    $update[$i] = Model::factory('Config')->updateConfig(intval($modarr[$i]), $updatedata);
                    if (!$update) {
                        $updateerr[] = $modarr[$i];
                    }
                }
                if (isset($updateerr)) {
                    $message = array('type' => 'error', 'message' => 'id=' . implode(' ', $updateerr) . '修改失败.');
                } else {
                    $message = array('type' => 'success', 'message' => '修改成功.');
                }
            }
        }
        $navres = Model::factory('Config')->getConfigNav();
        $tablist = Model::factory('Config')->getConfigByTypeID($typeid);
        Template::factory('Config/List',
            array('typeid' => $typeid, 'message' => $message, 'navres' => $navres, 'tablist' => $tablist))->response();
    }

    //添加变量
    public function action_Add()
    {
        $navres = Model::factory('Config')->getConfigNav();
        $message = null;
        if (isset($this->post['submit'])) {
            $valid = Validation::factory($this->post)->rule('name', 'not_empty');
            if ($this->post['val_type'] == 'int' || $this->post['val_type'] == 'float' || $this->post['val_type'] == 'string') {
                $valid = $valid->rule('txtvalue', 'not_empty');
                $val = $this->post['txtvalue'];
            } else {
                if ($this->post['val_type'] == 'text' || $this->post['val_type'] == 'json') {
                    $valid = $valid->rule('textareavalue', 'not_empty');
                    $val = $this->post['textareavalue'];
                } else {
                    $val = $this->post['radiovalue'];
                }
            }
            if ($this->post['typename'] == '0') {
                $valid = $valid->rule('moretype', 'not_empty');
                $typeid = intval($this->post['maxtid']) + 1;
                $typename = $this->post['moretype'];
            } else {
                $typearr = explode('|', $this->post['typename']);
                $typeid = intval($typearr[0]);
                $typename = $typearr[1];
            }
            if ($valid->check()) {
                $add = Model::factory('Config')->create(array(
                    'name' => $this->post['name'],
                    'typeid' => $typeid,
                    'typename' => $typename,
                    'value' => $val,
                    'val_type' => $this->post['val_type'],
                    'description' => $this->post['description'],
                ));
                if ($add) {
                    $message = array('type' => 'success', 'message' => '添加成功.');
                } else {
                    $message = array('type' => 'error', 'message' => '添加失败.');
                }
            } else {
                $message = array('type' => 'error', 'message' => '添加失败,请查看是否有必填信息未填.');
            }
        }
        Template::factory('Config/Add', array('typeid' => '', 'message' => $message, 'navres' => $navres))->response();
    }

    //变量回收
    public function action_Recovery()
    {
        $message = null;
        if (isset($this->get['id']) && isset($this->get['is_show'])) {
            $update = Model::factory('Config')->updateConfig(intval($this->get['id']),
                array('is_show' => intval($this->get['is_show'])));
            if ($update) {
                $message = array('type' => 'success', 'message' => '设置显示/隐藏成功.');
            } else {
                $message = array('type' => 'error', 'message' => '设置显示/隐藏失败.');
            }
        }
        $navres = Model::factory('Config')->getConfigNav();
        $hideres = Model::factory('Config')->getConfigHide();
        Template::factory('Config/Recovery',
            array('typeid' => '', 'message' => $message, 'navres' => $navres, 'hideres' => $hideres))->response();
    }
}