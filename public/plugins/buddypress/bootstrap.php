<?php

/**
 * Dequeue styles.
 */
function enlightenment_bp_bootstrap_dequeue_styles() {
	wp_dequeue_style( 'bp-sitewide-notices-block' );
	wp_dequeue_style( 'bp-login-form-block' );
	wp_dequeue_style( 'bp-classic-widget-styles' );
	wp_dequeue_style( 'bp-nouveau-priority-nav' );
}
add_action( 'wp_enqueue_scripts', 'enlightenment_bp_bootstrap_dequeue_styles' );
add_filter( 'wp_print_footer_scripts', 'enlightenment_bp_bootstrap_dequeue_styles', 8 );

function enlightenment_bp_bootstrap_nouveau_styles( $styles ) {
	unset( $styles['bp-nouveau'] );

	return $styles;
}
add_filter( 'bp_nouveau_enqueue_styles', 'enlightenment_bp_bootstrap_nouveau_styles', 999 );

function enlightenment_bp_bootstrap_notifications_args( $args ) {
	$args['container_class']             .= ' d-flex';
	$args['dropdown_class']              .= ' dropdown';
	$args['alert_count_class']            = str_replace( ' alert', ' badge text-bg-danger', $args['alert_count_class'] );
	$args['toggle_class']                .= ' dropdown-toggle btn btn-link p-0 border-0 rounded-0';
	$args['toggle_extra_atts']            = array_merge( $args['toggle_extra_atts'], array(
		'data-bs-toggle' => 'dropdown',
		'data-bs-target' => sprintf( '#%s', $args['dropdown_id'] ),
	) );
	$args['dropdown_menu_class']         .= ' dropdown-menu dropdown-menu-end';
	$args['notifications_list_class']    .= ' list-unstyled mb-0';
	$args['notification_class']          .= ' dropdown-item';
	$args['no_notifications_link_class'] .= ' dropdown-item';

	return $args;
}
add_filter( 'enlightenment_bp_notifications_args', 'enlightenment_bp_bootstrap_notifications_args' );

function enlightenment_bp_bootstrap_friends_notifications_toggle_label( $output ) {
	return sprintf(
		'<i class="fas fa-user-friends" role="presentation" aria-hidden="true"></i> <span class="screen-reader-text visually-hidden">%s</span>',
		$output
	);
}
add_filter( 'enlightenment_bp_friends_notifications_toggle_label', 'enlightenment_bp_bootstrap_friends_notifications_toggle_label' );

function enlightenment_bp_bootstrap_messages_notifications_toggle_label( $output ) {
	return sprintf(
		'<i class="fas fa-comments" role="presentation" aria-hidden="true"></i> <span class="screen-reader-text visually-hidden">%s</span>',
		$output
	);
}
add_filter( 'enlightenment_bp_messages_notifications_toggle_label', 'enlightenment_bp_bootstrap_messages_notifications_toggle_label' );

function enlightenment_bp_bootstrap_other_notifications_toggle_label( $output ) {
	return sprintf(
		'<i class="fas fa-bell" role="presentation" aria-hidden="true"></i> <span class="screen-reader-text visually-hidden">%s</span>',
		$output
	);
}
add_filter( 'enlightenment_bp_other_notifications_toggle_label', 'enlightenment_bp_bootstrap_other_notifications_toggle_label' );

function enlightenment_bp_bootstrap_header_account_login_args( $args ) {
	$args['container_class'] .= ' dropdown';
	$args['toggle_class']    .= ' dropdown-toggle btn btn-link p-0 border-0 rounded-0';

	$args['toggle_extra_atts']['data-bs-toggle'] = 'dropdown';

	$args['dropdown_menu_class']        .= ' dropdown-menu dropdown-menu-end';
	$args['dropdown_link_class']        .= ' dropdown-item';
	$args['dropdown_link_active_class'] .= ' active';

	return $args;
}
add_filter( 'enlightenment_bp_header_account_login_args', 'enlightenment_bp_bootstrap_header_account_login_args' );

