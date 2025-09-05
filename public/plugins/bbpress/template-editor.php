<?php

function enlightenment_bbp_templates( $templates ) {
	if ( 'topics' === bbp_show_on_root() && isset( $templates['forum-archive'] ) ) {
		$post_type = get_post_type_object( 'topic' );

		$templates['forum-archive'] = array(
			'name'        => sprintf( __( '%s Archive', 'enlightenment' ), $post_type->labels->name ),
			'conditional' => array( 'is_post_type_archive', $post_type->name ),
			'type'        => 'post_type_archive',
		);

		$keys = array_keys( $templates );
		$keys[ array_search( 'forum-archive', $keys ) ] = 'topic-archive';

		$templates = array_combine( $keys, $templates );
	}

	if ( bbp_allow_search() ) {
		$templates['bbpress-search']   = array(
			'name'        => __( 'bbPress Search', 'enlightenment' ),
			'conditional' => 'bbp_is_search',
			'type'        => 'bbpress',
		);
	}

	$templates['bbpress-user']   = array(
		'name'        => __( 'bbPress User', 'enlightenment' ),
		'conditional' => 'bbp_is_single_user',
		'type'        => 'bbpress',
	);

    return $templates;
}
add_filter( 'enlightenment_templates', 'enlightenment_bbp_templates' );

function enlightenment_bbp_customizer_templates( $templates ) {
	if ( 'topics' === bbp_show_on_root() && isset( $templates['topic-archive'] ) ) {
		$templates['topic-archive']['url'] = bbp_get_forums_url();
	}

	$default_hooks = array_keys( enlightenment_template_hooks() );

	if ( bbp_allow_search() && isset( $templates['bbpress-search'] ) ) {
		add_filter( 'bbp_get_search_terms', 'enlightenment_bbp_template_editor_customizer_simulate_search_terms' );
	    $templates['bbpress-search']['url'] = bbp_get_search_results_url();
		remove_filter( 'bbp_get_search_terms', 'enlightenment_bbp_template_editor_customizer_simulate_search_terms' );
	}

	if ( isset( $templates['bbpress-user'] ) ) {
		$topics = get_posts( array(
			'post_type'      => 'topic',
			'posts_per_page' => 1,
		) );

		$circumvent_bp_intercept = (
			class_exists( 'BBP_BuddyPress_Members' )
			&&
			( 10 == has_action( 'bbp_pre_get_user_profile_url', array( bbpress()->extend->buddypress->members, 'get_user_profile_url' ) ) )
		);

		if ( $circumvent_bp_intercept ) {
			remove_filter( 'bbp_pre_get_user_profile_url', array( bbpress()->extend->buddypress->members, 'get_user_profile_url' ) );
		}

	    $templates['bbpress-user']['url'] = bbp_get_user_profile_url( count( $topics ) ? $topics[0]->post_author : get_current_user_id() );

		if ( $circumvent_bp_intercept ) {
			add_filter( 'bbp_pre_get_user_profile_url', array( bbpress()->extend->buddypress->members, 'get_user_profile_url' ) );
		}
	}

    return $templates;
}
add_filter( 'enlightenment_customizer_templates', 'enlightenment_bbp_customizer_templates' );

function enlightenment_bbp_template_editor_customizer_simulate_search_terms( $terms ) {
	$topics = get_posts( array(
		'post_type'  => 'topic',
		'posts_per_page' => 1,
	) );

	if ( count( $topics ) ) {
		$terms = strtolower( explode( ' ', $topics[0]->post_title )[0] );
	}

	return $terms;
}

function enlightenment_bbp_template_editor_simulate_query( $template, $atts ) {
	global $wp_query;

	if ( 'bbpress' != $atts['type'] ) {
		return;
	}

	switch ( $template ) {
		case 'bbpress-search':
			$wp_query->bbp_is_search = true;

			break;

		case 'bbpress-user':
			$wp_query->bbp_is_single_user = true;

			break;
	}
}
add_action( 'enlightenment_template_editor_simulate_query', 'enlightenment_bbp_template_editor_simulate_query', 10, 2 );

function enlightenment_bbp_template_editor_reset_query( $template ) {
	global $wp_query;

	switch ( $template ) {
		case 'bbpress-search':
			unset( $wp_query->bbp_is_search );

			break;

		case 'bbpress-user':
			unset( $wp_query->bbp_is_single_user );

			break;
	}
}
add_action( 'enlightenment_template_editor_reset_query', 'enlightenment_bbp_template_editor_reset_query' );

function enlightenment_bbp_current_query( $query ) {
	if ( bbp_is_search() ) {
        $query = 'bbpress-search';
    } elseif( bbp_is_single_user() ) {
        $query = 'bbpress-user';
    }

	return $query;
}
add_filter( 'enlightenment_current_query', 'enlightenment_bbp_current_query' );

