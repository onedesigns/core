<?php

function enlightenment_bp_template_functions( $functions ) {
	$functions['enlightenment_bp_notifications']                = __( 'Notifications',             'enlightenment' );
	$functions['enlightenment_bp_header_account_login']         = __( 'My Account',                'enlightenment' );
	$functions['enlightenment_bp_template_notices']             = __( 'BuddyPress Notices',        'enlightenment' );
	$functions['enlightenment_bp_members_stats']                = __( 'Member Stats',              'enlightenment' );
	$functions['enlightenment_bp_activity_nav']                 = __( 'Activity Navigation',       'enlightenment' );
	$functions['enlightenment_bp_user_avatar']                  = __( 'Member Avatar',             'enlightenment' );
	$functions['enlightenment_bp_user_header_content']          = __( 'Member Header',             'enlightenment' );
	$functions['enlightenment_bp_group_avatar']                 = __( 'Group Avatar',              'enlightenment' );
	$functions['enlightenment_bp_group_header_content']         = __( 'Group Header Content',      'enlightenment' );
	$functions['enlightenment_bp_group_header_actions']         = __( 'Group Header Actions',      'enlightenment' );
	$functions['enlightenment_bp_groups_stats']                 = __( 'Groups Stats',              'enlightenment' );
	$functions['enlightenment_bp_groups_directory_group_types'] = __( 'Group Types',               'enlightenment' );
	$functions['enlightenment_bp_blogs_stats']                  = __( 'Sites Stats',               'enlightenment' );
	$functions['enlightenment_bp_blog_types']                   = __( 'Site Types',                'enlightenment' );
	$functions['enlightenment_bp_group_creation_screen']        = __( 'Create Group',              'enlightenment' );
	$functions['enlightenment_bp_activity_post_form']           = __( 'Activity Post Form',        'enlightenment' );
	$functions['enlightenment_bp_directory_nav']                = __( 'Directory Navigation',      'enlightenment' );
	$functions['enlightenment_bp_search_and_filters_bar']       = __( 'Search and Filters Bar',    'enlightenment' );
	$functions['enlightenment_bp_displayed_user_nav']           = __( 'Displayed User Navigation', 'enlightenment' );
	$functions['enlightenment_bp_group_description']            = __( 'Group Description',         'enlightenment' );
	$functions['enlightenment_bp_group_nav']                    = __( 'Group Navigation',          'enlightenment' );
	$functions['enlightenment_bp_group_creation_tabs']          = __( 'Group Creation Tabs',       'enlightenment' );
	$functions['enlightenment_bp_activity_loop']                = __( 'Activity Stream',           'enlightenment' );
	$functions['enlightenment_bp_displayed_user_body']          = __( 'Displayed User Content',    'enlightenment' );
	$functions['enlightenment_bp_members_loop']                 = __( 'Members Directory',         'enlightenment' );
	$functions['enlightenment_bp_group_body']                   = __( 'Group Conent',              'enlightenment' );
	$functions['enlightenment_bp_groups_loop']                  = __( 'Groups Directory',          'enlightenment' );
	$functions['enlightenment_bp_blogs_loop']                   = __( 'Sites Directory',           'enlightenment' );
	$functions['enlightenment_bp_blog_create_form']             = __( 'Create a Site',             'enlightenment' );

	return $functions;
}
add_filter( 'enlightenment_template_functions', 'enlightenment_bp_template_functions' );

function enlightenment_bp_site_header_hooks( $hooks ) {
	$hooks['enlightenment_site_header']['functions'][] = 'enlightenment_bp_notifications';
	$hooks['enlightenment_site_header']['functions'][] = 'enlightenment_bp_header_account_login';

	return $hooks;
}
add_filter( 'enlightenment_site_header_hooks', 'enlightenment_bp_site_header_hooks' );

function enlightenment_bp_page_content_hooks( $hooks ) {
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_template_notices';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_members_stats';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_activity_nav';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_user_avatar';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_user_header_content';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_group_avatar';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_group_header_content';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_group_header_actions';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_groups_stats';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_groups_directory_group_types';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_blogs_stats';
	$hooks['enlightenment_page_header']['functions'][] = 'enlightenment_bp_blog_types';

	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_bp_activity_post_form';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_bp_directory_nav';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_bp_search_and_filters_bar';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_bp_displayed_user_nav';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_bp_group_description';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_bp_group_nav';
	$hooks['enlightenment_before_page_content']['functions'][] = 'enlightenment_bp_group_creation_tabs';

	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bp_activity_loop';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bp_displayed_user_body';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bp_members_loop';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bp_group_body';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bp_groups_loop';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bp_group_creation_screen';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bp_blogs_loop';
	$hooks['enlightenment_page_content']['functions'][] = 'enlightenment_bp_blog_create_form';

	return $hooks;
}
add_filter( 'enlightenment_page_content_hooks', 'enlightenment_bp_page_content_hooks' );

