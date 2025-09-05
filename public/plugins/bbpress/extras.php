<?php

function enlightenment_bbp_template_include_theme_supports( $template ) {
	bbpress()->theme_compat->bbpress_template = $template;

	return $template;
}
add_filter( 'bbp_template_include_theme_supports', 'enlightenment_bbp_template_include_theme_supports' );

function enlightenment_bbp_admin_filter_settings_sections( $sections ) {
	unset( $sections['bbp_settings_theme_compat'] );

	return $sections;
}
add_filter( 'bbp_admin_get_settings_sections', 'enlightenment_bbp_admin_filter_settings_sections' );

function enlightenment_bbp_admin_filter_settings_fields( $fields ) {
	unset( $fields['bbp_settings_theme_compat'] );

	return $fields;
}
add_filter( 'bbp_admin_get_settings_fields', 'enlightenment_bbp_admin_filter_settings_fields' );

function enlightenment_bbp_forum_query( $query ) {
	if( is_admin() ) {
		return;
	}

	global $wp_the_query;

	if( $wp_the_query === $query && bbp_is_forum_archive() ) {
		$args = '';
		$args = bbp_parse_args( $args, array(
			'post_type'           => bbp_get_forum_post_type(),
			'post_parent'         => 0,
			'post_status'         => bbp_get_public_status_id(),
			'posts_per_page'      => get_option( '_bbp_forums_per_page', 50 ),
			'ignore_sticky_posts' => true,
			'orderby'             => 'menu_order title',
			'order'               => 'ASC'
		), 'has_forums' );

		$query->set( 'post_type',           $args['post_type'] );
		$query->set( 'post_parent',         $args['post_parent'] );
		$query->set( 'post_status',         $args['post_status'] );
		$query->set( 'posts_per_page',      $args['posts_per_page'] );
		$query->set( 'ignore_sticky_posts', $args['ignore_sticky_posts'] );
		$query->set( 'orderby',             $args['orderby'] );
		$query->set( 'order',               $args['order'] );

		// Run the query
		$bbp              = bbpress();
		$bbp->forum_query = new WP_Query( $args );
	}
}
add_action( 'pre_get_posts', 'enlightenment_bbp_forum_query' );

function enlightenment_bbp_breadcrumbs( $output, $args ) {
    if ( ! is_bbpress() && ! bbp_is_forum_edit() ) {
        return $output;
    }

	$output  = enlightenment_open_tag( $args['container'], $args['container_class'], $args['container_id'] );
	$output .= bbp_get_breadcrumb();
	$output .= enlightenment_close_tag( $args['container'] );

	return $output;
}
add_filter( 'enlightenment_breadcrumbs', 'enlightenment_bbp_breadcrumbs', 10, 2 );

function enlightenment_bbp_body_container() {
	echo enlightenment_open_tag( 'div', 'bbp-body' );
}

function enlightenment_setup_forums_query() {
	if( ! bbp_is_single_forum() ) {
		return;
	}

	if( bbp_has_forums() ) {

	}
}
//add_action( 'enlightenment_before_the_loop', 'enlightenment_setup_forums_query' );

