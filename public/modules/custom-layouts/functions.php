<?php

function enlightenment_available_layouts() {
	$layouts = array(
		'content-sidebar' => array(
			'name'          => __( 'Content / Sidebar', 'enlightenment' ),
			'image'         => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="currentColor" d="M0 0v30.2h100V0H0zm0 36.457v63.541h63.541V36.457H0zm69.791 0v63.541h30.2V36.457h-30.2z"/></svg>',
			'body_class'    => 'layout-content-sidebar',
			'content_class' => '',
			'sidebar_class' => '',
			'extra_atts'    => '',
		),
		'sidebar-content' => array(
			'name'          => __( 'Sidebar / Content', 'enlightenment' ),
			'image'         => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="currentColor" d="M0 0v30.2h100V0H0zm0 36.457v63.541h30.2V36.457H0zm36.457 0v63.541h63.541V36.457H36.457z"/></svg>',
			'body_class'    => 'layout-sidebar-content',
			'content_class' => '',
			'sidebar_class' => '',
			'extra_atts'    => '',
		),
		'full-width' => array(
			'name'          => __( 'Full Width', 'enlightenment' ),
			'image'         => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="currentColor" d="M0 0v30.2h100V0H0zm0 36.457v63.541h100V36.457H0z"/></svg>',
			'body_class'    => 'layout-full-width',
			'content_class' => '',
			'sidebar_class' => '',
			'extra_atts'    => '',
		),
	);

	global $enlightenment_registered_layouts;

	$enlightenment_registered_layouts = array();

	do_action( 'enlightenment_register_layouts' );

	$layouts = array_merge( $layouts, $enlightenment_registered_layouts );

	return apply_filters( 'enlightenment_available_layouts', $layouts );
}

function enlightenment_register_layout( $handle, $name, $image, $body_class = '', $content_class = '', $sidebar_class = '', $extra_atts = '' ) {
	global $enlightenment_registered_layouts;

	$enlightenment_registered_layouts[ $handle ] = array(
		'name'          => $name,
		'image'         => $image,
		'body_class'    => $body_class,
		'content_class' => $content_class,
		'sidebar_class' => $sidebar_class,
		'extra_atts'    => $extra_atts,
	);
}

function enlightenment_custom_layouts() {
	$theme_support = get_theme_support( 'enlightenment-custom-layouts' );

	$supported_layouts = array_shift( $theme_support );
	$available_layouts = enlightenment_available_layouts();

	$layouts = array();

	foreach ( $available_layouts as $layout => $atts ) {
		if( in_array( $layout, $supported_layouts ) ) {
			$layouts[ $layout ] = $atts;
		}
	}

	return apply_filters( 'enlightenment_custom_layouts', $layouts );
}

function enlightenment_archive_layouts() {
	$default_layout = enlightenment_default_layout();

	$layouts = array(
		'error404'   => $default_layout,
		'search'     => $default_layout,
		'blog'       => $default_layout,
		'post'       => $default_layout,
		'page'       => 'full-width',
		'attachment' => $default_layout,
		'author'     => $default_layout,
		'date'       => $default_layout,
		'category'   => $default_layout,
		'post_tag'   => $default_layout,
	);

	if ( class_exists( 'WPSEO_Options' ) && true === WPSEO_Options::get( 'disable-attachment' ) ) {
		unset( $layouts['attachment'] );
	}

	$post_types = get_post_types( array( 'has_archive' => true ) );

	foreach ( $post_types as $post_type ) {
		$layouts[ $post_type . '-archive' ] = enlightenment_default_layout();
	}

	$post_types = get_post_types( array( 'publicly_queryable' => true ) );

	unset( $post_types['post'] );
	unset( $post_types['attachment'] );
	unset( $post_types['e-landing-page'] );
	unset( $post_types['e-floating-buttons'] );

	foreach ( $post_types as $post_type ) {
		$layouts[ $post_type ] = enlightenment_default_layout();
	}

	$taxonomies = get_taxonomies( array( 'public' => true ) );

	unset( $taxonomies['category'] );
	unset( $taxonomies['post_tag'] );
	unset( $taxonomies['post_format'] );
	unset( $taxonomies['product_shipping_class'] );

	foreach ( $taxonomies as $taxonomy ) {
		$layouts[ $taxonomy ] = enlightenment_default_layout();
	}

	return apply_filters( 'enlightenment_archive_layouts', $layouts );
}

