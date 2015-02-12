<?php
/**
 * Http客户端请求
 * 
 * @author unique@hiunique.com
 * @copyright 2015-1-26
 */
class Techo_Http_Client
{
    /**
     * 请求队列
     * 
     * @access private
     * @var array
     */
    private $_requests = array();
    
    /**
     * 超时
     * 
     * @access private
     * @var float
     */
    private $_timeout = 0.5;
    
    /**
     * 请求头
     * 
     * @access private
     * @var array
     */
    private $_headers = array();
    
    /**
     * 设置
     * 
     * @access private
     * @var array
     */
    private $_options = array(
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CONNECTTIMEOUT => 20,
        CURLOPT_TIMEOUT        => 20
    );
    
    /**
     * 构造器
     * 
     * @access public
     * @param array $requests 请求队列
     */
    public function __construct(array $requests = array())
    {
        if (!extension_loaded('curl')) {
            throw new Techo_Http_Exception('CURL extension may be not be installed');
        }
        $this->_requests = $requests;
    }
    
    /**
     * 获取设置的超时
     * 
     * @access public
     * @return float
     */
    public function getTimeout()
    {
        return $this->_timeout;
    }
    
    /**
     * 设置时间
     * 
     * @access public
     * @param float $timeout 超时
     * @return Techo_Http_Client
     */
    public function setTimeout($timeout)
    {
        $this->_timeout = $timeout;
        return $this;
    }
    
    /**
     * 获取设置的设置
     * 
     * @access public
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }
    
    /**
     * 设置设置
     * 
     * @access public
     * @param array $options 设置
     * @return Techo_Http_Client
     */
    public function setOptions(array $options)
    {
        $this->_options = $this->_options + $options;
        return $this;
    }
    
    /**
     * 获取设置的请求头
     * 
     * @access public
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
    }
    
    /**
     * 设置请求头
     * 
     * @access public
     * @param array $headers 请求头
     * @return Techo_Http_Client
     */
    public function setHeaders(array $headers)
    {
        $this->_headers = $this->_headers + $headers;
        return $this;
    }
    
    /**
     * 向队列中加入一个GET请求
     * 
     * @access public
     * @param string $url url网址
     * @param array $options 设置
     * @param array $headers 请求头
     * @return Techo_Http_Client
     */
    public function addGetRequest($url = null, $options = null, $headers = null)
    {
        return $this->add($url, "GET", null, $options, $headers);
    }
    
    /**
     * 向队列中加入一个POST请求
     * 
     * @access public
     * @param string $url url网址
     * @param string $postData POST数据
     * @param string $options 设置
     * @param string $headers 请求头
     * @return Techo_Http_Client
     */
    public function addPostRequest($url = null, $postData = null, $options = null, $headers = null)
    {
        return $this->add($url, "POST", $postData, $options, $headers);
    }
    
    /**
     * 向队列中加入一个Request请求
     * 
     * @access public
     * @param Techo_Http_Request $request 请求对象
     * @return Techo_Http_Client
     */
    public function addRequest(Techo_Http_Request $request)
    {
        $this->_requests[] = $request;
        return $this;
    }
    
    /**
     * 向队列中加入一个Request请求
     * 
     * @access public
     * @param string $url url网址
     * @param string $method 请求类型
     * @param array $postData POST数据
     * @param array $options 设置
     * @param array $headers 请求头
     * @return Techo_Http_Client
     */
    public function add($url = null, $method = 'GET', $postData = null, $options = null, $headers = null)
    {
        $this->_requests[] = new Techo_Http_Request($url, $method, $postData, $options, $headers);
        return $this;
    }
    
    /**
     * 获取指定索引的Request
     * 
     * @access public
     * @param int $key 索引
     * @return array|false
     */
    public function getRequest($key)
    {
        if (array_key_exists($key, $this->_requests)) {
            return $this->_requests[$key];
        }
        return false;
    }
    
