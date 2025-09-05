<?php
/**
 * Customizer functions for Grid Loop.
 *
 * @package Enlightenment_Framework
 */

function enlightenment_archive_grids_customizer_links( $grids ) {
	if ( ! is_customize_preview() ) {
		return $grids;
	}

	if ( isset( $grids['search'] ) ) {
		$grids['search']['name'] = __( 'Search', 'enlightenment' );
		$grids['search']['url']  = add_query_arg( 's', '', home_url( '/' ) );
	}

	if ( isset( $grids['post'] ) ) {
		$grids['post']['name'] = __( 'Blog', 'enlightenment' );
		$grids['post']['url']  = 'page' == get_option( 'show_on_front' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
	}

	$posts = get_posts( array(
		'posts_per_page' => 1,
	) );

	if ( count( $posts ) ) {
		if ( isset( $grids['author'] ) ) {
			$grids['author']['name'] = __( 'Author', 'enlightenment' );
			$grids['author']['url']  = get_author_posts_url( $posts[0]->post_author );
		}

		if ( isset( $grids['date'] ) ) {
			$grids['date']['name'] = __( 'Date', 'enlightenment' );
			$grids['date']['url']  = get_year_link( date( 'Y', strtotime( $posts[0]->post_date ) ) );
		}
	}

	$post_types = get_post_types( array( 'has_archive' => true ), 'objects' );

	foreach ( $post_types as $name => $post_type ) {
		if ( isset( $grids[ $name ] ) ) {
			$grids[ $name ]['name'] = $post_type->labels->name;
			$grids[ $name ]['url']  = get_post_type_archive_link( $name );
		}
	}

	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

	unset( $taxonomies['post_format'] );
	unset( $taxonomies['product_shipping_class'] );

	foreach ( $taxonomies as $name => $taxonomy ) {
		if ( isset( $grids[ $name ] ) ) {
			if ( 'post_tag' == $name ) {
				$taxonomy->labels->singular_name = __( 'Post Tag', 'enlightenment' );
			}

			$url   = '';
			$terms = get_terms( array(
				'taxonomy' => $name,
				'number'   => 1,
			) );

			if ( count( $terms ) ) {
				$url = get_term_link( array_shift( $terms ) );
			}

			$grids[ $name ]['name'] = $taxonomy->labels->singular_name;
			$grids[ $name ]['url']  = $url;
		}
	}

	return $grids;
}
add_filter( 'enlightenment_archive_grids', 'enlightenment_archive_grids_customizer_links', 40 );

function enlightenment_grid_loop_templates() {
	$templates = array(
		'default'  => __( 'All',      'enlightenment' ),
		'search'   => __( 'Search',   'enlightenment' ),
		'post'     => __( 'Blog',     'enlightenment' ),
		'author'   => __( 'Author',   'enlightenment' ),
		'date'     => __( 'Date',     'enlightenment' ),
		'category' => __( 'Category', 'enlightenment' ),
		'post_tag' => __( 'Tag',      'enlightenment' ),
	);

	$post_types = get_post_types( array( 'has_archive' => true ), 'objects' );

	foreach ( $post_types as $name => $post_type ) {
		$templates[$name] = sprintf( __( '%s Archive', 'enlightenment' ), $post_type->labels->name );
	}

	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

	unset( $taxonomies['post_format'] );
	unset( $taxonomies['product_shipping_class'] );

	foreach ( $taxonomies as $name => $taxonomy ) {
		$templates[ $name ] = $taxonomy->labels->singular_name;
	}

	return apply_filters( 'enlightenment_grid_loop_templates', $templates );
}

function enlightenment_current_grid_template() {
	$template = '';

	if( is_home() && ! is_page() ) {
		$template = 'post';
	} elseif( is_author() ) {
		$template = 'author';
	} elseif( is_date() ) {
		$template = 'date';
	} elseif( is_post_type_archive() ) {
		$template = get_query_var( 'post_type' );
	} elseif( is_category() ) {
		$template = 'category';
	} elseif( is_tag() ) {
		$template = 'post_tag';
	} elseif( is_tax() ) {
		$template = get_queried_object()->taxonomy;
	} elseif( is_search() ) {
		$template = 'search';
	}

	return apply_filters( 'enlightenment_current_grid_template', $template );
}

/**
 * Customizer settings, sections and controls.
 */
function enlightenment_grid_loop_customize_register( $wp_customize ) {
	$type      = 'theme_mod';
	$cap       = 'edit_theme_options';
	$supports  = 'enlightenment-grid-loop';
	$grids     = enlightenment_archive_grids();
	$templates = enlightenment_grid_loop_templates();
	$columns   = enlightenment_grid_columns();
	$choices   = array();

	foreach( $columns as $grid => $atts ) {
		$choices[ $grid ] = array(
			'svg' => ( 0 === strpos( $attr['image'], '<svg ' ) ? $attr['image'] : '' ),
			'src' => ( 0 === strpos( $attr['image'], '<svg ' ) ? '' : $attr['image'] ),
			'alt' => $atts['name'],
		);
	}

	$wp_customize->add_panel( 'grid-loop', array(
		'title'          => __( 'Grid', 'enlightenment' ),
		'description'    => sprintf( '<p>%s</p>', __( 'Select a template to customize.' ) ),
		'priority'       => 106,
		'theme_supports' => $supports,
	) );

	foreach( $grids as $template => $atts ) {
		$wp_customize->add_section( sprintf( 'grid-loop-template-%s', $template ), array(
			'panel'          => 'grid-loop',
			'title'          => __( 'Grid', 'enlightenment' ),
			'description'    => sprintf( '<p>%s</p>', sprintf( __( 'You are customizing the grid for the <strong>%s</strong> template.', 'enlightenment' ), $templates[ $template ] ) ),
		) );

		$wp_customize->add_setting( sprintf( 'grids[%s][grid]', $template ), array(
			'type'              => $type,
			'capability'        => $cap,
			'theme_supports'    => $supports,
			'default'           => $atts['grid'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( new Enlightenment_Customize_Image_Radio_Control( $wp_customize, sprintf( 'grids[%s][grid]', $template ), array(
			'section'         => sprintf( 'grid-loop-template-%s', $template ),//'grid-loop',
			'label'           => __( 'Select Grid', 'enlightenment' ),
			'description'     => '',
			'choices'         => $choices,
		) ) );

		$wp_customize->add_setting( sprintf( 'grids[%s][lead_posts]', $template ), array(
			'type'              => $type,
			'capability'        => $cap,
			'theme_supports'    => $supports,
			'default'           => $atts['lead_posts'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( sprintf( 'grids[%s][lead_posts]', $template ), array(
			'section'         => sprintf( 'grid-loop-template-%s', $template ),//'grid-loop',
			'type'            => 'number',
			'label'           => __( 'Leading Posts', 'enlightenment' ),
			'description'     => '',
			'input_attrs'     => array(
				'min' => 0,
				'max' => get_option( 'posts_per_page' ),
			),
		) );
	}
}
add_action( 'customize_register', 'enlightenment_grid_loop_customize_register', 12 );

/**
 * Customizer settings, sections and controls.
 */
function enlightenment_bootstrap_grid_loop_customize_register( $wp_customize ) {
	if ( ! current_theme_supports( 'enlightenment-bootstrap' ) ) {
		return;
	}

	remove_action( 'customize_register', 'enlightenment_grid_loop_customize_register', 12 );

	$type        = 'theme_mod';
	$cap         = 'edit_theme_options';
	$supports    = 'enlightenment-grid-loop';
	$grids       = enlightenment_archive_grids();
	$templates   = enlightenment_grid_loop_templates();
	$columns     = enlightenment_grid_columns();
	$breakpoints = enlightenment_bootstrap_breakpoints();
	$choices     = array();

	$wp_customize->add_panel( 'grid-loop', array(
		'title'          => __( 'Grid', 'enlightenment' ),
		'description'    => sprintf( '<p>%s</p>', __( 'Select a template to customize.' ) ),
		'priority'       => 106,
		'theme_supports' => $supports,
	) );

	foreach ( $grids as $template => $atts ) {
		if ( ! isset( $templates[ $template ] ) ) {
			continue;
		}

		$wp_customize->add_section( sprintf( 'grid-loop-template-%s', $template ), array(
			'panel'          => 'grid-loop',
			'title'          => $templates[ $template ],
			'description'    => sprintf( '<p>%s</p>', sprintf( __( 'You are customizing the grid for the <strong>%s</strong> template.', 'enlightenment' ), $templates[ $template ] ) ),
		) );

		$prev = '';

		foreach ( $breakpoints as $breakpoint => $label ) {
			$choices = array();

			if( '' != $prev ) {
				$choices['inherit'] = array(
					'src' => '',
					'alt' => sprintf( __( 'Inherit from %s', 'enlightenment' ), $prev ),
				);
			}

			$prev = $label;

			foreach( $columns as $grid => $attr ) {
				$choices[ $grid ] = array(
					'svg' => ( 0 === strpos( $attr['image'], '<svg ' ) ? $attr['image'] : '' ),
					'src' => ( 0 === strpos( $attr['image'], '<svg ' ) ? '' : $attr['image'] ),
					'alt' => $attr['name'],
				);
			}

			$wp_customize->add_setting( sprintf( 'grids[%s][grid][%s]', $template, $breakpoint ), array(
				'type'              => $type,
				'capability'        => $cap,
				'theme_supports'    => $supports,
				'default'           => $atts['grid'][ $breakpoint ],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new Enlightenment_Customize_Image_Radio_Control( $wp_customize, sprintf( 'grids[%s][grid][%s]', $template, $breakpoint ), array(
				'section'         => sprintf( 'grid-loop-template-%s', $template ),//'grid-loop',
				'label'           => $label,
				'description'     => '',
				'choices'         => $choices,
			) ) );
		}

		$wp_customize->add_setting( sprintf( 'grids[%s][lead_posts]', $template ), array(
			'type'              => $type,
			'capability'        => $cap,
			'theme_supports'    => $supports,
			'default'           => $atts['lead_posts'],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( sprintf( 'grids[%s][lead_posts]', $template ), array(
			'section'         => sprintf( 'grid-loop-template-%s', $template ),//'grid-loop',
			'type'            => 'number',
			'label'           => __( 'Leading Posts', 'enlightenment' ),
			'description'     => '',
			'input_attrs'     => array(
				'min' => 0,
				'max' => get_option( 'posts_per_page' ),
			),
		) );
	}
}
add_action( 'customize_register', 'enlightenment_bootstrap_grid_loop_customize_register' );

function enlightenment_grid_loop_customize_controls_scripts() {
	wp_enqueue_script( 'enlightenment-customize-controls' );
}
add_action( 'customize_controls_enqueue_scripts', 'enlightenment_grid_loop_customize_controls_scripts' );

function enlightenment_grid_loop_customize_controls_args( $args ) {
	$args['grid_loop'] = array(
		'templates' => enlightenment_archive_grids(),
	);

	return $args;
}
add_filter( 'enlightenment_customize_controls_args', 'enlightenment_grid_loop_customize_controls_args' );
