<?php namespace Bugsmonitor;

/**
 * Singleton
 */

class Singleton
{

    /**
     * This stores the only instance of this class.
     *
     * @var boolean|object
     */
    private static $instance = false;


    /**
     * This is how we get our single instance
     *
     * @return object
     */
    public static function getInstance()
    {
        if (self::$instance == false) {
            self::$instance = new static();
        }

        return self::$instance;
    }


    /**
     * Don't allow the "new Class" construct
     */
    protected function __construct()
    {
    }


    /**
     * Don't allow clones of this object
     */
    private function __clone()
    {
    }


    /**
     * Don't allow serialization of this object
     */
    private function __wakeup()
    {
    }
}