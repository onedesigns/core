<?php

add_filter( 'current_theme_supports-enlightenment-unlimited-sidebars', 'enlightenment_filter_current_theme_supports', 10, 3 );

function enlightenment_sidebar_locations_options( $locations ) {
	if ( is_singular() ) {
		global $post;

		$post_id   = isset( $post ) ? $post->ID : $_GET['post'];
		$post_type = get_post_type( $post_id );
		$sidebars  = array();

		$sidebars[ $post_type ] = get_post_meta( $post_id, '_enlightenment_sidebar_locations', true );

		if( '' == $sidebars[ $post_type ] ) {
			$sidebars = get_theme_mod( 'sidebar_locations' );
		}
	} else {
		$sidebars = get_theme_mod( 'sidebar_locations' );
	}

	foreach ( $locations as $template => $locs ) {
		foreach ( $locs as $location => $atts ) {
			if( isset( $sidebars[ $template ][ $location ] ) ) {
				$locations[ $template ][ $location ]['sidebar'] = $sidebars[ $template ][ $location ];
			}
		}
	}

	return $locations;
}
add_filter( 'enlightenment_sidebar_locations_options', 'enlightenment_sidebar_locations_options' );

function enlightenment_registered_sidebars_default_atts_merge_theme_support_args( $atts ) {
	if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'custom_sidebar_background' ) ) {
		$atts['background'] = array(
			'color'    => 'rgba(255,255,255,0)',
			'image'    => false,
			'position' => 'center-center',
			'repeat'   => 'no-repeat',
			'size'     => 'cover',
			'scroll'   => 'scroll',
		);
	}

	if ( current_theme_supports( 'enlightenment-unlimited-sidebars', 'custom_widgets_background' ) ) {
		$atts['widgets_background_color'] = 'rgba(255,255,255,0)';
	}

	$atts['sidebar_title_color']       = current_theme_supports( 'enlightenment-unlimited-sidebars', 'sidebar_title_color' );
	$atts['sidebar_text_color']        = current_theme_supports( 'enlightenment-unlimited-sidebars', 'sidebar_text_color' );
	$atts['widgets_title_color']       = current_theme_supports( 'enlightenment-unlimited-sidebars', 'widgets_title_color' );
	$atts['widgets_text_color']        = current_theme_supports( 'enlightenment-unlimited-sidebars', 'widgets_text_color' );
	$atts['widgets_link_color']        = current_theme_supports( 'enlightenment-unlimited-sidebars', 'widgets_link_color' );
	$atts['widgets_link_hover_color']  = current_theme_supports( 'enlightenment-unlimited-sidebars', 'widgets_link_hover_color' );
	$atts['widgets_link_active_color'] = current_theme_supports( 'enlightenment-unlimited-sidebars', 'widgets_link_active_color' );

	return $atts;
}
add_filter( 'enlightenment_registered_sidebars_default_atts', 'enlightenment_registered_sidebars_default_atts_merge_theme_support_args' );

function enlightenment_registered_sidebars_merge_theme_options( $sidebars ) {
	$options = get_theme_mod( 'sidebars' );

	if ( empty( $options ) ) {
		return $sidebars;
	}

	if ( ! is_array( $options ) ) {
		return $sidebars;
	}

	$defaults = enlightenment_registered_sidebars_default_atts();

	foreach ( $options as $sidebar => $atts ) {
		$options[ $sidebar ] = array_merge( $defaults, $atts );
	}

	$sidebars = array_merge( $sidebars, $options );

	return $sidebars;
}
add_filter( 'enlightenment_registered_sidebars', 'enlightenment_registered_sidebars_merge_theme_options' );

function enlightenment_unlimited_sidebars_sidebar_class_args( $args ) {
	$sidebar = enlightenment_get_dynamic_sidebar_id();

	if ( isset( $sidebar ) ) {
		$args['class'] .= ' custom-sidebar custom-' . $sidebar;
	}

	return $args;
}
add_filter( 'enlightenment_sidebar_class_args', 'enlightenment_unlimited_sidebars_sidebar_class_args' );

