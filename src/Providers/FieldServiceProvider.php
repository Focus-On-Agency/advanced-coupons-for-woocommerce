<?php

namespace Focuson\AdvancedCoupons\Providers;

use Focuson\AdvancedCoupons\Controllers\DiscountController;
use Focuson\AdvancedCoupons\Models\Terms;
use Focuson\AdvancedCoupons\Support\BaseServiceProvider;

class FieldServiceProvider extends BaseServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
    }
    
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
            'label'       => __('Apply automatically', 'advanced_coupons_for_woocommerce'),
            'description' => __('If checked, the coupon will be applied automatically to the cart.', 'advanced_coupons_for_woocommerce'),
            'desc_tip'    => true,
        ));
    }

    public function add_fields_to_restriction_tab()
    {
        $tags = Terms::getProductTags()->pluck('name', 'term_id')->toArray();

        $view = $this->config->get('advanced_coupons_for_woocommerce.slug') . '::admin.quantity_restriction-fields';
        echo $this->view->render($view);

        $view = $this->config->get('advanced_coupons_for_woocommerce.slug') . '::admin.tag_restriction-fields';
        echo $this->view->render($view, [
            'tags' => $tags
        ]);
    }

	public function add_woocommerce_discount_user_history_tab($tabs)
    {
        $tabs['user_history_tab'] = array(
            'label'    => __('User History', 'advanced_coupons_for_woocommerce'),
            'target'   => 'user_history_data',
            'class'    => 'user_history_tab',
            'icon'     => 'dashicons-admin-users',
            'priority' => 10,
        );

        return $tabs;
    }

	public function add_user_history_tab_content()
    {
        $view = $this->config->get('advanced_coupons_for_woocommerce.slug') . '::admin.user_history-tab';
        echo $this->view->render($view);
    }
}
