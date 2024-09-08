<div class="options_group">
    @php
    woocommerce_wp_text_input(array(
        'id'          => 'wda_min_quantity',
        'label'       => __('Minimum quantity', 'woocommerce_discount_advanced'),
        'description' => __('The minimum quantity of products allowed in the cart.', 'woocommerce_discount_advanced'),
        'type'        => 'number',
        'desc_tip'    => true,
        'placeholder' => __('No minimum', 'woocommerce'),
        'custom_attributes' => array(
            'min'  => '0',
            'step' => '1',
        ),
    ));

    woocommerce_wp_text_input(array(
        'id'          => 'wda_max_quantity',
        'label'       => __('Maximum quantity', 'woocommerce_discount_advanced'),
        'description' => __('The maximum quantity of products allowed in the cart.', 'woocommerce_discount_advanced'),
        'type'        => 'number',
        'desc_tip'    => true,
        'placeholder' => __('No maximum', 'woocommerce'),
        'custom_attributes' => array(
            'min'  => '0',
            'step' => '1',
        ),
    ));
    @endphp
</div>