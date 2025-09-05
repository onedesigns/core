<?php

function enlightenment_enqueue_masonry_script() {
	if( is_singular() || is_404() ) {
		return;
	}

	if( ! enlightenment_bootstrap_is_grid_active() ) {
		return;
	}

	$grids  = enlightenment_current_grid();

	foreach( $grids as $breakpoint => $grid ) {
		$atts = enlightenment_get_grid( $grid );

		if( ! empty( $atts['entry_class'] ) ) {
			$entry_class  = explode( ' ', $atts['entry_class'] );
			$prefix       = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );
			$column_width = '.' . sprintf( $entry_class[0], $prefix );
			break;
		}
	}

	$defaults = array(
		'masonry'      => true,
		'masonry_args' => array(
			'container'          => '.entries-list',
			'columnWidth'        => '.entries-list ' . $column_width,
			'itemSelector'       => '.entries-list ' . $column_width,
			'transitionDuration' => '0.7s',
		),
	);

	$args = apply_filters( 'enlightenment_masonry_script_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$theme_support_args = get_theme_support( 'enlightenment-grid-loop' );

	if( is_array( $theme_support_args ) ) {
		$theme_support_args = array_shift( $theme_support_args );
	} else {
		$theme_support_args = array();
	}

	$args = wp_parse_args( $theme_support_args, $args );

	if( $args['masonry'] ) {
		wp_enqueue_script( 'masonry' );
		wp_localize_script( 'masonry', 'enlightenment_masonry_args', $args['masonry_args'] );
	}
}
add_action( 'wp_enqueue_scripts', 'enlightenment_enqueue_masonry_script' );
