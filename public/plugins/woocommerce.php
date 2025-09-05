<?php
/**
 * WooCommerce Compatibility Functions
 *
 * @package Enlightenment_Framework
 * @subpackage Plugins
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

function enlightenment_woocommerce_functions_directory() {
	return apply_filters( 'enlightenment_woocommerce_functions_directory', enlightenment_plugins_directory() . '/woocommerce' );
}

require_once( enlightenment_woocommerce_functions_directory() . '/actions.php' );
require_once( enlightenment_woocommerce_functions_directory() . '/filters.php' );
require_once( enlightenment_woocommerce_functions_directory() . '/template-tags.php' );
require_once( enlightenment_woocommerce_functions_directory() . '/extras.php' );
require_once( enlightenment_woocommerce_functions_directory() . '/native-hooks.php' );
require_once( enlightenment_woocommerce_functions_directory() . '/default-hooks.php' );
require_once( enlightenment_woocommerce_functions_directory() . '/customizer.php' );

function enlightenment_woocommerce_theme_supported_functions() {
	require_if_theme_supports( 'enlightenment-bootstrap', enlightenment_woocommerce_functions_directory() . '/bootstrap.php' );
	require_if_theme_supports( 'enlightenment-grid-loop', enlightenment_woocommerce_functions_directory() . '/grid-loop.php' );
	require_if_theme_supports( 'enlightenment-template-editor', enlightenment_woocommerce_functions_directory() . '/template-editor.php' );
}
add_action( 'after_setup_theme', 'enlightenment_woocommerce_theme_supported_functions', 40 );
