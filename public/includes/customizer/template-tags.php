<?php

function enlightenment_print_css_property( $args = null ) {
	$defaults = array(
		'media_query' => '',
		'selector'    => '',
		'property'    => '',
		'value'       => '',
		'echo'        => true,
	);
	$defaults = apply_filters( 'enlightenment_print_css_property_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( ! empty( $args['media_query'] ) && empty( $args['selector'] ) ) {
		if ( 0 === strpos( $args['property'], '--' ) ) {
			$args['selector'] = ':root';
		} else {
			_doing_it_wrong( __FUNCTION__, __( 'The selector parameter is required when media_query is not empty.', 'enlightenment' ), '2.0.0' );
			return '';
		}
	}

	if ( empty( $args['property'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'The property parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['value'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'The value parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	$output = '';

	if ( ! empty( $args['media_query'] ) ) {
		$output .= sprintf( "\n@media %s {", esc_attr( $args['media_query'] ) );
	}

	if ( ! empty( $args['selector'] ) ) {
		$output .= sprintf( "\n%s {\n", $args['selector'] );
	}

	$value = esc_attr( $args['value'] );
	$value = str_replace( array( '&quot;', '&#039;', '&gt;' ), array( '"', "'", '>' ), $value );

	$output .= sprintf( "\t%s: %s;\n", esc_attr( $args['property'] ), $value );

	if ( ! empty( $args['selector'] ) ) {
		$output .= "}\n";
	}

	if ( ! empty( $args['media_query'] ) ) {
		$output .= "}\n";
	}

	$output = apply_filters( 'enlightenment_print_css_property', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_print_color_property( $args = null ) {
	$defaults = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'property'     => 'color',
		'value'        => '',
		'format'       => '',
		'opacity'      => 1,
		'rgb_wrapper'  => true,
		'echo'         => true,
	) );
	$defaults = apply_filters( 'enlightenment_print_color_property_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['value'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'The value parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	$value = $args['value'];

	if ( in_array( $args['format'], array( 'rgb', 'rgba' ) ) ) {
		$value = enlightenment_hex2rgb( $value, array(
			'format'  => $args['format'],
			'opacity' => $args['opacity'],
			'wrapper' => $args['rgb_wrapper'],
		) );
	}

	$output = enlightenment_print_css_property( array_merge( $args, array(
		'value' => $value,
		'echo'  => false,
	) ) );

	$output = apply_filters( 'enlightenment_print_color_property', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_print_font_family_property( $args = null ) {
	$defaults = array(
		'media_query' => '',
		'selector'    => '',
		'property'    => 'font-family',
		'value'       => '',
		'echo'        => true,
	);
	$defaults = apply_filters( 'enlightenment_print_font_family_property_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['value'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'The value parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	$fonts = enlightenment_get_available_fonts();

	if ( ! isset( $fonts[ $args['value'] ] ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( __( 'The font %s is not available.', 'enlightenment' ), sprintf( '&quot;%s&quot;', $args['value'] ) ), '2.0.0' );
		return '';
	}

	$fontlist = $fonts[ $args['value'] ]['family'];

	if ( false !== strpos( $fontlist, ' ' ) ) {
		$fontlist = join( ', ', array_map( function( $font ) {
			$font = trim( $font );

			if ( false !== strpos( $font, ' ' ) && false === strpos( $font, '"' ) ) {
				$font = sprintf( '"%s"', $font );
			}

			return $font;
		}, explode( ',', $fontlist ) ) );
	}

	$output = enlightenment_print_css_property( array_merge( $args, array(
		'value' => sprintf( '%s, %s', $fontlist, $fonts[ $args['value'] ]['category'] ),
		'echo'  => false,
	) ) );

	$output = apply_filters( 'enlightenment_print_font_family_property', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_print_font_size_property( $args = null ) {
	$defaults = array(
		'media_query' => '',
		'selector'    => '',
		'property'    => 'font-size',
		'value'       => '',
		'echo'        => true,
	);
	$defaults = apply_filters( 'enlightenment_print_font_size_property_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['value'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'The value parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	$value = $args['value'];

	if ( is_numeric( $value ) ) {
		$value .= apply_filters( 'enlightenment_default_font_size_unit', 'px' );
	}

	$output = enlightenment_print_css_property( array_merge( $args, array(
		'value' => $value,
		'echo'  => false,
	) ) );

	$output = apply_filters( 'enlightenment_print_font_size_property', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_print_font_variant_properties( $args = null ) {
	$defaults = array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'value'        => '',
		'echo'         => true,
	);
	$defaults = apply_filters( 'enlightenment_print_font_variant_properties_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['value'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'The value parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$style = false !== strpos( $args['value'], 'italic' ) ? 'italic' : 'normal';

	switch ( $args['value'] ) {
		case '300':
		case '300italic':
			$weight = '300';
			break;

		case '400':
		case 'regular':
		case 'italic':
			$weight = '400';
			break;

		case '500':
		case '500italic':
			$weight = '500';
			break;

		case '600':
		case '600italic':
			$weight = '600';
			break;

		case '700':
		case '700italic':
			$weight = '700';
			break;

		default:
			$weight = '';
	}

	$output = '';

	if ( ! empty( $args['media_query'] ) ) {
		$output .= sprintf( "\n@media %s {", esc_attr( $args['media_query'] ) );
	}

	if ( ! empty( $args['selector'] ) ) {
		$output .= sprintf( "\n%s {\n", $args['selector'] );
	}

	$output .= enlightenment_print_css_property( array(
		'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-font-style', $args['css_variable'] ) : 'font-style',
		'value'    => $style,
		'echo'     => false,
	) );

	$output .= enlightenment_print_css_property( array(
		'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-font-weight', $args['css_variable'] ) : 'font-weight',
		'value'    => $weight,
		'echo'     => false,
	) );

	if ( ! empty( $args['selector'] ) ) {
		$output .= "}\n";
	}

	if ( ! empty( $args['media_query'] ) ) {
		$output .= "}\n";
	}

	$output  = apply_filters( 'enlightenment_print_font_variant_properties', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_print_letter_spacing_property( $args = null ) {
	$defaults = array(
		'media_query' => '',
		'selector'    => '',
		'property'    => 'letter-spacing',
		'value'       => '',
		'echo'        => true,
	);
	$defaults = apply_filters( 'enlightenment_print_letter_spacing_property_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['value'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'The value parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	$value = is_numeric( $args['value'] ) ? sprintf( '%spx', $args['value'] ) : $args['value'];

	$output = enlightenment_print_css_property( array_merge( $args, array(
		'value' => $value,
		'echo'  => false,
	) ) );

	$output = apply_filters( 'enlightenment_print_letter_spacing_property', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_print_background_image_property( $args = null ) {
	$defaults = array(
		'media_query' => '',
		'selector'    => '',
		'property'    => 'background-image',
		'value'       => '',
		'echo'        => true,
	);
	$defaults = apply_filters( 'enlightenment_print_background_image_property_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['value'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'The value parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	$value = $args['value'];

	if ( is_numeric( $value ) ) {
		$value = wp_get_attachment_image_src( $value, 'full' );

		if ( ! empty( $value ) ) {
			$value = $value[0];
		}
	}

	$output = enlightenment_print_css_property( array_merge( $args, array(
		'value' => $value,
		'echo'  => false,
	) ) );

	$output = apply_filters( 'enlightenment_print_background_image_property', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_print_background_position_property( $args = null ) {
	$defaults = array(
		'media_query' => '',
		'selector'    => '',
		'property'    => 'background-position',
		'value'       => '',
		'echo'        => true,
	);
	$defaults = apply_filters( 'enlightenment_print_background_position_property_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['value'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'The value parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	$output = enlightenment_print_css_property( array_merge( $args, array(
		'value' => str_replace( '-', ' ', $args['value'] ),
		'echo'  => false,
	) ) );

	$output = apply_filters( 'enlightenment_print_background_position_property', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_print_background_scroll_property( $args = null ) {
	$defaults = array(
		'media_query' => '',
		'selector'    => '',
		'property'    => 'background-attachment',
		'value'       => '',
		'echo'        => true,
	);
	$defaults = apply_filters( 'enlightenment_print_background_scroll_property_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( empty( $args['value'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'The value parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	$output = enlightenment_print_css_property( array_merge( $args, array(
		'value' => 'fixed' == $args['value'] ? 'fixed' : 'scroll',
		'echo'  => false,
	) ) );

	$output = apply_filters( 'enlightenment_print_background_scroll_property', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_print_color_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'format'       => '',
		'opacity'      => 1,
		'rgb_wrapper'  => true,
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_color_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_color_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_color_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'color',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_color_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_color_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_font_family_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_font_family_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_font_family_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_font_family_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'font-family',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_font_family_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_font_family_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_font_size_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_font_size_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_font_size_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_font_size_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'font-size',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_font_size_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_font_size_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_font_variant_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_font_variant_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_font_variant_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output = enlightenment_print_font_variant_properties( array_merge( $args, array(
			'value' => $value,
			'echo'  => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_font_variant_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_font_variant_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_line_height_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_line_height_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_line_height_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_css_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'line-height',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_line_height_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_line_height_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_letter_spacing_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_letter_spacing_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_letter_spacing_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_letter_spacing_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'letter-spacing',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_letter_spacing_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_letter_spacing_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_text_align_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_text_align_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_text_align_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_css_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'text-align',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_text_align_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_text_align_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_text_decoration_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_text_decoration_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_text_decoration_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_css_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'text-decoration-line',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_text_decoration_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_text_decoration_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_text_transform_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_text_transform_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_text_transform_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_css_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'text-transform',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_text_transform_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_text_transform_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_background_color_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_background_color_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_background_color_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_css_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'background-color',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_background_color_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_print_background_image_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_background_image_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_background_image_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_background_image_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'background-image',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_background_image_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_background_image_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_background_position_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_background_position_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_background_position_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_background_position_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'background-position',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_background_position_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_background_position_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_background_repeat_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_background_repeat_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value  = apply_filters( 'enlightenment_print_background_repeat_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_css_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'background-repeat',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_background_repeat_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_background_repeat_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_background_size_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_background_size_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_background_size_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_css_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'background-size',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_background_size_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_background_size_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_background_scroll_option( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$default = apply_filters( 'enlightenment_print_background_scroll_option_settings_defaults', $default_options[ $args['option'] ], $args );
	$value   = apply_filters( 'enlightenment_print_background_scroll_option_settings', get_theme_mod( $args['option'] ), $args );

	$output = '';

	if ( $value && $value != $default ) {
		$output .= enlightenment_print_background_scroll_property( array_merge( $args, array(
			'property' => ! empty( $args['css_variable'] ) ? $args['css_variable'] : 'background-attachment',
			'value'    => $value,
			'echo'     => false,
		) ) );
	}

	$output = apply_filters( 'enlightenment_print_background_scroll_option', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_background_scroll_option', 'enlightenment_sanitize_custom_css' );

function enlightenment_sanitize_custom_css( $input, $default = false ) {
	$input = wp_kses( $input, 'strip' );
	$input = str_replace( 'behavior',   '', $input );
	$input = str_replace( 'expression', '', $input );
	$input = str_replace( 'binding',    '', $input );
	$input = str_replace( '@import',    '', $input );

	return $input;
}

function enlightenment_print_background_options( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$defaults = apply_filters( 'enlightenment_print_background_settings_defaults', $default_options[ $args['option'] ], $args );

	$defaults = wp_parse_args( $defaults, array(
		'color'    => false,
		'image'    => false,
		'position' => false,
		'repeat'   => false,
		'size'     => false,
		'scroll'   => false,
	) );

	$settings = apply_filters( 'enlightenment_print_background_settings', get_theme_mod( $args['option'] ), $args );

	$settings = wp_parse_args( $settings, array(
		'color'    => false,
		'image'    => false,
		'position' => false,
		'repeat'   => false,
		'size'     => false,
		'scroll'   => false,
	) );

	if ( empty( $settings ) || $settings == $defaults ) {
		return '';
	}

	extract( $settings );

	$properties = '';

	if ( $color && $color != $defaults['color'] ) {
		$properties .= enlightenment_print_css_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-bg-color', $args['css_variable'] ) : 'background-color',
			'value'    => $color,
			'echo'     => false,
		) );
	}

	if ( $image && $image != $defaults['image'] ) {
		$properties .= enlightenment_print_background_image_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-bg-image', $args['css_variable'] ) : 'background-image',
			'value'    => $image,
			'echo'     => false,
		) );
	}

	if ( $position && $position != $defaults['position'] ) {
		$properties .= enlightenment_print_background_position_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-bg-position', $args['css_variable'] ) : 'background-position',
			'value'    => $position,
			'echo'     => false,
		) );
	}

	if ( $repeat && $repeat != $defaults['repeat'] ) {
		$properties .= enlightenment_print_css_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-bg-repeat', $args['css_variable'] ) : 'background-repeat',
			'value'    => $repeat,
			'echo'     => false,
		) );
	}

	if ( $size && $size != $defaults['size'] ) {
		$properties .= enlightenment_print_css_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-bg-size', $args['css_variable'] ) : 'background-size',
			'value'    => $size,
			'echo'     => false,
		) );
	}

	if ( $scroll && $scroll != $defaults['scroll'] ) {
		$properties .= enlightenment_print_background_scroll_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-bg-attachment', $args['css_variable'] ) : 'background-attachment',
			'value'    => $scroll,
			'echo'     => false,
		) );
	}

	$output = '';

	if ( ! empty( $properties ) ) {
		if ( ! empty( $args['media_query'] ) ) {
			$output .= sprintf( "\n@media %s {", esc_attr( $args['media_query'] ) );
		}

		$output .= sprintf( "\n%s {\n", $args['selector'] );

		$output .= $properties;

		$output .= "}\n";

		if ( ! empty( $args['media_query'] ) ) {
			$output .= "}\n";
		}
	}

	$output = apply_filters( 'enlightenment_print_background_options', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_background_options', 'enlightenment_sanitize_custom_css' );

function enlightenment_print_typography_options( $args = null ) {
	$args = wp_parse_args( $args, array(
		'media_query'  => '',
		'selector'     => '',
		'css_variable' => '',
		'option'       => '',
		'color_format' => '',
		'opacity'      => 1,
		'rgb_wrapper'  => true,
		'echo'         => true,
	) );

	if ( empty( $args['selector'] ) && ! empty( $args['css_variable'] ) ) {
		$args['selector'] = ':root';
	}

	if ( empty( $args['selector'] ) && empty( $args['css_variable'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Either the selector or CSS variable parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( empty( $args['option'] ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Option parameter is required.', 'enlightenment' ), '2.0.0' );
		return '';
	}

	if ( ! empty( $args['css_variable'] ) && 0 !== strpos( $args['css_variable'], '--' ) ) {
		$args['css_variable'] = sprintf( '--%s', $args['css_variable'] );
	}

	$default_options = enlightenment_default_theme_mods();

	if ( ! isset( $default_options[ $args['option'] ] ) ) {
		$default_options[ $args['option'] ] = false;
	}

	$defaults = apply_filters( 'enlightenment_print_typography_settings_defaults', $default_options[ $args['option'] ], $args );

	$defaults = wp_parse_args( $defaults, array(
		'font_color'      => false,
		'font_family'     => false,
		'font_size'       => false,
		'font_variant'    => false,
		'line_height'     => false,
		'letter_spacing'  => false,
		'text_align'      => false,
		'text_decoration' => false,
		'text_transform'  => false,
	) );

	$settings = apply_filters( 'enlightenment_print_typography_settings', get_theme_mod( $args['option'] ), $args );

	$settings = wp_parse_args( $settings, array(
		'font_color'      => false,
		'font_family'     => false,
		'font_size'       => false,
		'font_variant'    => false,
		'line_height'     => false,
		'letter_spacing'  => false,
		'text_align'      => false,
		'text_decoration' => false,
		'text_transform'  => false,
	) );

	if ( empty( $settings ) || $settings == $defaults ) {
		return '';
	}

	extract( $settings );

	$properties = '';

	if ( $font_color && $font_color != $defaults['font_color'] ) {
		$variable = '%s-color';

		if ( ! $args['rgb_wrapper'] && in_array( $args['color_format'], array( 'rgb', 'rgba' ) ) ) {
			$variable .= '-rgb';

			$args['color_format'] = 'rgb';
		}

		$properties .= enlightenment_print_color_property( array(
			'property'    => ! empty( $args['css_variable'] ) ? sprintf( $variable, $args['css_variable'] ) : 'color',
			'value'       => $font_color,
			'format'      => $args['color_format'],
			'opacity'     => $args['opacity'],
			'rgb_wrapper' => $args['rgb_wrapper'],
			'echo'        => false,
		) );

		if (
			! empty( $args['css_variable'] )
			&&
			! $args['rgb_wrapper']
			&&
			'rgb' == $args['color_format']
		) {
			$properties .= enlightenment_print_css_property( array(
				'property' => sprintf( '%s-color-opacity', $args['css_variable'] ),
				'value'    => $args['opacity'],
				'echo'     => false,
			) );
		}
	}

	if ( $font_family && $font_family != $defaults['font_family'] ) {
		$properties .= enlightenment_print_font_family_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-font-family', $args['css_variable'] ) : 'font-family',
			'value'    => $font_family,
			'echo'     => false,
		) );
	}

	if ( $font_size && $font_size != $defaults['font_size'] ) {
		$properties .= enlightenment_print_font_size_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-font-size', $args['css_variable'] ) : 'font-size',
			'value'    => $font_size,
			'echo'     => false,
		) );
	}

	if ( $font_variant && $font_variant != $defaults['font_variant'] ) {
		$properties .= enlightenment_print_font_variant_properties( array(
			'css_variable' => $args['css_variable'],
			'value'        => $font_variant,
			'echo'         => false,
		) );
	}

	if ( $line_height && $line_height != $defaults['line_height'] ) {
		$properties .= enlightenment_print_css_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-line-height', $args['css_variable'] ) : 'line-height',
			'value'    => $line_height,
			'echo'     => false,
		) );
	}

	if ( $letter_spacing && $letter_spacing != $defaults['letter_spacing'] ) {
		$properties .= enlightenment_print_letter_spacing_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-letter-spacing', $args['css_variable'] ) : 'letter-spacing',
			'value'    => $letter_spacing,
			'echo'     => false,
		) );
	}

	if ( $text_align && $text_align != $defaults['text_align'] ) {
		$properties .= enlightenment_print_css_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-text-align', $args['css_variable'] ) : 'text-align',
			'value'    => $text_align,
			'echo'     => false,
		) );
	}

	if ( $text_decoration && $text_decoration != $defaults['text_decoration'] ) {
		$properties .= enlightenment_print_css_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-text-decoration-line', $args['css_variable'] ) : 'text-decoration-line',
			'value'    => $text_decoration,
			'echo'     => false,
		) );
	}

	if ( $text_transform && $text_transform != $defaults['text_transform'] ) {
		$properties .= enlightenment_print_css_property( array(
			'property' => ! empty( $args['css_variable'] ) ? sprintf( '%s-text-transform', $args['css_variable'] ) : 'text-transform',
			'value'    => $text_transform,
			'echo'     => false,
		) );
	}

	$output = '';

	if ( ! empty( $properties ) ) {
		if ( ! empty( $args['media_query'] ) ) {
			$output .= sprintf( "\n@media %s {", esc_attr( $args['media_query'] ) );
		}

		$output .= sprintf( "\n%s {\n", $args['selector'] );

		$output .= $properties;

		$output .= "}\n";

		if ( ! empty( $args['media_query'] ) ) {
			$output .= "}\n";
		}
	}

	$output = apply_filters( 'enlightenment_print_typography_options', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

add_filter( 'enlightenment_print_typography_options', 'enlightenment_sanitize_custom_css' );
