<?php
/**
 * Techo 抽象基类
 * 
 * @author unique@hiunique.com
 * @copyright 2015-2-11
 */
namespace Techo;
abstract class Base
{
    /**
     * 属性数据堆栈
     * 
     * @access protected
     * @var array
     */
    protected $_propertyStack = array();
    /**
     * 对象属性数据堆栈
     * 
     * @access protected
     * @var array
     */
    protected $_objPropertyStack = array();
    /**
     * 属性数据堆栈当前指针位置
     * 
     * @access public
     * @var int
     */
    public $position = 0;
    /**
     * 属性数据堆栈当前大小
     * 
     * @access 
     * @var int
     */
    public $size = 0;
    public function __construct()
    {
    }
    public function __call($name, $args)
    {
        if (method_exists($this, $args)) {
            return $this->$name($args);
        }
        throw new \Techo\Exception('The method you called isn\'t exist');
    }
    public function __set($name, $value)
    {
        $this->_objPropertyStack[$name] = $value;
    }
    public function __get($name)
    {
        if (array_key_exists($name, $this->_objPropertyStack)) {
            return $this->_objPropertyStack[$name];
        }
        if (method_exists($this, $name)) {
            return $this->$name;
        }
        return false;
    }
    public function first()
    {
        $this->position = 0;
        if ($this->size > $this->position) {
            $this->_objPropertyStack = $this->_propertyStack[$this->position];
            return $this->_objPropertyStack;
        }
        return false;
    }
    public function last()
    {
        $this->position = $this->size - 1;
        if ($this->position > -1) {
            $this->_objPropertyStack = $this->_propertyStack[$this->position];
            return $this->_objPropertyStack;
        }
        return false;
    }
    public function push(array $value)
    {
        $this->_propertyStack[] = $value;
        $this->_objPropertyStack = $value;
        $this->size ++;
        return $this->size;
    }
    public function next()
    {
        $this->position ++;
        if ($this->position < $this->size) {
            $this->_objPropertyStack = $this->_propertyStack[$this->position];
            return $this->_objPropertyStack;
        }
        return false;
    }
}