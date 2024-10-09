<?php

namespace Focuson\AdvancedCoupons;

use Focuson\AdvancedCoupons\Controllers\DiscountController;
use Focuson\AdvancedCoupons\Support\BasePlugin;

class AdvancedCoupons extends BasePlugin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function boot()
    {
        add_filter('woocommerce_coupon_is_valid', [DiscountController::class, 'validate_wda'], 10, 2);

        add_action('woocommerce_before_calculate_totals', [DiscountController::class, 'wda_apply_automatic_coupons']);

        add_action('wp_login', [DiscountController::class, 'wda_apply_automatic_coupons'], 10, 2);

        add_action('woocommerce_process_shop_coupon_meta', [DiscountController::class, 'wda_clear_cache']);
    }

}
