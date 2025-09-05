<?php

function enlightenment_sidebar_locations() {
	$templates = enlightenment_unlimited_sidebars_templates();
	$locations = apply_filters( 'enlightenment_sidebar_locations', array(
		'primary' => array(
			'name'      => __( 'Primary Sidebar', 'enlightenment' ),
			'contained' => true,
			'sidebar'   => 'sidebar-1',
		),
	) );

	$options = array();

	foreach ( $templates as $template => $name ) {
		$options[ $template ] = $locations;
	}

	return apply_filters( 'enlightenment_sidebar_locations_options', $options );
}

function enlightenment_unlimited_sidebars_templates() {
	$templates = array(
		'error404'   => __( '404',      'enlightenment' ),
		'search'     => __( 'Search',   'enlightenment' ),
		'blog'       => __( 'Blog',     'enlightenment' ),
		'post'       => __( 'Post',     'enlightenment' ),
		'page'       => __( 'Page',     'enlightenment' ),
		'attachment' => __( 'Media',    'enlightenment' ),
		'author'     => __( 'Author',   'enlightenment' ),
		'date'       => __( 'Date',     'enlightenment' ),
		'category'   => __( 'Category', 'enlightenment' ),
		'post_tag'   => __( 'Post Tag', 'enlightenment' ),
	);

	if ( class_exists( 'WPSEO_Options' ) && true === WPSEO_Options::get( 'disable-attachment' ) ) {
		unset( $templates['attachment'] );
	}

	$post_types = get_post_types( array( 'has_archive' => true ), 'objects' );

	foreach ( $post_types as $name => $post_type ) {
		$templates[ $name . '-archive' ] = sprintf( __( '%s Archive', 'enlightenment' ), $post_type->labels->name );
	}

	$post_types = get_post_types( array( 'publicly_queryable' => true ), 'objects' );

	unset( $post_types['post'] );
	unset( $post_types['attachment'] );
	unset( $post_types['e-landing-page'] );
	unset( $post_types['e-floating-buttons'] );

	foreach ( $post_types as $name => $post_type ) {
		$templates[ $name ] = $post_type->labels->singular_name;
	}

	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

	unset( $taxonomies['category'] );
	unset( $taxonomies['post_tag'] );
	unset( $taxonomies['post_format'] );
	unset( $taxonomies['product_shipping_class'] );

	foreach ( $taxonomies as $name => $taxonomy ) {
		$templates[ $name ] = $taxonomy->labels->singular_name;
	}

	return apply_filters( 'enlightenment_unlimited_sidebars_templates', $templates );
}

function enlightenment_registered_sidebars_default_atts() {
	$defaults = array(
		'description'               => '',
		'display_title'             => false,
		'display_description'       => false,
		'background'                => false,
		'widgets_background_color'  => false,
		'sidebar_title_color'       => false,
		'sidebar_text_color'        => false,
		'widgets_title_color'       => false,
		'widgets_text_color'        => false,
		'widgets_link_color'        => false,
		'widgets_link_hover_color'  => false,
		'widgets_link_active_color' => false,
	);

	if ( current_theme_supports( 'enlightenment-grid-loop' ) ) {
		$defaults['grid'] = enlightenment_default_grid();
	}

	if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
		$defaults['contain_widgets'] = true;
	}

	return apply_filters( 'enlightenment_registered_sidebars_default_atts', $defaults );
}

function enlightenment_registered_sidebars() {
	global $wp_registered_sidebars;

	$defaults = enlightenment_registered_sidebars_default_atts();
	$sidebars = array();

	foreach ( $wp_registered_sidebars as $sidebar => $atts ) {
		$sidebars[ $sidebar ] = array(
			'name'                => $atts['name'],
			'id'                  => $atts['id'],
			'description'         => $atts['description'],
			'display_title'       => false,
			'display_description' => false,
		);

		$sidebars[ $sidebar ] = array_merge( $sidebars[ $sidebar ], $defaults );
	}

	return apply_filters( 'enlightenment_registered_sidebars', $sidebars );
}

function enlightenment_current_sidebars_template() {
	$template = '';

	if ( is_404() ) {
		$template = 'error404';
	} elseif ( is_search() ) {
		$template = 'search';
	} elseif ( is_home() ) {
		$template = 'blog';
	} elseif ( is_page() ) {
		$template = 'page';
	} elseif ( is_author() ) {
		$template = 'author';
	} elseif ( is_date() ) {
		$template = 'date';
	} elseif ( is_category() ) {
		$template = 'category';
	} elseif ( is_tag() ) {
		$template = 'post_tag';
	} elseif ( is_post_type_archive() ) {
		$template = get_queried_object()->name . '-archive';
	} elseif ( is_singular() ) {
		$template = get_post_type();
	} elseif ( is_tax( 'post_format' ) ) {
		$template = 'blog';
	} elseif ( is_tax() ) {
		$template = get_queried_object()->taxonomy;
	}

	return apply_filters( 'enlightenment_current_sidebars_template', $template );
}

function enlightenment_get_dynamic_sidebar_id() {
	if ( is_admin() ) {
		return;
	}

	$locations = enlightenment_sidebar_locations();
	$template  = enlightenment_current_sidebars_template();
	$sidebar   = enlightenment_current_sidebar_name();

	$dynamic_sidebar = '';

	if ( isset( $locations[ $template ][ $sidebar ] ) ) {
		$dynamic_sidebar = $locations[ $template ][ $sidebar ]['sidebar'];
	}

	return apply_filters( 'enlightenment_get_dynamic_sidebar_id', $dynamic_sidebar );
}
