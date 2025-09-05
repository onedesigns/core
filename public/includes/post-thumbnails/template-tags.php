<?php

function enlightenment_post_thumbnail( $args = null ) {
	$defaults = array(
		'container'       => 'figure',
		'container_class' => 'entry-media',
		'post'            => null,
		'size'            => 'enlightenment-post-thumb',//'post-thumbnail',
		'attr'            => array(),
		'default'         => null,
		'link_to_post'    => ! is_singular() || enlightenment_is_custom_loop(),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_post_thumbnail_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	if ( ! has_post_thumbnail() && empty( $args['default'] ) ) {
		return;
	}

	$args['attr']['alt']   = the_title_attribute( array('echo' => 0 ) );
	$args['attr']['title'] = the_title_attribute( array('echo' => 0 ) );

	$html = get_the_post_thumbnail( $args['post'], $args['size'], $args['attr'] );

	if ( empty( $html ) ) {
		if ( empty( $args['default'] ) ) {
			return;
		}

		$html = $args['default'];
	}

	if ( $args['link_to_post'] ) {
		$html = sprintf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( array( 'echo' => 0 ) ), $html );
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= $html;
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_post_thumbnail', $output, $args );

	if ( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
