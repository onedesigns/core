<?php

function enlightenment_archive_layouts_merge_theme_options( $layouts ) {
	$options = get_theme_mod( 'layouts' );

	if ( ! is_array( $options ) ) {
		$options = array();
	}

	return array_merge( $layouts, $options );
}
add_filter( 'enlightenment_archive_layouts', 'enlightenment_archive_layouts_merge_theme_options', 14 );

function enlightenment_elementor_filter_archive_layouts( $layouts ) {
	$layouts['elementor_library'] = 'full-width';

	return $layouts;
}
add_filter( 'enlightenment_archive_layouts', 'enlightenment_elementor_filter_archive_layouts' );

function enlightenment_elementor_ajax_current_layout( $layout ) {
	if ( ! wp_doing_ajax() ) {
		return $layout;
	}

	if ( ! isset( $_REQUEST['action'] ) || 'elementor_ajax' != $_REQUEST['action'] ) {
		return $layout;
	}

	if ( ! isset( $_REQUEST['editor_post_id'] ) ) {
		return $layout;
	}

	$post_id = absint( $_REQUEST['editor_post_id'] );

	if ( ! $post_id ) {
		return $layout;
	}

	$post = get_post( $post_id );

	if ( ! $post instanceof WP_Post ) {
		return $layout;
	}

	$layouts = enlightenment_archive_layouts();
	$meta    = get_post_meta( $post_id, '_enlightenment_custom_layout', true );

	if ( ! empty( $meta ) ) {
		$layout = $meta;
	} elseif ( ! in_array( $post->post_type, array( 'post', 'page', 'attachment' ) ) ) {
		$layout = $layouts[ $post->post_type ];
	} elseif ( in_array( $post->post_type, array( 'post', 'attachment' ) ) ) {
		$layout = $layouts['post'];
	} elseif ( 'page' == $post->post_type ) {
		$layout = $layouts['page'];
	}

	return $layout;
}
add_filter( 'enlightenment_current_layout', 'enlightenment_elementor_ajax_current_layout' );

function enlightenment_remove_sidebar_in_full_width( $is_active_sidebar, $index ) {
	if ( 'full-width' == enlightenment_current_layout() && 'sidebar-1' == $index ) {
		return false;
	}

	return $is_active_sidebar;
}
add_filter( 'is_active_sidebar', 'enlightenment_remove_sidebar_in_full_width', 8, 2 );

function enlightenment_set_layout_body_class( $classes ) {
	$layout = enlightenment_get_layout( enlightenment_current_layout() );

	if ( ! empty( $layout['body_class'] ) ) {
		$classes[] = $layout['body_class'];
	}

	return $classes;
}
add_filter( 'body_class', 'enlightenment_set_layout_body_class' );

function enlightenment_set_layout_content_class( $args ) {
	$layout = enlightenment_get_layout( enlightenment_current_layout() );

	if ( ! empty( $layout['content_class'] ) ) {
		$args['class'] .= ' ' . $layout['content_class'];
	}

	return $args;
}
add_filter( 'enlightenment_page_content_class_args', 'enlightenment_set_layout_content_class' );

function enlightenment_set_layout_sidebar_class( $args ) {
	$layout = enlightenment_get_layout( enlightenment_current_layout() );

	if ( ! empty( $layout['sidebar_class'] ) && 'primary' == enlightenment_current_sidebar_name() ) {
		$args['class'] .= ' ' . $layout['sidebar_class'];
	}

	return $args;
}
add_filter( 'enlightenment_sidebar_class_args', 'enlightenment_set_layout_sidebar_class' );
