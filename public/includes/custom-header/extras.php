<?php

function enlightenment_custom_header_body_class( $classes ) {
	if ( has_custom_header() || get_header_textcolor() != get_theme_support( 'custom-header', 'default-text-color' ) ) {
		$classes[] = 'custom-header';

		if ( has_header_image() ) {
			$classes[] = 'custom-header-image';
		}

		if ( is_header_video_active() ) {
			$classes[] = 'custom-header-video';
		}

		if ( get_header_textcolor() != get_theme_support( 'custom-header', 'default-text-color' ) ) {
			$classes[] = 'custom-header-textcolor';

			if ( 'blank' == get_header_textcolor() ) {
				$classes[] = 'custom-header-blank-textcolor';
			}
		}
	}

	return $classes;
}
add_filter( 'body_class', 'enlightenment_custom_header_body_class' );