function enlightenment_unlimited_sidebars_print_css( $output ) {
	$sidebars = enlightenment_registered_sidebars();

	foreach ( $sidebars as $sidebar => $atts ) {
		$selector                 = '.custom-' . $sidebar;
		$bg_selector              = $selector;
		$widget_headings_selector = $selector . ' .widget-title, ' . $selector . ' .widget h1, ' . $selector . ' .widget h2, ' . $selector . ' .widget h3, ' . $selector . ' .widget h4, ' . $selector . ' .widget h5, ' . $selector . ' .widget h6';

		if ( is_array( $atts['background'] ) ) {
			$output .= enlightenment_print_background_options( array(
				'selector' => $bg_selector,
				'option'   => $sidebar,
				'echo'     => false,
			) );
		}

		$output .= enlightenment_print_color_option( array(
			'selector' => $selector . ' .sidebar-title',
			'option'   => $sidebar,
			'echo'     => false,
		) );

		$output .= enlightenment_print_color_option( array(
			'selector' => $selector . ' .sidebar-description',
			'option'   => $sidebar,
			'echo'     => false,
		) );

		$output .= enlightenment_print_color_option( array(
			'selector' => $selector . ' .widget',
			'option'   => $sidebar,
			'echo'     => false,
		) );

		$output .= enlightenment_print_color_option( array(
			'selector' => $widget_headings_selector,
			'option'   => $sidebar,
			'echo'     => false,
		) );

		$output .= enlightenment_print_color_option( array(
			'selector' => $selector . ' .widget a',
			'option'   => $sidebar,
			'echo'     => false,
		) );

		$output .= enlightenment_print_color_option( array(
			'selector' => $selector . ' .widget a:hover',
			'option'   => $sidebar,
			'echo'     => false,
		) );

		$output .= enlightenment_print_color_option( array(
			'selector' => $selector . ' .widget a:active',
			'option'   => $sidebar,
			'echo'     => false,
		) );

		$output .= enlightenment_print_background_color_option( array(
			'selector' => $selector . ' .widget',
			'option'   => $sidebar,
			'echo'     => false,
		) );
	}

	return $output;
}
add_filter( 'enlightenment_theme_custom_css', 'enlightenment_unlimited_sidebars_print_css' );

function enlightenment_unlimited_sidebars_print_background_settings_defaults( $defaults, $args ) {
	$default_options  = enlightenment_default_theme_mods();
	$default_sidebars = isset( $default_options['sidebars'] ) ? $default_options['sidebars'] : array();
	$sidebars         = enlightenment_registered_sidebars();
	$defaults_atts    = enlightenment_registered_sidebars_default_atts();

	if ( array_key_exists( $args['option'], $default_sidebars ) ) {
		$defaults = $default_sidebars[ $args['option'] ]['background'];
	} elseif ( array_key_exists( $args['option'], $sidebars ) ) {
		$defaults = $defaults_atts['background'];
	}

	return $defaults;
}
add_filter( 'enlightenment_print_background_settings_defaults', 'enlightenment_unlimited_sidebars_print_background_settings_defaults', 10, 2 );

function enlightenment_unlimited_sidebars_print_background_settings( $settings, $args ) {
	$sidebars = enlightenment_registered_sidebars();

	if ( array_key_exists( $args['option'], $sidebars ) ) {
		$settings = $sidebars[ $args['option'] ]['background'];
	}

	return $settings;
}
add_filter( 'enlightenment_print_background_settings', 'enlightenment_unlimited_sidebars_print_background_settings', 10, 2 );

function enlightenment_unlimited_sidebars_print_color_option_settings_defaults( $color, $args ) {
	$default_options  = enlightenment_default_theme_mods();
	$default_sidebars = isset( $default_options['sidebars'] ) ? $default_options['sidebars'] : array();

	if( array_key_exists( $args['option'], $default_sidebars ) ) {
		if ( strpos( $args['selector'], '.sidebar-title' ) ) {
			$color = $default_sidebars[ $args['option'] ]['sidebar_title_color'];
		} elseif ( strpos( $args['selector'], '.sidebar-description' ) ) {
			$color = $default_sidebars[ $args['option'] ]['sidebar_text_color'];
		} elseif ( strpos( $args['selector'], '.widget-title' ) ) {
			$color = $default_sidebars[ $args['option'] ]['widgets_title_color'];
		} elseif ( strpos( $args['selector'], '.widget a:hover' ) ) {
			$color = $default_sidebars[ $args['option'] ]['widgets_link_hover_color'];
		} elseif ( strpos( $args['selector'], '.widget a:active' ) ) {
			$color = $default_sidebars[ $args['option'] ]['widgets_link_active_color'];
		} elseif ( strpos( $args['selector'], '.widget a' ) ) {
			$color = $default_sidebars[ $args['option'] ]['widgets_link_color'];
		} elseif ( strpos( $args['selector'], '.widget' ) ) {
			$color = $default_sidebars[ $args['option'] ]['widgets_text_color'];
		}
	} else {
		$defaults = enlightenment_registered_sidebars_default_atts();

		if ( strpos( $args['selector'], '.sidebar-title' ) ) {
			$color = $defaults['sidebar_title_color'];
		} elseif ( strpos( $args['selector'], '.sidebar-description' ) ) {
			$color = $defaults['sidebar_text_color'];
		} elseif ( strpos( $args['selector'], '.widget-title' ) ) {
			$color = $defaults['widgets_title_color'];
		} elseif ( strpos( $args['selector'], '.widget a:hover' ) ) {
			$color = $defaults['widgets_link_hover_color'];
		} elseif ( strpos( $args['selector'], '.widget a:active' ) ) {
			$color = $defaults['widgets_link_active_color'];
		} elseif ( strpos( $args['selector'], '.widget a' ) ) {
			$color = $defaults['widgets_link_color'];
		} elseif ( strpos( $args['selector'], '.widget' ) ) {
			$color = $defaults['widgets_text_color'];
		}
	}

	return $color;
}
add_filter( 'enlightenment_print_color_option_settings_defaults', 'enlightenment_unlimited_sidebars_print_color_option_settings_defaults', 10, 2 );

