<?php

function enlightenment_lightbox_theme_support_args() {
	global $_wp_theme_features;
	$defaults = array(
		'script'      => 'colorbox',
		'script_args' => array(
			'selector'  => 'a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"]',
			'maxWidth'  => '100%',
			'maxHeight' => '100%',
		),
	);
	$args = get_theme_support( 'enlightenment-lightbox' );

	if( is_array( $args ) ) {
		$args = array_shift( $args );
	} else {
		$args = $_wp_theme_features['enlightenment-lightbox'] = array();
	}

	$args = wp_parse_args( $args, $defaults );
	$_wp_theme_features['enlightenment-lightbox'][0] = $args;
}
add_action( 'after_setup_theme', 'enlightenment_lightbox_theme_support_args', 50 );

function enlightenment_enqueue_lightbox_style() {
	wp_enqueue_style( current_theme_supports( 'enlightenment-lightbox', 'script' ) );
}
add_action( 'wp_enqueue_scripts', 'enlightenment_enqueue_lightbox_style' );

function enlightenment_enqueue_lightbox_script() {
	wp_enqueue_script( current_theme_supports( 'enlightenment-lightbox', 'script' ) );
	wp_localize_script( current_theme_supports( 'enlightenment-lightbox', 'script' ), sprintf( 'enlightenment_%s_args', current_theme_supports( 'enlightenment-lightbox', 'script' ) ), current_theme_supports( 'enlightenment-lightbox', 'script_args' ) );
}
add_action( 'wp_enqueue_scripts', 'enlightenment_enqueue_lightbox_script' );
