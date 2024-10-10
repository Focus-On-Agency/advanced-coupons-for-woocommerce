<?php

namespace Focuson\AdvancedCoupons\Controllers;

use Focuson\AdvancedCoupons\Support\BaseController;

use Carbon\Carbon;
use Focuson\AdvancedCoupons\Support\Cache;

class DiscountController
{
	public static function store_wda_fields($post_id, $coupon)
	{
		$fields = [
			'wda_min_quantity'			=> null,
			'wda_max_quantity'			=> null,
			'wda_product_tags_included' => [],
			'wda_product_tags_excluded' => [],
			'wda_min_orders'			=> null,
			'wda_max_orders'			=> null,
			'wda_min_total_spent'		=> null,
			'wda_max_total_spent'		=> null,
			'wda_user_roles'			=> [],
		];
		
		foreach ($fields as $field => $default) {
			$value = isset($_POST[$field]) ? wc_clean($_POST[$field]) : $default;

			update_post_meta($post_id, $field, $value);
		}

		$first_order = isset($_POST['wda_first_order']) ? 'yes' : 'no';
		update_post_meta($post_id, 'wda_first_order', $first_order);

		$apply_automatically = isset($_POST['wda_apply_automatically']) ? 'yes' : 'no';
		update_post_meta($post_id, 'wda_apply_automatically', $apply_automatically);
	}

	public static function validate_wda($valid, $coupon)
	{
		// Get coupon meta
		$coupon_meta = get_post_meta($coupon->get_id());

		$min_quantity = (int)($coupon_meta['wda_min_quantity'][0] ?? null);
		$max_quantity = (int)($coupon_meta['wda_max_quantity'][0] ?? null);

		$tags_included = !empty($coupon_meta['wda_product_tags_included'][0]) 
			? maybe_unserialize($coupon_meta['wda_product_tags_included'][0]) 
			: []
		;
		$tags_excluded = !empty($coupon_meta['wda_product_tags_excluded'][0]) 
			? maybe_unserialize($coupon_meta['wda_product_tags_excluded'][0]) 
			: []
		;

		$min_orders = (int)($coupon_meta['wda_min_orders'][0] ?? null);
		$max_orders = (int)($coupon_meta['wda_max_orders'][0] ?? null);

		$min_total_spent = (float)($coupon_meta['wda_min_total_spent'][0] ?? null);
		$max_total_spent = (float)($coupon_meta['wda_max_total_spent'][0] ?? null);

		$user_roles = !empty($coupon_meta['wda_user_roles'][0]) 
			? maybe_unserialize($coupon_meta['wda_user_roles'][0]) 
			: []
		;
		$first_order = (bool)($coupon_meta['wda_first_order'][0] ?? false);


		// Get cart quantity and user data
		$cart_quantity = WC()->cart->get_cart_contents_count();
		$user = wp_get_current_user();

		$user_orders = Cache::remember('user_' . $user->ID . '_order_count', 10 * 60, function () use ($user) {
			// Get only completed, processing and on-hold orders
			$statuses = ['wc-completed', 'wc-processing', 'wc-on-hold'];
			$orders = wc_get_order([
				'customer_id' => $user->ID, 
				'post_status' => $statuses,
				'return' => 'ids'
			]);

			return $orders ? count($orders) : 0;
		});

		$total_spent = Cache::remember('user_' . $user->ID . '_total_spent', 10 * 60, function () use ($user) {
			return wc_get_customer_total_spent($user->ID);
		});

		// 1. Check min and max quantity
		if ($min_quantity && $cart_quantity < $min_quantity) {
			return self::wda_error_response(
				__('The cart does not meet the minimum quantity required.', 'advanced_coupons_for_woocommerce'),
			);
		}
		if ($max_quantity && $cart_quantity > $max_quantity) {
			return self::wda_error_response(
				__('The cart exceeds the maximum quantity allowed.', 'advanced_coupons_for_woocommerce'),
			);
		}

		// 2. Check product tags
		$product_ids = array_column(WC()->cart->get_cart(), 'product_id');
		$product_tags = wp_get_object_terms($product_ids, 'product_tag', array('fields' => 'ids'));

		if ($tags_included && !array_intersect($tags_included, $product_tags)) {
			return self::wda_error_response(
				__('The cart does not contain the required product tags.', 'advanced_coupons_for_woocommerce')
			);
		}
		if ($tags_excluded && array_intersect($tags_excluded, $product_tags)) {
			return self::wda_error_response(
				__('The cart contains excluded product tags.', 'advanced_coupons_for_woocommerce')
			);
		}

		// 3. Check min and max orders
		if ($min_orders && $user_orders < $min_orders) {
			return self::wda_error_response(
				__('You do not have enough orders to apply this coupon.', 'advanced_coupons_for_woocommerce'),
			);
		}
		if ($max_orders && $user_orders > $max_orders) {
			return self::wda_error_response(
				__('You have too many orders to apply this coupon.', 'advanced_coupons_for_woocommerce'),
			);
		}

		// 4. Check min and max total spent
		if ($min_total_spent && $total_spent < $min_total_spent) {
			return self::wda_error_response(
				__('You have not spent enough to apply this coupon.', 'advanced_coupons_for_woocommerce')
			);
		}
		if ($max_total_spent && $total_spent > $max_total_spent) {
			return self::wda_error_response(
				__('You have spent too much to apply this coupon.', 'advanced_coupons_for_woocommerce')
			);
		}

		// 5. Check user role
		if ($user_roles && !array_intersect($user_roles, $user->roles)) {
			return self::wda_error_response(
				__('This coupon is not valid for your user role.', 'advanced_coupons_for_woocommerce')
			);
		}

		// 6. Check first order
		if (!$first_order && $user_orders > 0) {
			return self::wda_error_response(
				__('This coupon is only valid for the first order.', 'advanced_coupons_for_woocommerce')
			);
		}

		return $valid;
	}

	private static function wda_error_response($message)
	{
		add_filter('woocommerce_coupon_error', function($error, $coupon) use ($message) {
			return $message;
		}, 10, 2);

		return false;
	}

	public static function wda_apply_automatic_coupons()
	{
		$cache_duration = Carbon::now()->addYear();

		$coupons = Cache::remember('wda_automatic_coupons', $cache_duration, function () {
			return get_posts([
				'post_type'      => 'shop_coupon',
				'posts_per_page' => -1,
				'meta_query'     => [
					[
						'key'   => 'wda_apply_automatically',
						'value' => 'yes',
					]
				],
				'post_status'    => 'publish',
			]);
		});

		$applied_coupons = WC()->cart->get_applied_coupons();

		foreach ($coupons as $coupon) {
			$coupon_obj = new \WC_Coupon($coupon->ID);
			$coupon_code = $coupon_obj->get_code();
	
			// Check if the coupon has already been applied
			if (!in_array($coupon_code, $applied_coupons) && $coupon_obj->is_valid()) {
				WC()->cart->add_discount($coupon_code);
			}
		}
	}

	public static function wda_clear_cache($post_id)
	{
		if(get_post_meta($post_id, 'wda_apply_automatically', true) === 'yes')
		{
			Cache::forget('wda_automatic_coupons');            
		}
	}
}
