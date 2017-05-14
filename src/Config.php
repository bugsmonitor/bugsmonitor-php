<?php namespace Bugsmonitor;

class Config
{

    const NOTIFIER         = 'bugsmonitor-php';
    const NOTIFIER_VERSION = '1.1.2';

    protected $options = array();

    protected $defaults = array(
        // Application version
        'version'    => '',

        // The BugsMonitor project key
        'projectKey' => '',

        // The BugsMonitor API key
        'apiKey'     => '',

        // The BugsMonitor api url, do not change
        'apiHost'    => 'https://api.bugsmonitor.com',

        // The BugsMonitor api path, do not change
        'apiPath'    => '',

        // Keys filtered in bug report
        'filterKeys' => array(
            'pass',
            'password',
            'confirm_password',
            'password_confirm',
            'password_confirmation',
        ),

        // Number lines of code in bug report
        'codeLength' => 10,
    );

    protected $user = array();


    /**
     * Config constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->setConfigs($config);
    }


    /**
     * @param array $config
     */
    public function setConfigs(Array $config)
    {
        $this->options = array_merge($this->defaults, $config);
    }


    /**
     * @param array $user_data
     */
    public function setUser(Array $user_data)
    {
        $this->user = $user_data;
    }


    /**
     */
    public function getUser()
    {
        return array(
            'id'    => array_key_exists('id', $this->user) ? $this->user['id'] : '',
            'name'  => array_key_exists('name', $this->user) ? $this->user['name'] : '',
            'email' => array_key_exists('email', $this->user) ? $this->user['email'] : '',
        );
    }


    /**
     * Set your API key for this project
     *
     * @param string $key
     */
    public function setApiKey($key)
    {
        $this->options['apiKey'] = $key;
    }


    /**
     * This is used only for testing env, do not use this
     *
     * @param string $apiHost
     */
    public function setApiHost($apiHost)
    {
        $this->options['apiHost'] = $apiHost;
    }


    /**
     * This is used only for testing env, do not use this
     *
     * @param string $apiPath
     */
    public function setApiPath($apiPath)
    {
        $this->options['apiPath'] = $apiPath;
    }


    /**
     * Set array of strings to filter out variables before sending bug report.
     * This value will replace default values in property $filterKeys. For add new please use addFilterKeys()
     *
     * Example:
     * array(
     *  'password',
     *  'password_confirmation'
     * )
     *
     * @param array $filterKeys
     */
    public function setFilterKeys(Array $filterKeys)
    {
        $this->options['filterKeys'] = $filterKeys;
    }


    /**
     * Add array or string to filter out variables before sending bug report.
     *
     * Example:
     * addFilterKeys(array(
     *  'password',
     *  'password_confirmation'
     * ))
     * OR
     * addFilterKeys('secret-key')
     *
     * @param string|array $filterKeys
     */
    public function addFilterKeys($filterKeys)
    {
        if ( ! is_array($filterKeys)) {
            $filterKeys = array( $filterKeys );
        }

        $this->options['filterKeys'] = array_merge($this->options['filterKeys'], $filterKeys);
    }


    /**
     * Set code length included in bug report.
     *
     * @param int $codeLength
     */
    public function setCodeLength($codeLength)
    {
        $this->options['codeLength'] = $codeLength;
    }


    /**
     * @return int
     */
    public function getCodeLength()
    {
        return $this->options['codeLength'];
    }


    /**
     * @return string
     */
    public function getProjectKey()
    {
        return $this->options['projectKey'];
    }


    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->options['apiKey'];
    }


    /**
     * @return string
     */
    public function getApiHost()
    {
        return $this->options['apiHost'];
    }


    /**
     * @return string
     */
    public function getApiPath()
    {
        return $this->options['apiPath'];
    }


    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->options['version'];
    }


    /**
     * Check if key isset in filters
     *
     * @param string $key
     *
     * @return bool
     */
    public function inFilters($key)
    {
        return array_key_exists($key, $this->options['filterKeys']);
    }

}