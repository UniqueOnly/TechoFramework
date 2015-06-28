<?php
namespace Techo\Utils;

class HttpHeader
{
    /**
     * 设置过期时间
     * 
     * @static
     * @access public
     * @param number $timeout 秒数(等于0秒表示禁止缓存,小于0不做操作)
     */
    public static function expire($timeout = 0)
    {
        if ($timeout >= 0) {
            $expire = time() + $timeout;
            $expireGMT = gmdate('D, d M Y H:i:s', $expire) . ' GMT';
            if ($timeout == 0) {
                header('Cache-Control: no-cache');
                header('Cache-Control ：no-store');
                header('Pramga: no-cache');
                header('Expires：1L');
            } else {
                header('Last-Modified: ' . $expireGMT);
                header('Expires: ' . $expireGMT);
                header('Cache-Control: max-age=' . $timeout);
                header("Pragma: cache");
            }
        }
    }
}