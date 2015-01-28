<?php
interface Techo_Validate_Interface
{
    public function isValid($content);
    public function setMessages();
    public function getMessages(array $messages);
}