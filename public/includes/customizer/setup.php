<?php

/**
 * Enqueue script for custom customize control.
 */
function enlightenment_customize_controls_enqueue_scripts() {
	wp_register_style( 'enlightenment-customize-controls', enlightenment_styles_directory_uri() . '/customize-controls.css', false, null );
	wp_register_script( 'enlightenment-customize-controls', enlightenment_scripts_directory_uri() . '/customize-controls.js', array( 'jquery', 'customize-controls' ), null, true );

	wp_localize_script(
		'enlightenment-customize-controls',
		'enlightenment_customize_controls_args',
		apply_filters( 'enlightenment_customize_controls_args', array(
			'defaults'  => enlightenment_default_theme_mods(),
		)
	) );
}
add_action( 'customize_controls_enqueue_scripts', 'enlightenment_customize_controls_enqueue_scripts', 0 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function enlightenment_customize_preview_js() {
	wp_enqueue_script( 'enlightenment-customize-preview', enlightenment_scripts_directory_uri() . '/customize-preview.js', array( 'customize-preview' ), null, true );

	/*wp_localize_script(
		'enlightenment-customize-preview',
		'enlightenment_customize_preview_args',
		apply_filters( 'enlightenment_customize_preview_args', array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
		)
	) );*/
}
add_action( 'customize_preview_init', 'enlightenment_customize_preview_js' );

function enlightenment_customize_preview_localize_js() {
	wp_localize_script(
		'enlightenment-customize-preview',
		'enlightenment_customize_preview_args',
		apply_filters( 'enlightenment_customize_preview_args', array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
		)
	) );
}
add_action( 'wp_enqueue_scripts', 'enlightenment_customize_preview_localize_js' );

/**
 * Make sure settings of type 'enlightenment_inert_setting_type' always have a
 * hook attached to prevent breaking the update provess of real settings.
 */
do_action( 'customize_update_enlightenment_inert_setting_type', '__return_false' );
