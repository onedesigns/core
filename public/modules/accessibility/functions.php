<?php

function enlightenment_nav_menu_title( $args = null ) {
	$defaults = array(
		'container'       => 'h2',
		'container_class' => 'screen-reader-text',
		'text'            => __( 'Menu', 'enlightenment' ),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_nav_menu_title_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= esc_attr( $args['text'] );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_nav_menu_title', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}
