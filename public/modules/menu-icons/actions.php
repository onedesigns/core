<?php

function enlightenment_menu_icons_theme_support_args() {
	$defaults = array(
		'iconset' => 'font-awesome',
	);

	$args = get_theme_support( 'enlightenment-menu-icons' );
	$args = is_array( $args ) ? array_shift( $args ) : array();
	$args = wp_parse_args( $args, $defaults );

	global $_wp_theme_features;

	if( ! is_array( $_wp_theme_features['enlightenment-menu-icons'] ) ) {
		$_wp_theme_features['enlightenment-menu-icons'] = array();
	}

	$_wp_theme_features['enlightenment-menu-icons'][0] = $args;
}
add_action( 'after_setup_theme', 'enlightenment_menu_icons_theme_support_args', 50 );
