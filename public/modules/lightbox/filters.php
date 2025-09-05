<?php

add_filter( 'current_theme_supports-enlightenment-lightbox', 'enlightenment_filter_current_theme_supports', 10, 3 );

function enlightenment_lightbox_rel_attachment( $link ) {
	return str_replace( "<a ", "<a rel='attachment' ", $link );
}
add_filter( 'wp_get_attachment_link', 'enlightenment_lightbox_rel_attachment' );

function enlightenment_call_lightbox_script( $deps ) {
	$deps[] = current_theme_supports( 'enlightenment-lightbox', 'script' );
	return $deps;
}
add_filter( 'enlightenment_call_js', 'enlightenment_call_lightbox_script' );
