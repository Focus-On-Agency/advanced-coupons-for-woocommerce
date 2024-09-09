<?php

namespace Focuson\WoocommerceDiscountAdvanced\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Upload configurations from the given path
     * 
     * @return void
     */
    public function register()
    {
        $this->app['view']->addNamespace(
            config('woocommerce_discount_advanced.slug'),
            plugin_dir_path(__DIR__) . '../resources/views'
        );
    }
}
