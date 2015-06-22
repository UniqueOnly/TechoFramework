<?php
namespace Techo;
require_once APP_PATH . 'library/Techo/Exception.php';
require_once APP_PATH . 'library/Techo/Config.php';
class Server
{
    
    protected $_configPool = array();
    
    private function readConfig()
    {
        $config = new \Techo\Config(APP_CONFIG);
        $this->_configPool = $config->getAll();
    }
    
    private function loadAppConfig()
    {
        
    }
    
    private function startAutoLoad()
    {
        try {
            $autoLoadConfigs = $this->_configPool['autoload'];
            foreach ($autoLoadConfigs as $val) {
                $this->autoLoad($val);
            }
        } catch (\Techo\Exception $e) {
            $e->getMessage();
        } 
    }
    
    public function run()
    {
        $this->readConfig();
        $this->startAutoLoad();
        ob_start();
        \Techo\Router::init();
        ob_end_flush();
    }
    
    private function autoLoad($path)
    {
        if (is_dir($path)) {
            spl_autoload_register(function($class) use ($path) {
                if (!empty($class)) {
                    $class = ltrim($class, '\\');
                    $file = $path . str_replace(array('_', '\\'), '/', $class) . '.php';
                    if (file_exists($file)) {
                        include $file;
                    } else {
                        throw new \Techo\Exception('The class isn\'t exist');
                    }
                } else {
                    throw new \Techo\Exception('The class isn\'t exist');
                }
            });
        } else {
            throw new \Techo\Exception('The path is an unvalid path');
        }
    }
}