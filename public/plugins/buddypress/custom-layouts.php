<?php

function enlightenment_bp_archive_layouts( $layouts ) {
	return array_merge( $layouts, array(
		'activity'           => enlightenment_default_layout(),
		'members'            => enlightenment_default_layout(),
		'groups'             => enlightenment_default_layout(),
		'user-activity'      => enlightenment_default_layout(),
		'user-page'          => enlightenment_default_layout(),
		'group-activity'     => enlightenment_default_layout(),
		'group-page'         => enlightenment_default_layout(),
		'group-create'       => enlightenment_default_layout(),
		'sites'              => enlightenment_default_layout(),
		'site-create'        => enlightenment_default_layout(),
	) );
}
add_filter( 'enlightenment_archive_layouts', 'enlightenment_bp_archive_layouts' );

function enlightenment_bp_current_layout( $layout ) {
	if ( ! is_buddypress() ) {
		return $layout;
	}

	if ( enlightenment_bp_is_access_restricted() ) {
		if ( current_theme_supports( 'enlightenment-bootstrap' ) ) {
	        return array(
	            'smartphone-portrait'  => 'full-width',
	            'smartphone-landscape' => 'inherit',
	            'tablet-portrait'      => 'inherit',
	            'tablet-landscape'     => 'inherit',
	            'desktop-laptop'       => 'inherit',
	        );
	    }

	    return 'full-width';
	}

	$layouts = enlightenment_archive_layouts();

	if ( bp_is_activity_directory() ) {
		$layout = $layouts['activity'];
	} elseif ( bp_is_user() ) {
		if ( bp_is_user_activity() ) {
			$layout = $layouts['user-activity'];
		} else {
			$layout = $layouts['user-page'];
		}
	} elseif ( bp_is_members_directory() ) {
		$layout = $layouts['members'];
	} elseif ( bp_is_group() ) {
		if ( bp_is_group_activity() ) {
			$layout = $layouts['group-activity'];
		} else {
			$layout = $layouts['group-page'];
		}
	} elseif ( bp_is_groups_directory() ) {
		$layout = $layouts['groups'];
	} elseif ( bp_is_group_create() ) {
		$layout = $layouts['group-create'];
	} elseif ( bp_is_blogs_directory() ) {
		$layout = $layouts['sites'];
	} elseif ( bp_is_create_blog() ) {
		$layout = $layouts['site-create'];
	}

	return $layout;
}
add_filter( 'enlightenment_current_layout', 'enlightenment_bp_current_layout' );

function enlightenment_bp_layout_templates( $templates ) {
	return array_merge( $templates, array(
		'activity'           => array(
			'name' => __( 'Site-wide Activity', 'enlightenment' ),
			'url'  => '',
		),
		'members'            => array(
			'name' => __( 'Members Directory', 'enlightenment' ),
			'url'  => '',
		),
		'groups'             => array(
			'name' => __( 'Groups Directory', 'enlightenment' ),
			'url'  => '',
		),
		'user-activity'      => array(
			'name' => __( 'Member Activity', 'enlightenment' ),
			'url'  => '',
		),
		'user-page'          => array(
			'name' => __( 'Member Page', 'enlightenment' ),
			'url'  => '',
		),
		'group-activity'     => array(
			'name' => __( 'Group Activity', 'enlightenment' ),
			'url'  => '',
		),
		'group-page'         => array(
			'name' => __( 'Group Page', 'enlightenment' ),
			'url'  => '',
		),
	) );
}
add_filter( 'enlightenment_layout_templates', 'enlightenment_bp_layout_templates' );

function enlightenment_bp_current_layout_template( $template ) {
	if ( ! is_buddypress() ) {
		return $template;
	}

	if ( bp_is_activity_directory() ) {
		$template = 'activity';
	} elseif ( bp_is_user() ) {
		if ( bp_is_user_activity() ) {
			$template = 'user-activity';
		} else {
			$template = 'user-page';
		}
	} elseif ( bp_is_members_directory() ) {
		$template = 'members';
	} elseif ( bp_is_group() ) {
		if ( bp_is_group_activity() ) {
			$template = 'group-activity';
		} else {
			$template = 'group-page';
		}
	} elseif ( bp_is_groups_directory() ) {
		$template = 'groups';
	}

	return $template;
}
add_filter( 'enlightenment_current_layout_template', 'enlightenment_bp_current_layout_template' );
