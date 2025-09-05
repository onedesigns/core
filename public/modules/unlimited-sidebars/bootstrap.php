<?php

function enlightenment_unlimited_sidebars_open_widgets_container() {
	$locations = enlightenment_sidebar_locations();
	$template  = enlightenment_current_sidebars_template();
	$location  = enlightenment_current_sidebar_name();

	if( true === $locations[ $template ][ $location ]['contained'] ) {
		return;
	}

	$sidebars = enlightenment_registered_sidebars();
	$sidebar  = enlightenment_get_dynamic_sidebar_id();

	if ( $sidebars[ $sidebar ]['contain_widgets'] ) {
		enlightenment_open_container();
	} else {
		enlightenment_open_container_fluid();
	}
}
add_action( 'enlightenment_before_widgets', 'enlightenment_unlimited_sidebars_open_widgets_container', 2 );

function enlightenment_unlimited_sidebars_close_widgets_container() {
	$locations = enlightenment_sidebar_locations();
	$template  = enlightenment_current_sidebars_template();
	$location  = enlightenment_current_sidebar_name();

	if( true === $locations[ $template ][ $location ]['contained'] ) {
		return;
	}

	$sidebars = enlightenment_registered_sidebars();
	$sidebar  = enlightenment_get_dynamic_sidebar_id();

	if ( $sidebars[ $sidebar ]['contain_widgets'] ) {
		enlightenment_close_container();
	} else {
		enlightenment_close_container_fluid();
	}
}
add_action( 'enlightenment_after_widgets', 'enlightenment_unlimited_sidebars_close_widgets_container', 999 );

function enlightenment_bootstrap_remove_primary_sidebar_in_full_width( $is_active_sidebar, $index ) {
	if( current_theme_supports( 'enlightenment-custom-layouts' ) && 'primary' == enlightenment_current_sidebar_name() ) {
		remove_filter( 'is_active_sidebar', 'enlightenment_remove_primary_sidebar_in_full_width', 5, 2 );

		$layouts = enlightenment_current_layout();
		$remove  = true;

		foreach( $layouts as $breakpoint => $layout ) {
			if( 'full-width' != $layout ) {
				$remove = false;
			}
		}

		if( $remove ) {
			return false;
		}

		return $is_active_sidebar;
	}
	return $is_active_sidebar;
}
add_filter( 'is_active_sidebar', 'enlightenment_bootstrap_remove_primary_sidebar_in_full_width', 4, 2 );