function enlightenment_bbp_strip_nbsp( $output ) {
	$output = str_replace( '&nbsp;', ' ', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_topic_meta', 'enlightenment_bbp_strip_nbsp' );
add_filter( 'bbp_get_author_link', 'enlightenment_bbp_strip_nbsp' );
add_filter( 'bbp_get_topic_author_link', 'enlightenment_bbp_strip_nbsp' );
add_filter( 'bbp_get_reply_author_link', 'enlightenment_bbp_strip_nbsp' );

function enlightenment_bbp_wrap_anonymous_topic_author( $output, $topic_id ) {
	if( bbp_is_topic_anonymous( $topic_id ) ) {
		$author_name = get_post_meta( $topic_id, '_bbp_anonymous_name', true );
		$output = str_replace( $author_name, sprintf( '<span class="bbp-author-name">%s</span>', $author_name ), $output );
	}

	return $output;
}
add_filter( 'bbp_get_topic_author_display_name', 'enlightenment_bbp_wrap_anonymous_topic_author', 10, 2 );

function enlightenment_bbp_wrap_anonymous_reply_author( $output, $reply_id ) {
	if( bbp_is_reply_anonymous( $reply_id ) ) {
		$author_name = get_post_meta( $reply_id, '_bbp_anonymous_name', true );
		$output = str_replace( $author_name, sprintf( '<span class="bbp-author-name">%s</span>', $author_name ), $output );
	}

	return $output;
}
add_filter( 'bbp_get_reply_author_display_name', 'enlightenment_bbp_wrap_anonymous_reply_author', 10, 2 );

function enlightenment_bbp_enable_visual_editor( $args = array() ) {
    $args['tinymce'] = true;

    return $args;
}
add_filter( 'bbp_after_get_the_content_parse_args', 'enlightenment_bbp_enable_visual_editor' );

function enlightenment_bp_change_bbp_member_forums_screen_replies_function() {
	buddypress()->members->nav->edit_nav( array(
		'screen_function' => 'enlightenment_bbp_member_forums_screen_replies',
	), bbp_get_reply_archive_slug(), 'forums' );

	if ( 3 === has_action( 'bp_screens', 'bbp_member_forums_screen_replies' ) ) {
		remove_action( 'bp_screens', 'bbp_member_forums_screen_replies', 3 );
		add_action( 'bp_screens', 'enlightenment_bbp_member_forums_screen_replies', 3 );
	}

	$subnav_item = (array) buddypress()->members->nav->get_secondary( array(
		'parent_slug' => 'forums',
		'slug'        => bbp_get_reply_archive_slug(),
	), false );
	$subnav_item = reset( $subnav_item );

	bp_core_register_subnav_screen_function( $subnav_item, 'members' );
}
// add_action( 'bp_setup_nav','enlightenment_bp_change_bbp_member_forums_screen_replies_function', 100 );
add_action( 'bp_forums_setup_nav','enlightenment_bp_change_bbp_member_forums_screen_replies_function' );

function enlightenment_bbp_member_forums_screen_replies() {
	add_action( 'bp_template_content', 'enlightenment_bbp_member_forums_replies_content' );
	bp_core_load_template( apply_filters( 'bbp_member_forums_screen_replies', 'members/single/plugins' ) );
}

function enlightenment_bbp_member_forums_replies_content() {
	$output  = enlightenment_open_tag( 'div', 'bbpress-wrapper', 'bbpress-forums' );

	ob_start();
	bbp_get_template_part( 'user', 'replies-created' );
	$output .= ob_get_clean();

	if ( ! bbp_thread_replies() ) {
		$output = str_replace( '<li class="bbp-body">', '<li class="bbp-body"><ul class="bbp-replies-list">', $output );
		$output = str_replace( '</li><!-- .bbp-body -->', '</ul></li><!-- .bbp-body -->', $output );

		$output = str_replace( '<div id="post-', '<li><div id="post-', $output );
		$output = str_replace( '</div><!-- .reply -->', '</div><!-- .reply --></li>', $output );
	}

	$output .= enlightenment_close_tag( 'div' );

	echo $output;
}

function enlightenment_bbp_filter_single_user_body( $output ) {
	$output = str_replace( 'class="password-button-wrapper"', 'class="password-button-wrapper hide-if-no-js"', $output );
	
	if ( ! bbp_thread_replies() ) {
		$output = str_replace( '<li class="bbp-body">', '<li class="bbp-body"><ul class="bbp-replies-list">', $output );
		$output = str_replace( '</li><!-- .bbp-body -->', '</ul></li><!-- .bbp-body -->', $output );

		$output = str_replace( '<div id="post-', '<li><div id="post-', $output );
		$output = str_replace( '</div><!-- .reply -->', '</div><!-- .reply --></li>', $output );
	}

	return $output;
}
add_filter( 'enlightenment_bbp_single_user_body', 'enlightenment_bbp_filter_single_user_body' );

// add_action( 'bbp_template_before_single_topic', 'enlightenment_ob_start' );

function enlightenment_bbp_group_forum_display() {
	$output = ob_get_clean();

	if ( ! bbp_thread_replies() ) {
		$output = str_replace( '<li class="bbp-body">', '<li class="bbp-body"><ul class="bbp-replies-list">', $output );
		$output = str_replace( '</li><!-- .bbp-body -->', '</ul></li><!-- .bbp-body -->', $output );

		$output = str_replace( '<div id="post-', '<li><div id="post-', $output );
		$output = str_replace( '</div><!-- .reply -->', '</div><!-- .reply --></li>', $output );
	}

	echo $output;
}
// add_action( 'bbp_template_after_single_topic', 'enlightenment_bbp_group_forum_display' );

function enlightenment_bbp_filter_shortcode_bbp_single_topic( $output ) {
	if ( ! bbp_thread_replies() ) {
		$output = str_replace( '<li class="bbp-body">', '<li class="bbp-body"><ul class="bbp-replies-list">', $output );
		$output = str_replace( '</li><!-- .bbp-body -->', '</ul></li><!-- .bbp-body -->', $output );

		$output = str_replace( '<div id="post-', '<li><div id="post-', $output );
		$output = str_replace( '</div><!-- .reply -->', '</div><!-- .reply --></li>', $output );
	}

	return $output;
}
add_filter( 'enlightenment_bbp_display_shortcode_bbp_single_topic', 'enlightenment_bbp_filter_shortcode_bbp_single_topic' );
