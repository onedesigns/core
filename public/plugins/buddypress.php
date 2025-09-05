<?php
/**
 * BuddyPress Compatibility Functions
 *
 * @package Enlightenment_Framework
 * @subpackage Plugins
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'buddypress' ) ) {
	return;
}

function enlightenment_buddypress_functions_directory() {
	return apply_filters( 'enlightenment_buddypress_functions_directory', enlightenment_plugins_directory() . '/buddypress' );
}

require_once( enlightenment_buddypress_functions_directory() . '/ajax.php' );
require_once( enlightenment_buddypress_functions_directory() . '/functions.php' );
require_once( enlightenment_buddypress_functions_directory() . '/actions.php' );
require_once( enlightenment_buddypress_functions_directory() . '/filters.php' );
require_once( enlightenment_buddypress_functions_directory() . '/notifications.php' );
require_once( enlightenment_buddypress_functions_directory() . '/template-tags.php' );
require_once( enlightenment_buddypress_functions_directory() . '/widgets.php' );
require_once( enlightenment_buddypress_functions_directory() . '/plugins.php' );
require_once( enlightenment_buddypress_functions_directory() . '/default-hooks.php' );

function enlightenment_bp_theme_supported_functions() {
	require_if_theme_supports( 'enlightenment-bootstrap', enlightenment_buddypress_functions_directory() . '/bootstrap.php' );
	require_if_theme_supports( 'enlightenment-custom-layouts', enlightenment_buddypress_functions_directory() . '/custom-layouts.php' );
	require_if_theme_supports( 'enlightenment-template-editor', enlightenment_buddypress_functions_directory() . '/template-editor.php' );
	require_if_theme_supports( 'enlightenment-unlimited-sidebars', enlightenment_buddypress_functions_directory() . '/unlimited-sidebars.php' );
}
add_action( 'after_setup_theme', 'enlightenment_bp_theme_supported_functions', 40 );
