<?php

function enlightenment_post_nav_thumbnail( $args ) {
    $args['prev_format'] = str_replace( '%2$s', '[post_thumbnail] %2$s', $args['prev_format'] );
	$args['next_format'] = str_replace( '%2$s', '[post_thumbnail] %2$s', $args['next_format'] );

	return $args;
}
add_filter( 'enlightenment_post_nav_args', 'enlightenment_post_nav_thumbnail' );

function enlightenment_post_nav_link_thumbnail( $output, $format, $link, $post ) {
    if ( ! has_post_thumbnail( $post ) ) {
        return str_replace( '[post_thumbnail]', '', $output );
	}

	if ( false === strpos( $output, '[post_thumbnail]' ) ) {
		return $output;
	}

    $args = array(
        'thumbnail_wrap_tag'   => '',
        'thumbnail_wrap_class' => '',
    );
    $args = apply_filters( 'enlightenment_post_nav_link_thumbnail_args', $args );

    $post_thumbnail  = enlightenment_open_tag( $args['thumbnail_wrap_tag'], $args['thumbnail_wrap_class'] );
	$post_thumbnail .= get_the_post_thumbnail( $post, 'thumbnail' );
	$post_thumbnail .= enlightenment_close_tag( $args['thumbnail_wrap_tag'] );

    $output = str_replace( '[post_thumbnail]', $post_thumbnail, $output );

	return $output;
}
add_filter( 'previous_post_link', 'enlightenment_post_nav_link_thumbnail', 10, 4 );
add_filter( 'next_post_link', 'enlightenment_post_nav_link_thumbnail', 10, 4 );
