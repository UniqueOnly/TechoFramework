<?php
/**
 * Techo 自动加载配置文件类
 * 将配置对象类以数组的形式操作
 * 
 * @author unique@hiunique.com
 * @copyright 2015-2-20
 */
namespace Techo;
class Config
{
    /**
     * 配置文件目录路径
     * 
     * @access protected
     * @var string
     */
    protected $_path = null;
    
    /**
     * 配置文件自动加载池
     * 
     * @access protected
     * @var array
     */
    protected $_configs = array();
    
    /**
     * 构造器
     * 
     * @access public
     * @param string $path 配置文件目录路径
     */
    public function __construct($path = null)
    {
        if (is_dir($path)) {
            if (!file_exists($path)) {
                mkdir($path, 0777);;
            }
            $this->_path = $path;
        } else {
            throw new \Techo\Exception('The path is an unvalid path');
        }    
    }

    /**
     * 是否存在该配置文件
     * 
     * @access public
     * @param string $key 文件名
     * @return boolean
     */
    public function isExists($key)
    {
        return empty($this->_configs[$key]) ? false : true;
    }

    /**
     * 获取指定文件名配置文件
     * 
     * @access public
     * @param string $key 文件名
     * @return array
     */
    public function get($key)
    {
        if (empty($this->_configs[$key])) {
            $filePath = $this->_path . DIRECTORY_SEPARATOR . $key . '.php';
            $config = require_once $filePath;
            $this->_configs[$key] = $config;
        }
        return $this->_configs[$key];
    }

    /**
     * 添加配置文件
     * 
     * @access public
     * @param string $key 文件名
     * @param array $value 配置值
     * @return \Techo\Config
     */
    public function set($key, $value = array())
    {
        file_put_contents($this->_path . DIRECTORY_SEPARATOR . $key . '.php', "<?php\r\n return " . var_export($value, true) . ';');
        $this->_configs[$key] = $value;
        return $this;
    }

    /**
     * 删除一个配置文件
     * 
     * @access public
     * @param string $key 文件名
     * @return \Techo\Config
     */
    public function delete($key)
    {
        if (isset($this->_configs[$key])) {
            unset($this->_configs[$key]);
        }
        $filePath = $this->_path . DIRECTORY_SEPARATOR . $key . '.php';
        if (is_file($filePath)) {
            unlink($filePath);
        }
        return $this;
    }

    /**
     * 加载所有配置文件
     *
     * @access public
     * @return array
     */
    public function getAll()
    {
        if ($configDir = opendir($this->_path)) {
            while (false !== ($file = readdir($configDir))) {
                if ( $file  !=  "."  &&  $file  !=  ".." ) {
                    $filePath = $this->_path . DIRECTORY_SEPARATOR . $file;
                    $key = substr($file, 0, strlen($file) - 4);
                    $this->_configs[$key] = require_once $filePath;
                }
            }
            closedir($configDir);
        }
        return $this->_configs;
    }
}