<?php
/**
 * Customizer functions for Web Fonts.
 *
 * @package Enlightenment_Framework
 */

function enlightenment_get_safe_fonts() {
	$safe_fonts = array();
	$has_filter = ( 10 === has_filter( 'enlightenment_available_fonts', 'enlightenment_add_web_fonts_to_available_fonts' ) );

	if ( $has_filter ) {
		remove_filter( 'enlightenment_available_fonts', 'enlightenment_add_web_fonts_to_available_fonts' );
	}

	foreach( enlightenment_get_available_fonts() as $font => $args ) {
		$safe_fonts[ $font ] = sprintf( '%1$s, %2$s', $args['family'], $args['category'] );
	}

	if ( $has_filter ) {
		add_filter( 'enlightenment_available_fonts', 'enlightenment_add_web_fonts_to_available_fonts' );
	}

	return $safe_fonts;
}

function enlightenment_ajax_get_web_fonts() {
	header('Content-Type: application/json; charset=utf-8');

	$response = wp_remote_get( 'https://gwfh.mranftl.com/api/fonts' );

	if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
		$fonts = json_decode( wp_remote_retrieve_body( $response ) );

		$fonts = apply_filters( 'enlightenment_remote_web_fonts', $fonts );

		echo json_encode( array_values( $fonts ) );
	}

	die();
}
add_action( 'wp_ajax_enlightenment_get_web_fonts', 'enlightenment_ajax_get_web_fonts' );

function enlightenment_web_fonts_customize_controls_args( $args ) {
	$variants = array_keys( enlightenment_get_font_variants() );

	if ( in_array( '400', $variants ) ) {
		$variants[] = 'regular';
	}

	$args['web_fonts'] = array(
		'local'             => enlightenment_get_web_fonts(),
		'safe_fonts'        => enlightenment_get_safe_fonts(),
		'supportedVariants' => $variants,
		'ajaxUrl'           => add_query_arg( 'action', 'enlightenment_get_web_fonts', admin_url( 'admin-ajax.php' ) ),
		'ajaxError'         => __( 'Unable to retrieve web fonts list at this time. Make sure you are online and refresh this page.', 'enlightenment' ),
	);

	return $args;
}
add_filter( 'enlightenment_customize_controls_args', 'enlightenment_web_fonts_customize_controls_args' );

function enlightenment_web_fonts_customize_preview_args( $args ) {
	$args['web_fonts'] = array(
		'local'      => enlightenment_get_web_fonts(),
		'subsets'    => current_theme_supports( 'enlightenment-web-fonts', 'subsets' ),
		'safe_fonts' => enlightenment_get_safe_fonts(),
		'selectors'  => enlightenment_custom_typography_selectors(),
	);

	return $args;
}
add_filter( 'enlightenment_customize_preview_args', 'enlightenment_web_fonts_customize_preview_args' );

function enlightenment_web_fonts_export_settings( $mods ) {
	$mods['web_fonts']['fonts'] = enlightenment_get_web_fonts();

	return $mods;
}
add_filter( 'enlightenment_export_settings', 'enlightenment_web_fonts_export_settings' );

function enlightenment_are_web_fonts_cdn_hosted( $control ) {
	return ! $control->manager->get_setting( 'web_fonts[host_locally]' )->value();
}

/**
 * Customizer settings, sections and controls.
 */
