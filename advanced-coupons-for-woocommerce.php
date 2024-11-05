<?php
/**
 * Plugin Name: Advanced Coupons for WooCommerce
 * Description: A discount management plugin for WooCommerce, which allows the creation of discounts based on rules such as amount, quantity, type, of products in the cart or even user role and etc...
 * Version: 3.0.7
 * Author: Focus On
 * Author URI: https://github.com/Focus-On-Agency
 * Text Domain: advanced-coupons-for-woocommerce
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.6
 * Requires PHP: 7.4
 * WC requires at least: 9.0
 * WC tested up to: 9.0
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Tags: woocommerce, discount, advanced, rules, management
 * Contributors: pcatapan
 * GitHub Plugin URI:
 * Donate link: https://donate.stripe.com/dR6dU04JV0kx1Z6dR6
 */

if (!defined('ABSPATH')) exit;

// Initialize the plugin
add_action('plugins_loaded', 'focuson_advancedcoupons_init');
add_action('before_woocommerce_init', 'focuson_advancedcoupons_declare_woocommerce_compatibility');

/**
 * Initialize the plugin
*/
function focuson_advancedcoupons_init()
{
	// Check plugin requirements
	if (!focuson_advancedcoupons_check_requirements()) return;

	// Load dependencies
	focuson_advancedcoupons_load_dependencies();

	// Load translations
	load_plugin_textdomain('advanced-coupons-for-woocommerce', false, dirname(plugin_basename(__FILE__)) . '/languages');

	// Boot the plugin
	(new \Focuson\AdvancedCoupons\AdvancedCoupons())->boot();
}

/**
 * Check plugin requirements
*/
function focuson_advancedcoupons_check_requirements()
{
	// Check for Composer autoloader
	if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
		focuson_advancedcoupons_display_error_and_deactivate(__('Error loading plugin. Autoload not found.', 'advanced-coupons-for-woocommerce'));
		return false;
	}

	return true;
}

/**
 * Load plugin dependencies
*/
function focuson_advancedcoupons_load_dependencies()
{
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Display an error message and deactivate the plugin
*/
function focuson_advancedcoupons_display_error_and_deactivate($message)
{
	add_action('admin_notices', function() use ($message) {
		echo '<div class="notice notice-error"><p><strong>Woo Advanced Discounts</strong>: ' . esc_html($message) . '</p></div>';
	});

	if (function_exists('deactivate_plugins')) {
		deactivate_plugins(plugin_basename(__FILE__));
	}
}

/**
 * Declare compatibility with WooCommerce custom order tables
*/
function focuson_advancedcoupons_declare_woocommerce_compatibility()
{
	if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
	}
}