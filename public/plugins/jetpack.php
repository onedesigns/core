<?php
/**
 * WooCommerce Compatibility Functions
 *
 * @package Enlightenment_Framework
 * @subpackage Plugins
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if( ! class_exists( 'Jetpack' ) ) {
	return;
}

function enlightenment_jetpack_functions_directory() {
	return apply_filters( 'enlightenment_jetpack_functions_directory', enlightenment_plugins_directory() . '/jetpack' );
}

function enlightenment_jetpack_theme_supported_functions() {
	require_if_theme_supports( 'enlightenment-bootstrap', enlightenment_jetpack_functions_directory() . '/bootstrap.php' );

	if( in_array( 'custom-content-types', Jetpack::get_active_modules() ) ) {
		require_once( enlightenment_jetpack_functions_directory() . '/portfolio.php' );
		require_once( enlightenment_jetpack_functions_directory() . '/testimonials.php' );
		require_once( enlightenment_jetpack_functions_directory() . '/sharing.php' );
	}
}
add_action( 'after_setup_theme', 'enlightenment_jetpack_theme_supported_functions', 40 );
