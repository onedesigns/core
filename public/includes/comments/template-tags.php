<?php

function enlightenment_comments_template( $args = null ) {
	$defaults = array(
		'file'              => '/comments.php',
		'separate_comments' => false,
		'withcomments'      => true,
	);
	$defaults = apply_filters( 'enlightenment_comments_template_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	if( $args['withcomments'] ) {
		global $withcomments;
		$withcomments = true;
	}

	comments_template( $args['file'], $args['separate_comments'] );
}

function enlightenment_comments_password_notice( $args = null ) {
	$defaults = array(
		'container'            => 'aside',
		'container_id'         => 'comments',
		'container_class'      => '',
		'notice_tag'           => 'p',
		'notice_class'         => 'nocomments',
		'notice_text'          => __( 'This post is password protected. Enter the password to view comments.', 'enlightenment' ),
		'only_if_have_coments' => true,
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_comments_password_notice_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if ( $args['only_if_have_coments'] && ! have_comments() ) {
		return false;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_id'], $args['container_class'] );
	$output .= enlightenment_open_tag( $args['notice_tag'], $args['notice_class'] );
	$output .= esc_attr( $args['notice_text'] );
	$output .= enlightenment_close_tag( $args['notice_tag'] );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_comments_password_notice', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_comments_number( $args = null ) {
	$defaults = array(
		'container'       => 'h3',
		'container_class' => 'comments-title',
		'container_id'    => '',
		'format'          => __( '%1$s for &ldquo;%2$s&rdquo;', 'enlightenment' ),
		'zero'            => __( 'No Comments', 'enlightenment' ),
		'one'             => __( '1 Comment', 'enlightenment' ),
		'more'            => __( '% Comments', 'enlightenment' ),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_comments_number_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$comments_number = get_comments_number_text( $args['zero'], $args['one'], $args['more'] );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= sprintf( $args['format'], $comments_number, get_the_title() );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_comments_number', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_list_comments( $args = null ) {
	$defaults = array(
		'class'             => 'comment-list',
		'walker'            => new Enlightenment_Walker_Comment,
		'max_depth'         => '',
		'style'             => '',
		'callback'          => 'enlightenment_comment',
		'end-callback'      => null,
		'type'              => 'all',
		'page'              => '',
		'per_page'          => '',
		'avatar_size'       => 32,
		'reverse_top_level' => null,
		'reverse_children'  => '',
		'format'            => current_theme_supports( 'html5', 'comment-list' ) ? 'html5' : 'xhtml',
		'short_ping'        => false,
		'echo'              => true,
	);
	$defaults = apply_filters( 'enlightenment_list_comments_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	$output  = '';

	ob_start();
	do_action( 'enlightenment_before_comments_list' );
	$output .= ob_get_clean();

	$output .= enlightenment_open_tag( $args['style'], $args['class'] );
	ob_start();
	wp_list_comments( $args );
	$output .= ob_get_clean();
	$output .= enlightenment_close_tag( $args['style'] );

	ob_start();
	do_action( 'enlightenment_after_comments_list' );
	$output .= ob_get_clean();

	$output  = apply_filters( 'enlightenment_list_comments', $output );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_comment_author_avatar( $comment, $args ) {
	$defaults = array(
		'avatar_container'       => 'span',
		'avatar_container_class' => 'comment-author-avatar',
		'avatar_size'            => 32,
		'echo'                   => true,
	);
	$defaults = apply_filters( 'enlightenment_comment_author_avatar_args', $defaults, $comment );
	$args     = wp_parse_args( $args, $defaults );

	if( $defaults['echo'] ) {
		$args['echo'] = true;
	}

	if( $args['avatar_size'] == 0) {
		return false;
	}

	$output  = enlightenment_open_tag( $args['avatar_container'], $args['avatar_container_class'] );
	$output .= get_avatar( $comment, $args['avatar_size'] );
	$output .= enlightenment_close_tag( $args['avatar_container'] );

	$output = apply_filters( 'enlightenment_comment_author_avatar', $output, $args, $comment );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_comment_author( $comment, $args = null ) {
	$defaults = array(
		'container'              => 'h4',
		'container_class'        => 'comment-author vcard',
		'container_extra_atts'   => '',
		'author_name_tag'        => 'cite',
		'author_name_class'      => 'fn',
		'author_name_extra_atts' => '',
		'before'                 => '',
		'after'                  => sprintf( ' <span class="says">%s</span>', __( 'says:', 'enlightenment' ) ),
		'echo'                   => true,
	);
	$defaults = apply_filters( 'enlightenment_comment_author_args', $defaults, $comment );
	$args     = wp_parse_args( $args, $defaults );

	$output  = $args['before'];
	$output .= enlightenment_open_tag( $args['container'], $args['container_class'], '', $args['container_extra_atts'] );
	$output .= enlightenment_open_tag( $args['author_name_tag'], $args['author_name_class'], '', $args['author_name_extra_atts'] );
	$output .= get_comment_author_link();
	$output .= enlightenment_close_tag( $args['author_name_tag'] );
	$output .= enlightenment_close_tag( $args['container'] );
	$output .= $args['after'];

	$output = apply_filters( 'enlightenment_comment_author', $output, $args, $comment );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_comment_awaiting_moderation( $comment, $args = null ) {
	$defaults = array(
		'container'       => 'em',
		'container_class' => 'comment-awaiting-moderation',
		'text'            => __( 'Your comment is awaiting moderation.', 'enlightenment' ),
		'before'          => '',
		'after'           => '<br />',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_comment_awaiting_moderation_args', $defaults, $comment );
	$args     = wp_parse_args( $args, $defaults );

	if( $comment->comment_approved !== '0' ) {
		return;
	}

	$output  = $args['before'];
	$output .= enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= wp_kses( $args['text'], 'strip' );
	$output .= enlightenment_close_tag( $args['container'] );
	$output .= $args['after'];

	$output = apply_filters( 'enlightenment_comment_awaiting_moderation', $output, $args, $comment );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_comment_time( $comment, $args = null ) {
	$defaults = array(
		'comment'         => 0,
		'container'       => 'span',
		'container_class' => 'comment-time',
		'time_extra_atts' => '',
		'before'          => '',
		'after'           => '',
		'text_format'     => _x( '%1$s at %2$s', 'date time', 'enlightenment' ),
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_comment_time_args', $defaults, $comment );
	$args     = wp_parse_args( $args, $defaults );

	$time = sprintf(
		'<time datetime="%s"%s>%s</time>',
		get_comment_date('Y-m-d'),
		$args['time_extra_atts'],
		sprintf( $args['text_format'], get_comment_date(),  get_comment_time() )
	);

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= $args['before'];
	$output .= sprintf( '<a href="%s">%s</a>', htmlspecialchars( get_comment_link( $comment->comment_ID ) ), $time );
	$output .= $args['after'];
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_comment_time', $output, $args, $comment );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_comment_meta( $comment, $args = null ) {
	$defaults = array(
		'container'       => current_theme_supports( 'html5', 'comment-list' ) ? 'aside' : 'div',
		'container_class' => 'comment-meta commentmetadata',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_comment_meta_args', $defaults, $comment );
	$args     = wp_parse_args( $args, $defaults );

	if( ! has_action( 'enlightenment_comment_meta' ) ) {
		return;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	ob_start();
	do_action( 'enlightenment_comment_meta', $comment );
	$output .= ob_get_clean();
	$output .= enlightenment_close_tag( $args['container'] );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_comment_reply_link( $comment = null, $args = null, $post = null ) {
	$defaults = array(
		'add_below'     => 'comment',
		'respond_id'    => 'respond',
		'reply_text'    => __( 'Reply', 'enlightenment' ),
		/* translators: Comment reply button text. 1: Comment author name */
		'reply_to_text' => __( 'Reply to %s', 'enlightenment' ),
		'login_text'    => __( 'Log in to Reply', 'enlightenment' ),
		'max_depth'     => 0,
		'depth'         => 0,
		'before'        => '',
		'after'         => '',
		'echo'          => true,
	);
	$defaults = apply_filters( 'enlightenment_comment_reply_link_args', $defaults, $comment );
	$args     = wp_parse_args( $args, $defaults );

	$output = get_comment_reply_link( $args, $comment, $post );
	$output = apply_filters( 'enlightenment_comment_reply_link', $output, $args, $comment, $post );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}

function enlightenment_comment_form( $args = null, $post_id = null ) {
	comment_form( $args, $post_id );
}

function enlightenment_comments_closed_notice( $args = null ) {
	$defaults = array(
		'container'             => 'aside',
		'container_id'          => 'respond',
		'container_class'       => 'comment-respond',
		'notice_tag'            => 'p',
		'notice_class'          => 'comments-closed',
		'notice_text'           => __( 'Comments are closed.', 'enlightenment' ),
		'only_if_have_comments' => true,
		'echo'                  => true,
	);
	$defaults = apply_filters( 'enlightenment_comments_closed_notice_args', $defaults );
	$args     = wp_parse_args( $args, $defaults );

	if( $args['only_if_have_comments'] && ! have_comments() ) {
		return false;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= enlightenment_open_tag( $args['notice_tag'], $args['notice_class'] );
	$output .= wp_kses( $args['notice_text'], 'strip' );
	$output .= enlightenment_close_tag( $args['notice_tag'] );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_comments_closed_notice', $output );

	if( ! $args['echo'] ) {
		return $output;
	}
	echo $output;
}
