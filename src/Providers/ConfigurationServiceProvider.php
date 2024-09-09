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
        $this->mergeConfigFrom(
            plugin_dir_path(__DIR__) . '../config/woocommerce_discount_advanced.php', 
            'woocommerce_discount_advanced'
        );
    }
}
