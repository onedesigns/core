<?php

function enlightenment_web_fonts_set_theme_options_args( $args ) {
	$mods = get_theme_mods();
	$mod  = get_theme_mod( 'web_fonts' );

	if ( ! isset( $mods['web_fonts'] ) ) {
		return $args;
	}

	if ( isset( $mods['web_fonts']['host_locally'] ) ) {
		$args['host_locally'] = $mod['host_locally'];
	}

	if ( isset( $mods['web_fonts']['provider'] ) ) {
		$args['provider'] = $mod['provider'];
	}

	if ( isset( $mods['web_fonts']['subsets'] ) ) {
		$args['subsets'] = $mod['subsets'];
	}

	if ( isset( $mods['web_fonts']['filter_by_subsets'] ) ) {
		$args['filter_by_subsets'] = $mod['filter_by_subsets'];
	}

	if ( isset( $mods['web_fonts']['font_display'] ) ) {
		$args['font_display'] = $mod['font_display'];
	}

	if ( isset( $mods['web_fonts']['sort_fonts_by'] ) ) {
		$args['sort_fonts_by'] = $mod['sort_fonts_by'];
	}

	return $args;
}
add_filter( 'enlightenment_web_fonts_theme_support_args', 'enlightenment_web_fonts_set_theme_options_args' );

function enlightenment_web_fonts_set_default_theme_mods( $mods ) {
	$default_mods = enlightenment_web_fonts_get_default_theme_mods();

	if ( ! isset( $mods['web_fonts'] ) ) {
		$mods['web_fonts'] = array();
	}

	$mods['web_fonts'] = array_merge( $mods['web_fonts'], $default_mods );

	return $mods;
}
add_filter( 'enlightenment_default_theme_mods', 'enlightenment_web_fonts_set_default_theme_mods' );

function enlightenment_set_theme_options_web_fonts( $fonts ) {
	$option = get_theme_mod( 'web_fonts' );

	if ( is_array( $option ) && isset( $option['fonts'] ) ) {
		// Remove any local fonts that are disabled in customizer.
		$fonts = array_intersect_key( $fonts, $option['fonts'] );
		// Add any extra fonts enabled in customizer.
		$fonts = array_merge( $fonts, $option['fonts'] );
	}

	return $fonts;
}
add_filter( 'enlightenment_web_fonts', 'enlightenment_set_theme_options_web_fonts' );

function enlightenment_set_web_fonts_ascent_overrides( $fonts ) {
	$overrides = apply_filters( 'enlightenment_web_fonts_ascent_overrides', array(
		'Hind' => '120',
	) );

	foreach ( $fonts as $font => $atts ) {
		if ( isset( $overrides[ $font ] ) ) {
			$fonts[ $font ]['ascent'] = $overrides[ $font ];
		}
	}

	return $fonts;
}
add_filter( 'enlightenment_web_fonts', 'enlightenment_set_web_fonts_ascent_overrides', 999 );

function enlightenment_add_web_fonts_to_available_fonts( $fonts ) {
	return array_merge( $fonts, enlightenment_get_web_fonts() );
}
add_filter( 'enlightenment_available_fonts', 'enlightenment_add_web_fonts_to_available_fonts' );

function enlightenment_set_theme_support_fonts( $available_fonts ) {
	if ( doing_filter( 'enlightenment_web_fonts_theme_support_args' ) ) {
		return $available_fonts;
	}

	$extra_fonts = current_theme_supports( 'enlightenment-web-fonts', 'extra_fonts' );

	if ( ! is_array( $extra_fonts ) ) {
		return $available_fonts;
	}

	return wp_parse_args( $extra_fonts, $available_fonts );
}
add_filter( 'enlightenment_web_fonts', 'enlightenment_set_theme_support_fonts' );

add_filter( 'current_theme_supports-enlightenment-web-fonts', 'enlightenment_filter_current_theme_supports', 10, 3 );

function enlightenment_remote_web_fonts_remove_emoji( $fonts ) {
	foreach ( $fonts as $key => $font ) {
		if (
			1 === count( $font->subsets )
			&&
			in_array( 'emoji', $font->subsets )
		) {
			unset( $fonts[ $key ] );
		}
	}

	return $fonts;
}
add_filter( 'enlightenment_remote_web_fonts', 'enlightenment_remote_web_fonts_remove_emoji' );

function enlightenment_remote_web_fonts_remove_icons( $fonts ) {
	foreach ( $fonts as $key => $font ) {
		if ( 0 === strpos( $font->family, 'Material Icons' ) ) {
			unset( $fonts[ $key ] );
		}
	}

	return $fonts;
}
add_filter( 'enlightenment_remote_web_fonts', 'enlightenment_remote_web_fonts_remove_icons' );

function enlightenment_web_fonts_set_theme_stylesheet_deps( $deps ) {
	if ( current_theme_supports( 'enlightenment-web-fonts', 'host_locally' ) ) {
		return $deps;
	}

	$fonts = enlightenment_get_fonts_to_load();

	if ( ! empty( $fonts ) ) {
		$deps[] = 'enlightenment-web-fonts';
	}

	return $deps;
}
add_filter( 'enlightenment_theme_stylesheet_deps', 'enlightenment_web_fonts_set_theme_stylesheet_deps' );

function enlightenment_web_fonts_set_theme_inline_style( $output ) {
	if ( ! current_theme_supports( 'enlightenment-web-fonts', 'host_locally' ) ) {
		return $output;
	}

	$output .= enlightenment_get_web_fonts_inline_style();

	return $output;
}
add_filter( 'enlightenment_theme_custom_css', 'enlightenment_web_fonts_set_theme_inline_style' );
