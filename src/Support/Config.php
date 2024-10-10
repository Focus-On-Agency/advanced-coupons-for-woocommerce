<?php

namespace Focuson\AdvancedCoupons\Support;

class Config
{
    protected $config = [];

    public function __construct()
    {
        $configPath = __DIR__ . '/../../config';

        // Get all configuration files
        foreach (glob($configPath . '/*.php') as $file) {
            $this->config[basename($file, '.php')] = require $file;
        }
    }

    /**
     * Get a configuration value
     *
     * @param string $key
     * @param mixed $default
     * 
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $config = $this->config;

        // Navigate through the configuration array
        foreach ($keys as $k) {
            if (array_key_exists($k, $config)) {
                $config = $config[$k];
            } else {
                return $default;
            }
        }

        return $config;
    }
}