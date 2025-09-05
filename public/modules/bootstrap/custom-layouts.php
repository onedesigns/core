<?php

/*function enlightenment_bootstrap_custom_layouts( $layouts ) {
	$layouts['content-sidebar']['content_class'] = 'col-lg-8';
	$layouts['content-sidebar']['sidebar_class'] = 'col-lg-4';

	$layouts['sidebar-content']['content_class'] = 'col-lg-8 push-lg-4';
	$layouts['sidebar-content']['sidebar_class'] = 'col-lg-4 pull-lg-8';

	$layouts['full-width']['content_class']      = '';
	$layouts['full-width']['sidebar_class']      = '';

	return $layouts;
}
add_filter( 'enlightenment_custom_layouts', 'enlightenment_bootstrap_custom_layouts' );*/

function enlightenment_bootstrap_custom_layouts( $layouts ) {
	$layouts['content-sidebar']['body_class']    = 'layout%s-content-sidebar';
	$layouts['content-sidebar']['content_class'] = 'col%s-8';
	$layouts['content-sidebar']['sidebar_class'] = 'col%1$s-4 order%1$s-0 d%1$s-block';

	$layouts['sidebar-content']['body_class']    = 'layout%s-sidebar-content';
	$layouts['sidebar-content']['content_class'] = 'col%1$s-8';
	$layouts['sidebar-content']['sidebar_class'] = 'col%1$s-4 order%1$s-first d%1$s-block';

	$layouts['full-width']['body_class']         = 'layout%s-full-width';
	$layouts['full-width']['content_class']      = 'col%s-12';
	$layouts['full-width']['sidebar_class']      = 'd%s-none';

	return $layouts;
}
add_filter( 'enlightenment_custom_layouts', 'enlightenment_bootstrap_custom_layouts' );

function enlightenment_bootstrap_default_layout( $layout ) {
	return array(
		'smartphone-portrait'  => 'full-width',
		'smartphone-landscape' => 'inherit',
		'tablet-portrait'      => 'inherit',
		'tablet-landscape'     => 'content-sidebar',
		'desktop-laptop'       => 'inherit',
	);
}
add_filter( 'enlightenment_default_layout', 'enlightenment_bootstrap_default_layout' );

function enlightenment_bootstrap_archive_layouts( $layouts ) {
	$layouts['page'] = array(
		'smartphone-portrait'  => 'full-width',
		'smartphone-landscape' => 'inherit',
		'tablet-portrait'      => 'inherit',
		'tablet-landscape'     => 'inherit',
		'desktop-laptop'       => 'inherit',
	);

	return $layouts;
}
add_filter( 'enlightenment_archive_layouts', 'enlightenment_bootstrap_archive_layouts' );

function enlightenment_elementor_bootstrap_archive_layouts( $layouts ) {
	$layouts['elementor_library'] = array(
		'smartphone-portrait'  => 'full-width',
		'smartphone-landscape' => 'inherit',
		'tablet-portrait'      => 'inherit',
		'tablet-landscape'     => 'inherit',
		'desktop-laptop'       => 'inherit',
	);

	return $layouts;
}
add_filter( 'enlightenment_archive_layouts', 'enlightenment_elementor_bootstrap_archive_layouts', 12 );

remove_filter( 'enlightenment_archive_layouts', 'enlightenment_archive_layouts_merge_theme_options', 14 );

function enlightenment_bootstrap_archive_layouts_merge_theme_options( $layouts ) {
	$options = get_theme_mod( 'layouts' );

	if ( ! is_array( $options ) ) {
		$options = array();
	}

	foreach ( $options as $template => $layout ) {
		if ( ! isset( $layouts[ $template ] ) ) {
			$layouts[ $template ] = $layout;
		}

		$layouts[ $template ] = array_merge( $layouts[ $template ], $layout );
	}

	return $layouts;
}
add_filter( 'enlightenment_archive_layouts', 'enlightenment_bootstrap_archive_layouts_merge_theme_options', 14 );

remove_filter( 'admin_body_class', 'enlightenment_set_block_editor_layout_body_class' );
remove_filter( 'is_active_sidebar', 'enlightenment_remove_sidebar_in_full_width', 8, 2 );
remove_filter( 'body_class', 'enlightenment_set_layout_body_class' );
remove_filter( 'enlightenment_page_content_class_args', 'enlightenment_set_layout_content_class' );
remove_filter( 'enlightenment_sidebar_class_args', 'enlightenment_set_layout_sidebar_class' );

function enlightenment_bootstrap_set_block_editor_layout_body_class( $class ) {
    global $current_screen, $post;

	if (
		! isset( $current_screen )
		||
		! $current_screen->is_block_editor()
		||
		! $post instanceof WP_Post
	) {
		return $class;
	}

    $layouts = enlightenment_current_layout();

	$classes = array();

	foreach ( $layouts as $breakpoint => $layout ) {
		$atts = enlightenment_get_layout( $layout );

		if ( ! empty( $atts['body_class'] ) ) {
			$prefix    = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );
			$classes[] = sprintf( 'enlightenment-%s', sprintf( $atts['body_class'], $prefix ) );
		}
	}

    if ( ! empty( $classes ) ) {
		$class .= sprintf( ' %s ', join( ' ', $classes ) );
	}

    return $class;
}
add_filter( 'admin_body_class', 'enlightenment_bootstrap_set_block_editor_layout_body_class' );

