<?php

function enlightenment_accessibility_nav_menu_args( $args ) {
	if( '' != $args['theme_location'] ) {
		$args['items_wrap'] = enlightenment_nav_menu_title( array( 'echo' => false ) ) . $args['items_wrap'];
	}

	return $args;
}
add_filter( 'enlightenment_wp_nav_menu_args', 'enlightenment_accessibility_nav_menu_args', 22 );

function nav_menu_link_aria_roles( $atts, $item ) {
	if( in_array( 'dropdown', (array) $item->classes ) ) {
		$atts['role']          = 'button';
		$atts['aria-expanded'] = 'false';
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'nav_menu_link_aria_roles', 10, 2 );

function enlightenment_submenu_aria_roles( $atts ) {
	$atts .= ' role="menu"';
	return $atts;
}
add_filter( 'enlightenment_submenu_extra_atts', 'enlightenment_submenu_aria_roles' );

function enlightenment_accessibility_search_form_args( $args ) {
	$defaults = array(
		'label_class' => 'screen-reader-text',
		'label'       => __( 'Search for:', 'enlightenment' ),
	);
	$defaults = apply_filters( 'enlightenment_accessibility_search_form_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$args['before'] .= sprintf(
		'<label%s%s>',
		( ! empty( $args['input_id'] ) ? ' for="' . esc_attr( $args['input_id'] ) . '"' : '' ),
		enlightenment_class( $args['label_class'] )
	);
	$args['before'] .= $args['label'];
	$args['before'] .= '</label>';

	return $args;
}
add_filter( 'enlightenment_search_form_args', 'enlightenment_accessibility_search_form_args' );
