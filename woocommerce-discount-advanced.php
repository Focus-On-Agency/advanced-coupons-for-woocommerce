<?php
/**
 * Plugin Name: Woocommerce Discount Advanced
 * Description: A discount management plugin for WooCommerce, which allows the creation of discounts based on rules such as amount, quantity, type, of products in the cart or even user role and etc...
 * Version: 1.0.0
 * Author: Focus On
 * Author URI: https://github.com/Focus-On-Agency
 * Text Domain: woocommerce_discount_advanced
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
 * Stable tag: 1.0.0
 * GitHub Plugin URI:
 * Donate link: https://donate.stripe.com/dR6dU04JV0kx1Z6dR6
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('plugins_loaded', function () {
    if (!class_exists(\Roots\Acorn\Bootloader::class) && file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
    }
    
    if (class_exists(\Roots\Acorn\Bootloader::class)) {
        $app = \Roots\Acorn\Application::getInstance();
        $bootloader = \Roots\Acorn\Bootloader::getInstance();
        if (!$app->isBooted()) {
            $bootloader->boot();
        }
    } else {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>Woo Advanced Discounts</strong>' . __(' error during plugin loading. Acorn not found.', 'woocommerce_discount_advanced') . '</p></div>';
        });

        deactivate_plugins(plugin_basename(__FILE__));
        return;
    }


    load_plugin_textdomain('woocommerce_discount_advanced', false, dirname(plugin_basename(__FILE__)) . '/languages');

    if (class_exists(\Focuson\WoocommerceDiscountAdvanced\WoocommerceDiscountAdvanced::class)) {
        (new \Focuson\WoocommerceDiscountAdvanced\WoocommerceDiscountAdvanced(app()))
            ->register()
            ->boot()
        ;

    } else {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>Woo Advanced Discounts</strong>' . __(' error during plugin loading. Plugin class not found.', 'woocommerce_discount_advanced') . '</p></div>';
        });

        deactivate_plugins(plugin_basename(__FILE__));
        return;
    }
});

add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});