function enlightenment_bp_bootstrap_header_account_login( $output ) {
	$output = str_replace( 'class="bbp-login-form"', 'class="bbp-login-form px-4 py-2"', $output );
	$output = str_replace( 'class="bbp-username"', 'class="bbp-username mb-3"', $output );
	$output = str_replace( 'for="user_login"', 'for="user_login" class="form-label"', $output );
	$output = str_replace( sprintf( 'id="user_login" placeholder="%s"', __( 'Username', 'enlightenment' ) ), 'id="user_login"', $output );
	$output = str_replace( 'id="user_login"', 'id="user_login" class="form-control"', $output );
	$output = str_replace( 'class="bbp-password"', 'class="bbp-password mb-3"', $output );
	$output = str_replace( 'for="user_pass"', 'for="user_pass" class="form-label"', $output );
	$output = str_replace( sprintf( 'id="user_pass" placeholder="%s"', __( 'Password', 'enlightenment' ) ), 'id="user_pass"', $output );
	$output = str_replace( 'id="user_pass"', 'id="user_pass" class="form-control"', $output );
	$output = str_replace( 'class="bbp-remember-me"', 'class="bbp-remember-me mb-3 form-check"', $output );
	$output = str_replace( 'id="rememberme"', 'id="rememberme" class="form-check-input"', $output );
	$output = str_replace( 'for="rememberme"', 'for="rememberme" class="form-check-label"', $output );
	$output = str_replace( 'class="button submit user-submit btn btn-secondary"', 'class="button submit user-submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_header_account_login', 'enlightenment_bp_bootstrap_header_account_login' );

function enlightenment_bp_bootstrap_template_notices( $output ) {
	$bp = buddypress();

	if ( empty( $bp->template_message ) ) {
		return $output;
	}

	switch ( $bp->template_message_type ) {
		case 'success':
			$type = 'success';
			$icon = 'check';
			break;

		case 'bp-sitewide-notice':
			$type = 'info';
			$icon = 'info';
			break;

		case 'error':
		default:
			$type = 'danger';
			$icon = 'exclamation';
			break;
	}

	$offset = strpos( $output, 'class="bp-tooltip"' );
	if ( false !== $offset ) {
		$alert_class = sprintf( ' alert alert-%s alert-dismissible d-flex', $type );
	} else {
		$alert_class = sprintf( ' alert alert-%s d-flex', $type );
	}

	$offset = strpos( $output, 'class="bp-feedback bp-messages ' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, $alert_class, $offset + 30, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, sprintf( 'fas fa-%s-circle mt-1 me-2 ', $icon ), $offset + 7, 0 );

		$offset_a = strpos( $output, '<strong>', $offset );
		if ( false !== $offset_a ) {
			$offset_a = strpos( $output, '<p>', $offset );
	        $output   = substr_replace( $output, '<div class="flex-grow-1">', $offset_a, 3 );
			$offset_a = strpos( $output, '<strong>', $offset_a );
			$output   = substr_replace( $output, '<h3 class="alert-heading h4">', $offset_a, 8 );
			$offset_a = strpos( $output, '</strong>', $offset_a );
			$output   = substr_replace( $output, '</h3>' . "\n" . '<p class="mb-0">', $offset_a, 9 );

			$offset_b = strpos( $output, '<br />', $offset_a );
			if ( false !== $offset_b ) {
				$output = substr_replace( $output, '', $offset_b, 6 );
			}

			$offset_a = strpos( $output, '</p>', $offset_a );
	        $output   = substr_replace( $output, "\n" . '</div>', $offset_a + 4, 0 );
		} else {
			$offset = strpos( $output, '<p>', $offset );
	        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
		}

		$offset_a = strpos( $output, 'class="bp-tooltip"', $offset );
		if ( false !== $offset_a ) {
			$output   = substr_replace( $output, ' btn-close', $offset_a + 17, 0 );
			$offset_a = strpos( $output, 'data-bp-tooltip="', $offset_a );
			$output   = substr_replace( $output, 'data-bs-dismiss="alert" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset_a, 17 );
			$offset_a = strpos( $output, '<span class="dashicons dashicons-dismiss" aria-hidden="true"></span>', $offset_a );
			$output   = substr_replace( $output, '', $offset_a, 68 );
		}
    }

	return $output;
}
add_filter( 'enlightenment_bp_template_notices', 'enlightenment_bp_bootstrap_template_notices' );

function enlightenment_bp_bootstrap_post_form_templates( $output ) {
	$offset = strpos( $output, 'class="bp-remove-item dashicons dashicons-no"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, 'fas fa-times', $offset + 22, 22 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
		$output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_wp_footer_content', 'enlightenment_bp_bootstrap_post_form_templates' );

function enlightenment_bp_bootstrap_screen_reader_text( $output ) {
	return str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );
}
add_filter( 'enlightenment_bp_activity_post_form',    'enlightenment_bp_bootstrap_screen_reader_text' );
add_filter( 'enlightenment_bp_activity_show_filters', 'enlightenment_bp_bootstrap_screen_reader_text' );
add_filter( 'enlightenment_bp_activity_filter',       'enlightenment_bp_bootstrap_screen_reader_text' );
add_filter( 'enlightenment_bp_group_activity_nav',    'enlightenment_bp_bootstrap_screen_reader_text' );

function enlightenment_bp_bootstrap_directory_nav( $output ) {
	$offset = strpos( $output, 'class="component-navigation ' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, ' nav', $offset + 27, 0 );

		$end    = strpos( $output, '</ul>', $offset );
		$offset = strpos( $output, '<li ', $offset );
		while ( false !== $offset && $offset < $end ) {
			$offset = strpos( $output, 'class="', $offset );
	        $output = substr_replace( $output, 'nav-item ', $offset + 7, 0 );
			$offset = strpos( $output, '<a ', $offset );
	        $output = substr_replace( $output, ' class="nav-link"', $offset + 2, 0 );

			$end    = strpos( $output, '</ul>', $offset );
			$offset = strpos( $output, '<li ', $offset );
		}
    }

	return $output;
}
add_filter( 'enlightenment_bp_directory_nav', 'enlightenment_bp_bootstrap_directory_nav' );

function enlightenment_bp_bootstrap_search_and_filters_bar( $output ) {
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = str_replace( 'class="subnav-filters filters no-ajax"', 'class="subnav-filters filters no-ajax row align-items-center"', $output );
	$output = str_replace( 'class="subnav-search clearfix"', 'class="subnav-search col-md flex-md-grow-1 flex-md-shrink-0 d-flex align-items-center"', $output );
	$output = str_replace( 'class="dir-search activity-search bp-search"', 'class="dir-search activity-search bp-search order-first w-100"', $output );
	$output = str_replace( 'class="component-filters clearfix"', 'class="component-filters col-md flex-md-grow-0 flex-md-shrink-1"', $output );
	$output = str_replace( 'id="dir-activity-search"', 'id="dir-activity-search" class="form-control"', $output );
	$output = str_replace( 'id="activity-filter-by"', 'id="activity-filter-by" class="form-select w-auto"', $output );
	$output = str_replace( 'id="dir-members-search"', 'id="dir-members-search" class="form-control"', $output );
	$output = str_replace( 'id="members-order-by"', 'id="members-order-by" class="form-select w-auto"', $output );
	$output = str_replace( 'id="dir-groups-search"', 'id="dir-groups-search" class="form-control"', $output );
	$output = str_replace( 'id="groups-order-by"', 'id="groups-order-by" class="form-select w-auto"', $output );
	$output = str_replace( 'id="dir-blogs-search"', 'id="dir-blogs-search" class="form-control"', $output );
	$output = str_replace( 'id="blogs-order-by"', 'id="blogs-order-by" class="form-select w-auto"', $output );
	$output = str_replace( 'class="nouveau-search-submit"', 'class="nouveau-search-submit btn btn-light"', $output );
	$output = str_replace( 'class="dashicons dashicons-search"', 'class="fas fa-search"', $output );

	$offset = strpos( $output, 'class="bp-dir-search-form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, '<div class="input-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_search_and_filters_bar', 'enlightenment_bp_bootstrap_search_and_filters_bar' );

function enlightenment_bp_bootstrap_member_header_actions_args( $args ) {
	$args['container'] = 'div';

	return $args;
}
add_filter( 'enlightenment_bp_member_header_actions_args', 'enlightenment_bp_bootstrap_member_header_actions_args' );

function enlightenment_bp_filter_header_actions_buttons_args( $args ) {
	if( ! doing_action( 'bp_member_header_actions' ) && ! doing_action( 'bp_directory_members_actions' ) && ! doing_action( 'wp_ajax_bp_follow' ) && ! doing_action( 'wp_ajax_bp_unfollow' ) ) {
		return $args;
	}

	if( function_exists( 'bp_follow_add_profile_follow_button' ) ) {
		if( doing_action( 'bp_follow_get_add_follow_button' ) ) {
			$args['wrapper_class'] .= ' btn-group';
			$args['link_class']    .= ' btn btn-secondary';
		} else {
			$args['link_class']    .= ' dropdown-item';
		}
	} else {
		if( doing_action( 'bp_get_add_friend_button' ) ) {
			$args['wrapper_class'] .= ' btn-group';
			$args['link_class']    .= ' btn btn-secondary';
		} else {
			$args['link_class']    .= ' dropdown-item';
		}
	}

	return $args;
}
// add_filter( 'bp_follow_get_add_follow_button', 'enlightenment_bp_filter_header_actions_buttons_args' );
// add_filter( 'bp_get_add_friend_button', 'enlightenment_bp_filter_header_actions_buttons_args' );
// add_filter( 'bp_get_send_public_message_button', 'enlightenment_bp_filter_header_actions_buttons_args' );
// add_filter( 'bp_get_send_message_button_args', 'enlightenment_bp_filter_header_actions_buttons_args' );

function enlightenment_bp_directory_members_actions_open_btn_group() {
	global $members_template;

	if( ! is_user_logged_in() ) {
		return;
	}

	if( bp_is_my_profile() && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	if( bp_loggedin_user_id() === bp_displayed_user_id() && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	if( isset( $members_template ) && bp_loggedin_user_id() == $members_template->member->ID && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	echo enlightenment_open_tag( 'div', 'btn-group' );
}
// add_action( 'bp_directory_members_actions', 'enlightenment_bp_directory_members_actions_open_btn_group', 4 );

function enlightenment_bp_member_header_actions_open_dropdown() {
	global $members_template;

	if( ! is_user_logged_in() ) {
		return;
	}

	if( bp_is_my_profile() ) {
		return;
	}

	if( bp_loggedin_user_id() === bp_displayed_user_id() ) {
		return;
	}

	if( isset( $members_template ) && bp_loggedin_user_id() == $members_template->member->ID ) {
		return;
	}

	echo enlightenment_open_tag( 'div', 'more-user-actions btn-group dropdown', 'more-user-actions' );
	echo enlightenment_open_tag( 'a', 'btn btn-secondary dropdown-toggle', 'user-actions-dropdown-toggle', array(
		'href'          => '#',
		'data-bs-toggle'   => 'dropdown',
		//'data-bs-target'   => '#more-user-actions',
		'aria-haspopup' => 'true',
		'aria-expanded' => 'false',
	) );
	echo enlightenment_close_tag( 'a' );
	echo enlightenment_open_tag( 'div', 'dropdown-menu', 'user-actions-popup', array(
		'aria-labelledby' => 'user-actions-dropdown-toggle',
	) );
}
// add_action( 'bp_member_header_actions', 'enlightenment_bp_member_header_actions_open_dropdown', 6 );

function enlightenment_bp_member_header_actions_close_dropdown() {
	global $members_template;

	if( ! is_user_logged_in() ) {
		return;
	}

	if( bp_is_my_profile() ) {
		return;
	}

	if( bp_loggedin_user_id() === bp_displayed_user_id() ) {
		return;
	}

	if( isset( $members_template ) && bp_loggedin_user_id() == $members_template->member->ID ) {
		return;
	}

	enlightenment_close_div();
	enlightenment_close_div();
}
// add_action( 'bp_member_header_actions', 'enlightenment_bp_member_header_actions_close_dropdown', 999 );

function enlightenment_bp_bootstrap_member_header_actions( $output ) {
	$output = str_replace( 'class="member-header-actions action"', 'class="member-header-actions action btn-group"', $output );

	$start = strpos( $output, '<div class="member-header-actions action btn-group">' );
	if ( false !== $start ) {
		$start += 52;
		$end    = strpos( $output, '</div></div>', $start ) + 6;
		$offset = strpos( $output, '<div ', $start );

		if ( false !== $offset && $offset < $end ) {
			// Start with second element
			$offset = strpos( $output, '<div ', $offset + 1 );
			$end    = strpos( $output, '</div></div>', $start ) + 6;

			if ( false !== $offset && $offset < $end ) {
				$output = substr_replace( $output, '<div class="more-user-actions btn-group dropdown" id="more-user-actions"><a class="btn btn-primary btn-lg dropdown-toggle" id="user-actions-dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a><div class="dropdown-menu dropdown-menu-end" id="user-actions-popup" aria-labelledby="user-actions-dropdown-toggle">', $offset, 0 );
				$offset = strpos( $output, '</div></div>', $offset );
				$output = substr_replace( $output, '</div></div>', $offset + 6, 0 );
			}
		}
	}
	// $output = str_replace( 'class=" friendship-button ', 'class=" friendship-button btn-group ', $output );
	// $output = str_replace( 'class=" friendship-button ', 'class=" friendship-button btn-group ', $output );

	return $output;
}
add_filter( 'enlightenment_bp_member_header_actions', 'enlightenment_bp_bootstrap_member_header_actions' );

function enlightenment_bp_bootstrap_members_buttons( $buttons, $user_id, $type ) {
	if ( wp_doing_ajax() ) {
		return $buttons;
	}

	$did_one = false;

	foreach ( $buttons as $key => $button ) {
		if ( $did_one ) {
			$buttons[ $key ]['button_attr']['class'] .= ' dropdown-item';
		} else {
			$buttons[ $key ]['parent_attr']['class'] .= ' btn-group';
			$buttons[ $key ]['button_attr']['class'] .= ' btn btn-primary btn-lg';
		}

		$did_one = true;
	}

	return $buttons;
}
add_filter( 'bp_nouveau_get_members_buttons', 'enlightenment_bp_bootstrap_members_buttons', 10, 3 );

// Filter separately for AJAX
function enlightenment_bp_bootstrap_add_friend_button( $args ) {
	if ( ! wp_doing_ajax() ) {
		return $args;
	}

	remove_filter( 'bp_get_add_friend_button', 'enlightenment_bp_bootstrap_add_friend_button' );
	$buttons = bp_nouveau_get_members_buttons( array( 'type' => ( bp_is_user() ? 'profile' : 'header' ) ) );
	add_filter( 'bp_get_add_friend_button', 'enlightenment_bp_bootstrap_add_friend_button' );

	if ( ! isset( $buttons['member_friendship'] ) ) {
		return $args;
	}

	$keys = array_keys( $buttons );


	if ( 'member_friendship' == $keys[0] ) {
		$args['wrapper_class'] .= ' btn-group';
		$args['link_class']    .= ' btn btn-primary btn-lg';
	} else {
		$args['link_class'] .= ' dropdown-item';
	}

	return $args;
}
// add_filter( 'bp_get_add_friend_button', 'enlightenment_bp_bootstrap_add_friend_button' );

// Filter separately for AJAX
function enlightenment_bp_bootstrap_send_public_message_button( $args ) {
	if ( ! wp_doing_ajax() ) {
		return $args;
	}

	remove_filter( 'bp_get_send_public_message_button', 'enlightenment_bp_bootstrap_send_public_message_button' );
	$buttons = bp_nouveau_get_members_buttons( array( 'type' => ( bp_is_user() ? 'profile' : 'header' ) ) );
	add_filter( 'bp_get_send_public_message_button', 'enlightenment_bp_bootstrap_send_public_message_button' );

	if ( ! isset( $buttons['public_message'] ) ) {
		return $args;
	}

	$keys = array_keys( $buttons );

	if ( 'public_message' == $keys[0] ) {
		if ( ! isset( $args['wrapper_class'] ) ) {
			$args['wrapper_class']  = 'btn-group';
		} else {
			$args['wrapper_class'] .= ' btn-group';
		}

		$args['link_class'] .= ' btn btn-primary btn-lg';
	} else {
		$args['link_class'] .= ' dropdown-item';
	}

	return $args;
}
// add_filter( 'bp_get_send_public_message_button', 'enlightenment_bp_bootstrap_send_public_message_button' );

function enlightenment_bp_directory_members_actions_open_dropdown() {
	global $members_template;

	if( ! is_user_logged_in() ) {
		return;
	}

	if( bp_is_my_profile() && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	if( bp_loggedin_user_id() === bp_displayed_user_id() && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	if( isset( $members_template ) && bp_loggedin_user_id() == $members_template->member->ID && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	echo enlightenment_open_tag( 'div', 'more-user-actions btn-group dropdown', 'more-user-actions' );
	echo enlightenment_open_tag( 'a', 'btn btn-secondary dropdown-toggle', 'user-actions-dropdown-toggle', array(
		'href'          => '#',
		'data-bs-toggle'   => 'dropdown',
		//'data-bs-target'   => '#more-user-actions',
		'aria-haspopup' => 'true',
		'aria-expanded' => 'false',
	) );
	echo enlightenment_close_tag( 'a' );
	echo enlightenment_open_tag( 'div', 'dropdown-menu', 'user-actions-popup', array(
		'aria-labelledby' => 'user-actions-dropdown-toggle',
	) );
}
// add_action( 'bp_directory_members_actions', 'enlightenment_bp_directory_members_actions_open_dropdown', 6 );

function enlightenment_bp_directory_members_actions_close_dropdown() {
	global $members_template;

	if( ! is_user_logged_in() ) {
		return;
	}

	if( bp_is_my_profile() && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	if( bp_loggedin_user_id() === bp_displayed_user_id() && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	if( isset( $members_template ) && bp_loggedin_user_id() == $members_template->member->ID && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	enlightenment_close_div();
	enlightenment_close_div();
}
// add_action( 'bp_directory_members_actions', 'enlightenment_bp_directory_members_actions_close_dropdown', 999 );

function enlightenment_bp_directory_members_actions_close_btn_group() {
	global $members_template;

	if( ! is_user_logged_in() ) {
		return;
	}

	if( bp_is_my_profile() && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	if( bp_loggedin_user_id() === bp_displayed_user_id() && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	if( isset( $members_template ) && bp_loggedin_user_id() == $members_template->member->ID && ! ( bp_is_user_friends() || bp_is_current_action( 'following' ) || bp_is_current_action( 'followers' ) ) ) {
		return;
	}

	enlightenment_close_div();
}
// add_action( 'bp_directory_members_actions', 'enlightenment_bp_directory_members_actions_close_btn_group', 999 );

function enlightenment_bp_group_members_list_item_maybe_ob_start() {
	if( bp_is_active( 'friends' ) ) {
		ob_start();
	}
}
// add_action( 'bp_group_members_list_item', 'enlightenment_bp_group_members_list_item_maybe_ob_start', 999 );

function enlightenment_bp_group_members_list_item_action() {
	global $members_template;

	$output = ob_get_clean();

	if( ! is_user_logged_in() ) {
		echo $output;
		return;
	}

	if( isset( $members_template ) && bp_loggedin_user_id() == $members_template->member->ID ) {
		echo $output;
		return;
	}

	if( function_exists( 'bp_follow_add_group_member_follow_button' ) ) {
		$friend_button = bp_get_add_friend_button( bp_get_group_member_id(), bp_get_group_member_is_friend() );

		$output = str_replace( $friend_button, '', $output );

		$friend_button = str_replace( '" class="friendship-button', '" class="friendship-button dropdown-item', $friend_button );

		$output = str_replace( '<div class="action">', '<div class="action btn-group" role="group">', $output );
		$output = str_replace( 'class="follow-button', 'class="follow-button btn-group', $output );
		$output = str_replace( 'class="follow"', 'class="follow btn btn-secondary"', $output );
		$output = str_replace( 'class="unfollow"', 'class="unfollow btn btn-secondary"', $output );

		$output .= enlightenment_open_tag( 'div', 'more-user-actions btn-group dropdown' );
		$output .= enlightenment_open_tag( 'a', 'btn btn-secondary dropdown-toggle', '', array(
			'href'          => '#',
			'data-bs-toggle'   => 'dropdown',
			'aria-haspopup' => 'true',
			'aria-expanded' => 'false',
		) );
		$output .= enlightenment_close_tag( 'a' );
		$output .= enlightenment_open_tag( 'div', 'dropdown-menu' );
		$output .= $friend_button;
		$output .= enlightenment_close_tag( 'div' );
		$output .= enlightenment_close_tag( 'div' );
	} else {
		$output = str_replace( '<div class="friendship-button', '<div class="friendship-button btn-group', $output );
		$output = str_replace( '" class="friendship-button', '" class="friendship-button btn btn-secondary', $output );
	}

	echo $output;
}
// add_action( 'bp_group_members_list_item_action', 'enlightenment_bp_group_members_list_item_action', 999 );

function enlightenment_bp_bootstrap_displayed_user_nav( $output ) {
	$output = str_replace( '<ul>', '<ul class="nav">', $output );
	$output = str_replace( 'class="bp-priority-object-nav-nav-items"', 'class="bp-priority-object-nav-nav-items nav d-flex"', $output );
	$output = str_replace( 'class="bp-personal-tab ', 'class="bp-personal-tab nav-item ', $output );
	$output = str_replace( 'class="bp-personal-tab"', 'class="bp-personal-tab nav-item"', $output );
	$output = str_replace( '<a ', '<a class="nav-link" ', $output );

	$offset = strpos( $output, 'class="bp-personal-tab nav-item current selected"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="nav-link"', $offset );
		$output = substr_replace( $output, ' active', $offset + 15, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_displayed_user_nav', 'enlightenment_bp_bootstrap_displayed_user_nav' );

function enlightenment_bp_bootstrap_template_member_front_output( $output ) {
	$offset = strpos( $output, 'class="bp-feedback custom-homepage-info info"' );
	if ( false !== $offset ) {
		$start  = strpos( $output, '<button ', $offset );
		$end    = strpos( $output, '</button>', $start ) + 9;
		$length = $end - $start;
		$button = substr( $output, $start, $length );
		$output = substr_replace( $output, '', $start, $length );
		$start  = strpos( $output, '<strong>', $offset );
		$output = substr_replace( $output, $button . "\n", $start, 0 );
	}

	$offset = strpos( $output, 'class="bp-feedback custom-homepage-info info"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="bp-tooltip"', $offset );
		$output = substr_replace( $output, ' close', $offset + 17, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-dismiss"', $offset );
		$output = substr_replace( $output, '', $offset, 35 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '&times;', $offset + 1, 0 );
		$offset = strpos( $output, '<strong>', $offset );
		$output = substr_replace( $output, '<h3 class="alert-heading h4">', $offset, 8 );
		$offset = strpos( $output, '</strong>', $offset );
		$output = substr_replace( $output, '</h3>', $offset, 9 );
		$offset = strpos( $output, '<br/>', $offset );
		$output = substr_replace( $output, '', $offset, 5 );
	}

	$output = str_replace( 'class="bp-feedback custom-homepage-info info"', 'class="bp-feedback custom-homepage-info info alert alert-info"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_front_output', 'enlightenment_bp_bootstrap_template_member_front_output' );

function enlightenment_bp_bootstrap_template_member_subnav( $output ) {
	$output = str_replace( 'class="subnav ', 'class="subnav list-unstyled btn-group d-inline-flex mb-0 ', $output );
	$output = str_replace( 'class="subnav"', 'class="subnav list-unstyled btn-group d-inline-flex mb-0"', $output );
	$output = str_replace( 'class="bp-personal-sub-tab ', 'class="bp-personal-sub-tab btn-group ', $output );
	$output = str_replace( 'class="bp-personal-sub-tab"', 'class="bp-personal-sub-tab btn-group"', $output );

	$offset = strpos( $output, 'class="bp-personal-sub-tab btn-group"' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<a ', $offset );
		$output = substr_replace( $output, 'class="btn btn-outline-secondary" ', $offset + 3, 0 );

		$offset = strpos( $output, 'class="bp-personal-sub-tab btn-group"', $offset );
	}

	$offset = strpos( $output, 'class="bp-personal-sub-tab btn-group current selected"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<a ', $offset );
		$output = substr_replace( $output, 'class="btn btn-secondary" ', $offset + 3, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_profile_output', 'enlightenment_bp_bootstrap_template_member_subnav' );
add_filter( 'enlightenment_bp_filter_template_member_notifications_output', 'enlightenment_bp_bootstrap_template_member_subnav' );
add_filter( 'enlightenment_bp_filter_template_member_messages_output', 'enlightenment_bp_bootstrap_template_member_subnav' );
add_filter( 'enlightenment_bp_filter_template_member_friends_output', 'enlightenment_bp_bootstrap_template_member_subnav' );
add_filter( 'enlightenment_bp_filter_template_member_groups_output', 'enlightenment_bp_bootstrap_template_member_subnav' );
add_filter( 'enlightenment_bp_filter_template_member_blogs_output', 'enlightenment_bp_bootstrap_template_member_subnav' );
add_filter( 'enlightenment_bp_filter_template_member_settings_output', 'enlightenment_bp_bootstrap_template_member_subnav' );
add_filter( 'enlightenment_bp_filter_template_member_plugins_output', 'enlightenment_bp_bootstrap_template_member_subnav' );

function enlightenment_bp_bootstrap_template_member_search_and_filters( $output ) {
	$output = str_replace( 'class="subnav-filters ', 'class="subnav-filters d-flex align-items-center ', $output );
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );

	$offset = strpos( $output, 'class="component-filters clearfix"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, 'ms-md-auto', $offset + 25, 8 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
		$output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, '<select ', $offset );
		$output = substr_replace( $output, 'class="form-select" ', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_activity_output', 'enlightenment_bp_bootstrap_template_member_search_and_filters' );
add_filter( 'enlightenment_bp_filter_template_member_notifications_output', 'enlightenment_bp_bootstrap_template_member_search_and_filters' );
add_filter( 'enlightenment_bp_filter_template_member_friends_output', 'enlightenment_bp_bootstrap_template_member_search_and_filters' );
add_filter( 'enlightenment_bp_filter_template_member_groups_output', 'enlightenment_bp_bootstrap_template_member_search_and_filters' );
add_filter( 'enlightenment_bp_filter_template_member_blogs_output', 'enlightenment_bp_bootstrap_template_member_search_and_filters' );

function enlightenment_bp_bootstrap_pagination( $output ) {
	// $output = str_replace( 'class="bp-pagination ', 'class="bp-pagination d-flex align-items-center ', $output );
	$output = str_replace( 'class="bp-pagination ', 'class="bp-pagination d-md-flex align-items-md-center ', $output );
	// $output = str_replace( 'class="pag-data"', 'class="pag-data mb-0"', $output );
	$output = str_replace( 'class="pag-data"', 'class="pag-data mb-md-0"', $output );

	$offset = strpos( $output, 'class="bp-pagination ' );
    while ( false !== $offset ) {
        // $output = substr_replace( $output,  ' d-flex align-items-center', $offset + 20, 0 );
		// $offset = strpos( $output, 'class="pag-data"', $offset );
        // $output = substr_replace( $output, ' mb-0', $offset + 15, 0 );

		$offset_a = strpos( $output, 'class="bp-pagination-links ', $offset );
		if ( false !== $offset_a ) {
	        $output   = substr_replace( $output, ' ms-auto', $offset_a + 26, 0 );
			// $offset_a = strpos( $output, '<p class="pag-data mb-0">', $offset_a );
			$offset_a = strpos( $output, '<p class="pag-data mb-md-0">', $offset_a );
	        // $output   = substr_replace( $output, '<ul class="pag-data pagination">', $offset_a, 25 );
			$output   = substr_replace( $output, '<ul class="pag-data pagination">', $offset_a, 28 );

			$offset_b = strpos( $output, '<span ', $offset_a );
			$end_b    = strpos( $output, '</p>', $offset_a );
			while ( false !== $offset_b && $offset_b < $end_b ) {
				$output   = substr_replace( $output, '<li class="page-item">', $offset_b, 0 );
				$offset_b = strpos( $output, '</span>', $offset_b );
				$output   = substr_replace( $output, '</li>', $offset_b + 7, 0 );

				$end_b    = strpos( $output, '</p>', $offset_a );
				$offset_b = strpos( $output, '<span ', $offset_b );
			}

			$offset_b = strpos( $output, '<a ', $offset_a );
			$end_b    = strpos( $output, '</p>', $offset_a );
			while ( false !== $offset_b && $offset_b < $end_b ) {
				$output   = substr_replace( $output, '<li class="page-item">', $offset_b, 0 );
				$offset_b = strpos( $output, '</a>', $offset_b );
				$output   = substr_replace( $output, '</li>', $offset_b + 4, 0 );

				$end_b    = strpos( $output, '</p>', $offset_a );
				$offset_b = strpos( $output, '<a ', $offset_b );
			}

			$offset_b = strpos( $output, '<li class="page-item">', $offset_a );
			$end_b    = strpos( $output, '</p>', $offset_a );
			while ( false !== $offset_b && $offset_b < $end_b ) {
				$offset_c = strpos( $output, 'class="page-numbers current"', $offset_b );
				$end_c    = strpos( $output, '</li>', $offset_b );

				if ( false !== $offset_c && $offset_c < $end_c ) {
					$output = substr_replace( $output, ' active', $offset_b + 20, 0 );
					break;
				}

				$end_b    = strpos( $output, '</p>', $offset_a );
				$offset_b = strpos( $output, '<li class="page-item">', $offset_b + 1 );
			}

			$offset_a = strpos( $output, '</p>', $offset_a );
	        $output = substr_replace( $output, '</ul>', $offset_a, 4 );
		}

		$offset = strpos( $output, 'class="bp-pagination ', $offset + 1 );
    }

	$output = str_replace( 'class="page-numbers current"', 'class="page-numbers current page-link"', $output );
	$output = str_replace( 'class="page-numbers"', 'class="page-numbers page-link"', $output );
	$output = str_replace( 'class="page-numbers dots"', 'class="page-numbers dots page-link"', $output );
	$output = str_replace( 'class="prev page-numbers"', 'class="prev page-numbers page-link"', $output );
	$output = str_replace( 'class="next page-numbers"', 'class="next page-numbers page-link"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_members/single/notifications/notifications-loop_output', 'enlightenment_bp_bootstrap_pagination' );
add_filter( 'enlightenment_bp_filter_template_member_friends_output', 'enlightenment_bp_bootstrap_pagination' );
add_filter( 'enlightenment_bp_filter_template_members/members-loop_output', 'enlightenment_bp_bootstrap_pagination' );
add_filter( 'enlightenment_bp_filter_template_member_groups_output', 'enlightenment_bp_bootstrap_pagination' );
add_filter( 'enlightenment_bp_filter_template_groups/groups-loop_output', 'enlightenment_bp_bootstrap_pagination' );
add_filter( 'enlightenment_bp_filter_template_groups/single/members-loop_output', 'enlightenment_bp_bootstrap_pagination' );
add_filter( 'enlightenment_bp_filter_template_groups/single/requests-loop_output', 'enlightenment_bp_bootstrap_pagination' );
add_filter( 'enlightenment_bp_filter_template_blogs/blogs-loop_output', 'enlightenment_bp_bootstrap_pagination' );

function enlightenment_bp_bootstrap_ajax_loader( $output ) {
	$offset = strpos( $output, '<div id="bp-ajax-loader">' );
    if ( false !== $offset ) {
        $offset = strpos( $output, 'class="bp-feedback bp-messages loading"', $offset );
        $output = substr_replace( $output, 'd-flex align-items-center justify-content-center ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-spinner fa-pulse ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="ms-2 mb-0"', $offset + 2, 0 );
    }

	return $output;
}
add_filter( 'enlightenment_bp_activity_loop', 'enlightenment_bp_bootstrap_ajax_loader' );
add_filter( 'enlightenment_bp_members_loop', 'enlightenment_bp_bootstrap_ajax_loader' );
add_filter( 'enlightenment_bp_groups_loop', 'enlightenment_bp_bootstrap_ajax_loader' );
add_filter( 'enlightenment_bp_filter_template_member_activity_output', 'enlightenment_bp_bootstrap_ajax_loader' );
add_filter( 'enlightenment_bp_filter_template_member_notifications_output', 'enlightenment_bp_bootstrap_ajax_loader' );
add_filter( 'enlightenment_bp_filter_template_member_friends_output', 'enlightenment_bp_bootstrap_ajax_loader' );
add_filter( 'enlightenment_bp_filter_template_member_groups_output', 'enlightenment_bp_bootstrap_ajax_loader' );
add_filter( 'enlightenment_bp_filter_template_member_blogs_output', 'enlightenment_bp_bootstrap_ajax_loader' );
add_filter( 'enlightenment_bp_filter_template_group_members_output', 'enlightenment_bp_bootstrap_ajax_loader' );
add_filter( 'enlightenment_bp_filter_group_admin_membership-requests_screen', 'enlightenment_bp_bootstrap_ajax_loader' );
add_filter( 'enlightenment_bp_blogs_loop', 'enlightenment_bp_bootstrap_ajax_loader' );

function enlightenment_bp_bootstrap_template_member_activity_output( $output ) {
    $output = str_replace( 'class="bp-screen-title bp-screen-reader-text"', 'class="bp-screen-title bp-screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_activity_output', 'enlightenment_bp_bootstrap_template_member_activity_output' );

function enlightenment_bp_bootstrap_member_activity_post_form( $output ) {
	return str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );
}
add_filter( 'enlightenment_bp_filter_member_activity_post_form', 'enlightenment_bp_bootstrap_member_activity_post_form' );

function enlightenment_bp_bootstrap_template_activity_activity_loop_output( $output ) {
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );

	$offset = strpos( $output, 'class="bp-feedback bp-messages info"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	$output = str_replace( 'class="button large primary button-primary"', 'class="button large primary button-primary btn btn-primary"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_activity/activity-loop_output', 'enlightenment_bp_bootstrap_template_activity_activity_loop_output' );

function enlightenment_bp_bootstrap_activity_entry_buttons( $buttons ) {
    foreach ( $buttons as $key => $button ) {
		if ( isset( $buttons[ $key ]['link_text'] ) ) {
	        $buttons[ $key ]['link_text'] = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $button['link_text'] );
		}

		if ( isset( $buttons[ $key ]['button_attr']['data-bp-tooltip'] ) ) {
			$buttons[ $key ]['button_attr']['data-bs-toggle']    = 'tooltip';
			$buttons[ $key ]['button_attr']['data-bs-placement'] = 'top';
			$buttons[ $key ]['button_attr']['title']             = $button['button_attr']['data-bp-tooltip'];

	        unset( $buttons[ $key ]['button_attr']['data-bp-tooltip'] );
		}
    }

    return $buttons;
}
add_filter( 'bp_nouveau_get_activity_entry_buttons', 'enlightenment_bp_bootstrap_activity_entry_buttons' );

function enlightenment_bp_bootstrap_template_activity_type_parts_content_new_avatar_output( $output ) {
	$output = str_replace( 'class="button large primary button-primary"', 'class="button large primary button-primary btn btn-primary"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_activity/type-parts/content-new-avatar_output', 'enlightenment_bp_bootstrap_template_activity_type_parts_content_new_avatar_output' );

function enlightenment_bp_bootstrap_activity_entry_comments( $output ) {
	$offset = strpos( $output, 'class="ac-form"' );
    while ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '<div class="d-flex">', $offset + 1, 0 );
		$offset = strpos( $output, '</form>', $offset );
        $output = substr_replace( $output, '</div>' . "\n", $offset, 0 );

		$offset = strpos( $output, 'class="ac-form"', $offset );
    }

	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="ac-reply-avatar"', 'class="ac-reply-avatar flex-grow-0 flex-shrink-1 me-2"', $output );
	$output = str_replace( 'class="ac-reply-content"', 'class="ac-reply-content flex-grow-1 flex-shrink-0"', $output );
	$output = str_replace( 'class="ac-textarea"', 'class="ac-textarea mb-2"', $output );
	$output = str_replace( 'class="ac-input bp-suggestions"', 'class="ac-input bp-suggestions form-control"', $output );
	$output = str_replace( 'name="ac_form_submit"', 'name="ac_form_submit" class="btn btn-secondary btn-sm"', $output );
	$output = str_replace( 'class="ac-reply-cancel"', 'class="ac-reply-cancel btn btn-secondary btn-sm"', $output );

	return $output;
}
add_action( 'enlightenment_bp_filter_activity_entry_comments', 'enlightenment_bp_bootstrap_activity_entry_comments' );

function enlightenment_bp_bootstrap_activity_comments_thread( $output, $end_ul ) {
	$document = new DOMDocument();

	libxml_use_internal_errors( true );
	$document->loadHTML( $output . $end_ul );
	libxml_clear_errors();

	$has_filter = has_filter( 'bp_activity_recurse_comments_start_ul', 'enlightenment_bp_filter_activity_comments_thread_ob_start' );
	if ( $has_filter ) {
		remove_filter( 'bp_activity_recurse_comments_start_ul', 'enlightenment_bp_filter_activity_comments_thread_ob_start', $has_filter );
	}

	$output = apply_filters( 'bp_activity_recurse_comments_start_ul', '<ul>' );

	if ( $has_filter ) {
		add_filter( 'bp_activity_recurse_comments_start_ul', 'enlightenment_bp_filter_activity_comments_thread_ob_start', $has_filter );
	}

	$items = $document->getElementsByTagName( 'li' );

	if( $items->length ) {
		foreach ( $items as $item ) {
			// Only handle direct child nodes
			if ( 'body' != $item->parentNode->parentNode->tagName ) {
				continue;
			}

			$li = new DOMDocument();
			$li->appendChild( $li->importNode( $item, true ) );

			$comment = trim( $li->saveHTML() );

			$offset = strpos( $comment, '<li ' );
		    if ( false !== $offset ) {
				$offset  = strpos( $comment, '<div class="acomment-avatar item-avatar"', $offset );
				$comment = substr_replace( $comment, ' me-2', $offset + 27, 0 );
				$comment = substr_replace( $comment, '<div class="d-flex">' . "\n", $offset, 0 );
				$offset  = strpos( $comment, '</div>', $offset );
				$comment = substr_replace( $comment, "\n" . '<div class="flex-grow-1">', $offset + 6, 0 );
				$comment = substr_replace( $comment, '</div>' . "\n" . '</div>' . "\n", -5, 0 );
			}

			$output .= $comment;
		}
	}

	return $output;
}
add_action( 'enlightenment_bp_filter_activity_comments_thread', 'enlightenment_bp_bootstrap_activity_comments_thread', 10, 2 );

function enlightenment_bp_bootstrap_activity_recurse_comments_start_ul( $output ) {
	return str_replace( 'class="activity-comments-list"', 'class="activity-comments-list list-unstyled mb-0"', $output );
}
add_filter( 'bp_activity_recurse_comments_start_ul', 'enlightenment_bp_bootstrap_activity_recurse_comments_start_ul', 12 );

function enlightenment_bp_bootstrap_ajax_new_activity_comment_error_class( $class ) {
	$class .= ' alert alert-danger mt-2 mb-0';

	return $class;
}
add_filter( 'enlightenment_bp_ajax_new_activity_comment_error_class', 'enlightenment_bp_bootstrap_ajax_new_activity_comment_error_class' );

function enlightenment_bp_bootstrap_template_activity_comment_output( $output ) {

	$offset = strpos( $output, '<li ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<div class="acomment-avatar item-avatar"', $offset );
		$output = substr_replace( $output, ' me-2', $offset + 27, 0 );
		$output = substr_replace( $output, '<div class="d-flex">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="flex-grow-1">', $offset + 6, 0 );

		if ( false !== strpos( $output, '<ul class="rtmedia-list rtm-activity-media-list ', $offset ) ) {
			$offset = strpos( $output, '</ul>', $offset );
		}

		$offset = strpos( $output, '</li>', $offset );
		$output = substr_replace( $output, '</div>' . "\n" . '</div>' . "\n", $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_activity/comment_output', 'enlightenment_bp_bootstrap_template_activity_comment_output' );

function enlightenment_bp_bootstrap_template_member_profile_public_output( $output ) {
	$start = strpos( $output, '<table class="profile-fields bp-tables-user">' );
	$end   = strpos( $output, '<table class="profile-fields bp-tables-user">', $start );
	while ( false !== $start ) {
		$offset = $start;
		$output = substr_replace( $output, '<div class="table-responsive">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</table>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );

		$start = strpos( $output, '<table class="profile-fields bp-tables-user">', $offset );
	}

	$output = str_replace( 'class="profile-fields bp-tables-user"', 'class="profile-fields bp-tables-user table"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_profile_public_output', 'enlightenment_bp_bootstrap_template_member_profile_public_output' );

function enlightenment_bp_bootstrap_member_profile_editfield( $output ) {
	$offset = strpos( $output, 'class="editfield ' );
	while ( false !== $offset ) {
		$start  = strpos( $output, 'field_type_', $offset ) + 11;
		$end    = strpos( $output, '"', $start );
		$length = $end - $start;
		$type   = substr( $output, $start, $length );

		switch ( $type ) {
			case 'textbox':
			case 'telephone':
			case 'url':
			case 'number':
				$offset = strpos( $output, '<fieldset>', $offset );
				$output = substr_replace( $output, '<div class="mb-3 row">', $offset, 10 );
				$offset = strpos( $output, '<legend id="', $offset );
				$output = substr_replace( $output, '<div class="col-sm-3"><label class="col-form-label" for="', $offset, 12 );
				$offset = strpos( $output, '-1"', $offset );
				$output = substr_replace( $output, '', $offset, 2 );
				$offset = strpos( $output, '</legend>', $offset );
				$output = substr_replace( $output, '</label></div>', $offset, 9 );
				$output = substr_replace( $output, "\n" . '<div class="col-sm-9">', $offset + 14, 0 );
				$offset = strpos( $output, '<input ', $offset );
				$output = substr_replace( $output, 'class="form-control" ', $offset + 7, 0 );

				$offset_a = strpos( $output, '<p class="description"', $offset );
				$end_a    = strpos( $output, '</fieldset>', $offset );
				if ( false !== $offset_a && $offset_a < $end_a ) {
					$output = substr_replace( $output, '<div class="description form-text"', $offset_a, 22 );
					$offset_a = strpos( $output, '</p>', $offset_a );
					$output = substr_replace( $output, '</div>', $offset_a, 4 );
				}

				$offset = strpos( $output, '</fieldset>', $offset );
				$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
				$offset = strpos( $output, '</fieldset>', $offset );
				$output = substr_replace( $output, '</div>', $offset, 11 );

				break;

			case 'selectbox':
			case 'multiselectbox':
				$offset = strpos( $output, '<fieldset>', $offset );
				$output = substr_replace( $output, '<div class="mb-3 row">', $offset, 10 );
				$offset = strpos( $output, '<legend id="', $offset );
				$output = substr_replace( $output, '<div class="col-sm-3"><label class="col-form-label" for="', $offset, 12 );
				$offset = strpos( $output, '-1"', $offset );
				$output = substr_replace( $output, '', $offset, 2 );
				$offset = strpos( $output, '</legend>', $offset );
				$output = substr_replace( $output, '</label></div>' . "\n" . '<div class="col-sm-9">', $offset, 9 );
				$offset = strpos( $output, '<select ', $offset );
				$output = substr_replace( $output, 'class="form-select" ', $offset + 8, 0 );

				$offset_a = strpos( $output, '<p class="description"', $offset );
				$end_a    = strpos( $output, '</fieldset>', $offset );
				if ( false !== $offset_a && $offset_a < $end_a ) {
					$start  = $offset_a;
					$end    = strpos( $output, '</p>', $start ) + 4;
					$length = $end - $start;
					$tag    = substr( $output, $start, $length );
					$tag    = str_replace( '<p class="description"', '<div class="description form-text"', $tag );
					$tag    = str_replace( '</p>', '</div>', $tag );
					$output = substr_replace( $output, '', $start, $length );

					$offset_c = strpos( $output, 'class="clear-value"', $offset );
					$end_c    = strpos( $output, '</fieldset>', $offset );
					if ( $offset_c !== $offset_b && $offset_c < $end_c ) {
						$offset = strpos( $output, '</a>', $offset ) + 4;
					} else {
						$offset = strpos( $output, '</select>', $offset ) + 9;
					}

					$output = substr_replace( $output, "\n" . $tag, $offset, 0 );
				}

				$offset = strpos( $output, '</fieldset>', $offset );
				$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
				$offset = strpos( $output, '</fieldset>', $offset );
				$output = substr_replace( $output, '</div>', $offset, 11 );

				break;

			case 'textarea':
				$offset = strpos( $output, '<fieldset>', $offset );
				$output = substr_replace( $output, '<div class="mb-3 row">', $offset, 10 );
				$offset = strpos( $output, '<legend id="', $offset );
				$output = substr_replace( $output, '<div class="col-sm-3"><label class="col-form-label" for="', $offset, 12 );
				$offset = strpos( $output, '-1"', $offset );
				$output = substr_replace( $output, '', $offset, 2 );
				$offset = strpos( $output, '</legend>', $offset );
				$output = substr_replace( $output, '</label></div>', $offset, 9 );
				$output = substr_replace( $output, "\n" . '<div class="col-sm-9">', $offset + 14, 0 );
				$offset = strpos( $output, '<textarea ', $offset );

				$offset_a = strpos( $output, 'name="field_', $offset );
				if ( false !== $offset_a ) {
					$start_a  = $offset_a + 12;
					$end_a    = strpos( $output, '"', $start_a );
					$length_a = $end_a - $start_a;
					$field_id = substr( $output, $start_a, $length_a );

					if ( ! bp_xprofile_is_richtext_enabled_for_field( $field_id ) ) {
						$output = substr_replace( $output, 'class="form-control" ', $offset + 10, 0 );
					}
				}

				$offset_a = strpos( $output, '<p class="description"', $offset );
				$end_a    = strpos( $output, '</fieldset>', $offset );
				if ( false !== $offset_a && $offset_a < $end_a ) {
					$output   = substr_replace( $output, '<div class="description form-text"', $offset_a, 22 );
					$offset_a = strpos( $output, '</p>', $offset_a );
					$output   = substr_replace( $output, '</div>', $offset_a, 4 );
				}

				$offset = strpos( $output, '</fieldset>', $offset );
				$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
				$offset = strpos( $output, '</fieldset>', $offset );
				$output = substr_replace( $output, '</div>', $offset, 11 );

				break;

			case 'datebox':
				$offset = strpos( $output, '<fieldset>', $offset );
				$output = substr_replace( $output, '<div class="mb-3 row">', $offset, 10 );
				$offset = strpos( $output, '<legend>', $offset );
				$output = substr_replace( $output, '<div class="col-sm-3"><label class="col-form-label">', $offset, 8 );
				$offset = strpos( $output, '</legend>', $offset );
				$output = substr_replace( $output, "\n" . '<div class="col-sm-9">', $offset + 9, 0 );
				$output = substr_replace( $output, '</label></div>', $offset, 9 );

				$offset_a = strpos( $output, '<p class="description"', $offset );
				$end_a    = strpos( $output, '</fieldset>', $offset );
				if ( false !== $offset_a && $offset_a < $end_a ) {
					$start  = $offset_a;
					$end    = strpos( $output, '</p>', $start ) + 4;
					$length = $end - $start;
					$tag    = substr( $output, $start, $length );
					$tag    = str_replace( '<p class="description"', '<div class="description form-text"', $tag );
					$tag    = str_replace( '</p>', '</div>', $tag );
					$output = substr_replace( $output, '', $start, $length );
				}

				$offset = strpos( $output, 'class="input-options datebox-selects"', $offset );
				$output = substr_replace( $output, ' row gx-2', $offset + 36, 0 );

				$offset_b = strpos( $output, '<label ', $offset );
				$end_b    = strpos( $output, '</div>', $offset );
				$did_one  = false;
				while ( false !== $offset_b && $offset_b < $end_b ) {
					$offset_b = strpos( $output, '<label ', $offset_b );
					$output   = substr_replace( $output, '<tmp class="col-12 col-sm-4">' . "\n", $offset_b, 0 );
					$offset_b = strpos( $output, 'class="xprofile-field-label"', $offset_b );
					$output   = substr_replace( $output, ' form-label me-1', $offset_b + 27, 0 );
					$offset_b = strpos( $output, '<select ', $offset_b );
					$output   = substr_replace( $output, 'class="form-select" ', $offset_b + 8, 0 );
					$offset_b = strpos( $output, '</select>', $offset_b );
					$output   = substr_replace( $output, "\n" . '</tmp>', $offset_b + 9, 0 );

					$offset_b = strpos( $output, '<label ', $offset_b );
					$end_b    = strpos( $output, '</div>', $offset );
					$did_one  = true;
				}

				if ( false !== $offset_a && $offset_a < $end_a ) {
					$offset = strpos( $output, '</div>', $offset );
					$output = substr_replace( $output, "\n" . $tag, $offset + 6, 0 );
				}

				$offset = strpos( $output, '</fieldset>', $offset );
				$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
				$offset = strpos( $output, '</fieldset>', $offset );
				$output = substr_replace( $output, '</div>', $offset, 11 );

				$output = str_replace( '<tmp ',  '<div ',  $output );
				$output = str_replace( '</tmp>', '</div>', $output );

				break;

			case 'checkbox':
			case 'radio':
				$offset = strpos( $output, '<fieldset>', $offset );
				$output = substr_replace( $output, '<div class="mb-3 row">', $offset, 10 );
				$offset = strpos( $output, '<legend>', $offset );
				$output = substr_replace( $output, '<div class="col-sm-3"><label class="col-form-label">', $offset, 8 );
				$offset = strpos( $output, '</legend>', $offset );
				$output = substr_replace( $output, '</label></div>' . "\n" . '<div class="col-sm-9">', $offset, 9 );

				$offset_a = strpos( $output, '<p class="description"', $offset );
				$end_a    = strpos( $output, '</fieldset>', $offset );
				if ( false !== $offset_a && $offset_a < $end_a ) {
					$start  = $offset_a;
					$end    = strpos( $output, '</p>', $start ) + 4;
					$length = $end - $start;
					$tag    = substr( $output, $start, $length );
					$tag    = str_replace( '<p class="description"', '<div class="description form-text"', $tag );
					$tag    = str_replace( '</p>', '</div>', $tag );
					$tag    = str_replace( ' tabindex="0"', '', $tag );
					$output = substr_replace( $output, '', $start, $length );
				}

				$offset = strpos( $output, 'class="input-options ', $offset );

				$offset_b = strpos( $output, '<label ', $offset );
				$end_b    = strpos( $output, '</div>', $offset );
				while ( false !== $offset_b && $offset_b < $end_b ) {
					$start  = $offset_b;
					$end    = strpos( $output, '>', $offset_b ) + 1;
					$length = $end - $start;
					$label  = substr( $output, $start, $length );
					$label  = str_replace( '<label ', '<label class="form-check-label" ', $label );
					$output = substr_replace( $output, '', $start, $length );

					$output = substr_replace( $output, '<tmp class="form-check">', $offset_b, 0 );
					$offset_b = strpos( $output, '<input ', $offset_b );
					$output = substr_replace( $output, 'class="form-check-input" ', $offset_b + 7, 0 );
					$offset_b = strpos( $output, '>', $offset_b );
					$output = substr_replace( $output, $label, $offset_b + 1, 0 );
					$offset_b = strpos( $output, '</label>', $offset_b );
					$output = substr_replace( $output, '</tmp>', $offset_b + 8, 0 );

					$offset_b = strpos( $output, '<label ', $offset_b );
					$end_b    = strpos( $output, '</div>', $offset );
				}

				if ( false !== $offset_a && $offset_a < $end_a ) {
					$offset = strpos( $output, '</div>', $offset + 6 );

					$offset_c = strpos( $output, 'class="clear-value"', $offset );
					$end_c    = strpos( $output, '</fieldset>', $offset );
					if ( $offset_c !== $offset_b && $offset_c < $end_c ) {
						$offset = strpos( $output, '</a>', $offset ) + 4;
					}

					$output = substr_replace( $output, "\n" . $tag, $offset, 0 );
				}

				$offset = strpos( $output, '</fieldset>', $offset );
				$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
				$offset = strpos( $output, '</fieldset>', $offset );
				$output = substr_replace( $output, '</div>', $offset, 11 );

				$output = str_replace( '<tmp ',  '<div ',  $output );
				$output = str_replace( '</tmp>', '</div>', $output );

				break;
		}

		$offset = strpos( $output, 'class="editfield ', $offset + 1 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_profile_edit_output', 'enlightenment_bp_bootstrap_member_profile_editfield' );
add_filter( 'enlightenment_bp_register_form', 'enlightenment_bp_bootstrap_member_profile_editfield' );

function enlightenment_bp_bootstrap_template_member_profile_edit_output( $output ) {
	$output = str_replace( 'class="button-tabs button-nav"', 'class="button-tabs button-nav nav nav-tabs mb-3"', $output );
	$output = str_replace( 'id="profile-group-edit-submit"', 'id="profile-group-edit-submit" class="btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_profile_edit_output', 'enlightenment_bp_bootstrap_template_member_profile_edit_output' );

function enlightenment_bp_bootstrap_xprofile_edit_visibilty( $output ) {
	if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) {
		$offset = strpos( $output, '<p class="field-visibility-settings-toggle field-visibility-settings-header"' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, 'div', $offset + 1, 1 );
			$offset = strpos( $output, 'class="current-visibility-level"', $offset );
			$output = substr_replace( $output, ' screen-reader-text visually-hidden', $offset + 31, 0 );
			$offset = strpos( $output, '<button class="visibility-toggle-link text-button"', $offset );
			$output = substr_replace( $output, '<span class="dropdown">', $offset, 0 );
			$offset = strpos( $output, '<button class="visibility-toggle-link text-button"', $offset );
			$output = substr_replace( $output, ' dropdown-toggle p-0 border-0 bg-transparent', $offset + 49, 0 );
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, ' data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"', $offset, 0 );
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, sprintf( '<span aria-hidden="true">%s</span> <span class="screen-reader-text visually-hidden">', bp_get_the_profile_field_visibility_level_label() ), $offset + 1, 0 );
			$offset = strpos( $output, '</button>', $offset );
			$output = substr_replace( $output, '</span>', $offset, 0 );
			$offset = strpos( $output, '</p>', $offset );
			$output = substr_replace( $output, '', $offset, 4 );
			$offset = strpos( $output, 'class="field-visibility-settings"', $offset );
			$output = substr_replace( $output, ' dropdown-menu dropdown-menu-end', $offset + 32, 0 );
			$offset = strpos( $output, '<fieldset>', $offset );
			$output = substr_replace( $output, '', $offset, 10 );
			$offset = strpos( $output, '<legend>', $offset );
			$output = substr_replace( $output, '<span class="screen-reader-text visually-hidden">', $offset, 8 );
			$offset = strpos( $output, '</legend>', $offset );
			$output = substr_replace( $output, '</span>', $offset, 9 );
			$offset = strpos( $output, '</fieldset>', $offset );
			$output = substr_replace( $output, '', $offset, 11 );

			$start  = strpos( $output, '<button class="field-visibility-settings-close button" type="button">', $offset );
			$end    = strpos( $output, '</button>', $start ) + 9;
			$length = $end - $start;
			$output = substr_replace( $output, '', $start, $length );

			$offset = strpos( $output, '</div>', $offset );
			$output = substr_replace( $output, "\n" . '</span>' . "\n" . '</div>', $offset + 6, 0 );
		}
	} else {
		$output = str_replace( 'class="field-visibility-settings-notoggle field-visibility-settings-header"', 'class="field-visibility-settings-notoggle field-visibility-settings-header form-text mb-0"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_xprofile_edit_visibilty', 'enlightenment_bp_bootstrap_xprofile_edit_visibilty' );

function enlightenment_bp_bootstrap_xprofile_visibility_radio_buttons_args( $args ) {
	$args['before'] = '';
    $args['after']  = '';

    return $args;
}
add_filter( 'bp_after_xprofile_visibility_radio_buttons_parse_args', 'enlightenment_bp_bootstrap_xprofile_visibility_radio_buttons_args' );

function enlightenment_bp_bootstrap_profile_visibility_radio_buttons( $output ) {
	$offset = strpos( $output, '<label ' );
	while ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$tag    = substr( $output, $start, $length );
		$tag    = str_replace( '<label ', '<label class="form-check-label" ', $tag );

		$output = substr_replace( $output, '<div class="form-check dropdown-item mb-0">' . "\n", $offset, $length );
		$offset = strpos( $output, 'type="radio"', $offset );
		$output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, '<span class="field-visibility-text">', $offset );
		$output = substr_replace( $output, $tag, $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );

		$offset = strpos( $output, '<label ', $offset + 1 );
	}

    return $output;
}
add_filter( 'bp_profile_get_visibility_radio_buttons', 'enlightenment_bp_bootstrap_profile_visibility_radio_buttons' );

function enlightenment_bp_bootstrap_xprofile_profile_group_tabs( $tabs ) {
	foreach ( $tabs as $key => $tab ) {
		$tabs[ $key ] = str_replace( 'class="current"><a ', 'class="current nav-item"><a class="nav-link active" ', $tabs[ $key ] );
		$tabs[ $key ] = str_replace( '<li ><a ', '<li class="nav-item"><a class="nav-link" ', $tabs[ $key ] );
	}

	return $tabs;
}
add_filter( 'xprofile_filter_profile_group_tabs', 'enlightenment_bp_bootstrap_xprofile_profile_group_tabs' );

function enlightenment_bp_bootstrap_attachment_script_data( $script_data ) {
	$script_data['extra_css'] = 'jcrop';

	return $script_data;
}
add_filter( 'bp_attachment_avatar_script_data', 'enlightenment_bp_bootstrap_attachment_script_data' );
add_filter( 'bp_attachments_cover_image_script_data', 'enlightenment_bp_bootstrap_attachment_script_data' );

function enlightenment_bp_bootstrap_template_member_profile_change_avatar_output( $output ) {
	if ( ! (int) bp_get_option( 'bp-disable-avatar-uploads' ) ) {
		$offset = strpos( $output, 'class="bp-feedback info">' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, ' alert alert-info d-flex', $offset + 23, 0 );
			$offset = strpos( $output, 'class="bp-icon"', $offset );
			$output = substr_replace( $output, ' fas fa-info-circle mt-1 me-2', $offset + 14, 0 );
		}

		switch( bp_get_avatar_admin_step() ) {
			case 'upload-image':
				$offset = strpos( $output, '<p id="avatar-upload">' );
				if ( false !== $offset ) {
					$output = substr_replace( $output, 'div', $offset + 1, 1 );
					$offset = strpos( $output, '<label for="file"', $offset );
					$start  = $offset;
					$end    = strpos( $output, '</label>', $offset ) + 8;
					$length = $end - $start;
					$label  = substr( $output, $start, $length );
					$label  = str_replace( '<label ', '<label class="form-label" ', $label );
					$output = substr_replace( $output, '', $start, $length );

					$output = substr_replace( $output, '<div class="mb-3">' . "\n" . $label . "\n", $offset, 0 );
					$offset = strpos( $output, '<input type="file"', $offset );
					$output = substr_replace( $output, 'class="form-control" ', $offset + 7, 0 );
					$offset = strpos( $output, ' />', $offset );
					$output = substr_replace( $output, "\n" . '</div>', $offset + 3, 0 );
					$offset = strpos( $output, 'type="submit"', $offset );
					$output = substr_replace( $output, 'class="btn btn-primary btn-lg" ', $offset, 0 );
					$offset = strpos( $output, '</p>', $offset );
					$output = substr_replace( $output, 'div', $offset + 2, 1 );

					if ( bp_get_user_has_avatar() ) {
						$offset = strpos( $output, 'class="bp-help-text"', $offset );
						$output = substr_replace( $output, ' alert alert-info mt-3', $offset + 19, 0 );
						$offset = strpos( $output, '<p>', $offset );
						$output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
						$offset = strpos( $output, 'class="button edit"', $offset );
						$output = substr_replace( $output, ' btn btn-danger', $offset + 18, 0 );
					}
				}

				break;

			case 'crop-image':
				$offset = strpos( $output, '<p class="bp-help-text screen-header">' );
				if ( false !== $offset ) {
					$offset = strpos( $output, '<img ', $offset );
					$output = substr_replace( $output, '<div class="row">' . "\n" . '<div class="col flex-grow-1 flex-shrink-0">' . "\n", $offset, 0 );
					$offset = strpos( $output, ' />', $offset );
					$output = substr_replace( $output, "\n" . '</div>', $offset + 3, 0 );
					$offset = strpos( $output, '<div id="avatar-crop-pane">', $offset );
					$output = substr_replace( $output, 'class="overflow-hidden" ', $offset + 5, 0 );
					$output = substr_replace( $output, '<div class="col flex-grow-1 flex-shrink-0 d-flex flex-column align-items-center">' . "\n", $offset, 0 );
					$offset = strpos( $output, '<input type="submit"', $offset );
					$output = substr_replace( $output, 'class="btn btn-primary btn-lg" ', $offset + 7, 0 );
					$offset = strpos( $output, ' />', $offset );
					$output = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset + 3, 0 );
				}

				break;
		}

		$offset = strpos( $output, 'id="tmpl-bp-avatar-nav"' );
		if ( false !== $offset ) {
			$offset = strpos( $output, 'class="bp-avatar-nav-item"', $offset );
			$output = substr_replace( $output, ' nav-link', $offset + 25, 0 );
		}

		$offset = strpos( $output, 'id="tmpl-upload-window"' );
		if ( false !== $offset ) {
			$offset = strpos( $output, 'id="{{data.drop_element}}"', $offset );
			$output = substr_replace( $output, 'class="card bg-body-secondary" ', $offset, 0 );
			$offset = strpos( $output, 'class="drag-drop-inside"', $offset );
			$output = substr_replace( $output, ' card-body text-center', $offset + 23, 0 );
			$offset = strpos( $output, 'class="drag-drop-info"', $offset );
			$output = substr_replace( $output, ' mt-3', $offset + 21, 0 );
			$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
			$output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
			$offset = strpos( $output, 'id="{{data.browse_button}}"', $offset );
			$output = substr_replace( $output, 'class="btn btn-primary btn-lg" ', $offset, 0 );
		}

		$offset = strpos( $output, 'id="tmpl-progress-window"' );
		if ( false !== $offset ) {
			$offset = strpos( $output, 'class="bp-progress"', $offset );
			$output = substr_replace( $output, ' progress', $offset + 18, 0 );
			$offset = strpos( $output, 'class="bp-bar"', $offset );
			$output = substr_replace( $output, ' progress-bar progress-bar-striped progress-bar-animated', $offset + 13, 0 );
		}

		$offset = strpos( $output, 'id="tmpl-bp-avatar-item"' );
		if ( false !== $offset ) {
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, "\n" . '<div class="row">', $offset + 1, 0 );
			$offset = strpos( $output, 'id="avatar-to-crop"', $offset );
			$output = substr_replace( $output, 'class="col flex-grow-1 flex-shrink-0" ', $offset, 0 );
			$offset = strpos( $output, 'class="avatar-crop-management"', $offset );
			$output = substr_replace( $output, ' col flex-grow-1 flex-shrink-0 d-flex flex-column align-items-center', $offset + 29, 0 );
			$offset = strpos( $output, 'class="avatar"', $offset );
			$output = substr_replace( $output, ' overflow-hidden mb-3', $offset + 13, 0 );
			$offset = strpos( $output, 'class="button avatar-crop-submit"', $offset );
			$output = substr_replace( $output, ' btn btn-primary btn-lg', $offset + 32, 0 );
			$offset = strpos( $output, '</script>', $offset );
			$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
		}

		$offset = strpos( $output, 'id="tmpl-bp-avatar-webcam"' );
		if ( false !== $offset ) {
			$offset = strpos( $output, 'class="warning">', $offset );
	        $output = substr_replace( $output, ' alert alert-warning', $offset + 14, 0 );
			$offset = strpos( $output, '#>', $offset );
			$output = substr_replace( $output, "\n" . '<div class="row">', $offset + 2, 0 );
			$offset = strpos( $output, 'id="avatar-to-crop"', $offset );
			$output = substr_replace( $output, 'class="col flex-grow-1 flex-shrink-0" ', $offset, 0 );
			$offset = strpos( $output, 'class="avatar-crop-management"', $offset );
			$output = substr_replace( $output, ' col flex-grow-1 flex-shrink-0 d-flex flex-column align-items-center', $offset + 29, 0 );
			$offset = strpos( $output, 'class="avatar"', $offset );
			$output = substr_replace( $output, ' overflow-hidden mb-3', $offset + 13, 0 );
			$offset = strpos( $output, 'class="button avatar-webcam-capture"', $offset );
			$output = substr_replace( $output, ' btn btn-secondary btn-lg', $offset + 35, 0 );
			$offset = strpos( $output, 'class="button avatar-webcam-save"', $offset );
			$output = substr_replace( $output, ' btn btn-primary btn-lg', $offset + 32, 0 );
			$offset = strpos( $output, '<#', $offset );
			$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
		}

		$offset = strpos( $output, 'id="tmpl-bp-avatar-delete"' );
	    if ( false !== $offset ) {
			$offset = strpos( $output, '<p>', $offset );
	        $output = substr_replace( $output, ' class="alert alert-info"', $offset + 2, 0 );
			$offset = strpos( $output, 'class="button edit"', $offset );
	        $output = substr_replace( $output, ' btn btn-danger btn-lg', $offset + 18, 0 );
			$offset = strpos( $output, 'class="bp-feedback bp-messages info"', $offset );
			$output = substr_replace( $output, ' alert alert-info d-flex ', $offset + 30, 0 );
			$offset = strpos( $output, 'class="bp-icon"', $offset );
	        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
			$offset = strpos( $output, '<p>', $offset );
	        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
			$offset = strpos( $output, 'class="button edit"', $offset );
	        $output = substr_replace( $output, ' btn btn-danger btn-lg', $offset + 18, 0 );
	    }
	} else {
		$offset = strpos( $output, 'class="bp-help-text">' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, ' alert alert-info', $offset + 19, 0 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_profile_change-avatar_output', 'enlightenment_bp_bootstrap_template_member_profile_change_avatar_output' );

function enlightenment_bp_bootstrap_template_member_profile_change_cover_image_output( $output ) {
	$offset = strpos( $output, 'class="info bp-feedback"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
    }

	$offset = strpos( $output, 'id="tmpl-upload-window"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'id="{{data.drop_element}}"', $offset );
		$output = substr_replace( $output, 'class="card bg-body-secondary" ', $offset, 0 );
		$offset = strpos( $output, 'class="drag-drop-inside"', $offset );
		$output = substr_replace( $output, ' card-body text-center', $offset + 23, 0 );
		$offset = strpos( $output, 'class="drag-drop-info"', $offset );
		$output = substr_replace( $output, ' mt-3', $offset + 21, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
		$output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'id="{{data.browse_button}}"', $offset );
		$output = substr_replace( $output, 'class="btn btn-primary btn-lg" ', $offset, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-progress-window"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="bp-progress"', $offset );
		$output = substr_replace( $output, ' progress', $offset + 18, 0 );
		$offset = strpos( $output, 'class="bp-bar"', $offset );
		$output = substr_replace( $output, ' progress-bar progress-bar-striped progress-bar-animated', $offset + 13, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-cover-image-delete"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="alert alert-info mt-3"', $offset + 2, 0 );
		$offset = strpos( $output, 'class="button edit"', $offset );
		$output = substr_replace( $output, ' btn btn-danger btn-lg', $offset + 18, 0 );
		$offset = strpos( $output, 'class="bp-feedback bp-messages info"', $offset );
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="alert alert-info mt-3"', $offset + 2, 0 );
		$offset = strpos( $output, 'class="button edit"', $offset );
		$output = substr_replace( $output, ' btn btn-danger btn-lg', $offset + 18, 0 );
		$offset = strpos( $output, 'class="bp-feedback bp-messages info"', $offset );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_profile_change-cover-image_output', 'enlightenment_bp_bootstrap_template_member_profile_change_cover_image_output' );

function enlightenment_bp_bootstrap_template_members_single_notifications_notifications_loop_output( $output ) {
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );

	$offset = strpos( $output, 'class="bp-feedback bp-messages info"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = str_replace( 'class="dashicons dashicons-arrow-down"', 'class="fas fa-angle-down"', $output );
	$output = str_replace( 'class="dashicons dashicons-arrow-up"', 'class="fas fa-angle-up"', $output );

	$output = str_replace( '<table ', '<div class="table-responsive">' . "\n" . '<table ', $output );
	$output = str_replace( 'class="notifications bp-tables-user"', 'class="notifications bp-tables-user table table-striped"', $output );
	$output = str_replace( '</table>', '</table>' . "\n" . '</div>', $output );
	$output = str_replace( '<th class="icon"></th>', '<th class="icon d-none"></th>', $output );
	$output = str_replace( '<td></td>', '<td class="d-none"></td>', $output );

	$offset = strpos( $output, '<th class="bulk-select-all">' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, '<div class="form-check">', $offset + 28, 0 );
		$offset = strpos( $output, 'id="select-all-notifications"', $offset );
        $output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, '<label class="bp-screen-reader-text visually-hidden"', $offset );
        $output = substr_replace( $output, 'form-check-label', $offset + 14, 29 );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, '<span class="bp-screen-reader-text visually-hidden">', $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, '</span>', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );
    }

	$offset = strpos( $output, '<td class="bulk-select-check">' );
    while ( false !== $offset ) {
		$start  = strpos( $output, 'id="', $offset );
		$end    = strpos( $output, '</td>', $offset );
		if ( false !== $start && $start < $end ) {
			$start += 4;
			$end    = strpos( $output, '"', $start );
			$length = $end - $start;
			$id     = substr( $output, $start, $length );
		}

		$offset = strpos( $output, sprintf( '<label for="%s">', $id ), $offset );
        $output = substr_replace( $output, '<div class="form-check">', $offset, strlen( sprintf( '<label for="%s">', $id ) ) );
		$offset = strpos( $output, 'class="notification-check"', $offset );
        $output = substr_replace( $output, ' form-check-input', $offset + 25, 0 );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, sprintf( '<label for="%s" class="form-check-label">', $id ), $offset + 1, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );

		$offset = strpos( $output, '<td class="bulk-select-check">', $offset );
    }

	$output = str_replace( '<td class="bulk-select-check"><label for="', '<td class="bulk-select-check"><label class="mb-0" for="', $output );

	$output = str_replace( 'class="actions"', 'class="actions text-center"', $output );
	$output = str_replace( 'class="notification-actions"', 'class="notification-actions text-center"', $output );
	$output = str_replace( 'class="notifications-options-nav"', 'class="notifications-options-nav d-flex"', $output );
	$output = str_replace( 'class="select-wrap"', 'class="select-wrap me-2"', $output );
	$output = str_replace( 'id="notification-select"', 'id="notification-select" class="form-control"', $output );
	$output = str_replace( 'class="button action"', 'class="button action btn btn-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_members/single/notifications/notifications-loop_output', 'enlightenment_bp_bootstrap_template_members_single_notifications_notifications_loop_output' );

function enlightenment_bp_bootstrap_notification_action_links( $output ) {
	$output = str_replace( 'class="mark-unread ', 'class="mark-unread btn btn-link ', $output );
	$output = str_replace( 'class="mark-read ', 'class="mark-read btn btn-link ', $output );
	$output = str_replace( 'class="delete ', 'class="delete btn btn-link ', $output );
	$output = str_replace( 'class="dashicons dashicons-hidden"', 'class="far fa-eye-slash fa-fw"', $output );
	$output = str_replace( 'class="dashicons dashicons-visibility"', 'class="far fa-eye fa-fw"', $output );
	$output = str_replace( 'class="dashicons dashicons-dismiss"', 'class="fas fa-times fa-fw"', $output );
	$output = str_replace( ' | ', '', $output );
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = sprintf( '<div class="d-flex">%s</div>', $output );

	return $output;
}
add_filter( 'bp_get_the_notification_action_links', 'enlightenment_bp_bootstrap_notification_action_links' );

function enlightenment_bp_bootstrap_member_messages_content( $output ) {
	$offset = strpos( $output, 'id="tmpl-bp-messages-filters"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="user-messages-search"', $offset );
		$output = substr_replace( $output, ' col-md', $offset + 27, 0 );
		$offset = strpos( $output, 'class="user-messages-bulk-actions"', $offset );
        $output = substr_replace( $output, ' col-md order-last order-md-first mt-3 mt-md-0', $offset + 33, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-bulk-actions"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<input type="checkbox"', $offset );
		$output = substr_replace( $output, '<div class="form-check">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'id="user_messages_select_all"', $offset );
        $output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, 'for="user_messages_select_all"', $offset );
        $output = substr_replace( $output, 'class="form-check-label" ', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
		$offset = strpos( $output, 'class="bulk-actions-wrap ', $offset );
        $output = substr_replace( $output, ' d-inline-flex', $offset + 24, 0 );
		$offset = strpos( $output, 'class="bulk-actions select-wrap"', $offset );
        $output = substr_replace( $output, ' me-2', $offset + 31, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
		$output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'id="user-messages-bulk-actions"', $offset );
		$output = substr_replace( $output, 'class="form-control" ', $offset, 0 );
		$offset = strpos( $output, 'class="messages-button bulk-apply bp-tooltip"', $offset );
		$output = substr_replace( $output, ' btn btn-secondary', $offset + 22, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, 'class="dashicons dashicons-yes"', $offset );
        $output = substr_replace( $output, 'fas fa-check', $offset + 7, 23 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
		$output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-messages-paginate"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<# if ( 1 !== data.page || data.total_page !== data.page ) { #>' . "\n" . '<ul class="pagination">', $offset + 1, 0 );

		$offset = strpos( $output, '<button ', $offset );
		$output = substr_replace( $output, '<li class="page-item">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="button messages-button"', $offset );
        $output = substr_replace( $output, ' page-link', $offset + 29, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-arrow-left"', $offset );
        $output = substr_replace( $output, 'fas fa-arrow-left', $offset + 7, 30 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</li>', $offset + 9, 0 );

		$offset = strpos( $output, '<button ', $offset );
		$output = substr_replace( $output, '<li class="page-item">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="button messages-button"', $offset );
        $output = substr_replace( $output, ' page-link', $offset + 29, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-arrow-right"', $offset );
        $output = substr_replace( $output, 'fas fa-arrow-right', $offset + 7, 31 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</li>', $offset + 9, 0 );

		$offset = strpos( $output, '<# } #>', $offset );
        $output = substr_replace( $output, "\n" . '</ul>' . "\n" . '<# } #>', $offset + 7, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-messages-feedback"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="bp-feedback ', $offset );
		$output = substr_replace( $output, ' alert alert-info d-flex', $offset + 18, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-messages-thread"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row align-items-center">', $offset + 1, 0 );
		$offset = strpos( $output, 'class="thread-cb"', $offset );
        $output = substr_replace( $output, ' col flex-grow-0 flex-shrink-1', $offset + 16, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="thread-from"', $offset );
        $output = substr_replace( $output, ' col-3', $offset + 18, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="thread-to"', $offset );
        $output = substr_replace( $output, ' col-3', $offset + 16, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="thread-content"', $offset );
        $output = substr_replace( $output, ' col flex-grow-1 flex-shrink-0', $offset + 21, 0 );
		$offset = strpos( $output, 'class="excerpt"', $offset );
        $output = substr_replace( $output, ' mb-0', $offset + 14, 0 );
		$offset = strpos( $output, 'class="thread-date"', $offset );
        $output = substr_replace( $output, ' col-2 text-end', $offset + 18, 0 );
		$offset = strpos( $output, '</script>', $offset );
        $output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-messages-preview"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="messages-title"', $offset );
		$output = substr_replace( $output, ' ms-2', $offset + 21, 0 );
		$offset = strpos( $output, 'class="preview-pane-header"', $offset );
        $output = substr_replace( $output, ' row align-items-center mb-3', $offset + 26, 0 );
		$offset = strpos( $output, 'class="thread-participants"', $offset );
        $output = substr_replace( $output, ' col d-flex align-items-center mb-0', $offset + 26, 0 );
		$offset = strpos( $output, '<dd>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 3, 0 );
		$offset = strpos( $output, 'class="participants-list"', $offset );
        $output = substr_replace( $output, ' list-unstyled d-flex mb-0', $offset + 24, 0 );
		$offset = strpos( $output, '<li>', $offset );
        $output = substr_replace( $output, ' class="ms-1"', $offset + 3, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, 'class="actions"', $offset );
        $output = substr_replace( $output, ' col d-flex flex-row-reverse', $offset + 14, 0 );
		$offset = strpos( $output, 'class="message-action-delete ', $offset );
        $output = substr_replace( $output, ' btn btn-link', $offset + 28, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '<i class="fas fa-times fa-fw"></i>', $offset + 1, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="message-action-exit ', $offset );
        $output = substr_replace( $output, ' btn btn-link', $offset + 26, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '<i class="fas fa-sign-out-alt fa-fw"></i>', $offset + 1, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="message-action-unstar ', $offset );
        $output = substr_replace( $output, ' btn btn-link', $offset + 28, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '<i class="fas fa-star fa-fw"></i>', $offset + 1, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="message-action-star ', $offset );
        $output = substr_replace( $output, ' btn btn-link', $offset + 26, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '<i class="far fa-star fa-fw"></i>', $offset + 1, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="message-action-view ', $offset );
        $output = substr_replace( $output, ' btn btn-link', $offset + 26, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '<i class="fas fa-comment fa-fw"></i>', $offset + 1, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="bp-messages-hook inbox-list-item"', $offset );
        $output = substr_replace( $output, ' table', $offset + 39, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-messages-single-header"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="single-message-thread-header"', $offset );
        $output = substr_replace( $output, ' d-flex align-items-center', $offset + 35, 0 );
		$offset = strpos( $output, 'class="thread-participants"', $offset );
        $output = substr_replace( $output, ' d-flex align-items-center mb-0', $offset + 26, 0 );
		$offset = strpos( $output, '<dd>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 3, 0 );
		$offset = strpos( $output, 'class="participants-list"', $offset );
        $output = substr_replace( $output, ' list-unstyled d-flex mb-0', $offset + 24, 0 );
		$offset = strpos( $output, '<li>', $offset );
        $output = substr_replace( $output, ' class="ms-1"', $offset + 3, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, 'class="actions"', $offset );
        $output = substr_replace( $output, ' d-flex', $offset + 14, 0 );
		$offset = strpos( $output, 'class="message-action-delete ', $offset );
        $output = substr_replace( $output, ' btn btn-link', $offset + 28, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '<i class="fas fa-times fa-fw"></i>', $offset + 1, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="message-action-exit ', $offset );
        $output = substr_replace( $output, ' btn btn-link', $offset + 26, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '<i class="fas fa-sign-out-alt fa-fw"></i>', $offset + 1, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-messages-single-list"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="message-action-unstar ', $offset );
        $output = substr_replace( $output, ' btn btn-link', $offset + 28, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '<i class="fas fa-star fa-fw"></i>', $offset + 1, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="message-action-star ', $offset );
        $output = substr_replace( $output, ' btn btn-link', $offset + 26, 0 );
		$offset = strpos( $output, 'data-bp-tooltip="', $offset );
        $output = substr_replace( $output, 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 17 );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '<i class="far fa-star fa-fw"></i>', $offset + 1, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-messages-single"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'id="bp-message-thread-list"', $offset );
		$output = substr_replace( $output, 'class="list-unstyled" ', $offset, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="submit"', $offset );
		$output = substr_replace( $output, ' mt-3', $offset + 13, 0 );
		$offset = strpos( $output, 'id="send_reply_button"', $offset );
		$output = substr_replace( $output, 'class="btn btn-primary btn-lg" ', $offset, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-messages-form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'for="send-to-input"', $offset );
        $output = substr_replace( $output, 'class="form-label" ', $offset, 0 );
		$offset = strpos( $output, 'class="send-to-input"', $offset );
        $output = substr_replace( $output, ' form-control w-100', $offset + 20, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 2, 0 );

		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'for="subject"', $offset );
        $output = substr_replace( $output, 'class="form-label" ', $offset, 0 );
		$offset = strpos( $output, 'id="subject"', $offset );
        $output = substr_replace( $output, 'class="form-control" ', $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 2, 0 );

		$offset = strpos( $output, 'class="submit"', $offset );
		$output = substr_replace( $output, ' mt-3', $offset + 13, 0 );
		$offset = strpos( $output, 'class="button bp-primary-action"', $offset );
		$output = substr_replace( $output, ' btn btn-primary btn-lg', $offset + 31, 0 );
		$offset = strpos( $output, 'class="text-button small bp-secondary-action"', $offset );
		$output = substr_replace( $output, ' btn btn-secondary btn-lg', $offset + 44, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_member_messages_content', 'enlightenment_bp_bootstrap_member_messages_content' );
add_filter( 'enlightenment_bp_filter_wp_footer_content', 'enlightenment_bp_bootstrap_member_messages_content' );

function enlightenment_bp_bootstrap_message_search_form( $output ) {
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text  visually-hidden"', $output );
	$output = str_replace( 'id="user_messages_search"', 'id="user_messages_search" class="form-control"', $output );
	$output = str_replace( 'id="user_messages_search_submit"', 'id="user_messages_search_submit" class="btn btn-light"', $output );
	$output = str_replace( 'class="dashicons dashicons-search"', 'class="fas fa-search"', $output );

	$offset = strpos( $output, 'id="user_messages_search_form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<input type="search"', $offset );
		$output = substr_replace( $output, '<span class="input-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</span>', $offset + 9, 0 );
	}

	return $output;
}
add_filter( 'bp_message_search_form', 'enlightenment_bp_bootstrap_message_search_form' );

function enlightenment_bp_bootstrap_template_member_friends_output( $output ) {
	$offset = strpos( $output, 'class="bp-feedback bp-messages info"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	switch ( bp_current_action() ) {
		case 'requests':
			$offset = strpos( $output, '<h2 class="screen-heading friendship-requests-screen">' );
			if ( false !== $offset ) {
				$start  = $offset;
				$end    = strpos( $output, '</h2>', $start ) + 5;
				$length = $end - $start;
				$tag    = substr( $output, $start, $length );
				$output = substr_replace( $output, '', $start, $length );

				$offset = strpos( $output, '<div data-bp-list="friendship_requests">', $offset );
				$output = substr_replace( $output, "\n" . $tag, $offset + 40, 0 );
			}

			$offset = strpos( $output, '<div class="item-block">' );
		    if ( false === $offset ) {
				$output = str_replace( 'class="item"', 'class="item-block"', $output );

				$offset = strpos( $output, 'id="friend-list"' );
				if ( false !== $offset ) {
					$offset = strpos( $output, '<li id="friendship-', $offset );

					while ( false !== $offset ) {
						$offset = strpos( $output, 'class="item-avatar"', $offset );
						$offset = strpos( $output, '</div>', $offset );
				        $output = substr_replace( $output, "\n" . '<div class="item">', $offset + 6, 0 );
						$offset = strpos( $output, '</li>', $offset );
				        $output = substr_replace( $output, '</div>' . "\n", $offset, 0 );

						$offset = strpos( $output, '<li id="friendship-', $offset );
					}
				}
		    }

			$offset = strpos( $output, '<div class="list-wrap">' );
		    if ( false === $offset ) {
				$offset = strpos( $output, 'id="friend-list"' );

				if ( false !== $offset ) {
					$offset = strpos( $output, '<li id="friendship-', $offset );

					while ( false !== $offset ) {
						$offset = strpos( $output, '>', $offset );
				        $output = substr_replace( $output, "\n" . '<div class="list-wrap row">', $offset + 1, 0 );
						$offset = strpos( $output, '</li>', $offset );
				        $output = substr_replace( $output, '</div>' . "\n", $offset, 0 );

						$offset = strpos( $output, '<li id="friendship-', $offset );
					}
				}
		    }

			$output = str_replace( 'class="list-wrap"', 'class="list-wrap row"', $output );
			$output = str_replace( 'class="item-avatar"', 'class="item-avatar col flex-grow-0 flex-shrink-1"', $output );
			$output = str_replace( 'class="item"', 'class="item col flex-grow-1 flex-shrink-0"', $output );
			$output = str_replace( 'class=" friends-meta action"', 'class="friends-meta action d-flex"', $output );
			$output = str_replace( 'class=" btn-group generic-button"', 'class="generic-button"', $output );
			$output = str_replace( 'class="button accept btn btn-primary btn-lg"', 'class="button accept btn btn-primary"', $output );
			$output = str_replace( 'class="button reject dropdown-item"', 'class="button reject btn btn-secondary"', $output );

			$offset = strpos( $output, 'class="generic-button"' );
		    if ( false !== $offset ) {
				$offset = strpos( $output, 'id="friend-list"' );

				if ( false !== $offset ) {
					$offset = strpos( $output, '<li id="friendship-', $offset );

					while ( false !== $offset ) {
						$end    = strpos( $output, '</li>', $offset );

						$offset_a = strpos( $output, 'class="generic-button"', $offset );
						if ( false !== $offset_a && $offset_a < $end ) {
							$offset_b = strpos( $output, 'class="generic-button"', $offset_a + 1 );

							if ( false !== $offset_b && $offset_b < $end ) {
								$output = substr_replace( $output, ' ms-1', $offset_b + 21, 0 );
							}
						}

						$offset = strpos( $output, '<li id="friendship-', $offset + 1 );
					}
			    }
			}

			break;
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_friends_output', 'enlightenment_bp_bootstrap_template_member_friends_output' );

function enlightenment_bp_bootstrap_template_members_loop_output( $output ) {
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );

	$offset = strpos( $output, 'class="bp-feedback bp-messages info"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	$output = str_replace( 'class="list-wrap"', 'class="list-wrap row"', $output );
	$output = str_replace( 'class="item-avatar"', 'class="item-avatar col flex-grow-0 flex-shrink-1"', $output );
	$output = str_replace( 'class="item"', 'class="item col flex-grow-1 flex-shrink-0"', $output );
	$output = str_replace( 'class=" members-meta action"', 'class="members-meta action list-unstyled"', $output );
	$output = str_replace( 'class=" friends-meta action"', 'class="friends-meta action list-unstyled"', $output );
	$output = str_replace( 'class="friendship-button ', 'class="friendship-button btn btn-secondary ', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_members/members-loop_output', 'enlightenment_bp_bootstrap_template_members_loop_output' );
add_filter( 'enlightenment_bp_filter_template_groups/single/members-loop_output', 'enlightenment_bp_bootstrap_template_members_loop_output' );

function enlightenment_bp_bootstrap_loop_classes( $classes ) {
	$classes[] = 'row';
	$classes[] = 'list-unstyled';

	return $classes;
}
add_filter( 'bp_nouveau_get_loop_classes', 'enlightenment_bp_bootstrap_loop_classes' );

function enlightenment_bp_bootstrap_loop_item_class( $classes ) {
	if (
		doing_action( 'wp_ajax_widget_groups_list' )
		||
		doing_action( 'wp_ajax_nopriv_widget_groups_list' )
	) {
		return $classes;
	}

	if (
		enlightenment_has_in_call_stack( array( 'BP_Classic_Groups_Widget', 'widget' ) )
		||
		enlightenment_has_in_call_stack( array( 'BP_Groups_Widget', 'widget' ) )
	) {
		return $classes;
	}

	$classes[] = 'col-12';
	$classes[] = 'col-md-6';

	return $classes;
}
add_filter( 'bp_get_member_class', 'enlightenment_bp_bootstrap_loop_item_class' );
add_filter( 'bp_get_group_class', 'enlightenment_bp_bootstrap_loop_item_class' );
add_filter( 'bp_get_blog_class', 'enlightenment_bp_bootstrap_loop_item_class' );

function enlightenment_bp_bootstrap_template_member_groups_output( $output ) {
	$offset = strpos( $output, 'class="bp-feedback bp-messages info"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	switch ( bp_current_action() ) {
		case 'invites':
			$offset = strpos( $output, '<h2 class="screen-heading group-invites-screen">' );
			if ( false !== $offset ) {
				$output  = substr_replace( $output, '<div class="group-invites">' . "\n", $offset, 0 );
				$output .= "\n" . '</div>';
			}

			$offset = strpos( $output, '<p class="desc">' );
			while ( false !== $offset ) {
				$output = substr_replace( $output, 'div', $offset + 1, 1 );

				$offset_a = strpos( $output, '<p>', $offset );
				$end_a = strpos( $output, '</p>', $offset );
				if ( false !== $offset_a && $offset_a < $end_a ) {
					$offset = $end_a;
				}

				$offset = strpos( $output, '</p>', $offset );
				$output = substr_replace( $output, 'div', $offset + 2, 1 );

				$offset = strpos( $output, '<p class="desc">', $offset + 1 );
			}

			$output = str_replace( 'class="invites item-list bp-list"', 'class="invites item-list bp-list row list-unstyled"', $output );
			$output = str_replace( 'class="item-entry invites-list"', 'class="item-entry invites-list col-12 col-md-6"', $output );
			$output = str_replace( 'class="wrap"', 'class="wrap row"', $output );
			$output = str_replace( 'class="item-avatar"', 'class="item-avatar col flex-grow-0 flex-shrink-1"', $output );
			$output = str_replace( 'class="item"', 'class="item col flex-grow-1 flex-shrink-0"', $output );
			$output = str_replace( 'class=" groups-meta action"', 'class="groups-meta action d-flex list-unstyled"', $output );
			$output = str_replace( 'class=" reject generic-button"', 'class="reject generic-button ms-1"', $output );
			$output = str_replace( 'class="button accept ', 'class="button accept btn btn-primary ', $output );
			$output = str_replace( 'class="button reject ', 'class="button reject btn btn-secondary ', $output );

			break;
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_groups_output', 'enlightenment_bp_bootstrap_template_member_groups_output' );

function enlightenment_bp_bootstrap_template_groups_groups_loop_output( $output ) {
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );

	$offset = strpos( $output, 'class="bp-feedback bp-messages info"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	$output = str_replace( 'class="list-wrap"', 'class="list-wrap row"', $output );
	$output = str_replace( 'class="item-avatar"', 'class="item-avatar col flex-grow-0 flex-shrink-1"', $output );
	$output = str_replace( 'class="item"', 'class="item col flex-grow-1 flex-shrink-0"', $output );
	$output = str_replace( 'class="group-button ', 'class="group-button btn btn-secondary ', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_groups/groups-loop_output', 'enlightenment_bp_bootstrap_template_groups_groups_loop_output' );

function enlightenment_bp_bootstrap_template_member_settings_general_output( $output ) {
	$output = str_replace( 'class="info email-pwd-info"', 'class="info email-pwd-info alert alert-info"', $output );
	$output = str_replace( 'class="user-pass1-wrap"', 'class="user-pass1-wrap mb-3"', $output );
	$output = str_replace( 'class="button wp-generate-pw"', 'class="button wp-generate-pw btn btn-secondary mb-3"', $output );
	$output = str_replace( '<div id="pass-strength-result" aria-live="polite"></div>', '<div class="form-text"><span id="pass-strength-result" aria-live="polite"></span></div>', $output );

	$offset = strpos( $output, '<label for="pwd">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, 'class="col-form-label" ', $offset + 7, 0 );
        $output = substr_replace( $output, '<div class="mb-3 row">' . "\n" . '<div class="col-sm-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '<span>(', $offset );
        $output = substr_replace( $output, '<i class="fas fa-info-circle bp-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $offset, 7 );
		$offset = strpos( $output, ')</span>', $offset );
        $output = substr_replace( $output, '"></i>', $offset, 8 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );
		$offset = strpos( $output, '<input type="password"', $offset );
        $output = substr_replace( $output, '<div class="col-sm-9">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="settings-input small"', $offset );
        $output = substr_replace( $output, ' form-control', $offset + 21, 6 );
		$offset = strpos( $output, '&nbsp;', $offset );
        $output = substr_replace( $output, '<span class="d-block form-text">', $offset, 6 );
		$offset = strpos( $output, '</a>', $offset );
        $output = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset + 4, 0 );
		$offset = strpos( $output, '</a>', $offset );
        $output = substr_replace( $output, '</span>', $offset + 4, 0 );
    }

	$offset = strpos( $output, '<label for="email">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, 'class="col-form-label" ', $offset + 7, 0 );
        $output = substr_replace( $output, '<div class="mb-3 row">' . "\n" . '<div class="col-sm-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '<input type="email"', $offset );
        $output = substr_replace( $output, '</div>' . "\n" . '<div class="col-sm-9">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="settings-input"', $offset );
        $output = substr_replace( $output, ' form-control', $offset + 21, 0 );
		$offset = strpos( $output, '/>', $offset );
        $output = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset + 2, 0 );
    }

	$offset = strpos( $output, 'class="info bp-feedback"' );
    if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '<div class="alert alert-info d-flex">', $offset + 1, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="text"', $offset );
        $output = substr_replace( $output, 'mb-0 ', $offset + 7, 0 );
		$offset = strpos( $output, '</p>', $offset );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
    }

	$offset = strpos( $output, '<label for="pass1">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, 'class="col-form-label" ', $offset + 7, 0 );
		$output = substr_replace( $output, '<div class="mb-3 row">' . "\n" . '<div class="col-sm-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );
		$offset = strpos( $output, '<span class="password-input-wrapper">', $offset );
        $output = substr_replace( $output, '<div class="col-sm-9">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="password-input-wrapper"', $offset );
        $output = substr_replace( $output, ' input-group', $offset + 29, 0 );
		$offset = strpos( $output, 'class="settings-input small ', $offset );
        $output = substr_replace( $output, ' form-control', $offset + 21, 6 );
		$offset = strpos( $output, '</span>', $offset );
        $output = substr_replace( $output, '', $offset, 7 );
		$offset = strpos( $output, 'class="button wp-hide-pw"', $offset );
        $output = substr_replace( $output, ' btn btn-light', $offset + 24, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-hidden"', $offset );
        $output = substr_replace( $output, 'far fa-eye-slash', $offset + 7, 26 );
		$offset = strpos( $output, 'class="text bp-screen-reader-text"', $offset );
        $output = substr_replace( $output, ' visually-hidden', $offset + 33, 0 );
		$offset = strpos( $output, '</button>', $offset );
        $output = substr_replace( $output, "\n" . '</span>', $offset + 9, 0 );
		$offset = strpos( $output, 'class="button wp-cancel-pw"', $offset );
        $output = substr_replace( $output, ' btn btn-light', $offset + 26, 0 );
		$offset = strpos( $output, '</div>', $offset );
        $output = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset, 0 );
    }

	$offset = strpos( $output, 'class="user-pass2-wrap"' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, ' mb-3 row', $offset + 22, 0 );
		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, '<div class="col-sm-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="label"' );
		$output = substr_replace( $output, 'col-form-label ', $offset + 7, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, '</div>', $offset + 8, 0 );
		$offset = strpos( $output, '<input name="pass2"', $offset );
        $output = substr_replace( $output, '<div class="col-sm-9">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="settings-input small ', $offset );
        $output = substr_replace( $output, ' form-control', $offset + 21, 6 );
		$offset = strpos( $output, '/>', $offset );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 2, 0 );
    }

	$offset = strpos( $output, 'class="pw-weak"' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, ' mb-3 form-check', $offset + 14, 0 );
		$offset = strpos( $output, '<label>' );
		$output = substr_replace( $output, '', $offset, 7 );
		$offset = strpos( $output, 'class="pw-checkbox"', $offset );
		$output = substr_replace( $output, ' form-check-input', $offset + 18, 0 );
        $output = substr_replace( $output, 'id="pw-weak-input"', $offset, 0 );
		$offset = strpos( $output, '<span id="pw-weak-text-label">', $offset );
        $output = substr_replace( $output, '<label class="form-check-label" for="pw-weak-input">', $offset, 0 );
    }

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_settings_general_output', 'enlightenment_bp_bootstrap_template_member_settings_general_output' );

function enlightenment_bp_bootstrap_template_member_settings_notifications_output( $output ) {
	$output = str_replace( 'class="bp-help-text email-notifications-info"', 'class="bp-help-text email-notifications-info alert alert-info"', $output );
	$output = str_replace( 'class="notification-settings"', 'class="notification-settings table"', $output );
	$output = str_replace( '<th class="icon">&nbsp;</th>', '', $output );
	$output = str_replace( '<th class="icon"></th>', '', $output );
	$output = str_replace( '<td>&nbsp;</td>', '', $output );
	$output = str_replace( '<td></td>', '', $output );
	$output = str_replace( 'class="title"', 'class="title w-100"', $output );
	$output = str_replace( '<td>', '<td class="w-100">', $output );
	$output = str_replace( 'class="yes"', 'class="yes text-center"', $output );
	$output = str_replace( 'class="no"', 'class="no text-center"', $output );
	$output = str_replace( '<input type="radio" name="notifications[', '<div class="form-check"><input type="radio" name="notifications[', $output );
	$output = str_replace( ' id="notification-', ' class="form-check-input" id="notification-', $output );
	$output = str_replace( ' class="bp-screen-reader-text"', ' class="form-check-label"><span class="bp-screen-reader-text visually-hidden"', $output );
	$output = str_replace( '</label>', '</span></label>', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_settings_notifications_output', 'enlightenment_bp_bootstrap_template_member_settings_notifications_output' );

function enlightenment_bp_bootstrap_template_member_settings_profile_output( $output ) {
	$output = str_replace( 'class="bp-help-text profile-visibility-info"', 'class="bp-help-text profile-visibility-info alert alert-info"', $output );
	$output = str_replace( 'class="profile-settings bp-tables-user"', 'class="profile-settings bp-tables-user table"', $output );
	$output = str_replace( 'class="title field-group-name"', 'class="title field-group-name w-100"', $output );
	$output = str_replace( 'class="field-name"', 'class="field-name w-100"', $output );
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_settings_profile_output', 'enlightenment_bp_bootstrap_template_member_settings_profile_output' );

function enlightenment_bp_bootstrap_template_member_settings_invites_output( $output ) {
	$offset = strpos( $output, 'class="bp-feedback bp-messages info"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, ' alert alert-info d-flex', $offset + 35, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	$offset = strpos( $output, '<label for="account-group-invites-preferences">' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, '<div class="mb-3 form-check">', $offset, 47 );
		$offset = strpos( $output, 'id="account-group-invites-preferences"', $offset );
        $output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, "\n" . '<label class="form-check-label" for="account-group-invites-preferences">', $offset + 2, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
    }

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_settings_invites_output', 'enlightenment_bp_bootstrap_template_member_settings_invites_output' );

function enlightenment_bp_bootstrap_template_member_settings_capabilities_output( $output ) {
	$offset = strpos( $output, '<label for="user-spammer">' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, '<div class="mb-3 form-check">', $offset, 26 );
		$offset = strpos( $output, 'id="user-spammer"', $offset );
        $output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, "\n" . '<label class="form-check-label" for="user-spammer">', $offset + 2, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
    }

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_settings_capabilities_output', 'enlightenment_bp_bootstrap_template_member_settings_capabilities_output' );

function enlightenment_bp_bootstrap_template_member_settings_data_output( $output ) {
	$output = str_replace( '<p><strong><a ', '<p><a class="btn btn-primary btn-lg" ', $output );
	$output = str_replace( '</strong></p>', '</strong></p>', $output );
	$output = str_replace( 'type="submit"', 'type="submit" class="btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_settings_data_output', 'enlightenment_bp_bootstrap_template_member_settings_data_output' );

function enlightenment_bp_bootstrap_template_member_settings_delete_account_output( $output ) {
	$offset = strpos( $output, 'class="bp-feedback bp-messages warning"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' alert alert-warning d-flex', $offset + 38, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
		$output = substr_replace( $output, 'fas fa-exclamation-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
	}

	$offset = strpos( $output, '<label id="delete-account-understand" class="warn" for="delete-account-understand">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="mb-3 form-check">', $offset, 83 );
		$offset = strpos( $output, 'class="disabled"', $offset );
		$output = substr_replace( $output, 'form-check-input ', $offset + 7, 0 );
		$output = substr_replace( $output, 'id="delete-account-understand" ', $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, "\n" . '<label class="form-check-label warn" for="delete-account-understand">', $offset + 2, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_settings_delete-account_output', 'enlightenment_bp_bootstrap_template_member_settings_delete_account_output' );

function enlightenment_bp_bootstrap_template_member_settings_rtmedia_privacy_output( $output ) {
	$start = strpos( $output, '<div class="rtm-privacy-levels">' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<label>', $start );

		while ( false !== $offset && $offset < $end ) {
			$start_a  = strpos( $output, 'value="', $offset );
			$quot_chr = '"';

			if ( false === $start_a ) {
				$start_a  = strpos( $output, "value='", $offset );
				$quot_chr = "'";
			}

			$start_a = $start_a + 7;
			$end_a   = strpos( $output, $quot_chr, $start_a );
			$length  = $end_a - $start_a;
			$value   = substr( $output, $start_a, $length );

			$output = substr_replace( $output, '<tmp class="form-check">' . "\n", $offset, 7 );
			$offset = strpos( $output, sprintf( 'value=%1$s%2$s%1$s', $quot_chr, $value ), $offset );
			$output = substr_replace( $output, sprintf( 'class="form-check-input" id="rtmedia-default-privacy-%s" ', $value ), $offset, 0 );
			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, "\n" . sprintf( '<label class="form-check-label" for="rtmedia-default-privacy-%s">', $value ), $offset + 1, 0 );
			$offset = strpos( $output, '</label>', $offset );
			$output = substr_replace( $output, "\n" . '</tmp>', $offset + 8, 0 );
			$offset = strpos( $output, '<br/>', $offset );
			$output = substr_replace( $output, '', $offset, 5 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<label>', $offset );
		}
	}

	$output = str_replace( '<tmp ',  '<div ',  $output );
	$output = str_replace( 'class="rtm-privacy-levels"', 'class="rtm-privacy-levels mb-3"', $output );
	$output = str_replace( 'class="auto"', 'class="auto btn btn-primary btn-lg"', $output );

	$output = str_replace( '</tmp>', '</div>', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_member_settings_privacy_output', 'enlightenment_bp_bootstrap_template_member_settings_rtmedia_privacy_output' );

function enlightenment_bp_bootstrap_submit_button( $buttons ) {
	foreach ( $buttons as $button => $args ) {
		if ( isset( $buttons[ $button ]['attributes']['class'] ) ) {
			$buttons[ $button ]['attributes']['class'] .= ' btn btn-primary btn-lg';
		} else {
			$buttons[ $button ]['attributes']['class']  = 'btn btn-primary btn-lg';
		}
	}
	return $buttons;
}
add_filter( 'bp_nouveau_get_submit_button', 'enlightenment_bp_bootstrap_submit_button' );

function enlightenment_bp_bootstrap_group_creation_tabs( $output ) {
	$output = str_replace( 'class="group-create-buttons button-tabs"', 'class="group-create-buttons button-tabs nav"', $output );
	$output = str_replace( '<li>', '<li class="nav-item">', $output );
	$output = str_replace( 'class="current"', 'class="current nav-item"', $output );
	$output = str_replace( '<a ', '<a class="nav-link" ', $output );
	$output = str_replace( '<span>', '<span class="nav-link">', $output );

	$offset = strpos( $output, '<li class="current nav-item">' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="nav-link"', $offset );
        $output = substr_replace( $output, ' active', $offset + 15, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_group_creation_tabs', 'enlightenment_bp_bootstrap_group_creation_tabs' );

function enlightenment_bp_bootstrap_group_creation_screen( $output ) {
	$output = str_replace( 'class="submit"', 'class="submit d-flex"', $output );
	$output = str_replace( 'id="group-creation-create"', 'id="group-creation-create" class="btn btn-primary btn-lg"', $output );
	$output = str_replace( 'id="group-creation-next"', 'id="group-creation-next" class="btn btn-primary btn-lg ms-3"', $output );
	$output = str_replace( 'id="group-creation-finish"', 'id="group-creation-finish" class="btn btn-primary btn-lg ms-3"', $output );
	$output = str_replace( 'id="group-creation-previous"', 'id="group-creation-previous" class="btn btn-secondary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_group_creation_screen', 'enlightenment_bp_bootstrap_group_creation_screen' );

function enlightenment_bp_bootstrap_template_group_admin_output( $output ) {
	return str_replace( 'id="save"', 'id="save" class="btn btn-primary btn-lg"', $output );
}
add_filter( 'enlightenment_bp_filter_template_group_admin_output', 'enlightenment_bp_bootstrap_template_group_admin_output' );

function enlightenment_bp_bootstrap_group_admin_edit_details_screen( $output ) {
	$offset = strpos( $output, '<label for="group-name">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="form-label"' . "\n", $offset + 6, 0 );
		$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'id="group-name"', $offset );
        $output = substr_replace( $output, 'class="form-control" ', $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 2, 0 );
	}

	$offset = strpos( $output, '<label for="group-desc">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="form-label"' . "\n", $offset + 6, 0 );
		$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'id="group-desc"', $offset );
        $output = substr_replace( $output, 'class="form-control" ', $offset, 0 );
		$offset = strpos( $output, '</textarea>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 11, 0 );
	}

	$offset = strpos( $output, '<p class="bp-controls-wrap">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="bp-controls-wrap form-check mb-3">', $offset, 28 );
		$offset = strpos( $output, '<label for="group-notify-members" class="bp-label-text">', $offset );
        $output = substr_replace( $output, '', $offset, 56 );
		$offset = strpos( $output, 'id="group-notify-members"', $offset );
		$output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, ' <label for="group-notify-members" class="bp-label-text form-check-label">', $offset + 2, 0 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset, 4 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_group_creation_group-details_screen', 'enlightenment_bp_bootstrap_group_admin_edit_details_screen' );
// add_filter( 'enlightenment_bp_filter_group_admin_group-details_screen', 'enlightenment_bp_bootstrap_group_admin_edit_details_screen' );
add_filter( 'enlightenment_bp_filter_group_admin_edit-details_screen', 'enlightenment_bp_bootstrap_group_admin_edit_details_screen' );

function enlightenment_bp_bootstrap_group_admin_group_settings_screen( $output ) {
	$output = str_replace( 'class="group-create-types"', 'class="group-create-types mb-3"', $output );
	$output = str_replace( 'class="radio group-invitations"', 'class="radio group-invitations mb-3"', $output );

	$offset = strpos( $output, '<label ' );
	while ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$tag    = substr( $output, $start, $length );
		$tag    = str_replace( '<label ', '<label class="form-check-label" ', $tag );

		$output = substr_replace( $output, '<div class="form-check">' . "\n", $offset, $length );
		$offset = strpos( $output, 'type="', $offset );
		$output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, ' ' . $tag, $offset + 2, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );

		$offset = strpos( $output, '<label ', $offset + 1 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_group_creation_group-settings_screen', 'enlightenment_bp_bootstrap_group_admin_group_settings_screen' );
add_filter( 'enlightenment_bp_filter_group_admin_group-settings_screen', 'enlightenment_bp_bootstrap_group_admin_group_settings_screen' );

function enlightenment_bp_bootstrap_group_admin_media_setting_screen( $output ) {
	$start = strpos( $output, '<div class="radio">' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<label>', $start );

		while ( false !== $offset && $offset < $end ) {
			$id  = '';
			$val = '';
			$for = '';

			$start_a = strpos( $output, ' value="', $offset );
			if ( false !== $start_a ) {
				$start_a += 8;
				$end_a    = strpos( $output, '"', $start_a );
				$length   = $end_a - $start_a;
				$val      = substr( $output, $start_a, $length );
			}

			$start_a = strpos( $output, ' id="', $offset );
			if ( false !== $start_a ) {
				$start_a += 5;
				$end_a    = strpos( $output, '"', $start_a );
				$length   = $end_a - $start_a;
				$id       = substr( $output, $start_a, $length );

				if ( 'all' == $val && 'rt_media_group_level_moderators' == $id ) {
					$id     = 'rt_media_group_level_all';
					$output = substr_replace( $output, $id, $start_a, $length );
				}
			}

			if ( ! empty( $id ) ) {
				$for = sprintf( ' for="%s"', esc_attr( $id ) );
			}

			$output = substr_replace( $output, '<tmp class="form-check">', $offset, 7 );
			$offset = strpos( $output, '<input ', $offset );
			$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
			$offset = strpos( $output, '<strong>', $offset );
			$output = substr_replace( $output, sprintf( '<label class="form-check-label"%s>', $for ) . "\n", $offset, 0 );
			$offset = strpos( $output, '</label>', $offset );
			$output = substr_replace( $output, "\n" . '</tmp>', $offset + 8, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<label>', $offset );
		}
	}

	$output = str_replace( '<tmp ',  '<div ',  $output );
	$output = str_replace( '</tmp>', '</div>', $output );
	$output = str_replace( 'class="radio"', 'class="radio mb-3"', $output );
	$output = str_replace( '<hr>', '', $output );
	$output = str_replace( 'name="save"', 'name="save" id="save"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_group_creation_media-setting_screen', 'enlightenment_bp_bootstrap_group_admin_media_setting_screen' );
add_filter( 'enlightenment_bp_filter_group_admin_media-setting_screen', 'enlightenment_bp_bootstrap_group_admin_media_setting_screen' );

function enlightenment_bp_bootstrap_group_admin_forum_screen( $output ) {
	$checkbox_class = ' form-check';

	$offset = strpos( $output, 'class="field-group"' );
	if ( false !== $offset ) {
		while ( false !== $offset ) {
			$output = substr_replace( $output, ' mb-3', $offset + 18, 0 );

			$offset = strpos( $output, 'class="field-group"', $offset + 1 );
		}
	} else {
		$checkbox_class .= ' mb-3';
	}

	$offset = strpos( $output, '<div class="checkbox">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, $checkbox_class, $offset + 20, 0 );
		$offset = strpos( $output, '<label ', $offset );
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$tag    = substr( $output, $start, $length );
		$tag    = str_replace( '<label ', '<label class="form-check-label" ', $tag );

		$output = substr_replace( $output, '', $offset, $length );
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, 'class="form-check-input" ', $offset + 7, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, ' ' . $tag, $offset + 2, 0 );
	}

	$offset = strpos( $output, '<label for="bbp_group_forum_id">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, 'class="col-form-label" ', $offset + 7, 0 );
		$output = substr_replace( $output, '<div class="row">' . "\n" . '<div class="col-sm-3 col-xl-2">' . "\n", $offset, 0 );
		$offset = strpos( $output, '<select ', $offset );
		$output = substr_replace( $output, '</div>' . "\n" . '<div class="col-sm-9 col-xl-10">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</select>', $offset );

		$offset_a = strpos( $output, '<p class="description">', $offset );
		$end_a    = strpos( $output, '</div>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$offset = strpos( $output, '</p>', $offset_a );
			$output = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset + 4, 0 );
		} else {
			$output = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset + 9, 0 );
		}
	}

	$offset = strpos( $output, '<p class="description">' );
	while ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="description form-text">', $offset, 23 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 4 );

		$offset = strpos( $output, '<p class="description">', $offset + 1 );
	}

	$output = str_replace( 'class="bbp_dropdown"', 'class="bbp_dropdown form-select"', $output );
	$output = str_replace( 'type="submit"', 'type="submit" id="save"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_group_creation_forum_screen', 'enlightenment_bp_bootstrap_group_admin_forum_screen' );
add_filter( 'enlightenment_bp_filter_group_admin_forum_screen', 'enlightenment_bp_bootstrap_group_admin_forum_screen' );

function enlightenment_bp_bootstrap_group_admin_group_avatar_screen( $output ) {
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );

	switch( bp_get_avatar_admin_step() ) {
		case 'upload-image':
			$offset = strpos( $output, '<p>' );
			while ( false !== $offset ) {
				$end   = strpos( $output, '</p>', $offset );
				$label = strpos( $output, '<label for="file"', $offset );

				if ( false !== $label && $label < $end ) {
					$output = substr_replace( $output, 'div class="hide-if-js"', $offset + 1, 1 );
					$offset = strpos( $output, '<label for="file"', $offset );
					$start  = $offset;
					$end    = strpos( $output, '</label>', $offset ) + 8;
					$length = $end - $start;
					$label  = substr( $output, $start, $length );
					$label  = str_replace( '<label ', '<label class="form-label" ', $label );
					$output = substr_replace( $output, '', $start, $length );

					$output = substr_replace( $output, '<div class="mb-3">' . "\n" . $label . "\n", $offset, 0 );
					$offset = strpos( $output, '<input type="file"', $offset );
					$output = substr_replace( $output, 'class="form-control" ', $offset + 7, 0 );
					$offset = strpos( $output, ' />', $offset );
					$output = substr_replace( $output, "\n" . '</div>', $offset + 3, 0 );
					$offset = strpos( $output, '<input type="submit"', $offset );
					$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
					$offset = strpos( $output, 'type="submit"', $offset );
					$output = substr_replace( $output, 'class="btn btn-primary btn-lg" ', $offset, 0 );
					$offset = strpos( $output, '/>', $offset );
					$output = substr_replace( $output, "\n" . '</div>', $offset + 2, 0 );
					$offset = strpos( $output, '</p>', $offset );
					$output = substr_replace( $output, 'div', $offset + 2, 1 );

					break;
				}

				$offset = strpos( $output, '<p>', $offset + 1 );
			}

			break;

		case 'crop-image':
			$offset = strpos( $output, '<h2>' );
			if ( false !== $offset ) {
				$output = substr_replace( $output, 'p', $offset + 1, 2 );
				$offset = strpos( $output, '</h2>', $offset );
				$output = substr_replace( $output, 'p', $offset + 2, 2 );
				$offset = strpos( $output, '<img ', $offset );
				$output = substr_replace( $output, '<div class="row">' . "\n" . '<div class="col flex-grow-1 flex-shrink-0">' . "\n", $offset, 0 );
				$offset = strpos( $output, ' />', $offset );
				$output = substr_replace( $output, "\n" . '</div>', $offset + 3, 0 );
				$offset = strpos( $output, '<div id="avatar-crop-pane">', $offset );
				$output = substr_replace( $output, 'class="overflow-hidden" ', $offset + 5, 0 );
				$output = substr_replace( $output, '<div class="col flex-grow-1 flex-shrink-0 d-flex flex-column align-items-center">' . "\n", $offset, 0 );
				$offset = strpos( $output, '<input type="submit"', $offset );
				$output = substr_replace( $output, 'class="btn btn-primary btn-lg" ', $offset + 7, 0 );
				$offset = strpos( $output, ' />', $offset );
				$output = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset + 3, 0 );
			}

			break;
	}

	$offset = strpos( $output, '<div class="left-menu">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="row mb-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="left-menu"', $offset );
		$output = substr_replace( $output, ' col flex-grow-0 flex-shrink-1', $offset + 16, 0 );
		$offset = strpos( $output, 'class="main-column"', $offset );
		$output = substr_replace( $output, ' col flex-grow-1 flex-shrink-0', $offset + 18, 0 );
		$offset = strpos( $output, '</div><!-- .main-column -->', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 27, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-avatar-nav"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="bp-avatar-nav-item"', $offset );
		$output = substr_replace( $output, ' nav-link', $offset + 25, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-upload-window"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'id="{{data.drop_element}}"', $offset );
		$output = substr_replace( $output, 'class="card bg-body-secondary" ', $offset, 0 );
		$offset = strpos( $output, 'class="drag-drop-inside"', $offset );
		$output = substr_replace( $output, ' card-body text-center', $offset + 23, 0 );
		$offset = strpos( $output, 'class="drag-drop-info"', $offset );
		$output = substr_replace( $output, ' mt-3', $offset + 21, 0 );
		$offset = strpos( $output, 'id="{{data.browse_button}}"', $offset );
		$output = substr_replace( $output, 'class="btn btn-primary btn-lg" ', $offset, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-progress-window"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="bp-progress"', $offset );
		$output = substr_replace( $output, ' progress', $offset + 18, 0 );
		$offset = strpos( $output, 'class="bp-bar"', $offset );
		$output = substr_replace( $output, ' progress-bar progress-bar-striped progress-bar-animated', $offset + 13, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-avatar-item"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row">', $offset + 1, 0 );
		$offset = strpos( $output, 'id="avatar-to-crop"', $offset );
		$output = substr_replace( $output, 'class="col flex-grow-1 flex-shrink-0" ', $offset, 0 );
		$offset = strpos( $output, 'class="avatar-crop-management"', $offset );
		$output = substr_replace( $output, ' col flex-grow-1 flex-shrink-0 d-flex flex-column align-items-center', $offset + 29, 0 );
		$offset = strpos( $output, 'class="avatar"', $offset );
		$output = substr_replace( $output, ' overflow-hidden mb-3', $offset + 13, 0 );
		$offset = strpos( $output, 'class="button avatar-crop-submit"', $offset );
		$output = substr_replace( $output, ' btn btn-primary btn-lg', $offset + 32, 0 );
		$offset = strpos( $output, '</script>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-avatar-webcam"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="warning">', $offset );
		$output = substr_replace( $output, ' alert alert-warning', $offset + 14, 0 );
		$offset = strpos( $output, '#>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="row">', $offset + 2, 0 );
		$offset = strpos( $output, 'id="avatar-to-crop"', $offset );
		$output = substr_replace( $output, 'class="col flex-grow-1 flex-shrink-0" ', $offset, 0 );
		$offset = strpos( $output, 'class="avatar-crop-management"', $offset );
		$output = substr_replace( $output, ' col flex-grow-1 flex-shrink-0 d-flex flex-column align-items-center', $offset + 29, 0 );
		$offset = strpos( $output, 'class="avatar"', $offset );
		$output = substr_replace( $output, ' overflow-hidden mb-3', $offset + 13, 0 );
		$offset = strpos( $output, 'class="button avatar-webcam-capture"', $offset );
		$output = substr_replace( $output, ' btn btn-secondary btn-lg', $offset + 35, 0 );
		$offset = strpos( $output, 'class="button avatar-webcam-save"', $offset );
		$output = substr_replace( $output, ' btn btn-primary btn-lg', $offset + 32, 0 );
		$offset = strpos( $output, '<#', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-avatar-delete"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="alert alert-info"', $offset + 2, 0 );
		$offset = strpos( $output, 'class="button edit"', $offset );
		$output = substr_replace( $output, ' btn btn-danger btn-lg', $offset + 18, 0 );
		$offset = strpos( $output, 'class="bp-feedback bp-messages info"', $offset );
		$output = substr_replace( $output, ' alert alert-info d-flex ', $offset + 30, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
		$output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
		$offset = strpos( $output, 'class="button edit"', $offset );
		$output = substr_replace( $output, ' btn btn-danger btn-lg', $offset + 18, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_group_creation_group-avatar_screen', 'enlightenment_bp_bootstrap_group_admin_group_avatar_screen' );
add_filter( 'enlightenment_bp_filter_group_admin_group-avatar_screen', 'enlightenment_bp_bootstrap_group_admin_group_avatar_screen' );

function enlightenment_bp_bootstrap_group_admin_group_cover_image_screen( $output ) {
	$offset = strpos( $output, 'id="tmpl-upload-window"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'id="{{data.drop_element}}"', $offset );
		$output = substr_replace( $output, 'class="card bg-body-secondary" ', $offset, 0 );
		$offset = strpos( $output, 'class="drag-drop-inside"', $offset );
		$output = substr_replace( $output, ' card-body text-center', $offset + 23, 0 );
		$offset = strpos( $output, 'class="drag-drop-info"', $offset );
		$output = substr_replace( $output, ' mt-3', $offset + 21, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
		$output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'id="{{data.browse_button}}"', $offset );
		$output = substr_replace( $output, 'class="btn btn-primary btn-lg" ', $offset, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-progress-window"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="bp-progress"', $offset );
		$output = substr_replace( $output, ' progress', $offset + 18, 0 );
		$offset = strpos( $output, 'class="bp-bar"', $offset );
		$output = substr_replace( $output, ' progress-bar progress-bar-striped progress-bar-animated', $offset + 13, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-cover-image-delete"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="alert alert-info mt-3"', $offset + 2, 0 );
		$offset = strpos( $output, 'class="button edit"', $offset );
		$output = substr_replace( $output, ' btn btn-danger btn-lg', $offset + 18, 0 );
		$offset = strpos( $output, 'class="bp-feedback bp-messages info"', $offset );
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="alert alert-info mt-3"', $offset + 2, 0 );
		$offset = strpos( $output, 'class="button edit"', $offset );
		$output = substr_replace( $output, ' btn btn-danger btn-lg', $offset + 18, 0 );
		$offset = strpos( $output, 'class="bp-feedback bp-messages info"', $offset );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_group_creation_group-cover-image_screen', 'enlightenment_bp_bootstrap_group_admin_group_cover_image_screen' );
add_filter( 'enlightenment_bp_filter_group_admin_group-cover-image_screen', 'enlightenment_bp_bootstrap_group_admin_group_cover_image_screen' );

function enlightenment_bp_bootstrap_group_invites_screen( $output ) {
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = str_replace( 'class="bp-invites-nav-item"', 'class="bp-invites-nav-item btn btn-outline-secondary"', $output );
	$output = str_replace( 'id="group_invites_search"', 'id="group_invites_search" class="form-control"', $output );
	$output = str_replace( 'class="nouveau-search-submit"', 'class="nouveau-search-submit btn btn-light"', $output );
	$output = str_replace( 'class="dashicons dashicons-search"', 'class="fas fa-search"', $output );
	$output = str_replace( 'class="button bp-secondary-action"', 'class="button bp-secondary-action btn btn-secondary"', $output );
	$output = str_replace( 'class="button bp-primary-action"', 'class="button bp-primary-action btn btn-primary"', $output );

	$offset = strpos( $output, 'id="tmpl-bp-group-invites-feedback"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="bp-feedback ', $offset );
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-invites-users"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="d-flex align-items-center">', $offset + 1, 0 );
        $offset = strpos( $output, 'class="item-avatar"', $offset );
        $output = substr_replace( $output, ' me-2', $offset + 18, 0 );
		$offset = strpos( $output, 'class="item"', $offset );
        $output = substr_replace( $output, ' me-2', $offset + 11, 0 );
		$offset = strpos( $output, 'class="group-inviters"', $offset );
        $output = substr_replace( $output, ' list-unstyled d-flex align-items-center', $offset + 21, 0 );
		$offset = strpos( $output, '<li>', $offset );
		$offset = strpos( $output, '<li>', $offset + 1 );
        $output = substr_replace( $output, ' class="ms-1"', $offset + 3, 0 );
		$offset = strpos( $output, 'class="action"', $offset );
        $output = substr_replace( $output, ' ms-auto', $offset + 13, 0 );
		$offset = strpos( $output, 'class="button ', $offset );
        $output = substr_replace( $output, ' btn btn-link btn-lg', $offset + 13, 0 );
		$offset = strpos( $output, 'class="icons"', $offset );
        $output = substr_replace( $output, ' fas fa-<# if ( data.selected ) { #>minus<# } else { #>plus<# } #>-circle', $offset + 12, 0 );
		$offset = strpos( $output, 'class="button ', $offset );
        $output = substr_replace( $output, ' btn btn-link btn-lg', $offset + 13, 0 );
		$offset = strpos( $output, 'class=" icons"', $offset );
        $output = substr_replace( $output, ' fas fa-minus-circle', $offset + 13, 0 );
		$offset = strpos( $output, '</script>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-invites-form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, ' class="form-label"', $offset + 6, 0 );
		$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="bp-faux-placeholder-label"', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 32, 0 );
		$offset = strpos( $output, '</textarea>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 11, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-invites-filters"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, '<div class="input-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-invites-paginate"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<# if ( 1 !== data.page || data.total_page !== data.page ) { #>' . "\n" . '<ul class="pagination">', $offset + 1, 0 );

		$offset = strpos( $output, '<a ', $offset );
		$output = substr_replace( $output, '<li class="page-item">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="button ', $offset );
        $output = substr_replace( $output, ' page-link', $offset + 13, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-arrow-left"', $offset );
        $output = substr_replace( $output, 'fas fa-arrow-left', $offset + 7, 30 );
		$offset = strpos( $output, '</a>', $offset );
		$output = substr_replace( $output, "\n" . '</li>', $offset + 4, 0 );

		$offset = strpos( $output, '<a ', $offset );
		$output = substr_replace( $output, '<li class="page-item">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="button ', $offset );
        $output = substr_replace( $output, ' page-link', $offset + 13, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-arrow-right"', $offset );
        $output = substr_replace( $output, 'fas fa-arrow-right', $offset + 7, 31 );
		$offset = strpos( $output, '</a>', $offset );
		$output = substr_replace( $output, "\n" . '</li>', $offset + 4, 0 );

		$offset = strpos( $output, '<# } #>', $offset );
        $output = substr_replace( $output, "\n" . '</ul>' . "\n" . '<# } #>', $offset + 7, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_group_creation_group-invites_screen', 'enlightenment_bp_bootstrap_group_invites_screen' );
add_filter( 'enlightenment_bp_filter_group_admin_group-invites_screen', 'enlightenment_bp_bootstrap_group_invites_screen' );
add_filter( 'enlightenment_bp_filter_template_group_send-invites_output', 'enlightenment_bp_bootstrap_group_invites_screen' );
add_filter( 'enlightenment_bp_filter_wp_footer_content', 'enlightenment_bp_bootstrap_group_invites_screen' );

function enlightenment_bp_bootstrap_group_admin_manage_members_screen( $output ) {
	$output = str_replace( 'class="subnav-filters"', 'class="subnav-filters list-unstyled row align-items-center"', $output );
	$output = str_replace( 'class="last filter"', 'class="last filter col"', $output );
	$output = str_replace( 'class="left-menu"', 'class="left-menu col d-flex flex-row-reverse"', $output );
	$output = str_replace( 'class="bp-search"', 'class="bp-search col-12 order-first mb-3"', $output );
	$output = str_replace( '<table id="group-members-list-table" class="bp-list"></table>', '<div class="table-responsive"><table id="group-members-list-table" class="bp-list table"></table></div>', $output );

	$offset = strpos( $output, 'class="subnav-filters filters ' );
    if ( false !== $offset ) {
        $offset = strpos( $output, '<ul>', $offset );
        $output = substr_replace( $output, ' class="col-md flex-md-grow-1 flex-md-shrink-0 d-flex align-items-center list-unstyled mb-0"', $offset + 3, 0 );
    }

	return $output;
}
add_filter( 'enlightenment_bp_filter_group_admin_manage-members_screen', 'enlightenment_bp_bootstrap_group_admin_manage_members_screen' );

function enlightenment_bp_bootstrap_group_admin_manage_members_js_templates( $output ) {
	$offset = strpos( $output, 'id="tmpl-bp-manage-members-empty-row"' );
    if ( false !== $offset ) {
		$offset = strpos( $output, 'class="bp-feedback info"' );
		$output = substr_replace( $output, ' alert alert-info d-flex mb-0', $offset + 23, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
		$output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	$offset = strpos( $output, 'id="tmpl-bp-manage-members-row"' );
    if ( false !== $offset ) {
		$offset = strpos( $output, '<td class="uname-column">' );
		$output = substr_replace( $output, "\n" . '<div class="d-flex align-items-center">' . "\n" . '<div class="group-avatar me-3">' . "\n" . '<a href="{{{data.link}}}">' . "\n" . '<img src="{{{data.avatar_urls.thumb}}}" alt="{{data.name}}" class="avatar profile-photo"/>' . "\n" . '</a>' . "\n" . '</div>', $offset + 25, 0 );
		$offset = strpos( $output, '<div class="group-member">', $offset );
		$output = substr_replace( $output, '<div class="w-100">' . "\n", $offset, 0 );

		$offset = strpos( $output, '<img ', $offset );
		$start  = $offset;
		$end    = strpos( $output, '/>', $start ) + 2;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );

		$offset = strpos( $output, 'class="group-member-actions row-actions"', $offset );
		$output = substr_replace( $output, ' btn-group', $offset + 39, 0 );

		$offset_a = strpos( $output, '<span ', $offset );
		$end_a    = strpos( $output, '</div>', $offset );
		while ( false !== $offset_a && $offset_a < $end_a ) {
			$offset_a = strpos( $output, 'class="', $offset_a );
			$output   = substr_replace( $output, 'btn-group ', $offset_a + 7, 0 );
			$offset_a = strpos( $output, '<a ', $offset_a );
			$output   = substr_replace( $output, ' class="btn btn-secondary btn-sm"', $offset_a + 2, 0 );

			$offset_b = strpos( $output, '|', $offset_a );
			$end_b    = strpos( $output, '</span>', $offset_a );
			if ( false !== $offset_b && $offset_b < $end_b ) {
				$output = substr_replace( $output, '', $offset_b, 1 );
			}

			$end_a    = strpos( $output, '</div>', $offset );
			$offset_a = strpos( $output, '<span ', $offset_a );
		}

		$offset_a = strpos( $output, '<span>', $offset );
		$end_a    = strpos( $output, '</div>', $offset );
		while ( false !== $offset_a && $offset_a < $end_a ) {
			$output   = substr_replace( $output, ' class="btn-group"', $offset_a + 5, 0 );
			$offset_a = strpos( $output, '<a ', $offset_a );
			$output   = substr_replace( $output, ' class="btn btn-secondary btn-sm"', $offset_a + 2, 0 );

			$offset_b = strpos( $output, '|', $offset_a );
			$end_b    = strpos( $output, '</span>', $offset_a );
			if ( false !== $offset_b && $offset_b < $end_b ) {
				$output = substr_replace( $output, '', $offset_b, 1 );
			}

			$end_a    = strpos( $output, '</div>', $offset );
			$offset_a = strpos( $output, '<span>', $offset_a );
		}

		$offset = strpos( $output, '</td>', $offset );
		$output = substr_replace( $output, '</div>' . "\n" . '</div>' . "\n", $offset, 0 );
    }

	$offset = strpos( $output, 'id="tmpl-bp-manage-members-search"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
		$output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, '<div class="input-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="small"', $offset );
		$output = substr_replace( $output, ' form-control', $offset + 12, 0 );
		$offset = strpos( $output, 'class="bp-button bp-search"', $offset );
		$output = substr_replace( $output, ' btn btn-light', $offset + 26, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-search"', $offset );
		$output = substr_replace( $output, 'fas fa-search', $offset + 7, 26 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
		$output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
	}

	$offset = strpos( $output, 'id="tmpl-bp-manage-members-paginate"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<# if ( 1', $offset );
		$output = substr_replace( $output, '<ul class="pagination">' . "\n", $offset, 0 );
		$offset = strpos( $output, '<button ', $offset );
        $output = substr_replace( $output, '<li class="page-item">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="group-members-paginate-button"', $offset );
		$output = substr_replace( $output, ' page-link', $offset + 36, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-arrow-left"', $offset );
        $output = substr_replace( $output, 'fas fa-arrow-left', $offset + 7, 30 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
		$output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</li>', $offset + 9, 0 );
		$offset = strpos( $output, '<button ', $offset );
        $output = substr_replace( $output, '<li class="page-item">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="group-members-paginate-button"', $offset );
		$output = substr_replace( $output, ' page-link', $offset + 36, 0 );
		$offset = strpos( $output, 'class="bp-screen-reader-text"', $offset );
		$output = substr_replace( $output, ' visually-hidden', $offset + 28, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-arrow-right"', $offset );
        $output = substr_replace( $output, 'fas fa-arrow-right', $offset + 7, 31 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</li>', $offset + 9, 0 );
		$offset = strpos( $output, '<# } #>', $offset );
		$output = substr_replace( $output, "\n" . '</ul>', $offset + 7, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_group_admin_manage-members_screen', 'enlightenment_bp_bootstrap_group_admin_manage_members_js_templates' );
add_filter( 'enlightenment_bp_filter_wp_footer_content', 'enlightenment_bp_bootstrap_group_admin_manage_members_js_templates' );

function enlightenment_bp_bootstrap_template_groups_single_requests_loop_output( $output ) {
	$output = str_replace( 'class="item-list bp-list membership-requests-list"', 'class="item-list bp-list membership-requests-list list-unstyled row"', $output );
	$output = str_replace( '<li>', '<li class="col-12 col-md-6 d-flex align-items-center">', $output );
	$output = str_replace( 'class=" groups-meta action"', 'class="groups-meta action btn-group"', $output );
	$output = str_replace( 'class="generic-button"', 'class="generic-button btn-group"', $output );
	$output = str_replace( 'class="button ', 'class="button btn btn-secondary btn-sm ', $output );

	$offset = strpos( $output, 'class="bp-feedback bp-messages info"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	$offset = strpos( $output, 'id="request-list"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<li class="col-12 ', $offset );
	    while ( false !== $offset ) {
			$offset = strpos( $output, 'lass="item-avatar"', $offset );
	        $output = substr_replace( $output, ' me-3', $offset + 17, 0 );
			$offset = strpos( $output, '<div class="item">', $offset );
	        $output = substr_replace( $output, '<div class="w-100">'. "\n", $offset, 0 );
			$offset = strpos( $output, '</li>', $offset );
	        $output = substr_replace( $output, '</div>'. "\n", $offset, 0 );

			$offset = strpos( $output, '<li class="col-12 ', $offset );
	    }
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_groups/single/requests-loop_output', 'enlightenment_bp_bootstrap_template_groups_single_requests_loop_output' );

function enlightenment_bp_bootstrap_group_admin_delete_group_screen( $output ) {
	$output = str_replace( 'id="delete-group-button"', 'id="delete-group-button" class="btn btn-primary btn-lg"', $output );

	$offset = strpos( $output, 'class="bp-feedback bp-messages warning"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' alert alert-warning d-flex', $offset + 38, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
		$output = substr_replace( $output, 'fas fa-exclamation-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
	}

	$offset = strpos( $output, '<label for="delete-group-understand" class="bp-label-text warn">' );
	if ( false !== $offset ) {
		$start  = $offset;
		$end    = strpos( $output, '>', $start ) + 1;
		$length = $end - $start;
		$tag    = substr( $output, $start, $length );
		$tag    = str_replace( 'class="bp-label-text warn"', 'class="bp-label-text warn form-check-label"', $tag );

		$output = substr_replace( $output, '<div class="form-check mb-3">' . "\n", $offset, $length );
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, 'class="form-check-input" ', $offset + 7, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, ' ' . $tag, $offset + 2, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_group_admin_delete-group_screen', 'enlightenment_bp_bootstrap_group_admin_delete_group_screen' );

function enlightenment_bp_bootstrap_group_header_buttons( $output ) {
	$output = str_replace( 'class="generic-button"', 'class="generic-button d-flex"', $output );
	$output = str_replace( 'class="group-button ', 'class="group-button btn btn-primary btn-lg ', $output );

	return $output;
}
add_filter( 'enlightenment_bp_group_header_buttons', 'enlightenment_bp_bootstrap_group_header_buttons' );

function enlightenment_bp_bootstrap_group_nav( $output ) {
	$output = str_replace( '<ul>', '<ul class="nav">', $output );
	$output = str_replace( 'class="bp-priority-object-nav-nav-items"', 'class="bp-priority-object-nav-nav-items nav d-flex"', $output );
	$output = str_replace( 'class="bp-groups-tab ', 'class="bp-groups-tab nav-item ', $output );
	$output = str_replace( 'class="bp-groups-tab"', 'class="bp-groups-tab nav-item"', $output );
	$output = str_replace( '<a ', '<a class="nav-link" ', $output );

	$offset = strpos( $output, 'class="bp-groups-tab nav-item current selected"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, 'class="nav-link"', $offset );
		$output = substr_replace( $output, ' active', $offset + 15, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_group_nav', 'enlightenment_bp_bootstrap_group_nav' );

function enlightenment_bp_bootstrap_group_header_actions( $output ) {
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'id="group-admins"', 'id="group-admins" class="list-unstyled d-flex flex-wrap"', $output );
	$output = str_replace( 'id="group-mods"', 'id="group-mods" class="list-unstyled d-flex flex-wrap"', $output );
	$output = str_replace( 'class="bp-tooltip"', 'class="bp-tooltip d-block"', $output );
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );

	return $output;
}
add_filter( 'enlightenment_bp_group_header_actions', 'enlightenment_bp_bootstrap_group_header_actions' );

function enlightenment_bp_bootstrap_group_status_message_output( $output ) {
	$output = str_replace( 'class="info"', 'class="info alert alert-info"', $output );
	$output = str_replace( '<p>', '<p class="mb-0">', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_group_status_message_output', 'enlightenment_bp_bootstrap_group_status_message_output' );

function enlightenment_bp_bootstrap_template_group_request_membership_output( $output ) {
	$output = str_replace( '<p><input type="submit" name="group-request-send"', '<input type="submit" name="group-request-send" class="btn btn-primary btn-lg"', $output );

	$offset = strpos( $output, 'id="request-membership-form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<label ', $offset );
		$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'id="group-request-membership-comments"', $offset );
        $output = substr_replace( $output, 'class="form-control" ', $offset, 0 );
		$offset = strpos( $output, '</textarea>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 11, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_group_request-membership_output', 'enlightenment_bp_bootstrap_template_group_request_membership_output' );

function enlightenment_bp_bootstrap_template_group_activity_output( $output ) {
	$output = str_replace( 'class="bp-screen-title bp-screen-reader-text"', 'class="bp-screen-title bp-screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );

	$output = str_replace( 'class="subnav-filters filters clearfix"', 'class="subnav-filters filters row align-items-center"', $output );
	$output = str_replace( 'class="subnav-search clearfix"', 'class="subnav-search col-md flex-md-grow-1 flex-md-shrink-0 d-flex align-items-center"', $output );
	$output = str_replace( 'class="group-act-search"', 'class="group-act-search order-first w-100"', $output );
	$output = str_replace( 'class="component-filters clearfix"', 'class="component-filters col-md flex-md-grow-0 flex-md-shrink-1"', $output );
	$output = str_replace( 'id="group-home-search"', 'id="group-home-search" class="form-control"', $output );
	$output = str_replace( 'id="group-activity-search"', 'id="group-activity-search" class="form-control"', $output );
	$output = str_replace( 'id="activity-filter-by"', 'id="activity-filter-by" class="form-select w-auto"', $output );
	$output = str_replace( 'class="nouveau-search-submit"', 'class="nouveau-search-submit btn btn-light"', $output );
	$output = str_replace( 'class="dashicons dashicons-search"', 'class="fas fa-search"', $output );

	$offset = strpos( $output, 'class="subnav-filters filters ' );
    if ( false !== $offset ) {
        $offset = strpos( $output, '<ul>', $offset );
        $output = substr_replace( $output, ' class="col-md flex-md-grow-1 flex-md-shrink-0 d-flex align-items-center list-unstyled mb-0"', $offset + 3, 0 );
    }

	$offset = strpos( $output, 'class="bp-dir-search-form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, '<div class="input-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
	}

	$offset = strpos( $output, '<li id="bp-activity-ajax-loader">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, 'div', $offset + 1, 2 );
		$offset = strpos( $output, '</li>', $offset );
		$output = substr_replace( $output, 'div', $offset + 2, 2 );
	}

	$offset = strpos( $output, 'id="bp-activity-ajax-loader"' );
    if ( false !== $offset ) {
        $offset = strpos( $output, 'class="bp-feedback bp-messages loading"', $offset );
        $output = substr_replace( $output, 'd-flex align-items-center justify-content-center ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-spinner fa-pulse ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="ms-2 mb-0"', $offset + 2, 0 );
    }

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_group_activity_output', 'enlightenment_bp_bootstrap_template_group_activity_output' );

function enlightenment_bp_bootstrap_template_group_members_output( $output ) {
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = str_replace( 'class="subnav-filters filters clearfix no-subnav"', 'class="subnav-filters filters no-subnav row align-items-center"', $output );
	$output = str_replace( 'class="group-search members-search bp-search"', 'class="group-search members-search bp-search col-md flex-md-grow-1 flex-md-shrink-0 d-flex align-items-center"', $output );
	$output = str_replace( 'class="component-filters clearfix"', 'class="component-filters col-md flex-md-grow-0 flex-md-shrink-1"', $output );
	$output = str_replace( 'id="group-members-search"', 'id="group-members-search" class="form-control"', $output );
	$output = str_replace( 'id="groups_members-order-by"', 'id="groups_members-order-by" class="form-select w-auto"', $output );
	$output = str_replace( 'class="nouveau-search-submit"', 'class="nouveau-search-submit btn btn-light"', $output );
	$output = str_replace( 'class="dashicons dashicons-search"', 'class="fas fa-search"', $output );

	$offset = strpos( $output, 'class="bp-dir-search-form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, '<div class="input-group">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</button>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_group_members_output', 'enlightenment_bp_bootstrap_template_group_members_output' );

function enlightenment_bp_bootstrap_template_group_subnav( $output ) {
	$output = str_replace( 'class="subnav ', 'class="subnav list-unstyled btn-group d-inline-flex mb-0 ', $output );
	$output = str_replace( 'class="subnav"', 'class="subnav list-unstyled btn-group d-inline-flex mb-0"', $output );
	$output = str_replace( 'class="bp-groups-admin-tab ', 'class="bp-groups-admin-tab btn-group ', $output );
	$output = str_replace( 'class="bp-groups-admin-tab"', 'class="bp-groups-admin-tab btn-group"', $output );

	$offset = strpos( $output, 'class="bp-groups-admin-tab btn-group"' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '<a ', $offset );
		$output = substr_replace( $output, 'class="btn btn-outline-secondary" ', $offset + 3, 0 );

		$offset = strpos( $output, 'class="bp-groups-admin-tab btn-group"', $offset );
	}

	$offset = strpos( $output, 'class="bp-groups-admin-tab btn-group current selected"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<a ', $offset );
		$output = substr_replace( $output, 'class="btn btn-secondary" ', $offset + 3, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_group_admin_output', 'enlightenment_bp_bootstrap_template_group_subnav' );

function enlightenment_bp_bootstrap_template_blogs_blogs_loop_output( $output ) {
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );

	$offset = strpos( $output, 'class="bp-feedback bp-messages info"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	$output = str_replace( 'class="list-wrap"', 'class="list-wrap row align-items-center"', $output );
	$output = str_replace( 'class="item-avatar"', 'class="item-avatar col flex-grow-0 flex-shrink-1"', $output );
	$output = str_replace( 'class="item"', 'class="item col flex-grow-1 flex-shrink-0"', $output );
	$output = str_replace( 'class=" blogs-meta action"', 'class="blogs-meta action list-unstyled"', $output );
	$output = str_replace( 'class="blog-button ', 'class="blog-button btn btn-secondary ', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_blogs/blogs-loop_output', 'enlightenment_bp_bootstrap_template_blogs_blogs_loop_output' );

function enlightenment_bp_bootstrap_blog_create_form( $output ) {
	$offset = strpos( $output, 'class="bp-feedback bp-messages info"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	if ( ! bp_blog_signup_enabled() ) {
		return $output;
	}

	if ( isset( $_POST['stage'] ) && 'gimmeanotherblog' == $_POST['stage'] ) {
		if ( did_action( 'wp_insert_site') ) {
			$offset = strpos( $output, '<p>' );

			if ( false !== $offset ) {
				$output = substr_replace( $output, ' class="alert alert-success"', $offset + 2, 0 );
			}
		} else {
			$result = bp_blogs_validate_blog_form();

			if ( isset( $result['errors'] ) && is_wp_error( $result['errors'] ) ) {
				$offset = strpos( $output, '<p>' );

			    if ( false !== $offset ) {
			        $output = substr_replace( $output, ' class="alert alert-danger"', $offset + 2, 0 );
			    }
			}
		}
	}

	$offset = strpos( $output, '<label for="blogname">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
        $output = substr_replace( $output, '<span class="mb-3 row">' . "\n" . '<span class="col-sm-2">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, "\n" . '</span>' . "\n" . '<span class="col-sm-10">', $offset + 8, 0 );

		$error     = '';
		$offseet_a = strpos( $output, '<p class="error">', $offset );
		$end_a     = strpos( $output, '<input name="blogname"', $offset );
		if ( false !== $offseet_a && $offseet_a < $end_a ) {
			$start  = $offseet_a;
			$end    = strpos( $output, '</p>', $start ) + 4;
			$length = $end - $start;
			$error  = substr( $output, $start, $length );
			$error  = str_replace( '<p class="error">', '<span class="d-block form-text text-danger">', $error );
			$error  = str_replace( '</p>', '</span>', $error );

			$output = substr_replace( $output, '', $start, $length );
		}

		if ( is_subdomain_install() ) {
			$offset = strpos( $output, '<input name="blogname"', $offset );
	        $output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
	        $output = substr_replace( $output, '<span class="input-group">' . "\n", $offset, 0 );
			$offset = strpos( $output, '<span class="suffix_address">', $offset );
	        $output = substr_replace( $output, ' input-group-text', $offset + 27, 0 );
			$offset = strpos( $output, '</span>', $offset );
	        $output = substr_replace( $output, "\n" . '</span>' . "\n" . $error, $offset + 7, 0 );
		} else {
			$offset = strpos( $output, '<span class="prefix_address">', $offset );
	        $output = substr_replace( $output, ' input-group-text', $offset + 27, 0 );
	        $output = substr_replace( $output, '<span class="input-group">' . "\n", $offset, 0 );
			$offset = strpos( $output, '<input name="blogname"', $offset );
	        $output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
			$offset = strpos( $output, '/>', $offset );
	        $output = substr_replace( $output, "\n" . '</span>' . $error . "\n", $offset + 2, 0 );
		}

		$offset = strpos( $output, '<br />', $offset );
        $output = substr_replace( $output, "\n" . '</span>' . "\n" . '</span>', $offset, 6 );
    }

	$offset = strpos( $output, '<label for="blog_title">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
        $output = substr_replace( $output, '<span class="mb-3 row">' . "\n" . '<span class="col-sm-2">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, "\n" . '</span>' . "\n" . '<span class="col-sm-10">', $offset + 8, 0 );

		$error     = '';
		$offseet_a = strpos( $output, '<p class="error">', $offset );
		$end_a     = strpos( $output, '<input name="blog_title"', $offset );
		if ( false !== $offseet_a && $offseet_a < $end_a ) {
			$start  = $offseet_a;
			$end    = strpos( $output, '</p>', $start ) + 4;
			$length = $end - $start;
			$error  = substr( $output, $start, $length );
			$error  = str_replace( '<p class="error">', '<span class="d-block form-text text-danger">', $error );
			$error  = str_replace( '</p>', '</span>', $error );

			$output = substr_replace( $output, '', $start, $length );
		}

		$offset = strpos( $output, '<input name="blog_title"', $offset );
        $output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
		$offset = strpos( $output, '/>', $offset );
        $output = substr_replace( $output, "\n" . $error . "\n" . '</span>' . "\n" . '</span>', $offset + 2, 0 );
	}

	$offset = strpos( $output, '<fieldset class="create-site">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, "\n" . '<div class="mb-3">', $offset + 30, 0 );
		$offset = strpos( $output, '</fieldset>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, '<label class="checkbox" for="blog_public_on">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, '<span class="form-check">', $offset, 45 );
		$offset = strpos( $output, 'id="blog_public_on"', $offset );
		$output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, '<strong>', $offset );
		$output = substr_replace( $output, '<label class="checkbox form-check-label" for="blog_public_on">', $offset, 8 );
		$offset = strpos( $output, '</strong>', $offset );
		$output = substr_replace( $output, '', $offset, 9 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</span>', $offset + 8, 0 );
	}

	$offset = strpos( $output, '<label class="checkbox" for="blog_public_off">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, '<span class="form-check">', $offset, 46 );
		$offset = strpos( $output, 'id="blog_public_off"', $offset );
		$output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, '<strong>', $offset );
		$output = substr_replace( $output, '<label class="checkbox form-check-label" for="blog_public_off">', $offset, 8 );
		$offset = strpos( $output, '</strong>', $offset );
		$output = substr_replace( $output, '', $offset, 9 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</span>', $offset + 8, 0 );
	}

	$output = str_replace( 'class="submit"', 'class="submit btn btn-primary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_blog_create_form', 'enlightenment_bp_bootstrap_blog_create_form' );

function enlightenment_bp_bootstrap_register_form( $output ) {
	$offset = strpos( $output, 'class="bp-feedback bp-messages info"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-info d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-info-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	$offset = strpos( $output, '<label for="signup_username">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
        $output = substr_replace( $output, '<div class="mb-3 row">' . "\n" . '<div class="col-sm-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="col-sm-9">', $offset + 8, 0 );

		$error     = '';
		$offseet_a = strpos( $output, '<div class="bp-messages bp-feedback error">', $offset );
		$end_a     = strpos( $output, '<input ', $offset );
		if ( false !== $offseet_a && $offseet_a < $end_a ) {
			$start  = strpos( $output, '<p>', $offseet_a );
			$end    = strpos( $output, '</p>', $start ) + 4;
			$length = $end - $start;
			$error  = substr( $output, $start, $length );
			$error  = str_replace( '<p>', '<span class="d-block form-text text-danger">', $error );
			$error  = str_replace( '</p>', '</span>', $error );

			$start  = $offseet_a;
			$end    = strpos( $output, '</div>', $start ) + 6;
			$length = $end - $start;
			$output = substr_replace( $output, '', $start, $length );
		}

		$offset = strpos( $output, '<input ', $offset );
        $output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
		$offset = strpos( $output, '/>', $offset );
        $output = substr_replace( $output, "\n" . $error . "\n" . '</div>' . "\n" . '</div>', $offset + 2, 0 );
	}

	$offset = strpos( $output, '<label for="signup_email">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
        $output = substr_replace( $output, '<div class="mb-3 row">' . "\n" . '<div class="col-sm-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="col-sm-9">', $offset + 8, 0 );

		$error     = '';
		$offseet_a = strpos( $output, '<div class="bp-messages bp-feedback error">', $offset );
		$end_a     = strpos( $output, '<input ', $offset );
		if ( false !== $offseet_a && $offseet_a < $end_a ) {
			$start  = strpos( $output, '<p>', $offseet_a );
			$end    = strpos( $output, '</p>', $start ) + 4;
			$length = $end - $start;
			$error  = substr( $output, $start, $length );
			$error  = str_replace( '<p>', '<span class="d-block form-text text-danger">', $error );
			$error  = str_replace( '</p>', '</span>', $error );

			$start  = $offseet_a;
			$end    = strpos( $output, '</div>', $start ) + 6;
			$length = $end - $start;
			$output = substr_replace( $output, '', $start, $length );
		}

		$offset = strpos( $output, '<input ', $offset );
        $output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
		$offset = strpos( $output, '/>', $offset );
        $output = substr_replace( $output, "\n" . $error . "\n" . '</div>' . "\n" . '</div>', $offset + 2, 0 );
	}

	$offset = strpos( $output, '<label for="pass1">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
        $output = substr_replace( $output, '<div class="row">' . "\n" . '<div class="col-sm-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
		$offset = strpos( $output, 'class="user-pass1-wrap"', $offset );
        $output = substr_replace( $output, ' col-sm-9', $offset + 22, 0 );
		$offset = strpos( $output, 'class="wp-pwd"', $offset );
        $output = substr_replace( $output, ' mb-3', $offset + 13, 0 );
		$offset = strpos( $output, 'class="password-input-wrapper"', $offset );
        $output = substr_replace( $output, ' input-group', $offset + 29, 0 );
		$offset = strpos( $output, 'class="password-entry"', $offset );
        $output = substr_replace( $output, ' form-control', $offset + 21, 0 );
		$offset = strpos( $output, 'class="button wp-hide-pw"', $offset );
        $output = substr_replace( $output, ' btn btn-light', $offset + 24, 0 );
		$offset = strpos( $output, 'class="dashicons dashicons-hidden"', $offset );
        $output = substr_replace( $output, 'far fa-eye-slash', $offset + 7, 26 );
		$offset = strpos( $output, '<div id="pass-strength-result"', $offset );
        $output = substr_replace( $output, '<div class="form-text"><span', $offset, 4 );
		$offset = strpos( $output, '</div>', $offset );
        $output = substr_replace( $output, '</span></div>', $offset, 6 );
		$offset = strpos( $output, 'class="pw-weak"', $offset );
        $output = substr_replace( $output, ' mb-3 form-check', $offset + 14, 0 );
		$offset = strpos( $output, '<label>', $offset );
        $output = substr_replace( $output, '', $offset, 7 );
		$offset = strpos( $output, '<input ', $offset );
        $output = substr_replace( $output, ' id="pw-weak"', $offset + 6, 0 );
		$offset = strpos( $output, 'class="pw-checkbox"', $offset );
        $output = substr_replace( $output, ' form-check-input', $offset + 18, 0 );
		$offset = strpos( $output, '/>', $offset );
        $output = substr_replace( $output, "\n" . '<label for="pw-weak" class="form-check-label">', $offset + 2, 0 );
		$offset = strpos( $output, '</div>', $offset );
		$offset = strpos( $output, '</div>', $offset + 1 );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
	}

	$offset = strpos( $output, '<p class="user-pass2-wrap">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="user-pass2-wrap mb-3 row">', $offset, 27 );
		$offset = strpos( $output, '<label for="pass2">', $offset );
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
		$output = substr_replace( $output, '<div class="col-sm-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
		$offset = strpos( $output, '<br />', $offset );
        $output = substr_replace( $output, "\n" . '<div class="col-sm-9">', $offset, 6 );
		$offset = strpos( $output, 'class="password-entry-confirm"', $offset );
        $output = substr_replace( $output, ' form-control', $offset + 29, 0 );
		$offset = strpos( $output, '/>', $offset );
        // $output = substr_replace( $output, "\n" . '</div>', $offset + 2, 0 );
		$offset = strpos( $output, '</p>', $offset );
        $output = substr_replace( $output, '', $offset, 4 );
		$offset = strpos( $output, '<p class="description indicator-hint">', $offset );
        $output = substr_replace( $output, '<div class="description indicator-hint form-text">', $offset, 38 );
		$offset = strpos( $output, '</p>', $offset );
        $output = substr_replace( $output, '</div>' . "\n" . '</div>' . "\n" . '</div>', $offset, 4 );
	}

	$offset = strpos( $output, 'id="blog-details-section"' );
    if ( false !== $offset ) {
		$offset = strpos( $output, '<p><label for="signup_with_blog">', $offset );
		$output = substr_replace( $output, '<div class="mb-3 form-check">', $offset, 33 );
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, ' class="form-check-input"', $offset + 6, 0 );
		$offset = strpos( $output, '/>', $offset );
        $output = substr_replace( $output, ' <label class="form-check-label" for="signup_with_blog">', $offset + 2, 0 );
		$offset = strpos( $output, '</p>', $offset );
        $output = substr_replace( $output, '</div>', $offset, 4 );
	}

	/**
	 * Register a Site
	**/
	$offset = strpos( $output, '<label for="signup_blog_url">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
        $output = substr_replace( $output, '<div class="mb-3 row">' . "\n" . '<div class="col-sm-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="col-sm-9">', $offset + 8, 0 );

		$error     = '';
		$offseet_a = strpos( $output, '<div class="bp-messages bp-feedback error">', $offset );
		$end_a     = strpos( $output, '<input ', $offset );
		if ( false !== $offseet_a && $offseet_a < $end_a ) {
			$start  = strpos( $output, '<p>', $offseet_a );
			$end    = strpos( $output, '</p>', $start ) + 4;
			$length = $end - $start;
			$error  = substr( $output, $start, $length );
			$error  = str_replace( '<p>', '<span class="d-block form-text text-danger">', $error );
			$error  = str_replace( '</p>', '</span>', $error );

			$start  = $offseet_a;
			$end    = strpos( $output, '</div>', $start ) + 6;
			$length = $end - $start;
			$output = substr_replace( $output, '', $start, $length );
		}

		$offset += 38;

		if ( is_subdomain_install() ) {
	        $output = substr_replace( $output, '<div class="input-group">' . "\n" . '<div class="input-group-text">', $offset, 0 );
			$offset = strpos( $output, '<input ', $offset );
	        $output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
			$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
			$offset = strpos( $output, '/>', $offset );
	        // $output = substr_replace( $output, ' input-group-text', $offset + 27, 0 );
			$output = substr_replace( $output, '<div class="input-group-text">' . "\n", $offset + 2, 0 );
			$offset = strpos( $output, '/', $offset + 2 );
	        $output = substr_replace( $output, "\n" . '</div>' . "\n" . $error . "\n" . '</div>' . "\n" . '</div>' . "\n" . '</div>', $offset + 1, 0 );
		} else {
			// $offset = strpos( $output, '<span class="prefix_address">', $offset );
	        // $output = substr_replace( $output, ' input-group-text', $offset, 0 );
	        $output = substr_replace( $output, '<div class="input-group">' . "\n" . '<div class="input-group-text">', $offset, 0 );
			$offset = strpos( $output, '<input ', $offset );
	        $output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
	        $output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
			$offset = strpos( $output, '/>', $offset );
	        $output = substr_replace( $output, "\n" . '</div>' . $error . "\n" . '</div>' . "\n" . '</div>', $offset + 2, 0 );
		}

		// $offset = strpos( $output, '<br />', $offset );
        // $output = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset, 6 );
    }

	$offset = strpos( $output, '<label for="signup_blog_title">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="col-form-label"', $offset + 6, 0 );
        $output = substr_replace( $output, '<div class="mb-3 row">' . "\n" . '<div class="col-sm-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="col-sm-9">', $offset + 8, 0 );

		$error     = '';
		$offseet_a = strpos( $output, '<div class="bp-messages bp-feedback error">', $offset );
		$end_a     = strpos( $output, '<input ', $offset );
		if ( false !== $offseet_a && $offseet_a < $end_a ) {
			$start  = strpos( $output, '<p>', $offseet_a );
			$end    = strpos( $output, '</p>', $start ) + 4;
			$length = $end - $start;
			$error  = substr( $output, $start, $length );
			$error  = str_replace( '<p>', '<span class="d-block form-text text-danger">', $error );
			$error  = str_replace( '</p>', '</span>', $error );

			$start  = $offseet_a;
			$end    = strpos( $output, '</div>', $start ) + 6;
			$length = $end - $start;
			$output = substr_replace( $output, '', $start, $length );
		}

		$offset = strpos( $output, '<input ', $offset );
        $output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
		$offset = strpos( $output, '/>', $offset );
        $output = substr_replace( $output, "\n" . $error . "\n" . '</div>' . "\n" . '</div>', $offset + 2, 0 );
	}

	$offset = strpos( $output, '<span class="label">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
		$offset = strpos( $output, '<label for="signup_blog_privacy">', $offset );
		$output = substr_replace( $output, '<div class="form-check">', $offset, 33 );
		$offset = strpos( $output, 'id="signup_blog_privacy_public"', $offset );
		$output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, ' <label class="form-check-label" for="signup_blog_privacy_public">', $offset + 2, 0 );
		// $offset = strpos( $output, '</strong>', $offset );
		// $output = substr_replace( $output, '', $offset, 9 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 8, 0 );
		$offset = strpos( $output, '<label for="signup_blog_privacy">', $offset );
		$output = substr_replace( $output, '<div class="form-check">', $offset, 33 );
		$offset = strpos( $output, 'id="signup_blog_privacy_private"', $offset );
		$output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, '/>', $offset );
		$output = substr_replace( $output, '<label class="form-check-label" for="signup_blog_privacy_private">', $offset + 2, 0 );
		// $offset = strpos( $output, '</strong>', $offset );
		// $output = substr_replace( $output, '', $offset, 9 );
		$offset = strpos( $output, '</label>', $offset );
		$output = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset + 8, 0 );
		// $offset = strpos( $output, '</fieldset>', $offset );
		// $output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, 'class="privacy-policy-accept"' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, ' form-check mb-3', $offset + 28, 0 );
		$offset = strpos( $output, '<label for="signup-privacy-policy-accept">', $offset );
		$output = substr_replace( $output, '', $offset, 42 );
		$offset = strpos( $output, 'id="signup-privacy-policy-accept"', $offset );
		$output = substr_replace( $output, 'class="form-check-input" ', $offset, 0 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, ' <label for="signup-privacy-policy-accept" class="form-check-label">', $offset + 1, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_register_form', 'enlightenment_bp_bootstrap_register_form' );

function enlightenment_bp_bootstrap_activate_page( $output ) {
	$offset = strpos( $output, 'class="bp-feedback bp-messages bp-template-notice error"' );
    if ( false !== $offset ) {
        $output = substr_replace( $output, 'alert alert-danger d-flex ', $offset + 7, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, 'fas fa-exclamation-circle mt-1 me-2 ', $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    }

	$offset = strpos( $output, '<label for="key">' );
    if ( false !== $offset ) {
		$output = substr_replace( $output, ' class="col-sm-2 col-form-label"', $offset + 6, 0 );
		$output = substr_replace( $output, '<div class="mb-3 row">', $offset, 0 );
		$offset = strpos( $output, '</label>', $offset );
        $output = substr_replace( $output, "\n" . '<div class="col-sm-10">', $offset + 8, 0 );
		$offset = strpos( $output, '<input ', $offset );
        $output = substr_replace( $output, ' class="form-control"', $offset + 6, 0 );
		$offset = strpos( $output, '/>', $offset );
        $output = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset + 2, 0 );
	}

	if ( bp_account_was_activated() ) {
		$output = str_replace( '<p><a ', '<a class="btn btn-primary btn-lg" ', $output );
		$output = str_replace( '</a></p>', '</a>', $output );
		$output = str_replace( '<p>', '<p class="alert alert-success">', $output );
	} else {
		$output = str_replace( '<p>', '<p class="alert alert-info">', $output );
	}

	$output = str_replace( 'class="submit"', 'class="submit mb-0"', $output );
	$output = str_replace( 'type="submit"', 'class="btn btn-primary btn-lg" type="submit"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_activate_page', 'enlightenment_bp_bootstrap_activate_page' );

function enlightenment_bp_bootstrap_member_group_block( $output ) {
	$output = str_replace( 'class="button large primary button-primary wp-block-button__link wp-element-button"', 'class="button large primary button-primary btn btn-primary"', $output );
	$output = str_replace( 'class="button large primary button-primary"', 'class="button large primary button-primary btn btn-primary"', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_bp_member', 'enlightenment_bp_bootstrap_member_group_block' );
add_filter( 'enlightenment_render_block_bp_group', 'enlightenment_bp_bootstrap_member_group_block' );

function enlightenment_bp_bootstrap_latest_activities_block( $output ) {
	if ( false !== strpos( $output, 'class="widget-error"' ) ) {
		$output = str_replace( 'class="widget-error"', 'class="widget-error border-0 shadow-none"', $output );
		$output = str_replace( 'class="bp-feedback bp-messages info"', 'class="bp-feedback bp-messages info alert alert-info d-flex align-items-baseline"', $output );
		$output = str_replace( 'class="bp-icon"', 'class="fas fa-info-circle me-2"', $output );
		$output = str_replace( '<p>', '<p class="px-0 mb-0">', $output );
	}

	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_bp_latest_activities', 'enlightenment_bp_bootstrap_latest_activities_block' );

function enlightenment_bp_bootstrap_bp_sitewide_notices_block( $output ) {
	$output = str_replace( 'class="bp-sitewide-notice-message info bp-notice"', 'class="bp-sitewide-notice-message info bp-notice alert alert-info alert-dismissible fade show mb-0"', $output );
	$output = str_replace( '<strong>', '<strong class="alert-heading d-block h4">', $output );
	$output = str_replace( 'class="bp-tooltip button dismiss-notice"', 'class="bp-tooltip button dismiss-notice btn-close" data-bs-dismiss="alert"', $output );
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="', $output );
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );
	$output = str_replace( ' <span aria-hidden="true">&#x2716;</span>', '', $output );

	return $output;
}
add_filter( 'enlightenment_render_block_bp_sitewide_notices', 'enlightenment_bp_bootstrap_bp_sitewide_notices_block' );

function enlightenment_bp_bootstrap_bp_before_login_widget_loggedin() {
	if ( ! enlightenment_has_in_call_stack( 'bp_block_render_login_form_block' ) ) {
		return;
	}

	echo enlightenment_open_tag( 'div', 'd-flex align-items-center' );
}
add_action( 'bp_before_login_widget_loggedin', 'enlightenment_bp_bootstrap_bp_before_login_widget_loggedin', 999 );

function enlightenment_bp_bootstrap_bp_after_login_widget_loggedin() {
	if ( ! enlightenment_has_in_call_stack( 'bp_block_render_login_form_block' ) ) {
		return;
	}

	echo enlightenment_close_div();
}
add_action( 'bp_after_login_widget_loggedin', 'enlightenment_bp_bootstrap_bp_after_login_widget_loggedin', 1 );

function enlightenment_bp_bootstrap_bp_login_form_block( $output ) {
	if ( is_user_logged_in() ) {
		$output = str_replace( 'class="bp-login-widget-user-avatar"', 'class="bp-login-widget-user-avatar flex-grow-0 flex-shrink-1 me-3"', $output );
		$output = str_replace( 'class="bp-login-widget-user-links"', 'class="bp-login-widget-user-links flex-grow-1 flex-shrink-0"', $output );
		$output = str_replace( 'class="logout"', 'class="logout btn btn-secondary btn-sm"', $output );
	} else {
		$output = str_replace( 'class="login-username"', 'class="login-username mb-3"', $output );
		$output = str_replace( 'class="login-password"', 'class="login-password mb-3"', $output );
		$output = str_replace( 'class="login-remember"', 'class="login-remember mb-3 form-check"', $output );
		$output = str_replace( 'class="bp-login-widget-pwd-link"', 'class="bp-login-widget-pwd-link mb-0"', $output );

		$mb = false !== strpos( $output, 'class="bp-login-widget-pwd-link ' ) ? 3 : 0;

		$offset = strpos( $output, '<p class="bp-login-widget-register-link">' );
		if ( false !== $offset ) {
			$offset = strpos( $output, '<p class="login-submit">' );
			$output = substr_replace( $output, ' mb-0', $offset + 22, 0 );
			$output = substr_replace( $output, sprintf( '<div class="d-flex flex-wrap gap-3 mb-%d">', $mb ), $offset, 0 );
			$offset = strpos( $output, 'class="wp-block-button__link wp-element-button"', $offset );
			$output = substr_replace( $output, 'btn btn-primary btn-lg', $offset + 7, 39 );
			$offset = strpos( $output, '<p class="bp-login-widget-register-link">', $offset );
			$output = substr_replace( $output, ' mb-0', $offset + 39, 0 );
			$offset = strpos( $output, 'class="wp-block-button__link wp-element-button"', $offset );
			$output = substr_replace( $output, 'btn btn-secondary btn-lg', $offset + 7, 39 );
			$offset = strpos( $output, '</p>', $offset );
			$output = substr_replace( $output, '</div>', $offset + 4, 0 );
		} else {
			$output = str_replace( 'class="login-submit"', sprintf( 'class="login-submit mb-%d"', $mb ), $output );
			$output = str_replace( 'class="wp-block-button__link wp-element-button"', 'class="btn btn-primary btn-lg"', $output );
		}

		$output = str_replace( 'for="bp-login-widget-user-login"', 'for="bp-login-widget-user-login" class="form-label"', $output );
		$output = str_replace( 'for="bp-login-widget-user-pass"', 'for="bp-login-widget-user-pass" class="form-label"', $output );
		$output = str_replace( 'class="input"', 'class="input form-control"', $output );
		$output = str_replace( 'id="bp-login-widget-rememberme"', 'id="bp-login-widget-rememberme" class="form-check-input"', $output );
		$output = str_replace( 'class="button button-primary"', 'class="button button-primary btn btn-primary btn-lg"', $output );

		$offset = strpos( $output, 'class="login-remember ' );
		if ( false !== $offset ) {
			$offset = strpos( $output, '<label', $offset );
			$close  = strpos( $output, '>', $offset ) + 1;
			$length = $close - $offset;
			$tag    = substr( $output, $offset, $length );
			$output = substr_replace( $output, '', $offset, $length );
			$tag    = str_replace( '<label>', '<label for="bp-login-widget-rememberme" class="form-check-label">', $tag );
			$offset = strpos( $output, 'id="bp-login-widget-rememberme"', $offset );
			$offset = strpos( $output, '/> ', $offset );
			$output = substr_replace( $output, "\n" . $tag, $offset + 3, 0 );
		}
	}

	return $output;
}
add_filter( 'enlightenment_render_block_bp_login_form', 'enlightenment_bp_bootstrap_bp_login_form_block' );

function enlightenment_bp_bootstrap_block_type_metadata( $metadata ) {
	switch ( $metadata['name'] ) {
		case 'bp/login-form':
		case 'bp/primary-nav':
		case 'bp/dynamic-members':
		case 'bp/friends':
		case 'bp/dynamic-groups':
		case 'bp/sitewide-notices':
			unset( $metadata['style'] );
			break;
	}

	return $metadata;
}
add_filter( 'block_type_metadata', 'enlightenment_bp_bootstrap_block_type_metadata' );

function enlightenment_bp_bootstrap_widget_error( $output ) {
	$offset = strpos( $output, 'class="bp-feedback bp-messages ' );
    if ( false !== $offset ) {
		$start  = $offset + 31;
		$end    = strpos( $output, '"', $start );
		$length = $end - $start;
		$type   = substr( $output, $start, $length );

		switch ( $type ) {
			case 'success':
				$type = 'success';
				$icon = 'check';
				break;

			case 'warning':
				$type = 'warning';
				$icon = 'exclamation';
				break;

			case 'error':
				$type = 'danger';
				$icon = 'exclamation';
				break;

			case 'info':
			default:
				$type = 'info';
				$icon = 'info';
				break;
		}

        $output = substr_replace( $output, sprintf( ' alert alert-%s d-flex', $type ), $offset + 30, 0 );
		$offset = strpos( $output, 'class="bp-icon"', $offset );
        $output = substr_replace( $output, sprintf( 'fas fa-%s-circle mt-1 me-2 ', $icon ), $offset + 7, 0 );
		$offset = strpos( $output, '<p>', $offset );
        $output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
    } else {
		$output = str_replace( 'class="widget-error"', 'class="widget-error alert alert-danger"', $output );
	}

	return $output;
}
add_filter( 'enlightenment_widget', 'enlightenment_bp_bootstrap_widget_error' );

function enlightenment_bp_bootstrap_primary_nav_widget( $output ) {
	$output = str_replace( 'class="bp-priority-object-nav-nav-items"', 'class="bp-priority-object-nav-nav-items nav"', $output );
	$output = str_replace( 'class="bp-personal-tab ', 'class="bp-personal-tab nav-item ', $output );
	$output = str_replace( 'class="bp-personal-tab"', 'class="bp-personal-tab nav-item"', $output );
	$output = str_replace( '<a ', '<a class="nav-link" ', $output );
	$output = str_replace( 'class="count"', 'class="count badge rounded-pill text-bg-secondary"', $output );

	$offset = strpos( $output, 'class="bp-personal-tab nav-item current ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<a class="nav-link" ', $offset );
		$output = substr_replace( $output, ' active', $offset + 18, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_widget_bp_nouveau_sidebar_object_nav_widget', 'enlightenment_bp_bootstrap_primary_nav_widget' );

function enlightenment_bp_bootstrap_latest_activities_widget( $output ) {
	$output = str_replace( '<footer>', '<footer class="d-flex align-items-center">', $output );
	$output = str_replace( '<cite>', '<cite class="me-2">', $output );
	$output = str_replace( 'class="bp-tooltip"', 'class="bp-tooltip d-block"', $output );
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );

	return $output;
}
add_filter( 'enlightenment_widget_bp_latest_activities', 'enlightenment_bp_bootstrap_latest_activities_widget' );
add_filter( 'enlightenment_widget_bp_classic_templates_nouveau_latest_activities', 'enlightenment_bp_bootstrap_latest_activities_widget' );

function enlightenment_bp_bootstrap_avatar_block_widget( $output ) {
	$output = str_replace( 'class="bp-tooltip"', 'class="bp-tooltip d-block"', $output );
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );

	return $output;
}
add_filter( 'enlightenment_widget_bp_core_recently_active_widget', 'enlightenment_bp_bootstrap_avatar_block_widget' );
add_filter( 'enlightenment_widget_bp_classic_members_recently_active_widget', 'enlightenment_bp_bootstrap_avatar_block_widget' );
add_filter( 'enlightenment_widget_bp_core_whos_online_widget', 'enlightenment_bp_bootstrap_avatar_block_widget' );
add_filter( 'enlightenment_widget_bp_classic_members_whos_online_widget', 'enlightenment_bp_bootstrap_avatar_block_widget' );

function enlightenment_bp_bootstrap_login_widget_loggedin( $output ) {
	if ( enlightenment_has_in_call_stack( 'bp_block_render_login_form_block' ) ) {
		return $output;
	}

	return sprintf( '<div class="d-flex align-items-center">%s</div>', $output );
}
add_filter( 'enlightenment_bp_filter_login_widget_loggedin', 'enlightenment_bp_bootstrap_login_widget_loggedin' );

function enlightenment_bp_bootstrap_login_widget( $output ) {
	if ( is_user_logged_in() ) {
		$output = str_replace( 'class="bp-login-widget-user-avatar"', 'class="bp-login-widget-user-avatar flex-grow-0 flex-shrink-1 me-3"', $output );
		$output = str_replace( 'class="bp-login-widget-user-links"', 'class="bp-login-widget-user-links flex-grow-1 flex-shrink-0 ms-0"', $output );
		$output = str_replace( 'class="logout"', 'class="logout btn btn-secondary btn-sm"', $output );
		// $output = sprintf( '<div class="d-flex align-items-center">%s</div>', $output );
	} else {
		$offset = strpos( $output, '<label for="bp-login-widget-rememberme">' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, '', $offset, 40 );
			$offset = strpos( $output, ' /> ', $offset );
			$output = substr_replace( $output, '<label for="bp-login-widget-rememberme">', $offset + 4, 0 );
		}

		$output = str_replace( 'class="forgetmenot"', 'class="forgetmenot mb-3 form-check"', $output );
		$output = str_replace( 'for="bp-login-widget-rememberme"', 'for="bp-login-widget-rememberme" class="form-check-label"', $output );
		$output = str_replace( 'id="bp-login-widget-rememberme"', 'id="bp-login-widget-rememberme" class="form-check-input"', $output );
		$output = str_replace( 'id="bp-login-widget-submit"', 'id="bp-login-widget-submit" class="btn btn-secondary btn-lg"', $output );
		$output = str_replace( 'class="bp-login-widget-register-link"', 'class="bp-login-widget-register-link ms-3"', $output );

		$offset = strpos( $output, '<label for="bp-login-widget-user-login">' );
		if ( false !== $offset ) {
			$output = substr_replace( $output, ' class="form-label"', $offset + 6, 0 );
			$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
			$offset = strpos( $output, 'class="input"', $offset );
			$output = substr_replace( $output, ' form-control', $offset + 12, 0 );
			$offset = strpos( $output, ' />', $offset );
			$output = substr_replace( $output, "\n" . '</div>', $offset + 3, 0 );
			$offset = strpos( $output, '<label for="bp-login-widget-user-pass">', $offset );
			$output = substr_replace( $output, ' class="form-label"', $offset + 6, 0 );
			$output = substr_replace( $output, '<div class="mb-3">' . "\n", $offset, 0 );
			$offset = strpos( $output, 'class="input"', $offset );
			$output = substr_replace( $output, ' form-control', $offset + 12, 0 );
			$offset = strpos( $output, ' />', $offset );
			$output = substr_replace( $output, "\n" . '</div>', $offset + 3, 0 );

			if ( bp_get_signup_allowed() ) {
				$offset = strpos( $output, '<input type="submit"', $offset );
				$output = substr_replace( $output, '<div class="d-flex align-items-center">' . "\n", $offset, 0 );
				$offset = strpos( $output, '</span>', $offset );
				$output = substr_replace( $output, "\n" . '</div>', $offset + 7, 0 );
			}
		}
	}

	return $output;
}
add_filter( 'enlightenment_widget_bp_core_login_widget', 'enlightenment_bp_bootstrap_login_widget' );
add_filter( 'enlightenment_widget_bp_classic_core_login_widget', 'enlightenment_bp_bootstrap_login_widget' );

function enlightenment_bp_bootstrap_bbp_login_form( $output ) {
	$output = str_replace( 'id="user_login"', sprintf( 'id="user_login" placeholder="%s"', __( 'Username', 'enlightenment' ) ), $output );
	$output = str_replace( 'id="user_pass"', sprintf( 'id="user_pass" placeholder="%s"', __( 'Password', 'enlightenment' ) ), $output );
	$output = str_replace( 'class="button submit user-submit"', 'class="button submit user-submit btn btn-secondary"', $output );

	return $output;
}
add_filter( 'enlightenment_bbp_login_form', 'enlightenment_bp_bootstrap_bbp_login_form' );

function enlightenment_bp_bootstrap_sitewide_notices_args( $args ) {
	$args['container_class'] .= ' fixed-bottom';

	return $args;
}
add_filter( 'enlightenment_bp_sitewide_notices_args', 'enlightenment_bp_bootstrap_sitewide_notices_args' );

function enlightenment_bp_bootstrap_sitewide_notices( $output ) {
	$output = str_replace( 'class="info notice"', 'class="info notice toast fade m-4" role="alert" aria-live="assertive" aria-atomic="true"', $output );
	$output = str_replace( 'class="bp-tooltip button"', 'class="bp-tooltip button btn-close ms-auto" data-bs-dismiss="toast"', $output );
	$output = str_replace( 'class="bp-tooltip"', 'class="bp-tooltip btn-close ms-auto" data-bs-dismiss="toast"', $output );
	$output = str_replace( 'data-bp-tooltip="', 'data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="', $output );
	$output = str_replace( 'class="bp-screen-reader-text"', 'class="bp-screen-reader-text visually-hidden"', $output );
	$output = str_replace( '<span aria-hidden="true">&Chi;</span>', '', $output );

	$offset = strpos( $output, 'class="info notice ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<strong>', $offset );
		$output = substr_replace( $output, '<div class="toast-header">' . "\n", $offset, 0 );

		/**
		 * Get tag of dismiss button
		 *
		 * Before 9.0.0 it used to be a button tag and then it changed to a tag.
		 */
		$offset = strpos( $output, 'id="close-notice"', $offset );
		$start  = strrpos( $output, '<', $offset - strlen( $output ) ) + 1;
		$end    = strpos( $output, ' ', $start );
		$length = $end - $start;
		$tag    = substr( $output, $start, $length );

		$offset = strpos( $output, sprintf( '</%s>', $tag ), $offset );
		$output = substr_replace( $output, "\n" . '</div>' . "\n" . '<div class="toast-body">', $offset + strlen( $tag ) + 3, 0 );
		$offset = strpos( $output, '<input ', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_sitewide_notices', 'enlightenment_bp_bootstrap_sitewide_notices' );

function enlightenment_bp_bootstrap_option_rtmedia_options( $value ) {
	$value['styles_enabled'] = 0;

	return $value;
}
add_filter( 'option_rtmedia-options', 'enlightenment_bp_bootstrap_option_rtmedia_options' );
add_filter( 'site_option_rtmedia-options', 'enlightenment_bp_bootstrap_option_rtmedia_options' );

function enlightenment_bp_bootstrap_rtmedia_options() {
	global $rtmedia;

	if ( ! isset( $rtmedia ) ) {
        return;
    }

	$rtmedia->options['styles_enabled'] = 0;
}
add_action( 'init', 'enlightenment_bp_bootstrap_rtmedia_options' );

function enlightenment_bp_bootstrap_rtmedia_settings_sub_tabs( $tabs, $tab ) {
	if ( 'rtmedia-settings' != $tab ) {
		return $tabs;
	}

	if ( ! class_exists( 'RTMediaFormHandler' ) ) {
		return $tabs;
	}

	$tabs[60]['callback'] = 'enlightenment_bp_bootstrap_rtmedia_custom_css_content';

	return $tabs;
}
add_filter( 'rtmedia_add_settings_sub_tabs', 'enlightenment_bp_bootstrap_rtmedia_settings_sub_tabs', 10, 2 );

function enlightenment_bp_bootstrap_rtmedia_custom_css_content() {
	global $rtmedia;
	$options     = RTMediaFormHandler::extract_settings( 'styles', $rtmedia->options );
	$render_data = RTMediaFormHandler::custom_css_render_options( $options );

	if ( isset( $render_data['disable_styles'] ) ) {
		unset( $render_data['disable_styles'] );
	}

	$render_groups     = array();
	$render_groups[10] = esc_html__( 'Custom CSS settings', 'enlightenment' );

	RTMediaFormHandler::render_tab_content( $render_data, $render_groups, 10 );
}

function enlightenment_bp_bootstrap_rtmedia_sub_nav( $output ) {
	$output = str_replace( 'class="subnav"', 'class="subnav list-unstyled btn-group mb-0"', $output );

	$offset = strpos( $output, '<li id="rtmedia-nav-item-' );
	while ( false !== $offset ) {
		$offset_a = strpos( $output, 'class="', $offset );
		$end_a    = strpos( $output, '>', $offset );
		if ( false !== $offset_a && $offset_a < $end_a ) {
			$output = substr_replace( $output, 'btn-group ', $offset_a + 7, 0 );
		} else {
			$output = substr_replace( $output, ' class="btn-group"', $offset + 3, 0 );
		}

		$offset = strpos( $output, '<a ', $offset );
		$output = substr_replace( $output, 'class="btn btn-outline-secondary" ', $offset + 3, 0 );

		$offset_b = strpos( $output, 'class="count"', $offset );
		$end_b    = strpos( $output, '</a>', $offset );
		if ( false !== $offset_b && $offset_b < $end_b ) {
			$offset_c = strpos( $output, '>0<', $offset_b );
			$end_c    = strpos( $output, '</span>', $offset_b );
			if ( false !== $offset_c && $offset_c < $end_c ) {
				$output = substr_replace( $output, '', $offset_c + 1, 1 );
				$output = substr_replace( $output, ' d-none', $offset_b + 12, 0 );
			} else {
				$output = substr_replace( $output, ' badge text-bg-light ms-2', $offset_b + 12, 0 );
			}
		}

		$offset = strpos( $output, '<li id="rtmedia-nav-item-', $offset );
	}

	$offset = strpos( $output, 'class="btn-group current selected"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '<a class="btn btn-outline-secondary"', $offset );
		$output = substr_replace( $output, '', $offset + 18, 8 ); //delete 'outline'
	}

	return $output;
}
add_filter( 'enlightenment_bp_rtmedia_sub_nav', 'enlightenment_bp_bootstrap_rtmedia_sub_nav' );

function enlightenment_bp_bootstrap_rtmedia_login_register_modal( $output ) {
	$offset = strpos( $output, 'class="rtmedia-popup mfp-hide rtm-modal"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' modal fade" tabindex="-1" aria-hidden="true"', $offset + 39, 1 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="modal-dialog">', $offset + 1, 0 );
		$offset = strpos( $output, 'id="rtm-modal-container"', $offset );
		$output = substr_replace( $output, 'class="modal-content" ', $offset, 0 );
		$offset = strpos( $output, '<h2 ', $offset );
		$output = substr_replace( $output, '<div class="modal-header">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="rtm-modal-title"', $offset );
		$output = substr_replace( $output, ' modal-title h5', $offset + 22, 0 );
		$offset = strpos( $output, '</h2>', $offset );
		$output = substr_replace( $output, sprintf( '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="%s"></button>', __( 'Close', 'enlightenment' ) ) . "\n" . '</div>' . "\n" . '<div class="modal-body">', $offset + 5, 0 );
		$offset = strpos( $output, '<p>', $offset );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '</div>' . "\n" . '</div>', $offset + 6, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/media-gallery', 'enlightenment_bp_bootstrap_rtmedia_login_register_modal' );
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/album-gallery', 'enlightenment_bp_bootstrap_rtmedia_login_register_modal' );

function enlightenment_bp_bootstrap_rtmedia_create_album_modal( $output ) {
	$output = str_replace( 'class="rtm-input-medium"', 'class="rtm-input-medium form-control"', $output );
	$output = str_replace( 'id="rtmedia_create_new_album"', 'id="rtmedia_create_new_album" class="btn btn-primary btn-lg"', $output );

	$offset = strpos( $output, 'class="mfp-hide rtmedia-popup"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' modal fade" tabindex="-1" aria-hidden="true"', $offset + 29, 1 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="modal-dialog">', $offset + 1, 0 );
		$offset = strpos( $output, 'id="rtm-modal-container"', $offset );
		$output = substr_replace( $output, 'class="modal-content" ', $offset, 0 );
		$offset = strpos( $output, '<h2 ', $offset );
		$output = substr_replace( $output, '<div class="modal-header">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="rtm-modal-title"', $offset );
		$output = substr_replace( $output, ' modal-title h5', $offset + 22, 0 );
		$offset = strpos( $output, '</h2>', $offset );
		$output = substr_replace( $output, sprintf( '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="%s"></button>', __( 'Close', 'enlightenment' ) ) . '</div>' . "\n" . '<div class="modal-body">', $offset + 5, 0 );
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="mb-3"', $offset + 2, 0 );
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="mb-3"', $offset + 2, 0 );
		$offset = strpos( $output, 'id="rtmedia_create_album_nonce"', $offset );
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, '</div>' . "\n" . '<div class="modal-footer">', $offset, 3 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 4 );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/media-gallery', 'enlightenment_bp_bootstrap_rtmedia_create_album_modal' );
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/album-gallery', 'enlightenment_bp_bootstrap_rtmedia_create_album_modal' );

function enlightenment_bp_bootstrap_rtmedia_merge_album_modal( $output ) {
	$output = str_replace( 'class="rtmedia-merge-user-album-list"', 'class="rtmedia-merge-user-album-list form-control"', $output );
	$output = str_replace( 'class="rtmedia-merge-selected"', 'class="rtmedia-merge-selected btn btn-primary btn-lg"', $output );

	$offset = strpos( $output, 'class="rtmedia-merge-container rtmedia-popup mfp-hide"' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, ' modal fade" tabindex="-1" aria-hidden="true"', $offset + 53, 1 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="modal-dialog">', $offset + 1, 0 );
		$offset = strpos( $output, 'id="rtm-modal-container"', $offset );
		$output = substr_replace( $output, 'class="modal-content" ', $offset, 0 );
		$offset = strpos( $output, '<h2 ', $offset );
		$output = substr_replace( $output, '<div class="modal-header">' . "\n", $offset, 0 );
		$offset = strpos( $output, 'class="rtm-modal-title"', $offset );
		$output = substr_replace( $output, ' modal-title h5', $offset + 22, 0 );
		$offset = strpos( $output, '</h2>', $offset );
		$output = substr_replace( $output, sprintf( '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="%s"></button>', __( 'Close', 'enlightenment' ) ) . "\n" . '</div>', $offset + 5, 0 );
		$offset = strpos( $output, 'class="album-merge-form"', $offset );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="modal-body">', $offset + 1, 0 );
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
		$offset = strpos( $output, '<input type="submit"', $offset );
		$output = substr_replace( $output, '</div>' . "\n" . '<div class="modal-footer">', $offset, 0 );
		$offset = strpos( $output, '</form>', $offset );
		$output = substr_replace( $output, '</div>', $offset, 0 );
		$offset = strpos( $output, '</form>', $offset );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/media-gallery', 'enlightenment_bp_bootstrap_rtmedia_merge_album_modal' );

function enlightenment_bp_bootstrap_rtmedia_media_gallery_header( $output ) {
	$output = str_replace( 'class="clearfix rtm-gallery-media-title-container"', 'class="rtm-gallery-media-title-container d-flex flex-column flex-md-row flex-md-wrap align-items-start align-items-md-center"', $output );
	$output = str_replace( 'id="rtm-gallery-title-container" class="clearfix"', 'id="rtm-gallery-title-container" class="d-flex flex-column flex-md-row flex-md-wrap align-items-start align-items-md-center"', $output );
	$output = str_replace( 'class="rtm-gallery-title"', 'class="rtm-gallery-title mb-md-0"', $output );
	$output = str_replace( 'class="gallery-description gallery-album-description"', 'class="gallery-description gallery-album-description order-1 w-100"', $output );
	$output = str_replace( 'class="rtm-media-options ', 'class="rtm-media-options btn-group ms-md-auto ', $output );
	$output = str_replace( 'class="rtm-media-options"', 'class="rtm-media-options btn-group ms-md-auto"', $output );
	$output = str_replace( 'class="click-nav rtm-media-options-list"', 'class="rtm-media-options-list btn-group"', $output );
	$output = str_replace( 'class="no-js"', 'class="no-js btn-group dropdown"', $output );
	$output = str_replace( 'class="clicker rtmedia-action-buttons"', 'class="clicker rtmedia-action-buttons btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"', $output );
	$output = str_replace( 'class="dashicons dashicons-admin-generic"', 'class="fas fa-cog me-1"', $output );
	$output = str_replace( 'class="rtm-options"', 'class="rtm-options dropdown-menu dropdown-menu-end"', $output );
	$output = str_replace( 'class="primary rtmedia-upload-media-link ', 'class="primary rtmedia-upload-media-link btn btn-secondary ', $output );
	$output = str_replace( 'class="primary rtmedia-upload-media-link"', 'class="primary rtmedia-upload-media-link btn btn-secondary"', $output );
	$output = str_replace( 'class="rtmedia-upload-media-link primary ', 'class="rtmedia-upload-media-link primary btn btn-secondary ', $output );
	$output = str_replace( 'class="rtmedia-upload-media-link primary"', 'class="rtmedia-upload-media-link primary btn btn-secondary"', $output );
	$output = str_replace( 'class="dashicons dashicons-upload"', 'class="fas fa-upload me-1"', $output );
	$output = str_replace( 'class="rtm-media-gallery-uploader"', 'class="rtm-media-gallery-uploader" style="display: none;"', $output );
	$output = str_replace( 'class="rtmedia-no-media-found"', 'class="rtmedia-no-media-found alert alert-info"', $output );

	$offset = strpos( $output, 'class="clicker rtmedia-action-buttons ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '</div>', $offset );
		$offset = strpos( $output, '</div>', $offset + 1 );
		$output = substr_replace( $output, '<i class="d-none"></i>', $offset + 6, 0 );
	}

	$options = apply_filters( 'rtmedia_gallery_actions', array() );
	if ( ! empty( $options ) ) {
		foreach ( $options as $option ) {
			$option   = str_replace( '  ',  ' ',  $option );
			$option   = str_replace( '" >', '">', $option );
			$option   = str_replace( "' >", "'>", $option );
			$filtered = $option;

			$start = strpos( $filtered, '<a ' );
			if ( false !== $start ) {
				$end    = strpos( $filtered, '</a>' );
				$offset = strpos( $filtered, "class='" );

				if ( false !== $offset && $offset > $start && $offset < $end ) {
					$filtered = substr_replace( $filtered, 'dropdown-item ', $offset + 7, 0 );
				} else {
					$offset = strpos( $filtered, 'class="' );

					if ( false !== $offset && $offset > $start && $offset < $end ) {
						$filtered = substr_replace( $filtered, 'dropdown-item ', $offset + 7, 0 );
					} else {
						$filtered = substr_replace( $filtered, ' class="dropdown-item"', $start + 2, 0 );
					}
				}
			} else {
				$start = strpos( $filtered, '<button ' );

				if ( false !== $start ) {
					$end    = strpos( $filtered, '</button>' );
					$offset = strpos( $filtered, "class='" );

					if ( false !== $offset && $offset > $start && $offset < $end ) {
						$filtered = substr_replace( $filtered, 'dropdown-item ', $offset + 7, 0 );
					} else {
						$offset = strpos( $filtered, 'class="' );

						if ( false !== $offset && $offset > $start && $offset < $end ) {
							$filtered = substr_replace( $filtered, 'dropdown-item ', $offset + 7, 0 );
						} else {
							$filtered = substr_replace( $filtered, ' class="dropdown-item"', $start + 7, 0 );
						}
					}
				}
			}

			$output = str_replace( $option, $filtered, $output );
		}
	}

	$output = str_replace( 'href="#rtmedia-login-register-modal"', 'href="#rtmedia-login-register-modal" data-bs-toggle="modal" data-bs-target="#rtmedia-login-register-modal"', $output );
	$output = str_replace( 'href="#rtmedia-merge"', 'href="#rtmedia-merge" data-bs-toggle="modal" data-bs-target="#rtmedia-merge"', $output );
	$output = str_replace( 'href="#rtmedia-create-album-modal"', 'href="#rtmedia-create-album-modal" data-bs-toggle="modal" data-bs-target="#rtmedia-create-album-modal"', $output );
	$output = str_replace( "href='#rtmedia-create-album-modal'", 'href="#rtmedia-create-album-modal" data-bs-toggle="modal" data-bs-target="#rtmedia-create-album-modal"', $output );
	$output = str_replace( ' rtmedia-modal-link"', '"', $output );
	$output = str_replace( " rtmedia-modal-link'", "'", $output );

	$output = str_replace( 'class="dashicons dashicons-edit"', 'class="fas fa-edit fa-fw me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-edit'", 'class="fas fa-edit fa-fw me-1"', $output );
	$output = str_replace( 'class="dashicons dashicons-trash"', 'class="fas fa-trash fa-fw me-1"', $output );
	$output = str_replace( 'class="dashicons dashicons-randomize"', 'class="fas fa-random fa-fw me-1"', $output );
	$output = str_replace( 'class="dashicons dashicons-plus-alt"', 'class="fas fa-plus-circle fa-fw me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-plus-alt'", 'class="fas fa-plus-circle fa-fw me-1"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/media-gallery', 'enlightenment_bp_bootstrap_rtmedia_media_gallery_header' );
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/album-gallery', 'enlightenment_bp_bootstrap_rtmedia_media_gallery_header' );

function enlightenment_bp_bootstrap_rtmedia_gallery_search( $output ) {
	// close #rtm-media-options early and open new container
	// that will be closed by #rtm-media-options original closing tag
	$output = sprintf( '</div>%s<div class="w-100 mt-3">%s', "\n", $output );

	$output = str_replace( 'class="media_search"', 'class="media_search input-group"', $output );
	$output = str_replace( "class='media_search'", 'class="media_search input-group"', $output );
	$output = str_replace( 'class="media_search_input"', 'class="media_search_input form-control"', $output );
	$output = str_replace( 'class="search_by"', 'class="search_by form-control"', $output );
	$output = str_replace( "class='search_by'", 'class="search_by form-control"', $output );
	$output = str_replace( '<span id="media_search_remove" class="media_search_remove search_option"><i class="dashicons dashicons-no"></i></span>', '<button id="media_search_remove" class="media_search_remove search_option btn btn-light rounded-0"><i class="fas fa-times"></i></button>', $output );
	$output = str_replace( "<span id='media_search_remove' class='media_search_remove search_option'><i class='dashicons dashicons-no'></i></span>", '<button id="media_search_remove" class="media_search_remove search_option btn btn-light rounded-0"><i class="fas fa-times"></i></button>', $output );
	$output = str_replace( '<button type="submit" id="media_search" class="search_option"><i class="dashicons dashicons-search"></i></button>', '<button type="submit" id="media_search" class="search_option btn btn-light"><i class="fas fa-search"></i></button>', $output );
	$output = str_replace( "<button type='submit' id='media_search' class='search_option'><i class='dashicons dashicons-search'></i></button>", '<button type="submit" id="media_search" class="search_option btn btn-light"><i class="fas fa-search"></i></button>', $output );

	return $output;
}
add_filter( 'rtmedia_gallery_search', 'enlightenment_bp_bootstrap_rtmedia_gallery_search' );

function enlightenment_bp_bootstrap_rtmedia_media_gallery_uploader( $output ) {
	return str_replace( 'class="rtmedia-upload-not-allowed"', 'class="rtmedia-upload-not-allowed alert alert-danger"', $output );
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/media-gallery', 'enlightenment_bp_bootstrap_rtmedia_media_gallery_uploader' );
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/album-gallery', 'enlightenment_bp_bootstrap_rtmedia_media_gallery_uploader' );

function enlightenment_bp_bootstrap_rtmedia_media_gallery_pagination( $output ) {
	global $paged;
	$output = str_replace( 'class="rtm-pagination clearfix"', 'class="rtm-pagination d-flex align-items-sm-center"', $output );
	$output = str_replace( 'class="rtmedia-page-no rtm-page-number"', 'class="rtmedia-page-no rtm-page-number d-flex align-items-center ms-auto"', $output );
	$output = str_replace( 'class="rtm-label"', 'class="rtm-label d-none d-sm-block text-nowrap"', $output );
	$output = str_replace( 'class="rtm-go-to-num"', 'class="rtm-go-to-num form-control text-center mx-1"', $output );
	$output = str_replace( 'class="rtmedia-page-link button"', 'class="rtmedia-page-link button btn btn-secondary"', $output );

	$start = strpos( $output, '<div class="rtm-paginate">' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<span ', $start );
		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, '<li class="page-item">', $offset, 0 );
			$offset = strpos( $output, '<span ', $offset );
			$offset = strpos( $output, 'class="', $offset );
			$output = substr_replace( $output, 'page-link ', $offset + 7, 0 );
			$offset = strpos( $output, '</span>', $offset );
			$output = substr_replace( $output, '</li>', $offset + 7, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<span ', $offset );
		}

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<span>', $start );
		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="page-link"', $offset + 5, 0 );
			$output = substr_replace( $output, '<li class="page-item">', $offset, 0 );
			$offset = strpos( $output, '</span>', $offset );
			$output = substr_replace( $output, '</li>', $offset + 7, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<span>', $offset );
		}

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<a ', $start );
		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, '<li class="page-item">', $offset, 0 );
			$offset = strpos( $output, '<a ', $offset );
			$offset = strpos( $output, 'class="', $offset );
			$output = substr_replace( $output, 'page-link ', $offset + 7, 0 );
			$offset = strpos( $output, '</a>', $offset );
			$output = substr_replace( $output, '</li>', $offset + 4, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<a ', $offset );
		}

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<li class="page-item">', $start );
		while ( false !== $offset && $offset < $end ) {
			$offset_a = strpos( $output, 'class="page-link current"', $offset );
			$end_a    = strpos( $output, '</li>', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output = substr_replace( $output, ' active', $offset + 20, 0 );
				break;
			}

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li class="page-item">', $offset + 1 );
		}

		if ( ! empty( $paged ) ) {
			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, sprintf( 'data-page="%d"', $paged ), $start );

			if ( false !== $offset && $offset < $end ) {
				$offset_a = strrpos( $output, 'class="page-link ', $offset - strlen( $output ) );
				$end_a    = strpos( $output, '>', $offset );

				if ( false !== $offset_a && $offset_a < $end_a ) {
					$output = substr_replace( $output, ' active', $offset_a + 16, 0 );
				}
			}
		}
	}

	$output = str_replace( 'class="rtm-paginate"', 'class="rtm-paginate order-first pagination"', $output );
	$output = str_replace( 'class="dashicons dashicons-arrow-left-alt2"', 'class="fas fa-arrow-left"', $output );
	$output = str_replace( "class='dashicons dashicons-arrow-left-alt2'", 'class="fas fa-arrow-left"', $output );
	$output = str_replace( 'class="dashicons dashicons-arrow-right-alt2"', 'class="fas fa-arrow-right"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/media-gallery', 'enlightenment_bp_bootstrap_rtmedia_media_gallery_pagination' );
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/album-gallery', 'enlightenment_bp_bootstrap_rtmedia_media_gallery_pagination' );

function enlightenment_bp_bootstrap_template_rtmedia_media_media_gallery( $output ) {
	$output = str_replace( 'class="rtmedia-list rtmedia-list-media ', 'class="rtmedia-list rtmedia-list-media row list-unstyled ', $output );
	$output = str_replace( 'class="rtmedia-list-item"', 'class="rtmedia-list-item col-6 col-md-3 d-flex flex-column"', $output );
	$output = str_replace( 'class="rtmedia-gallery-item-actions"', 'class="rtmedia-gallery-item-actions d-flex"', $output );
	$output = str_replace( "class='rtmedia-gallery-item-actions'", 'class="rtmedia-gallery-item-actions d-flex"', $output );
	$output = str_replace( 'class="dashicons dashicons-edit"', 'class="far fa-edit me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-edit'", 'class="far fa-edit me-1"', $output );
	$output = str_replace( 'class="no-popup rtm-delete-media"', 'class="no-popup rtm-delete-media ms-auto"', $output );
	$output = str_replace( 'class="dashicons dashicons-trash"', 'class="far fa-trash-alt me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-trash'", 'class="far fa-trash-alt me-1"', $output );
	$output = str_replace( 'class="rtmedia-list-item-a"', 'class="rtmedia-list-item-a order-first"', $output );
	$output = str_replace( 'class="rtmedia-item-title hide"', 'class="rtmedia-item-title hide d-none"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/media-gallery', 'enlightenment_bp_bootstrap_template_rtmedia_media_media_gallery' );

function enlightenment_bp_bootstrap_template_rtmedia_media_album_gallery( $output ) {
	$output = str_replace( 'class="rtmedia-list-media rtmedia-list ', 'class="rtmedia-list-media rtmedia-list row list-unstyled ', $output );
	$output = str_replace( 'class="rtmedia-list-item"', 'class="rtmedia-list-item col-6 col-md-4 d-flex flex-column"', $output );
	$output = str_replace( 'class="rtmedia-gallery-item-actions"', 'class="rtmedia-gallery-item-actions d-flex"', $output );
	$output = str_replace( "class='rtmedia-gallery-item-actions'", 'class="rtmedia-gallery-item-actions d-flex"', $output );
	$output = str_replace( 'class="dashicons dashicons-edit"', 'class="far fa-edit me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-edit'", 'class="far fa-edit me-1"', $output );
	$output = str_replace( 'class="no-popup rtm-delete-media"', 'class="no-popup rtm-delete-media ms-auto"', $output );
	$output = str_replace( 'class="dashicons dashicons-trash"', 'class="far fa-trash-alt me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-trash'", 'class="far fa-trash-alt me-1"', $output );
	$output = str_replace( 'class="rtmedia-list-item-a"', 'class="rtmedia-list-item-a order-first"', $output );
	$output = str_replace( 'class="rtmedia-item-title hide"', 'class="rtmedia-item-title hide d-none"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/album-gallery', 'enlightenment_bp_bootstrap_template_rtmedia_media_album_gallery' );

function enlightenment_bp_bootstrap_template_rtmedia_media_media_gallery_item( $output ) {
	$output = str_replace( 'class="rtmedia-list-item"', 'class="rtmedia-list-item col-lg-3 d-flex flex-column"', $output );
	$output = str_replace( 'class="rtmedia-gallery-item-actions"', 'class="rtmedia-gallery-item-actions d-flex"', $output );
	$output = str_replace( "class='rtmedia-gallery-item-actions'", 'class="rtmedia-gallery-item-actions d-flex"', $output );
	$output = str_replace( 'class="dashicons dashicons-edit"', 'class="far fa-edit me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-edit'", 'class="far fa-edit me-1"', $output );
	$output = str_replace( 'class="no-popup rtm-delete-media"', 'class="no-popup rtm-delete-media ms-auto"', $output );
	$output = str_replace( 'class="dashicons dashicons-trash"', 'class="far fa-trash-alt me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-trash'", 'class="far fa-trash-alt me-1"', $output );
	$output = str_replace( 'class="rtmedia-list-item-a"', 'class="rtmedia-list-item-a order-first"', $output );
	$output = str_replace( 'class="rtmedia-item-title hide"', 'class="rtmedia-item-title hide d-none"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/media-gallery-item', 'enlightenment_bp_bootstrap_template_rtmedia_media_media_gallery_item' );

function enlightenment_bp_bootstrap_template_rtmedia_media_album_gallery_item( $output ) {
	return str_replace( 'class="rtmedia-list-item"', 'class="rtmedia-list-item col-lg-4 d-flex flex-column"', $output );
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/album-gallery-item', 'enlightenment_bp_bootstrap_template_rtmedia_media_album_gallery_item' );

function enlightenment_bp_bootstrap_rtmedia_update_media_message_html( $output ) {
	$output = str_replace( "class='rtmedia-success media-edit-messge'", 'class="rtmedia-success media-edit-messge alert alert-success"', $output );
	$output = str_replace( 'class="rtmedia-success media-edit-messge"', 'class="rtmedia-success media-edit-messge alert alert-success"', $output );
	$output = str_replace( 'class="rtmedia-warning media-edit-messge"', 'class="rtmedia-warning media-edit-messge alert alert-danger"',  $output );

	return $output;
}
add_filter( 'rtmedia_update_media_message_html', 'enlightenment_bp_bootstrap_rtmedia_update_media_message_html' );

function enlightenment_bp_bootstrap_template_rtmedia_media_album_single_edit( $output ) {
	$start = strpos( $output, '<ul class="rtm-tabs ' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<li ', $start );
		while ( false !== $offset && $offset < $end ) {
			$offset_a = strpos( $output, 'class="', $offset );
			$end_a    = strpos( $output, '>', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output = substr_replace( $output, 'nav-item ', $offset_a + 7, 0 );
			} else {
				$output = substr_replace( $output, ' class="nav-item"', $offset + 3, 0 );
			}

			$offset = strpos( $output, '<a ', $offset );

			$offset_a = strpos( $output, 'class="', $offset );
			$end_a    = strpos( $output, '>', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output = substr_replace( $output, 'nav-link ', $offset_a + 7, 0 );
			} else {
				$offset_a = strpos( $output, "class='", $offset );
				$end_a    = strpos( $output, '>', $offset );
				if ( false !== $offset_a && $offset_a < $end_a ) {
					$output = substr_replace( $output, 'nav-link ', $offset_a + 7, 0 );
				} else {
					$output = substr_replace( $output, ' class="nav-link"', $offset + 3, 0 );
				}
			}

			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, ' data-bs-toggle="tab" role="tab"', $offset, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li ', $offset );
		}

		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<li class="nav-item active">', $start );
		if ( false !== $offset && $offset < $end ) {
			$offset = strpos( $output, 'class="nav-link"', $offset );
			$output = substr_replace( $output, ' active', $offset + 15, 0 );
		}
	}

	$output = str_replace( 'class="rtm-tabs clearfix"', 'class="rtm-tabs nav nav-tabs mb-3" role="tablist"', $output );
	$output = str_replace( 'class="dashicons dashicons-edit"', 'class="far fa-edit me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-edit'", 'class="far fa-edit me-1"', $output );
	$output = str_replace( 'class="dashicons dashicons-list-view"', 'class="fas fa-list-ul me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-list-view'", 'class="fas fa-list-ul me-1"', $output );

	$output = str_replace( 'class="rtm-tabs-content"', 'class="rtm-tabs-content tab-content"', $output );
	$output = str_replace( 'class="content"', 'class="content tab-pane fade" role="tabpanel"', $output );
	$output = str_replace( 'class="content active"', 'class="content active tab-pane fade show" role="tabpanel"', $output );

	$output = str_replace( 'class="rtmedia-edit-title rtm-field-wrap"', 'class="rtmedia-edit-title rtm-field-wrap mb-3"', $output );
	$output = str_replace( 'class="rtmedia-title-editor"', 'class="rtmedia-title-editor form-control"', $output );
	$output = str_replace( 'class="rtmedia-editor-description rtm-field-wrap"', 'class="rtmedia-editor-description rtm-field-wrap mb-3"', $output );
	$output = str_replace( 'class="rtmedia-desc-textarea"', 'class="rtmedia-desc-textarea form-control"', $output );
	$output = str_replace( "class='rtmedia-desc-textarea'", 'class="rtmedia-desc-textarea form-control"', $output );

	$output = str_replace( 'class="button rtmedia-move"', 'class="button rtmedia-move btn btn-secondary"', $output );
	$output = str_replace( 'class="button rtmedia-delete-selected"', 'class="button rtmedia-delete-selected btn btn-danger"', $output );
	$output = str_replace( 'class="rtmedia-move-selected"', 'class="rtmedia-move-selected btn btn-secondary"', $output );
	$output = str_replace( 'class="rtmedia-list ', 'class="rtmedia-list row list-unstyled ', $output );
	$output = str_replace( 'class="rtmedia-list-item"', 'class="rtmedia-list-item col-lg-3 d-flex flex-column"', $output );
	$output = str_replace( 'class="rtmedia-gallery-item-actions"', 'class="rtmedia-gallery-item-actions d-flex"', $output );
	$output = str_replace( "class='rtmedia-gallery-item-actions'", 'class="rtmedia-gallery-item-actions d-flex"', $output );
	$output = str_replace( 'class="dashicons dashicons-edit"', 'class="far fa-edit me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-edit'", 'class="far fa-edit me-1"', $output );
	$output = str_replace( 'class="no-popup rtm-delete-media"', 'class="no-popup rtm-delete-media ms-auto"', $output );
	$output = str_replace( 'class="dashicons dashicons-trash"', 'class="far fa-trash-alt me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-trash'", 'class="far fa-trash-alt me-1"', $output );
	$output = str_replace( 'class="rtmedia-list-item-a"', 'class="rtmedia-list-item-a order-first"', $output );
	$output = str_replace( 'class="rtmedia-item-title hide"', 'class="rtmedia-item-title hide d-none"', $output );

	$output = str_replace( 'class="rtmedia-save-album"', 'class="rtmedia-save-album btn btn-primary btn-lg"', $output );
	$output = str_replace( "class='rtmedia-save-album'", 'class="rtmedia-save-album btn btn-primary btn-lg"', $output );
	$output = str_replace( 'class="button rtm-button rtm-button-back"', 'class="button rtm-button rtm-button-back btn btn-secondary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/album-single-edit', 'enlightenment_bp_bootstrap_template_rtmedia_media_album_single_edit' );

function enlightenment_bp_bootstrap_template_rtmedia_media_media_single( $output ) {
	$output = str_replace( 'class="rtmedia-single-meta rtm-single-meta"', 'class="rtmedia-single-meta rtm-single-meta mt-3"', $output );

	$offset = strpos( $output, 'class="rtmedia-like rtmedia-action-buttons ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-heart" aria-hidden="true"></i> ', $offset + 1, 0 );
	}

	$offset = strpos( $output, "class='rtmedia-comment-link rtmedia-comments-link'" );
	if ( false === $offset ) {
		$offset = strpos( $output, 'class="rtmedia-comment-link rtmedia-comments-link"' );
	}
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-comment" aria-hidden="true"></i> ', $offset + 1, 0 );
	}

	$offset = strpos( $output, 'class="rtmedia-view-conversation"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-eye" aria-hidden="true"></i> ', $offset + 1, 0 );
	}

	$offset = strpos( $output, 'class="rtmedia-edit rtmedia-action-buttons button"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-edit" aria-hidden="true"></i> ', $offset + 1, 0 );
	}

	$offset = strpos( $output, 'class="rtmedia-delete-media rtmedia-action-buttons button"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-trash-alt" aria-hidden="true"></i> ', $offset + 1, 0 );
	}

	$output = str_replace( 'class="rtmedia-item-actions ', 'class="rtmedia-item-actions d-flex ', $output );

	$start = strpos( $output, '<div class="rtmedia-item-actions ' );
	if ( false !== $start ) {
		$end     = strpos( $output, '</div>', $start );
		$offset  = strpos( $output, '<form ', $start );
		$did_one = false;

		while ( false !== $offset && $offset < $end ) {
			if ( $did_one ) {
				$end_a    = strpos( $output, '>', $offset );
				$offset_a = strpos( $output, 'class="', $offset );

				// Avoid empty form tags
				if ( '</form>' != substr( $output, $end_a + 1, 7 ) ) {
					if ( false !== $offset_a && $offset_a < $end_a ) {
						$output = substr_replace( $output, 'ms-2 ', $offset_a + 7, 0 );
					} else {
						$offset_a = strpos( $output, "class='", $offset );

						if ( false !== $offset_a && $offset_a < $end_a ) {
							$output = substr_replace( $output, 'ms-2 ', $offset_a + 7, 0 );
						} else {
							$output = substr_replace( $output, ' class="ms-2"', $offset + 5, 0 );
						}
					}
				}
			}

			$end_a    = strpos( $output, '</form>', $offset );
			$offset_a = strpos( $output, 'type="submit"', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$end_b    = strpos( $output, '>', $offset_a );
				$offset_b = strpos( $output, 'class="', $offset_a );

				if ( false !== $offset_b && $offset_b < $end_b ) {
					$output = substr_replace( $output, 'btn btn-secondary btn-sm ', $offset_b + 7, 0 );
				} else {
					$end_b    = strpos( $output, '>', $offset_a );
					$offset_b = strpos( $output, "class='", $offset_a );

					if ( false !== $offset_b && $offset_b < $end_b ) {
						$output = substr_replace( $output, 'btn btn-secondary btn-sm ', $offset_b + 7, 0 );
					} else {
						$output = substr_replace( $output, ' class="btn btn-secondary btn-sm"', $offset_a + 13, 0 );
					}
				}
			}

			$end     = strpos( $output, '</div>', $start );
			$offset  = strpos( $output, '<form ', $offset + 1 );
			$did_one = true;
		}
	}

	$output = str_replace( 'class="rtmedia-actions-before-comments clearfix"', 'class="rtmedia-actions-before-comments d-flex"', $output );

	$start = strpos( $output, '<div class="rtmedia-actions-before-comments ' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<form ', $start );
		while ( false !== $offset && $offset < $end ) {
			$end_a    = strpos( $output, '</form>', $offset );
			$offset_a = strpos( $output, 'type="submit"', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$end_b    = strpos( $output, '>', $offset_a );
				$offset_b = strpos( $output, 'class="', $offset_a );

				if ( false !== $offset_b && $offset_b < $end_b ) {
					$output = substr_replace( $output, 'btn btn-link ', $offset_b + 7, 0 );
				} else {
					$end_b    = strpos( $output, '>', $offset_a );
					$offset_b = strpos( $output, "class='", $offset_a );

					if ( false !== $offset_b && $offset_b < $end_b ) {
						$output = substr_replace( $output, 'btn btn-link ', $offset_b + 7, 0 );
					} else {
						$output = substr_replace( $output, ' class="btn btn-link"', $offset_a + 13, 0 );
					}
				}
			}

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<form ', $offset + 1 );
		}

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<a ', $start );
		while ( false !== $offset && $offset < $end ) {
			$end_a    = strpos( $output, '>', $offset );
			$offset_a = strpos( $output, 'class="', $offset );

			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output = substr_replace( $output, 'btn btn-link ', $offset_a + 7, 0 );
			} else {
				$end_a    = strpos( $output, '>', $offset );
				$offset_a = strpos( $output, "class='", $offset );

				if ( false !== $offset_a && $offset_a < $end_a ) {
					$output = substr_replace( $output, 'btn btn-link ', $offset_a + 7, 0 );
				} else {
					$output = substr_replace( $output, ' class="btn btn-link"', $offset + 13, 0 );
				}
			}

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<form ', $offset + 1 );
		}
	}

	$output = str_replace( 'class="dashicons dashicons-thumbs-up"', 'class="fas fa-heart"', $output );
	$output = str_replace( 'class="rtm-comment-list"', 'class="rtm-comment-list list-unstyled mb-0"', $output );
	$output = str_replace( 'class="bp-suggestions ac-input ', 'class="bp-suggestions ac-input form-control w-100 ', $output );
	$output = str_replace( 'class="bp-suggestions ac-input"', 'class="bp-suggestions ac-input form-control w-100"', $output );
	$output = str_replace( 'class="rt_media_comment_submit"', 'class="rt_media_comment_submit btn btn-primary btn-sm ms-auto"', $output );
	$output = str_replace( 'class="rtmedia-container rtmedia-uploader-div"', 'class="rtmedia-container rtmedia-uploader-div w-100"', $output );
	$output = str_replace( 'class="rtmedia-comment-media-upload"', 'class="rtmedia-comment-media-upload btn btn-secondary btn-sm"', $output );

	$offset = strpos( $output, 'id="rt_media_comment_form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="d-flex flex-wrap">', $offset + 1, 0 );
		$offset = strpos( $output, '</form>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/media-single', 'enlightenment_bp_bootstrap_template_rtmedia_media_media_single' );

remove_action( 'rtmedia_before_media', 'rtmedia_content_before_media', 10 );

function enlightenment_bp_bootstrap_ajax_template_rtmedia_media_media_single( $output ) {
	$output = sprintf( '<span class="mfp-close d-none" aria-hidden="true"></span><div class="modal-header"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="%s"></button></div> %s', esc_attr__( 'Close', 'enlightenment' ), $output );

	$output = str_replace( 'class="rtmedia-container rtmedia-single-container"', 'class="rtmedia-container rtmedia-single-container modal-body"', $output );
	$output = str_replace( 'class="rtm-lightbox-container clearfix"', 'class="rtm-lightbox-container row"', $output );
	$output = str_replace( 'class="rtm-mfp-close mfp-close dashicons dashicons-no-alt"', 'class="rtm-mfp-close mfp-close fas fa-times"', $output );
	$output = str_replace( 'class="rtmedia-no-media-found"', 'class="rtmedia-no-media-found alert alert-info mb-0"', $output );


	$offset = strpos( $output, '<p class="rtmedia-no-media-found ' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="col-12 d-flex align-items-center justify-content-center">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
	}

	$output = str_replace( 'class="rtmedia-single-media rtm-single-media ', 'class="rtmedia-single-media rtm-single-media col-lg-8 ', $output );
	$output = str_replace( 'class="rtmedia-single-meta rtm-single-meta"', 'class="rtmedia-single-meta rtm-single-meta col-lg-4"', $output );

	$offset = strpos( $output, 'id="rtmedia-single-media-container"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="carousel">', $offset + 1, 0 );
		$offset = strpos( $output, 'class="rtmedia-media"', $offset );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 6, 0 );
	}

	$output = str_replace( 'class="rtmedia-media"', 'class="carousel-inner"', $output );
	$output = str_replace( 'class="mfp-arrow mfp-arrow-left ', 'class="mfp-arrow mfp-arrow-left carousel-control-prev ', $output );
	$output = str_replace( 'class="mfp-arrow mfp-arrow-right ', 'class="mfp-arrow mfp-arrow-right carousel-control-next ', $output );

	$offset = strpos( $output, 'class="mfp-arrow mfp-arrow-left ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<span class="carousel-control-prev-icon mfp-prevent-close" aria-hidden="true"></span> <span class="screen-reader-text visually-hidden">%s</span>', __( 'Previous', 'enlightenment' ) ), $offset + 1, 0 );
	}

	$offset = strpos( $output, 'class="mfp-arrow mfp-arrow-right ' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, sprintf( '<span class="carousel-control-next-icon mfp-prevent-close" aria-hidden="true"></span> <span class="screen-reader-text visually-hidden">%s</span>', __( 'Next', 'enlightenment' ) ), $offset + 1, 0 );
	}

	$output = str_replace( 'class="rtm-ltb-action-container clearfix"', 'class="rtm-ltb-action-container d-flex align-items-center mt-3"', $output );
	$output = str_replace( "class='rtm-ltb-action-container clearfix'", 'class="rtm-ltb-action-container d-flex align-items-center mt-3"', $output );
	$output = str_replace( "class='rtm-ltb-title'", 'class="rtm-ltb-title ms-auto"', $output );
	$output = str_replace( 'class="rtmedia-actions rtmedia-author-actions rtm-item-actions"', 'class="rtmedia-actions rtmedia-author-actions rtm-item-actions order-first d-flex"', $output );

	$offset = strpos( $output, 'class="rtmedia-like rtmedia-action-buttons ' );
	while ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-heart" aria-hidden="true"></i> ', $offset + 1, 0 );

		$offset = strpos( $output, 'class="rtmedia-like rtmedia-action-buttons ', $offset );
	}

	$offset = strpos( $output, "class='rtmedia-comment-link rtmedia-comments-link'" );
	if ( false === $offset ) {
		$offset = strpos( $output, 'class="rtmedia-comment-link rtmedia-comments-link"' );
	}
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-comment" aria-hidden="true"></i> ', $offset + 1, 0 );
	}

	$offset = strpos( $output, 'class="rtmedia-view-conversation"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-eye" aria-hidden="true"></i> ', $offset + 1, 0 );
	}

	$offset = strpos( $output, 'class="rtmedia-edit rtmedia-action-buttons button"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-edit" aria-hidden="true"></i> ', $offset + 1, 0 );
	}

	$offset = strpos( $output, 'class="rtmedia-delete-media rtmedia-action-buttons button"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, '<i class="fas fa-trash-alt" aria-hidden="true"></i> ', $offset + 1, 0 );
	}

	$start = strpos( $output, '<div class="rtmedia-actions ' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<form ', $start );

		while ( false !== $offset && $offset < $end ) {
			$offset = strpos( $output, 'action="', $offset );
			$offset = strpos( $output, '"', $offset + 8 );
			if ( '/like/' === substr( $output, $offset - 6, 6 ) ) {
				$output = substr_replace( $output, ' class="order-first"', $offset + 1, 0 );
			}

			$end_a    = strpos( $output, '</form>', $offset );
			$offset_a = strpos( $output, 'type="submit"', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$end_b    = strpos( $output, '>', $offset_a );
				$offset_b = strpos( $output, 'class="', $offset_a );

				if ( false !== $offset_b && $offset_b < $end_b ) {
					$output = substr_replace( $output, 'btn btn-link ', $offset_b + 7, 0 );
				} else {
					$end_b    = strpos( $output, '>', $offset_a );
					$offset_b = strpos( $output, "class='", $offset_a );

					if ( false !== $offset_b && $offset_b < $end_b ) {
						$output = substr_replace( $output, 'btn btn-link ', $offset_b + 7, 0 );
					} else {
						$output = substr_replace( $output, ' class="btn btn-link"', $offset_a + 13, 0 );
					}
				}
			}

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<form ', $offset + 1 );
		}
	}

	$offset = strpos( $output, '<div class="username">' );
	if ( false !== $offset ) {
		$output = substr_replace( $output, '<div class="rtm-username-privacy-wrap flex-grow-1 flex-shrink-1">', $offset, 0 );
		$offset = strpos( $output, '<div class="rtm-time-privacy clearfix">', $offset );
		$offset = strpos( $output, '</div>', $offset );
		$output = substr_replace( $output, '</div>', $offset + 6, 0 );
	}

	$output = str_replace( 'class="rtm-user-meta-details"', 'class="rtm-user-meta-details d-flex align-items-center mb-3"', $output );
	$output = str_replace( 'class="userprofile rtm-user-avatar"', 'class="userprofile rtm-user-avatar flex-grow-0 flex-shrink-1 me-3"', $output );
	$output = str_replace( 'class="username"', 'class="username h5"', $output );
	$output = str_replace( 'class="rtm-time-privacy clearfix"', 'class="rtm-time-privacy text-body-secondary"', $output );
	$output = str_replace( 'class="dashicons dashicons-admin-site" title="', 'class="fas fa-globe rt-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = str_replace( 'class="dashicons dashicons-groups" title="', 'class="fas fa-users rt-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = str_replace( 'class="dashicons dashicons-networking" title="', 'class="fas fa-user-friends rt-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = str_replace( 'class="dashicons dashicons-lock" title="', 'class="fas fa-lock rt-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = str_replace( 'class="dashicons dashicons-dismiss" title="', 'class="fas fa-times-circle rt-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = str_replace( 'class="rtmedia-media-description rtm-more"', 'class="rtmedia-media-description rtm-more my-3"', $output );
	$output = str_replace( 'class="rtmedia-actions-before-comments clearfix"', 'class="rtmedia-actions-before-comments d-flex"', $output );

	$start = strpos( $output, '<div class="rtmedia-actions-before-comments ' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<form ', $start );
		while ( false !== $offset && $offset < $end ) {
			$end_a    = strpos( $output, '</form>', $offset );
			$offset_a = strpos( $output, 'type="submit"', $offset );

			if ( false !== $offset_a && $offset_a < $end_a ) {
				$end_b    = strpos( $output, '>', $offset_a );
				$offset_b = strpos( $output, 'class="', $offset_a );

				if ( false !== $offset_b && $offset_b < $end_b ) {
					$output = substr_replace( $output, 'btn btn-link ', $offset_b + 7, 0 );
				} else {
					$end_b    = strpos( $output, '>', $offset_a );
					$offset_b = strpos( $output, "class='", $offset_a );

					if ( false !== $offset_b && $offset_b < $end_b ) {
						$output = substr_replace( $output, 'btn btn-link ', $offset_b + 7, 0 );
					} else {
						$output = substr_replace( $output, ' class="btn btn-link"', $offset_a + 13, 0 );
					}
				}
			}

			$offset = strpos( $output, '<form ', $offset + 1 );
		}

		$end    = strpos( $output, '</div>', $start );
		$offset = strpos( $output, '<a ', $start );
		while ( false !== $offset && $offset < $end ) {
			$end_a    = strpos( $output, '>', $offset );
			$offset_a = strpos( $output, 'class="', $offset );

			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output = substr_replace( $output, 'btn btn-link ', $offset_a + 7, 0 );
			} else {
				$end_a    = strpos( $output, '>', $offset );
				$offset_a = strpos( $output, "class='", $offset );

				if ( false !== $offset_a && $offset_a < $end_a ) {
					$output = substr_replace( $output, 'btn btn-link ', $offset_a + 7, 0 );
				} else {
					$output = substr_replace( $output, ' class="btn btn-link"', $offset + 13, 0 );
				}
			}

			$offset = strpos( $output, '<form ', $offset + 1 );
		}
	}

	$output = str_replace( 'class="dashicons dashicons-thumbs-up"', 'class="fas fa-heart"', $output );
	$output = str_replace( 'class="rtm-comment-list"', 'class="rtm-comment-list list-unstyled mb-0"', $output );
	$output = str_replace( 'class="bp-suggestions ac-input ', 'class="bp-suggestions ac-input form-control w-100 ', $output );
	$output = str_replace( 'class="bp-suggestions ac-input"', 'class="bp-suggestions ac-input form-control w-100"', $output );
	$output = str_replace( 'class="rt_media_comment_submit"', 'class="rt_media_comment_submit btn btn-primary btn-sm ms-auto"', $output );
	$output = str_replace( 'class="rtmedia-container rtmedia-uploader-div"', 'class="rtmedia-container rtmedia-uploader-div w-100"', $output );
	$output = str_replace( 'class="rtmedia-comment-media-upload"', 'class="rtmedia-comment-media-upload btn btn-secondary btn-sm"', $output );

	$offset = strpos( $output, 'id="rt_media_comment_form"' );
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="d-flex flex-wrap">', $offset + 1, 0 );
		$offset = strpos( $output, '</form>', $offset );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	return $output;
}
add_filter( 'enlightenment_bp_filter_ajax_template_rtmedia_media/media-single', 'enlightenment_bp_bootstrap_ajax_template_rtmedia_media_media_single' );

function enlightenment_bp_bootstrap_rtmedia_single_comment( $output ) {
	// $output = str_replace( 'class="rtmedia-comment ', 'class="rtmedia-comment d-flex ', $output );
	// $output = str_replace( 'class="rtmedia-comment"', 'class="rtmedia-comment d-flex"', $output );
	$output = str_replace( 'class="rtmedia-comment-user-pic cleafix"', 'class="rtmedia-comment-user-pic flex-grow-0 flex-shrink-1 me-2"', $output );
	$output = str_replace( "class='rtmedia-comment-user-pic cleafix'", 'class="rtmedia-comment-user-pic flex-grow-0 flex-shrink-1 me-2"', $output );
	$output = str_replace( 'class="rtm-comment-wrap"', 'class="rtm-comment-wrap flex-grow-1 flex-shrink-1"', $output );
	$output = str_replace( "class='rtm-comment-wrap'", 'class="rtm-comment-wrap flex-grow-1 flex-shrink-1"', $output );
	$output = str_replace( '"rtmedia-comment-date"', '"rtmedia-comment-date text-body-secondary"', $output );
	$output = str_replace( '"rtmedia-delete-comment dashicons dashicons-no-alt" title="', '"rtmedia-delete-comment fas fa-times rt-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="', $output );
	$output = str_replace( 'class="rtmedia-comment-media-list ', 'class="rtmedia-comment-media-list list-unstyled mb-0 ', $output );

	$offset = strpos( $output, '<li class="rtmedia-comment ' );
	if ( false === $offset ) {
		$offset = strpos( $output, '<li class="rtmedia-comment"' );
	}
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="d-flex">', $offset + 1, 0 );
		$offset = strrpos( $output, '</li>' );
		$output = substr_replace( $output, '</div>' . "\n", $offset, 0 );
	}

	$offset = strpos( $output, '<li class="rtmedia-comment hide ' );
	if ( false === $offset ) {
		$offset = strpos( $output, '<li class="rtmedia-comment hide"' );
	}
	if ( false !== $offset ) {
		$output = substr_replace( $output, '', $offset + 26, 5 );
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, ' hidden', $offset, 0 );
	}

	return $output;
}
add_filter( 'rtmedia_single_comment', 'enlightenment_bp_bootstrap_rtmedia_single_comment' );

function enlightenment_bp_bootstrap_template_rtmedia_media_media_single_edit( $output ) {
	$start = strpos( $output, '<ul class="rtm-tabs ' );
	if ( false !== $start ) {
		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<li ', $start );
		while ( false !== $offset && $offset < $end ) {
			$offset_a = strpos( $output, 'class="', $offset );
			$end_a    = strpos( $output, '>', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output = substr_replace( $output, 'nav-item ', $offset_a + 7, 0 );
			} else {
				$output = substr_replace( $output, ' class="nav-item"', $offset + 3, 0 );
			}

			$offset = strpos( $output, '<a ', $offset );

			$offset_a = strpos( $output, 'class="', $offset );
			$end_a    = strpos( $output, '>', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output = substr_replace( $output, 'nav-link ', $offset_a + 7, 0 );
			} else {
				$offset_a = strpos( $output, "class='", $offset );
				$end_a    = strpos( $output, '>', $offset );
				if ( false !== $offset_a && $offset_a < $end_a ) {
					$output = substr_replace( $output, 'nav-link ', $offset_a + 7, 0 );
				} else {
					$output = substr_replace( $output, ' class="nav-link"', $offset + 3, 0 );
				}
			}

			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, ' data-bs-toggle="tab" role="tab"', $offset, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li ', $offset );
		}

		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<li>', $start );
		while ( false !== $offset && $offset < $end ) {
			$output = substr_replace( $output, ' class="nav-item"', $offset + 3, 0 );
			$offset = strpos( $output, '<a ', $offset );

			$offset_a = strpos( $output, 'class="', $offset );
			$end_a    = strpos( $output, '>', $offset );
			if ( false !== $offset_a && $offset_a < $end_a ) {
				$output = substr_replace( $output, 'nav-link ', $offset_a + 7, 0 );
			} else {
				$offset_a = strpos( $output, "class='", $offset );
				$end_a    = strpos( $output, '>', $offset );
				if ( false !== $offset_a && $offset_a < $end_a ) {
					$output = substr_replace( $output, 'nav-link ', $offset_a + 7, 0 );
				} else {
					$output = substr_replace( $output, ' class="nav-link"', $offset + 3, 0 );
				}
			}

			$offset = strpos( $output, '>', $offset );
			$output = substr_replace( $output, ' data-bs-toggle="tab" role="tab"', $offset, 0 );

			$end    = strpos( $output, '</div>', $start );
			$offset = strpos( $output, '<li>', $offset );
		}

		$end    = strpos( $output, '</ul>', $start );
		$offset = strpos( $output, '<li class="nav-item active">', $start );
		if ( false !== $offset && $offset < $end ) {
			$offset = strpos( $output, 'class="nav-link"', $offset );
			$output = substr_replace( $output, ' active', $offset + 15, 0 );
		}
	}

	$output = str_replace( 'class="rtm-tabs clearfix"', 'class="rtm-tabs nav nav-tabs mb-3" role="tablist"', $output );
	$output = str_replace( 'class="dashicons dashicons-edit"', 'class="far fa-edit me-1"', $output );
	$output = str_replace( "class='dashicons dashicons-edit'", 'class="far fa-edit me-1"', $output );
	$output = str_replace( 'class="dashicons dashicons-format-image"', 'class="far fa-image me-1"', $output );

	$output = str_replace( 'class="rtm-tabs-content"', 'class="rtm-tabs-content tab-content"', $output );
	$output = str_replace( 'class="content" id="panel1"', 'class="content tab-pane fade active show" id="panel1" role="tabpanel"', $output );
	$output = str_replace( 'class="content"', 'class="content tab-pane fade" role="tabpanel"', $output );

	$offset = strpos( $output, 'class="rtmedia-edit-title rtm-field-wrap"' );
    if ( false !== $offset ) {
		$offset = strpos( $output, '<label>', $offset );
        $output = substr_replace( $output, ' class="col-sm-2 col-form-label"', $offset + 6, 0 );
		$offset = strpos( $output, '<input ', $offset );
        $output = substr_replace( $output, '<div class="col-sm-10">' . "\n", $offset, 0 );
		$offset = strpos( $output, '<input ', $offset );
		$offset = strpos( $output, '>', $offset );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 1, 0 );
	}

	$output = str_replace( 'class="rtmedia-edit-title rtm-field-wrap"', 'class="rtmedia-edit-title rtm-field-wrap mb-3 row"', $output );
	$output = str_replace( 'class="rtmedia-title-editor"', 'class="rtmedia-title-editor form-control"', $output );

	$offset = strpos( $output, 'class="rtmedia-editor-description rtm-field-wrap"' );
    if ( false !== $offset ) {
		$offset = strpos( $output, '<label>', $offset );
        $output = substr_replace( $output, ' class="col-sm-2 col-form-label"', $offset + 6, 0 );
		$offset = strpos( $output, '<textarea ', $offset );
        $output = substr_replace( $output, '<div class="col-sm-10">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</textarea>', $offset );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 11, 0 );
	}

	$output = str_replace( 'class="rtmedia-editor-description rtm-field-wrap"', 'class="rtmedia-editor-description rtm-field-wrap mb-3 row"', $output );
	$output = str_replace( 'class="rtmedia-desc-textarea"', 'class="rtmedia-desc-textarea form-control"', $output );
	$output = str_replace( "class='rtmedia-desc-textarea'", 'class="rtmedia-desc-textarea form-control"', $output );

	$offset = strpos( $output, 'class="rtmedia-edit-privacy rtm-field-wrap"' );
	if ( false === $offset ) {
		$offset = strpos( $output, "class='rtmedia-edit-privacy rtm-field-wrap'" );
	}
    if ( false !== $offset ) {
		$offset = strpos( $output, '<select ', $offset );
        $output = substr_replace( $output, '<div class="col-sm-10">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</select>', $offset );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
	}

	$output = str_replace( 'class="rtmedia-edit-privacy rtm-field-wrap"', 'class="rtmedia-edit-privacy rtm-field-wrap mb-3 row"', $output );
	$output = str_replace( "class='rtmedia-edit-privacy rtm-field-wrap'", 'class="rtmedia-edit-privacy rtm-field-wrap mb-3 row"', $output );
	$output = str_replace( '<label for="privacy">', '<label for="privacy" class="col-sm-2 col-form-label">', $output );
	$output = str_replace( "<label for='privacy'>", '<label for="privacy" class="col-sm-2 col-form-label">', $output );
	$output = str_replace( '</label> : <div class="col-sm-10">', ' :</label> <div class="col-sm-10">', $output );
	$output = str_replace( 'class="rtm-form-select privacy"', 'class="rtm-form-select privacy form-select"', $output );

	$offset = strpos( $output, 'class="rtmedia-edit-change-album rtm-field-wrap"' );
    if ( false !== $offset ) {
		$offset = strpos( $output, '<label ', $offset );
        $output = substr_replace( $output, ' class="col-sm-2 col-form-label"', $offset + 6, 0 );
		$offset = strpos( $output, '<select ', $offset );
        $output = substr_replace( $output, '<div class="col-sm-10">' . "\n", $offset, 0 );
		$offset = strpos( $output, '</select>', $offset );
        $output = substr_replace( $output, "\n" . '</div>', $offset + 9, 0 );
	}

	$output = str_replace( 'class="rtmedia-edit-change-album rtm-field-wrap"', 'class="rtmedia-edit-change-album rtm-field-wrap mb-3 row"', $output );
	$output = str_replace( 'class="rtmedia-merge-user-album-list"', 'class="rtmedia-merge-user-album-list form-select"', $output );

	$output = str_replace( 'class="button rtmedia-image-edit"', 'class="button rtmedia-image-edit btn btn-secondary float-none"', $output );

	$output = str_replace( 'class="button rtm-button rtm-button-save"', 'class="button rtm-button rtm-button-save btn btn-primary btn-lg"', $output );
	$output = str_replace( 'class="button rtm-button rtm-button-back"', 'class="button rtm-button rtm-button-back btn btn-secondary btn-lg"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_template_rtmedia_media/media-single-edit', 'enlightenment_bp_bootstrap_template_rtmedia_media_media_single_edit' );

function enlightenment_bp_bootstrap_rtmedia_media_array_backbone( $object ) {
	$object->media_actions = str_replace( 'class="rtmedia-gallery-item-actions"', 'class="rtmedia-gallery-item-actions d-flex"', $object->media_actions );
	$object->media_actions = str_replace( "class='rtmedia-gallery-item-actions'", 'class="rtmedia-gallery-item-actions d-flex"', $object->media_actions );
	$object->media_actions = str_replace( 'class="dashicons dashicons-edit"', 'class="far fa-edit me-1"', $object->media_actions );
	$object->media_actions = str_replace( "class='dashicons dashicons-edit'", 'class="far fa-edit me-1"', $object->media_actions );
	$object->media_actions = str_replace( 'class="no-popup rtm-delete-media"', 'class="no-popup rtm-delete-media ms-auto"', $object->media_actions );
	$object->media_actions = str_replace( "class='no-popup rtm-delete-media'", 'class="no-popup rtm-delete-media ms-auto"', $object->media_actions );
	$object->media_actions = str_replace( 'class="dashicons dashicons-trash"', 'class="far fa-trash-alt me-1"', $object->media_actions );
	$object->media_actions = str_replace( "class='dashicons dashicons-trash'", 'class="far fa-trash-alt me-1"', $object->media_actions );

	return $object;
}
add_filter( 'rtmedia_media_array_backbone', 'enlightenment_bp_bootstrap_rtmedia_media_array_backbone', 12 );

function enlightenment_bp_bootstrap_rtmedia_upload_tabs( $tabs ) {
	if ( isset( $tabs['file_upload'] ) && isset( $tabs['file_upload']['default'] ) ) {
		$tab = $tabs['file_upload']['default']['content'];

		$tab = str_replace( '<div id="drag-drop-area" class="drag-drop clearfix">', '', $tab );

		$offset = strpos( $tab, "<div class='rtm-album-privacy'>" );
		if ( false === $offset ) {
			$offset = strpos( $tab, '<div class="rtm-album-privacy">' );
		}
		if ( false !== $offset ) {
			$offset = strpos( $tab, '</div>', $offset );
			$tab    = substr_replace( $tab, '<div id="drag-drop-area" class="drag-drop clearfix">', $offset + 6, 0 );
		}

		$start = strpos( $tab, "<div class='rtm-album-privacy'>" );
		if ( false === $start ) {
			$start = strpos( $tab, '<div class="rtm-album-privacy">' );
		}
		if ( false !== $start ) {
			$end    = strpos( $tab, '</div>', $start );
			$offset = strpos( $tab, '<span>', $start );

			while ( false !== $offset && $offset < $end ) {
				$tab    = substr_replace( $tab, '<dummy-div class="mb-3 row">', $offset, 6 );
				$offset = strpos( $tab, '<label', $offset );
				$tab    = substr_replace( $tab, ' class="col-sm-2 col-form-label"', $offset + 6, 0 );
				$offset = strpos( $tab, '</label>', $offset );
				$tab    = substr_replace( $tab, '<dummy-div class="col-sm-10">', $offset + 8, 0 );
				$offset = strpos( $tab, '</span>', $offset );
				$tab    = substr_replace( $tab, '</dummy-div></dummy-div>', $offset, 7 );

				$end    = strpos( $tab, '</div>', $start );
				$offset = strpos( $tab, '<span>', $offset );
			}

			$tab = str_replace( '<dummy-div ', '<div ', $tab );
			$tab = str_replace( '</dummy-div>', '</div>', $tab );
		}

		$tab = str_replace( 'class="dashicons dashicons-format-gallery"', 'class="fas fa-images fa-fw me-2"', $tab );
		$tab = str_replace( 'class="dashicons dashicons-visibility"', 'class="fas fa-eye fa-fw me-2"', $tab );
		$tab = str_replace( "class='dashicons dashicons-visibility'", 'class="fas fa-eye fa-fw me-2"', $tab );
		$tab = str_replace( 'class="rtmedia-user-album-list"', 'class="rtmedia-user-album-list form-control"', $tab );
		$tab = str_replace( 'class="rtm-form-select privacy"', 'class="rtm-form-select privacy form-control"', $tab );

		$offset = strpos( $tab, '<span class="drag-drop-info">' );
		if ( false !== $offset ) {
			$offset = strpos( $tab, '</span>', $offset );
			$tab    = substr_replace( $tab, '', $offset, 7 );
			$offset = strpos( $tab, '</i>', $offset );
			$tab    = substr_replace( $tab, '</span>', $offset + 4, 0 );
		}

		$offset = strpos( $tab, '<div class="rtmedia-upload-terms">' );
		if ( false !== $offset ) {
			$start  = $offset;
			$end    = strpos( $tab, '</div>', $offset ) + 6;
			$length = $end - $start;
			$check  = substr( $tab, $start, $length );
			$tab    = substr_replace( $tab, '', $start, $length );

			$offset = strpos( $tab, '<ul class="plupload_filelist_content ', $offset );
			$offset = strpos( $tab, '</ul>', $offset );
			$tab    = substr_replace( $tab, $check, $offset + 5, 0 );
		}

		$offset = strpos( $tab, '<input type="button" class="start-media-upload"' );
		if ( false !== $offset ) {
			$start  = $offset;
			$end    = strpos( $tab, '/>', $offset ) + 2;
			$length = $end - $start;
			$button = substr( $tab, $start, $length );
			$tab    = substr_replace( $tab, '', $start, $length );

			$offset = strpos( $tab, '<ul class="plupload_filelist_content ', $offset );
			$offset = strpos( $tab, '</ul>', $offset );
			$tab    = substr_replace( $tab, $button, $offset + 5, 0 );
		}

		$tab = str_replace( 'class="drag-drop clearfix"', 'class="drag-drop card bg-body-secondary"', $tab );
		$tab = str_replace( 'class="rtm-upload-tab-content"', 'class="rtm-upload-tab-content card-body text-center"', $tab );
		$tab = str_replace( 'class="rtm-select-files"', 'class="rtm-select-files d-flex flex-column align-items-center"', $tab );
		$tab = str_replace( 'class="rtmedia-upload-input rtmedia-file"', 'class="rtmedia-upload-input rtmedia-file btn btn-primary btn-lg mb-3"', $tab );
		$tab = str_replace( 'class="rtm-seperator"', 'class="rtm-seperator d-none"', $tab );
		$tab = str_replace( 'class="rtm-separator"', 'class="rtm-separator d-none"', $tab );
		$tab = str_replace( 'class="drag-drop-info"', 'class="drag-drop-info order-first my-3"', $tab );
		$tab = str_replace( 'class="rtm-file-size-limit dashicons dashicons-info"', 'class="rtm-file-size-limit fas fa-info-circle bp-tooltip" data-bs-toggle="tooltip" data-bs-placement="top"', $tab );

		// $tab = str_replace( 'class="clearfix"', 'class="mb-3"', $tab );
		$tab = str_replace( 'class="plupload_filelist_content ', 'class="plupload_filelist_content list-unstyled row ', $tab );
		$tab = str_replace( 'class="start-media-upload"', 'class="start-media-upload btn btn-primary"', $tab );

		$tab = str_replace( 'class="rtmedia-upload-terms"', 'class="rtmedia-upload-terms form-check mt-2"', $tab );
		$tab = str_replace( 'id="rtmedia_upload_terms_conditions"', 'id="rtmedia_upload_terms_conditions" class="form-check-input"', $tab );
		$tab = str_replace( 'for="rtmedia_upload_terms_conditions"', 'for="rtmedia_upload_terms_conditions" class="form-check-label"', $tab );

		$tabs['file_upload']['default']['content'] = $tab;
	}

	if ( isset( $tabs['file_upload'] ) && isset( $tabs['file_upload']['comment'] ) ) {
		$tab = $tabs['file_upload']['comment']['content'];

		$tab = str_replace( 'class="plupload_filelist_content ', 'class="plupload_filelist_content list-unstyled mb-0 ', $tab );

		$tabs['file_upload']['comment']['content'] = $tab;
	}

	return $tabs;
}
add_filter( 'rtmedia_upload_tabs', 'enlightenment_bp_bootstrap_rtmedia_upload_tabs' );

function enlightenment_bp_bootstrap_rtmedia_post_form_uploader( $output ) {
	if ( false === strpos( $output, 'class="rtmedia-container rtmedia-uploader-div"' ) ) {
		return $output;
	}

	$output = str_replace( 'class="rtmedia-container rtmedia-uploader-div"', 'class="rtmedia-container rtmedia-uploader-div d-none"', $output );
	$output = str_replace( 'id="rtmedia-action-update" class="clearfix"', 'id="rtmedia-action-update" class="d-flex"', $output );
	$output = str_replace( 'class="rtm-form-select privacy"', 'class="rtm-form-select privacy form-select ms-2"', $output );
	$output = str_replace( 'class="rtmedia-add-media-button"', 'class="rtmedia-add-media-button btn btn-secondary"', $output );
	$output = str_replace( 'class="plupload_filelist_content ui-sortable rtm-plupload-list clearfix"', 'class="plupload_filelist_content ui-sortable rtm-plupload-list list-unstyled row mb-0"', $output );

	$output = str_replace( 'class="rtmedia-upload-terms"', 'class="rtmedia-upload-terms form-check mt-2"', $output );
	$output = str_replace( 'id="rtmedia_upload_terms_conditions"', 'id="rtmedia_upload_terms_conditions" class="form-check-input"', $output );
	$output = str_replace( 'for="rtmedia_upload_terms_conditions"', 'for="rtmedia_upload_terms_conditions" class="form-check-label"', $output );

	return $output;
}
add_filter( 'enlightenment_bp_activity_post_form', 'enlightenment_bp_bootstrap_rtmedia_post_form_uploader' );
add_filter( 'enlightenment_bp_filter_member_activity_post_form', 'enlightenment_bp_bootstrap_rtmedia_post_form_uploader' );

function enlightenment_bp_bootstrap_rtmedia_activity_content_body( $output ) {
	return str_replace( 'class="rtmedia-list rtm-activity-media-list ', 'class="rtmedia-list rtm-activity-media-list list-unstyled ', $output );
}
add_filter( 'enlightenment_bp_filter_rtmedia_activity_content_body', 'enlightenment_bp_bootstrap_rtmedia_activity_content_body' );

function enlightenment_bp_bootstrap_rtmedia_comment_activity_content_body( $output ) {
	$output = str_replace( 'class="rtmedia-comment-media-list rtm-activity-media-list ', 'class="rtmedia-comment-media-list rtm-activity-media-list list-unstyled ', $output );
	$output = str_replace( 'class="rtmedia-list rtm-activity-media-list ', 'class="rtmedia-list rtm-activity-media-list list-unstyled ', $output );

	return $output;
}
add_filter( 'enlightenment_bp_filter_rtmedia_comment_activity_content_body', 'enlightenment_bp_bootstrap_rtmedia_comment_activity_content_body' );

function enlightenment_bp_bootstrap_rtmedia_activity_comment_media( $output ) {
	return str_replace( 'class="rtmedia-list rtm-activity-media-list ', 'class="rtmedia-list rtm-activity-media-list list-unstyled ', $output );
}
add_action( 'enlightenment_bp_filter_activity_comments_thread', 'enlightenment_bp_bootstrap_rtmedia_activity_comment_media', 10, 2 );
add_filter( 'enlightenment_bp_filter_template_activity/comment_output', 'enlightenment_bp_bootstrap_rtmedia_activity_comment_media' );

function enlightenment_bp_bootstrap_rtmedia_activity_comment_media_upload( $output ) {
	if ( false === strpos( $output, 'class="rtmedia-comment-media-upload"' ) ) {
		return $output;
	}

	$output = str_replace( 'class="ac-reply-content ', 'class="ac-reply-content d-flex flex-wrap ', $output );
	$output = str_replace( 'class="ac-reply-content"', 'class="ac-reply-content d-flex flex-wrap"', $output );
	$output = str_replace( 'class="ac-textarea ', 'class="ac-textarea w-100 ', $output );
	$output = str_replace( 'class="ac-textarea"', 'class="ac-textarea w-100"', $output );
	$output = str_replace( 'class="rtmedia-container rtmedia-uploader-div"', 'class="rtmedia-container rtmedia-uploader-div w-100"', $output );
	$output = str_replace( 'class="rtmedia-comment-media-upload"', 'class="rtmedia-comment-media-upload btn btn-secondary btn-sm"', $output );

	return $output;
}
add_action( 'enlightenment_bp_filter_activity_entry_comments', 'enlightenment_bp_bootstrap_rtmedia_activity_comment_media_upload' );

function enlightenment_bp_bootstrap_rtm_privacy_message_on_website( $output ) {
	$offset = strpos( $output, "class='privacy_message_wrapper'" );
	if ( false === $offset ) {
		$offset = strpos( $output, 'class="privacy_message_wrapper"' );
	}
	if ( false !== $offset ) {
		$start  = strpos( $output, "style='", $offset );
		$end    = strpos( $output, "'", $start + 7 ) + 1;
		$length = $end - $start;
		$output = substr_replace( $output, '', $start, $length );
	}

	$offset = strpos( $output, "class='privacy_message_wrapper'" );
	if ( false === $offset ) {
		$offset = strpos( $output, 'class="privacy_message_wrapper"' );
	}
	if ( false !== $offset ) {
		$offset = strpos( $output, '>', $offset );
		$output = substr_replace( $output, "\n" . '<div class="toast-body">' . sprintf( '<button type="button" id="close_rtm_privacy_message" class="ms-auto mb-1 btn-close float-end" data-bs-dismiss="toast" aria-label="Close"></button>', __( 'Close', 'enlightenment' ) ), $offset + 1, 0 ) . "\n";
		$offset = strpos( $output, '<p>', $offset );
		$output = substr_replace( $output, ' class="mb-0"', $offset + 2, 0 );
		$offset = strpos( $output, '</p>', $offset );
		$output = substr_replace( $output, "\n" . '</div>', $offset + 4, 0 );
	}

	$output = str_replace( 'class="privacy_message_wrapper"', 'class="privacy_message_wrapper toast fade m-4 show" role="alert" aria-live="assertive" aria-atomic="true"', $output );
	$output = str_replace( "class='privacy_message_wrapper'", 'class="privacy_message_wrapper toast fade m-4 show" role="alert" aria-live="assertive" aria-atomic="true"', $output );
	$output = str_replace( '<span class="dashicons dashicons-no" id="close_rtm_privacy_message"></span>', '', $output );
	$output = str_replace( "<span class='dashicons dashicons-no' id='close_rtm_privacy_message'></span>", '', $output );

	$output = sprintf( '<div class="rtm-privacy-message fixed-bottom">%s</div>', $output );

	return $output;
}
add_filter( 'enlightenment_rtm_privacy_message_on_website', 'enlightenment_bp_bootstrap_rtm_privacy_message_on_website' );

function enlightenment_bp_bootstrap_button( $button ) {
	$button['link_class'] .= ' btn btn-secondary';

	return $button;
}
//add_filter( 'bp_get_add_friend_button', 'enlightenment_bp_bootstrap_button' );
//add_filter( 'bp_follow_get_add_follow_button', 'enlightenment_bp_bootstrap_button' );

function enlightenment_bp_open_content_container() {
	echo enlightenment_open_tag( 'div', bp_is_activity_component() || bp_is_group_activity() ? 'col-lg-6' : 'col-lg-9' );
}

function enlightenment_bp_open_primary_sidebar_container() {
	echo enlightenment_open_tag( 'div', 'col-lg-3 stickit' );
}

function enlightenment_bp_open_secondary_sidebar_container() {
	echo enlightenment_open_tag( 'div', 'col-lg-3 stickit' );
}
