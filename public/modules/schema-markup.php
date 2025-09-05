<?php

function enlightenment_schema_markup_directory() {
	return apply_filters( 'enlightenment_schema_markup_directory', enlightenment_modules_directory() . '/schema-markup' );
}

require_once( enlightenment_schema_markup_directory() . '/filters.php' );

function enlightenment_schema_markup_theme_supported_functions() {
	require_if_theme_supports( 'enlightenment-post-thumbnails', enlightenment_schema_markup_directory() . '/post-thumbnails.php' );
}
add_action( 'after_setup_theme', 'enlightenment_schema_markup_theme_supported_functions', 40 );
