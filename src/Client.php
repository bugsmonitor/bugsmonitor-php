<?php namespace Bugsender;

class Client extends Singleton
{

    private $config;

    private $handler;

    private $additional_data = [];

    //public function __construct($config)
    //{
    //    $this->config = new Config($config);

        //if (self::$instance != null) {
        //    self::$instance = new self();
        //}
        //
        //return self::$instance;

        // lub
        //if (null === static::$instance) {
        //    static::$instance = new static();
        //}
        //return static::$instance;
    //}


    public function init(Array $config)
    {
        if (!array_key_exists('privateKey', $config) || !array_key_exists('publicKey', $config)) {
            throw new \Exception('Public or/and private keys not provided.');
        }

        $this->config = new Config($config);

        $this->handler = new Handler();

        //$client = new static([
        //    'privateKey' => $config['privateKey'],
        //    'publicKey'  => $config['publicKey']
        //]);
    }


    public function setHandlers()
    {
        $this->handler->setup();
    }


    /**
     * @param      $config mixed
     *
     * @param null $val
     *
     * @return mixed
     */
    public function setConfig($config, $val = null)
    {
        if (!is_array($config) && $val !== null) {
            $config = array($config => $val);
        }

        return $this->config->setConfigs($config);
    }

    public function getConfig()
    {
        return $this->config;
    }


    /**
     * @return array
     */
    public function getAdditionalData()
    {
        return $this->additional_data;
    }


    /**
     * @param array $additional_data
     */
    public function setAdditionalData($additional_data)
    {
        $this->additional_data[] = $additional_data;
    }


    protected function prepareMessage($type, $message, $file, $line, $exception = null) {
        $trace  = ( is_object($exception) ) ? array_slice(debug_backtrace(), 1) : $exception->getTrace();
        $source = ( $file !== null AND $line !== null ) ? $this->getCode($file, $line) : '';
    }
}