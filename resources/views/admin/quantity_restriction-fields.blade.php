<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="options_group">
    <?php
    woocommerce_wp_text_input(array(
        'id'          => 'wda_min_quantity',
        'label'       => __('Minimum quantity', 'advanced-coupons-for-woocommerce'),
        'description' => __('The minimum quantity of products allowed in the cart.', 'advanced-coupons-for-woocommerce'),
        'type'        => 'number',
        'desc_tip'    => true,
        'placeholder' => __('No minimum', 'advanced-coupons-for-woocommerce'),
        'custom_attributes' => array(
            'min'  => '0',
            'step' => '1',
        ),
    ));

    woocommerce_wp_text_input(array(
        'id'          => 'wda_max_quantity',
        'label'       => __('Maximum quantity', 'advanced-coupons-for-woocommerce'),
        'description' => __('The maximum quantity of products allowed in the cart.', 'advanced-coupons-for-woocommerce'),
        'type'        => 'number',
        'desc_tip'    => true,
        'placeholder' => __('No maximum', 'advanced-coupons-for-woocommerce'),
        'custom_attributes' => array(
            'min'  => '0',
            'step' => '1',
        ),
    ));
    ?>
</div>