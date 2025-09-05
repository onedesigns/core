<?php

function enlightenment_skip_link( $args = false ) {
	$defaults = array(
		'container_class' => 'skip-link screen-reader-text',
		'target'          => '#content',
		'text'            => __( 'Skip to content', 'enlightenment' ),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_skip_link_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( 'a', $args['container_class'], '', array(
		'href'  => esc_url( $args['target'] ),
		'title' => esc_attr( $args['text'] ),
	) );
	$output .= wp_kses( $args['text'], 'strip' );
	$output .= enlightenment_close_tag( 'a' );

	$output = apply_filters( 'enlightenment_skip_link', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}
add_action( 'enlightenment_before_site', 'enlightenment_skip_link', 1 );
