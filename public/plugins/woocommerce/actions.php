<?php

function enlightenment_woocommerce_theme_support_args() {
	$defaults = array(
        'product_gallery_zoom'     => false,
    	'product_gallery_lightbox' => false,
    	'product_gallery_slider'   => false,
	);

	$args = get_theme_support( 'enlightenment-woocommerce' );
	$args = is_array( $args ) ? array_shift( $args ) : array();
	$args = wp_parse_args( $args, $defaults );

	global $_wp_theme_features;

	if( ! is_array( $_wp_theme_features['enlightenment-woocommerce'] ) ) {
		$_wp_theme_features['enlightenment-woocommerce'] = array();
	}

	$_wp_theme_features['enlightenment-woocommerce'][0] = $args;
}
add_action( 'after_setup_theme', 'enlightenment_woocommerce_theme_support_args', 50 );

add_action( 'woocommerce_before_template_part', 'enlightenment_ob_start', 999 );

function enlightenment_woocommerce_filter_template_output( $template_name, $template_path, $located, $args ) {
    $output = ob_get_clean();

	$template_slug = $template_name;
	$template_slug = str_replace( '-',    '_', $template_slug );
	$template_slug = str_replace( '/',    '_', $template_slug );
	$template_slug = str_replace( '.php', '',  $template_slug );

    $output = apply_filters(
		'enlightenment_woocommerce_filter_template_output',
		$output, $template_name, $template_path, $located, $args
	);

    $output = apply_filters(
		sprintf( 'enlightenment_woocommerce_filter_template_%s_output', $template_slug ),
		$output, $template_name, $template_path, $located, $args
	);

    echo $output;
}
add_action( 'woocommerce_after_template_part', 'enlightenment_woocommerce_filter_template_output', 10, 4 );

add_action( 'enlightenment_before_comment_header', 'woocommerce_review_display_rating' );

function enlightenment_woocommerce_maybe_hide_review_form_start() {
	global $product;

	if( ! is_singular( 'product' ) ) {
		return;
	}

	if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' ) {
		return;
	}

	if ( wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) {
		return;
	}

	ob_start();
}
add_action( 'comment_form_before', 'enlightenment_woocommerce_maybe_hide_review_form_start' );

function enlightenment_woocommerce_maybe_hide_review_form_end() {
	global $product;

	if( ! is_singular( 'product' ) ) {
		return;
	}

	if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' ) {
		return;
	}

	if ( wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) {
		return;
	}

	ob_end_clean();

	enlightenment_woocommerce_verification_required_notice();
}
add_action( 'comment_form_after', 'enlightenment_woocommerce_maybe_hide_review_form_end' );
