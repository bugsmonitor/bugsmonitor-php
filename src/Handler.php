<?php namespace Bugsmonitor;

class Handler
{

    protected $client;

    const FATAL   = 'fatal';
    const ERROR   = 'error';
    const WARNING = 'warning';
    const INFO    = 'info';


    public function setup()
    {
        set_exception_handler(array( $this, 'exceptionHandler' ));
        set_error_handler(array( $this, 'errorHandler' ));
        register_shutdown_function(array( $this, 'fatalErrorHandler' ));
    }


    public function exceptionHandler($exception)
    {
        $type    = get_class($exception);
        $message = $exception->getMessage();
        $file    = $exception->getFile();
        $line    = $exception->getLine();
        $trace   = $exception->getTrace();

        Notifier::exception($type, $message, $file, $line, $trace);
    }


    public function errorHandler($errno, $errstr, $errfile = null, $errline = null)
    {
        Notifier::error(self::translateError($errno), $errstr, $errfile, $errline);
    }


    public function fatalErrorHandler()
    {
        $error = error_get_last();

        if ($error !== null) {
            $type    = $error['type'];
            $message = $error['message'];
            $file    = $error['file'];
            $line    = $error['line'];

            Notifier::fatalError($type, $message, $file, $line);

            if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
                error_clear_last();
            }
        }
    }


    protected static function translateError($errno)
    {
        switch ($errno) {
            case E_CORE_ERROR:
            case E_PARSE:
            case E_COMPILE_ERROR:
                return self::FATAL;

            case E_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                return self::ERROR;

            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                return self::WARNING;

            case E_NOTICE:
            case E_USER_NOTICE:
            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                return self::INFO;
        }

        return self::ERROR;
    }
}