function enlightenment_bp_templates( $templates ) {
	return array_merge( $templates, array(
		'activity' => array(
			'name'        => __( 'Activity', 'enlightenment' ),
			'conditional' => 'bp_is_activity_directory',
			'type'        => 'buddypress',
		),
		'members' => array(
			'name'        => __( 'Members', 'enlightenment' ),
			'conditional' => 'bp_is_members_directory',
			'type'        => 'buddypress',
		),
		'groups' => array(
			'name'        => __( 'Groups', 'enlightenment' ),
			'conditional' => 'bp_is_groups_directory',
			'type'        => 'buddypress',
		),
		'user-activity' => array(
			'name'        => __( 'Member Activity', 'enlightenment' ),
			'conditional' => 'bp_is_user_activity',
			'type'        => 'buddypress',
		),
		'user-page' => array(
			'name'        => __( 'Member Page', 'enlightenment' ),
			'conditional' => 'enlightenment_bp_is_user_page',
			'type'        => 'buddypress',
		),
		'group-activity' => array(
			'name'        => __( 'Group Activity', 'enlightenment' ),
			'conditional' => 'enlightenment_bp_is_group_activity',
			'type'        => 'buddypress',
		),
		'group-page' => array(
			'name'        => __( 'Group Page', 'enlightenment' ),
			'conditional' => 'enlightenment_bp_is_group_page',
			'type'        => 'buddypress',
		),
	) );
}
add_filter( 'enlightenment_templates', 'enlightenment_bp_templates' );

function enlightenment_bp_customizer_templates( $templates ) {
	$users = bp_core_get_users( array(
		'per_page' => 1,
	) );

	$user_domain = count( $users ) ? bp_members_get_user_url( $users['users'][0]->ID ) : bp_loggedin_user_domain();

	$groups = groups_get_groups( array(
		'orderby' => 'last_activity',
		'per_page' => 1,
	) );

	$group_domain = count( $users ) ? bp_get_group_url( $groups['groups'][0]->id ) : '';

	if ( isset( $templates['activity'] ) ) {
		$templates['activity']['url'] = bp_get_activity_directory_permalink();
	}

	if ( isset( $templates['members'] ) ) {
		$templates['members']['url'] = bp_get_members_directory_permalink();
	}

	if ( isset( $templates['groups'] ) ) {
		$templates['groups']['url'] = bp_get_groups_directory_permalink();
	}

	if ( isset( $templates['user-activity'] ) ) {
		$templates['user-activity']['url'] = trailingslashit( $user_domain . bp_get_activity_slug() );
	}

	if ( isset( $templates['user-page'] ) ) {
		$templates['user-page']['url'] = trailingslashit( $user_domain . bp_get_profile_slug() );
	}

	if ( isset( $templates['group-activity'] ) ) {
		$templates['group-activity']['url'] = trailingslashit( $group_domain . 'activity' );
	}

	if ( isset( $templates['group-page'] ) ) {
		$templates['group-page']['url'] = trailingslashit( $group_domain . 'members' );
	}

	return $templates;
}
add_filter( 'enlightenment_customizer_templates', 'enlightenment_bp_customizer_templates' );

function enlightenment_bp_current_query( $query ) {
	if ( ! is_buddypress() ) {
		return $query;
	}

	if ( bp_is_activity_directory() ) {
		$query = 'activity';
	} elseif ( bp_is_user() ) {
		if ( bp_is_user_activity() ) {
			$query = 'user-activity';
		} else {
			$query = 'user-page';
		}
	} elseif ( bp_is_members_directory() ) {
		$query = 'members';
	} elseif ( bp_is_group() ) {
		if( bp_is_group_activity() ) {
			$query = 'group-activity';
		} else {
			$query = 'group-page';
		}
	} elseif ( bp_is_groups_directory() ) {
		$query = 'groups';
	}

	return $query;
}
add_filter( 'enlightenment_current_query', 'enlightenment_bp_current_query' );
