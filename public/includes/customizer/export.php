<?php

function enlightenment_export_settings() {
	if( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( __( 'Cheatin&#8217; uh?', 'enlightenment' ), 403 );
	}

	$defaults = enlightenment_default_theme_mods();
	$mods     = array();

	foreach ( $defaults as $mod => $value) {
		$mods[ $mod ] = get_theme_mod( $mod, $defaults[ $mod ] );
	}

	if( current_theme_supports( 'custom-header' ) ) {
		$mods['header_textcolor'] = get_header_textcolor();
		$mods['header_image']     = get_header_image();
	}

	if( current_theme_supports( 'custom-background' ) ) {
		$mods['background_color']      = get_theme_mod( 'background_color', get_theme_support( 'custom-background', 'default-color' ) );
		$mods['background_image']      = get_theme_mod( 'background_image', get_theme_support( 'custom-background', 'default-image' ) );
		$mods['background_repeat']     = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );
		$mods['background_position_x'] = get_theme_mod( 'background_position_x', get_theme_support( 'custom-background', 'default-position-x' ) );
		$mods['background_position_y'] = get_theme_mod( 'background_position_y', get_theme_support( 'custom-background', 'default-position-y' ) );
		$mods['background_attachment'] = get_theme_mod( 'background_attachment', get_theme_support( 'custom-background', 'default-attachment' ) );
	}

	$mods = apply_filters( 'enlightenment_export_settings', $mods );

	header( 'Pragma: public' );
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: private', false );
	header( 'Content-type: application/json; charset: UTF-8' );
	header( 'Content-Disposition: attachment; filename="theme-settings-' . gmdate( 'd-m-Y' ) . '.json"' );
	header( 'Content-Transfer-Encoding: binary' );
	header( 'Connection: close' );

	$options = 0;
	if( defined( 'JSON_PRETTY_PRINT' ) ) {
		$options = JSON_PRETTY_PRINT;
	}
	echo json_encode( $mods, $options );

	die();
}
add_action( 'wp_ajax_enlightenment_export_settings', 'enlightenment_export_settings' );

function enlightenment_allow_json_files( $types ) {
	$types['json'][] = 'json';
	return $types;
}
add_filter( 'ext2type', 'enlightenment_allow_json_files' );

function enlightenment_allow_json_mimes($mime_types){
    $mime_types['json'] = 'application/json'; //Adding svg extension
    return $mime_types;
}
add_filter( 'upload_mimes', 'enlightenment_allow_json_mimes' );
