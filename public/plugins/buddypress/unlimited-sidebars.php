<?php

function enlightenment_bp_unlimited_sidebars_templates( $templates ) {
	return array_merge( $templates, array(
		'activity'       => __( 'Site-wide Activity', 'enlightenment' ),
		'members'        => __( 'Members Directory',  'enlightenment' ),
		'groups'         => __( 'Groups Directory',   'enlightenment' ),
		'user-activity'  => __( 'Member Activity',    'enlightenment' ),
		'user-page'      => __( 'Member Page',        'enlightenment' ),
		'group-activity' => __( 'Group Activity',     'enlightenment' ),
		'group-page'     => __( 'Group Page',         'enlightenment' ),
		'sites'          => __( 'Sites',              'enlightenment' ),
		'site-create'    => __( 'Create a Site',      'enlightenment' ),
	) );
}
add_filter( 'enlightenment_unlimited_sidebars_templates', 'enlightenment_bp_unlimited_sidebars_templates' );

function enlightenment_bp_unlimited_sidebars_customizer_templates( $templates ) {
	$users = bp_core_get_users( array(
		'per_page' => 1,
	) );

	$user_domain = count( $users ) ? bp_members_get_user_url( $users['users'][0]->ID ) : bp_loggedin_user_domain();

	$groups = groups_get_groups( array(
		'orderby' => 'last_activity',
		'per_page' => 1,
	) );

	$group_domain = count( $users ) ? bp_get_group_url( $groups['groups'][0]->id ) : '';

	return array_merge( $templates, array(
		'activity'       => array(
			'name' => __( 'Site-wide Activity', 'enlightenment' ),
			'url'  => bp_get_activity_directory_permalink(),
		),
		'members'        => array(
			'name' => __( 'Members Directory',  'enlightenment' ),
			'url'  => bp_get_members_directory_permalink(),
		),
		'groups'         => array(
			'name' => __( 'Groups Directory',   'enlightenment' ),
			'url'  => bp_get_groups_directory_permalink(),
		),
		'user-activity'  => array(
			'name' => __( 'Member Activity',    'enlightenment' ),
			'url'  => trailingslashit( $user_domain . bp_get_activity_slug() ),
		),
		'user-page'      => array(
			'name' => __( 'Member Page',        'enlightenment' ),
			'url'  => trailingslashit( $user_domain . bp_get_profile_slug() ),
		),
		'group-activity' => array(
			'name' => __( 'Group Activity',     'enlightenment' ),
			'url'  => trailingslashit( $group_domain . 'activity' ),
		),
		'group-page'     => array(
			'name' => __( 'Group Page',         'enlightenment' ),
			'url'  => trailingslashit( $group_domain . 'members' ),
		),
		'sites'          => array(
			'name' => __( 'Sites',              'enlightenment' ),
			'url'  => bp_get_blogs_directory_permalink(),
		),
		'site-create'    => array(
			'name' => __( 'Create a Site',      'enlightenment' ),
			'url'  => trailingslashit( bp_get_blogs_directory_permalink() . 'create' ),
		),
	) );
}
add_filter( 'enlightenment_unlimited_sidebars_customizer_templates', 'enlightenment_bp_unlimited_sidebars_customizer_templates' );

function enlightenment_bp_current_sidebars_template( $template ) {
	if( ! is_buddypress() ) {
		return $template;
	}

	if( bp_is_activity_directory() ) {
		$template = 'activity';
	} elseif( bp_is_user() ) {
		if( bp_is_user_activity() ) {
			$template = 'user-activity';
		} else {
			$template = 'user-page';
		}
	} elseif( bp_is_members_directory() ) {
		$template = 'members';
	} elseif( bp_is_group() ) {
		if( bp_is_group_activity() ) {
			$template = 'group-activity';
		} else {
			$template = 'group-page';
		}
	} elseif( bp_is_groups_directory() ) {
		$template = 'groups';
	} elseif( bp_is_blogs_directory() ) {
		$template = 'sites';
	} elseif( bp_is_create_blog() ) {
		$template = 'site-create';
	}

	return $template;
}
add_filter( 'enlightenment_current_sidebars_template', 'enlightenment_bp_current_sidebars_template' );

function enlightenment_bp_rtmedia_unlimited_sidebars_templates( $templates ) {
	if ( ! function_exists( 'is_rtmedia_page' ) ) {
		return $templates;
	}

	return array_merge( $templates, array(
		'media' => __( 'Media', 'enlightenment' ),
	) );
}
add_filter( 'enlightenment_unlimited_sidebars_templates', 'enlightenment_bp_rtmedia_unlimited_sidebars_templates' );

function enlightenment_bp_rtmedia_unlimited_sidebars_customizer_templates( $templates ) {
	if ( ! function_exists( 'is_rtmedia_page' ) ) {
		return $templates;
	}

	$media_slug  = defined( 'RTMEDIA_MEDIA_SLUG' ) ? RTMEDIA_MEDIA_SLUG : 'media';

	return array_merge( $templates, array(
		'media' => array(
			'name' => __( 'Media', 'enlightenment' ),
			'url'  => trailingslashit( home_url( $media_slug ) ),
		),
	) );
}
add_filter( 'enlightenment_unlimited_sidebars_customizer_templates', 'enlightenment_bp_rtmedia_unlimited_sidebars_customizer_templates' );

function enlightenment_bp_rtmedia_current_sidebars_template( $template ) {
	if ( ! function_exists( 'is_rtmedia_page' ) ) {
		return $template;
	}

	if( is_rtmedia_page() ) {
		$template = 'media';
	}

	return $template;
}
add_filter( 'enlightenment_current_sidebars_template', 'enlightenment_bp_rtmedia_current_sidebars_template' );
