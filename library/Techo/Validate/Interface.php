<?php
/**
 * Validate 接口
 * 
 * @author unique@hiunique.com
 * @copyright 2015-1-29
 */
interface Techo_Validate_Interface
{
    public function isValid($content);
    public function setMessages();
    public function getMessages(array $messages);
}