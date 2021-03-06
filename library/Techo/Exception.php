<?php
/**
 * echo异常类
 *
 * @author unique@hiunique.com
 * @copyright 2015-1-26
 */
namespace Techo;
class Exception extends \Exception
{
    /**
     * 构造器
     *
     * @access public
     * @param string $message 异常消息
     * @param int $code 异常代码
     */
    public function __construct($message = null, $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
    }
}