function enlightenment_web_fonts_customize_register( $wp_customize ) {
	$defaults = enlightenment_default_theme_mods()['web_fonts'];
	$cap      = 'edit_theme_options';
	$type     = 'theme_mod';

	$wp_customize->add_section( 'web-fonts', array(
		'title'          => __( 'Google Fonts', 'enlightenment' ),
		'priority'       => 50,
		'theme_supports' => 'enlightenment-web-fonts',
	) );

	$wp_customize->add_setting( 'web_fonts[host_locally]', array(
		'type'              => $type,
		'capability'        => $cap,
		'default'           => $defaults['host_locally'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'enlightenment_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'web_fonts[host_locally]', array(
		'type'            => 'checkbox',
		'section'         => 'web-fonts',
		'label'           => __( 'Host Fonts Locally', 'enlightenment' ),
		'description'     => __( 'Useful for full GDPR compliance.', 'enlightenment' ),
	) );

	$wp_customize->add_setting( 'web_fonts[provider]', array(
		'type'              => $type,
		'capability'        => $cap,
		'default'           => $defaults['provider'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'web_fonts[provider]', array(
		'type'        => 'select',
		'section'     => 'web-fonts',
		'label'       => __( 'Provider', 'enlightenment' ),
		'description' => __( 'Bunny Fonts is a GDPR-friently mirror of Google Fonts.', 'enlightenment' ),
		'choices'     => array(
			'google-fonts' => __( 'Google Fonts', 'enlightenment' ),
			'bunny-fonts'  => __( 'Bunny Fonts',  'enlightenment' ),
		),
		'active_callback' => 'enlightenment_are_web_fonts_cdn_hosted',
	) );

	$wp_customize->add_setting( 'web_fonts[subsets]', array(
		'type'              => $type,
		'capability'        => $cap,
		'default'           => $defaults['subsets'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'enlightenment_sanitize_multiple_checkboxes',
	) );
	$wp_customize->add_control( new Enlightenment_Customize_Subsets_Control( $wp_customize, 'web_fonts[subsets]', array(
		'section'         => 'web-fonts',
		'label'           => __( 'Subsets', 'enlightenment' ),
		'description'     => __( 'Select your font subsets.', 'enlightenment' ),
	) ) );

	$wp_customize->add_setting( 'web_fonts[filter_by_subsets]', array(
		'type'              => $type,
		'capability'        => $cap,
		'default'           => $defaults['filter_by_subsets'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'enlightenment_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'web_fonts[filter_by_subsets]', array(
		'type'            => 'checkbox',
		'section'         => 'web-fonts',
		'label'           => __( 'Filter by Subsets', 'enlightenment' ),
		'description'     => __( 'Only display fonts with selected subsets.', 'enlightenment' ),
	) );

	$wp_customize->add_setting( 'web_fonts[font_display]', array(
		'type'              => $type,
		'capability'        => $cap,
		'default'           => $defaults['font_display'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'web_fonts[font_display]', array(
		'type'        => 'select',
		'section'     => 'web-fonts',
		'label'       => __( 'Font Display', 'enlightenment' ),
		'description' => sprintf( '<a href="https://css-tricks.com/almanac/properties/f/font-display/#aa-values" target="_blank">%s</a>', __( 'Learn more about what each choice means.', 'enlightenment' ) ),
		'choices'     => array(
			'auto'     => __( 'Automatic', 'enlightenment' ),
			'block'    => __( 'Block',     'enlightenment' ),
			'swap'     => __( 'Swap',      'enlightenment' ),
			'fallback' => __( 'Fallback',  'enlightenment' ),
			'optional' => __( 'Optional',  'enlightenment' ),
		),
	) );

	$wp_customize->add_setting( 'web_fonts[sort_fonts_by]', array(
		'type'              => $type,
		'capability'        => $cap,
		'default'           => $defaults['sort_fonts_by'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'web_fonts[sort_fonts_by]', array(
		'type'       => 'select',
		'section'    => 'web-fonts',
		'label'      => __( 'Sort Fonts by', 'enlightenment' ),
		'choices'    => array(
			'popularity' => __( 'Popularity',    'enlightenment' ),
			'alpha'      => __( 'Alphabetical',  'enlightenment' ),
			'date'       => __( 'Date Modified', 'enlightenment' ),
			'style'      => __( 'Most Styles',   'enlightenment' ),
			'subset'     => __( 'Most Subsets',  'enlightenment' ),
		),
	) );

	$wp_customize->add_setting( 'web_fonts[fonts]', array(
		'type'              => $type,
		'capability'        => $cap,
		'default'           => $defaults['fonts'],
		'transport'         => 'postMessage',
		'sanitize_callback' => 'enlightenment_sanitize_web_fonts',
	) );
	$wp_customize->add_control( new Enlightenment_Customize_Web_Fonts_Control( $wp_customize, 'web_fonts[fonts]', array(
		'section'         => 'web-fonts',
		'label'           => __( 'Fonts in Google Directory', 'enlightenment' ),
		'description'     => __( 'Fonts selected here will become available in typography controls.', 'enlightenment' ),
	) ) );
}
add_action( 'customize_register', 'enlightenment_web_fonts_customize_register' );

function enlightenment_sanitize_web_fonts( $input ) {
	foreach( $input as $font => $atts ) {
		$input[ $font ]['family']   = sanitize_text_field( $atts['family'] );
		$input[ $font ]['category'] = sanitize_text_field( $atts['category'] );
		$input[ $font ]['subsets']  = is_array( $atts['subsets']  ) ? $atts['subsets']  : array();
		$input[ $font ]['variants'] = is_array( $atts['variants'] ) ? $atts['variants'] : array();

		foreach( $atts['subsets'] as $key => $subset ) {
			$input[ $font ]['subsets'][ $key ] = sanitize_text_field( $subset );
		}

		foreach( $atts['variants'] as $key => $variant ) {
			$input[ $font ]['variants'][ $key ] = sanitize_text_field( $variant );
		}
	}

	return $input;
}

function enlightenment_download_web_fonts_after_customize_save( $wp_customize ) {
	if ( false !== has_filter( 'enlightenment_web_fonts_theme_support_args', 'enlightenment_web_fonts_set_theme_options_args' ) ) {
		enlightenment_web_fonts_set_theme_support_args();
	}

	if ( ! current_theme_supports( 'enlightenment-web-fonts', 'host_locally' ) ) {
		return;
	}

	// Reset the array to prevent it being populated by obsolete options.
	$GLOBALS['enlightenment_web_fonts'] = array();

	enlightenment_enqueue_fonts();

	$fonts     = enlightenment_get_fonts_to_load();
	$web_fonts = enlightenment_get_web_fonts();

	foreach ( $fonts as $font => $styles ) {
		$variants = $styles;
		$atts     = enlightenment_get_font_atts( $font );

		if ( ! empty( $atts['src'] ) ) {
			$variants = array_diff( $styles, array_keys( $atts['src'] ) );

			if ( ! empty( $atts['subsetMap'] ) ) {
				$subsets = array_intersect( $atts['subsets'], current_theme_supports( 'enlightenment-web-fonts', 'subsets' ) );

				if ( count( array_diff( $subsets, $atts['subsetMap'] ) ) ) {
					$variants = array_merge( $styles, $atts['src'] );
				}
			}
		}

		if ( empty( $variants ) ) {
			continue;
		}

		enlightenment_download_web_font( $font, $variants );
	}
}
add_action( 'customize_save_after', 'enlightenment_download_web_fonts_after_customize_save' );
