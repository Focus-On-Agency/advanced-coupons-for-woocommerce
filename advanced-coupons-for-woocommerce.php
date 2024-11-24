<?php
/**
 * Plugin Name: Advanced Coupons for WooCommerce
 * Description: A discount management plugin for WooCommerce, which allows the creation of discounts based on rules such as amount, quantity, type, of products in the cart or even user role and etc...
 * Version: 4.0.0
 * Author: Focus On
 * Author URI: https://github.com/Focus-On-Agency
 * Text Domain: advanced-coupons-for-woocommerce
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.7.1
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

// Prevent plugin activation if WooCommerce is not active
register_activation_hook(__FILE__, 'focuson_advancedcoupons_check_requirements');

// PSR-4 Autoloader
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

// Initialize the plugin
add_action('plugins_loaded', 'focuson_advancedcoupons_init');
add_action('before_woocommerce_init', 'focuson_advancedcoupons_declare_woocommerce_compatibility');

/**
 * Initialize the plugin
*/
function focuson_advancedcoupons_init()
{
	// Load translations
	add_action('init', function () {
        load_plugin_textdomain(
            'advanced-coupons-for-woocommerce',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    });

	// Boot the plugin
    $plugin = new \Focuson\AdvancedCoupons\AdvancedCoupons();
    $plugin->boot();
}

/**
 * Check plugin requirements
*/
function focuson_advancedcoupons_check_requirements()
{
	if (!is_woocommerce_activated())
	{
		deactivate_plugins(plugin_basename(__FILE__));

		 // Mostra messaggio di errore
        wp_die(
            sprintf(
                /* translators: 1. URL link. */
                esc_html__('Advanced Coupons for WooCommerce requires %1$sWooCommerce%2$s to be installed and activated. The plugin has been deactivated.', 'advanced-coupons-for-woocommerce'),
                '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">',
                '</a>'
            ),
            esc_html__('Plugin Activation Error', 'advanced-coupons-for-woocommerce'),
            ['back_link' => true]
        );
    }

}

/**
 * Display an error message and deactivate the plugin
*/
function focuson_advancedcoupons_display_error_and_deactivate($message)
{
	add_action('admin_notices', function() use ($message) {
		echo '<div class="notice notice-error"><p><strong>Woo Advanced Discounts</strong>: ' . esc_html($message) . '</p></div>';
	});

	deactivate_plugins(plugin_basename(__FILE__));
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

/**
 * Check if WooCommerce is active.
 *
 * @return bool true if WooCommerce is active, otherwise false.
 */
function is_woocommerce_activated(): bool {
	return class_exists( 'woocommerce' );
}

add_action('deactivated_plugin', 'focuson_advancedcoupons_on_woocommerce_deactivation');
function focuson_advancedcoupons_on_woocommerce_deactivation($plugin, $network_deactivating) {
    if ($plugin === 'woocommerce/woocommerce.php') {
        deactivate_plugins(plugin_basename(__FILE__));
    }
}