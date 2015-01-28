<?php
/**
 * Validate 抽象类
 * 
 * @author unique@hiunique.com
 * @copyright 2015-1-29
 */
abstract class Techo_Validate_Abstract implements Techo_Validate_Interface
{
    protected $_content;
    
    protected $_messages = array();
    
    protected $_errorCodes = array();
    
    public function getMessages()
    {
        return $this->_messages;
    }
    
    public function setMessage($message, $key = null)
    {
        if (null === $key) {
            array_push($this->_messages, $message);
            return $this;
        }
        if (array_key_exists($key, $this->_messages)) {
            throw new Techo_Validate_Exception("The Key:$key is already exists");
        }
        $this->_messages[$key] = $message;
        return $this;
    }
    
    public function setMessages(array $messages)
    {
        foreach ($messages as $key => $message) {
            $this->setMessage($message, $key);
        }
        return $this;
    }

    
}
