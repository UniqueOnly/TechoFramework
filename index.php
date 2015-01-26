<?php
    require_once 'Techo/Http/Request.php';
    $request = new Techo_Http_Request("http://www.baidu.com");
    echo (string)$request;