<?php

function enlightenment_open_sidebar_row() {
	if( ! current_theme_supports( 'enlightenment-unlimited-sidebars' ) ) {
		return;
	}

	if( ! current_theme_supports( 'enlightenment-grid-loop' ) ) {
		return;
	}

	$sidebars = get_theme_mod( 'sidebars' );
	$sidebar  = enlightenment_get_dynamic_sidebar_id();

	if ( isset( $sidebars[ $sidebar ] ) && isset( $sidebars[ $sidebar ]['grid'] ) ) {
		if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
			foreach ( $sidebars[ $sidebar ]['grid'] as $breakpoint => $grid ) {
				if ( 'inherit' == $grid ) {
					continue;
				}

				$atts = enlightenment_get_grid( $grid );

				if ( 1 < $atts['content_columns'] ) {
					enlightenment_open_row();
					break;
				}
			}
		} else {
			$grid = enlightenment_get_grid( $sidebars[ $sidebar ]['grid'] );

			if ( 1 < $grid['content_columns'] ) {
				enlightenment_open_row();
			}
		}
	}
}
add_action( 'enlightenment_before_widgets', 'enlightenment_open_sidebar_row', 999 );

function enlightenment_close_sidebar_row() {
	if ( ! current_theme_supports( 'enlightenment-unlimited-sidebars' ) ) {
		return;
	}

	if ( ! current_theme_supports( 'enlightenment-grid-loop' ) ) {
		return;
	}

	$sidebars = get_theme_mod( 'sidebars' );
	$sidebar  = enlightenment_get_dynamic_sidebar_id();

	if ( isset( $sidebars[ $sidebar ] ) && isset( $sidebars[ $sidebar ]['grid'] ) ) {
		if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
			foreach ( $sidebars[ $sidebar ]['grid'] as $breakpoint => $grid ) {
				if ( 'inherit' == $grid ) {
					continue;
				}

				$atts = enlightenment_get_grid( $grid );

				if ( 1 < $atts['content_columns'] ) {
					enlightenment_close_row();
					break;
				}
			}
		} else {
			$grid = enlightenment_get_grid( $sidebars[ $sidebar ]['grid'] );

			if ( 1 < $grid['content_columns'] ) {
				enlightenment_close_row();
			}
		}
	}
}
add_action( 'enlightenment_after_widgets', 'enlightenment_close_sidebar_row', 1 );
