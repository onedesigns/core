<?php
/**
 * Customizer functions for Custom Layouts.
 *
 * @package Enlightenment_Framework
 */

function enlightenment_layout_templates() {
	$templates = array(
		'error404'  => array(
			'name' => __( '404',      'enlightenment' ),
			'url'  => add_query_arg( 'page_id', -1, home_url( '/' ) ),
		),
		'search'    => array(
			'name' => __( 'Search',   'enlightenment' ),
			'url'  => add_query_arg( 's', '', home_url( '/' ) ),
		),
		'blog'      => array(
			'name' => __( 'Blog',     'enlightenment' ),
			'url'  => 'page' == get_option( 'show_on_front' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' ),
		),
		'post'      => array(
			'name' => __( 'Post',     'enlightenment' ),
			'url'  => '',
		),
		'page'      => array(
			'name' => __( 'Page',     'enlightenment' ),
			'url'  => '',
		),
		'attachment' => array(
			'name' => __( 'Media',    'enlightenment' ),
			'url'  => '',
		),
		'author'    => array(
			'name' => __( 'Author',   'enlightenment' ),
			'url'  => '',
		),
		'date'      => array(
			'name' => __( 'Date',     'enlightenment' ),
			'url'  => '',
		),
		'category'  => array(
			'name' => __( 'Category', 'enlightenment' ),
			'url'  => '',
		),
		'post_tag'  => array(
			'name' => __( 'Post Tag', 'enlightenment' ),
			'url'  => '',
		),
	);

	if ( class_exists( 'WPSEO_Options' ) && true === WPSEO_Options::get( 'disable-attachment' ) ) {
		unset( $templates['attachment'] );
	}

	$posts = get_posts( array(
		'posts_per_page' => 1,
	) );

	if ( count( $posts ) ) {
		$templates['post']['url']   = get_permalink( $posts[0] );
		$templates['author']['url'] = get_author_posts_url( $posts[0]->post_author );
		$templates['date']['url']   = get_year_link( date( 'Y', strtotime( $posts[0]->post_date ) ) );
	}

	$pages = get_posts( array(
		'posts_per_page' => 1,
		'post_type'      => 'page',
	) );

	if ( count( $pages ) ) {
		$templates['page']['url'] = get_permalink( $pages[0] );
	}

	if ( isset( $templates['attachment'] ) ) {
		$media = get_posts( array(
			'posts_per_page' => 1,
			'post_type'      => 'attachment',
			'post_status'    => 'any',
		) );

		if ( count( $media ) ) {
			$templates['attachment']['url'] = get_permalink( $media[0] );
		}
	}

	$cats = get_categories( array(
		'number' => 1,
	) );

	if ( count( $cats ) ) {
		$templates['category']['url'] = get_category_link( $cats[0] );
	}

	$tags = get_tags( array(
		'number' => 1,
	) );

	if ( count( $tags ) ) {
		$templates['post_tag']['url'] = get_tag_link( $tags[0] );
	}

	$post_types = get_post_types( array( 'has_archive' => true ), 'objects' );

	foreach ( $post_types as $name => $post_type ) {
		$templates[ $name . '-archive' ] = array(
			'name' => sprintf( __( '%s Archive', 'enlightenment' ), $post_type->labels->name ),
			'url'  => get_post_type_archive_link( $name ),
		);
	}

	$post_types = get_post_types( array( 'publicly_queryable' => true ), 'objects' );

	unset( $post_types['post'] );
	unset( $post_types['attachment'] );
	unset( $post_types['e-landing-page'] );
	unset( $post_types['e-floating-buttons'] );

	foreach ( $post_types as $name => $post_type ) {
		if ( 'elementor_library' == $name ) {
			$post_type->labels->singular_name = __( 'Elementor Template', 'enlightenment' );
		}

		$url   = '';
		$posts = get_posts( array(
			'posts_per_page' => 1,
			'post_type'      => $name,
		) );

		if ( count( $posts ) ) {
			$url = get_permalink( $posts[0] );
		}

		$templates[ $name ] = array(
			'name' => $post_type->labels->singular_name,
			'url'  => $url,
		);
	}

	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

	unset( $taxonomies['category'] );
	unset( $taxonomies['post_tag'] );
	unset( $taxonomies['post_format'] );
	unset( $taxonomies['product_shipping_class'] );

	foreach( $taxonomies as $name => $taxonomy ) {
		switch ( $name ) {
			case 'product_cat':
				$taxonomy->labels->singular_name = __( 'Product Category', 'enlightenment' );
				break;

			case 'product_tag':
				$taxonomy->labels->singular_name = __( 'Product Tag', 'enlightenment' );
				break;
		}

		$url   = '';
		$terms = get_terms( array(
			'taxonomy' => $name,
			'number'   => 1,
		) );

		if ( count( $terms ) ) {
			$url = get_term_link( array_shift( $terms ) );
		}

		$templates[ $name ] = array(
			'name' => $taxonomy->labels->singular_name,
			'url'  => $url,
		);
	}

	return apply_filters( 'enlightenment_layout_templates', $templates );
}

function enlightenment_current_layout_template() {
	if( is_404() ) {
		$template = 'error404';
	} elseif( is_search() ) {
		$template = 'search';
	} elseif( is_home() ) {
		$template = 'blog';
	} elseif( is_author() ) {
		$template = 'author';
	} elseif( is_date() ) {
		$template = 'date';
	} elseif( is_category() ) {
		$template = 'category';
	} elseif( is_tag() ) {
		$template = 'post_tag';
	} elseif( is_post_type_archive() ) {
		$template = get_query_var( 'post_type' ) . '-archive';
	} elseif( is_tax( 'post_format' ) ) {
		$template = 'blog';
	} elseif( is_tax() ) {
		$template = get_queried_object()->taxonomy;
	} elseif( is_singular() ) {
		if( ! is_singular( array( 'post', 'page', 'attachment' ) ) ) {
			$template = get_post_type();
		} elseif( is_page() ) {
			$template = 'page';
		} else {
			$template = 'post';
		}
	} else {
		$template = 'blog';
	}

	return apply_filters( 'enlightenment_current_layout_template', $template );
}

function enlightenment_is_active_layout_template( $control ) {
	$template = str_replace( 'layouts[', '', str_replace( ']', '', $control->id ) );

	if( enlightenment_current_layout_template() == $template ) {
		return true;
	}

	return false;
}

/**
 * Customizer settings, sections and controls.
 */
function enlightenment_custom_layouts_customize_register( $wp_customize ) {
	$type     = 'theme_mod';
	$cap      = 'edit_theme_options';
	$supports = 'enlightenment-custom-layouts';
	$layouts  = enlightenment_archive_layouts();
	$choices  = array();

	foreach( enlightenment_custom_layouts() as $layout => $atts ) {
		$choices[ $layout ] = array(
			'svg' => ( 0 === strpos( $atts['image'], '<svg ' ) ? $atts['image'] : '' ),
			'src' => ( 0 === strpos( $atts['image'], '<svg ' ) ? '' : $atts['image'] ),
			'alt' => $atts['name'],
		);
	}

	$wp_customize->add_panel( 'custom-layouts', array(
		'title'          => __( 'Layout', 'enlightenment' ),
		'description'    => sprintf( '<p>%s</p>', __( 'Select a template to customize. You can also override the layout for individual posts' ) ),
		'priority'       => 105,
		'theme_supports' => $supports,
	) );

	foreach( enlightenment_layout_templates() as $template => $atts ) {
		$wp_customize->add_section( sprintf( 'custom-layouts-template-%s', $template ), array(
			'panel'          => 'custom-layouts',
			'title'          => __( 'Layout', 'enlightenment' ),
			'description'    => sprintf( '<p>%s</p>', sprintf( __( 'You are customizing the layout for the %1$s%2$s%3$s template.', 'enlightenment' ), '<strong>', $atts['name'], '</strong>' ) ),
		) );

		$wp_customize->add_setting( sprintf( 'layouts[%s]', $template ), array(
			'type'              => $type,
			'capability'        => $cap,
			'theme_supports'    => $supports,
			'default'           => $layouts[ $template ],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( new Enlightenment_Customize_Image_Radio_Control( $wp_customize, sprintf( 'layouts[%s]', $template ), array(
			'section'         => sprintf( 'custom-layouts-template-%s', $template ),//'custom-layouts',
			'label'           => __( 'Select Layout', 'enlightenment' ),
			'description'     => '',//sprintf( __( 'Layout settings for the %s template.', 'enlightenment' ), $atts['name'] ),
			'choices'         => $choices,
			'active_callback' => 'enlightenment_is_active_layout_template',
		) ) );

		/*$wp_customize->add_setting( 'reset_layouts[' . $template . ']', array(
			'type'              => $type,
			'capability'        => $cap,
			'theme_supports'    => $supports,
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( new Enlightenment_Customize_Reset_Control( $wp_customize, 'reset_layouts[' . $template . ']', array(
			'section'         => 'custom-layouts',
			'label'           => __( 'Reset Layout', 'enlightenment' ),
			'description'     => __( 'Reset the layout to its default setting.', 'enlightenment' ),
			'value'           => __( 'Reset Default', 'enlightenment' ),
			'active_callback' => 'enlightenment_is_active_layout_template',
		) ) );*/
	}
}
add_action( 'customize_register', 'enlightenment_custom_layouts_customize_register', 12 );

function enlightenment_bootstrap_is_active_layout_template( $control ) {
	$prefixes   = enlightenment_bootstrap_breakpoint_prefixes();
	$control_id = $control->id;

	foreach( $prefixes as $breakpoint => $prefix ) {
		$control_id = str_replace( sprintf( '[%s]', $breakpoint ), '', $control_id );
	}

	$template = str_replace( array( 'layouts[', ']' ), '', $control_id );

	if( enlightenment_current_layout_template() == $template ) {
		return true;
	}

	return false;
}

/**
 * Customizer settings, sections and controls.
 */
function enlightenment_bootstrap_custom_layouts_customize_register( $wp_customize ) {
	if( ! current_theme_supports( 'enlightenment-bootstrap' ) ) {
		return;
	}

	remove_action( 'customize_register', 'enlightenment_custom_layouts_customize_register', 12 );

	$type        = 'theme_mod';
	$cap         = 'edit_theme_options';
	$supports    = 'enlightenment-custom-layouts';
	$layouts     = enlightenment_archive_layouts();
	$breakpoints = enlightenment_bootstrap_breakpoints();

	$wp_customize->add_panel( 'custom-layouts', array(
		'title'          => __( 'Layout', 'enlightenment' ),
		'description'    => sprintf( '<p>%s</p>', __( 'Select a template to customize. You can also override the layout for individual posts' ) ),
		'priority'       => 105,
		'theme_supports' => $supports,
	) );

	foreach( enlightenment_layout_templates() as $template => $atts ) {
		$wp_customize->add_section( sprintf( 'custom-layouts-template-%s', $template ), array(
			'panel'          => 'custom-layouts',
			'title'          => $atts['name'],
			'description'    => sprintf( '<p>%s</p>', sprintf( __( 'You are customizing the layout for the %1$s%2$s%3$s template.', 'enlightenment' ), '<strong>', $atts['name'], '</strong>' ) ),
		) );

		$prev = '';

		foreach( $breakpoints as $breakpoint => $label ) {
			$choices = array();

			if( '' != $prev ) {
				$choices['inherit'] = array(
					'src' => '',
					'alt' => sprintf( __( 'Inherit from %s', 'enlightenment' ), $prev ),
				);
			}

			$prev = $label;

			foreach( enlightenment_custom_layouts() as $layout => $atts ) {
				$choices[ $layout ] = array(
					'svg' => ( 0 === strpos( $atts['image'], '<svg ' ) ? $atts['image'] : '' ),
					'src' => ( 0 === strpos( $atts['image'], '<svg ' ) ? '' : $atts['image'] ),
					'alt' => $atts['name'],
				);
			}

			$wp_customize->add_setting( sprintf( 'layouts[%s][%s]', $template, $breakpoint ), array(
				'type'              => $type,
				'capability'        => $cap,
				'theme_supports'    => $supports,
				'default'           => $layouts[ $template ][ $breakpoint ],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control( new Enlightenment_Customize_Image_Radio_Control( $wp_customize, sprintf( 'layouts[%s][%s]', $template, $breakpoint ), array(
				'section'         => sprintf( 'custom-layouts-template-%s', $template ),//'custom-layouts',
				'label'           => $label,
				'description'     => '',//sprintf( __( 'Layout settings for the %s template.', 'enlightenment' ), $atts['name'] ),
				'choices'         => $choices,
				// 'active_callback' => 'enlightenment_bootstrap_is_active_layout_template',
			) ) );
		}

		/*$wp_customize->add_setting( 'reset_layouts[' . $template . ']', array(
			'type'              => $type,
			'capability'        => $cap,
			'theme_supports'    => $supports,
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( new Enlightenment_Customize_Reset_Control( $wp_customize, 'reset_layouts[' . $template . ']', array(
			'section'         => 'custom-layouts',
			'label'           => __( 'Reset Layout', 'enlightenment' ),
			'description'     => __( 'Reset the layout to its default setting.', 'enlightenment' ),
			'value'           => __( 'Reset Default', 'enlightenment' ),
			'active_callback' => 'enlightenment_is_active_layout_template',
		) ) );*/
	}
}
add_action( 'customize_register', 'enlightenment_bootstrap_custom_layouts_customize_register' );

function enlightenment_custom_layouts_customize_controls_scripts() {
	wp_enqueue_script( 'enlightenment-customize-controls' );
}
add_action( 'customize_controls_enqueue_scripts', 'enlightenment_custom_layouts_customize_controls_scripts' );

function enlightenment_custom_layouts_customize_controls_args( $args ) {
	$args['custom_layouts'] = array(
		'templates' => enlightenment_layout_templates(),
	);

	return $args;
}
add_filter( 'enlightenment_customize_controls_args', 'enlightenment_custom_layouts_customize_controls_args' );
