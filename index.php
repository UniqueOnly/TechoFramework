<?php
define('TECHO_STARTTIME', microtime(true));
header("Content-Type:text/html;charset=utf-8");
define('APP_PATH', dirname(__FILE__) . '/');
define('APP_CONFIG', APP_PATH . 'config/');
require_once APP_PATH . 'library/Techo/Server.php';
$server = new \Techo\Server();
$server->run();
/* echo microtime(true) - TECHO_STARTTIME; */


