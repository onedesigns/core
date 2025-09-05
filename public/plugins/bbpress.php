<?php
/**
 * bbPress Compatibility Functions
 *
 * @package Enlightenment_Framework
 * @subpackage Plugins
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'bbpress' ) ) {
	return;
}

function enlightenment_bbpress_functions_directory() {
	return apply_filters( 'enlightenment_bbpress_functions_directory', enlightenment_plugins_directory() . '/bbpress' );
}

require_once( enlightenment_bbpress_functions_directory() . '/functions.php' );
require_once( enlightenment_bbpress_functions_directory() . '/filters.php' );
require_once( enlightenment_bbpress_functions_directory() . '/template-tags.php' );
require_once( enlightenment_bbpress_functions_directory() . '/extras.php' );
require_once( enlightenment_bbpress_functions_directory() . '/default-hooks.php' );

function enlightenment_bbp_theme_supported_functions() {
	require_if_theme_supports( 'enlightenment-bootstrap', enlightenment_bbpress_functions_directory() . '/bootstrap.php' );
	require_if_theme_supports( 'enlightenment-custom-layouts', enlightenment_bbpress_functions_directory() . '/custom-layouts.php' );
	require_if_theme_supports( 'enlightenment-grid-loop', enlightenment_bbpress_functions_directory() . '/grid-loop.php' );
	require_if_theme_supports( 'enlightenment-template-editor', enlightenment_bbpress_functions_directory() . '/template-editor.php' );
	require_if_theme_supports( 'enlightenment-unlimited-sidebars', enlightenment_bbpress_functions_directory() . '/unlimited-sidebars.php' );
}
add_action( 'after_setup_theme', 'enlightenment_bbp_theme_supported_functions', 40 );