function enlightenment_default_layout() {
	return apply_filters( 'enlightenment_default_layout', 'content-sidebar' );
}

function enlightenment_current_layout() {
	if ( is_admin() ) {
		global $current_screen, $post;

		if (
			! isset( $current_screen )
			||
			! $current_screen->is_block_editor()
			||
			! $post instanceof WP_Post
		) {
	        return apply_filters( 'enlightenment_current_layout', enlightenment_default_layout() );
	    }

		$layouts = enlightenment_archive_layouts();
		$meta    = get_post_meta( $post->ID, '_enlightenment_custom_layout', true );

		if ( ! empty( $meta ) ) {
	        $layout = $meta;
	    } elseif ( ! in_array( $post->post_type, array( 'post', 'page', 'attachment' ) ) ) {
	        $layout = $layouts[ $post->post_type ];
	    } elseif ( in_array( $post->post_type, array( 'post', 'attachment' ) ) ) {
	        $layout = $layouts['post'];
	    } elseif ( 'page' == $post->post_type ) {
	        $layout = $layouts['page'];
	    }
	} else {
		$layouts = enlightenment_archive_layouts();

		if ( is_404() ) {
			$layout = $layouts['error404'];
		} elseif ( is_search() ) {
			$layout = $layouts['search'];
		} elseif ( is_home() ) {
			$layout = $layouts['blog'];
		} elseif ( is_author() ) {
			$layout = $layouts['author'];
		} elseif ( is_date() ) {
			$layout = $layouts['date'];
		} elseif ( is_category() ) {
			$layout = $layouts['category'];
		} elseif ( is_tag() ) {
			$layout = $layouts['post_tag'];
		} elseif ( is_post_type_archive() ) {
			$layout = $layouts[ get_query_var( 'post_type' ) . '-archive' ];
		} elseif ( is_tax( 'post_format' ) ) {
			$layout = $layouts['blog'];
		} elseif ( is_tax() ) {
			$layout = $layouts[ get_queried_object()->taxonomy ];
		} elseif ( is_singular() ) {
			$meta = get_post_meta( get_the_ID(), '_enlightenment_custom_layout', true );

			if ( ! empty( $meta ) ) {
				$layout = $meta;
			} elseif ( ! is_singular( array( 'post', 'page', 'attachment', 'buddypress', 'wpforms' ) ) ) {
				$layout = $layouts[ get_post_type() ];
			} elseif ( is_page() ) {
				$layout = $layouts['page'];
			} else {
				$layout = $layouts['post'];
			}
		} else {
			$layout = $layouts['blog'];
		}
	}

	return apply_filters( 'enlightenment_current_layout', $layout );
}

function enlightenment_get_layout( $layout ) {
	$layouts = enlightenment_custom_layouts();

	if ( isset( $layouts[ $layout ] ) ) {
		return $layouts[ $layout ];
	}

	return false;
}

function enlightenment_delete_custom_layout_post_meta_when_empty( $check, $post_id, $meta_key, $meta_value ) {
	if ( '_enlightenment_custom_layout' != $meta_key ) {
		return $check;
	}

	if ( ! empty( $meta_value ) ) {
		return $check;
	}

	delete_post_meta( $post_id, $meta_key );

	return true;
}
add_filter( 'update_post_metadata', 'enlightenment_delete_custom_layout_post_meta_when_empty', 10, 4 );
