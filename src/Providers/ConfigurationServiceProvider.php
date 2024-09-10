<?php

namespace Focuson\AdvancedCoupons\Providers;

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
            plugin_dir_path(__DIR__) . '../config/advanced_coupons_for_woocommerce.php', 
            'advanced_coupons_for_woocommerce'
        );
    }
}
