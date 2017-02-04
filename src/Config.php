<?php namespace Bugsender;

class Config
{

    protected $version = '0.6.0';

    protected $publicKey = '';

    protected $privateKey = '';

    protected $apiHost = 'http://new.bugsmonitor.com/';

    protected $apiPath = 'api/report_bug';

    protected $filterKeys = array(
        'pass',
        'password',
        'confirm_password',
        'password_confirm',
        'password_confirmation',
    );

    protected $codeLength = 10;


    public function __construct($config)
    {
        $this->setConfigs($config);
    }


    public function setConfigs($config)
    {
        if (array_key_exists('publicKey', $config)) {
            $this->publicKey = $config['publicKey'];
        }
        if (array_key_exists('privateKey', $config)) {
            $this->privateKey = $config['privateKey'];
        }
        if (array_key_exists('apiHost', $config)) {
            $this->apiHost = $config['apiHost'];
        }
        if (array_key_exists('apiPath', $config)) {
            $this->apiPath = $config['apiPath'];
        }
        if (array_key_exists('filterKeys', $config)) {
            $this->filterKeys = $config['filterKeys'];
        }
        if (array_key_exists('codeLength', $config)) {
            $this->codeLength = $config['codeLength'];
        }
        //if (array_key_exists('', $config)) {
        //    $this-> = $config[''];
        //}
        //if (array_key_exists('', $config)) {
        //    $this-> = $config[''];
        //}
    }


    /**
     * @param string $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }


    /**
     * @param string $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }


    /**
     * @param string $apiHost
     */
    public function setApiHost($apiHost)
    {
        $this->apiHost = $apiHost;
    }


    /**
     * @param string $apiPath
     */
    public function setApiPath($apiPath)
    {
        $this->apiPath = $apiPath;
    }


    /**
     * @param array $filterKeys
     */
    public function setFilterKeys($filterKeys)
    {
        $this->filterKeys = $filterKeys;
    }


    /**
     * @param int $codeLength
     */
    public function setCodeLength($codeLength)
    {
        $this->codeLength = $codeLength;
    }


    /**
     * @return int
     */
    public function getCodeLength()
    {
        return $this->codeLength;
    }


    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }


    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }


    /**
     * @return string
     */
    public function getApiHost()
    {
        return $this->apiHost;
    }


    /**
     * @return string
     */
    public function getApiPath()
    {
        return $this->apiPath;
    }



    public function inFilters($key)
    {
        return array_key_exists($key, $this->filterKeys);
    }

}