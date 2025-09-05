<?php

function enlightenment_comment( $comment, $args, $depth ) {
	$defaults = array(
		'comment_class'              => 'comment-body' . ( empty( $args['has_children'] ) ? '' : ' parent' ),
		'comment_id'                 => 'comment-' . get_comment_ID(),
		'comment_extra_atts'         => '',
		'header_tag'                 => current_theme_supports( 'html5', 'comment-list' ) ? 'header' : 'div',
		'header_class'               => 'comment-header',
		'comment_content_tag'        => 'div',
		'comment_content_class'      => 'comment-content',
		'comment_content_extra_atts' => '',
		'footer_tag'                 => current_theme_supports( 'html5', 'comment-list' ) ? 'footer' : 'div',
		'footer_class'               => 'comment-footer',
		'depth'                      => $depth,
	);
	$defaults = apply_filters( 'enlightenment_comment_args', $defaults, $comment );
	$args     = wp_parse_args( $args, $defaults );

	$GLOBALS['comment'] = $comment;
	extract( $args, EXTR_SKIP );

	if( 'ul' == $args['style'] || 'ol' == $args['style'] ) {
		$args['style'] = 'li';
	} else {
		$args['style'] = current_theme_supports( 'html5', 'comment-list' ) ? 'article' : 'div';
	}

	do_action( 'enlightenment_before_comment', $comment, $args );
	echo enlightenment_open_tag(
		$args['style'],
		join( ' ', get_comment_class( $args['comment_class'] ) ),
		$args['comment_id'],
		$args['comment_extra_atts']
	);

	do_action( 'enlightenment_before_comment_header', $comment, $args );
	if( has_action( 'enlightenment_comment_header' ) ) {
		echo enlightenment_open_tag( $args['header_tag'], $args['header_class'] );
		do_action( 'enlightenment_comment_header', $comment, $args );
		echo enlightenment_close_tag( $args['header_tag'] );
	}
	do_action( 'enlightenment_after_comment_header', $comment, $args );

	do_action( 'enlightenment_before_comment_content', $comment, $args );
	if( has_action( 'enlightenment_comment_content' ) ) {
		echo enlightenment_open_tag(
			$args['comment_content_tag'],
			$args['comment_content_class'],
			'',
			$args['comment_content_extra_atts']
		);
		do_action( 'enlightenment_comment_content', $comment, $args );
		echo enlightenment_close_tag( $args['comment_content_tag'] );
	}
	do_action( 'enlightenment_after_comment_content', $comment, $args );

	do_action( 'enlightenment_before_comment_footer', $comment, $args );
	if( has_action( 'enlightenment_comment_footer' ) ) {
		echo enlightenment_open_tag( $args['footer_tag'], $args['footer_class'] );
		do_action( 'enlightenment_comment_footer', $comment, $args );
		echo enlightenment_close_tag( $args['footer_tag'] );
	}
	do_action( 'enlightenment_after_comment_footer', $comment, $args );
}
