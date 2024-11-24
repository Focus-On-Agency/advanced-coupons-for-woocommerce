<?php

namespace Focuson\AdvancedCoupons\Providers;

use Focuson\AdvancedCoupons\Controllers\DiscountController;

class FieldServiceProvider
{
	private $base_dir;

	public function __construct()
	{
		$this->base_dir = plugin_dir_path(__FILE__) . '../../resources/views';
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
			'label'       => __('Apply automatically', 'advanced-coupons-for-woocommerce'),
			'description' => __('If checked, the coupon will be applied automatically to the cart.', 'advanced-coupons-for-woocommerce'),
			'desc_tip'    => true,
		));
	}

	public function add_fields_to_restriction_tab()
	{
		$tags = [];
		$terms = get_terms([
			'taxonomy'   => 'product_tag',
			'hide_empty' => false,
		]);

		if (!is_wp_error($terms)) {
			foreach ($terms as $term) {
				$tags[$term->term_id] = $term->name;
			}
		}

		$this->get_view('admin/quantity_restriction-fields');

		$this->get_view('admin/tag_restriction-fields', ['tags' => $tags]);
	}

	public function add_woocommerce_discount_user_history_tab($tabs)
	{
		$tabs['user_history_tab'] = array(
			'label'    => __('User History', 'advanced-coupons-for-woocommerce'),
			'target'   => 'user_history_data',
			'class'    => 'user_history_tab',
			'icon'     => 'dashicons-admin-users',
			'priority' => 10,
		);

		return $tabs;
	}

	public function add_user_history_tab_content()
	{
		$this->get_view('admin/user_history-tab');
	}

	private function get_view($view, $data = [])
	{
		$view_path = $this->base_dir . '/' . $view . '.php';

		if (file_exists($view_path)) {
			extract($data);

			include $view_path;
		} else {
			echo '<p>' . esc_html__('View not found:', 'advanced-coupons-for-woocommerce') . ' ' . esc_html($view) . '</p>';
		}
	}
}