function enlightenment_bbp_template_functions( $functions ) {
	$functions['enlightenment_bbp_template_notices']         = __( 'bbPress Notices',          'enlightenment' );
	$functions['enlightenment_bbp_single_user_avatar']       = __( 'bbPress User Avatar',      'enlightenment' );
	$functions['enlightenment_bbp_nav']                      = __( 'bbPress Navigation',       'enlightenment' );
	$functions['enlightenment_bbp_global_stats']             = __( 'bbPress Global Stats',     'enlightenment' );
	$functions['enlightenment_bbp_form_search']              = __( 'bbPress Search Form',      'enlightenment' );
	$functions['bbp_forum_subscription_link']                = __( 'Subscribe to Forum',       'enlightenment' );
	$functions['enlightenment_bbp_topic_tag_description']    = __( 'Topic Tag Description',    'enlightenment' );
	$functions['enlightenment_bbp_single_user_details']      = __( 'User Details',             'enlightenment' );
	$functions['enlightenment_bbp_forums_loop']              = __( 'Forums List',              'enlightenment' );
	$functions['enlightenment_bbp_topics_loop']              = __( 'Topics List',              'enlightenment' );
	$functions['enlightenment_bbp_search_loop']              = __( 'bbPress Search Results',   'enlightenment' );
	$functions['enlightenment_bbp_single_user_body']         = __( 'User Content',             'enlightenment' );
	$functions['enlightenment_bbp_single_forum_description'] = __( 'Forum Description',        'enlightenment' );
	$functions['enlightenment_bbp_forum_user_actions']       = __( 'Forum User Actions',       'enlightenment' );
	$functions['enlightenment_bbp_topic_user_actions']       = __( 'Topic User Actions',       'enlightenment' );
	$functions['enlightenment_bbp_topic_tag_list']           = __( 'Topic Tag List',           'enlightenment' );
	$functions['enlightenment_bbp_single_topic_description'] = __( 'Topic Description',        'enlightenment' );
	$functions['enlightenment_bbp_replies_loop']             = __( 'Topic Replies',            'enlightenment' );
	$functions['enlightenment_bbp_single_reply']             = __( 'Reply Content',            'enlightenment' );
	$functions['enlightenment_bbp_topic_form']               = __( 'New Topic Form',           'enlightenment' );
	$functions['enlightenment_bbp_reply_form']               = __( 'Reply to Topic Form',      'enlightenment' );
	$functions['enlightenment_bbp_alert_topic_lock']         = __( 'Topic Locked Alert',       'enlightenment' );

	return $functions;
}
add_filter( 'enlightenment_template_functions', 'enlightenment_bbp_template_functions' );

function enlightenment_bbp_page_content_hooks( $hooks ) {
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bbp_template_notices';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bbp_single_user_avatar';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bbp_nav';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bbp_global_stats';

	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_bbp_form_search';
	$hooks['enlightenment_before_page_content']['functions'][] = 'bbp_forum_subscription_link';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_bbp_topic_tag_description';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_bbp_single_user_details';

	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bbp_forums_loop';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bbp_topics_loop';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bbp_search_loop';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bbp_single_user_body';

	return $hooks;
}
add_filter( 'enlightenment_page_content_hooks', 'enlightenment_bbp_page_content_hooks' );

function enlightenment_bbp_entry_hooks( $hooks ) {
	$hooks['enlightenment_entry_header']['functions'][] = 'enlightenment_bbp_template_notices';
	$hooks['enlightenment_entry_header']['functions'][] = 'enlightenment_breadcrumbs';

	$hooks['enlightenment_before_entry_content']['functions'][] = 'enlightenment_breadcrumbs';
	$hooks['enlightenment_before_entry_content']['functions'][] = 'enlightenment_bbp_forum_user_actions';
	$hooks['enlightenment_before_entry_content']['functions'][] = 'enlightenment_bbp_topic_user_actions';
	$hooks['enlightenment_before_entry_content']['functions'][] = 'enlightenment_bbp_topic_tag_list';
	$hooks['enlightenment_before_entry_content']['functions'][] = 'enlightenment_bbp_single_topic_description';

	$hooks['enlightenment_entry_content']['functions'][] = 'enlightenment_bbp_replies_loop';
	$hooks['enlightenment_entry_content']['functions'][] = 'enlightenment_bbp_single_reply';

	$hooks['enlightenment_after_entry_content']['functions'][] = 'enlightenment_bbp_topic_form';

	$hooks['enlightenment_before_entry_footer']['functions'][] = 'enlightenment_bbp_reply_form';

	$hooks['enlightenment_entry_footer']['functions'][] = 'enlightenment_bbp_alert_topic_lock';

	return $hooks;
}
add_filter( 'enlightenment_entry_hooks', 'enlightenment_bbp_entry_hooks' );
