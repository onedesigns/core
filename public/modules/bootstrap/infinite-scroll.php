<?php

function enlightenment_bootstrap_infinite_scroll_script_args( $args ) {
	if ( ! current_theme_supports( 'enlightenment-grid-loop' ) ) {
		return $args;
	}
	
	$grids = enlightenment_current_grid();

	foreach ( $grids as $breakpoint => $grid ) {
		if ( 'inherit' == $grid ) {
			continue;
		}

		$atts = enlightenment_get_grid( $grid );

		if ( 1 != $atts['content_columns'] ) {
			$entry_class  = explode( ' ', $atts['entry_class'] );
			$prefix       = enlightenment_bootstrap_get_breakpoint_prefix( $breakpoint );
			$itemSelector = '.' . sprintf( $entry_class[0], $prefix );

			$args['contentSelector'] = '.entries-list > .row';
			$args['itemSelector']    = $args['contentSelector'] . ' > ' . $itemSelector;

			break;
		}
	}

	return $args;
}
add_filter( 'enlightenment_infinite_scroll_script_args', 'enlightenment_bootstrap_infinite_scroll_script_args' );
