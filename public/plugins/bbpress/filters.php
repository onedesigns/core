<?php

function enlightenment_bbp_form_search_placeholder( $output ) {
	return str_replace( 'id="bbp_search"', sprintf( 'id="bbp_search" placeholder="%s"', __( 'Search the forums &#8230;', 'enlightenment' ) ), $output );
}
add_filter( 'enlightenment_bbp_form_search', 'enlightenment_bbp_form_search_placeholder' );

function enlightenment_bbp_the_archive_title( $title ) {
	if ( bbp_is_topic_tag() ) {
		$title = sprintf( esc_html__( 'Topic Tag: %s', 'enlightenment' ), sprintf( '<span>%s</span>', bbp_get_topic_tag_name() ) );

		if ( current_user_can( 'manage_topic_tags' ) ) {
			$title .= sprintf( ' <a href="%s" class="bbp-edit-topic-tag-link">%s</a>', esc_url( bbp_get_topic_tag_edit_link() ), esc_html( sprintf( '(%s)', _x( 'Edit', 'Edit Topic Tag', 'enlightenment' ) ) ) );
		}
	} elseif ( bbp_is_topic_tag_edit() ) {
		$title = sprintf( esc_html__( 'Edit Topic Tag: %s', 'enlightenment' ), sprintf( '<span>%s</span>', bbp_get_topic_tag_name() ) );
	} elseif ( bbp_is_search() ) {
		// Get search terms
		$search_terms = bbp_get_search_terms();

		// No search terms specified
		if ( empty( $search_terms ) ) {
			$title = esc_html__( 'Search', 'enlightenment' );

		// Include search terms in title
		} else {
			$title = sprintf( esc_html__( "Search Results for '%s'", 'enlightenment' ), esc_attr( $search_terms ) );
		}

		// Filter & return
		$title = apply_filters( 'bbp_get_search_title', $title, $search_terms );
	} elseif ( bbp_is_single_user() ) {
		$title = bbp_get_displayed_user_field( 'display_name' );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'enlightenment_bbp_the_archive_title' );

function enlightenment_bbp_the_archive_description( $description ) {
	if ( bbp_is_single_user() ) {
		$description = sprintf( '@%s', bbp_get_displayed_user_field( 'user_nicename' ) );
	}

	return $description;
}
add_filter( 'get_the_archive_description', 'enlightenment_bbp_the_archive_description' );

function enlightenment_bbp_the_title( $title, $id = false ) {
	if ( false === $id ) {
		return $title;
	}

	if ( 'forum' == get_post_type( $id ) ) {
		remove_filter( 'the_title', 'enlightenment_bbp_the_title', 10, 2 );
		$title = bbp_get_forum_title( $id );
		add_filter( 'the_title', 'enlightenment_bbp_the_title', 10, 2 );
	} elseif ( 'topic' == get_post_type( $id ) ) {
		remove_filter( 'the_title', 'enlightenment_bbp_the_title', 10, 2 );
		$title = bbp_get_topic_title( $id );
		add_filter( 'the_title', 'enlightenment_bbp_the_title', 10, 2 );
	}

	return $title;
}
add_filter( 'the_title', 'enlightenment_bbp_the_title', 10, 2 );

function enlightenment_bbp_forum_title_private( $title ) {
	if ( bbp_user_can_view_forum() ) {
		return $title;
	}

	return esc_html__( 'Private', 'enlightenment' );
}
add_filter( 'bbp_get_forum_title', 'enlightenment_bbp_forum_title_private' );

function enlightenment_bbp_topic_title_private( $title ) {
	if ( bbp_user_can_view_forum( array( 'forum_id' => bbp_get_topic_forum_id() ) ) ) {
		return $title;
	}

	return esc_html__( 'Private', 'enlightenment' );
}
add_filter( 'bbp_get_topic_title', 'enlightenment_bbp_topic_title_private' );

function enlightenment_bbp_forum_content_private( $content ) {
	if ( 'forum' != get_post_type() ) {
		return $content;
	}

	if ( bbp_user_can_view_forum() ) {
		return $content;
	}

	return '';
}
add_filter( 'the_content', 'enlightenment_bbp_forum_content_private' );

function enlightenment_bbp_filter_single_forum_description( $output ) {
	return str_replace( 'class="bbp-template-notice info"', 'class="bbp-template-notice info bbp-single-forum-description"', $output );
}
add_filter( 'bbp_get_single_forum_description', 'enlightenment_bbp_filter_single_forum_description' );

function enlightenment_bbp_filter_single_topic_description( $output ) {
	return str_replace( 'class="bbp-template-notice info"', 'class="bbp-template-notice info bbp-single-topic-description"', $output );
}
add_filter( 'bbp_get_single_topic_description', 'enlightenment_bbp_filter_single_topic_description' );

function enlightenment_bbp_filter_user_subscribe_link_args( $args ) {
	$args['before'] = '';

	return $args;
}
add_filter( 'bbp_before_get_user_subscribe_link_parse_args', 'enlightenment_bbp_filter_user_subscribe_link_args' );

function enlightenment_bbp_filter_topic_tag_edit( $output ) {
	$offset = strpos( $output, 'id="bbp-edit-topic-tag"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<legend>', $offset );
		$output = substr_replace( $output, ' class="screen-reader-text"', $offset + 7, 0 );
	}

	$offset = strpos( $output, '<input type="text" id="tag-description"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<textarea', $offset, 18 );
		$offset = strpos( $output, ' size="20"', $offset );
		$output = substr_replace( $output, ' rows="5" cols="40"', $offset, 10 );
		$offset = strpos( $output, ' value="', $offset );
		$output = substr_replace( $output, '>', $offset, 8 );
		$offset = strpos( $output, '" />', $offset );
		$output = substr_replace( $output, '</textarea>', $offset, 4 );
	}

	return $output;
}
add_filter( 'enlightenment_bbp_topic_tag_edit', 'enlightenment_bbp_filter_topic_tag_edit' );

function enlightenment_bbp_filter_display_shortcode( $output, $query_name ) {
	if ( '' === $query_name && false !== strpos( $output, '<dl role="main">' ) ) {
		$query_name = 'bbp_stats';
	}

	return apply_filters( "enlightenment_bbp_display_shortcode_$query_name", $output );
}
add_filter( 'bbp_display_shortcode', 'enlightenment_bbp_filter_display_shortcode', 10, 2 );
