<?php
namespace Techo\Utils;

class HttpHeader
{
    /**
     * 设置过期时间
     * 
     * @static
     * @access public
     * @param number $timeout 秒数(小于或等于0秒表示禁止缓存)
     */
    public static function expire($timeout = 0)
    {
        header ('Last-Modified: ' . gmdate (DATE_RFC822, time() + $timeout));
        header ('Expires: ' . gmdate (DATE_RFC822, time() + $timeout));
        header ('Cache-Control: max-age=' . $timeout);
        if ($timeout < 0) {
            header('Cache-Control: no-cache, must-revalidate');
            header('Pramga: no-cache');
        }  
    }
}