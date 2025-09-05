<?php

/*function enlightenment_bootstrap_grid_columns( $columns ) {
	$columns['onecol']['entry_class']        = '';
	$columns['onecol']['full_width_class']   = '';

	$columns['twocol']['entry_class']        = 'col-sm-6';
	$columns['twocol']['full_width_class']   = 'col-md-12';

	$columns['threecol']['entry_class']      = 'col-sm-4';
	$columns['threecol']['full_width_class'] = 'col-md-12';

	$columns['fourcol']['entry_class']       = 'col-md-3 col-sm-6';
	$columns['fourcol']['full_width_class']  = 'col-md-12';

	return $columns;
}
add_filter( 'enlightenment_grid_columns', 'enlightenment_bootstrap_grid_columns' );*/

function enlightenment_bootstrap_grid_columns( $columns ) {
	$columns['onecol']['body_class']         = '';
	$columns['onecol']['content_class']      = '';
	$columns['onecol']['entry_class']        = 'col%s-12';
	$columns['onecol']['full_width_class']   = 'col%s-12';

	$columns['twocol']['body_class']         = 'grid-columns%s-2';
	$columns['twocol']['content_class']      = 'content-columns%s-2';
	$columns['twocol']['entry_class']        = 'col%s-6';
	$columns['twocol']['full_width_class']   = 'col-md-12';

	$columns['threecol']['body_class']       = 'grid-columns%s-3';
	$columns['threecol']['content_class']    = 'content-columns%s-3';
	$columns['threecol']['entry_class']      = 'col%s-4';
	$columns['threecol']['full_width_class'] = 'col%s-12';

	$columns['fourcol']['body_class']        = 'grid-columns%s-4';
	$columns['fourcol']['content_class']     = 'content-columns%s-4';
	$columns['fourcol']['entry_class']       = 'col%s-3';
	$columns['fourcol']['full_width_class']  = 'col%s-12';

	return $columns;
}
add_filter( 'enlightenment_grid_columns', 'enlightenment_bootstrap_grid_columns' );

function enlightenment_bootstrap_default_grid( $grid ) {
	return array(
		'smartphone-portrait'  => 'onecol',
		'smartphone-landscape' => 'inherit',
		'tablet-portrait'      => 'inherit',
		'tablet-landscape'     => 'inherit',
		'desktop-laptop'       => 'inherit',
	);
}
add_filter( 'enlightenment_default_grid', 'enlightenment_bootstrap_default_grid' );

remove_filter( 'enlightenment_archive_grids', 'enlightenment_archive_grids_merge_theme_options', 30 );

function enlightenment_bootstrap_archive_grids_merge_theme_options( $grids ) {
	$options = get_theme_mod( 'grids' );

	if ( ! is_array( $options ) ) {
		$options = array();
	}

	foreach ( $options as $template => $atts ) {
		if ( ! isset( $grids[ $template ] ) ) {
			$grids[ $template ] = $atts;
		}

		if ( ! empty( $atts['grid'] ) ) {
			$grids[ $template ]['grid'] = array_merge( $grids[ $template ]['grid'], $atts['grid'] );
		}

		if ( isset( $atts['lead_posts'] ) ) {
			$grids[ $template ]['lead_posts'] = $atts['lead_posts'];
		}
	}

	return $grids;
}
add_filter( 'enlightenment_archive_grids', 'enlightenment_bootstrap_archive_grids_merge_theme_options', 30 );

function enlightenment_bootstrap_is_grid_active() {
	$grids  = enlightenment_current_grid();
	$active = false;

	foreach( $grids as $breakpoint => $grid ) {
		if( 'inherit' == $grid ) {
			continue;
		}

		$atts = enlightenment_get_grid( $grid );

		if( 1 != $atts['content_columns'] ) {
			$active = true;
			break;
		}
	}

	return apply_filters( 'enlightenment_bootstrap_is_grid_active', $active );
}

function enlightenment_remove_grid_classes() {
	remove_filter( 'body_class', 'enlightenment_set_grid_body_class' );
	remove_filter( 'body_class', 'enlightenment_grid_active_body_class' );
	remove_filter( 'enlightenment_page_content_class_args', 'enlightenment_set_grid_content_class' );
	remove_filter( 'post_class', 'enlightenment_set_grid_entry_class' );
	remove_filter( 'post_class', 'enlightenment_grid_post_class' );
}
add_action( 'init', 'enlightenment_remove_grid_classes' );

function enlightenment_bootstrap_grid_active_body_class( $classes ) {
	if( is_singular() || is_404() || ! enlightenment_bootstrap_is_grid_active() ) {
		return $classes;
	}

	$class = apply_filters( 'enlightenment_bootstrap_grid_active_body_class', 'grid%s-active' );

	$grids = enlightenment_current_grid();

	foreach( $grids as $breakpoint => $grid ) {
		if( 'inherit' == $grid ) {
			continue;
		}

		$atts = enlightenment_get_grid( $grid );

		if( 1 < $atts['content_columns'] ) {
			$prefix    = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );
			$classes[] = sprintf( $class, $prefix );
		}
	}

	return $classes;
}
add_filter( 'body_class', 'enlightenment_bootstrap_grid_active_body_class' );