    /**
     * 获取指定Request的索引
     * 
     * @param Techo_Http_Request $request 请求对象
     * @return int|false
     */
    public function getKey(Techo_Http_Request $request)
    {
        return array_search($request, $this->_requests);
    }
    
    /**
     * 执行Http请求操作
     * 
     * @access 
     * @throws Techo_Http_Exception
     * @return array 关联数组content，info，error
     */
    public function run()
    {
        if(count($this->_requests) == 1) {
            var_dump($this->_singleHttp());exit();
            return $this->_singleHttp();
        } elseif (count($this->_requests) > 1) {
            return $this->_multiHttp();
        }
        throw new Techo_Http_Exception('Must Run At Least One Http Request');
    }
    
    /**
     * 获取设置
     * 
     * @access private
     * @param Techo_Http_Request $request 请求对象
     * @return array
     */
    private function _getOptions(Techo_Http_Request $request)
    {
        $options = $this->_options;
        if ($request->getOptions()) {
            $options = $options + $request->getOptions();
        }
        if ((ini_get('safe_mode') == 'Off' || !ini_get('safe_mode')) && ini_get('open_basedir') == '') {
            $options[CURLOPT_FOLLOWLOCATION] = isset($options[CURLOPT_FOLLOWLOCATION]) ? $options[CURLOPT_FOLLOWLOCATION] : 1;
            $options[CURLOPT_MAXREDIRS] = isset($options[CURLOPT_MAXREDIRS]) ? $options[CURLOPT_MAXREDIRS] : 5;
        }
        $headers = $this->_headers;
        if ($request->getHeaders()) {
            $headers = $headers + $request->getHeaders();
        }
        $options[CURLOPT_URL] = $request->getUrl();
        if ($request->getPostdata()) {
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = $request->getPostdata();
        }
        if ($headers) {
            $options[CURLOPT_HEADER] = 0;
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        if (!empty($options[CURLOPT_WRITEFUNCTION])) {
            $callback = $options[CURLOPT_WRITEFUNCTION];
            unset($options[CURLOPT_WRITEFUNCTION]);
            $options[CURLOPT_WRITEFUNCTION] = $callback;
        }
        return $options;
    }
    
    /**
     * 单例Http请求
     * 
     * @access private
     * @return array
     */
    private function _singleHttp()
    {
        $ch = curl_init();
        $request = array_shift($this->_requests);
        $options = $this->_getOptions($request);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_error($ch);
        curl_close($ch);
        return array(
            'content' => $content,
            'info' => $info,
            'error' => $error
        );
    }
    
    /**
     * 并发Http请求
     * 
     * @access private
     * @return array
     */
    private function _multiHttp()
    {
        $mh = curl_multi_init();
        $requestMap = array();
        $responses = array();
        foreach ($this->_requests as $key => $request) {
            $ch = curl_init();
            $options = $this->_getOptions($request);
            curl_setopt_array($ch, $options);
            curl_multi_add_handle($mh, $ch);
            $requestMap[(string)$ch] = $key;
        }
        $requestsCnt = count($mh);
        do {
            while(($code = curl_multi_exec($mh, $active)) == CURLM_CALL_MULTI_PERFORM);
            if ($code != CURLM_OK) {
            	break;
            }
            while ($done = curl_multi_info_read($mh)) {
                $info = curl_getinfo($done['handle']);
                $error = curl_errno($done['handle']);
                $content = curl_multi_getcontent($done['handle']);
                $responses[$requestMap[(string)$done['handle']]] = array(
                    'content' => $content,
                    'info' => $info,
                    'error' => $error
                );
                curl_multi_remove_handle($mh, $done['handle']);
                curl_close($done['handle']);
            }
            if ($active) {
                curl_multi_select($mh, $this->_timeout);
            }
        } while($active);
        curl_multi_close($mh);
        return $responses;
    }
    
    /**
     * 析构函数
     * 
     * @access public
     */
    public function __destruct()
    {
        unset($this->_requests, $this->_timeout, $this->_options, $this->_headers);
    }

}