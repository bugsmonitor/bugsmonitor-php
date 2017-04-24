<?php namespace Bugsmonitor;

class Bugsmonitor extends Singleton
{

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Handler
     */
    private $handler;

    /**
     * @var array
     */
    private $additional_data = [];


    /**
     * @param array $config
     *
     * @throws \Exception
     */
    public function init(Array $config)
    {
        if ( ! array_key_exists('apiKey', $config)) {
            throw new \Exception('API key not provided.');
        }

        $this->config = new Config($config);

        $this->handler = new Handler();
    }


    /**
     *
     */
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
        if ( ! is_array($config) && $val !== null) {
            $config = array( $config => $val );
        }

        return $this->config->setConfigs($config);
    }


    public function setUser($user_data)
    {
        return $this->config->setUser($user_data);
    }


    /**
     * @return mixed
     */
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
}