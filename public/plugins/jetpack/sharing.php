<?php

function enlightenment_jetpack_woocommerce_single_product_shortcode_sharing_enqueue_scripts( $enqueue ) {
	$sharer         = new Sharing_Service();
	$global_options = $sharer->get_global_options();

	if ( empty( $global_options['show'] ) || ! in_array( 'product', $global_options['show'] ) ) {
		return $enqueue;
	}

	$queried_object = get_queried_object();

	if ( ! $queried_object instanceof WP_Post ) {
		return $enqueue;
	}

	if ( false === strpos( $queried_object->post_content, '[product_page ' ) ) {
		return $enqueue;
	}

	return true;
}
add_filter( 'sharing_enqueue_scripts', 'enlightenment_jetpack_woocommerce_single_product_shortcode_sharing_enqueue_scripts' );
