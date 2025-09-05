<?php

function enlightenment_woocommerce_template_functions( $functions ) {
	$functions['enlightenment_woocommerce_user_account']   = __( 'User Account Menu',           'enlightenment' );
	$functions['enlightenment_shopping_cart']              = __( 'Shopping Cart',               'enlightenment' );
	$functions['enlightenment_product_categories']         = __( 'Product Categories',          'enlightenment' );
	$functions['woocommerce_output_all_notices']           = __( 'WooCommerce Notices',         'enlightenment' );
	$functions['woocommerce_result_count']                 = __( 'Product Search Result Count', 'enlightenment' );
	$functions['woocommerce_catalog_ordering']             = __( 'Product Catalog Ordering',    'enlightenment' );
	$functions['wc_print_notices']                         = __( 'Product Notices',             'enlightenment' );
	$functions['woocommerce_show_product_loop_sale_flash'] = __( 'Product Sale Flash',          'enlightenment' );
	$functions['woocommerce_show_product_sale_flash']      = __( 'Single Product Sale Flash',   'enlightenment' );
	$functions['woocommerce_show_product_images']          = __( 'Product Images',              'enlightenment' );
	$functions['woocommerce_template_single_title']        = __( 'Single Product Title',        'enlightenment' );
	$functions['woocommerce_template_loop_rating']         = __( 'Product Rating',              'enlightenment' );
	$functions['woocommerce_template_single_rating']       = __( 'Single Product Rating',       'enlightenment' );
	$functions['woocommerce_template_loop_price']          = __( 'Product Price',               'enlightenment' );
	$functions['woocommerce_template_loop_add_to_cart']    = __( 'Add to Cart Button',          'enlightenment' );
	$functions['woocommerce_template_single_price']        = __( 'Single Product Price',        'enlightenment' );
	$functions['woocommerce_template_single_excerpt']      = __( 'Single Product Description',  'enlightenment' );
	$functions['woocommerce_template_single_add_to_cart']  = __( 'Single Product Add to Cart',  'enlightenment' );
	$functions['woocommerce_template_single_meta']         = __( 'Single Product Meta',         'enlightenment' );
	$functions['woocommerce_template_single_sharing']      = __( 'Single Product Sharing',      'enlightenment' );
	$functions['woocommerce_output_product_data_tabs']     = __( 'Products Tabs',               'enlightenment' );
	$functions['enlightenment_woocommerce_upsell_display'] = __( 'Upsell Products',             'enlightenment' );
	$functions['woocommerce_output_related_products']      = __( 'Related Products',            'enlightenment' );
	$functions['wc_no_products_found']                     = __( 'No Products Found',           'enlightenment' );

	return $functions;
}
add_filter( 'enlightenment_template_functions', 'enlightenment_woocommerce_template_functions' );

function enlightenment_woocommerce_site_header_hooks( $hooks ) {
	$hooks['enlightenment_site_header']['functions'][] = 'enlightenment_woocommerce_user_account';
	$hooks['enlightenment_site_header']['functions'][] = 'enlightenment_shopping_cart';

	return $hooks;
}
add_filter( 'enlightenment_site_header_hooks', 'enlightenment_woocommerce_site_header_hooks' );

function enlightenment_woocommerce_the_loop_hooks( $hooks ) {
	$hooks['enlightenment_before_entries_list']['functions'][] = 'enlightenment_product_categories';
	$hooks['enlightenment_before_entries_list']['functions'][] = 'woocommerce_output_all_notices';
	$hooks['enlightenment_before_entries_list']['functions'][] = 'woocommerce_result_count';
	$hooks['enlightenment_before_entries_list']['functions'][] = 'woocommerce_catalog_ordering';

	$hooks['enlightenment_no_entries']['functions'][] = 'wc_no_products_found';

	return $hooks;
}
add_filter( 'enlightenment_the_loop_hooks', 'enlightenment_woocommerce_the_loop_hooks' );

function enlightenment_woocommerce_entry_hooks( $hooks ) {
	$hooks['enlightenment_before_entry']['functions'][] = 'wc_print_notices';

	$hooks['enlightenment_before_entry_header']['functions'][] = 'woocommerce_show_product_loop_sale_flash';
	$hooks['enlightenment_before_entry_header']['functions'][] = 'enlightenment_post_thumbnail';

	$hooks['enlightenment_after_entry_header']['functions'][] = 'woocommerce_show_product_sale_flash';
	$hooks['enlightenment_after_entry_header']['functions'][] = 'woocommerce_show_product_images';
	$hooks['enlightenment_after_entry_header']['functions'][] = 'woocommerce_template_loop_rating';
	$hooks['enlightenment_after_entry_header']['functions'][] = 'woocommerce_template_loop_price';

	$hooks['enlightenment_before_entry_content']['functions'][] = 'woocommerce_template_single_title';
	$hooks['enlightenment_before_entry_content']['functions'][] = 'woocommerce_template_single_rating';
	$hooks['enlightenment_before_entry_content']['functions'][] = 'woocommerce_template_single_price';
	$hooks['enlightenment_before_entry_content']['functions'][] = 'woocommerce_template_single_excerpt';
	$hooks['enlightenment_before_entry_content']['functions'][] = 'woocommerce_template_single_add_to_cart';
	$hooks['enlightenment_before_entry_content']['functions'][] = 'woocommerce_template_single_meta';
	$hooks['enlightenment_before_entry_content']['functions'][] = 'woocommerce_template_single_sharing';

	$hooks['enlightenment_after_entry_footer']['functions'][] = 'woocommerce_template_loop_add_to_cart';

	return $hooks;
}
add_filter( 'enlightenment_entry_hooks', 'enlightenment_woocommerce_entry_hooks' );
