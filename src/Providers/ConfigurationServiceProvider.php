<?php

namespace Focuson\WoocommerceDiscountAdvanced\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigurationServiceProvider extends ServiceProvider
{
    /**
     * Upload configurations from the given path
     * 
     * @return void
     */
    public function register()
    {
        $files = glob(__DIR__ . '/../../config/*.php');

        foreach ($files as $file) {
            $config = require $file;

            foreach ($config as $key => $value) {
                $this->app['config']->set($key, $value);
            }
        }
    }
}