function enlightenment_bootstrap_remove_sidebar_in_full_width( $is_active_sidebar, $index ) {
	if ( 'sidebar-1' != $index ) {
		return $is_active_sidebar;
	}

	$layouts = enlightenment_current_layout();
	$remove  = true;

	foreach ( $layouts as $breakpoint => $layout ) {
		if ( 'full-width' != $layout ) {
			$remove = false;
		}
	}

	if ( $remove ) {
		return false;
	}

	return $is_active_sidebar;
}
add_filter( 'is_active_sidebar', 'enlightenment_bootstrap_remove_sidebar_in_full_width', 8, 2 );

function enlightenment_bootstrap_set_layout_body_class( $classes ) {
	$layouts = enlightenment_current_layout();

	if ( ! is_array( $layouts ) ) {
		return $classes;
	}

	foreach ( $layouts as $breakpoint => $layout ) {
		$atts = enlightenment_get_layout( $layout );

		if ( ! empty( $atts['body_class'] ) ) {
			$prefix    = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );
			$classes[] = sprintf( $atts['body_class'], $prefix );
		}
	}

	return $classes;
}
add_filter( 'body_class', 'enlightenment_bootstrap_set_layout_body_class' );

/*function enlightenment_bootstrap_open_page_content_container() {
	$layout = enlightenment_get_layout( enlightenment_current_layout() );

	if( '' != $layout['content_class'] ) {
		echo enlightenment_open_tag( 'div', $layout['content_class'] );
	}
}
add_action( 'enlightenment_before_site_content', 'enlightenment_bootstrap_open_page_content_container', 999 );*/

function enlightenment_bootstrap_open_page_content_container() {
	$layouts = enlightenment_current_layout();
	$class   = '';

	foreach ( $layouts as $breakpoint => $layout ) {
		if ( 'inherit' == $layout ) {
			continue;
		}

		$atts = enlightenment_get_layout( $layout );

		if( '' != $atts['content_class'] ) {
			$prefix = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );
			$class .= ' ' . sprintf( $atts['content_class'], $prefix );
		}
	}

	$class = trim( $class );

	if ( '' != $class ) {
		echo enlightenment_open_tag( 'div', $class );
	}
}
add_action( 'enlightenment_before_site_content', 'enlightenment_bootstrap_open_page_content_container', 999 );

/*function enlightenment_bootstrap_close_page_content_container() {
	$layout = enlightenment_get_layout( enlightenment_current_layout() );

	if( '' != $layout['content_class'] ) {
		echo enlightenment_close_tag( 'div' );
	}
}
add_action( 'enlightenment_after_site_content', 'enlightenment_bootstrap_close_page_content_container', 1 );*/

function enlightenment_bootstrap_close_page_content_container() {
	$layouts = enlightenment_current_layout();
	$close   = false;

	foreach( $layouts as $breakpoint => $layout ) {
		if( 'inherit' == $layout ) {
			continue;
		}

		$atts = enlightenment_get_layout( $layout );

		if( '' != $atts['content_class'] ) {
			$close = true;
			break;
		}
	}

	if( $close ) {
		echo enlightenment_close_tag( 'div' );
	}
}
add_action( 'enlightenment_after_site_content', 'enlightenment_bootstrap_close_page_content_container', 1 );

/*function enlightenment_bootstrap_open_sidebar_container() {
	if( 'primary' != enlightenment_current_sidebar_name() ) {
		return;
	}

	$layout = enlightenment_get_layout( enlightenment_current_layout() );

	echo enlightenment_open_tag( 'div', $layout['sidebar_class'] );
}
add_action( 'enlightenment_before_sidebar', 'enlightenment_bootstrap_open_sidebar_container', 999 );*/

function enlightenment_bootstrap_open_sidebar_container() {
	if( 'primary' != enlightenment_current_sidebar_name() ) {
		return;
	}

	$layouts = enlightenment_current_layout();
	$class   = '';

	foreach( $layouts as $breakpoint => $layout ) {
		if( 'inherit' == $layout ) {
			continue;
		}

		$atts = enlightenment_get_layout( $layout );

		if( '' != $atts['sidebar_class'] ) {
			$prefix = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );
			$class .= ' ' . sprintf( $atts['sidebar_class'], $prefix );
		}
	}

	$class = trim( $class );

	if( '' != $class ) {
		echo enlightenment_open_tag( 'div', $class );
	}
}
add_action( 'enlightenment_before_sidebar', 'enlightenment_bootstrap_open_sidebar_container', 999 );

function enlightenment_bootstrap_close_sidebar_container() {
	if( 'primary' != enlightenment_current_sidebar_name() ) {
		return;
	}

	$layouts = enlightenment_current_layout();
	$close   = false;

	foreach( $layouts as $breakpoint => $layout ) {
		if( 'inherit' == $layout ) {
			continue;
		}

		$atts = enlightenment_get_layout( $layout );

		if( '' != $atts['sidebar_class'] ) {
			$close = true;
			break;
		}
	}

	if( $close ) {
		enlightenment_close_div();
	}
}
add_action( 'enlightenment_after_sidebar', 'enlightenment_bootstrap_close_sidebar_container', 1 );
