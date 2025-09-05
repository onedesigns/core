<?php

add_filter( 'current_theme_supports-enlightenment-ajax-navigation', 'enlightenment_filter_current_theme_supports', 10, 3 );

function enlightenment_call_ajax_navigation_script( $deps ) {
	$deps[] = 'ajax-navigation';
	return $deps;
}
add_filter( 'enlightenment_call_js', 'enlightenment_call_ajax_navigation_script' );
