<?php

function enlightenment_unlimited_sidebars_theme_support_args() {
	$defaults = array(
		'custom_sidebar_background' => false,
		'custom_widgets_background' => false,
		'sidebar_title_color'       => false,
		'sidebar_text_color'        => false,
		'widgets_title_color'       => false,
		'widgets_text_color'        => false,
		'widgets_link_color'        => false,
		'widgets_link_hover_color'  => false,
		'widgets_link_active_color' => false,
	);

	$args = get_theme_support( 'enlightenment-unlimited-sidebars' );
	$args = is_array( $args ) ? array_shift( $args ) : array();
	$args = wp_parse_args( $args, $defaults );

	global $_wp_theme_features;

	if( ! is_array( $_wp_theme_features['enlightenment-unlimited-sidebars'] ) ) {
		$_wp_theme_features['enlightenment-unlimited-sidebars'] = array();
	}

	$_wp_theme_features['enlightenment-unlimited-sidebars'][0] = $args;
}
add_action( 'after_setup_theme', 'enlightenment_unlimited_sidebars_theme_support_args', 50 );

function enlightenment_register_unlimited_sidebars_post_meta() {
	$settings   = enlightenment_sidebar_locations();
	$post_types = array_merge(
        array(
            'page' => 'page',
        ),
        get_post_types( array(
            'publicly_queryable' => true,
        ) )
    );

	foreach ( $post_types as $key => $post_type ) {
		if ( ! post_type_supports( $post_type, 'custom-fields' ) ) {
			unset( $post_types[ $key ] );
		}
	}

	unset( $post_types['attachment'] );
	unset( $post_types['e-landing-page'] );
	unset( $post_types['e-floating-buttons'] );

	foreach ( $post_types as $post_type ) {
		$args = array(
			'single'        => true,
			'type'          => 'object',
			'auth_callback' => 'enlightenment_can_edit_post_type',
		);

		$locations  = $settings[ $post_type ];
		$properties = array();

		foreach ( $locations as $location => $atts ) {
			$properties[ $location ] = array(
				'type' => 'string',
			);
		}

		$args['show_in_rest'] = array(
			'schema' => array(
				'type'       => 'object',
				'properties' => $properties,
			),
		);

		register_post_meta( $post_type, '_enlightenment_sidebar_locations', $args );
	}
}
/* Always hook with a priority greater than 1 to ensure it's executed after `widgets_init` */
add_action( 'init', 'enlightenment_register_unlimited_sidebars_post_meta' );

function enlightenment_register_dynamic_sidebars() {
	global $wp_registered_sidebars, $enlightenment_default_sidebars;

	$enlightenment_default_sidebars = array_keys( $wp_registered_sidebars );

	$sidebars = get_theme_mod( 'sidebars' );

	if ( empty( $sidebars ) ) {
		return;
	}

	if ( ! is_array( $sidebars ) ) {
		return;
	}

	foreach ( $sidebars as $sidebar => $atts ) {
		$before = '';
		$after  = '';

		if ( current_theme_supports( 'enlightenment-grid-loop' ) && isset( $atts['grid'] ) ) {
			$class = '';

			if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
				$prefixes = enlightenment_bootstrap_breakpoint_prefixes();

				foreach( $atts['grid'] as $breakpoint => $grid ) {
					if( 'inherit' == $grid ) {
						continue;
					}

					$attr = enlightenment_get_grid( $grid );

					if ( 1 < $attr['content_columns'] ) {
						$class .= sprintf( ' %s', sprintf( $attr['entry_class'], $prefixes[ $breakpoint ] ) );
					}
				}
			} else {
				$grid = enlightenment_get_grid( $atts['grid'] );

				if ( 1 < $grid['content_columns'] ) {
					$class = sprintf( '%s', $attr['entry_class'] );
				}
			}

			if ( ! empty( $class ) ) {
				$before = enlightenment_open_tag( 'div', trim( $class ) );
				$after  = enlightenment_close_tag( 'div' );
			}
		}

		register_sidebar( array(
			'name'          => $atts['name'],
			'id'            => $sidebar,
			'before_widget' => $before . '<section id="%1$s" class="widget %2$s">' . "\n",
			'after_widget'  => '</section>' . $after . "\n",
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>' . "\n",
		) );
	}
}
add_action( 'widgets_init', 'enlightenment_register_dynamic_sidebars', 30 );

function enlightenment_get_sidebar( $name = null ) {
	$templates = array();
	$name = (string) $name;

	if ( '' !== $name ) {
		$templates[] = "sidebar-{$name}.php";
	}

	$templates[] = 'sidebar.php';

	locate_template( $templates, true, false );
}
add_action( 'get_sidebar', 'enlightenment_get_sidebar', 9999 );

function enlightenment_sidebar_heading( $args = null ) {
	$defaults = array(
		'container'                      => 'header',
		'container_class'                => 'sidebar-heading',
		'container_id'                   => '',
		'container_extra_atts'           => '',
		'sidebar_title_tag'              => 'h2',
		'sidebar_title_class'            => 'sidebar-title',
		'sidebar_title_id'               => '',
		'sidebar_title_extra_atts'       => '',
		'sidebar_description_tag'        => 'div',
		'sidebar_description_class'      => 'sidebar-description',
		'sidebar_description_id'         => '',
		'sidebar_description_extra_atts' => '',
		'echo'                           => true,
	);
	$defaults = apply_filters( 'enlightenment_sidebar_header_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$locations = enlightenment_sidebar_locations();
	$template  = enlightenment_current_sidebars_template();
	$location  = enlightenment_current_sidebar_name();

	if( '' == $locations[$template][$location]['sidebar'] ) {
		return;
	}

	$sidebars = enlightenment_registered_sidebars();
	$sidebar  = $sidebars[ $locations[$template][$location]['sidebar'] ];

	$sidebar_title       = apply_filters( 'enlightenment_sidebar_title',       $sidebar['name'] );
	$sidebar_description = apply_filters( 'enlightenment_sidebar_description', $sidebar['description'] );

	if( ! $sidebar['display_title'] && ! ( $sidebar['display_description'] ) ) {
		return;
	}

	$output = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	if( $sidebar['display_title'] ) {
		$output .= enlightenment_open_tag( $args['sidebar_title_tag'],  $args['sidebar_title_class'], $args['sidebar_title_id'], $args['sidebar_title_extra_atts'] );
		$output .= $sidebar_title;
		$output .= enlightenment_close_tag( $args['sidebar_title_tag'] );
	}

	if( $sidebar['display_description'] && ! empty( $sidebar_description ) ) {
		$output .= enlightenment_open_tag( $args['sidebar_description_tag'], $args['sidebar_description_class'], $args['sidebar_description_id'], $args['sidebar_description_extra_atts'] );
		$output .= $sidebar_description . "\n";
		$output .= enlightenment_close_tag( $args['sidebar_description_tag'] );
	}

	$output .= enlightenment_close_tag( $args['container'] );
	$output  = apply_filters( 'enlightenment_sidebar_header', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
add_action( 'enlightenment_before_widgets', 'enlightenment_sidebar_heading', 9 );
