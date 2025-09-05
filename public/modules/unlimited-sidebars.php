<?php

function enlightenment_unlimited_sidebars_directory() {
	return apply_filters( 'enlightenment_unlimited_sidebars_directory', enlightenment_modules_directory() . '/unlimited-sidebars' );
}

require_once( enlightenment_unlimited_sidebars_directory() . '/functions.php' );
require_once( enlightenment_unlimited_sidebars_directory() . '/actions.php' );
require_once( enlightenment_unlimited_sidebars_directory() . '/filters.php' );
require_once( enlightenment_unlimited_sidebars_directory() . '/gutenberg.php' );
require_once( enlightenment_unlimited_sidebars_directory() . '/customizer.php' );

if ( is_admin() ) {
	require_once( enlightenment_unlimited_sidebars_directory() . '/meta-boxes.php' );
}

function enlightenment_unlimited_sidebars_theme_supported_functions() {
	require_if_theme_supports( 'enlightenment-bootstrap', enlightenment_unlimited_sidebars_directory() . '/bootstrap.php' );
}
add_action( 'after_setup_theme', 'enlightenment_unlimited_sidebars_theme_supported_functions', 40 );
