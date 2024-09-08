<?php

namespace Focuson\WoocommerceDiscountAdvanced\Providers;

use Focuson\WoocommerceDiscountAdvanced\Controllers\DiscountController;
use Focuson\WoocommerceDiscountAdvanced\Models\Terms;
use Illuminate\Support\ServiceProvider;

class FieldServiceProvider extends ServiceProvider
{
    public function register()
    {
        add_action('woocommerce_coupon_options', [$this, 'add_field_apply_automatically'], 10, 0);

        add_action('woocommerce_coupon_options_usage_restriction', [$this, 'add_fields_to_restriction_tab'], 10, 0);

        add_filter('woocommerce_coupon_data_tabs', [$this, 'add_woocommerce_discount_user_history_tab']);

        add_action('woocommerce_coupon_data_panels', [$this, 'add_user_history_tab_content']);

        add_action('woocommerce_coupon_options_save', [DiscountController::class, 'store_wda_fields'], 10, 2);
    }

	public function add_field_apply_automatically()
    {
        woocommerce_wp_checkbox(array(
            'id'          => 'wda_apply_automatically',
            'label'       => __('Apply automatically', 'woocommerce_discount_advanced'),
            'description' => __('If checked, the coupon will be applied automatically to the cart.', 'woocommerce_discount_advanced'),
            'desc_tip'    => true,
        ));
    }

    public function add_fields_to_restriction_tab()
    {
        $tags = Terms::getProductTags()->pluck('name', 'term_id')->toArray();

        echo view('admin.quantity_restriction-fields');

        echo view('admin.tag_restriction-fields', [
            'tags' => $tags
        ]);
    }

	public function add_woocommerce_discount_user_history_tab($tabs)
    {
        $tabs['user_history_tab'] = array(
            'label'    => __('User History', 'woocommerce_discount_advanced'),
            'target'   => 'user_history_data',
            'class'    => 'user_history_tab',
            'icon'     => 'dashicons-admin-users',
            'priority' => 10,
        );

        return $tabs;
    }

	public function add_user_history_tab_content()
    {
        echo view('admin.user_history-tab');
    }
}
