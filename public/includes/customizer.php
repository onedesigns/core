<?php
/**
 * Theme Customizer
 *
 * @package Enlightenment_Framework
 */

function enlightenment_customizer_directory() {
	return apply_filters( 'enlightenment_customizer_directory', enlightenment_includes_directory() . '/customizer' );
}

/**
 * Setup Theme Customizer.
 */
require_once( enlightenment_customizer_directory() . '/setup.php' );

/**
 * Template Tags for outputting Theme Settings.
 */
require_once( enlightenment_customizer_directory() . '/template-tags.php' );

/**
 * Export settings functions.
 */
require_once( enlightenment_customizer_directory() . '/export.php' );

/**
 * Default Theme Mods.
 */
require_once( enlightenment_customizer_directory() . '/default-mods.php' );

/**
 * Extend the Theme Customizer API.
 */
function enlightenment_extend_customizer( $wp_customize ) {
	require_once( enlightenment_customizer_directory() . '/customize-controls.php' );
	require_once( enlightenment_customizer_directory() . '/sanitize.php' );
	require_once( enlightenment_customizer_directory() . '/validate.php' );
}
add_action( 'customize_register', 'enlightenment_extend_customizer', 0 );
