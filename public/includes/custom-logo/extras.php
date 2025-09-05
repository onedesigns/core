<?php

function enlightenment_site_branding_logo( $args ) {
	$logo = get_custom_logo();

	if ( ! empty( $logo ) && apply_filters( 'enlightenment_site_title_logo', true ) ) {
		$args['format'] = sprintf( '%1$s %2$s', $logo, $args['format'] );
	}

	return $args;
}
add_filter( 'enlightenment_site_branding_args', 'enlightenment_site_branding_logo' );

function enlightenment_site_title_logo( $site_title ) {
	$logo = get_custom_logo();

	if ( ! empty( $logo ) && apply_filters( 'enlightenment_site_title_logo', true ) ) {
		if ( apply_filters( 'enlightenment_hide_site_title_text', false ) ) {
			$site_title = sprintf( '%1$s <span class="site-title-text hidden">%2$s</span>', ''/*$logo*/, $site_title );
		} else {
			$site_title = sprintf( '%1$s <span class="site-title-text">%2$s</span>', ''/*$logo*/, $site_title );
		}
	}

	return $site_title;
}
add_filter( 'enlightenment_site_title_format', 'enlightenment_site_title_logo' );

function enlightenment_logo_image_wrap_home_link( $logo ) {
	return sprintf( '<a href="%s" rel="home">%s</a>', home_url( '/' ), $logo );
}
