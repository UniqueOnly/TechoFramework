<?php
/**
 *
 * @author unique@hiunique.com
 * @date 2015-1-26
 * @version 1.0.0
 */
class Techo_Http_Client
{

    private $_requests = array();

    private $_timeout  = 20;

    private $_headers  = array();

    private $_options  = array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CONNECTTIMEOUT => 20,
        CURLOPT_TIMEOUT        => 20
    );

    /**
     *
     * @param array $requests
     */
    public function __construct(array $requests)
    {
        $this->_requests = $requests ? $requests : array();
    }
    /**
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->_timeout;
    }
    /**
     *
     * @param int $timeout
     * @return Techo_Http_Client
     */
    public function setTimeout($timeout)
    {
        $this->_timeout = $timeout;
        return $this;
    }
    /**
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }
    /**
     *
     * @param array $options
     * @return Techo_Http_Client
     */
    public function setOptions(array $options)
    {
        $this->_options = $this->_options + $options;
        return $this;
    }
    /**
     *
     * @return array:
     */
    public function getHeaders()
    {
        return $this->_headers;
    }
    /**
     *
     * @param array $headers
     * @return Techo_Http_Client
     */
    public function setHeaders(array $headers)
    {
        $this->_headers = $this->_headers + $headers;
        return $this;
    }
    /**
     *
     * @param Techo_Http_Request $request
     * @return Techo_Http_Client
     */
    public function addRequest(Techo_Http_Request $request)
    {
        $this->_requests[] = $request;
        return $this;
    }
    /**
     *
     * @param string $url
     * @param string $method
     * @param array $postData
     * @param array $options
     * @param array $headers
     * @return Techo_Http_Client
     */
    public function add($url = null, $method = 'GET', $postData = null, $options = null, $headers = null)
    {
        $this->_requests[] = new Techo_Http_Request($url, $method, $postData, $options, $headers);
        return $this;
    }
    /**
     *
     * @return array
     */
    public function run()
    {
        if(count($this->_requests) == 1) {
            return $this->_singleHttp();
        }
    }
    /**
     *
     * @param Techo_Http_Request $request
     * @return array
     */
    private function _getOptions(Techo_Http_Request $request)
    {
        $options = $this->_options;
        if ($request->getOptions()) {
            $options = $options + $request->getOptions();
        }
        if ((ini_get('safe_mode') == 'Off' || !ini_get('safe_mode')) && ini_get('open_basedir') == '') {
            $options[CURLOPT_FOLLOWLOCATION] = $options[CURLOPT_FOLLOWLOCATION] ? $options[CURLOPT_FOLLOWLOCATION] : 1;
            $options[CURLOPT_MAXREDIRS]      = $options[CURLOPT_MAXREDIRS] ? $options[CURLOPT_MAXREDIRS] : 5;
        }
        $headers = $this->_headers;
        if ($request->getHeaders()) {
            $headers = $headers + $request->getHeaders();
        }
        $options[CURLOPT_URL] = $request->getUrl();
        if ($request->getPostdata()) {
            $options[CURLOPT_POST]       = 1;
            $options[CURLOPT_POSTFIELDS] = $request->getPostdata();
        }
        if ($headers) {
            $options[CURLOPT_HEADER]     = 0;
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        if (!empty($options[CURLOPT_WRITEFUNCTION])) {
            $callback                       = $options[CURLOPT_WRITEFUNCTION];
            unset($options[CURLOPT_WRITEFUNCTION]);
            $options[CURLOPT_WRITEFUNCTION] = $callback;
        }
        return $options;
    }
    /**
     *
     * @return array
     */
    private function _singleHttp()
    {
        $ch      = curl_init();
        $request = array_shift($this->requests);
        $options = $this->_getOptions($request);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $info    = curl_getinfo($ch);
        $error   = curl_error($ch);
        curl_close($ch);
        return array(
            'content' => $content,
            'info'    => $info,
            'error'   => $error
        );
    }
    /**
     *
     */
    public function __destruct()
    {
        unset($this->_requests, $this->_timeout, $this->_options, $this->_headers);
    }

}