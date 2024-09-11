
# Advanced Coupons for Woocommerce

**Advanced Coupons for Woocommerce** is a plugin for WooCommerce that enables advanced discount management based on flexible rules. Administrators can configure custom discounts according to various criteria such as product quantity, order history, user role, product tags, and more.

[![Support Us](https://img.shields.io/badge/Donate-Support%20Us-blue.svg)](https://donate.stripe.com/dR6dU04JV0kx1Z6dR6)

## Description

The plugin allows you to manage complex discounts by defining specific rules such as:
- Minimum or maximum quantity of products in the cart.
- Total amount spent by the user on previous orders.
- Number of past orders placed by the user.
- Apply discounts only for specific product tags.
- Limit discounts to specific user roles.
- Automatically apply coupons when the defined rules are met.

## Key Features

- **Quantity-based discounts**: Define discount rules based on the minimum or maximum quantity of products in the cart.
- **Order history-based discounts**: Create custom discounts based on the number of orders or the total amount spent by the user in past orders.
- **User roles**: Apply discounts only to specific user roles, such as VIP members or registered customers.
- **Integration with product tags**: Create discounts that apply only to specific product tags, or exclude certain tags.
- **First order discount**: Apply discounts exclusively on the user’s first order.
- **Automatically apply coupons**: Coupons can be automatically applied if the defined conditions are met.

## Requirements

- **WooCommerce**: Requires version 9.0 or higher.
- **WordPress**: Requires version 5.0 or higher.
- **PHP**: Requires PHP 7.4 or higher.

## Installation

You can install the plugin in two ways:

### 1. Via Composer
1. Add the plugin repository to your composer.json by running:
   ```bash
   composer config repositories.advanced-coupons-for-woocommerce git https://github.com/Focus-On-Agency/advanced-coupons-for-woocommerce.git
   ```
2. Install the plugin:
   ```bash
   composer require advanced-coupons-for-woocommerce
   ```
3. Go to **Plugins > Add New** and activate **Advanced Coupons for Woocommerce**.
4. Configure the discount rules in **WooCommerce > Marketing > Coupons**.

### 2. Download the ZIP file
1. Go to the GitHub repository page and download the plugin's ZIP file.
2. Upload the ZIP file via **WordPress > Plugins > Add New > Upload Plugin**.
3. Activate **Advanced Coupons for Woocommerce** and configure the discount rules in **WooCommerce > Marketing > Coupons**.

## Star the repository

If you find this plugin useful, please consider leaving a star on [GitHub](https://github.com/Focus-On-Agency/advanced-coupons-for-woocommerce). Your support is greatly appreciated!

## Usage

After activation, go to **WooCommerce > Marketing > Coupons** to configure the rules:
1. Define discount rules based on product quantity, tags, categories, or other criteria.
2. Configure whether the discount is automatically applied or only if the user enters the coupon code.
3. Apply restrictions for specific user roles or for users who have placed a certain number of orders.

## Compatibility

- **WooCommerce**: Tested up to version 9.0.
- **WordPress**: Tested up to version 6.6.
- **HPOS (High-Performance Order Storage) support**: Fully compatible.

## Translations

The plugin is translated into Italian, English, French, and German. You can add more languages by uploading `.po` and `.mo` files to the `/languages` folder.

## License

This plugin is distributed under the GPL-2.0+ license. You can find more information at the following link: [GPL-2.0 License](http://www.gnu.org/licenses/gpl-2.0.txt).

## Contributors

- **[pcatapan]**(https://github.com/pcatapan)

## Donation Links

If you find this plugin useful and want to support ongoing development, consider making a donation: [Donate here](https://donate.stripe.com/dR6dU04JV0kx1Z6dR6).

## FAQ

**Q: How can I automatically apply coupons?**  
A: You can configure the coupons to be automatically applied when the defined conditions are met. This option is available in the **General** tab section.

**Q: Does the plugin support HPOS (High-Performance Order Storage)?**  
A: Yes, **Advanced Coupons for Woocommerce** is fully compatible with HPOS.

**Q: Can I limit discounts to specific user roles?**  
A: Yes, you can configure discounts that only apply to users with specific roles.