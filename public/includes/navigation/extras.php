<?php

function enlightenment_nav_menu_args( $args ) {
	if( '' != $args['theme_location'] ) {
		$args['container_class'] = sprintf( 'menu-%s-container', $args['theme_location'] );
		$args['walker']          = new Enlightenment_Walker_Nav_Menu;
	}

	return $args;
}
add_filter( 'enlightenment_wp_nav_menu_args', 'enlightenment_nav_menu_args', 20 );

function enlightenment_filter_archive_title( $output ) {
	if( is_home() ) {
		$output = '';

		if ( ! is_front_page() ) {
			$output = get_the_title( get_option( 'page_for_posts' ) );
		}

		$output = apply_filters( 'enlightenment_blog_title', $output );
	} elseif( is_search() ) {
		$output = sprintf( __( 'Search Results for: %s', 'enlightenment' ), sprintf( '<span>%s</span>', get_search_query() ) );
	} elseif( is_404() ) {
		$output = __( 'Content not found', 'enlightenment' );
	}

	return $output;
}
add_filter( 'get_the_archive_title', 'enlightenment_filter_archive_title' );

function enlightenment_blog_description( $output ) {
	if( is_home() ) {
		$output = '';

		if ( ! is_front_page() ) {
			$output = get_the_content( null, false, get_option( 'page_for_posts' ) );
		}

		$output = apply_filters( 'enlightenment_blog_description', $output );
	}

	return $output;
}
add_filter( 'get_the_archive_description', 'enlightenment_blog_description' );

function enlightenment_yoast_breadcrumbs( $output, $args ) {
    if ( ! function_exists( 'yoast_breadcrumb' ) ) {
        return $output;
    }

	$before  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$before .= $args['before'];

	$after  = $args['after'];
	$after .= enlightenment_close_tag( $args['container'] );

	$output = yoast_breadcrumb( $before, $after, false );
	$output = apply_filters( 'enlightenment_yoast_breadcrumbs', $output, $args );

	return $output;
}
add_filter( 'enlightenment_breadcrumbs', 'enlightenment_yoast_breadcrumbs', 12, 2 );
