<?php
namespace Techo;

class Router
{
    private static $_uri = null;
    public static function init($config)
    {
        self::$_uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        echo self::$_uri;
    }
    
}