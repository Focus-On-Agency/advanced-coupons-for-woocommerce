<?php

namespace Focuson\AdvancedCoupons\Support;

class Config
{
    protected $config = [];

    public function __construct()
    {
        $configPath = __DIR__ . '/../../config';

        // Carica tutti i file di configurazione
        foreach (glob($configPath . '/*.php') as $file) {
            $this->config[basename($file, '.php')] = require $file;
        }
    }

    /**
     * Ottiene un valore di configurazione.
     *
     * Supporta l'accesso annidato tramite notazione a punti, es. 'advanced_coupons_for_woocommerce.option1'.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $config = $this->config;

        // Scorri i livelli della chiave
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