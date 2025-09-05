<?php

add_filter( 'current_theme_supports-enlightenment-bootstrap', 'enlightenment_filter_current_theme_supports', 10, 3 );

function enlightenment_bootstrap_html_atts_args( $args ) {
	if ( 'dark' == enlightenment_bootstrap_get_current_color_mode() ) {
		$args['atts'] .= ' data-bs-theme="dark"';
	}

	return $args;
}
add_filter( 'enlightenment_html_atts_args', 'enlightenment_bootstrap_html_atts_args' );

function enlightenment_bootstrap_theme_stylesheet_deps( $deps ) {
	if ( current_theme_supports( 'enlightenment-bootstrap', 'load_styles' ) ) {
		$deps[] = 'bootstrap';
	}

	return $deps;
}
add_filter( 'enlightenment_theme_stylesheet_deps', 'enlightenment_bootstrap_theme_stylesheet_deps' );

function enlightenment_call_bootstrap_script( $deps ) {
	if ( current_theme_supports( 'enlightenment-bootstrap', 'load_scripts' ) ) {
		$deps[] = 'bootstrap';
	}

	return $deps;
}
add_filter( 'enlightenment_call_js', 'enlightenment_call_bootstrap_script' );

function enlightenment_bootstrap_call_js_args( $args ) {
	$args['color_modes'] = enlightenment_bootstrap_get_color_modes();

	return $args;
}
add_filter( 'enlightenment_call_js_args', 'enlightenment_bootstrap_call_js_args' );

function enlightenment_bootstrap_body_class( $classes ) {
	$expand     = current_theme_supports( 'enlightenment-bootstrap', 'navbar-expand' );
	$position   = current_theme_supports( 'enlightenment-bootstrap', 'navbar-position' );

	$classes[] = sprintf( 'has-navbar-%s', $position );

	if ( '' === $expand ) {
		$classes[] = 'has-navbar-expand';
	} elseif ( in_array( $expand, array( 'sm', 'md', 'lg', 'xl' ) ) ) {
		$classes[] = sprintf( 'has-navbar-expand-%s', esc_attr( $expand ) );
	}

	return $classes;
}
add_filter( 'body_class', 'enlightenment_bootstrap_body_class' );

function enlightenment_bootstrap_site_header_class_args( $args ) {
	$expand     = current_theme_supports( 'enlightenment-bootstrap', 'navbar-expand' );
	$position   = current_theme_supports( 'enlightenment-bootstrap', 'navbar-position' );
	$background = current_theme_supports( 'enlightenment-bootstrap', 'navbar-background' );

	$args['class'] .= ' navbar';

	if ( '' === $expand ) {
		$args['class'] .= ' navbar-expand';
	} elseif ( in_array( $expand, array( 'sm', 'md', 'lg', 'xl' ) ) ) {
		$args['class'] .= sprintf( ' navbar-expand-%s', esc_attr( $expand ) );
	}

	if ( in_array( $position, array( 'static-top', 'fixed-top', 'fixed-bottom', 'sticky-top' ) ) ) {
		$args['class'] .= sprintf( ' %s', esc_attr( $position ) );
	}

	if ( in_array( $background, array(
		'primary', 'secondary', 'success', 'info', 'warning', 'danger', 'light',
		'dark', 'black', 'white', 'body', 'transparent', 'body-secondary',
		'body-tertiary', 'primary-subtle', 'secondary-subtle', 'success-subtle',
		'info-subtle', 'warning-subtle', 'danger-subtle', 'light-subtle',
		'dark-subtle', 'gradient',
	) ) ) {
		$args['class'] .= sprintf( ' bg-%s', esc_attr( $background ) );
	}

	return $args;
}
add_filter( 'enlightenment_site_header_class_args', 'enlightenment_bootstrap_site_header_class_args' );

function enlightenment_bootstrap_site_header_extra_atts_args( $args ) {
	$color = current_theme_supports( 'enlightenment-bootstrap', 'navbar-color' );

	if ( in_array( $color, array( 'light', 'dark' ) ) ) {
		$args['atts']['data-bs-theme'] = $color;
	}

	return $args;
}
add_filter( 'enlightenment_site_header_extra_atts_args', 'enlightenment_bootstrap_site_header_extra_atts_args' );

function enlightenment_bootstrap_site_branding_args( $args ) {
	$args['format'] = str_replace( '%1$s%2$s', '%1$s', $args['format'] );

	return $args;
}
add_filter( 'enlightenment_site_branding_args', 'enlightenment_bootstrap_site_branding_args' );

function enlightenment_bootstrap_site_title_args( $args ) {
	$args['container'] = '';
	return $args;
}
add_filter( 'enlightenment_site_title_args', 'enlightenment_bootstrap_site_title_args' );

function enlightenment_bootstrap_site_title_home_link_args( $args ) {
	$args['container_class'] .= ' navbar-brand';
	return $args;
}
add_filter( 'enlightenment_site_title_home_link_args', 'enlightenment_bootstrap_site_title_home_link_args' );

function enlightenment_bootstrap_navicon_args( $args, $nav_menu_args ) {
	$args['container']            = 'button';
	$args['container_class']     .= ' navbar-toggler';
	$args['container_extra_atts'] = array(
		'type'           => 'button',
		'data-bs-toggle' => 'collapse',
		'data-bs-target' => isset( $nav_menu_args['theme_location'] ) ? sprintf( '#menu-%s-container', $nav_menu_args['theme_location'] ) : '.collapse',
	);

	$args['text']  = sprintf( '<span class="screen-reader-text visually-hidden">%s</span>', $args['text'] );
	$args['text'] .= '<span class="navbar-toggler-icon"></span>' . "\n";

	return $args;
}
add_filter( 'enlightenment_navicon_args', 'enlightenment_bootstrap_navicon_args', 10, 2 );

