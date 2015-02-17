<?php
namespace Techo;
class Server
{
    public static function autoLoad($class)
    {
        if (!empty($class)) {
            $class = ltrim($class, '\\');
            $file = str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $class) . '.php';
            if (file_exists(__LIBRARY__ .$file)) {
                include $file;
            } else {
                throw new \Techo\Exception('The class isn\'t exist');
            }
        } else {
            throw new \Techo\Exception('The class isn\'t exist');
        }
    }
}