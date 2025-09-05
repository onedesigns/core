<?php

function enlightenment_bbp_template_notices( $args = null ) {
	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_template_notices_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	do_action( 'bbp_template_notices' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_bbp_template_notices', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_the_forum() {
	if( bbp_is_forum_archive() ) {
		bbp_the_forum();
	}
}

function enlightenment_bbp_form_search( $args = null ) {
	if( bbp_is_topic_archive() && ! bbp_allow_search() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_form_search_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bbp_get_template_part( 'form', 'search' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_bbp_form_search', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_topic_tag_description( $args = null ) {
	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_topic_tag_description_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	do_action( 'bbp_template_before_topic_tag_description' );

	if ( bbp_is_topic_tag() ) {
		bbp_topic_tag_description( array(
			'before' => '<div class="bbp-template-notice info"><ul><li>',
			'after' => '</li></ul></div>',
		) );
	}

	do_action( 'bbp_template_after_topic_tag_description' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_bbp_topic_tag_description', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_global_stats( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'stats bbp-global-stats',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_global_stats_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$stats = bbp_get_statistics();

	if ( ! isset( $stats['forum_count'] ) ) {
		$stats['forum_count'] = '0';
		$stats['forum_count_int'] = 0;
	}

	if ( ! isset( $stats['topic_count'] ) ) {
		$stats['topic_count'] = '0';
		$stats['topic_count_int'] = 0;
	}

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );

	$output .= enlightenment_open_tag( 'span', 'bbp-forums-count' );
	$output .= sprintf( _n( '%s Forum', '%s Forums', $stats['forum_count_int'], 'enlightenment' ), sprintf( '<strong class="number">%s</strong>', esc_html( $stats['forum_count'] ) ) );
	$output .= enlightenment_close_tag( 'span' );

	$output .= enlightenment_open_tag( 'span', 'bbp-topics-count' );
	$output .= sprintf( _n( '%s Topic', '%s Topics', $stats['topic_count_int'], 'enlightenment' ), sprintf( '<strong class="number">%s</strong>', esc_html( $stats['topic_count'] ) ) );
	$output .= enlightenment_close_tag( 'span' );

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bbp_global_stats', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_single_forum_description() {
	if( post_password_required() ) {
		return;
	}

	bbp_single_forum_description();
}

function enlightenment_bbp_forum_user_actions( $args = null ) {
	if ( ! is_user_logged_in() ) {
        return;
    }

	$defaults = array(
		'container'            => 'nav',
		'container_class'      => 'user-actions',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_forum_user_actions_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );

	$output .= bbp_get_forum_subscription_link();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bbp_forum_user_actions', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_single_topic_description() {
	if( post_password_required() ) {
		return;
	}

	bbp_single_topic_description();
}

function enlightenment_bbp_topic_user_actions( $args = null ) {
	if ( ! is_user_logged_in() ) {
        return;
    }

	if ( ! current_user_can( 'edit_user', bbp_get_user_id( 0, false, true ) ) ) {
        return;
    }

	if ( ! bbp_is_subscriptions_active() && ! bbp_is_favorites_active() ) {
        return;
    }

	$defaults = array(
		'container'            => 'nav',
		'container_class'      => 'user-actions',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_topic_user_actions_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );

	$output .= bbp_get_topic_subscription_link();
	$output .= bbp_get_topic_favorite_link();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bbp_topic_user_actions', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_nav( $args = null ) {
	$defaults = array(
		'container'            => 'nav',
		'container_class'      => 'secondary-navigation',
		'container_id'         => 'bbp-nav',
		'container_extra_atts' => ' role="navigation"',
		'echo'                 => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_nav_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_class'], $args['container_extra_atts'] );

	$output .= enlightenment_open_tag( 'ul', 'menu nav' );

	$output .= enlightenment_open_tag( 'li', 'menu-item nav-item' . ( bbp_is_forum_archive() ? ' current-menu-item active' : '' ) );
	$output .= sprintf( '<a class="nav-link" href="%1$s">%2$s</a>', bbp_get_forums_url(), __( 'Forums', 'enlightenment' ) );
	$output .= enlightenment_close_tag( 'li' );

	$output .= enlightenment_open_tag( 'li', 'menu-item nav-item' . ( bbp_is_topic_archive() ? ' current-menu-item active' : '' ) );
	$output .= sprintf( '<a class="nav-link" href="%1$s">%2$s</a>', bbp_get_topics_url(), __( 'Reccent Topics', 'enlightenment' ) );
	$output .= enlightenment_close_tag( 'li' );

	if( is_user_logged_in() ) {
		if( bbp_is_favorites_active() ) {
			$output .= enlightenment_open_tag( 'li', 'menu-item nav-item' . ( bbp_is_favorites() ? ' current-menu-item active' : '' ) );
			$output .= sprintf( '<a class="nav-link" href="%1$s">%2$s</a>', bbp_get_favorites_permalink( get_current_user_id() ), __( 'Favorite Topics', 'enlightenment' ) );
			$output .= enlightenment_close_tag( 'li' );
		}

		if( bbp_is_subscriptions_active() ) {
			$output .= enlightenment_open_tag( 'li', 'menu-item nav-item' . ( bbp_is_subscriptions() ? ' current-menu-item active' : '' ) );
			$output .= sprintf( '<a class="nav-link" href="%1$s">%2$s</a>', bbp_get_subscriptions_permalink( get_current_user_id() ), __( 'Followed Topics', 'enlightenment' ) );
			$output .= enlightenment_close_tag( 'li' );
		}
	}

	foreach( array_keys( bbp_get_views() ) as $view ) {
		$output .= enlightenment_open_tag( 'li', 'menu-item nav-item' . ( ( bbp_is_single_view() && ( bbp_get_view_id( $view ) == bbp_get_view_id() ) ) ? ' current-menu-item active' : '' ) );
		$output .= sprintf( '<a class="nav-link bbp-view-title" href="%1$s">%2$s</a>', bbp_get_view_url( $view ), bbp_get_view_title( $view ) );
		$output .= enlightenment_close_tag( 'li' );
	}

	$output .= enlightenment_close_tag( 'ul' );

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bbp_nav', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_forums_loop( $args = null ) {
	if ( post_password_required() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_forums_loop_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	if ( bbp_has_forums() ) {
		bbp_get_template_part( 'loop', 'forums' );
	} elseif ( bbp_is_forum_archive() || bbp_is_forum_category() ) {
		bbp_get_template_part( 'feedback', 'no-forums' );
	}
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bbp_forums_loop', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_topics_loop( $args = null ) {
	if( bbp_is_forum_category() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_forums_loop_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	if ( bbp_is_single_view() ) {
		bbp_set_query_name( bbp_get_view_rewrite_id() );
	}

	ob_start();
	do_action( 'bbp_template_before_topics_index' );
	if ( post_password_required() ) {
		bbp_get_template_part( 'form', 'protected' );
	} elseif( ( bbp_is_single_view() && bbp_view_query () ) || bbp_has_topics() ) {
		bbp_get_template_part( 'pagination', 'topics' );
		bbp_get_template_part( 'loop', 'topics' );
		bbp_get_template_part( 'pagination', 'topics' );
	} elseif ( ! bbp_is_forum_category() ) {
		bbp_get_template_part( 'feedback', 'no-topics' );
	}
	do_action( 'bbp_template_after_topics_index' );
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bbp_topics_loop', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_forum_form( $args = null ) {
	if( bbp_is_forum_category() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_forum_form_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bbp_get_template_part( 'form', 'forum' );
	$output = ob_get_clean();

	$output = str_replace( bbp_get_breadcrumb(), '', $output );
	$output = str_replace( bbp_get_single_forum_description( array( 'forum_id' => bbp_get_forum_id() ) ), '', $output );

	$offset = strpos( $output, '<div id="bbpress-forums" class="bbpress-wrapper">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '', $offset, 49 );
		$offset = strpos( $output, '</div>', $offset );
		$pos    = false;

		while ( false !== $offset ) {
			$pos    = $offset;
			$offset = strpos( $output, '</div>', $offset + 1 );
		}

		if ( false !== $pos ) {
			$output = substr_replace( $output, '', $pos, 6 );
		}
	}

	$output = apply_filters( 'enlightenment_bbp_forum_form', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_topic_tag_edit( $args = null ) {
	if( bbp_is_forum_category() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_topic_tag_edit_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	do_action( 'bbp_template_before_topic_tag_edit' );
	bbp_get_template_part( 'form', 'topic-tag' );
	do_action( 'bbp_template_after_topic_tag_edit' );
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bbp_topic_tag_edit', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_replies_loop( $args = null ) {
	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_replies_loop_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();

	if( bbp_show_lead_topic() ) {
		bbp_get_template_part( 'content', 'single-topic-lead' );
	}

	if( bbp_has_replies() ) {
		bbp_get_template_part( 'pagination', 'replies' );

		ob_start();
		bbp_get_template_part( 'loop',       'replies' );
		$output = ob_get_clean();

		if ( ! bbp_thread_replies() ) {
			$output = str_replace( '<li class="bbp-body">', '<li class="bbp-body"><ul class="bbp-replies-list">', $output );
			$output = str_replace( '</li><!-- .bbp-body -->', '</ul></li><!-- .bbp-body -->', $output );

			$output = str_replace( '<div id="post-', '<li><div id="post-', $output );
			$output = str_replace( '</div><!-- .reply -->', '</div><!-- .reply --></li>', $output );
		}

		echo $output;

		bbp_get_template_part( 'pagination', 'replies' );
	}

	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bbp_replies_loop', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_topic_tag_list() {
	if( post_password_required() ) {
		return;
	}

	bbp_topic_tag_list();
}

function enlightenment_bbp_list_forums( $args = null ) {
	$defaults = array();
	$defaults = apply_filters( 'enlightenment_bbp_list_forums_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	do_action( 'bbp_theme_before_forum_sub_forums' );
	bbp_list_forums( $args );
	do_action( 'bbp_theme_after_forum_sub_forums' );
}

function enlightenment_bbp_forum_stats( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'stats forum-stats',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_forum_stats_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );

	$output .= enlightenment_open_tag( 'span', 'bbp-forum-topic-count' );
	$output .= bbp_get_forum_topic_count();
	$output .= enlightenment_close_tag( 'span' );

	$output .= enlightenment_open_tag( 'span', 'bbp-forum-reply-count' );
	$output .= bbp_show_lead_topic() ? bbp_get_forum_reply_count() : bbp_get_forum_post_count();
	$output .= enlightenment_close_tag( 'span' );

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bbp_forum_stats', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_forum_freshness( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'bbp-forum-freshness',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_forum_freshness_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );

	ob_start();
	do_action( 'bbp_theme_before_forum_freshness_link' );
	$output .= ob_get_clean();

	$output .= bbp_get_forum_freshness_link();

	ob_start();
	do_action( 'bbp_theme_after_forum_freshness_link' );
	$output .= ob_get_clean();

	$output .= enlightenment_bbp_topic_meta( array( 'echo' => false ) );

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bbp_forum_freshness', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_topic_meta( $args = null ) {
	$defaults = array(
		'container'       => 'p',
		'container_class' => 'bbp-topic-meta',
		'avatar_size'     => 48,
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_topic_meta_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );

	ob_start();
	do_action( 'bbp_theme_before_topic_author' );
	$output .= ob_get_clean();

	$output .= sprintf( '<span class="bbp-topic-freshness-author">%s</span>', bbp_get_author_link( array(
		'post_id' => bbp_get_forum_last_active_id(),
		'size' => $args['avatar_size'],
	) ) );

	ob_start();
	do_action( 'bbp_theme_after_topic_author' );
	$output .= ob_get_clean();

	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bbp_topic_meta', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_topic_form( $args = null ) {
	if( post_password_required() || bbp_is_forum_category() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_topic_form_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bbp_get_template_part( 'form', 'topic' );
	$output = ob_get_clean();

	$output = str_replace( bbp_get_breadcrumb(), '', $output );

	$output = apply_filters( 'enlightenment_bbp_topic_form', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_topic_merge_form( $args = null ) {
	if( post_password_required() || bbp_is_forum_category() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_topic_merge_form_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bbp_get_template_part( 'form', 'topic-merge' );
	$output = ob_get_clean();

	$output = str_replace( bbp_get_breadcrumb(), '', $output );

	$output = apply_filters( 'enlightenment_bbp_topic_merge_form', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_topic_split_form( $args = null ) {
	if( post_password_required() || bbp_is_forum_category() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_topic_split_form_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bbp_get_template_part( 'form', 'topic-split' );
	$output = ob_get_clean();

	$output = str_replace( bbp_get_breadcrumb(), '', $output );

	$output = apply_filters( 'enlightenment_bbp_topic_split_form', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_reply_form( $args = null ) {
	if( post_password_required() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_reply_form_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bbp_get_template_part( 'form', 'reply' );
	$output = ob_get_clean();

	$output = str_replace( bbp_get_breadcrumb(), '', $output );

	$output = apply_filters( 'enlightenment_bbp_reply_form', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_reply_move_form( $args = null ) {
	if( post_password_required() ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_reply_move_form_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bbp_get_template_part( 'form', 'reply-move' );
	$output = ob_get_clean();

	$output = str_replace( bbp_get_breadcrumb(), '', $output );

	$output = apply_filters( 'enlightenment_bbp_reply_move_form', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_alert_topic_lock( $args = null ) {
	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_alert_topic_lock_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bbp_get_template_part( 'alert', 'topic-lock' );
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bbp_alert_topic_lock', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_single_reply( $args = null ) {
	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_single_reply_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bbp_get_template_part( 'loop', 'single-reply' );
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bbp_single_reply', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_search_loop( $args = null ) {
	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_search_loop_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	bbp_set_query_name( bbp_get_search_rewrite_id() );

	ob_start();

	do_action( 'bbp_template_before_search' );

	if ( bbp_has_search_results() ) {
		bbp_get_template_part( 'pagination', 'search' );
		bbp_get_template_part( 'loop',       'search' );
		bbp_get_template_part( 'pagination', 'search' );
	} elseif ( bbp_get_search_terms() ) {
		bbp_get_template_part( 'feedback',   'no-search' );
	} else {
		bbp_get_template_part( 'form', 'search' );
	}

	do_action( 'bbp_template_after_search_results' );
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bbp_search_loop', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_single_user_avatar( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => '',
		'container_id'    => 'bbp-user-avatar',
		'wrapper'         => 'span',
		'wrapper_class'   => 'vcard',
		'avatar_size'     => 150,
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_single_user_avatar_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= enlightenment_open_tag( $args['wrapper'],   $args['wrapper_class'] );
	$output .= sprintf( '<a class="url fn n" href="%s" title="%s" rel="me">', bbp_get_user_profile_url(), bbp_get_displayed_user_field( 'display_name' ) );
	$output .= get_avatar( bbp_get_displayed_user_field( 'user_email', 'raw' ), apply_filters( 'bbp_single_user_details_avatar_size', $args['avatar_size'] ) );
	$output .= '</a>';
	$output .= enlightenment_close_tag( $args['wrapper'] );
	$output .= enlightenment_close_tag( $args['container'] );

	$output = apply_filters( 'enlightenment_single_bbp_user_avatar', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_single_user_details( $args = null ) {
	$defaults = array(
		'user_avatar' => false,
		'echo'        => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_single_user_details_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bbp_get_template_part( 'user', 'details' );
	$output = ob_get_clean();

	if ( false === $args['user_avatar'] ) {
		$start = strpos( $output, '<div id="bbp-user-avatar">' );
		if ( false !== $start ) {
			$end    = strpos( $output, '</div>', $start ) + 6;
			$length = $end - $start;
			$output = substr_replace( $output, '', $start, $length );
		}
	}

	$output = apply_filters( 'enlightenment_bbp_single_user_details', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_single_user_body( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => '',
		'container_id'    => 'bbp-user-body',
		'user_handle'     => false,
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_single_user_body_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	ob_start();
	if ( bbp_is_favorites() ) {
		bbp_get_template_part( 'user', 'favorites' );
	} elseif ( bbp_is_subscriptions() ) {
		bbp_get_template_part( 'user', 'subscriptions' );
	} elseif ( bbp_is_single_user_engagements() ) {
		bbp_get_template_part( 'user', 'engagements' );
	} elseif ( bbp_is_single_user_topics() ) {
		bbp_get_template_part( 'user', 'topics-created' );
	} elseif ( bbp_is_single_user_replies() ) {
		bbp_get_template_part( 'user', 'replies-created' );
	} elseif ( bbp_is_single_user_edit() ) {
		bbp_get_template_part( 'form', 'user-edit' );
	} elseif ( bbp_is_single_user_profile() ) {
		bbp_get_template_part( 'user', 'profile' );
	}
	$output .= ob_get_clean();
	$output .= enlightenment_close_tag( $args['container'] );

	if ( false === $args['user_handle'] ) {
		$output = str_replace( sprintf( '<h2 class="entry-title">@%s</h2>', bbp_get_displayed_user_field( 'user_nicename' ) ), '', $output );
	}

	$output = apply_filters( 'enlightenment_bbp_single_user_body', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_feedback_no_access( $args = null ) {
	$defaults = array(
		'container'       => 'div',
		'container_class' => 'bbp-template-notice info',
		'wrapper'         => 'ul',
		'wrapper_class'   => '',
		'element'         => 'li',
		'element_class'   => '',
		'echo'            => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_feedback_no_access_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'] );
	$output .= enlightenment_open_tag( $args['wrapper'],   $args['wrapper_class'] );
	$output .= enlightenment_open_tag( $args['element'],   $args['element_class'] );
	$output .= esc_html__( 'You do not have permission to view this forum.', 'enlightenment' );
	$output .= enlightenment_close_tag( $args['element'] );
	$output .= enlightenment_close_tag( $args['wrapper'] );
	$output .= enlightenment_close_tag( $args['container'] );

	$output  = apply_filters( 'enlightenment_bbp_feedback_no_access', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}

function enlightenment_bbp_form_protected( $args = null ) {
	$defaults = array(
		'echo' => true,
	);
	$defaults = apply_filters( 'enlightenment_bbp_form_protected_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	bbp_get_template_part( 'form', 'protected' );
	$output = ob_get_clean();
	$output = apply_filters( 'enlightenment_bbp_form_protected', $output );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
