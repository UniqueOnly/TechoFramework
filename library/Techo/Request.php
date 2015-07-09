<?php
namespace Techo;

class Request
{
    /**
     * 请求资源路径
     * @access public
     * @static
     * @return string
     */
    public static function uri()
    {
        return empty($_SERVER['REQUEST_URI']) ? false : urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }

    /**
     *
     * @access private
     * @access
     * @param string $method
     * @param string|array $name
     * @param mixed $default
     * @throws \Techo\Exception
     * @return mixed
     */
    private static function requestParam($method = 'get', $name, $default = null)
    {
        $params = array();
        $method = strtolower($method);
        switch ($method) {
            case 'get' : $params = $_GET; break;
            case 'post' : $params = $_POST; break;
            case 'put' :  parse_str(file_get_contents('php://input'), $params); break;
            default: throw new \Techo\Exception('Unsupport request method get params'); break;
        }
        if (is_array($name)) {
            $array = array();
            foreach ($name as $key => $val) {
                $array[$val] = empty($params[$val]) ? (empty($default[$val]) ? $default : $default[$val]) : $params[$val];
            }
            return $array;
        } else {
            return empty($params[$name]) ? $default : $params[$name];
        }
    }

    /**
     * get参数获取
     * @access public
     * @static
     * @param string|array $name
     * @param mixed $default
     * @return mixed
     */
    public static function get($name, $default = null)
    {
        return self::requestParam('get', $name, $default);
    }

    /**
     * post参数获取
     * @access public
     * @static
     * @param string|array $name
     * @param mixed $default
     * @return mixed
     */
    public static function post($name, $default = null)
    {
        return self::requestParam('post', $name, $default);
    }

    /**
     * put参数获取
     * @access public
     * @static
     * @param string|array $name
     * @param mixed $default
     * @return mixed
     */
    public static function put($name, $default = null)
    {
        return self::requestParam('put', $name, $default);
    }

    /**
     * 用户代理字符串
     * @access public
     * @static
     * @param mixed $default
     * @return mixed
     */
    public static function userAgent($default = null)
    {
        return empty($_SERVER['HTTP_USER_AGENT']) ? $default : $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * 用户请求地址IP
     * @access public
     * @static
     * @param mixed $default
     * @return mixed
     */
    public static function remoteAddr($default = null)
    {
        return $_SERVER['REMOTE_ADDR']; //待修改
    }
}