<?php

namespace Focuson\AdvancedCoupons;

use Focuson\AdvancedCoupons\Controllers\DiscountController;

class AdvancedCoupons
{
    public function __construct()
    {
        $this->registerProviders();
    }

    public function boot()
    {
        /**
         * Validate custom discount rules for WooCommerce coupons.
         */
        add_filter('woocommerce_coupon_is_valid', [DiscountController::class, 'validate_wda'], 10, 2);

        /**
         * Apply automatic coupons before calculating cart totals.
         */
        add_action('woocommerce_before_calculate_totals', [DiscountController::class, 'wda_apply_automatic_coupons']);

        /**
         * Apply automatic coupons after user login.
         */
        add_action('wp_login', [DiscountController::class, 'wda_apply_automatic_coupons'], 10, 2);

        /**
         * Clear coupon-related cache when processing a coupon in admin.
         */
        add_action('woocommerce_process_shop_coupon_meta', [DiscountController::class, 'wda_clear_cache']);
    }

    protected function registerProviders()
    {
        foreach (glob(__DIR__ . '/Providers/*.php') as $providerFile)
		{
            $fileName = basename($providerFile, '.php');
			$namespaceBase = str_replace('Support', '', __NAMESPACE__);
			$providerClass = $namespaceBase . '\\Providers\\' . $fileName;

            if (class_exists($providerClass)) {
                $provider = new $providerClass($this);
                
                // Check if the provider has a register method
                if (method_exists($provider, 'register')) {
                    $provider->register();
                }
            }
        }
    }
}