function enlightenment_bootstrap_set_grid_body_class( $classes ) {
	if( is_singular() || is_404() ) {
		return $classes;
	}

	$grids = enlightenment_current_grid();

	foreach( $grids as $breakpoint => $grid ) {
		if( 'inherit' == $grid ) {
			continue;
		}

		$atts = enlightenment_get_grid( $grid );

		if( ! empty( $atts['body_class'] ) ) {
			$prefix    = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );
			$classes[] = sprintf( $atts['body_class'], $prefix );
		}
	}

	return $classes;
}
add_filter( 'body_class', 'enlightenment_bootstrap_set_grid_body_class' );

function enlightenment_bootstrap_set_grid_content_class( $args ) {
	if( is_singular() || is_404() ) {
		return $args;
	}

	$grids = enlightenment_current_grid();

	foreach( $grids as $breakpoint => $grid ) {
		if( 'inherit' == $grid ) {
			continue;
		}

		$atts = enlightenment_get_grid( $grid );

		if( ! empty( $atts['content_class'] ) ) {
			$prefix         = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );
			$args['class'] .= ' ' . sprintf( $atts['content_class'], $prefix );
		}
	}

	return $args;
}
add_filter( 'enlightenment_page_content_class_args', 'enlightenment_bootstrap_set_grid_content_class' );

/*function enlightenment_grid_loop_content_row() {
	if( is_singular() || is_admin() ) {
		return;
	}

	$grid = enlightenment_get_grid( enlightenment_current_grid() );

	if( 1 != $grid['content_columns'] ) {
		add_action( 'enlightenment_before_entries', 'enlightenment_open_row', 999 );
		add_action( 'enlightenment_after_entries', 'enlightenment_close_row', 1 );
	}
}
add_action( 'wp', 'enlightenment_grid_loop_content_row' );*/

function enlightenment_grid_loop_content_row() {
	if( is_singular() || is_admin() || ! enlightenment_bootstrap_is_grid_active() ) {
		return;
	}

	add_action( 'enlightenment_before_entries', 'enlightenment_open_row', 999 );
	add_action( 'enlightenment_after_entries', 'enlightenment_close_row', 1 );
}
add_action( 'wp', 'enlightenment_grid_loop_content_row' );

function enlightenment_bootstrap_current_lead_posts( $lead_posts ) {
	$options = enlightenment_archive_grids();
	$grids   = enlightenment_current_grid();

	if( ! enlightenment_bootstrap_is_grid_active() ) {
		$lead_posts = get_option( 'posts_per_page' );
	} elseif( is_home() && ! is_page() ) {
		$lead_posts = $options['post']['lead_posts'];
	} elseif( is_author() ) {
		$lead_posts = $options['author']['lead_posts'];
	} elseif( is_date() ) {
		$lead_posts = $options['date']['lead_posts'];
	} elseif( is_post_type_archive() ) {
		$lead_posts = $options[ get_query_var( 'post_type' ) ]['lead_posts'];
	} elseif( is_category() ) {
		$lead_posts = $options['category']['lead_posts'];
	} elseif( is_tag() ) {
		$lead_posts = $options['post_tag']['lead_posts'];
	} elseif( is_tax( 'post_format' ) ) {
		$lead_posts = $options['post']['lead_posts'];
	} elseif( is_tax() ) {
		$lead_posts = $options[ get_queried_object()->taxonomy ]['lead_posts'];
	} elseif( is_search() ) {
		$lead_posts = $options['post']['lead_posts'];
	}

	return apply_filters( 'enlightenment_bootstrap_current_lead_posts', $lead_posts );
}
add_filter( 'enlightenment_pre_current_lead_posts', 'enlightenment_bootstrap_current_lead_posts' );

function enlightenment_bootstrap_is_lead_post( $is_lead_post ) {
	if ( is_admin() ) {
		$is_lead_post = false;
	} elseif ( is_singular() ) {
		$is_lead_post = true;
	} else {
		$is_lead_post = true;
		$grids        = enlightenment_current_grid();
		$prev         = '';

		foreach( $grids as $breakpoint => $grid ) {
			if ( 'inherit' == $grid ) {
				continue;
			}

			$atts = enlightenment_get_grid( $grid );

			if ( 1 == $atts['content_columns'] ) {
				continue;
			}

			global $enlightenment_post_counter, $enlightenment_custom_post_counter;

			if ( ! isset( $enlightenment_post_counter ) && ! isset( $enlightenment_custom_post_counter ) ) {
				_doing_it_wrong( __FUNCTION__, 'This function can only be called inside The Loop', '' );
				return;
			}

			$counter      = isset( $enlightenment_post_counter ) ? $enlightenment_post_counter : $enlightenment_custom_post_counter;
			$lead_posts   = enlightenment_current_lead_posts();
			$is_lead_post = ! is_paged() && $lead_posts >= $counter;

			if ( ! $is_lead_post ) {
				break;
			}
		}
	}

	return apply_filters( 'enlightenment_bootstrap_is_lead_post', $is_lead_post );
}
add_filter( 'enlightenment_pre_is_lead_post', 'enlightenment_bootstrap_is_lead_post' );