function enlightenment_unlimited_sidebars_print_color_option_settings( $color, $args ) {
	$sidebars = enlightenment_registered_sidebars();

	if( array_key_exists( $args['option'], $sidebars ) ) {
		if ( strpos( $args['selector'], '.sidebar-title' ) ) {
			$color = $sidebars[ $args['option'] ]['sidebar_title_color'];
		} elseif ( strpos( $args['selector'], '.sidebar-description' ) ) {
			$color = $sidebars[ $args['option'] ]['sidebar_text_color'];
		} elseif ( strpos( $args['selector'], '.widget-title' ) ) {
			$color = $sidebars[ $args['option'] ]['widgets_title_color'];
		} elseif ( strpos( $args['selector'], '.widget a:hover' ) ) {
			$color = $sidebars[ $args['option'] ]['widgets_link_hover_color'];
		} elseif ( strpos( $args['selector'], '.widget a:active' ) ) {
			$color = $sidebars[ $args['option'] ]['widgets_link_active_color'];
		} elseif ( strpos( $args['selector'], '.widget a' ) ) {
			$color = $sidebars[ $args['option'] ]['widgets_link_color'];
		} elseif ( strpos( $args['selector'], '.widget' ) ) {
			$color = $sidebars[ $args['option'] ]['widgets_text_color'];
		}
	}

	return $color;
}
add_filter( 'enlightenment_print_color_option_settings', 'enlightenment_unlimited_sidebars_print_color_option_settings', 10, 2 );

function enlightenment_unlimited_sidebars_print_background_color_option_settings_defaults( $color, $args ) {
	$sidebars = enlightenment_registered_sidebars();

	if ( ! array_key_exists( $args['option'], $sidebars ) ) {
		return $color;
	}

	$default_options  = enlightenment_default_theme_mods();
	$default_sidebars = isset( $default_options['sidebars'] ) ? $default_options['sidebars'] : array();

	if ( array_key_exists( $args['option'], $default_sidebars ) ) {
		$color = $default_sidebars[ $args['option'] ]['widgets_background_color'];
	} else {
		$defaults = enlightenment_registered_sidebars_default_atts();
		$color    = $defaults['widgets_background_color'];
	}

	return $color;
}
add_filter( 'enlightenment_print_background_color_option_settings_defaults', 'enlightenment_unlimited_sidebars_print_background_color_option_settings_defaults', 10, 2 );

function enlightenment_unlimited_sidebars_print_background_color_option_settings( $color, $args ) {
	$sidebars = enlightenment_registered_sidebars();

	if ( ! array_key_exists( $args['option'], $sidebars ) ) {
		return $color;
	}

	return $sidebars[ $args['option'] ]['widgets_background_color'];
}
add_filter( 'enlightenment_print_background_color_option_settings', 'enlightenment_unlimited_sidebars_print_background_color_option_settings', 10, 2 );

function enlightenment_remove_primary_sidebar_in_full_width( $is_active_sidebar, $index ) {
	if ( current_theme_supports( 'enlightenment-custom-layouts' ) && 'full-width' == enlightenment_current_layout() && 'primary' == enlightenment_current_sidebar_name() ) {
		remove_filter( 'is_active_sidebar', 'enlightenment_remove_sidebar_in_full_width', 8 );

		return false;
	}

	return $is_active_sidebar;
}
add_filter( 'is_active_sidebar', 'enlightenment_remove_primary_sidebar_in_full_width', 5, 2 );

add_filter( 'enlightenment_sidebar_title',       'esc_html', 1 );
add_filter( 'enlightenment_sidebar_description', 'esc_html', 1 );
add_filter( 'enlightenment_sidebar_description', 'wpautop' );

function enlightenment_delete_sidebar_locations_post_meta_when_empty( $check, $post_id, $meta_key, $meta_value ) {
	if ( '_enlightenment_sidebar_locations' != $meta_key ) {
		return $check;
	}

	if ( ! empty( $meta_value ) ) {
		return $check;
	}

	delete_post_meta( $post_id, $meta_key );

	return true;
}
add_filter( 'update_post_metadata', 'enlightenment_delete_sidebar_locations_post_meta_when_empty', 10, 4 );
