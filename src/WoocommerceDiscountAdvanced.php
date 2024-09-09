<?php

namespace Focuson\WoocommerceDiscountAdvanced;

use Focuson\WoocommerceDiscountAdvanced\Controllers\DiscountController;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Roots\Acorn\Application;

class WoocommerceDiscountAdvanced
{
    /**
     * The application instance.
     *
     * @var \Roots\Acorn\Application
     */
    protected $app;

    /**
     * Create a new Example instance.
     *
     * @param  \Roots\Acorn\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        $configProviders = ServiceProvider::defaultProviders()->merge(config('woocommerce_discount_advanced.providers', []))
            ->toArray()
        ;

        foreach ($configProviders as $provider) {
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }

        foreach (glob(__DIR__ . '/Providers/*.php') as $providerFile) {
            $fileName = basename($providerFile, '.php');

            $provider = __NAMESPACE__ . '\\Providers\\' . $fileName;

            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }

        return $this;
    }

    public function boot()
    {
        add_filter('woocommerce_coupon_is_valid', [DiscountController::class, 'validate_wda'], 10, 2);

        add_action('woocommerce_before_calculate_totals', [DiscountController::class, 'wda_apply_automatic_coupons']);

        add_action('wp_login', [DiscountController::class, 'wda_apply_automatic_coupons'], 10, 2);

        add_action('woocommerce_process_shop_coupon_meta', [DiscountController::class, 'wda_clear_cache']);
    }

}