function enlightenment_bootstrap_nav_menu_css_class( $classes, $item, $args ) {
	if ( doing_action( 'get_sidebar' ) ) {
		return $classes;
	}

	if ( defined( 'IFRAME_REQUEST' ) && true === IFRAME_REQUEST ) {
		return $classes;
	}

	if ( doing_filter( 'the_content' ) ) {
		return $classes;
	}

	if ( ! $item->menu_item_parent || 0 == $item->menu_item_parent ) {
		$classes[] = 'nav-item';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'enlightenment_bootstrap_nav_menu_css_class', 10, 3 );

function enlightenment_bootstrap_menu_parent_class( $items ) {
	if ( doing_action( 'get_sidebar' ) ) {
		return $items;
	}

	if ( defined( 'IFRAME_REQUEST' ) && true === IFRAME_REQUEST ) {
		return $items;
	}

	if ( doing_filter( 'the_content' ) ) {
		return $items;
	}

	$parents = array();

	foreach ( $items as $item ) {
		if ( $item->menu_item_parent && 0 < $item->menu_item_parent ) {
			$parents[] = $item->menu_item_parent;
		}
	}

	foreach ( $items as $item ) {
		if ( in_array( $item->ID, $parents ) ) {
			if( $item->menu_item_parent && 0 < $item->menu_item_parent ) {
				$item->classes[] = 'dropdown-submenu';
			} else {
				$item->classes[] = 'dropdown';
			}
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'enlightenment_bootstrap_menu_parent_class' );

function enlightenment_bootstrap_submenu_class( $classes, $args, $depth ) {
	if ( doing_action( 'get_sidebar' ) ) {
		return $classes;
	}

	if ( defined( 'IFRAME_REQUEST' ) && true === IFRAME_REQUEST ) {
		return $classes;
	}

	if ( doing_filter( 'the_content' ) ) {
		return $classes;
	}

	if ( 0 === $depth ) {
		$classes[] = 'dropdown-menu';
	} else {
		$classes[] = 'list-unstyled';
	}

	return $classes;
}
add_filter( 'nav_menu_submenu_css_class', 'enlightenment_bootstrap_submenu_class', 10, 3 );

function enlightenment_bootstrap_nav_menu_args( $args ) {
	if ( '' != $args['theme_location'] ) {
		$args['container_class'] .= ' collapse navbar-collapse';
		$args['container_id']     = sprintf( 'menu-%s-container', $args['theme_location'] );
		$args['menu_class']      .= ' navbar-nav ms-auto';

		$expand = current_theme_supports( 'enlightenment-bootstrap', 'navbar-expand' );

		switch ( $expand ) {
			case '':
				$args['menu_class'] .= ' container px-0';
				break;

			case 'sm':
			case 'md':
			case 'lg':
			case 'xl':
				$args['menu_class'] .= sprintf( ' container px-%s-0', esc_attr( $expand ) );
				break;

			default:
				$args['menu_class'] .= ' container';
				break;
		}
	}

	return $args;
}
add_filter( 'enlightenment_wp_nav_menu_args', 'enlightenment_bootstrap_nav_menu_args', 22 );

function enlightenment_bootstrap_nav_menu_link_attributes( $atts, $item ) {
	if ( doing_action( 'get_sidebar' ) ) {
		return $atts;
	}

	if ( defined( 'IFRAME_REQUEST' ) && true === IFRAME_REQUEST ) {
		return $atts;
	}

	if ( doing_filter( 'the_content' ) ) {
		return $atts;
	}

	if ( enlightenment_has_in_call_stack( array(
		array(
			'key'     => 'class',
			'value'   => 'Elementor',
			'compare' => 'STARTS_WITH',
		),
	) ) ) {
		return $atts;
	}

	if ( empty( $item->menu_item_parent ) ) {
		$class = 'nav-link';
	} else {
		$class = 'dropdown-item';
	}

	if ( ! isset( $atts['class'] ) ) {
		$atts['class'] = $class;
	} else {
		$atts['class'] .= sprintf( ' %s', $class );
	}

	if ( in_array( 'current-menu-item', $item->classes ) ) {
		$atts['class'] .= ' active';
	}

	if ( empty( $item->menu_item_parent ) && in_array( 'menu-item-has-children', $item->classes ) ) {
		$atts['class']         .= ' dropdown-toggle';
		$atts['data-bs-toggle'] = 'dropdown';
		$atts['aria-haspopup']  = 'true';
		$atts['aria-expanded']  = 'false';
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'enlightenment_bootstrap_nav_menu_link_attributes', 10, 2 );

function enlightenment_bootstrap_walker_nav_menu_start_el( $output, $menu_item ) {
	if (
		in_array( 'dropdown', (array) $menu_item->classes )
		&&
		( empty( $menu_item->url ) || '#' === $menu_item->url )
	) {
		$offset = strpos( $output, '<a ' );

		if ( false === $offset ) {
			$offset = strpos( $output, '<a>' );
		}

		if ( false !== $offset ) {
			$output = substr_replace( $output, 'button', $offset + 1, 1 );
			$offset = strpos( $output, '</a>', $offset );
			$output = substr_replace( $output, 'button', $offset + 2, 1 );
		}
	}

	if ( '#' === $menu_item->url ) {
		$output = str_replace( ' href="#"', '', $output );
	}

	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'enlightenment_bootstrap_walker_nav_menu_start_el', 10, 2 );

function enlightenment_bootstrap_search_form_wrap( $output ) {
	if ( doing_action( 'enlightenment_site_header' ) ) {
		$before  = '<aside class="dropdown searchform-dropdown" id="searchform-dropdown">';
		$before .= '<button class="dropdown-toggle btn btn-link p-0 border-0 rounded-0" id="toggle-search-form" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
		$before .= '<i class="fas fa-search" aria-hidden="true" role="presentation"></i>';
		$before .= sprintf( '<span class="screen-reader-text visually-hidden">%s</span>', __( 'Toggle Search Form', 'enlightenment' ) );
		$before .= '</button>';
		$before .= sprintf( '<h2 class="screen-reader-text visually-hidden">%s</h2>', __( 'Search', 'enlightenment' ) );
		$before .= '<ul class="dropdown-menu dropdown-menu-end" role="menu" aria-labelledby="toggle-search-form">';
		$before .= '<li class="dropdown-item">';

		$after   = '</li>';
		$after  .= '</ul>';
		$after  .= '</aside>';

		$output = $before . $output . $after;
	}

	return $output;
}
add_filter( 'enlightenment_search_form', 'enlightenment_bootstrap_search_form_wrap' );

function enlightenment_bootstrap_social_nav_menu_args( $args ) {
	$args['toggle_extra_atts']['aria-label'] = $args['toggle_text'];

	$args['toggle_class'] .= ' btn btn-link p-0 border-0 rounded-0';
	$args['toggle_text']   = sprintf( '<i class="fas fa-share-alt" aria-hidden="true" role="presentation"></i>', $args['toggle_text'] );
	$args['title_class']  .= ' visually-hidden';

	if ( doing_action( 'enlightenment_site_header' ) ) {
		$args['menu_class'] .= ' navbar-nav';
	} else {
		$args['menu_class'] .= ' nav';
	}

	$args['item_class'] .= ' nav-item';
	$args['link_class'] .= ' nav-link';

	return $args;
}
add_filter( 'enlightenment_social_nav_menu_args', 'enlightenment_bootstrap_social_nav_menu_args', 10, 2 );

function enlightenment_bootstrap_social_link_label( $label, $service ) {
	$icons = enlightenment_get_social_links_icons();

	if ( isset( $icons[ $service ] ) ) {
		$label = sprintf( '<i class="fa%s" aria-hidden="true" role="presentation"></i> <span class="screen-reader-text visually-hidden">%s</span>', $icons[ $service ], $label );
	}

	return $label;
}
add_filter( 'enlightenment_social_link_label', 'enlightenment_bootstrap_social_link_label', 10, 2 );

function enlightenment_bootstrap_archive_title_prefix( $output ) {
	if( false !== strpos( $output, ': ' ) ) {
		$output = explode( ': ', $output );
		$prefix = $output[0];
		$title  = $output[1];

		$prefix = sprintf( '<small class="page-title-prefix">%s</small>', $prefix );
		$output = $title . "\n" . $prefix;
	}

	return $output;
}
add_filter( 'get_the_archive_title', 'enlightenment_bootstrap_archive_title_prefix' );

function enlightenment_bootstrap_yoast_breadcrumbs( $output, $args ) {
	$output = str_replace(
		enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] ),
		sprintf(
			'%s%s',
			enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] ),
			enlightenment_open_tag( 'ol', 'breadcrumb' )
		),
		$output
	);
	$output = str_replace(
		enlightenment_close_tag( $args['container'] ),
		sprintf(
			'%s%s',
			enlightenment_close_tag( 'ol' ),
			enlightenment_close_tag( $args['container'] )
		),
		$output
	);
	$output = str_replace(
		sprintf( '<span>%s</span>', WPSEO_Options::get( 'breadcrumbs-home' ) ),
		'',
		$output
	);

	$output = str_replace( '<span>', '', $output );
	$output = str_replace( '</span>', '', $output );
	$output = str_replace( '<a ', '<li class="breadcrumb-item"><a ', $output );
	$output = str_replace( '</a>', '</a></li>', $output );

	if ( false !== strpos( $output, '<span class="breadcrumb_last"' ) ) {
		$output = str_replace( '<span class="breadcrumb_last" aria-current="page">', '<li class="breadcrumb-item active" aria-current="page"><span class="breadcrumb_last">', $output );
		$output = str_replace( '</ol>', '</span></li></ol>', $output );
	} elseif ( false !== strpos( $output, '<strong class="breadcrumb_last"' ) ) {
		$output = str_replace( '<strong class="breadcrumb_last" aria-current="page">', '<li class="breadcrumb-item active" aria-current="page"><strong class="breadcrumb_last">', $output );
		$output = str_replace( '</strong></ol>', '</strong></li></ol>', $output );
	}

	return $output;
}
add_filter( 'enlightenment_yoast_breadcrumbs', 'enlightenment_bootstrap_yoast_breadcrumbs' , 10, 2);

