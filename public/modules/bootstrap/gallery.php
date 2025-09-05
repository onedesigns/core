<?php

add_filter( 'use_default_gallery_style', '__return_false' );

function enlightenment_bootstrap_gallery_style_open_row( $output ) {
	return $output . '<div class="row">';
}

function enlightenment_bootstrap_gallery_shortcode_close_row( $output ) {
	// Allow use of Jetpack Tiled Galleries Module
	if( class_exists( 'Jetpack' ) && in_array( 'tiled-gallery', Jetpack::get_active_modules() ) ) {
		return $output;
	}

	return $output . '</div>';
}

function enlightenment_bootstrap_gallery_item( $item, $attachment, $id, $attr, $columns ) {
	$colspan = intval( 12 / $columns );
	
	return '<div class="col-md-' . $colspan . '">' . $item . '</div>';
}
