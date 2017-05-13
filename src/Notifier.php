<?php namespace Bugsmonitor;

class Notifier
{

    const ERROR   = 'error';
    const WARNING = 'warning';
    const INFO    = 'info';
    const DEBUG   = 'debug';


    public static function notify($type, $message, $file, $line, $trace)
    {
        $message = self::buildMessage($type, $message, $file, $line, $trace);

        self::send($message);
    }


    public static function exception($type, $message, $file, $line, $trace)
    {
        $message = self::buildMessage($type, $message, $file, $line, $trace);

        self::send($message);
    }


    public static function error($type, $message, $file, $line)
    {
        $message = self::buildMessage($type, $message, $file, $line, null);

        self::send($message);
    }


    public static function fatalError($type, $message, $file, $line)
    {
        $message = self::buildMessage($type, $message, $file, $line, null);

        self::send($message);
    }


    protected static function buildMessage($type, $message, $file, $line, $trace)
    {
        $client = Bugsmonitor::getInstance();
        $config = $client->getConfig();

        $source = ( $file !== null AND $line !== null ) ? self::getCode($file, $line, $config->getCodeLength()) : '';

        $message = array(
            'type'             => mb_convert_encoding($type, 'utf-8', 'utf-8'),
            'url'              => mb_convert_encoding(self::full_path(true), 'utf-8', 'utf-8'),
            'file'             => mb_convert_encoding($file, 'utf-8', 'utf-8'),
            'line'             => mb_convert_encoding($line, 'utf-8', 'utf-8'),
            'message'          => mb_convert_encoding($message, 'utf-8', 'utf-8'),
            'trace'            => $trace,
            'session'          => session_id(),
            'notifier'         => Config::NOTIFIER,
            'notifier_version' => Config::NOTIFIER_VERSION,
            'env'              => array(
                'version'      => mb_convert_encoding($config->getVersion(), 'utf-8', 'utf-8'),
                'lang'         => 'php',
                'lang_version' => PHP_VERSION,
            ),
            'user'             => $config->getUser(),
            'request'          => array(
                'request_type' => array_key_exists('REQUEST_METHOD', $_SERVER) ? mb_convert_encoding($_SERVER['REQUEST_METHOD'], 'utf-8', 'utf-8') : false,
                'ip'           => self::getIp(),
                'ua'           => array_key_exists('HTTP_USER_AGENT', $_SERVER) ? mb_convert_encoding($_SERVER['HTTP_USER_AGENT'], 'utf-8', 'utf-8') : false,
                'headers'      => self::getHeaders(),
                'query_string' => array_key_exists('QUERY_STRING', $_SERVER) ? mb_convert_encoding($_SERVER['QUERY_STRING'], 'utf-8', 'utf-8') : false,
                'post'         => false,
                'cookie'       => ! empty($_COOKIE) ? self::encodeUtf8($_COOKIE) : false,
            ),
            'additional'       => self::encodeUtf8($client->getAdditionalData()),
            'is_cli'           => php_sapi_name() == 'cli' ? 1 : 0,
            'is_fatal'         => self::isFatalError($type) ? 1 : 0,
            'code'             => $source,
        );

        if (isset($_POST) AND is_array($_POST) AND count($_POST) > 0) {
            // remove from request some data like passwords, cc_number etc.
            $post = self::filterKeys($_POST, $config);

            $message['request']['post'] = self::encodeUtf8($post);
            unset($post);
        }

        return $message;
    }


    protected static function getCode($file, $line, $codeLength)
    {
        if (file_exists($file)) {
            $file_content = file_get_contents($file);
            $start        = $line - floor($codeLength / 2);
            if ($start < 0) {
                $start = 0;
            }
            $lines = explode("\n", $file_content);

            return array_slice($lines, $start, $codeLength, true);
        }

        return '';
    }


    public static function full_path($qs = false)
    {
        $s        = &$_SERVER;
        $ssl      = ( ! empty($s['HTTPS']) && $s['HTTPS'] == 'on' ) ? true : false;
        $sp       = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port == '80' ) || ( $ssl && $port == '443' ) ) ? '' : ':' . $port;
        $host     = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null );
        $host     = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        $uri      = $protocol . '://' . $host . $s['REQUEST_URI'];
        if ($qs) {
            return $uri;
        }
        $segments = explode('?', $uri, 2);
        $url      = $segments[0];

        return $url;
    }


    public static function getIP()
    {
        if ( ! empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }


    /**
     *  source: http://www.php.net/manual/en/function.apache-request-headers.php
     *
     * @return array
     */
    public static function getHeaders()
    {
        if ( ! function_exists('apache_request_headers')) {
            $arh     = array();
            $rx_http = '/\AHTTP_/';
            foreach ($_SERVER as $key => $val) {
                if (preg_match($rx_http, $key)) {
                    $arh_key    = preg_replace($rx_http, '', $key);
                    $rx_matches = array();
                    // do some nasty string manipulations to restore the original letter case
                    // this should work in most cases
                    $rx_matches = explode('_', $arh_key);
                    if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                        foreach ($rx_matches as $ak_key => $ak_val) {
                            $rx_matches[$ak_key] = ucfirst($ak_val);
                        }
                        $arh_key = implode('-', $rx_matches);
                    }
                    $arh[mb_convert_encoding($arh_key, 'utf-8', 'utf-8')] = mb_convert_encoding($val, 'utf-8', 'utf-8');
                }
            }

            return ( $arh );
        } else {
            return apache_request_headers();
        }
    }


    public static function encodeUtf8($array)
    {
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    $array[$k] = self::encodeUtf8($v);
                } else {
                    $array[$k] = mb_convert_encoding($v, 'utf-8', 'utf-8');
                }
            }

            return $array;
        }

        return $array;
    }


    private static function filterKeys(Array $array, $config)
    {
        $result = [];
        foreach ($array as $key => $value) {
            if ($config->inFilters($key)) {
                $result[$key] = '***';
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }


    protected static function send($message)
    {
        $client             = Bugsmonitor::getInstance();
        $config             = $client->getConfig();
        $message['api_key'] = $config->getApiKey();
        $sData              = json_encode($message);
        $headers            = array(
            'Content-Type: text/plain',
            'Content-Length: ' . strlen($sData)
        );

        $ch = curl_init(implode('/', array( $config->getApiHost(), $config->getProjectKey(), $config->getApiPath() )));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $sData);
        $result = curl_exec($ch);

        curl_close($ch);
    }


    public static function translateError($error)
    {
        switch ($error) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                return self::ERROR;

            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                return self::WARNING;

            case E_NOTICE:
            case E_USER_NOTICE:
            case E_STRICT:
                return self::INFO;
        }

        return self::ERROR;
    }


    private static function isFatalError($type)
    {
        return in_array($type, array(
            E_ERROR,
            E_PARSE,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
            E_USER_ERROR,
        ));
    }

}
