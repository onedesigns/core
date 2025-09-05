<?php

function enlightenment_custom_layouts_directory() {
	return apply_filters( 'enlightenment_custom_layouts_directory', enlightenment_modules_directory() . '/custom-layouts' );
}

require_once( enlightenment_custom_layouts_directory() . '/functions.php' );
require_once( enlightenment_custom_layouts_directory() . '/actions.php' );
require_once( enlightenment_custom_layouts_directory() . '/filters.php' );
require_once( enlightenment_custom_layouts_directory() . '/gutenberg.php' );
require_once( enlightenment_custom_layouts_directory() . '/customizer.php' );

if ( is_admin() ) {
	require_once( enlightenment_custom_layouts_directory() . '/meta-boxes.php' );
}
