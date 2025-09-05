<?php

function enlightenment_post_thumbnails_directory() {
	return apply_filters( 'enlightenment_post_thumbnails_directory', enlightenment_includes_directory() . '/post-thumbnails' );
}

require_once( enlightenment_post_thumbnails_directory() . '/template-tags.php' );
require_once( enlightenment_post_thumbnails_directory() . '/filters.php' );

function enlightenment_post_thumbnails_theme_supported_functions() {
	require_if_theme_supports( 'enlightenment-post-thumbnail-header', enlightenment_post_thumbnails_directory() . '/post-thumbnail-header.php' );
}
add_action( 'after_setup_theme', 'enlightenment_post_thumbnails_theme_supported_functions', 40 );