add_filter( 'wpseo_breadcrumb_separator', '__return_false' );

function enlightenment_bootstrap_link_pages_args( $args ) {
	$args['container_class'] .= ' d-flex justify-content-center';

	switch( $args['next_or_number'] ) {
		case 'next':
			$args['before'] = '<div class="d-flex">';
			$args['after']  = '</div>';
			break;

		case 'number':
			$args['before']      = '<ul class="page-numbers pagination">';
			$args['after']       = '</ul>';
			break;
	}

	return $args;
}
add_filter( 'enlightenment_link_pages_args', 'enlightenment_bootstrap_link_pages_args' );

function enlightenment_bootstrap_wp_link_pages_link( $output, $pagenum ) {
	global $page;

	$output = str_replace( ' class="post-page-numbers"', '', $output );
	$output = str_replace( '<a ', '<a class="post-page-numbers" ', $output );

	if ( $pagenum == $page - 1 ) {
		$output = str_replace( 'class="post-page-numbers"', 'class="post-page-numbers prev"', $output );
	} elseif ( $pagenum == $page + 1 ) {
		$output = str_replace( 'class="post-page-numbers"', 'class="post-page-numbers next"', $output );
	}

	return $output;
}
add_filter( 'wp_link_pages_link', 'enlightenment_bootstrap_wp_link_pages_link', 10, 2 );

