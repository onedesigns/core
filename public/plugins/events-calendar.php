<?php
/**
 * The Events Calendar Compatibility Functions
 *
 * @package Enlightenment_Framework
 * @subpackage Plugins
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Tribe__Events__Main' ) ) {
	return;
}

function enlightenment_events_functions_directory() {
	return apply_filters( 'enlightenment_events_functions_directory', enlightenment_plugins_directory() . '/events-calendar' );
}

require_once( enlightenment_events_functions_directory() . '/functions.php' );
require_once( enlightenment_events_functions_directory() . '/actions.php' );
require_once( enlightenment_events_functions_directory() . '/filters.php' );
require_once( enlightenment_events_functions_directory() . '/template-tags.php' );
require_once( enlightenment_events_functions_directory() . '/extras.php' );
require_once( enlightenment_events_functions_directory() . '/default-hooks.php' );

function enlightenment_events_theme_supported_functions() {
	require_if_theme_supports( 'enlightenment-bootstrap', enlightenment_events_functions_directory() . '/bootstrap.php' );
	require_if_theme_supports( 'enlightenment-custom-layouts', enlightenment_events_functions_directory() . '/custom-layouts.php' );
	require_if_theme_supports( 'enlightenment-template-editor', enlightenment_events_functions_directory() . '/template-editor.php' );
}
add_action( 'after_setup_theme', 'enlightenment_events_theme_supported_functions', 40 );
