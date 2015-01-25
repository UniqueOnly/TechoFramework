<?php
/**
 * Http请求类
 * @author unique@hiunique.com
 * @copyright 2015-1-26
 */
class Techo_Http_Request
{
    /**
     * url地址
     * @access private
     * @var string
     */
	private $_url      = null;
	/**
	 * 请求类型
	 * @access private
	 * @var string
	 */
	private $_method   = 'GET';
	/**
	 * post数据
	 * @access private
	 * @var array
	 */
	private $_postData = null;
	/**
	 * 设置
	 * @access private
	 * @var array
	 */
	private $_options  = null;
	/**
	 * 请求头
	 * @access private
	 * @var array
	 */
	private $_headers  = null;
    /**
     * 构造器
     * @access public
     * @param string $url url地址
     * @param string $method 请求类型
     * @param array $postData post数据
     * @param array $options 设置
     * @param array $headers 请求头
     */
	public function __construct($url = null, $method = 'GET', $postData = null, $options = null, $headers = null)
	{
		$this->_url      = $url;
		$this->_method   = $method;
		$this->_postData = $postData;
		$this->_options  = $options;
		$this->_headers  = $headers;
	}
    /**
     * 获取设置的url地址
     * @access public
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }
    /**
     * 设置url地址
     * @access public
     * @param string $url url地址
     * @return Techo_Http_Request
     */
    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }
    /**
     * 获取设置的请求类型
     * @access public
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }
    /**
     * 设置请求类型
     * @access public
     * @param string $method 请求类型
     * @return Techo_Http_Request
     */
    public function setMethod($method)
    {
        $this->_method = $method;
        return $this;
    }
    /**
     * 获取设置的post数据
     * @access public
     * @return array
     */
    public function getPostdata()
    {
        return $this->_postData;
    }
    /**
     * 设置post数据
     * @access public
     * @param array $postData post数据
     * @return Techo_Http_Request
     */
    public function setPostdata($postData)
    {
        $this->_postData = $postData;
        return $this;
    }
    /**
     * 获取设置的设置
     * @access public
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }
    /**
     * 设置设置
     * @access public
     * @param array $options 设置
     * @return Techo_Http_Request
     */
    public function setOptions($options)
    {
        $this->_options = $options;
        return $this;
    }
    /**
     * 获取设置的请求头
     * @access public
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
    }
    /**
     * 设置请求头
     * @access public
     * @param array $headers 请求头
     * @return Techo_Http_Request
     */
    public function setHeaders($headers)
    {
        $this->_headers = $headers;
        return $this;
    }
    /**
     * 析构函数
     * @access public
     */
    public function __destruct()
    {
        unset($this->_url, $this->_method, $this->_postData, $this->_options, $this->_headers);
    }
}