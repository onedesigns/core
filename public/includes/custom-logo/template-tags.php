<?php

function enlightenment_logo_image_src() {
	$logo_id = get_theme_mod( 'custom_logo' );
	$src     =  '';

	if ( ! empty( $logo_id ) ) {
		$src = wp_get_attachment_image_src( $logo_id, 'full' )[0];
	}

	return $src;
}
