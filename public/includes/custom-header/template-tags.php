<?php

function enlightenment_custom_header_markup( $args = null ) {
    $defaults = array(
		'container'       => 'div',
		'container_class' => 'wp-custom-header',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_custom_header_markup_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

    ob_start();
    the_custom_header_markup();
    $output = ob_get_clean();

	if ( 'div' != $args['container'] ) {
	    $output = str_replace( '<div ',  sprintf( '<%s ',  esc_attr( $args['container'] ) ), $output );
	    $output = str_replace( '</div>', sprintf( '</%s>', esc_attr( $args['container'] ) ), $output );
	}

	if ( 'wp-custom-header' != $args['container_class'] ) {
	    $output = str_replace( 'class="wp-custom-header"', sprintf( 'class="%s"', esc_attr( $args['container_class'] ) ), $output );
	}

	$output = apply_filters( 'enlightenment_custom_header_markup', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}
