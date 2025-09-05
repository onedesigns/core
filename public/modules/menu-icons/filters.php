<?php

add_filter( 'current_theme_supports-enlightenment-menu-icons', 'enlightenment_filter_current_theme_supports', 10, 3 );

function enlightenment_menu_icons_item_element( $menu_item ) {
	$menu_item->icon = get_post_meta( $menu_item->ID, '_enlightenment_menu_item_icon', true );
    return $menu_item;
}
add_filter( 'wp_setup_nav_menu_item', 'enlightenment_menu_icons_item_element' );

function enlightenment_menu_icons_nav_menu_css_class( $classes, $item, $args ) {
	if ( '' != $item->icon ) {
		$classes[] = 'menu-item-has-icon';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'enlightenment_menu_icons_nav_menu_css_class', 10, 3 );

function enlightenment_menu_item_icon( $output, $item ) {
	if ( '' != $item->icon ) {
		$class  = sprintf( 'menu-item-icon %s', enlightenment_menu_icon_prefix() . $item->icon );
		
		if ( enlightenment_has_in_call_stack( array( 'WP_REST_Menu_Items_Controller', 'prepare_item_for_response' ) ) ) {
			$output = sprintf( '<i class="%1$s"><img class="%1$s" style="display: none;"></i> %2$s', esc_attr( $class ), $output );
		} else {
			$output = sprintf( '<span class="%s"></span> %s', esc_attr( $class ), $output );
		}
	}

	return $output;
}
add_filter( 'nav_menu_item_title', 'enlightenment_menu_item_icon', 10, 2 );

function enlightenment_navigation_link_block_menu_item_icon( $output) {
	$offset = strpos( $output, '<img class="menu-item-icon ' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, 'span', $offset + 1, 3 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '</span>', $offset + 1, 0 );

		$output = str_replace( ' wp-block-navigation-link ', ' wp-block-navigation-link has-icon ', $output );
		$output = str_replace( ' wp-block-navigation-link"', ' wp-block-navigation-link has-icon"', $output );
		$output = str_replace( ' wp-block-navigation-item ', ' wp-block-navigation-item has-icon ', $output );
		$output = str_replace( ' wp-block-navigation-item"', ' wp-block-navigation-item has-icon"', $output );
	}

	$offset = strpos( $output, 'aria-label="<img class="menu-item-icon ' );
	if ( false !== $offset ) {
		$start  = $offset + 12;
		$end    = strpos( $output, '> ', $start ) + 2;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	return $output;
}
add_filter( 'enlightenment_render_block_core_navigation_link', 'enlightenment_navigation_link_block_menu_item_icon' );
add_filter( 'enlightenment_render_block_core_navigation_submenu', 'enlightenment_navigation_link_block_menu_item_icon' );
