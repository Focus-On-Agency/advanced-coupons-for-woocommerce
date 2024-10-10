<div class="options_group">
	<?php
	woocommerce_wp_select(array(
		'id' => 'wda_product_tags_included',
		'label' => __('Product Tags', 'advanced-coupons-for-woocommerce'),
		'description' => __('Include the product tags that this coupon will be applied to.', 'advanced-coupons-for-woocommerce'),
		'desc_tip' => true,
		'options' => $tags,
		'class' => 'wc-enhanced-select',
		'style' => 'width: 50%;',
		'name' => 'wda_product_tags_included[]',
		'custom_attributes' => array(
			'multiple' => 'multiple',
			'data-placeholder' => __('Any tags', 'advanced-coupons-for-woocommerce'),
		),
	));
	?>

	<?php
	woocommerce_wp_select(array(
		'id' => 'wda_product_tags_excluded',
		'label' => __('Exclude tags', 'advanced-coupons-for-woocommerce'),
		'description' => __('Exclude the product tags that this coupon will be applied to.', 'advanced-coupons-for-woocommerce'),
		'desc_tip' => true,
		'options' => $tags,
		'class' => 'wc-enhanced-select',
		'style' => 'width: 50%;',
		'name' => 'wda_product_tags_excluded[]',
		'custom_attributes' => array(
			'multiple' => 'multiple',
			'data-placeholder' => __('Any tags', 'advanced-coupons-for-woocommerce'),
		),
	));
	?>
</div>