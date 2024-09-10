<?php

namespace Focuson\AdvancedCoupons\Providers;

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
            config('advanced_coupons_for_woocommerce.slug'),
            plugin_dir_path(__DIR__) . '../resources/views'
        );
    }
}
