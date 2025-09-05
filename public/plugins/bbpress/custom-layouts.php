<?php

function enlightenment_bbp_archive_layouts( $layouts ) {
	if ( 'topics' === bbp_show_on_root() && isset( $layouts['forum-archive'] ) ) {
		$keys = array_keys( $layouts );
		$keys[ array_search( 'forum-archive', $keys ) ] = 'topic-archive';

		$layouts = array_combine( $keys, $layouts );

		// Required to prevent Warning: Undefined array key "forum-archive".
		$layouts['forum-archive'] = $layouts['topic-archive'];
	}

	$default_layout = enlightenment_default_layout();

    $layouts['bbpress-view']   = $default_layout;
    $layouts['bbpress-search'] = $default_layout;
    $layouts['bbpress-user']   = $default_layout;

	return $layouts;
}
add_filter( 'enlightenment_archive_layouts', 'enlightenment_bbp_archive_layouts' );

function enlightenment_bbp_layout_templates( $templates ) {
	if ( 'topics' === bbp_show_on_root() && isset( $templates['forum-archive'] ) ) {
		$post_type = get_post_type_object( 'topic' );

		$templates['forum-archive'] = array(
			'name' => sprintf( __( '%s Archive', 'enlightenment' ), $post_type->labels->name ),
			'url'  => bbp_get_forums_url(),
		);

		$keys = array_keys( $templates );
		$keys[ array_search( 'forum-archive', $keys ) ] = 'topic-archive';

		$templates = array_combine( $keys, $templates );
	}

    $templates['bbpress-view'] = array(
		'name' => __( 'bbPress View', 'enlightenment' ),
		'url'  => bbp_get_forums_url(),
	);

	add_filter( 'bbp_get_search_terms', 'enlightenment_bbp_custom_layouts_customizer_simulate_search_terms' );
    $templates['bbpress-search'] = array(
		'name' => __( 'bbPress Search', 'enlightenment' ),
		'url'  => bbp_get_search_results_url(),
	);
	remove_filter( 'bbp_get_search_terms', 'enlightenment_bbp_custom_layouts_customizer_simulate_search_terms' );

	$topics = get_posts( array(
		'post_type'  => 'topic',
		'posts_per_page' => 1,
	) );

	if ( count( $topics ) ) {
		$user_id = $topics[0]->post_author;
	} else {
		$user_id = get_current_user_id();
	}

	$circumvent_bp_intercept = (
		class_exists( 'BBP_BuddyPress_Members' )
		&&
		( 10 == has_action( 'bbp_pre_get_user_profile_url', array( bbpress()->extend->buddypress->members, 'get_user_profile_url' ) ) )
	);

	if ( $circumvent_bp_intercept ) {
		remove_filter( 'bbp_pre_get_user_profile_url', array( bbpress()->extend->buddypress->members, 'get_user_profile_url' ) );
	}

    $templates['bbpress-user'] = array(
		'name' => __( 'bbPress User', 'enlightenment' ),
		'url'  => bbp_get_user_profile_url( $user_id ),
	);

	if ( $circumvent_bp_intercept ) {
		add_filter( 'bbp_pre_get_user_profile_url', array( bbpress()->extend->buddypress->members, 'get_user_profile_url' ) );
	}

	return $templates;
}
add_filter( 'enlightenment_layout_templates', 'enlightenment_bbp_layout_templates' );

function enlightenment_bbp_custom_layouts_customizer_simulate_search_terms( $terms ) {
	$topics = get_posts( array(
		'post_type'  => 'topic',
		'posts_per_page' => 1,
	) );

	if ( count( $topics ) ) {
		$terms = strtolower( explode( ' ', $topics[0]->post_title )[0] );
	}

	return $terms;
}

function enlightenment_bbp_current_layout( $layout ) {
	$layouts = enlightenment_archive_layouts();

	if ( is_post_type_archive( 'forum' ) && 'topics' === bbp_show_on_root() ) {
		$template = $layouts['topic-archive'];
	} elseif ( bbp_is_single_view() ) {
        $template = $layouts['bbpress-view'];
    } elseif ( bbp_is_search() ) {
		$layout = $layouts['bbpress-search'];
	} elseif ( bbp_is_single_user() ) {
		$layout = $layouts['bbpress-user'];
	}

	return $layout;
}
add_filter( 'enlightenment_current_layout', 'enlightenment_bbp_current_layout' );
