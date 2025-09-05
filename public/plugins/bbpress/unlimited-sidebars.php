<?php

function enlightenment_bbp_unlimited_sidebars_templates( $templates ) {
	if ( 'topics' === bbp_show_on_root() && isset( $templates['forum-archive'] ) ) {
		$post_type = get_post_type_object( 'topic' );

		$templates['forum-archive'] = sprintf( __( '%s Archive', 'enlightenment' ), $post_type->labels->name );

		$keys = array_keys( $templates );
		$keys[ array_search( 'forum-archive', $keys ) ] = 'topic-archive';

		$templates = array_combine( $keys, $templates );
	}

    $templates['bbpress-view']   = __( 'bbPress View',   'enlightenment' );
    $templates['bbpress-search'] = __( 'bbPress Search', 'enlightenment' );
    $templates['bbpress-user']   = __( 'bbPress User',   'enlightenment' );

    return $templates;
}
add_filter( 'enlightenment_unlimited_sidebars_templates', 'enlightenment_bbp_unlimited_sidebars_templates' );

function enlightenment_bbp_unlimited_sidebars_customizer_templates( $templates ) {
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

	add_filter( 'bbp_get_search_terms', 'enlightenment_bbp_unlimited_sidebars_customizer_simulate_search_terms' );
    $templates['bbpress-search'] = array(
		'name' => __( 'bbPress Search', 'enlightenment' ),
		'url'  => bbp_get_search_results_url(),
	);
	remove_filter( 'bbp_get_search_terms', 'enlightenment_bbp_unlimited_sidebars_customizer_simulate_search_terms' );

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
add_filter( 'enlightenment_unlimited_sidebars_customizer_templates', 'enlightenment_bbp_unlimited_sidebars_customizer_templates' );

function enlightenment_bbp_unlimited_sidebars_customizer_simulate_search_terms( $terms ) {
	$topics = get_posts( array(
		'post_type'  => 'topic',
		'posts_per_page' => 1,
	) );

	if ( count( $topics ) ) {
		$terms = strtolower( explode( ' ', $topics[0]->post_title )[0] );
	}

	return $terms;
}

function enlightenment_bbp_current_sidebars_template( $template ) {
    if ( bbp_is_single_view() ) {
        $template = 'bbpress-view';
    } elseif ( bbp_is_search() ) {
        $template = 'bbpress-search';
    } elseif( bbp_is_single_user() ) {
        $template = 'bbpress-user';
    }

    return $template;
}
add_filter( 'enlightenment_current_sidebars_template', 'enlightenment_bbp_current_sidebars_template' );
