<?php
/**
 * @name Util_Http
 * @description Http请求工具类
 * @author unique@hiunique.com
 * @date 2015-1-25
 * @version 1.0.0
 */
class Util_Http
{

    /**
     * 获取HTTP网址内容GET请求
     * 
     * @param string|array $url            
     * @param number $timeout                     
     * @return string|false
     */
    public static function httpGetContents($url, $timeout = 3)
    {
        if (is_array($url)) {
        	//do something
        }
        return self::singleHttpGetContents($url, $timeout);
    }

    /**
     * HTTP网址获取内容GET请求——单URL
     * @param string $url
     * @param number $timeout
     * @param string $error
     * @return string|false
     */
    protected function singleHttpGetContents($url, $timeout = 3, &$error = null)
    {
        $ch = curl_init($url);
        curl_setopt_array(
            $ch, 
            array(
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true
            )
        );
        $result = curl_exec($ch);
        if (false === $result) {
            $error = curl_error($ch);
        }
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (!in_array(intval($status / 100), array(2, 3))) {
            $result = false;
            $error = 'status is ' . $status;
        }
        curl_close($ch);
        return $result;
    }
    
    protected function multiHttpGetContents(array $url, $timeout = 3)
    {
        $multiCh = curl_multi_init();
        $responses = array();
        foreach ($url as $key => $val) {
            $chObj[$key] = curl_init($val);
            $chOptions = array(
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true
            );
            curl_setopt_array($chObj[$key], $chOptions);
            curl_multi_add_handle($multiCh, $chObj[$key]);
        }
    }
    /**
     * 提交HTTP网址POST请求
     * 
     * @param string|array $url            
     * @param array $data            
     * @param string $cookieFile
     *            默认当前目录temp.cookie
     * @param string $cookie            
     * @param number $timeout            
     * @param string $error            
     * @return string|false
     */
    public static function httpPostContents($url, array $data, $cookieFile = null, $cookie = null, $timeout = 3)
    {
        if (is_array($url)) {
            //do something
        }
        return self::singleHttpPostContents($url, $data, $cookieFile, $cookie, $timeout);
    }

    protected function singleHttpPostContents($url, array $data, $cookieFile = null, $cookie = null, $timeout = 3, &$error = null)
    {
        $ch = curl_init($url);
        $curlArray = array(
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data
        );
        if (!$cookieFile) {
            $cookieFile = dirname(__FILE__) . 'tmp.cookie';
        }
        if ($cookie) {
            $curlArray[CURLOPT_COOKIE] = $cookie;
        } else
        if ($cookieFile) {
            $curlArray[CURLOPT_COOKIEFILE] = $cookieFile;
        }
        $curlArray[CURLOPT_COOKIEJAR] = $cookieFile;
        curl_setopt_array($ch, $curlArray);
        $result = curl_exec($ch);
        if (false === $result) {
            $error = curl_error($ch);
        }
        var_dump($result . '/' . $error);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (!in_array(intval($status / 100), array(2, 3))) {
            $result = false;
            $error = 'status is ' . $status;
        }
        curl_close($ch);
        return $result;
    }
    /**
     * 获取HTTPS网址内容GET请求
     * @param string $url            
     * @param string $verify            
     * @param number $timeout                       
     * @return string|false
     */
    public static function httpsGetContents($url, $verify = null, $timeout = 3)
    {
        if (is_array($url)) {
            //do something
        }
        return self::singleHttpsGetContents($url, $verify, $timeout);
    }
    
    /**
     * 获取HTTPS网址内容GET请求
     * @param string $url
     * @param string $verify
     * @param number $timeout
     * @param string $error
     * @return string|false
     */
    protected function singleHttpsGetContents($url, $verify = null, $timeout = 3, &$error = null)
    {
        $ch = curl_init($url);
        $curlArray = array(
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true
        );
        if ($verify) {
            $curlArray[CURLOPT_SSL_VERIFYPEER] = true;
            $curlArray[CURLOPT_CAINFO] = $verify;
        } else {
            $curlArray[CURLOPT_SSL_VERIFYPEER] = false;
            $curlArray[CURLOPT_SSL_VERIFYHOST] = false;
        }
        curl_setopt_array($ch, $verify);
        $result = curl_exec($ch);
        if (false === $result) {
            $error = curl_error($ch);
        }
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (!in_array(intval($status / 100), array(2, 3))) {
            $result = false;
            $error = 'status is ' . $status;
        }
        curl_close($ch);
        return $result;
    }

