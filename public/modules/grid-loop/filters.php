<?php

function enlightenment_archive_grids_merge_theme_options( $grids ) {
	$options = get_theme_mod( 'grids' );

	if( ! is_array( $options ) ) {
		$options = array();
	}

	return array_merge( $grids, $options );
}
add_filter( 'enlightenment_archive_grids', 'enlightenment_archive_grids_merge_theme_options', 30 );

function enlightenment_grid_active_body_class( $classes ) {
	if( is_singular() || is_404() ) {
		return $classes;
	}

	$grid = enlightenment_get_grid( enlightenment_current_grid() );

	if( 1 < $grid['content_columns'] ) {
		$classes[] = apply_filters( 'enlightenment_grid_active_body_class', 'grid-active' );
	}

	return $classes;
}
add_filter( 'body_class', 'enlightenment_grid_active_body_class' );

function enlightenment_set_grid_body_class( $classes ) {
	if( is_singular() || is_404() ) {
		return $classes;
	}

	$grid = enlightenment_get_grid( enlightenment_current_grid() );

	if( ! empty( $grid['body_class'] ) ) {
		$classes[] = $grid['body_class'];
	}

	return $classes;
}
add_filter( 'body_class', 'enlightenment_set_grid_body_class' );

function enlightenment_set_grid_content_class( $args ) {
	if( is_singular() || is_404() ) {
		return $args;
	}

	$grid = enlightenment_get_grid( enlightenment_current_grid() );

	if( ! empty( $grid['content_class'] ) ) {
		$args['class'] .= ' ' . $grid['content_class'];
	}

	return $args;
}
add_filter( 'enlightenment_page_content_class_args', 'enlightenment_set_grid_content_class' );

function enlightenment_set_grid_entry_class( $classes ) {
	if( is_singular() || is_404() ) {
		return $classes;
	}

	$grid = enlightenment_get_grid( enlightenment_current_grid() );

	if( enlightenment_is_lead_post() && ! empty( $grid['full_width_class'] ) ) {
		$classes[] = $grid['full_width_class'];
		return $classes;
	}

	if( ! empty( $grid['entry_class'] ) ) {
		$classes[] = $grid['entry_class'];
	}

	return $classes;
}
add_filter( 'post_class', 'enlightenment_set_grid_entry_class' );

function enlightenment_grid_post_class( $classes ) {
	if( is_singular() || is_404() ) {
		return $classes;
	}

	$grid = enlightenment_get_grid( enlightenment_current_grid() );

	if( enlightenment_is_lead_post() ) {
		$classes[] = apply_filters( 'enlightenment_lead_post_class', 'entry-lead' );
	} elseif( 1 < $grid['content_columns'] ) {
		global $enlightenment_post_counter;

		$classes[] = apply_filters( 'enlightenment_teaser_post_class', 'entry-teaser' );

		$teaser_count = $enlightenment_post_counter - enlightenment_current_lead_posts();

		if( 0 == $teaser_count % 2 ) {
			$classes[] = apply_filters( 'enlightenment_teaser_even_class', 'teaser-even' );
		} else {
			$classes[] = apply_filters( 'enlightenment_teaser_odd_class', 'teaser-odd' );
		}

		$teaser_row_position = $teaser_count % $grid['content_columns'];

		if( 0 == $teaser_row_position ) {
			$teaser_row_position = $grid['content_columns'];
		}

		$classes[] = apply_filters( 'enlightenment_teaser_row_count_class', 'teaser-row-pos-' . $teaser_row_position );
	}

	return $classes;
}
add_filter( 'post_class', 'enlightenment_grid_post_class' );

function enlightenment_call_masonry_script( $deps ) {
	$deps[] = 'masonry';

	return $deps;
}
add_filter( 'enlightenment_call_js', 'enlightenment_call_masonry_script' );
