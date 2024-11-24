=== Advanced Coupons for WooCommerce ===
Contributors: pcatapan
Donate link: https://donate.stripe.com/dR6dU04JV0kx1Z6dR6
Tags: woocommerce, discount, advanced, rules, management
Requires at least: 5.0
Tested up to: 6.7.1
Requires PHP: 7.4
Stable tag: 4.0.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Advanced discount management for WooCommerce with flexible rules based on quantity, user roles, and order history.

== Description ==

Advanced Coupons for WooCommerce is a plugin for WooCommerce that enables advanced discount management based on flexible rules. Configure custom discounts according to various criteria, such as product quantity, order history, user role, and product tags.

== Key Features ==

- **Quantity-based discounts**: Define discount rules based on the minimum or maximum quantity of products in the cart.
- **Order history-based discounts**: Create discounts based on the number of orders or the total amount spent by the user in past orders.
- **User roles**: Apply discounts to specific user roles, like VIP members or registered customers.
- **Integration with product tags**: Create discounts that apply only to specific product tags or exclude certain tags.
- **First order discount**: Apply discounts exclusively on the user’s first order.
- **Automatic application of coupons**: Coupons can be automatically applied if the defined conditions are met.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/woocommerce-discount-advanced` directory or install the plugin directly through the WordPress plugins screen.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Configure discount rules in **WooCommerce > Marketing > Coupons**.

== Frequently Asked Questions ==

= How can I automatically apply coupons? =  
Configure the coupons to be automatically applied when the defined conditions are met. This option is available in the **General** tab section.

= Does the plugin support HPOS (High-Performance Order Storage)? =  
Yes, **Advanced Coupons for WooCommerce** is fully compatible with HPOS.

= Can I limit discounts to specific user roles? =  
Yes, you can configure discounts that only apply to users with specific roles.

== Changelog ==

= 4.0.0 =
* Compatibility update with WooCommerce 9.4.2 and WordPress 6.7.1.
* Remove all dependencies

= 3.0.3 =
* Compatibility update with WooCommerce 9.0 and WordPress 6.6.
* Improved caching and query performance for coupon rules.

= 3.0.2 =
* Minor bug fixes and performance improvements.

= 3.0.1 =
* Initial release with advanced discount management features.

== Upgrade Notice ==

= 3.0.3 =
Upgrade to maintain compatibility with the latest WooCommerce and WordPress versions.

== Usage ==

After activation, navigate to **WooCommerce > Marketing > Coupons** to configure the discount rules based on quantity, tags, categories, or other criteria. Configure whether the discount is automatically applied or requires a coupon code.