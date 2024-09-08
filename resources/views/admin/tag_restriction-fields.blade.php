<div class="options_group">
	@php
	woocommerce_wp_select(array(
		'id' => 'wda_product_tags_included',
		'label' => __('Product Tags', 'woocommerce_discount_advanced'),
		'description' => __('Include the product tags that this coupon will be applied to.', 'woocommerce_discount_advanced'),
		'desc_tip' => true,
		'options' => $tags,
		'class' => 'wc-enhanced-select',
		'style' => 'width: 50%;',
		'name' => 'wda_product_tags_included[]',
		'custom_attributes' => array(
			'multiple' => 'multiple',
			'data-placeholder' => __('Any tags', 'woocommerce_discount_advanced'),
		),
	));
	@endphp

	@php
	woocommerce_wp_select(array(
		'id' => 'wda_product_tags_excluded',
		'label' => __('Exclude tags', 'woocommerce_discount_advanced'),
		'description' => __('Exclude the product tags that this coupon will be applied to.', 'woocommerce_discount_advanced'),
		'desc_tip' => true,
		'options' => $tags,
		'class' => 'wc-enhanced-select',
		'style' => 'width: 50%;',
		'name' => 'wda_product_tags_excluded[]',
		'custom_attributes' => array(
			'multiple' => 'multiple',
			'data-placeholder' => __('Any tags', 'woocommerce_discount_advanced'),
		),
	));
	@endphp
</div>