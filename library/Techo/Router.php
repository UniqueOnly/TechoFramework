<?php
namespace Techo;

class Router
{

    public static function init($config)
    {
        $uri = \Techo\Request::uri();
        $matchResult = self::match($config, $uri);
        
    }
    
    public static function match($routers, $uri)
    {
        
    }
    
    public static function dispatch(\Techo\Dispatcher $dispatcher)
    {
        
    }
    
}