<?php

namespace Focuson\AdvancedCoupons\Controllers;

class DiscountController
{
	static public function store_wda_fields($post_id, $coupon)
	{
		// Verify nonce
		if (!current_user_can('manage_woocommerce')) {
			wp_die(esc_html__('Security check failed. Please try again.', 'advanced-coupons-for-woocommerce'));
		}
		
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
			$value = isset($_POST[$field]) ? sanitize_text_field(wp_unslash($_POST[$field])) : $default;

			update_post_meta($post_id, $field, $value);
		}

		$first_order = isset($_POST['wda_first_order']) ? 'yes' : 'no';
		update_post_meta($post_id, 'wda_first_order', $first_order);

		$apply_automatically = isset($_POST['wda_apply_automatically']) ? 'yes' : 'no';
		update_post_meta($post_id, 'wda_apply_automatically', $apply_automatically);
	}

	static public function validate_wda($valid, $coupon)
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

		$user_orders = self::get_user_order_count($user->ID);
		$total_spent = wc_get_customer_total_spent($user->ID);

		// 1. Check min and max quantity
		if ($min_quantity && $cart_quantity < $min_quantity) {
			return self::wda_error_response(
				__('The cart does not meet the minimum quantity required.', 'advanced-coupons-for-woocommerce'),
			);
		}
		if ($max_quantity && $cart_quantity > $max_quantity) {
			return self::wda_error_response(
				__('The cart exceeds the maximum quantity allowed.', 'advanced-coupons-for-woocommerce'),
			);
		}

		// 2. Check product tags
		$product_ids = array_column(WC()->cart->get_cart(), 'product_id');
		$product_tags = wp_get_object_terms($product_ids, 'product_tag', array('fields' => 'ids'));

		if ($tags_included && !array_intersect($tags_included, $product_tags)) {
			return self::wda_error_response(
				__('The cart does not contain the required product tags.', 'advanced-coupons-for-woocommerce')
			);
		}
		if ($tags_excluded && array_intersect($tags_excluded, $product_tags)) {
			return self::wda_error_response(
				__('The cart contains excluded product tags.', 'advanced-coupons-for-woocommerce')
			);
		}

		// 3. Check min and max orders
		if ($min_orders && $user_orders < $min_orders) {
			return self::wda_error_response(
				__('You do not have enough orders to apply this coupon.', 'advanced-coupons-for-woocommerce'),
			);
		}
		if ($max_orders && $user_orders > $max_orders) {
			return self::wda_error_response(
				__('You have too many orders to apply this coupon.', 'advanced-coupons-for-woocommerce'),
			);
		}

		// 4. Check min and max total spent
		if ($min_total_spent && $total_spent < $min_total_spent) {
			return self::wda_error_response(
				__('You have not spent enough to apply this coupon.', 'advanced-coupons-for-woocommerce')
			);
		}
		if ($max_total_spent && $total_spent > $max_total_spent) {
			return self::wda_error_response(
				__('You have spent too much to apply this coupon.', 'advanced-coupons-for-woocommerce')
			);
		}

		// 5. Check user role
		if ($user_roles && !array_intersect($user_roles, $user->roles)) {
			return self::wda_error_response(
				__('This coupon is not valid for your user role.', 'advanced-coupons-for-woocommerce')
			);
		}

		// 6. Check first order
		if (!$first_order && $user_orders > 0) {
			return self::wda_error_response(
				__('This coupon is only valid for the first order.', 'advanced-coupons-for-woocommerce')
			);
		}

		return $valid;
	}

	/**
	 * Get the total number of completed, processing, or on-hold orders for a user.
	 */
	private static function get_user_order_count($user_id)
	{
		$query_args = [
			'customer_id' => $user_id,
			'status'      => ['wc-completed', 'wc-processing', 'wc-on-hold'],
			'return'      => 'ids',
			'limit'       => -1,
		];
		$orders = wc_get_orders($query_args);

		return $orders ? count($orders) : 0;
	}

	static private function wda_error_response($message)
	{
		add_filter('woocommerce_coupon_error', function($error, $coupon) use ($message) {
			return $message;
		}, 10, 2);

		return false;
	}

	/**
	 * Apply automatic coupons
	 */
	static public function wda_apply_automatic_coupons()
    {
    	$cache_key = 'wda_automatic_coupons';
    
    	// Recupera i coupon automatici dalla cache, se disponibili
    	$coupons = get_transient($cache_key);
    
    	if ($coupons === false) {
    		// Ottieni i coupon che devono essere applicati automaticamente
    		$coupons = get_posts([
    			'post_type'      => 'shop_coupon',
    			'posts_per_page' => 50,
    			'meta_query'     => [
    				[
    					'key'   => 'wda_apply_automatically',
    					'value' => 'yes',
    				]
    			],
    			'post_status'    => 'publish',
    		]);
    
    		// Memorizza i coupon in cache per un anno
    		set_transient($cache_key, $coupons, YEAR_IN_SECONDS);
    	}
    
    	$applied_coupons = WC()->cart->get_applied_coupons();
    
    	// Verifica se esiste già un coupon non cumulabile applicato
    	foreach ($applied_coupons as $applied_coupon_code) {
    		$applied_coupon = new \WC_Coupon($applied_coupon_code);
    
    		// Se un coupon non cumulabile è applicato, termina
    		if ($applied_coupon->get_individual_use()) {
    			return;
    		}
    	}
    
    	// Applica i coupon automatici validi che non sono già stati applicati
    	foreach ($coupons as $coupon) {
    		$coupon_obj = new \WC_Coupon($coupon->ID);
    
    		// Aggiungi il coupon solo se non è già applicato e risulta valido
    		if (!in_array($coupon_obj->get_code(), $applied_coupons) && $coupon_obj->is_valid()) {
    			WC()->cart->add_discount($coupon_obj->get_code());
    		}
    	}
    }

	static public function wda_clear_cache($post_id)
	{
		if(get_post_meta($post_id, 'wda_apply_automatically', true) === 'yes')
		{
			delete_transient('wda_automatic_coupons');            
		}
	}
}