    /**
     * 提交HTTPS网址POST请求
     * @param string|array $url            
     * @param array $data            
     * @param string $cookieFile 默认当前目录temp.cookie
     * @param string $cookie            
     * @param string $verify            
     * @param number $timeout                        
     * @return string|false
     */
    public static function httpsPostContents($url, array $data, $cookieFile = null, $cookie = null, $verify = null, $timeout = 3)
    {
        if (is_array($url)) {
        	//do something
        }
        return self::singleHttpsPostContents($url, $data, $cookieFile, $cookie, $verify, $timeout);
    }
    
    /**
     * 提交HTTPS网址POST请求——单URL
     * @param string $url
     * @param array $data
     * @param string $cookieFile 默认当前目录temp.cookie
     * @param string $cookie
     * @param string $verify
     * @param number $timeout
     * @param string $error
     * @return string|false
     */
    protected function singleHttpsPostContents($url, array $data, $cookieFile = null, $cookie = null, $verify = null, $timeout = 3, &$error = null)
    {
        $ch = curl_init($url);
        $curlArray = array(
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data
        );
        if ($verify) {
            $curlArray[CURLOPT_SSL_VERIFYPEER] = true;
            $curlArray[CURLOPT_CAINFO] = $verify;
        } else {
            $curlArray[CURLOPT_SSL_VERIFYPEER] = false;
            $curlArray[CURLOPT_SSL_VERIFYHOST] = false;
        }
        if (!$cookieFile) {
            $cookieFile = dirname(__FILE__) . 'tmp.cookie';
        }
        if ($cookie) {
            $curlArray[CURLOPT_COOKIE] = $cookie;
        } else if ($cookieFile) {
            $curlArray[CURLOPT_COOKIEFILE] = $cookieFile;
        }
        $curlArray[CURLOPT_COOKIEJAR] = $cookieFile;
        curl_setopt_array($ch, $curlArray);
        $result = curl_exec($ch);
        if (false === $result) {
            $error = curl_error($ch);
        }
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (!in_array(intval($status / 100), array(2, 3))) {
            $result = false;
            $error = 'status is ' . $status;
        }
        curl_close($ch);
        return $result;
    }
    
    public static function multiCurl(array $requestUrl)
    {
        $result = $response = $curlObj = array();
        $nch = 0;
        $multiCurlObj = curl_multi_init();
        $header = array('Connection: Keep-Alive');
        foreach ($requestUrl as $index => $url) {
            $curlObj[$nch] = curl_init();
            $curlOptions = array(
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_USERAGENT => 'Service_Requester',
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POST => TRUE
            );
            curl_setopt_array($curlObj[$nch], $curlOptions);
            curl_multi_add_handle($multiCurlObj, $curlObj[$nch]);
            $nch++;
        }
        /* wait for performing request */
        do {
            $mrc = curl_multi_exec($multiCurlObj, $running);
        } while (CURLM_CALL_MULTI_PERFORM == $mrc);
    
        while ($running && $mrc == CURLM_OK) {
            // wait for network
            if (curl_multi_select($multiCurlObj, 0.5) > -1) {
                // pull in new data;
                do {
                    $mrc = curl_multi_exec($multiCurlObj, $running);
                } while (CURLM_CALL_MULTI_PERFORM == $mrc);
            }
        }
    
        if ($mrc != CURLM_OK) {
            return false;
        }
    
        /* get data */
        $nch = 0;
        foreach ($requestUrl as $moudle => $node) {
            if (($err = curl_error($curlObj[$nch])) == '') {
                $response[$nch]=curl_multi_getcontent($curlObj[$nch]);
                $result[$moudle]=$response[$nch];
            } else {
                return false;
            }
            curl_multi_remove_handle($multiCurlObj, $curlObj[$nch]);
            curl_close($curlObj[$nch]);
            $nch++;
        }
        curl_multi_close($multiCurlObj);
        return  $result;
    }
}
