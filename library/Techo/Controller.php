<?php
namespace Techo;

abstract class Controller
{
    /**
     * 前端渲染模板对象
     * 
     * @access protected
     * @var Object
     */
    protected $_tpl = null;
    protected function _setTpl($tplPath, $tplName)
    {
        if (file_exists($tplPath) && $tplName) {
            include $tplPath;
            $this->_tpl = new $tplName();
        } else {
            throw new \Techo\Exception("The tplPath or tplName is wrong!");
        }
    }
    protected function _getTpl()
    {
        return $this->_tpl;
    }
}