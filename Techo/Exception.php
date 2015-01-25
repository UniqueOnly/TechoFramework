<?php

class Techo_Exception extends Exception
{
    public function __construct($message = null, $code = 0)
    {
		$this->message = $message;
		$this->code    = $code;
    }
}