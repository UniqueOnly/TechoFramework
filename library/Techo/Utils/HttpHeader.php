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
        $expire = time() + $timeout;
        $expireGMT = gmdate('D, d M Y H:i:s', $expire) . ' GMT';
        header ('Last-Modified: ' . $expireGMT);
        header ('Expires: ' . $expireGMT);
        header ('Cache-Control: max-age=' . $timeout);
        header("Pragma: cache");
        if ($timeout < 0) {
            header('Cache-Control: no-cache, must-revalidate');
            header('Pramga: no-cache');
        }
    }
}