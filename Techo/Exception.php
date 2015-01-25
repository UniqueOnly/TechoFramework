<?php
/**
 * Techo异常类
 * @author unique@hiunique.com
 * @date 2015-1-26
 * @version 1.0.0
 */
class Techo_Exception extends Exception
{
    /**
     * 构造器
     * @param string $message 异常消息
     * @param int $code 异常代码
     */
    public function __construct($message = null, $code = 0)
    {
		$this->message = $message;
		$this->code    = $code;
    }
}