<?php

namespace Yoti\Util;

class Config
{
    const CONFIG_FILE_NAME = 'config.ini';

    private $config = [];

    public static function getInstance()
    {
        static $instance = NULL;
        if ($instance === NULL) {
            $instance = new self();
        }
        return $instance;
    }

    private function __construct()
    {
        $this->loadData();
    }

    private function loadData()
    {
        $configFile = $this->getConfigFile();
        if (!file_exists($configFile)) {
            error_log("Config file {$configFile} is missing.");
            return;
        }

        if ($config = parse_ini_file($configFile)) {
            $this->config = $config;
        }
    }

    public function get($param)
    {
        return isset($this->config[$param]) ? $this->config[$param] : NULL;
    }

    private function getConfigFile()
    {
        return __DIR__ . '/../../../' . self::CONFIG_FILE_NAME;
    }

    private function __clone() {}

    /**
     * Make sleep magic method private, so nobody can serialize instance.
     */
    private function __sleep() {}

    /**
     * Make wakeup magic method private, so nobody can unserialize instance.
     */
    private function __wakeup() {}
}