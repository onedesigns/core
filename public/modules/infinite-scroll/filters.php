<?php

add_filter( 'current_theme_supports-enlightenment-infinite-scroll', 'enlightenment_filter_current_theme_supports', 10, 3 );

function enlightenment_call_infinite_scroll_script( $deps ) {
	if ( is_singular() ) {
		return $deps;
	}

	if ( is_paged() ) {
		return $deps;
	}

	$deps[] = 'infinitescroll';

	return $deps;
}
add_filter( 'enlightenment_call_js', 'enlightenment_call_infinite_scroll_script' );

function enlightenment_infinite_scroll_set_max_page( $args ) {
	global $wp_query;

	if ( $wp_query->max_num_pages ) {
		$args['maxPage'] = $wp_query->max_num_pages;
	}

	return $args;
}
add_filter( 'enlightenment_infinite_scroll_script_args', 'enlightenment_infinite_scroll_set_max_page' );
