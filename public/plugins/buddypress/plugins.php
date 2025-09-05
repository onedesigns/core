<?php

function enlightenment_bp_rtmedia_template_hooks() {
	if ( ! function_exists( 'is_rtmedia_page' ) ) {
		return;
	}

	if ( is_buddypress() ) {
		return;
	}

	if ( ! is_rtmedia_page() ) {
		return;
	}

	remove_action( 'enlightenment_page_header', 'enlightenment_archive_title' );
	remove_action( 'enlightenment_page_header', 'enlightenment_archive_description' );
	remove_action( 'enlightenment_page_header', 'enlightenment_breadcrumbs' );
	remove_action( 'enlightenment_entry_header', 'enlightenment_entry_meta' );
	remove_action( 'enlightenment_after_entries_list', 'enlightenment_posts_nav' );
}
add_action( 'template_redirect', 'enlightenment_bp_rtmedia_template_hooks', 99 );

function enlightenment_rtm_body_class( $classes ) {
	if ( class_exists( 'RTMedia' ) ) {
		$classes[] = 'rtmedia-active';

		if ( is_rtmedia_page() && ! is_buddypress() ) {
			$classes[] = 'rtmedia-page';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'enlightenment_rtm_body_class' );

function enlightenment_bp_move_follow_button_first() {
	if( function_exists( 'bp_follow_add_profile_follow_button' ) ) {
		remove_action( 'bp_member_header_actions', 'bp_follow_add_profile_follow_button' );
		remove_action( 'bp_member_header_actions', 'bp_add_friend_button', 5 );

		add_action( 'bp_member_header_actions', 'bp_follow_add_profile_follow_button', 5 );
		add_action( 'bp_member_header_actions', 'bp_add_friend_button' );
	}

	if( function_exists( 'bp_follow_add_listing_follow_button' ) ) {
		remove_action( 'bp_directory_members_actions', 'bp_follow_add_listing_follow_button' );
		add_action( 'bp_directory_members_actions', 'bp_follow_add_listing_follow_button', 5 );
	}
}
add_action( 'init', 'enlightenment_bp_move_follow_button_first' );

function enlightenment_translate_bp_followers_text( $translated_text, $text, $domain ) {
	if( $domain != 'bp-follow' ) {
		return $translated_text;
	}

	$user_id = bp_is_user() ? bp_displayed_user_id() : bp_loggedin_user_id();
	$counts  = array( 'following' => 0, 'followers' => 0 );
	$counts  = bp_follow_total_follow_counts( array( 'user_id' => bp_displayed_user_id() ) );
//	$counts  = bp_follow_total_follow_counts( array( 'user_id' => $user_id ) );

	if( $text == 'Following <span>%d</span>' ) {
		if( 0 === $counts['following'] ) {
			$translated_text = __( 'Following <span class="no-count">%d</span>', 'enlightenment' );
		} else {
			$translated_text = __( 'Following <span class="count">%d</span>', 'enlightenment' );
		}
	} elseif( $text == 'Followers <span>%d</span>' ) {
		if( 0 === $counts['followers'] ) {
			$translated_text = __( 'Followers <span class="no-count">%d</span>', 'enlightenment' );
		} else {
			$translated_text = __( 'Followers <span class="count">%d</span>', 'enlightenment' );
		}
	}

	return $translated_text;
}
//add_filter( 'gettext', 'enlightenment_translate_bp_followers_text', 10, 3 );

function enlightenment_bp_register_rtmedia_update_action() {
	if( is_admin() ) {
		return;
	}

	bp_activity_set_action( 'rtmedia_update', 'rtmedia_update', __( 'Uploaded photos', 'enlightenment' ), false, __( 'Photo Uploads', 'enlightenment' ), array( 'member', 'group' ) );
}
add_action( 'bp_register_activity_actions', 'enlightenment_bp_register_rtmedia_update_action' );

function enlightenment_bp_filter_rtmedia_activity_action( $args ) {
	if( is_admin() && 'rtmedia_update' == $args['key'] ) {
		$args['value']   = __( 'Uploaded photos', 'enlightenment' );
		$args['label']   = __( 'Photo Uploads', 'enlightenment' );
		$args['context'] = array( 'activity', 'group' );
	}

	return $args;
}
add_filter( 'bp_activity_set_action', 'enlightenment_bp_filter_rtmedia_activity_action' );

add_filter( 'bp_get_options_nav_album', '__return_false' );

function enlightenment_bp_rtmedia_sub_nav( $args = null ) {
	global $rtmedia_query;

	if( ! isset( $rtmedia_query ) ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_rtmedia_sub_nav_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	rtmedia_sub_nav();
	do_action( 'rtmedia_sub_nav' );
	$output = ob_get_clean();

	$output = apply_filters( 'enlightenment_bp_rtmedia_sub_nav', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
add_action( 'bp_member_plugin_options_nav', 'enlightenment_bp_rtmedia_sub_nav' );

function enlightenment_bp_rtmedia_template_content( $args = null ) {
	global $rtmedia_query;

	if( ! isset( $rtmedia_query ) ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_bp_rtmedia_template_content_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	rtmedia_load_template();
	$output = ob_get_clean();

	$template_name = RTMediaTemplate::locate_template();
	if ( ! empty( $template_name ) ) {
		if ( 0 === strpos( $template_name, trailingslashit( get_stylesheet_directory() ) . 'rtmedia/' ) ) {
			$template_slug = str_replace( trailingslashit( get_stylesheet_directory() ) . 'rtmedia/', '', $template_name );

			if ( '.php' == substr( $template_slug, -4 ) ) {
		        $template_slug = substr( $template_slug, 0, -4 );
		    }
		} elseif ( 0 === strpos( $template_name, trailingslashit( get_template_directory() ) . 'rtmedia/' ) ) {
			$template_slug = str_replace( trailingslashit( get_template_directory() ) . 'rtmedia/', '', $template_name );

			if ( '.php' == substr( $template_slug, -4 ) ) {
		        $template_slug = substr( $template_slug, 0, -4 );
		    }
		} elseif ( 0 === strpos( $template_name, trailingslashit( RTMEDIA_PATH ) . 'templates/' ) ) {
			$template_slug = str_replace( trailingslashit( RTMEDIA_PATH ) . 'templates/', '', $template_name );

			if ( '.php' == substr( $template_slug, -4 ) ) {
		        $template_slug = substr( $template_slug, 0, -4 );
		    }
		}

		$template_slug = str_replace( '//', '/', $template_slug );

		$output = apply_filters( sprintf( 'enlightenment_bp_filter_template_rtmedia_%s', $template_slug ), $output, $args );
	}

	$output = apply_filters( 'enlightenment_bp_rtmedia_template_content', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
add_action( 'bp_template_content', 'enlightenment_bp_rtmedia_template_content' );

function enlightenment_bp_rtmedia_maybe_ob_start() {
	global $rt_ajax_request;

	if ( ! isset( $rt_ajax_request ) ) {
		return;
	}

	if ( true !== $rt_ajax_request ) {
		return;
	}

	ob_start();
}
add_action( 'rtmedia_before_template_load', 'enlightenment_bp_rtmedia_maybe_ob_start' );

function enlightenment_bp_rtmedia_filter_ajax_template() {
	global $rt_ajax_request;

	if ( ! isset( $rt_ajax_request ) ) {
		return;
	}

	if ( true !== $rt_ajax_request ) {
		return;
	}

	$output = ob_get_clean();

	if ( false !== strpos( $output, 'class="rtmedia-container rtmedia-single-container"' ) ) {
		$output = apply_filters( 'enlightenment_bp_filter_ajax_template_rtmedia_media/media-single', $output );
	}

	$output = apply_filters( 'enlightenment_bp_rtmedia_ajax_template_content', $output );

	echo $output;
}
add_action( 'rtmedia_after_template_load', 'enlightenment_bp_rtmedia_filter_ajax_template' );

function enlightenment_change_rtmedia_activity_username_single_media( $action, $username, $count, $user_nicename, $media_type ) {
    $user_id  = get_current_user_id();
    $user     = get_userdata( $user_id );
    $username = '<a href="' . get_rtmedia_user_link ( $user_id ) . '">' . $user->display_name . '</a>';
    $media_type_label = $count > 1 ? constant( 'RTMEDIA_' . strtoupper( $media_type ) .  '_PLURAL_LABEL' ) : constant( 'RTMEDIA_' . strtoupper( $media_type ) .  '_LABEL' );
    $action   = sprintf( __( '%s added %d %s', 'buddypress-media' ), $username, $count, $media_type_label );
    return $action;
}
add_filter('rtmedia_buddypress_action_text_fitler', 'enlightenment_change_rtmedia_activity_username_single_media', 10, 5);

function enlightenment_change_rtmedia_activity_username_multiple_media( $action, $username, $count, $user_nicename ) {
    $user_id  = get_current_user_id();
    $user     = get_userdata( $user_id );
    $username = '<a href="' . get_rtmedia_user_link ( $user_id ) . '">' . $user->display_name . '</a>';
    $action   = sprintf( __( '%s added %d %s', 'buddypress-media' ), $username, $count, RTMEDIA_MEDIA_SLUG );
    return $action;
}
add_filter('rtmedia_buddypress_action_text_fitler_multiple_media', 'enlightenment_change_rtmedia_activity_username_multiple_media', 10, 4);

function enlightenment_rtm_privacy_message_on_website( $args = null ) {
	if ( ! function_exists( 'rtm_privacy_message_on_website' ) ) {
		return;
	}

	$defaults = array(
		'echo' => true,
	);

	$defaults = apply_filters( 'enlightenment_rtm_privacy_message_on_website_args', $defaults );
	$args = wp_parse_args( $args, $defaults );

	ob_start();
	rtm_privacy_message_on_website();
	$output = ob_get_clean();

	if ( empty( $output ) ) {
		return;
	}

	$output = apply_filters( 'enlightenment_rtm_privacy_message_on_website', $output, $args );

	if( ! $args['echo'] ) {
		return $output;
	}

	echo $output;
}