function enlightenment_bootstrap_link_pages( $output, $args ) {
	switch( $args['next_or_number'] ) {
		case 'next':
			$output = str_replace( '<a class="post-page-numbers prev"', '<div><a class="post-page-numbers prev"', $output );
			$output = str_replace( '<a class="post-page-numbers next"', '<div class="ms-auto"><a class="post-page-numbers next"', $output );
			$output = str_replace( '</a>', '</a></div>', $output );
			break;

		case 'number':
			$output = str_replace( '<a class="post-page-numbers ', '<li class="page-item"><a class="post-page-numbers ', $output );
			$output = str_replace( '<a class="post-page-numbers"', '<li class="page-item"><a class="post-page-numbers"', $output );
			$output = str_replace( '<span class="post-page-numbers current"', '<li class="page-item active"><span class="post-page-numbers current"', $output );
			$output = str_replace( '</a>', '</a></li>', $output );
			$output = str_replace( '</span>', '</span></li>', $output );
			$output = str_replace( 'class="post-page-numbers ', 'class="post-page-numbers page-link ', $output );
			$output = str_replace( 'class="post-page-numbers"', 'class="post-page-numbers page-link"', $output );
			break;
	}

	return $output;
}
add_filter( 'enlightenment_link_pages', 'enlightenment_bootstrap_link_pages', 10, 2 );

function enlightenment_bootstrap_the_password_form( $output ) {
	$output = str_replace( '<p><label ', '<p class="mb-0"><label class="form-label" ', $output );
	$output = str_replace( ' <input name="post_password"', '</label> <span class="input-group"><input name="post_password" class="form-control"', $output );
	$output = str_replace( ' /></label>', ' />', $output );
	$output = str_replace( '<input type="submit"', '<input type="submit" class="btn btn-light"', $output );
	$output = str_replace( ' /></p>', ' /></span></p>', $output );

	return $output;
}
add_filter( 'the_password_form', 'enlightenment_bootstrap_the_password_form' );

function enlightenment_bootstrap_autor_hcard_args( $args ) {
	$args['container_class'] .= ' card';

	return $args;
}
add_filter( 'enlightenment_author_hcard_args', 'enlightenment_bootstrap_autor_hcard_args' );

function enlightenment_bootstrap_autor_hcard_avatar( $avatar ) {
	$avatar = sprintf( '<div class="card-body d-flex"><span class="flex-shrink-0 me-3">%s</span><div class="flex-grow-1">', $avatar );

	return $avatar;
}
add_filter( 'enlightenment_author_hcard_avatar', 'enlightenment_bootstrap_autor_hcard_avatar' );

function enlightenment_bootstrap_author_social_links( $output ) {
	$output = str_replace( 'class="author-social-links"', 'class="author-social-links list-unstyled row g-3 mb-0"', $output );

	$icons = enlightenment_get_author_social_links_icons();

	$services = enlightenment_get_author_social_links_services();
	foreach ( $services as $service => $label ) {
		if ( ! array_key_exists( $service, $icons ) ) {
			continue;
		}

		$offset = strpos( $output, sprintf( '<li class="author-social-link author-social-link-%s">', $service ) );
		if ( false !== $offset ) {
			$offset = strpos( $output, '<a ', $offset );
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, sprintf( '<i class="fa%s fa-fw" aria-hidden="true" role="presentation" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="%s"></i> <span class="screen-reader-text visually-hidden">', $icons[ $service ], esc_attr( $label ) ), $offset + 1, 0 );
			$offset = strpos( $output, '<span class="screen-reader-text visually-hidden">', $offset );
			$offset = strpos( $output, '>', $offset );
			$offset = strpos( $output, '<', $offset + 1 );
			$output = substr_replace( $output, '</span>', $offset, 0 );
		}
	}

	$start = strpos( $output, '<ul class="author-social-links ' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<li class="author-social-link ', $start );

		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' col flex-grow-0', $offset + 29, 0 );

			$offset = strpos( $output, '<li class="author-social-link ', $offset + 1 );
		}
	}

	if ( enlightenment_has_in_call_stack( 'enlightenment_author_hcard' ) ) {
		$output .= '</div></div>';
	}

	return $output;
}
add_filter( 'enlightenment_author_social_links', 'enlightenment_bootstrap_author_social_links' );

