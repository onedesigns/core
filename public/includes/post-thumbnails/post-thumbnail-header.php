<?php

function enlightenment_post_thumbnail_header_theme_support_args() {
	$defaults = array(
		'post_types' => array(),
	);

	$args = get_theme_support( 'enlightenment-post-thumbnail-header' );
	$args = is_array( $args ) ? array_shift( $args ) : array();
	$args = wp_parse_args( $args, $defaults );

	global $_wp_theme_features;

	if ( ! is_array( $_wp_theme_features['enlightenment-post-thumbnail-header'] ) ) {
		$_wp_theme_features['enlightenment-post-thumbnail-header'] = array();
	}

	$_wp_theme_features['enlightenment-post-thumbnail-header'][0] = $args;
}
add_action( 'after_setup_theme', 'enlightenment_post_thumbnail_header_theme_support_args', 50 );

add_filter( 'current_theme_supports-enlightenment-post-thumbnail-header', 'enlightenment_filter_current_theme_supports', 10, 3 );

function enlightenment_post_header_image( $url ) {
	$post_types = current_theme_supports( 'enlightenment-post-thumbnail-header', 'post_types' );

	if ( empty( $post_types ) ) {
		$post_types = '';
	}

	if ( is_singular( $post_types ) && has_post_thumbnail() ) {
		$url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		$url = $url[0];
	}

	return $url;
}
add_filter( 'theme_mod_header_image', 'enlightenment_post_header_image' );
