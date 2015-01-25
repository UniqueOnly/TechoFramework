<?php

class Techo_Http_Request
{
	private $_url      = null;
	private $_method   = 'GET';
	private $_postData = null;
	private $_options  = null;
	private $_headers  = null;
	
	public function __construct($url = null, $method = 'GET', $postData = null, $options = null, $headers = null)
	{
		$this->_url      = $url;
		$this->_method   = $method;
		$this->_postData = $postData;
		$this->_options  = $options;
		$this->_headers  = $headers;
	}

    public function getUrl()
    {
        return $this->_url;
    }

    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function setMethod($method)
    {
        $this->_method = $method;
        return $this;
    }

    public function getPostdata()
    {
        return $this->_postData;
    }

    public function setPostdata($postData)
    {
        $this->_postData = $postData;
        return $this;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function setOptions($options)
    {
        $this->_options = $options;
        return $this;
    }

    public function getHeaders()
    {
        return $this->_headers;
    }

    public function setHeaders($headers)
    {
        $this->_headers = $headers;
        return $this;
    }
    
    public function __destruct()
    {
        unset($this->_url, $this->_method, $this->_postData, $this->_options, $this->_headers);
    }
	
}