function enlightenment_open_grid_entry_container() {
	if ( is_singular() || is_404() || ! enlightenment_bootstrap_is_grid_active() ) {
		return;
	}

	$grids = enlightenment_current_grid();
	$class = '';

	foreach ( $grids as $breakpoint => $grid ) {
		if ( 'inherit' == $grid ) {
			continue;
		}

		$atts   = enlightenment_get_grid( $grid );
		$prefix = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );

		if ( enlightenment_is_lead_post() && ! empty( $atts['full_width_class'] ) ) {
			$class .= ' ' . sprintf( $atts['full_width_class'], $prefix );
		} elseif ( ! empty( $atts['entry_class'] ) ) {
			$class .= ' ' . sprintf( $atts['entry_class'], $prefix );
		}
	}

	$class = trim( $class );

	echo enlightenment_open_tag( 'div', $class );
}
add_action( 'enlightenment_before_entry', 'enlightenment_open_grid_entry_container', 999 );

function enlightenment_close_grid_entry_container() {
	if ( is_singular() || is_404() || ! enlightenment_bootstrap_is_grid_active() ) {
		return;
	}

	if ( enlightenment_is_lead_post() && ! empty( $atts['full_width_class'] ) ) {
		enlightenment_close_div();
	} else {
		$grids  = enlightenment_current_grid();

		foreach ( $grids as $breakpoint => $grid ) {
			if ( 'inherit' == $grid ) {
				continue;
			}

			$atts = enlightenment_get_grid( $grid );

			if ( ! empty( $atts['entry_class'] ) ) {
				enlightenment_close_div();
				break;
			}
		}
	}
}
add_action( 'enlightenment_after_entry', 'enlightenment_close_grid_entry_container', 1 );

function enlightenment_bootstrap_grid_post_class( $classes ) {
	if ( is_singular() || is_404() || ! enlightenment_bootstrap_is_grid_active() ) {
		return $classes;
	}

	if ( ! isset( $GLOBALS['enlightenment_post_counter'] ) ) {
		return $classes;
	}

	if ( enlightenment_is_lead_post() ) {
		$classes[] = apply_filters( 'enlightenment_lead_post_class', 'entry-lead' );
	} else {
		global $enlightenment_post_counter;

		$classes[] = apply_filters( 'enlightenment_teaser_post_class', 'entry-teaser' );

		$teaser_count = $enlightenment_post_counter - enlightenment_current_lead_posts();

		if ( 0 == $teaser_count % 2 ) {
			$classes[] = apply_filters( 'enlightenment_teaser_even_class', 'teaser-even' );
		} else {
			$classes[] = apply_filters( 'enlightenment_teaser_odd_class', 'teaser-odd' );
		}

		$grids  = enlightenment_current_grid();

		foreach ( $grids as $breakpoint => $grid ) {
			if ( 'inherit' == $grid ) {
				continue;
			}

			$atts   = enlightenment_get_grid( $grid );
			$pos    = $teaser_count % $atts['content_columns'];
			$prefix = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );

			if ( 0 == $pos ) {
				$pos = $atts['content_columns'];
			}

			$classes[] = apply_filters( 'enlightenment_teaser_row_count_class', sprintf( 'teaser%s-row-pos-', $prefix ) . $pos );
		}
	}

	return $classes;
}
add_filter( 'post_class', 'enlightenment_bootstrap_grid_post_class' );

function enlightenment_bootstrap_masonry_script_args( $args ) {
	$args['masonry_args']['container'] = '.entries-list > .row';

	$grids = array_reverse( enlightenment_current_grid() );

	foreach ( $grids as $breakpoint => $grid ) {
		if ( 'inherit' == $grid ) {
			continue;
		}

		$atts = enlightenment_get_grid( $grid );

		if ( ! empty( $atts['entry_class'] ) ) {
			$class  = explode( ' ', $atts['entry_class'] )[0];
			$prefix = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );
			$class  = sprintf( $class, $prefix );

			$args['masonry_args']['columnWidth']  = $args['masonry_args']['container'] . ' > .' . $class;

			break;
		}
	}

	$atts = enlightenment_get_grid( $grids['smartphone-portrait'] );

	if ( ! empty( $atts['entry_class'] ) ) {
		$class  = explode( ' ', $atts['entry_class'] )[0];
		$prefix = enlightenment_bootstrap_get_breakpoint_prefix( 'smartphone-portrait' );
		$class  = sprintf( $class, $prefix );

		$args['masonry_args']['itemSelector'] = $args['masonry_args']['container'] . ' > .' . $class;
	}

	return $args;
}
add_filter( 'enlightenment_masonry_script_args', 'enlightenment_bootstrap_masonry_script_args' );
