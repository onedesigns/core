<?php

function enlightenment_bootstrap_directory() {
	return apply_filters( 'enlightenment_bootstrap_directory', enlightenment_modules_directory() . '/bootstrap' );
}

require_once( enlightenment_bootstrap_directory() . '/functions.php' );
require_once( enlightenment_bootstrap_directory() . '/actions.php' );
require_once( enlightenment_bootstrap_directory() . '/filters.php' );
require_once( enlightenment_bootstrap_directory() . '/template-tags.php' );
require_once( enlightenment_bootstrap_directory() . '/widgets.php' );
require_once( enlightenment_bootstrap_directory() . '/gutenberg.php' );

function enlightenment_bootstrap_theme_supported_functions() {
	require_if_theme_supports( 'enlightenment-infinite-scroll', enlightenment_bootstrap_directory() . '/infinite-scroll.php' );
	require_if_theme_supports( 'enlightenment-custom-layouts', enlightenment_bootstrap_directory() . '/custom-layouts.php' );
	require_if_theme_supports( 'enlightenment-grid-loop', enlightenment_bootstrap_directory() . '/grid-loop.php' );
	require_if_theme_supports( 'enlightenment-unlimited-sidebars', enlightenment_bootstrap_directory() . '/unlimited-sidebars.php' );
}
add_action( 'after_setup_theme', 'enlightenment_bootstrap_theme_supported_functions', 40 );