function enlightenment_bootstrap_paginate_links( $output, $args ) {
	if( $args['paged'] ) {

		$output = str_replace( '<div class="nav-links">', '<ul class="nav-links pagination">', $output );
		$output = str_replace( '</div>', '</ul>', $output );

		if ( false !== strpos( $output, '<li>' ) ) {
			$output = str_replace( "<ul class='page-numbers'>", '<ul class="page-numbers pagination">', $output );
			$output = str_replace( '<li><a ', '<li class="page-item"><a ', $output );
			$output = str_replace( '<li><span class="page-numbers dots">', '<li class="page-item"><span class="page-numbers dots">', $output );

			$offset = strpos( $output, 'aria-current="page" ' );
			if ( false !== $offset ) {
				$offset = strrpos( $output, '<li>', $offset - strlen( $output ) );
				$output = substr_replace( $output, ' class="page-item active"', $offset + 3, 0 );
			}
		} else {
			$output = str_replace( '<a ', '<li class="page-item"><a ', $output );
			$output = str_replace( '</a>', '</a></li>', $output );

			$offset = strpos( $output, 'aria-current="page" ' );
			if ( false !== $offset ) {
				$offset = strrpos( $output, '<span ', $offset - strlen( $output ) );
				$output = substr_replace( $output, '<li class="page-item active">', $offset, 0 );
				$offset = strpos( $output, '</span>', $offset );
				$output = substr_replace( $output, '</li>', $offset + 7, 0 );
			}

			$offset = strpos( $output, '<span class="page-numbers dots">' );
			if ( false !== $offset ) {
				$output = substr_replace( $output, '<li class="page-item">', $offset, 0 );
				$offset = strpos( $output, '</span>', $offset );
				$output = substr_replace( $output, '</li>', $offset + 7, 0 );
			}
		}

		$output = str_replace( 'class="page-numbers current"', 'class="page-numbers current page-link"', $output );
		$output = str_replace( 'class="page-numbers"', 'class="page-numbers page-link"', $output );
		$output = str_replace( 'class="page-numbers dots"', 'class="page-numbers dots page-link"', $output );
		$output = str_replace( 'class="prev page-numbers"', 'class="prev page-numbers page-link"', $output );
		$output = str_replace( 'class="next page-numbers"', 'class="next page-numbers page-link"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_posts_nav',    'enlightenment_bootstrap_paginate_links', 10, 2 );
add_filter( 'enlightenment_comments_nav', 'enlightenment_bootstrap_paginate_links', 10, 2 );

function enlightenment_bootstrap_navigation_markup( $output ) {
	$output = str_replace( '"screen-reader-text"', '"screen-reader-text visually-hidden"',   $output );
	$output = str_replace( '"nav-links"',          '"nav-links d-flex"',             $output );
	$output = str_replace( '"nav-previous"',       '"nav-previous ms-auto order-1"', $output );

	return $output;
}
add_filter( 'enlightenment_posts_nav',    'enlightenment_bootstrap_navigation_markup' );
add_filter( 'enlightenment_comments_nav', 'enlightenment_bootstrap_navigation_markup' );

function enlightenment_bootstrap_post_nav( $output ) {
	$output = str_replace( '"screen-reader-text"', '"screen-reader-text visually-hidden"',   $output );
	$output = str_replace( '"nav-links"',          '"nav-links row"',                $output );
	$output = str_replace( '"nav-previous"',       '"nav-previous col-12 col-sm"', $output );
	$output = str_replace( '"nav-next"',           '"nav-next col-12 col-sm text-sm-end"', $output );

	return $output;
}
add_filter( 'enlightenment_post_nav', 'enlightenment_bootstrap_post_nav' );

function enlightenment_bootstrap_comment_args( $args ) {
	$args['comment_class'] .= ' d-flex';

	return $args;
}
add_filter( 'enlightenment_comment_args', 'enlightenment_bootstrap_comment_args' );

function enlightenment_bootstrap_comment_author_avatar_args( $args ) {
	$args['avatar_container_class'] .= ' flex-shrink-0 me-3';

	return $args;
}
add_filter( 'enlightenment_comment_author_avatar_args', 'enlightenment_bootstrap_comment_author_avatar_args' );

function enlightenment_bootstrap_comment_form_fields_args( $args ) {
	$args['author_label_class'] .= ' form-label';
	$args['author_class']       .= ' form-control';

	$args['email_label_class']  .= ' form-label';
	$args['email_class']        .= ' form-control';

	$args['url_label_class']    .= ' form-label';
	$args['url_class']          .= ' form-control';

	$args['after_author_label']  = str_replace( '">', ' text-danger">', $args['after_author_label'] );
	$args['after_email_label']   = str_replace( '">', ' text-danger">', $args['after_email_label'] );

	return $args;
}
add_filter( 'enlightenment_comment_form_fields_args', 'enlightenment_bootstrap_comment_form_fields_args' );

function enlightenment_bootstrap_comment_form_defaults_args( $args ) {
	$args['label_class']    .= ' form-label';
	$args['textarea_class'] .= ' form-control';

	return $args;
}
add_filter( 'enlightenment_comment_form_defaults_args', 'enlightenment_bootstrap_comment_form_defaults_args' );

function enlightenment_bootstrap_comment_form_defaults( $args ) {
	$args['comment_notes_before'] = str_replace( 'class="required"',    'class="required text-danger"', $args['comment_notes_before'] );
	$args['logged_in_as']         = str_replace( 'class="required"',    'class="required text-danger"', $args['logged_in_as']         );
	$args['submit_field']         = str_replace( 'class="form-submit"', 'class="form-submit mb-0"',     $args['submit_field']         );
	$args['class_submit']        .= ' btn btn-primary btn-lg';

	return $args;
}
add_filter( 'comment_form_defaults', 'enlightenment_bootstrap_comment_form_defaults' );

function enlightenment_bootstrap_search_form_args( $args ) {
	$args['before']        .= '<div class="input-group">';
	$args['after']         .= '</div>';
	$args['input_class']   .= ' form-control';
	$args['submit_class']  .= ' btn btn-light';

	return $args;
}
add_filter( 'enlightenment_search_form_args', 'enlightenment_bootstrap_search_form_args' );

function enlightenment_bootstrap_page_content_class_args( $args ) {
	if( current_theme_supports( 'enlightenment-custom-layouts' ) ) {
		return $args;
	}

	$args['class'] .= ' col-md-' . ( is_page() ? 12 : 8 );

	return $args;
}
add_filter( 'enlightenment_page_content_class_args', 'enlightenment_bootstrap_page_content_class_args' );

function enlightenment_bootstrap_sidebar_class_args( $args ) {
	if( current_theme_supports( 'enlightenment-custom-layouts' ) ) {
		return $args;
	}

	$args['class'] .= ' col-md-4';

	return $args;
}
add_filter( 'enlightenment_sidebar_class_args', 'enlightenment_bootstrap_sidebar_class_args' );

function enlightenment_bootstrap_screen_reader_class( $args ) {
	$args['container_class'] .= ' visually-hidden';

	return $args;
}
add_filter( 'enlightenment_nav_menu_title_args', 'enlightenment_bootstrap_screen_reader_class' );

function enlightenment_bootstrap_focusable_screen_reader_class( $args ) {
	$args['container_class'] .= ' visually-hidden-focusable';

	return $args;
}
add_filter( 'enlightenment_skip_link_args', 'enlightenment_bootstrap_focusable_screen_reader_class' );

function enlightenment_bootstrap_accessibility_search_form_args( $args ) {
	$args['label_class'] .= ' visually-hidden';

	return $args;
}
add_filter( 'enlightenment_accessibility_search_form_args', 'enlightenment_bootstrap_accessibility_search_form_args' );

function enlightenment_bootstrap_signup_form( $output ) {
	$output = str_replace( 'class="error"', 'class="error alert alert-danger"', $output );
	$output = str_replace( 'class="mu_alert"', 'class="mu_alert alert alert-info"', $output );
	$output = str_replace( 'class="wp-signup-radio-button"', 'class="wp-signup-radio-button form-check"', $output );
	$output = str_replace( 'name="signup_for"', 'name="signup_for" class="form-check-input"', $output );
	$output = str_replace( 'name="blog_public"', 'name="blog_public" class="form-check-input"', $output );
	$output = str_replace( 'class="checkbox"', 'class="checkbox form-check-label"', $output );
	$output = str_replace( 'class="prefix_address"', 'class="prefix_address input-group-text"', $output );
	$output = str_replace( 'class="suffix_address"', 'class="suffix_address input-group-text"', $output );
	$output = str_replace( '<p class="submit">', '<p class="submit mb-0">', $output );
	$output = str_replace( 'type="submit" name="submit" class="submit"', 'type="submit" name="submit" class="submit btn btn-primary btn-lg"', $output );

	$offset = strpos( $output, '<label for="user_name">' );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '</label>', $start ) + 8;
		$length = $end - $start;
		$label  = substr( $output, $start, $length );
		$label  = str_replace( '<label ', '<label class="form-label" ', $label );

		$output = substr_replace( $output, '', $start, $length );
		$offset = strpos( $output, '<input name="user_name"', $offset );
		$output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
		$output = substr_replace( $output, sprintf( '<div class="mb-3">%s%s', "\n", $label ), $offset, 0 );
		$offset = strpos( $output, '<p id="wp-signup-username-description">', $offset );
		$output = substr_replace( $output, ' class="form-text mb-0"', $offset + 2, 0 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
	}

	$offset = strpos( $output, '<label for="user_email">' );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '</label>', $start ) + 8;
		$length = $end - $start;
		$label  = substr( $output, $start, $length );
		$label  = str_replace( '<label ', '<label class="form-label" ', $label );

		$output = substr_replace( $output, '', $start, $length );
		$offset = strpos( $output, '<input name="user_email"', $offset );
		$output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
		$output = substr_replace( $output, sprintf( '<div class="mb-3">%s%s', "\n", $label ), $offset, 0 );
		$offset = strpos( $output, '<p id="wp-signup-email-description">', $offset );
		$output = substr_replace( $output, ' class="form-text mb-0"', $offset + 2, 0 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
	}

	$offset = strpos( $output, '<label for="blogname">' );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '</label>', $start ) + 8;
		$length = $end - $start;
		$label  = substr( $output, $start, $length );
		$label  = str_replace( '<label ', '<label class="form-label" ', $label );

		$output = substr_replace( $output, '', $start, $length );
		$offset = strpos( $output, '<div class="wp-signup-blogname">', $offset );
		$output = substr_replace( $output, ' input-group', $offset + 30, 0 );
		$output = substr_replace( $output, sprintf( '<div class="mb-3">%s%s', "\n", $label ), $offset, 0 );
		$offset = strpos( $output, '<input name="blogname"', $offset );
		$output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );

		if ( is_user_logged_in() ) {
			$offset = strpos( $output, '</div>', $offset );
			$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
		} else {
			$offset = strpos( $output, '<p>', $offset );
			$output = substr_replace( $output, ' class="form-text mb-0"', $offset + 2, 0 );
			$offset = strpos( $output, '</p>', $offset );
			$output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
		}
	}

	$offset = strpos( $output, '<label for="blog_title">' );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '</label>', $start ) + 8;
		$length = $end - $start;
		$label  = substr( $output, $start, $length );
		$label  = str_replace( '<label ', '<label class="form-label" ', $label );

		$output = substr_replace( $output, '', $start, $length );
		$offset = strpos( $output, '<input name="blog_title"', $offset );
		$output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
		$output = substr_replace( $output, sprintf( '<div class="mb-3">%s%s', "\n", $label ), $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 2, 0 );
	}

	$offset = strpos( $output, '<label for="site-language">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="form-label"', $offset + 6, 0 );
		$offset = strrpos( $output, '<p>', $offset - strlen( $output ) );
		$output = substr_replace( $output, '<div class="mb-3">', $offset, 3 );
		$offset = strpos( $output, '<select name="WPLANG"', $offset );
		$output = substr_replace( $output, ' class="form-select"', $offset + 7, 0 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, '</div>', $offset + 4, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_filter_signup_form', 'enlightenment_bootstrap_signup_form' );

function enlightenment_bootstrap_project_types_filter_args( $args ) {
	$args['filter_class']       .= ' nav';
	$args['filter_title_class'] .= ' visually-hidden';
	$args['term_class']         .= ' nav-item';
	$args['link_class']         .= ' nav-link';

	return $args;
}
add_filter( 'enlightenment_project_types_filter_args', 'enlightenment_bootstrap_project_types_filter_args' );

function enlightenment_bootstrap_project_types_filter_link_html( $output, $term, $link, $current_url ) {
	if ( $link == $current_url ) {
		$output = str_replace( ' nav-link', ' nav-link active', $output );
	}

	return $output;
}
add_filter( 'enlightenment_project_types_filter_link_html', 'enlightenment_bootstrap_project_types_filter_link_html', 10, 4 );

function enlightenment_bootstrap_fix_color_switcher_redirect_from_events( $is_redirected ) {
	if ( isset( $_REQUEST['action'] ) && 'enlightenment_color_mode' == $_REQUEST['action'] ) {
		return true;
	}

	return $is_redirected;
}
add_filter( 'tec_events_views_v2_redirected', 'enlightenment_bootstrap_fix_color_switcher_redirect_from_events' );

function enlightenment_bootstrap_dequeue_wpcf7_style() {
	wp_dequeue_style( 'contact-form-7' );
}
add_action( 'wpcf7_enqueue_styles', 'enlightenment_bootstrap_dequeue_wpcf7_style' );

function enlightenment_bootstrap_wpcf7_form_elements( $output ) {
	$output = str_replace( 'class="wpcf7-form-control-wrap ', 'class="wpcf7-form-control-wrap position-relative ', $output );

	$output = str_replace( 'class="wpcf7-quiz-label"', 'class="wpcf7-quiz-label form-label d-inline-block"', $output );

    $output = str_replace( 'class="wpcf7-form-control wpcf7-text ',     'class="wpcf7-form-control wpcf7-text form-control ',     $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-text"',     'class="wpcf7-form-control wpcf7-text form-control"',     $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-textarea ', 'class="wpcf7-form-control wpcf7-textarea form-control ', $output );
	$output = str_replace( 'class="wpcf7-form-control wpcf7-textarea"', 'class="wpcf7-form-control wpcf7-textarea form-control"', $output );
	$output = str_replace( 'class="wpcf7-form-control wpcf7-email ',    'class="wpcf7-form-control wpcf7-email form-control ',    $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-email"',    'class="wpcf7-form-control wpcf7-email form-control"',    $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-url ',      'class="wpcf7-form-control wpcf7-url form-control ',      $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-url"',      'class="wpcf7-form-control wpcf7-url form-control"',      $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-tel ',      'class="wpcf7-form-control wpcf7-tel form-control ',      $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-tel"',      'class="wpcf7-form-control wpcf7-tel form-control"',      $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-number ',   'class="wpcf7-form-control wpcf7-number form-control ',   $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-number"',   'class="wpcf7-form-control wpcf7-number form-control"',   $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-date ',     'class="wpcf7-form-control wpcf7-date form-control ',     $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-date"',     'class="wpcf7-form-control wpcf7-date form-control"',     $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-file ',     'class="wpcf7-form-control wpcf7-file form-control ',     $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-file"',     'class="wpcf7-form-control wpcf7-file form-control"',     $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-quiz ',     'class="wpcf7-form-control wpcf7-quiz form-control ',     $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-quiz"',     'class="wpcf7-form-control wpcf7-quiz form-control"',     $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-range ',    'class="wpcf7-form-control wpcf7-range form-range ',      $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-range"',    'class="wpcf7-form-control wpcf7-range form-range"',      $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-select ',   'class="wpcf7-form-control wpcf7-select form-select ',    $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-select"',   'class="wpcf7-form-control wpcf7-select form-select"',    $output );

	$output = str_replace( ' wpcf7-not-valid ', ' wpcf7-not-valid is-invalid ', $output );
	$output = str_replace( ' wpcf7-not-valid"', ' wpcf7-not-valid is-invalid"', $output );

	$output = str_replace( '<br />', '', $output );

	$offset = strpos( $output, 'class="wpcf7-list-item ' );
	while ( false !== $offset ) {
		$offset_a = strpos( $output, '>', $offset ) + 1;
		$tag      = substr( $output, $offset_a, 6 );
		if ( '<label' == $tag ) {
			$output = substr_replace( $output, ' class="form-check"', $offset_a + 6, 0 );
		} else {
			$output = substr_replace( $output, ' form-check', $offset + 22, 0 );
		}

		$offset = strpos( $output, 'class="wpcf7-list-item ', $offset + 1 );
	}

	$offset = strpos( $output, 'class="wpcf7-list-item"' );
	while ( false !== $offset ) {
		$offset_a = strpos( $output, '>', $offset ) + 1;
		$tag      = substr( $output, $offset_a, 6 );
		if ( '<label' == $tag ) {
			$output = substr_replace( $output, ' class="form-check"', $offset_a + 6, 0 );
		} else {
			$output = substr_replace( $output, ' form-check', $offset + 22, 0 );
		}

		$offset = strpos( $output, 'class="wpcf7-list-item"', $offset + 1 );
	}

	$offset = strpos( $output, '<input type="checkbox"' );
	while ( false !== $offset ) {
		$offset_a = strpos( $output, 'class="', $offset );
		$end_a    = strpos( $output, '>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, 'form-check-input ', $offset_a + 7, 0 );
		} else {
			$output = substr_replace( $output, ' class="form-check-input"', $offset + 22, 0 );
		}

		$offset = strpos( $output, '<input type="checkbox"', $offset + 1 );
	}

	$offset = strpos( $output, '<input type="radio"' );
	while ( false !== $offset ) {
		$offset_a = strpos( $output, 'class="', $offset );
		$end_a    = strpos( $output, '>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, 'form-check-input ', $offset_a + 7, 0 );
		} else {
			$output = substr_replace( $output, ' class="form-check-input"', $offset + 22, 0 );
		}

		$offset = strpos( $output, '<input type="radio"', $offset + 1 );
	}

	$output = str_replace( 'class="wpcf7-list-item-label"', 'class="wpcf7-list-item-label form-check-label"', $output );

	$offset = strpos( $output, 'class="wpcf7-quiz-label ' );
	while ( false !== $offset ) {
		$offset_a = strrpos( $output, '<label>', $offset - strlen( $output ) );
		if ( false !== $offset_a ) {
			$output = substr_replace( $output, ' class="d-block"', $offset_a + 6, 0 );
		}

		$offset = strpos( $output, 'class="wpcf7-quiz-label ', $offset + 1 );
	}

    $output = str_replace( 'class="wpcf7-form-control wpcf7-submit ', 'class="wpcf7-form-control wpcf7-submit btn btn-primary btn-lg ', $output );
    $output = str_replace( 'class="wpcf7-form-control wpcf7-submit"', 'class="wpcf7-form-control wpcf7-submit btn btn-primary btn-lg"', $output );
    $output = str_replace( 'class="wpcf7-form-control has-spinner wpcf7-submit ', 'class="wpcf7-form-control has-spinner wpcf7-submit btn btn-primary btn-lg ', $output );
    $output = str_replace( 'class="wpcf7-form-control has-spinner wpcf7-submit"', 'class="wpcf7-form-control has-spinner wpcf7-submit btn btn-primary btn-lg"', $output );

    return $output;
}
add_filter( 'wpcf7_form_elements', 'enlightenment_bootstrap_wpcf7_form_elements' );

function enlightenment_bootstrap_wpcf7_validation_error( $output ) {
	return str_replace( 'class="wpcf7-not-valid-tip"', 'class="wpcf7-not-valid-tip d-block form-text text-danger"', $output );
}
add_filter( 'wpcf7_validation_error', 'enlightenment_bootstrap_wpcf7_validation_error' );

function enlightenment_bootstrap_wpcf7_form_response_output( $output, $class, $content, $form, $status ) {
	$class = 'mb-0 alert';

	switch ( $status ) {
		case 'mail_sent':
			$class .= ' alert-success';
			break;

		case 'acceptance_missing':
		case 'validation_failed':
		case 'mail_failed':
		case 'aborted':
			$class .= ' alert-danger';
			break;

		case 'init':
		case 'submitting':
		case 'resetting':
			$class .= ' d-none';
			break;

		default:
			$class .= ' alert-info';
			break;
	}

	$output = str_replace( 'class="wpcf7-response-output"', sprintf( 'class="wpcf7-response-output %s"', $class ), $output );

	return $output;
}
add_filter( 'wpcf7_form_response_output', 'enlightenment_bootstrap_wpcf7_form_response_output', 10, 5 );

function enlightenment_bootstrap_wpcf7_shortcode_tag( $output ) {
	return str_replace( 'class="screen-reader-response"', 'class="screen-reader-response visually-hidden"', $output );
}
add_filter( 'enlightenment_filter_shortcode_tag_contact-form-7', 'enlightenment_bootstrap_wpcf7_shortcode_tag' );
add_filter( 'enlightenment_filter_shortcode_tag_contact-form', 'enlightenment_bootstrap_wpcf7_shortcode_tag' );
