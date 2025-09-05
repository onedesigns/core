<?php

function enlightenment_nav_menu( $args = null ) {
	$defaults = array(
		'theme_location'       => '',
		'menu'                 => '',
		'container'            => 'nav',
		'container_class'      => 'main-navigation',
		'container_id'         => 'site-navigation',
		'container_extra_atts' => array( 'role' => 'navigation' ),
		'collapse'             => true,
		'menu_class'           => 'menu',
		'menu_id'              => '',
		'echo'                 => true,
		'fallback_cb'          => 'wp_page_menu',
		'before'               => '',
		'after'                => '',
		'link_before'          => '',
		'link_after'           => '',
		'items_wrap'           => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'                => 0,
		'walker'               => new Enlightenment_Walker_Nav_Menu,
	);
	$defaults = apply_filters( 'enlightenment_nav_menu_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$nav_menu_args = wp_parse_args( array(
		'container'       => 'div',
		'container_class' => '',
		'container_id'    => '',
		'echo'            => false,
	), $args );
	$nav_menu_args = apply_filters( 'enlightenment_wp_nav_menu_args', $nav_menu_args );

	$nav_menu = wp_nav_menu( $nav_menu_args );

	if( '' == $nav_menu_args['theme_location'] ) {
		$args['container'] = '';
		$args['collapse']  = false;
	}

	$output  = '';

	if ( ! empty( $nav_menu ) ) {
		$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
		$output .= $args['collapse'] ? enlightenment_navicon( array( 'echo' => false ), $nav_menu_args ) : '';
		$output .= $nav_menu;
		$output .= enlightenment_close_tag( $args['container'] );
	}

	$output = apply_filters( 'enlightenment_nav_menu', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_navicon( $args = null, $nav_menu_args = null ) {
	$defaults = array(
		'container'            => 'a',
		'container_class'      => 'menu-toggle',
		'container_id'         => '',
		'container_extra_atts' => array( 'href' => '#site-navigation' ),
		'target'               => '#site-navigation',
		'text'                 => __( 'Toggle Navigation', 'enlightenment' ),
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_navicon_args', $defaults, $nav_menu_args );
	$args     = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );
	$output .= $args['text'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_navicon', $output, $args, $nav_menu_args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_social_nav_menu( $args = null ) {
	$defaults = array(
		'container'            => 'nav',
		'container_class'      => 'social-navigation',
		'container_id'         => 'social-navigation',
		'container_extra_atts' => array( 'role' => 'navigation' ),
		'toggle_tag'           => 'button',
		'toggle_class'         => 'social-navigation-toggle',
		'toggle_extra_atts'    => array( 'type' => 'button' ),
		'toggle_text'          => __( 'Toggle Social Links', 'enlightenment' ),
		'title_tag'            => 'h2',
		'title_class'          => 'screen-reader-text',
		'title_text'           => __( 'Follow Us', 'enlightenment' ),
		'menu_tag'             => 'ul',
		'menu_class'           => 'menu',
		'item_tag'             => 'li',
		'item_class'           => 'menu-item menu-item-service-%s',
		'link_class'           => '',
		'link_target'          => '',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_social_nav_menu_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$links  = enlightenment_get_social_links();
	$labels = enlightenment_get_social_links_services();

	if ( empty( $links ) ) {
		return;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'], $args['container_extra_atts'] );

	if ( ! empty( $args['toggle_text'] ) ) {
		$output .= enlightenment_open_tag( $args['toggle_tag'], $args['toggle_class'], '', $args['toggle_extra_atts'] );
		$output .= $args['toggle_text'];
		$output .= enlightenment_close_tag( $args['toggle_tag'] );
	}

	if ( ! empty( $args['title_text'] ) ) {
		$output .= enlightenment_open_tag( $args['title_tag'], $args['title_class'] );
		$output .= $args['title_text'];
		$output .= enlightenment_close_tag( $args['title_tag'] );
	}

	$output .= enlightenment_open_tag( $args['menu_tag'], $args['menu_class'] );

	foreach ( $links as $service => $url ) {
		if ( empty( $url ) ) {
			continue;
		}

		$link_atts = array(
			'href' => esc_url( $url ),
		);

		if ( ! empty( $args['link_target'] ) ) {
			$link_atts['target'] = $args['link_target'];
		}

		$output .= enlightenment_open_tag( $args['item_tag'], sprintf( $args['item_class'], $service ) );
		$output .= enlightenment_open_tag( 'a', $args['link_class'], '', $link_atts );
		$output .= apply_filters( 'enlightenment_social_link_label', $labels[ $service ], $service );
		$output .= enlightenment_close_tag( 'a' );
		$output .= enlightenment_close_tag( $args['item_tag'] );
	}

	$output .= enlightenment_close_tag( $args['menu_tag'] );

	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_social_nav_menu', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_archive_title( $args = null ) {
	$defaults = array(
		'container'       => is_singular() ? 'div' : 'h1',
		'container_class' => 'page-title',
		'container_id'    => '',
		'before'          => '',
		'after'           => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_archive_title_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$before  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$before .= $args['before'];

	$after  = $args['after'];
	$after .= enlightenment_close_tag( $args['container'] );

	ob_start();
	the_archive_title( $before, $after);
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_archive_title', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_archive_description( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'page-description',
		'container_id'    => '',
		'before'          => '',
		'after'           => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_archive_description_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$before  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$before .= $args['before'];

	$after  = $args['after'];
	$after .= enlightenment_close_tag( $args['container'] );

	ob_start();
	the_archive_description( $before, $after);
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_archive_description', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_breadcrumbs( $args = null ) {
	$defaults = array(
		'container'       => 'nav',
		'container_class' => 'breadcrumbs',
		'container_id'    => '',
		'before'          => '',
		'after'           => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_breadcrumbs_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$output = '';

	$output = apply_filters( 'enlightenment_breadcrumbs', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_link_pages( $args = null ) {
	$defaults = array(
		'container'        => 'nav',
		'container_class'  => 'page-links',
		'container_id'     => '',
		'before'           => '<p class="post-nav-links">' . __( 'Pages:', 'enlightenment' ),
		'after'            => '</p>',
		'link_before'      => '',
		'link_after'       => '',
		'aria_current'     => 'page',
		'next_or_number'   => 'number',
		'separator'        => ' ',
		'nextpagelink'     => __( 'Next page', 'enlightenment' ),
		'previouspagelink' => __( 'Previous page', 'enlightenment' ),
		'pagelink'         => '%',
		'echo'             => true,
	);
	$defaults = apply_filters( 'enlightenment_link_pages_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$html = wp_link_pages( wp_parse_args( array( 'echo' => false ), $args ) );

	if( empty( $html ) ) {
		return;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= $html;
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_link_pages', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_posts_nav( $args = null ) {
	$defaults = array(
		'container'       => 'nav',
		'container_class' => 'posts-nav',
		'container_id'    => 'posts-nav',
		'class'           => '',
		'label_tag'       => 'span',
		'label_class'     => 'posts-nav-label',
		'prev_text'       => __( 'Older posts', 'enlightenment' ),
		'next_text'       => __( 'Newer posts', 'enlightenment' ),
		'pointer_tag'     => 'span',
		'pointer_class'   => 'pointer',
		'prev_pointer'    => is_rtl() ? '&larr;' : '&rarr;',
		'next_pointer'    => is_rtl() ? '&rarr;' : '&larr;',
		'paged'           => false,
		'type'            => 'plain',
		'custom_cb'       => '',
		'custom_cb_args'  => array(),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_posts_nav_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( is_callable( $args['custom_cb'] ) ) {
		return call_user_func_array( $args['custom_cb'], $args['custom_cb_args'] );
	}

	global $wp_query;

	if ( 1 >= $wp_query->max_num_pages ) {
		return false;
	}

	$pointer_extra_atts = array(
		'aria-hidden' => 'true',
		'role'        => 'presentation',
	);

	$prev_text = '';
	$next_text = '';

	if ( is_rtl() ) {
		if ( ! empty( $args['prev_pointer'] ) ) {
			$prev_text .= enlightenment_open_tag( $args['pointer_tag'], $args['pointer_class'], '', $pointer_extra_atts );
			$prev_text .= $args['prev_pointer'];
			$prev_text .= enlightenment_close_tag( $args['pointer_tag'] );
		}

		if ( ! empty( $args['prev_pointer'] ) && ! empty( $args['prev_text'] ) ) {
			$prev_text .= ' ';
		}

		if ( ! empty( $args['prev_text'] ) ) {
			$prev_text .= enlightenment_open_tag( $args['label_tag'], $args['label_class'] );
			$prev_text .= $args['prev_text'];
			$prev_text .= enlightenment_close_tag( $args['label_tag'] );
		}

		if ( ! empty( $args['next_text'] ) ) {
			$next_text  = enlightenment_open_tag( $args['label_tag'], $args['label_class'] );
			$next_text .= $args['next_text'];
			$next_text .= enlightenment_close_tag( $args['label_tag'] );
		}

		if ( ! empty( $args['next_text'] ) && ! empty( $args['next_pointer'] ) ) {
			$next_text .= ' ';
		}

		if ( ! empty( $args['next_pointer'] ) ) {
			$next_text .= enlightenment_open_tag( $args['pointer_tag'], $args['pointer_class'], '', $pointer_extra_atts );
			$next_text .= $args['next_pointer'];
			$next_text .= enlightenment_close_tag( $args['pointer_tag'] );
		}
	} else {
		if ( ! empty( $args['prev_text'] ) ) {
			$prev_text .= enlightenment_open_tag( $args['label_tag'], $args['label_class'] );
			$prev_text .= $args['prev_text'];
			$prev_text .= enlightenment_close_tag( $args['label_tag'] );
		}

		if ( ! empty( $args['prev_pointer'] ) && ! empty( $args['prev_text'] ) ) {
			$prev_text .= ' ';
		}

		if ( ! empty( $args['prev_pointer'] ) ) {
			$prev_text .= enlightenment_open_tag( $args['pointer_tag'], $args['pointer_class'], '', $pointer_extra_atts );
			$prev_text .= $args['prev_pointer'];
			$prev_text .= enlightenment_close_tag( $args['pointer_tag'] );
		}

		if ( ! empty( $args['next_pointer'] ) ) {
			$next_text .= enlightenment_open_tag( $args['pointer_tag'], $args['pointer_class'], '', $pointer_extra_atts );
			$next_text .= $args['next_pointer'];
			$next_text .= enlightenment_close_tag( $args['pointer_tag'] );
		}

		if ( ! empty( $args['next_pointer'] ) && ! empty( $args['next_text'] ) ) {
			$next_text .= ' ';
		}

		if ( ! empty( $args['next_text'] ) ) {
			$next_text .= enlightenment_open_tag( $args['label_tag'], $args['label_class'] );
			$next_text .= $args['next_text'];
			$next_text .= enlightenment_close_tag( $args['label_tag'] );
		}
	}

	if ( $args['paged'] ) {
		$args['class'] .= ' pagination';
		$args['class']  = trim( $args['class'] );

		/**
		 * The function paginate_links() uses the reverse logic of
		 * get_the_posts_navigation() for prev_text/next_text so we need to
		 * reverse the parameters too.
		**/
		$args['prev_text'] = $next_text;
		$args['next_text'] = $prev_text;

		$output = get_the_posts_pagination( $args );
	} else {
		$args['class'] .= ' posts-navigation';
		$args['class']  = trim( $args['class'] );

		$args['prev_text'] = $prev_text;
		$args['next_text'] = $next_text;

		$output = get_the_posts_navigation( $args );
	}

	if ( ! empty( $args['container_class'] ) ) {
		$output = str_replace(
			sprintf( 'class="navigation %s"', esc_attr( $args['class'] ) ),
			sprintf( 'class="navigation %s %s"', esc_attr( $args['class'] ), esc_attr( trim( $args['container_class'] ) ) ),
			$output
		);
	}

	if ( ! empty( $args['container_id'] ) ) {
		$output = str_replace( ' role="navigation"', sprintf( ' id="%s" role="navigation"', esc_attr( $args['container_id'] ) ), $output );
	}

	if ( ! empty( $args['container'] ) && 'nav' != $args['container'] ) {
		$output = str_replace( '<nav ',  sprintf( '<%s ',  esc_attr( $args['container'] ) ), $output );
		$output = str_replace( '</nav>', sprintf( '</%s>', esc_attr( $args['container'] ) ), $output );
	}

	$output = apply_filters( 'enlightenment_posts_nav', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_post_nav( $args = null ) {
	$defaults = array(
		'container'        => 'nav',
		'container_class'  => 'post-nav',
		'container_id'     => 'post-nav',
		'prefix_tag'       => 'span',
		'prefix_class'     => 'nav-subtitle',
		'prev_prefix_text' => __( 'Previous', 'enlightenment' ),
		'next_prefix_text' => __( 'Next', 'enlightenment' ),
		'title_tag'        => 'span',
		'title_class'      => 'nav-title',
		'prev_title_text'  => '%title',
		'next_title_text'  => '%title',
		'date_tag'         => '',
		'date_class'       => '',
		'prev_date_text'   => '',
		'next_date_text'   => '',
		'pointer_tag'      => 'span',
		'pointer_class'    => 'pointer',
		'prev_pointer'     => '&larr;',
		'next_pointer'     => '&rarr;',
		'prev_format'      => is_rtl() ? '%2$s %3$s %4$s %1$s' : '%1$s %2$s %3$s %4$s',
		'next_format'      => is_rtl() ? '%1$s %2$s %3$s %4$s' : '%2$s %3$s %4$s %1$s',
		'custom_cb'        => '',
		'custom_cb_args'   => array(),
		'echo'             => true,
	);
	$defaults = apply_filters( 'enlightenment_post_nav_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( ! is_singular() ) {
		return false;
	}

	if ( 'post' != get_post_type() && ! get_post_type_object( get_post_type() )->has_archive ) {
		return false;
	}

	if ( is_callable( $args['custom_cb'] ) ) {
		return call_user_func_array( $args['custom_cb'], $args['custom_cb_args'] );
	}

	$pointer_extra_atts = array(
		'aria-hidden' => 'true',
		'role'        => 'presentation',
	);

	$prev_pointer  = enlightenment_open_tag( $args['pointer_tag'], $args['pointer_class'], '', $pointer_extra_atts );
	$prev_pointer .= $args['prev_pointer'];
	$prev_pointer .= enlightenment_close_tag( $args['pointer_tag'] );

	$prev_prefix   = enlightenment_open_tag( $args['prefix_tag'], $args['prefix_class'] );
	$prev_prefix  .= $args['prev_prefix_text'];
	$prev_prefix  .= enlightenment_close_tag( $args['prefix_tag'] );

	$prev_title    = enlightenment_open_tag( $args['title_tag'], $args['title_class'] );
	$prev_title   .= $args['prev_title_text'];
	$prev_title   .= enlightenment_close_tag( $args['title_tag'] );

	$prev_date     = enlightenment_open_tag( $args['date_tag'], $args['date_class'] );
	$prev_date    .= $args['prev_date_text'];
	$prev_date    .= enlightenment_close_tag( $args['date_tag'] );

	$prev_text     = sprintf( $args['prev_format'], $prev_pointer, $prev_prefix, $prev_title, $prev_date );

	$next_pointer  = enlightenment_open_tag( $args['pointer_tag'], $args['pointer_class'], '', $pointer_extra_atts );
	$next_pointer .= $args['next_pointer'];
	$next_pointer .= enlightenment_close_tag( $args['pointer_tag'] );

	$next_prefix   = enlightenment_open_tag( $args['prefix_tag'], $args['prefix_class'] );
	$next_prefix  .= $args['next_prefix_text'];
	$next_prefix  .= enlightenment_close_tag( $args['prefix_tag'] );

	$next_title    = enlightenment_open_tag( $args['title_tag'], $args['title_class'] );
	$next_title   .= $args['next_title_text'];
	$next_title   .= enlightenment_close_tag( $args['title_tag'] );

	$next_date     = enlightenment_open_tag( $args['date_tag'], $args['date_class'] );
	$next_date    .= $args['next_date_text'];
	$next_date    .= enlightenment_close_tag( $args['date_tag'] );

	$next_text     = sprintf( $args['next_format'], $next_pointer, $next_prefix, $next_title, $next_date );

	$args['prev_text'] = $prev_text;
	$args['next_text'] = $next_text;

	$output = get_the_post_navigation( $args );

	if ( ! empty( $args['container_class'] ) ) {
		$output = str_replace(
			'class="navigation ',
			sprintf( 'class="navigation %s ', esc_attr( trim( $args['container_class'] ) ) ),
			$output
		);
	}

	if ( ! empty( $args['container_id'] ) ) {
		$output = str_replace( ' role="navigation"', sprintf( ' id="%s" role="navigation"', esc_attr( $args['container_id'] ) ), $output );
	}

	if ( ! empty( $args['container'] ) && 'nav' != $args['container'] ) {
		$output = str_replace( '<nav ',  sprintf( '<%s ',  esc_attr( $args['container'] ) ), $output );
		$output = str_replace( '</nav>', sprintf( '</%s>', esc_attr( $args['container'] ) ), $output );
	}

	$output = apply_filters( 'enlightenment_post_nav', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_comments_nav( $args = null ) {
	$defaults = array(
		'container'       => 'nav',
		'container_class' => 'comments-nav',
		'container_id'    => 'comments-nav',
		'class'           => '',
		'link_container'  => 'div',
		'label_tag'       => 'span',
		'label_class'     => 'comments-nav-label',
		'prev_class'      => 'previous',
		'next_class'      => 'next',
		'pointer_tag'     => 'span',
		'prev_text'       => __( 'Older Comments', 'enlightenment' ),
		'next_text'       => __( 'Newer Comments', 'enlightenment' ),
		'pointer_class'   => 'pointer',
		'prev_pointer'    => is_rtl() ? '&larr;' : '&rarr;',
		'next_pointer'    => is_rtl() ? '&rarr;' : '&larr;',
		'paged'           => true,
		'type'            => 'plain',
		'custom_cb'       => '',
		'custom_cb_args'  => array(),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_comments_nav_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( is_callable( $args['custom_cb'] ) ) {
		return call_user_func_array( $args['custom_cb'], $args['custom_cb_args'] );
	}

	if ( 1 >= get_comment_pages_count() || ! get_option( 'page_comments' ) ) {
		return false;
	}

	$pointer_extra_atts = array(
		'aria-hidden' => 'true',
		'role'        => 'presentation',
	);

	/**
	 * The function paginate_links() uses the reverse logic of
	 * get_the_comments_navigation() for prev_text/next_text so we need to
	 * reverse the parameters too.
	**/
	if ( $args['paged'] ) {
		$prev_text = $args['prev_text'];
		$next_text = $args['next_text'];

		$args['prev_text'] = $next_text;
		$args['next_text'] = $prev_text;
	}

	$prev_text = '';
	$next_text = '';

	if ( is_rtl() ) {
		if ( ! empty( $args['prev_pointer'] ) ) {
			$prev_text .= enlightenment_open_tag( $args['pointer_tag'], $args['pointer_class'], '', $pointer_extra_atts );
			$prev_text .= $args['prev_pointer'];
			$prev_text .= enlightenment_close_tag( $args['pointer_tag'] );
		}

		if ( ! empty( $args['prev_pointer'] ) && ! empty( $args['prev_text'] ) ) {
			$prev_text .= ' ';
		}

		if ( ! empty( $args['prev_text'] ) ) {
			$prev_text .= enlightenment_open_tag( $args['label_tag'], $args['label_class'] );
			$prev_text .= $args['prev_text'];
			$prev_text .= enlightenment_close_tag( $args['label_tag'] );
		}

		if ( ! empty( $args['next_text'] ) ) {
			$next_text  = enlightenment_open_tag( $args['label_tag'], $args['label_class'] );
			$next_text .= $args['next_text'];
			$next_text .= enlightenment_close_tag( $args['label_tag'] );
		}

		if ( ! empty( $args['next_text'] ) && ! empty( $args['next_pointer'] ) ) {
			$next_text .= ' ';
		}

		if ( ! empty( $args['next_pointer'] ) ) {
			$next_text .= enlightenment_open_tag( $args['pointer_tag'], $args['pointer_class'], '', $pointer_extra_atts );
			$next_text .= $args['next_pointer'];
			$next_text .= enlightenment_close_tag( $args['pointer_tag'] );
		}
	} else {
		if ( ! empty( $args['prev_text'] ) ) {
			$prev_text .= enlightenment_open_tag( $args['label_tag'], $args['label_class'] );
			$prev_text .= $args['prev_text'];
			$prev_text .= enlightenment_close_tag( $args['label_tag'] );
		}

		if ( ! empty( $args['prev_pointer'] ) && ! empty( $args['prev_text'] ) ) {
			$prev_text .= ' ';
		}

		if ( ! empty( $args['prev_pointer'] ) ) {
			$prev_text .= enlightenment_open_tag( $args['pointer_tag'], $args['pointer_class'], '', $pointer_extra_atts );
			$prev_text .= $args['prev_pointer'];
			$prev_text .= enlightenment_close_tag( $args['pointer_tag'] );
		}

		if ( ! empty( $args['next_pointer'] ) ) {
			$next_text .= enlightenment_open_tag( $args['pointer_tag'], $args['pointer_class'], '', $pointer_extra_atts );
			$next_text .= $args['next_pointer'];
			$next_text .= enlightenment_close_tag( $args['pointer_tag'] );
		}

		if ( ! empty( $args['next_pointer'] ) && ! empty( $args['next_text'] ) ) {
			$next_text .= ' ';
		}

		if ( ! empty( $args['next_text'] ) ) {
			$next_text .= enlightenment_open_tag( $args['label_tag'], $args['label_class'] );
			$next_text .= $args['next_text'];
			$next_text .= enlightenment_close_tag( $args['label_tag'] );
		}
	}

	if ( $args['paged'] ) {
		$args['class'] .= ' comments-pagination';
		$args['class']  = trim( $args['class'] );

		/**
		 * The function paginate_links() uses the reverse logic of
		 * get_the_comments_navigation() for prev_text/next_text so we need to
		 * reverse the parameters too.
		**/
		$args['prev_text'] = $next_text;
		$args['next_text'] = $prev_text;

		$output = get_the_comments_pagination( $args );
	} else {
		$args['class'] .= ' comment-navigation';
		$args['class']  = trim( $args['class'] );

		$args['prev_text'] = $prev_text;
		$args['next_text'] = $next_text;

		$output = get_the_comments_navigation( $args );
	}

	if ( ! empty( $args['container_class'] ) ) {
		$output = str_replace(
			sprintf( 'class="navigation %s"', esc_attr( $args['class'] ) ),
			sprintf( 'class="navigation %s %s"', esc_attr( $args['class'] ), esc_attr( trim( $args['container_class'] ) ) ),
			$output
		);
	}

	if ( ! empty( $args['container_id'] ) ) {
		$output = str_replace( ' role="navigation"', sprintf( ' id="%s" role="navigation"', esc_attr( $args['container_id'] ) ), $output );
	}

	if ( ! empty( $args['container'] ) && 'nav' != $args['container'] ) {
		$output = str_replace( '<nav ',  sprintf( '<%s ',  esc_attr( $args['container'] ) ), $output );
		$output = str_replace( '</nav>', sprintf( '</%s>', esc_attr( $args['container'] ) ), $output );
	}

	$output = apply_filters( 'enlightenment_comments_nav', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
