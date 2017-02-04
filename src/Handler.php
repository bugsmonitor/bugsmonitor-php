<?php namespace Bugsender;

class Handler
{

    protected $client;

    const FATAL    = 'fatal';
    const ERROR   = 'error';
    const WARNING = 'warning';
    const INFO    = 'info';


    public function __construct()
    {
        //$this->client = $client;
    }


    public function setup()
    {
        set_exception_handler(array( $this, 'exceptionHandler' ));
        set_error_handler(array( $this, 'errorHandler' ));
        register_shutdown_function(array( $this, 'fatalErrorHandler' ));
    }

    public function exceptionHandler($exception)
    {
        echo "<pre>", var_dump($exception), "</pre>";

        $type    = get_class($exception);
        $message = $exception->getMessage();
        $file    = $exception->getFile();
        $line    = $exception->getLine();
        $trace   = $exception->getTrace();

        //$client = Client::getInstance();
        //$client->send()

        Notifier::exception($type, $message, $file, $line, $trace);

        //$this->client->send($type, $message, $file, $line, $exception);
    }


    public function errorHandler($errno, $errstr, $errfile = null, $errline = null)
    {
        echo "<pre>";
        var_dump([
            $errno, self::translateError($errno), $errstr, $errfile, $errline
        ]);
        echo "</pre>";

        //$type = 'PHP Error';
        // $this->send($this->client->translateError($errno), $errstr, $errfile, $errline);
    }


    public function fatalErrorHandler()
    {
        $error = error_get_last();

        echo '<p>Last error:</p>';
        echo "<pre>", var_dump($error), "</pre>";

        if ($error !== null) {
            $type    = $error['type'];
            $message = $error['message'];
            $file    = $error['file'];
            $line    = $error['line'];

            //$this->client->send($type, $message, $file, $line);
        }
    }

    protected static function translateError($errno) {
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
