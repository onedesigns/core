<?php

function enlightenment_menu_descriptions_nav_menu_css_class( $classes, $item, $args ) {
	if ( '' != $item->description ) {
		$classes[] = 'menu-item-has-description';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'enlightenment_menu_descriptions_nav_menu_css_class', 10, 3 );

function enlightenment_menu_item_description( $output, $item ) {
	if ( '' != $item->description ) {
		if ( enlightenment_has_in_call_stack( array( 'WP_REST_Menu_Items_Controller', 'prepare_item_for_response', ) ) ) {
			$output .= sprintf( '<br /><small class="menu-item-description" data-description="%1$s"><img class="br" style="display: none;"><img class="menu-item-description" style="display: none;" alt="%1$s"></small>', esc_attr( $item->description ) );
		} else {
			$output .= sprintf( '<br /><span class="menu-item-description">%s</span>', esc_html( $item->description ) );
		}
	}

	return $output;
}
add_filter( 'nav_menu_item_title', 'enlightenment_menu_item_description', 10, 2 );

function enlightenment_navigation_link_block_menu_item_description( $output) {
	$offset = strpos( $output, '<img class="br"><img class="menu-item-description" alt="' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, '<br /><span class="menu-item-description">', $offset, 56 );
		$offset += 42;
		$offset  = strpos( $output, '">', $offset );
		$output  = substr_replace( $output, '</span>', $offset, 2 );

		$output = str_replace( ' wp-block-navigation-link ', ' wp-block-navigation-link has-description ', $output );
		$output = str_replace( ' wp-block-navigation-link"', ' wp-block-navigation-link has-description"', $output );
		$output = str_replace( ' wp-block-navigation-item ', ' wp-block-navigation-item has-description ', $output );
		$output = str_replace( ' wp-block-navigation-item"', ' wp-block-navigation-item has-description"', $output );
	}

	$offset = strpos( $output, '<img class="br"><img class="menu-item-description" alt="' );
	if ( false !== $offset ) {
		$output  = substr_replace( $output, '', $offset, 56 );

		$start  = $offset;
		$end    = strpos( $output, '">', $start ) + 2;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	return $output;
}
add_filter( 'enlightenment_render_block_core_navigation_link', 'enlightenment_navigation_link_block_menu_item_description' );
add_filter( 'enlightenment_render_block_core_navigation_submenu', 'enlightenment_navigation_link_block_menu_item_description' );
